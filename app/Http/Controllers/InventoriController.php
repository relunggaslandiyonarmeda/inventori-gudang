<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class InventoriController extends Controller
{
    // ========== MIDDLEWARE CHECK ==========
    private function checkAuth()
    {
        if (!Session::get('admin_logged_in')) {
            return redirect()->route('login');
        }
        return null;
    }

    // ========== DASHBOARD ==========
    public function dashboard()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $totalBarang = MasterBarang::count();
        $totalStok = MasterBarang::sum('stok');
        $barangMasukHariIni = BarangMasuk::whereDate('tanggal', Carbon::today())->sum('jumlah_masuk');
        $barangKeluarHariIni = BarangKeluar::whereDate('tanggal', Carbon::today())->sum('jumlah_keluar');

        return view('dashboard', compact('totalBarang', 'totalStok', 'barangMasukHariIni', 'barangKeluarHariIni'));
    }

    // ========== MASTER BARANG ==========
    public function masterBarang()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangs = MasterBarang::orderBy('created_at', 'desc')->get();
        return view('master_barang.index', compact('barangs'));
    }

    public function masterBarangStore(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'barcode' => 'required|string|max:100|unique:master_barang,barcode',
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'required|in:A,B,C,D,E,F,G,H,O',
        ]);

        MasterBarang::create($request->all());

        return redirect()->route('master.barang')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function masterBarangUpdate(Request $request, $barcode)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'required|in:A,B,C,D,E,F,G,H,O',
        ]);

        $barang = MasterBarang::findOrFail($barcode);
        $barang->update($request->all());

        return redirect()->route('master.barang')->with('success', 'Barang berhasil diupdate!');
    }

    public function masterBarangDestroy($barcode)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barang = MasterBarang::findOrFail($barcode);
        $barang->delete();

        return redirect()->route('master.barang')->with('success', 'Barang berhasil dihapus!');
    }

    // ========== BARANG MASUK ==========
    public function barangMasuk()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangs = MasterBarang::orderBy('nama_barang', 'asc')->get();
        return view('barang_masuk.index', compact('barangs'));
    }

    public function barangMasukStore(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'barcode' => 'required|string|exists:master_barang,barcode',
            'jumlah_masuk' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Update stok di master_barang
        $barang = MasterBarang::findOrFail($request->barcode);
        $barang->stok = $barang->stok + $request->jumlah_masuk;
        $barang->save();

        // Simpan ke barang_masuk
        BarangMasuk::create($request->all());

        return redirect()->route('barang.masuk')->with('success', 'Barang masuk berhasil dicatat!');
    }

    public function barangMasukManual(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'barcode_manual' => 'required|string|exists:master_barang,barcode',
            'jumlah_masuk' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Update stok di master_barang
        $barang = MasterBarang::findOrFail($request->barcode_manual);
        $barang->stok = $barang->stok + $request->jumlah_masuk;
        $barang->save();

        // Simpan ke barang_masuk
        BarangMasuk::create([
            'barcode' => $request->barcode_manual,
            'jumlah_masuk' => $request->jumlah_masuk,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('barang.masuk')->with('success', 'Barang masuk berhasil dicatat!');
    }

    // ========== BARANG KELUAR ==========
    public function barangKeluar()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangs = MasterBarang::orderBy('nama_barang', 'asc')->get();
        return view('barang_keluar.index', compact('barangs'));
    }

    public function barangKeluarStore(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'barcode' => 'required|string|exists:master_barang,barcode',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Cek stok cukup
        $barang = MasterBarang::findOrFail($request->barcode);
        if ($barang->stok < $request->jumlah_keluar) {
            return back()->with('error', 'Stok tidak cukup! Stok tersedia: ' . $barang->stok);
        }

        // Update stok di master_barang
        $barang->stok = $barang->stok - $request->jumlah_keluar;
        $barang->save();

        // Simpan ke barang_keluar
        BarangKeluar::create($request->all());

        return redirect()->route('barang.keluar')->with('success', 'Barang keluar berhasil dicatat!');
    }

    public function barangKeluarManual(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'barcode_manual' => 'required|string|exists:master_barang,barcode',
            'jumlah_keluar' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Cek stok cukup
        $barang = MasterBarang::findOrFail($request->barcode_manual);
        if ($barang->stok < $request->jumlah_keluar) {
            return back()->with('error', 'Stok tidak cukup! Stok tersedia: ' . $barang->stok);
        }

        // Update stok di master_barang
        $barang->stok = $barang->stok - $request->jumlah_keluar;
        $barang->save();

        // Simpan ke barang_keluar
        BarangKeluar::create([
            'barcode' => $request->barcode_manual,
            'jumlah_keluar' => $request->jumlah_keluar,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('barang.keluar')->with('success', 'Barang keluar berhasil dicatat!');
    }

    // ========== LAPORAN ==========
    public function laporan()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        return view('laporan.index');
    }

    // Laporan Barang Masuk
    public function laporanMasuk(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalMasuk = $barangMasuks->sum('jumlah_masuk');

        return view('laporan.masuk', compact('barangMasuks', 'bulan', 'tahun', 'totalMasuk'));
    }

    public function laporanMasukPdf(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalMasuk = $barangMasuks->sum('jumlah_masuk');

        $pdf = Pdf::loadView('laporan.pdf.masuk', compact('barangMasuks', 'bulan', 'tahun', 'totalMasuk'));
        return $pdf->download('laporan_barang_masuk_' . $bulan . '_' . $tahun . '.pdf');
    }

    public function laporanMasukExcel(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        return Excel::download(new LaporanExport('masuk', $bulan, $tahun), 'laporan_barang_masuk_' . $bulan . '_' . $tahun . '.xlsx');
    }

    public function laporanMasukCsv(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $filename = 'laporan_barang_masuk_' . $bulan . '_' . $tahun . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['No', 'Tanggal', 'Barcode', 'Nama Barang', 'Jumlah', 'Keterangan']);
        
        $no = 1;
        foreach ($barangMasuks as $item) {
            fputcsv($output, [
                $no++,
                $item->tanggal->format('d-m-Y'),
                $item->barcode,
                $item->masterBarang->nama_barang ?? '-',
                $item->jumlah_masuk,
                $item->keterangan ?? '-'
            ]);
        }
        
        fputcsv($output, ['', '', '', 'TOTAL', $barangMasuks->sum('jumlah_masuk'), '']);
        
        fclose($output);
        exit;
    }

    // Laporan Barang Keluar
    public function laporanKeluar(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangKeluars = BarangKeluar::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalKeluar = $barangKeluars->sum('jumlah_keluar');

        return view('laporan.keluar', compact('barangKeluars', 'bulan', 'tahun', 'totalKeluar'));
    }

    public function laporanKeluarPdf(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangKeluars = BarangKeluar::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalKeluar = $barangKeluars->sum('jumlah_keluar');

        $pdf = Pdf::loadView('laporan.pdf.keluar', compact('barangKeluars', 'bulan', 'tahun', 'totalKeluar'));
        return $pdf->download('laporan_barang_keluar_' . $bulan . '_' . $tahun . '.pdf');
    }

    public function laporanKeluarExcel(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        return Excel::download(new LaporanExport('keluar', $bulan, $tahun), 'laporan_barang_keluar_' . $bulan . '_' . $tahun . '.xlsx');
    }

    public function laporanKeluarCsv(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangKeluars = BarangKeluar::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $filename = 'laporan_barang_keluar_' . $bulan . '_' . $tahun . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['No', 'Tanggal', 'Barcode', 'Nama Barang', 'Jumlah', 'Keterangan']);
        
        $no = 1;
        foreach ($barangKeluars as $item) {
            fputcsv($output, [
                $no++,
                $item->tanggal->format('d-m-Y'),
                $item->barcode,
                $item->masterBarang->nama_barang ?? '-',
                $item->jumlah_keluar,
                $item->keterangan ?? '-'
            ]);
        }
        
        fputcsv($output, ['', '', '', 'TOTAL', $barangKeluars->sum('jumlah_keluar'), '']);
        
        fclose($output);
        exit;
    }

    // Laporan Gabungan
    public function laporanGabungan(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $barangKeluars = BarangKeluar::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalMasuk = $barangMasuks->sum('jumlah_masuk');
        $totalKeluar = $barangKeluars->sum('jumlah_keluar');

        return view('laporan.gabungan', compact('barangMasuks', 'barangKeluars', 'bulan', 'tahun', 'totalMasuk', 'totalKeluar'));
    }

    public function laporanGabunganPdf(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $barangKeluars = BarangKeluar::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalMasuk = $barangMasuks->sum('jumlah_masuk');
        $totalKeluar = $barangKeluars->sum('jumlah_keluar');

        $pdf = Pdf::loadView('laporan.pdf.gabungan', compact('barangMasuks', 'barangKeluars', 'bulan', 'tahun', 'totalMasuk', 'totalKeluar'));
        return $pdf->download('laporan_transaksi_' . $bulan . '_' . $tahun . '.pdf');
    }

    public function laporanGabunganExcel(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        return Excel::download(new LaporanExport('gabungan', $bulan, $tahun), 'laporan_transaksi_' . $bulan . '_' . $tahun . '.xlsx');
    }

    public function laporanGabunganCsv(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $barangKeluars = BarangKeluar::with('masterBarang')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $filename = 'laporan_transaksi_' . $bulan . '_' . $tahun . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['No', 'Tanggal', 'Jenis', 'Barcode', 'Nama Barang', 'Jumlah', 'Keterangan']);
        
        $no = 1;
        foreach ($barangMasuks as $item) {
            fputcsv($output, [
                $no++,
                $item->tanggal->format('d-m-Y'),
                'MASUK',
                $item->barcode,
                $item->masterBarang->nama_barang ?? '-',
                $item->jumlah_masuk,
                $item->keterangan ?? '-'
            ]);
        }
        
        foreach ($barangKeluars as $item) {
            fputcsv($output, [
                $no++,
                $item->tanggal->format('d-m-Y'),
                'KELUAR',
                $item->barcode,
                $item->masterBarang->nama_barang ?? '-',
                $item->jumlah_keluar,
                $item->keterangan ?? '-'
            ]);
        }
        
        fclose($output);
        exit;
    }

    // ========== LAPORAN BARANG PER RAK ==========
    public function laporanRak(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $rak = $request->rak ?? 'all';
        
        // Get all rak options
        $rakOptions = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'O'];
        
        // Query barang berdasarkan rak
        if ($rak === 'all') {
            $barangs = MasterBarang::orderBy('lokasi_rak', 'asc')
                ->orderBy('nama_barang', 'asc')
                ->get()
                ->groupBy('lokasi_rak');
        } else {
            $barangs = MasterBarang::where('lokasi_rak', $rak)
                ->orderBy('nama_barang', 'asc')
                ->get()
                ->groupBy('lokasi_rak');
        }
        
        // Calculate totals
        $totalBarang = 0;
        $totalStok = 0;
        foreach ($barangs as $rakGroup => $items) {
            $totalBarang += $items->count();
            $totalStok += $items->sum('stok');
        }

        return view('laporan.rak', compact('barangs', 'rak', 'rakOptions', 'totalBarang', 'totalStok'));
    }

    public function laporanRakPdf(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $rak = $request->rak ?? 'all';
        
        if ($rak === 'all') {
            $barangs = MasterBarang::orderBy('lokasi_rak', 'asc')
                ->orderBy('nama_barang', 'asc')
                ->get()
                ->groupBy('lokasi_rak');
        } else {
            $barangs = MasterBarang::where('lokasi_rak', $rak)
                ->orderBy('nama_barang', 'asc')
                ->get()
                ->groupBy('lokasi_rak');
        }
        
        $totalBarang = 0;
        $totalStok = 0;
        foreach ($barangs as $rakGroup => $items) {
            $totalBarang += $items->count();
            $totalStok += $items->sum('stok');
        }

        $pdf = Pdf::loadView('laporan.pdf.rak', compact('barangs', 'rak', 'totalBarang', 'totalStok'));
        return $pdf->download('laporan_barang_per_rak' . ($rak !== 'all' ? '_rak_' . $rak : '') . '.pdf');
    }

    public function laporanRakExcel(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $rak = $request->rak ?? 'all';
        
        return Excel::download(new LaporanExport('rak', $rak), 'laporan_barang_per_rak' . ($rak !== 'all' ? '_rak_' . $rak : '') . '.xlsx');
    }

    public function laporanRakCsv(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $rak = $request->rak ?? 'all';
        
        if ($rak === 'all') {
            $barangs = MasterBarang::orderBy('lokasi_rak', 'asc')
                ->orderBy('nama_barang', 'asc')
                ->get()
                ->groupBy('lokasi_rak');
        } else {
            $barangs = MasterBarang::where('lokasi_rak', $rak)
                ->orderBy('nama_barang', 'asc')
                ->get()
                ->groupBy('lokasi_rak');
        }

        $filename = 'laporan_barang_per_rak' . ($rak !== 'all' ? '_rak_' . $rak : '') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        fputcsv($output, ['No', 'Rak', 'Barcode', 'Nama Barang', 'Stok']);
        
        $no = 1;
        foreach ($barangs as $rakGroup => $items) {
            foreach ($items as $item) {
                fputcsv($output, [
                    $no++,
                    'Rak ' . $item->lokasi_rak,
                    $item->barcode,
                    $item->nama_barang,
                    $item->stok
                ]);
            }
        }
        
        $totalStok = 0;
        foreach ($barangs as $items) {
            $totalStok += $items->sum('stok');
        }
        fputcsv($output, ['', '', 'TOTAL BARANG', $no - 1, $totalStok]);
        
        fclose($output);
        exit;
    }

    // ========== SEARCH API ==========
    public function searchBarang(Request $request)
    {
        $query = $request->q ?? '';
        $barangs = MasterBarang::where('nama_barang', 'like', '%' . $query . '%')
            ->orWhere('barcode', 'like', '%' . $query . '%')
            ->limit(10)
            ->get();
        
        return response()->json($barangs);
    }
}
