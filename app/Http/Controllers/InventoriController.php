<?php

namespace App\Http\Controllers;

use App\Models\MasterBarang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use App\Models\BarangRusak;
use App\Models\MasterVehicleGroup;
use App\Models\MasterLokasiUnit;
use App\Models\BarangRetur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class InventoriController extends Controller
{
    // ========== GET CURRENT USER ID ==========
    private function getCurrentUserId()
    {
        return Auth::id();
    }

    // ========== DASHBOARD ==========
    public function dashboard()
    {

        $totalBarang = MasterBarang::count();
        $totalStok = MasterBarang::sum('stok');
        $barangMasukHariIni = BarangMasuk::whereDate('tanggal', Carbon::today())->sum('jumlah_masuk');
        $barangKeluarHariIni = BarangKeluar::whereDate('tanggal', Carbon::today())->sum('jumlah_keluar');
        
        // Get empty stock items for warning
        $barangStokHabis = MasterBarang::where('stok', '=', 0)
            ->orderBy('nama_barang', 'asc')
            ->get();
            
        $totalStokHabis = $barangStokHabis->count();

        return view('dashboard', compact('totalBarang', 'totalStok', 'barangMasukHariIni', 'barangKeluarHariIni', 'barangStokHabis', 'totalStokHabis'));
    }

    // ========== MASTER BARANG ==========
    public function masterBarang(Request $request)
    {

        $search = $request->input('search');
        $rak = $request->input('rak');
        
        $barangs = MasterBarang::with(['createdBy', 'updatedBy'])
            ->when($search, function($query) use ($search) {
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

        $request->validate([
            'barcode' => 'required|string|max:100|unique:master_barang,barcode',
            'nama_barang' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'required|in:A,B,C,D,E,F,G,H,O',
        ]);

        $barang = MasterBarang::create([
            'barcode' => $request->barcode,
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'lokasi_rak' => $request->lokasi_rak,
            'created_by' => $this->getCurrentUserId(),
        ]);
        
        Log::info('Barang ditambahkan', [
            'barcode' => $barang->barcode,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
            'lokasi_rak' => $barang->lokasi_rak,
            'user' => Auth::user()->username
        ]);

        return redirect()->route('master.barang')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function masterBarangUpdate(Request $request, $barcode)
    {

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
        
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'stok' => $request->stok,
            'lokasi_rak' => $request->lokasi_rak,
            'updated_by' => $this->getCurrentUserId(),
        ]);

        Log::info('Barang diupdate', [
            'barcode' => $barang->barcode,
            'nama_barang' => $barang->nama_barang,
            'stok' => $barang->stok,
            'lokasi_rak' => $barang->lokasi_rak,
            'user' => Auth::user()->username
        ]);

        return redirect()->route('master.barang')->with('success', 'Barang berhasil diupdate!');
    }

    public function masterBarangDestroy($barcode)
    {

        $barang = MasterBarang::findOrFail($barcode);
        $barang->delete();

        Log::warning('Barang dihapus', [
            'barcode' => $barcode,
            'nama_barang' => $barang->nama_barang,
            'user' => Auth::user()->username
        ]);

        return redirect()->route('master.barang')->with('success', 'Barang berhasil dihapus!');
    }

// ========== RIWAYAT MASTER BARANG ==========
    public function masterBarangRiwayat(Request $request)
    {

        $search = $request->input('search');
        $filter = $request->input('filter', 'semua');

        $barangs = MasterBarang::with(['createdBy', 'updatedBy'])->withTrashed()
             ->when($search, function($query) use ($search) {
                 $query->where('barcode', 'like', '%' . $search . '%')
                       ->orWhere('nama_barang', 'like', '%' . $search . '%');
             })
             ->orderBy('updated_at', 'desc')
             ->orderBy('created_at', 'desc')
             ->paginate(15);

        $totalBarang = MasterBarang::withTrashed()->count();
        $totalDibuat = MasterBarang::withTrashed()->whereNotNull('created_by')->count();
        $totalDiupdate = MasterBarang::withTrashed()->whereNotNull('updated_by')->distinct()->count('updated_by');

        return view('master_barang.riwayat', compact('barangs', 'search', 'filter', 'totalBarang', 'totalDibuat', 'totalDiupdate'));
    }

    // ========== RESTORE MASTER BARANG (Admin only) ==========
    public function masterBarangRestore($barcode)
    {
        $barang = MasterBarang::withTrashed()->where('barcode', $barcode)->firstOrFail();
        $barang->restore();

        Log::info('Barang dipulihkan', [
            'barcode' => $barcode,
            'nama_barang' => $barang->nama_barang,
            'user' => Auth::user()->username
        ]);

        return redirect()->route('master.barang.riwayat')->with('success', 'Barang berhasil dipulihkan!');
    }

    // ========== BARANG MASUK ==========
    public function barangMasuk(Request $request)
    {

        $barangs = MasterBarang::orderBy('nama_barang', 'asc')->get();
        
        // Handle search filter
        $search = $request->search ?? '';
        $barangMasukList = [];
        
        if (!empty($search)) {
            $barangMasukList = BarangMasuk::whereHas('masterBarang', function($q) use ($search) {
                    $q->where('nama_barang', 'like', '%' . $search . '%')
                      ->orWhere('barcode', 'like', '%' . $search . '%');
                })
                ->orWhere('barcode', 'like', '%' . $search . '%')
                ->with('masterBarang')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        
        return view('barang_masuk.index', compact('barangs', 'search', 'barangMasukList'));
    }

    public function barangMasukStore(Request $request)
    {

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
                $barang->updated_by = $this->getCurrentUserId();
                $barang->save();

                // Simpan ke barang_masuk
                BarangMasuk::create([
                    'barcode' => $request->barcode,
                    'jumlah_masuk' => $request->jumlah_masuk,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'created_by' => $this->getCurrentUserId(),
                ]);
                
                Log::info('Barang masuk dicatat', [
                    'barcode' => $request->barcode,
                    'nama_barang' => $barang->nama_barang,
                    'jumlah_masuk' => $request->jumlah_masuk,
                    'tanggal' => $request->tanggal,
                    'user' => Auth::user()->username
                ]);
            });

            return redirect()->route('barang.masuk')->with('success', 'Barang masuk berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function barangMasukManual(Request $request)
    {

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
                    'created_by' => $this->getCurrentUserId(),
                ]);
            });

            return redirect()->route('barang.masuk')->with('success', 'Barang masuk berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // ========== RIWAYAT BARANG MASUK ==========
    public function barangMasukRiwayat(Request $request)
    {

        $search = $request->input('search');
        
$barangMasuk = BarangMasuk::with(['masterBarang', 'createdBy'])->withTrashed()
             ->when($search, function($query) use ($search) {
                 $query->where('barcode', 'like', '%' . $search . '%')
                       ->orWhereHas('masterBarang', function($q) use ($search) {
                           $q->where('nama_barang', 'like', '%' . $search . '%');
                       });
             })
             ->orderBy('tanggal', 'desc')
             ->get();
        
        $totalQty = $barangMasuk->sum('jumlah_masuk');
        $totalUser = $barangMasuk->pluck('created_by')->filter()->unique()->count();

        return view('barang_masuk.riwayat', compact('barangMasuk', 'search', 'totalQty', 'totalUser'));
    }

    // ========== BARANG KELUAR ==========
    public function barangKeluar(Request $request)
    {

        $barangs = MasterBarang::orderBy('nama_barang', 'asc')->get();
        
        // Handle search filter
        $search = $request->search ?? '';
        $barangKeluarList = [];
        
        if (!empty($search)) {
            $barangKeluarList = BarangKeluar::whereHas('masterBarang', function($q) use ($search) {
                    $q->where('nama_barang', 'like', '%' . $search . '%')
                      ->orWhere('barcode', 'like', '%' . $search . '%');
                })
                ->orWhere('barcode', 'like', '%' . $search . '%')
                ->with('masterBarang')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        
        return view('barang_keluar.index', compact('barangs', 'search', 'barangKeluarList'));
    }

    public function barangKeluarStore(Request $request)
    {

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
                $barang->updated_by = $this->getCurrentUserId();
                $barang->save();

                // Simpan ke barang_keluar
                BarangKeluar::create([
                    'barcode' => $request->barcode,
                    'jumlah_keluar' => $request->jumlah_keluar,
                    'tanggal' => $request->tanggal,
                    'keterangan' => $request->keterangan,
                    'created_by' => $this->getCurrentUserId(),
                ]);
                
                Log::info('Barang keluar dicatat', [
                    'barcode' => $request->barcode,
                    'nama_barang' => $barang->nama_barang,
                    'jumlah_keluar' => $request->jumlah_keluar,
                    'tanggal' => $request->tanggal,
                    'user' => Auth::user()->username
                ]);
            });

            return redirect()->route('barang.keluar')->with('success', 'Barang keluar berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    // ========== RIWAYAT BARANG KELUAR ==========
    public function barangKeluarRiwayat(Request $request)
    {

        $search = $request->input('search');

        $barangKeluar = BarangKeluar::with(['masterBarang', 'createdBy'])->withTrashed()
            ->when($search, function($query) use ($search) {
                $query->where('barcode', 'like', '%' . $search . '%')
                      ->orWhereHas('masterBarang', function($q) use ($search) {
                          $q->where('nama_barang', 'like', '%' . $search . '%');
                      });
            })
            ->orderBy('tanggal', 'desc')
            ->get();
        
        $totalQty = $barangKeluar->sum('jumlah_keluar');
        $totalUser = $barangKeluar->pluck('created_by')->filter()->unique()->count();

        return view('barang_keluar.riwayat', compact('barangKeluar', 'search', 'totalQty', 'totalUser'));
    }

    public function barangKeluarManual(Request $request)
    {

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
                    'created_by' => $this->getCurrentUserId(),
                ]);
            });

            return redirect()->route('barang.keluar')->with('success', 'Barang keluar berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

public function barangKeluarScannerInput(Request $request)
    {
        if ($authCheck) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $request->validate([
            'barcode' => 'required|string|exists:master_barang,barcode',
            'quantity' => 'nullable|integer|min:1',
        ]);

        try {
            $barang = MasterBarang::findOrFail($request->barcode);
            $quantity = $request->quantity ?? 1;

            if ($barang->stok < $quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak cukup! Stok tersedia: ' . $barang->stok
                ], 400);
            }

            DB::transaction(function () use ($request, $quantity, $barang) {
                $barang->stok = $barang->stok - $quantity;
                $barang->updated_by = $this->getCurrentUserId();
                $barang->save();

                BarangKeluar::create([
                    'barcode' => $request->barcode,
                    'jumlah_keluar' => $quantity,
                    'tanggal' => now()->toDateString(),
                    'keterangan' => 'Scan dari Panda PRJ 777 (Dashboard)',
                    'created_by' => $this->getCurrentUserId(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Berhasil: ' . $barang->nama_barang . ' (' . $quantity . ')',
                'barang' => [
                    'nama_barang' => $barang->nama_barang,
                    'stok_baru' => $barang->stok
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function barangKeluarQuickScan(Request $request)
     {
         if ($authCheck) return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);

        $request->validate([
            'items' => 'required|array',
            'items.*.barcode' => 'required|string|exists:master_barang,barcode',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $currentTime = now();

                foreach ($request->items as $item) {
                    // Cek stok cukup
                    $barang = MasterBarang::findOrFail($item['barcode']);
                    if ($barang->stok < $item['quantity']) {
                        throw new \Exception('Stok tidak cukup untuk ' . $barang->nama_barang . '! Stok tersedia: ' . $barang->stok);
                    }

                    // Update stok di master_barang
                    $barang->stok = $barang->stok - $item['quantity'];
                    $barang->updated_by = $this->getCurrentUserId();
                    $barang->save();

                    // Simpan ke barang_keluar
                    BarangKeluar::create([
                        'barcode' => $item['barcode'],
                        'jumlah_keluar' => $item['quantity'],
                        'tanggal' => $currentTime->toDateString(),
                        'keterangan' => 'Quick scan dari dashboard',
                        'created_by' => $this->getCurrentUserId(),
                    ]);

                    Log::info('Barang keluar via quick scan', [
                        'barcode' => $item['barcode'],
                        'nama_barang' => $barang->nama_barang,
                        'jumlah_keluar' => $item['quantity'],
                        'tanggal' => $currentTime->toDateString(),
                        'user' => Auth::user()->username
                    ]);
                }
            });

            return response()->json(['success' => true, 'message' => 'Barang keluar berhasil dicatat!']);
        } catch (\Exception $e) {
            Log::error('Error quick scan barang keluar', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->username
            ]);
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    // ========== BARANG RETUR ==========
    public function barangRetur(Request $request)
    {

        $search = $request->search ?? '';
        
        // Get barang_keluar that haven't been fully retured
        $barangKeluar = BarangKeluar::with(['masterBarang', 'createdBy'])
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
        $retur = BarangRetur::with(['barangKeluar.masterBarang', 'masterBarang', 'createdBy'])
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
                    'created_by' => $this->getCurrentUserId(),
                ]);

                // Update stok di master_barang (tambah stok)
                $barang = MasterBarang::findOrFail($barangKeluar->barcode);
                $barang->stok = $barang->stok + $request->jumlah_retur;
                $barang->updated_by = $this->getCurrentUserId();
                $barang->save();
            });

            return redirect()->route('barang.retur')->with('success', 'Barang retur berhasil dicatat!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function barangReturDestroy($id)
    {

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

    // ========== RIWAYAT BARANG RETUR ==========
    public function barangReturRiwayat(Request $request)
    {

        $search = $request->input('search');
        
$retur = BarangRetur::with(['masterBarang', 'createdBy'])->withTrashed()
             ->when($search, function($query) use ($search) {
                 $query->where('barcode', 'like', '%' . $search . '%')
                       ->orWhereHas('masterBarang', function($q) use ($search) {
                           $q->where('nama_barang', 'like', '%' . $search . '%');
                       });
             })
             ->orderBy('tanggal_retur', 'desc')
             ->get();
        
        $totalQty = $retur->sum('jumlah_retur');
        $totalUser = $retur->pluck('created_by')->filter()->unique()->count();

        return view('barang_retur.riwayat', compact('retur', 'search', 'totalQty', 'totalUser'));
    }

    // ========== LAPORAN ==========
    public function laporan()
    {

        return view('laporan.index');
    }

    // Laporan Barang Masuk
    public function laporanMasuk(Request $request)
    {

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with(['masterBarang', 'createdBy'])
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalMasuk = $barangMasuks->sum('jumlah_masuk');

        return view('laporan.masuk', compact('barangMasuks', 'bulan', 'tahun', 'totalMasuk'));
    }

    public function laporanMasukPdf(Request $request)
    {

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangMasuks = BarangMasuk::with(['masterBarang', 'createdBy'])
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

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        return Excel::download(new LaporanExport('masuk', $bulan, $tahun), 'laporan_barang_masuk_' . $bulan . '_' . $tahun . '.xlsx');
    }

    public function laporanMasukCsv(Request $request)
    {

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

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangKeluars = BarangKeluar::with(['masterBarang', 'createdBy'])
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

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $barangKeluars = BarangKeluar::with(['masterBarang', 'createdBy'])
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

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        return Excel::download(new LaporanExport('keluar', $bulan, $tahun), 'laporan_barang_keluar_' . $bulan . '_' . $tahun . '.xlsx');
    }

    public function laporanKeluarCsv(Request $request)
    {

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

        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        return Excel::download(new LaporanExport('gabungan', $bulan, $tahun), 'laporan_transaksi_' . $bulan . '_' . $tahun . '.xlsx');
    }

    public function laporanGabunganCsv(Request $request)
    {

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

        $rak = $request->rak ?? 'all';
        
        return Excel::download(new LaporanExport('rak', $rak, date('Y')), 'laporan_barang_per_rak' . ($rak !== 'all' ? '_rak_' . $rak : '') . '.xlsx');
    }

    public function laporanRakCsv(Request $request)
    {

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

    // ========== GLOBAL SEARCH API ==========
    public function globalSearch(Request $request)
    {
        if ($authCheck) return response()->json(['error' => 'Unauthorized'], 401);
        
        $query = $request->q ?? '';
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $results = [];
        
        // Search in Master Barang
        $barangs = MasterBarang::where('nama_barang', 'like', '%' . $query . '%')
            ->orWhere('barcode', 'like', '%' . $query . '%')
            ->orWhere('lokasi_rak', 'like', '%' . $query . '%')
            ->limit(5)
            ->get()
            ->map(function($item) use ($query) {
                return [
                    'type' => 'Master Barang',
                    'title' => $item->nama_barang,
                    'subtitle' => 'Barcode: ' . $item->barcode . ' | Rak: ' . ($item->lokasi_rak ?? '-'),
                    'url' => route('master.barang') . '?search=' . urlencode($item->barcode)
                ];
            });
        $results = array_merge($results, $barangs->toArray());
        
        // Search in Barang Masuk
        $masuks = BarangMasuk::whereHas('masterBarang', function($q) use ($query) {
                $q->where('nama_barang', 'like', '%' . $query . '%')
                  ->orWhere('barcode', 'like', '%' . $query . '%');
            })
            ->orWhere('barcode', 'like', '%' . $query . '%')
            ->with('masterBarang')
            ->limit(5)
            ->get()
            ->map(function($item) use ($query) {
                return [
                    'type' => 'Barang Masuk',
                    'title' => $item->masterBarang->nama_barang ?? 'N/A',
                    'subtitle' => 'Barcode: ' . $item->barcode . ' | ' . $item->tanggal,
                    'url' => route('barang.masuk') . '?search=' . urlencode($item->barcode)
                ];
            });
        $results = array_merge($results, $masuks->toArray());
        
        // Search in Barang Keluar
        $keluars = BarangKeluar::whereHas('masterBarang', function($q) use ($query) {
                $q->where('nama_barang', 'like', '%' . $query . '%')
                  ->orWhere('barcode', 'like', '%' . $query . '%');
            })
            ->orWhere('barcode', 'like', '%' . $query . '%')
            ->with('masterBarang')
            ->limit(5)
            ->get()
            ->map(function($item) use ($query) {
                return [
                    'type' => 'Barang Keluar',
                    'title' => $item->masterBarang->nama_barang ?? 'N/A',
                    'subtitle' => 'Barcode: ' . $item->barcode . ' | ' . $item->tanggal,
                    'url' => route('barang.keluar') . '?search=' . urlencode($item->barcode)
                ];
            });
        $results = array_merge($results, $keluars->toArray());
        
        // Search in Barang Rusak
        $rusaks = BarangRusak::where('vehicle_group_code', 'like', '%' . $query . '%')
            ->orWhere('merek', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->limit(5)
            ->get()
            ->map(function($item) use ($query) {
                return [
                    'type' => 'Barang Rusak',
                    'title' => $item->vehicle_group_code . ' - ' . $item->merek,
                    'subtitle' => 'No: ' . $item->nomor . ' | Kondisi: ' . $item->kondisi_unit,
                    'url' => route('barang.rusak') . '?search=' . urlencode($query)
                ];
            });
        $results = array_merge($results, $rusaks->toArray());
        
        // Search in Barang Retur
        $returs = BarangRetur::whereHas('masterBarang', function($q) use ($query) {
                $q->where('nama_barang', 'like', '%' . $query . '%')
                  ->orWhere('barcode', 'like', '%' . $query . '%');
            })
            ->with('masterBarang')
            ->limit(5)
            ->get()
            ->map(function($item) use ($query) {
                return [
                    'type' => 'Barang Retur',
                    'title' => $item->masterBarang->nama_barang ?? 'N/A',
                    'subtitle' => 'ID: ' . $item->id . ' | ' . $item->tanggal_retur,
                    'url' => route('barang.retur') . '?search=' . urlencode($query)
                ];
            });
        $results = array_merge($results, $returs->toArray());
        
        return response()->json(array_slice($results, 0, 20));
    }

    // ========== BARANG RUSAK ==========
    public function barangRusak()
    {

        $barangRusaks = BarangRusak::orderBy('id', 'desc')->paginate(10);
        $vehicleGroups = MasterVehicleGroup::orderBy('kode')->get();
        $lokasiUnits = MasterLokasiUnit::orderBy('lokasi')->get();
        $masterBarangs = MasterBarang::orderBy('nama_barang')->get();
        
        return view('barang_rusak.index', compact('barangRusaks', 'vehicleGroups', 'lokasiUnits', 'masterBarangs'));
    }

    public function barangRusakStore(Request $request)
    {

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
            'created_by' => $this->getCurrentUserId(),
        ]);

        // Save vehicle_group_code to master if not exists
        MasterVehicleGroup::firstOrCreate(['kode' => $request->vehicle_group_code]);

        // Save lokasi_unit to master if not exists
        MasterLokasiUnit::firstOrCreate(['lokasi' => $request->lokasi_unit]);

        Log::info('Barang Rusak ditambahkan', [
            'nomor' => $barangRusak->nomor,
            'vehicle_group_code' => $barangRusak->vehicle_group_code,
            'merek' => $barangRusak->merek,
            'user' => Auth::user()->username
        ]);

        return redirect()->route('barang.rusak')->with('success', 'Barang Rusak berhasil ditambahkan!');
    }

    public function barangRusakUpdate(Request $request, $id)
    {

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
            'user' => Auth::user()->username
        ]);

        return redirect()->route('barang.rusak')->with('success', 'Barang Rusak berhasil diupdate!');
    }

    public function barangRusakDestroy(Request $request, $id)
    {

        $barangRusak = BarangRusak::findOrFail($id);
        
        // Delete foto
        if ($barangRusak->foto) {
            // Note: In production, you would delete the file from storage
        }

        $nomor = $barangRusak->nomor;
        $barangRusak->delete();

        Log::info('Barang Rusak dihapus', [
            'nomor' => $nomor,
            'user' => Auth::user()->username
        ]);

        return redirect()->route('barang.rusak')->with('success', 'Barang Rusak berhasil dihapus!');
    }

    // ========== RIWAYAT BARANG RUSAK ==========
    public function barangRusakRiwayat(Request $request)
    {

        $search = $request->input('search');
        
        $rusak = BarangRusak::with('createdBy')
            ->when($search, function($query) use ($search) {
                $query->where('vehicle_group_code', 'like', '%' . $search . '%')
                      ->orWhere('description', 'like', '%' . $search . '%')
                      ->orWhere('merek', 'like', '%' . $search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('barang_rusak.riwayat', compact('rusak', 'search'));
    }

    // ========== LAPORAN BARANG RUSAK ==========
    public function laporanRusak(Request $request)
    {

        $barangRusaks = BarangRusak::with('createdBy')->withTrashed()->orderBy('created_at', 'desc')->get();
        
        return view('laporan.rusak', compact('barangRusaks'));
    }

    public function laporanRusakPdf(Request $request)
    {

        $barangRusaks = BarangRusak::with('createdBy')->withTrashed()->orderBy('created_at', 'desc')->get();

        $pdf = Pdf::loadView('laporan.pdf.rusak', compact('barangRusaks'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('laporan_barang_rusak.pdf');
    }

    public function laporanRusakExcel(Request $request)
    {

        $barangRusaks = BarangRusak::with('createdBy')->withTrashed()->orderBy('created_at', 'desc')->get();

        return Excel::download(new LaporanExport('rusak', $barangRusaks, ''), 'laporan_barang_rusak.xlsx');
    }

    public function laporanRusakCsv(Request $request)
    {

        $barangRusaks = BarangRusak::with('createdBy')->withTrashed()->orderBy('created_at', 'desc')->get();

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

    // ========== USER MANAGEMENT ==========
    public function users(Request $request)
    {

        // Only admin can access user management
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak! Hanya admin yang dapat mengakses halaman ini.');
        }

        $search = $request->input('search');
        $role = $request->input('role');

        $users = User::when($search, function($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                      ->orWhere('username', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
            })
            ->when($role, function($query) use ($role) {
                $query->where('role', $role);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('users.index', compact('users', 'search', 'role'));
    }

    public function usersStore(Request $request)
    {

        // Only admin can create users
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'menu_permissions' => $request->role === 'user' ? ($request->menu_permissions ?? []) : null,
        ]);

        Log::info('User created', [
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'created_by' => Auth::user()->username
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function usersUpdate(Request $request, $id)
    {

        // Only admin can update users
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak!');
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->menu_permissions = $request->role === 'user' ? ($request->menu_permissions ?? []) : null;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        Log::info('User updated', [
            'id' => $id,
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'updated_by' => Auth::user()->username
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function usersDestroy($id)
    {

        // Only admin can delete users
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak!');
        }

        // Prevent admin from deleting themselves
        if (Auth::id() == $id) {
            return redirect()->route('users.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();

        Log::info('User deleted', [
            'id' => $id,
            'name' => $userName,
            'deleted_by' => Auth::user()->username
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    // ========== PROFILE ==========
    public function profile()
    {

        $userId = Auth::id();
        $isAdmin = ($userId === 'admin');
        
        // For admin (hardcoded), return basic info
        if ($isAdmin) {
            return view('profile.index', [
                'user' => null,
                'isAdmin' => true,
            ]);
        }

        $user = User::findOrFail($userId);
        return view('profile.index', compact('user', 'isAdmin'));
    }

    public function profileUpdatePhoto(Request $request)
    {

        $userId = Auth::id();
        
        // Admin cannot update profile photo (hardcoded account)
        if ($userId === 'admin') {
            return back()->with('error', 'Akun admin tidak dapat mengubah foto profil!');
        }

        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = User::findOrFail($userId);

        // Delete old photo if exists
        if ($user->profile_photo) {
            $oldPath = storage_path('app/public/profile_photos/' . $user->profile_photo);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Store new photo
        $file = $request->file('profile_photo');
        $filename = time() . '_' . $user->id . '.' . $file->getClientOriginalExtension();
        
        // Ensure directory exists
        $directory = storage_path('app/public/profile_photos');
        if (!file_exists($directory)) {
            mkdir($directory, 0777, true);
        }
        
        $file->move($directory, $filename);

        $user->profile_photo = $filename;
        $user->save();

        Log::info('Profile photo updated', [
            'user_id' => $user->id,
            'username' => $user->username
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }

    public function profileUpdatePassword(Request $request)
    {
        $user = Auth::user();

        // Admin cannot change password this way (hardcoded)
        if ($user->username === 'admin') {
            return back()->with('error', 'Akun admin tidak dapat mengubah password melalui halaman ini!');
        }

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::findOrFail($userId);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        Log::info('Password changed', [
            'user_id' => $user->id,
            'username' => $user->username
        ]);

        return back()->with('success', 'Password berhasil diperbarui!');
    }
}
