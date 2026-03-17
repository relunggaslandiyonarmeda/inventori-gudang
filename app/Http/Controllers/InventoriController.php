<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangRusak;
use App\Models\MasterVehicleGroup;
use App\Models\MasterLokasiUnit;
use App\Models\BarangRetur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
    public function masterBarang(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $search = $request->input('search');
        $rak = $request->input('rak');
        
        $barangs = MasterBarang::when($search, function($query) use ($search) {
                $query->where('barcode', 'like', '%' . $search . '%')
                      ->orWhere('nama_barang', 'like', '%' . $search . '%')
                      ->orWhere('lokasi_rak', 'like', '%' . $search . '%');
            })
            ->when($rak, function($query) use ($rak) {
                $query->where('lokasi_rak', $rak);
            })
            ->orderBy('lokasi_rak', 'asc')
            ->orderBy('nama_barang', 'asc')
            ->paginate(10);
        
        // Get available racks for filter
        $raks = MasterBarang::select('lokasi_rak')->distinct()->orderBy('lokasi_rak', 'asc')->pluck('lokasi_rak');
            
        return view('master_barang.index', compact('barangs', 'search', 'rak', 'raks'));
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

        $barang = MasterBarang::create($request->all());
        
        Log::info('Barang ditambahkan', [
            'barcode' => $barang->barcode,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
            'lokasi_rak' => $barang->lokasi_rak,
            'user' => Session::get('admin_username')
        ]);

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
        
        // Prevent changing barcode to an existing one
        if ($request->has('barcode') && $request->barcode !== $barcode) {
            $exists = MasterBarang::where('barcode', $request->barcode)->exists();
            if ($exists) {
                return back()->with('error', 'Barcode sudah digunakan oleh barang lain!');
            }
        }
        
        $barang->update($request->all());

        Log::info('Barang diupdate', [
            'barcode' => $barang->barcode,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
            'lokasi_rak' => $barang->lokasi_rak,
            'user' => Session::get('admin_username')
        ]);

        return redirect()->route('master.barang')->with('success', 'Barang berhasil diupdate!');
    }

    public function masterBarangDestroy($barcode)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barang = MasterBarang::findOrFail($barcode);
        $barang->delete();

        Log::warning('Barang dihapus', [
            'barcode' => $barcode,
            'nama_barang' => $barang->nama_barang,
            'user' => Session::get('admin_username')
        ]);

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

        try {
            DB::transaction(function () use ($request) {
                // Update stok di master_barang
                $barang = MasterBarang::findOrFail($request->barcode);
                $barang->stok = $barang->stok + $request->jumlah_masuk;
                $barang->save();

                // Simpan ke barang_masuk
                BarangMasuk::create($request->all());
                
                Log::info('Barang masuk dicatat', [
                    'barcode' => $request->barcode,
                    'nama_barang' => $barang->nama_barang,
                    'jumlah_masuk' => $request->jumlah_masuk,
                    'tanggal' => $request->tanggal,
                    'user' => Session::get('admin_username')
                ]);
            });

            return redirect()->route('barang.masuk')->with('success', 'Barang masuk berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

        try {
            DB::transaction(function () use ($request) {
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
            });

            return redirect()->route('barang.masuk')->with('success', 'Barang masuk berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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

        try {
            DB::transaction(function () use ($request) {
                // Cek stok cukup
                $barang = MasterBarang::findOrFail($request->barcode);
                if ($barang->stok < $request->jumlah_keluar) {
                    throw new \Exception('Stok tidak cukup! Stok tersedia: ' . $barang->stok);
                }

                // Update stok di master_barang
                $barang->stok = $barang->stok - $request->jumlah_keluar;
                $barang->save();

                // Simpan ke barang_keluar
                BarangKeluar::create($request->all());
                
                Log::info('Barang keluar dicatat', [
                    'barcode' => $request->barcode,
                    'nama_barang' => $barang->nama_barang,
                    'jumlah_keluar' => $request->jumlah_keluar,
                    'tanggal' => $request->tanggal,
                    'user' => Session::get('admin_username')
                ]);
            });

            return redirect()->route('barang.keluar')->with('success', 'Barang keluar berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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

        try {
            DB::transaction(function () use ($request) {
                // Cek stok cukup
                $barang = MasterBarang::findOrFail($request->barcode_manual);
                if ($barang->stok < $request->jumlah_keluar) {
                    throw new \Exception('Stok tidak cukup! Stok tersedia: ' . $barang->stok);
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
            });

            return redirect()->route('barang.keluar')->with('success', 'Barang keluar berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ========== BARANG RETUR ==========
    public function barangRetur(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $search = $request->search ?? '';
        
        // Get barang_keluar that haven't been fully retured
        $barangKeluar = BarangKeluar::with('masterBarang')
            ->where(function($query) use ($search) {
                if ($search) {
                    $query->where('barcode', 'like', "%$search%")
                          ->orWhereHas('masterBarang', function($q) use ($search) {
                              $q->where('nama_barang', 'like', "%$search%");
                          });
                }
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        // Get all retur records
        $retur = BarangRetur::with(['barangKeluar.masterBarang', 'masterBarang'])
            ->orderBy('tanggal_retur', 'desc')
            ->get();

        // Calculate remaining quantity for each barang_keluar
        $remainingQty = [];
        foreach ($barangKeluar as $bk) {
            $totalRetur = BarangRetur::where('barang_keluar_id', $bk->id)->sum('jumlah_retur');
            $remainingQty[$bk->id] = $bk->jumlah_keluar - $totalRetur;
        }

        return view('barang_retur.index', compact('barangKeluar', 'retur', 'remainingQty', 'search'));
    }

    public function barangReturStore(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'barang_keluar_id' => 'required|exists:barang_keluar,id',
            'jumlah_retur' => 'required|integer|min:1',
            'tanggal_retur' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Get barang_keluar record
                $barangKeluar = BarangKeluar::findOrFail($request->barang_keluar_id);
                
                // Calculate how many have already been retured
                $totalRetur = BarangRetur::where('barang_keluar_id', $request->barang_keluar_id)
                    ->sum('jumlah_retur');
                
                $remainingQty = $barangKeluar->jumlah_keluar - $totalRetur;
                
                // Validate retur quantity
                if ($request->jumlah_retur > $remainingQty) {
                    throw new \Exception('Jumlah retur tidak boleh melebihi sisa yang belum diretur! Sisa: ' . $remainingQty);
                }

                // Save retur record
                BarangRetur::create([
                    'barang_keluar_id' => $request->barang_keluar_id,
                    'barcode' => $barangKeluar->barcode,
                    'jumlah_retur' => $request->jumlah_retur,
                    'tanggal_retur' => $request->tanggal_retur,
                    'keterangan' => $request->keterangan,
                ]);

                // Update stok di master_barang (tambah stok)
                $barang = MasterBarang::findOrFail($barangKeluar->barcode);
                $barang->stok = $barang->stok + $request->jumlah_retur;
                $barang->save();
            });

            return redirect()->route('barang.retur')->with('success', 'Barang retur berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function barangReturDestroy($id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        try {
            DB::transaction(function () use ($id) {
                $retur = BarangRetur::findOrFail($id);
                
                // Kurangi stok di master_barang
                $barang = MasterBarang::findOrFail($retur->barcode);
                $barang->stok = $barang->stok - $retur->jumlah_retur;
                $barang->save();
                
                // Delete retur record
                $retur->delete();
            });

            return redirect()->route('barang.retur')->with('success', 'Retur berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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
            ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
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
            ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
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
            ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
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
            ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
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
            ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
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
            ->whereRaw('(SELECT COALESCE(SUM(jumlah_retur), 0) FROM barang_retur WHERE barang_retur.barang_keluar_id = barang_keluar.id) < barang_keluar.jumlah_keluar')
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
        
        return Excel::download(new LaporanExport('rak', $rak, date('Y')), 'laporan_barang_per_rak' . ($rak !== 'all' ? '_rak_' . $rak : '') . '.xlsx');
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

    // ========== BARANG RUSAK ==========
    public function barangRusak()
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangRusaks = BarangRusak::orderBy('id', 'desc')->paginate(10);
        $vehicleGroups = MasterVehicleGroup::orderBy('kode')->get();
        $lokasiUnits = MasterLokasiUnit::orderBy('lokasi')->get();
        $masterBarangs = MasterBarang::orderBy('nama_barang')->get();
        
        return view('barang_rusak.index', compact('barangRusaks', 'vehicleGroups', 'lokasiUnits', 'masterBarangs'));
    }

    public function barangRusakStore(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'vehicle_group_code' => 'required|string|max:100',
            'description' => 'nullable|string',
            'tahun_perolehan' => 'required|integer|min:1900',
            'merek' => 'required|string|max:100',
            'lokasi_unit' => 'required|string|max:100',
            'kondisi_unit' => 'required|in:hidup,mati',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Generate nomor (BR-001, BR-002, etc.)
        $lastBarang = BarangRusak::orderBy('id', 'desc')->first();
        $nextNumber = $lastBarang ? (int)substr($lastBarang->nomor, 3) + 1 : 1;
        $nomor = 'BR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        // Handle foto upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = $nomor . '_' . time() . '.' . $foto->getClientOriginalExtension();
            
            // Ensure directory exists
            $directory = storage_path('app/public/foto_barang_rusak');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            
            // Copy file to storage
            copy($foto->getPathname(), $directory . '/' . $fotoName);
            $fotoPath = 'foto_barang_rusak/' . $fotoName;
        }

        // Save to barang_rusak
        $barangRusak = BarangRusak::create([
            'nomor' => $nomor,
            'vehicle_group_code' => $request->vehicle_group_code,
            'description' => $request->description,
            'tahun_perolehan' => $request->tahun_perolehan,
            'merek' => $request->merek,
            'foto' => $fotoPath,
            'lokasi_unit' => $request->lokasi_unit,
            'kondisi_unit' => $request->kondisi_unit,
            'keterangan' => $request->keterangan,
        ]);

        // Save vehicle_group_code to master if not exists
        MasterVehicleGroup::firstOrCreate(['kode' => $request->vehicle_group_code]);

        // Save lokasi_unit to master if not exists
        MasterLokasiUnit::firstOrCreate(['lokasi' => $request->lokasi_unit]);

        Log::info('Barang Rusak ditambahkan', [
            'nomor' => $barangRusak->nomor,
            'vehicle_group_code' => $barangRusak->vehicle_group_code,
            'merek' => $barangRusak->merek,
            'user' => Session::get('admin_username')
        ]);

        return redirect()->route('barang.rusak')->with('success', 'Barang Rusak berhasil ditambahkan!');
    }

    public function barangRusakUpdate(Request $request, $id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'vehicle_group_code' => 'required|string|max:100',
            'description' => 'nullable|string',
            'tahun_perolehan' => 'required|integer|min:1900',
            'merek' => 'required|string|max:100',
            'lokasi_unit' => 'required|string|max:100',
            'kondisi_unit' => 'required|in:hidup,mati',
            'keterangan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $barangRusak = BarangRusak::findOrFail($id);

        // Handle foto upload
        $fotoPath = $barangRusak->foto;
        if ($request->hasFile('foto')) {
            // Delete old foto
            if ($barangRusak->foto) {
                $oldFilePath = storage_path('app/public/' . $barangRusak->foto);
                if (file_exists($oldFilePath)) {
                    @unlink($oldFilePath);
                }
            }
            
            $foto = $request->file('foto');
            $fotoName = $barangRusak->nomor . '_' . time() . '.' . $foto->getClientOriginalExtension();
            
            // Ensure directory exists
            $directory = storage_path('app/public/foto_barang_rusak');
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
            
            // Copy file to storage
            copy($foto->getPathname(), $directory . '/' . $fotoName);
            $fotoPath = 'foto_barang_rusak/' . $fotoName;
        }

        $barangRusak->update([
            'vehicle_group_code' => $request->vehicle_group_code,
            'description' => $request->description,
            'tahun_perolehan' => $request->tahun_perolehan,
            'merek' => $request->merek,
            'foto' => $fotoPath,
            'lokasi_unit' => $request->lokasi_unit,
            'kondisi_unit' => $request->kondisi_unit,
            'keterangan' => $request->keterangan,
        ]);

        // Save vehicle_group_code to master if not exists
        MasterVehicleGroup::firstOrCreate(['kode' => $request->vehicle_group_code]);

        // Save lokasi_unit to master if not exists
        MasterLokasiUnit::firstOrCreate(['lokasi' => $request->lokasi_unit]);

        Log::info('Barang Rusak diupdate', [
            'nomor' => $barangRusak->nomor,
            'user' => Session::get('admin_username')
        ]);

        return redirect()->route('barang.rusak')->with('success', 'Barang Rusak berhasil diupdate!');
    }

    public function barangRusakDestroy(Request $request, $id)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangRusak = BarangRusak::findOrFail($id);
        
        // Delete foto
        if ($barangRusak->foto) {
            // Note: In production, you would delete the file from storage
        }

        $nomor = $barangRusak->nomor;
        $barangRusak->delete();

        Log::info('Barang Rusak dihapus', [
            'nomor' => $nomor,
            'user' => Session::get('admin_username')
        ]);

        return redirect()->route('barang.rusak')->with('success', 'Barang Rusak berhasil dihapus!');
    }

    // ========== LAPORAN BARANG RUSAK ==========
    public function laporanRusak(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangRusaks = BarangRusak::orderBy('created_at', 'desc')->get();
        
        return view('laporan.rusak', compact('barangRusaks'));
    }

    public function laporanRusakPdf(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangRusaks = BarangRusak::orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('laporan.pdf.rusak', compact('barangRusaks'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan_barang_rusak.pdf');
    }

    public function laporanRusakExcel(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangRusaks = BarangRusak::orderBy('created_at', 'desc')->get();

        return Excel::download(new LaporanExport('rusak', $barangRusaks, ''), 'laporan_barang_rusak.xlsx');
    }

    public function laporanRusakCsv(Request $request)
    {
        $authCheck = $this->checkAuth();
        if ($authCheck) return $authCheck;

        $barangRusaks = BarangRusak::orderBy('created_at', 'desc')->get();

        $filename = 'laporan_barang_rusak.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = ['No', 'Nomor', 'Vehicle Group Code', 'Description', 'Tahun Perolehan', 'Merek', 'Lokasi Unit', 'Kondisi Unit', 'Keterangan'];

        $callback = function() use ($barangRusaks, $columns) {
            $output = fopen('php://output', 'w');
            fputcsv($output, $columns);

            $no = 1;
            foreach ($barangRusaks as $br) {
                fputcsv($output, [
                    $no++,
                    $br->nomor,
                    $br->vehicle_group_code,
                    $br->description,
                    $br->tahun_perolehan,
                    $br->merek,
                    $br->lokasi_unit,
                    $br->kondisi_unit,
                    $br->keterangan,
                ]);
            }

            fclose($output);
        };

        return response()->stream($callback, 200, $headers);
    }
}
