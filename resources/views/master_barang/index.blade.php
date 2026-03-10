@extends('layouts.main')

@section('title', 'Master Barang')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Master Barang</li>
                </ol>
            </nav>
            <h1 class="page-title">Master Barang</h1>
            <p class="page-subtitle">Kelola data barang dengan scan barcode</p>
        </div>
    </div>
</div>

@if(Session::has('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>
    {{ Session::get('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>
    {{ Session::get('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="masterBarangTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="scan-tab-btn" data-bs-toggle="tab" data-bs-target="#scan" type="button">
            <i class="bi bi-camera me-2"></i>Scan Barcode
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="list-tab-btn" data-bs-toggle="tab" data-bs-target="#list" type="button">
            <i class="bi bi-list-ul me-2"></i>Daftar Barang
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="masterBarangTabContent">
    <!-- Scan Tab -->
    <div class="tab-pane fade show active" id="scan">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle text-primary me-2"></i>
                    Tambah Barang Baru
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Scanner -->
                    <div class="col-lg-6">
                        <div class="scanner-container mb-3" id="scanner-container">
                            <div id="interactive" class="viewport"></div>
                            <div class="scanner-overlay"></div>
                            <div class="scanner-hint">
                                <i class="bi bi-camera-video me-1"></i>
                                Arahkan kamera ke barcode
                            </div>
                        </div>
                        <div class="d-grid gap-2">
                            <button onclick="startScanner()" class="btn btn-primary">
                                <i class="bi bi-play-fill me-2"></i>Mulai Scanner
                            </button>
                            <button onclick="stopScanner()" class="btn btn-outline-danger">
                                <i class="bi bi-stop-fill me-2"></i>Stop Scanner
                            </button>
                        </div>
                    </div>

                    <!-- Form -->
                    <div class="col-lg-6">
                        <form action="{{ route('master.barang.store') }}" method="POST" id="barang-form">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-upc text-primary me-1"></i>
                                    Barcode
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                    <input type="text" name="barcode" id="barcode-input" class="form-control" required placeholder="Scan atau ketik barcode">
                                    <button type="button" onclick="generateBarcode()" class="btn btn-warning">
                                        <i class="bi bi-shuffle me-1"></i>Generate
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-box text-primary me-1"></i>
                                    Nama Barang
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                                    <input type="text" name="nama_barang" class="form-control" required placeholder="Masukkan nama barang">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-123 text-primary me-1"></i>
                                        Stok Awal
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-archive"></i></span>
                                        <input type="number" name="stok" value="0" min="0" class="form-control" required placeholder="0">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-grid text-primary me-1"></i>
                                        Lokasi Rak
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                        <select name="lokasi_rak" class="form-select" required>
                                            <option value="">Pilih Rak</option>
                                            <option value="A">Rak A</option>
                                            <option value="B">Rak B</option>
                                            <option value="C">Rak C</option>
                                            <option value="D">Rak D</option>
                                            <option value="E">Rak E</option>
                                            <option value="F">Rak F</option>
                                            <option value="G">Rak G</option>
                                            <option value="H">Rak H</option>
                                            <option value="O">Rak O</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-save me-2"></i>Simpan Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- List Tab -->
    <div class="tab-pane fade" id="list">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam text-primary me-2"></i>
                    Daftar Semua Barang
                </h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="search-table" placeholder="Cari barang..." class="form-control">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="barang-table">
                        <thead>
                            <tr>
                                <th><i class="bi bi-upc me-1"></i>Barcode</th>
                                <th><i class="bi bi-box me-1"></i>Nama Barang</th>
                                <th class="text-center"><i class="bi bi-archive me-1"></i>Stok</th>
                                <th class="text-center"><i class="bi bi-geo-alt me-1"></i>Rak</th>
                                <th class="text-center"><i class="bi bi-gear me-1"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangs as $barang)
                            <tr>
                                <td>
                                    <code class="bg-light px-2 py-1 rounded">{{ $barang->barcode }}</code>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ $barang->nama_barang }}</span>
                                </td>
                                <td class="text-center">
                                    @if($barang->stok > 10)
                                        <span class="badge bg-success-subtle text-success px-3 py-2">
                                            <i class="bi bi-check-circle me-1"></i>{{ $barang->stok }}
                                        </span>
                                    @elseif($barang->stok > 0)
                                        <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                            <i class="bi bi-exclamation-circle me-1"></i>{{ $barang->stok }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                            <i class="bi bi-x-circle me-1"></i>Habis
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary">{{ $barang->lokasi_rak }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button onclick="printBarcode('{{ $barang->barcode }}', '{{ $barang->nama_barang }}')"
                                            class="btn btn-outline-secondary" title="Cetak Barcode">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                        <button onclick="editBarang('{{ $barang->barcode }}', '{{ $barang->nama_barang }}', {{ $barang->stok }}, '{{ $barang->lokasi_rak }}')"
                                            class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('master.barang.destroy', $barang->barcode) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Hapus"
                                                onclick="return confirm('Yakin hapus barang ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                        <h5>Belum ada barang</h5>
                                        <p class="mb-0">Klik tab "Scan Barcode" untuk menambah barang baru</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($barangs->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="text-muted small">
                            Menampilkan {{ $barangs->firstItem() }} - {{ $barangs->lastItem() }} dari {{ $barangs->total() }} data
                        </div>
                        {{ $barangs->appends(request()->query())->fragment('list')->links('pagination::bootstrap-5') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square text-warning me-2"></i>
                    Edit Barang
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="edit-form" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Barcode</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                            <input type="text" id="edit-barcode" class="form-control" disabled>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Barang</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-box-seam"></i></span>
                            <input type="text" name="nama_barang" id="edit-nama" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-archive"></i></span>
                            <input type="number" name="stok" id="edit-stok" min="0" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Rak</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                            <select name="lokasi_rak" id="edit-rak" class="form-select" required>
                                <option value="A">Rak A</option>
                                <option value="B">Rak B</option>
                                <option value="C">Rak C</option>
                                <option value="D">Rak D</option>
                                <option value="E">Rak E</option>
                                <option value="F">Rak F</option>
                                <option value="G">Rak G</option>
                                <option value="H">Rak H</option>
                                <option value="O">Rak O</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save me-1"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let scannerActive = false;
let editModal = null;

document.addEventListener('DOMContentLoaded', function() {
    editModal = new bootstrap.Modal(document.getElementById('editModal'));
    
    // Check if this is a pagination request (has page parameter or hash is #list)
    const urlParams = new URLSearchParams(window.location.search);
    const hasPage = urlParams.has('page');
    const hash = window.location.hash;
    
    if (hasPage || hash === '#list') {
        // Switch to Daftar Barang tab
        const listTabBtn = document.getElementById('list-tab-btn');
        const listTab = document.getElementById('list');
        const scanTabBtn = document.getElementById('scan-tab-btn');
        const scanTab = document.getElementById('scan');
        
        scanTabBtn.classList.remove('active');
        scanTab.classList.remove('show', 'active');
        listTabBtn.classList.add('active');
        listTab.classList.add('show', 'active');
    }
});

function generateBarcode() {
    const timestamp = Date.now().toString();
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    const barcode = 'BRG' + timestamp.slice(-8) + random;
    document.getElementById('barcode-input').value = barcode;
}

// Print barcode function - 50mm x 20mm label size
function printBarcode(barcode, namaBarang) {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Cetak Barcode - ${barcode}</title>
            <script src="{{ asset('assets/js/JsBarcode.all.min.js') }}"><\/script>
            <style>
                @page { size: 50mm 20mm; margin: 0; }
                @media print { body { margin: 0; padding: 0; } .no-print { display: none !important; } }
                body { width: 50mm; height: 20mm; margin: 0; padding: 1mm; font-family: Arial, sans-serif; box-sizing: border-box; }
                .barcode-container { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; padding-top: 1mm; }
                .barcode-info { text-align: center; margin-bottom: -1mm; }
                .barcode-info .nama { font-size: 11pt; font-weight: bold; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 48mm; line-height: 1; }
                .barcode-svg { width: 45mm; height: 9mm; margin: 0; }
                .no-print { position: fixed; top: 10px; left: 10px; z-index: 9999; }
                .no-print button { padding: 6px 12px; margin: 3px; border: none; border-radius: 3px; cursor: pointer; font-size: 11px; }
                .btn-print { background: #4f46e5; color: white; }
                .btn-close { background: #6c757d; color: white; }
            </style>
        </head>
        <body>
            <div class="no-print">
                <button class="btn-print" onclick="window.print()">🖨️ Cetak</button>
                <button class="btn-close" onclick="window.close()">Tutup</button>
            </div>
            <div class="barcode-container">
                <div class="barcode-info">
                    <p class="nama">${namaBarang}</p>
                </div>
                <svg id="barcode" class="barcode-svg"></svg>
            </div>
            <script> JsBarcode("#barcode", "${barcode}", { format: "CODE128", width: 2, height: 35, displayValue: false, margin: 0 }); <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function startScanner() {
    if (scannerActive) return;
    
    // Security check removed - allow camera access from HTTP/IP
    
    Quagga.offDetected();
    
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#interactive'),
            constraints: {
                facingMode: "environment",
                width: { min: 640, ideal: 1280, max: 1920 },
                height: { min: 480, ideal: 720, max: 1080 }
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: true
        },
        numOfWorkers: navigator.hardwareConcurrency || 4,
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "upc_reader", "upc_e_reader", "code_93_reader"]
        },
        locate: true
    }, function(err) {
        if (err) {
            console.log(err);
            let errorMessage = 'Gagal memulai kamera: ' + err.message + '\n\n';
            
            if (err.name === 'NotAllowedError') {
                errorMessage += 'SOLUSI:\n' +
                    '1. Klik ikon 🔒/🔓 di address bar\n' +
                    '2. Izinkan akses kamera untuk situs ini\n' +
                    '3. Atau gunakan HTTPS untuk aksesScanner';
            } else if (err.name === 'NotFoundError') {
                errorMessage += 'SOLUSI: Pastikan perangkat memiliki kamera.';
            } else if (err.name === 'NotReadableError') {
                errorMessage += 'SOLUSI: Kamera sedang digunakan aplikasi lain.';
            } else {
                errorMessage += 'Pastikan kamera diizinkan dan tidak digunakan aplikasi lain.';
            }
            
            alert(errorMessage);
            return;
        }
        
        Quagga.onDetected(function(result) {
            if (result.codeResult && result.codeResult.code) {
                const code = result.codeResult.code;
                document.getElementById('barcode-input').value = code;
                document.getElementById('barcode-input').focus();
                
                let audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleRYAOpjf38mWXB8dPnb08euSXy4SO4Lm6OK2Xx8VQXni7e2wYyEQPIbm6+uuZCEPIHzn7uypYyEPHn7o7eqoZCEPJH/m7eqnYyAO');
                audio.play().catch(() => {});
                
                stopScanner();
            }
        });
        
        Quagga.start();
        scannerActive = true;
    });
}

function stopScanner() {
    if (!scannerActive) return;
    Quagga.stop();
    scannerActive = false;
}

function editBarang(barcode, nama, stok, rak) {
    document.getElementById('edit-barcode').value = barcode;
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-stok').value = stok;
    document.getElementById('edit-rak').value = rak;
    // Use Laravel's url() helper to get the correct base path for subdirectory support
    document.getElementById('edit-form').action = '{{ url('/master-barang') }}/' + encodeURIComponent(barcode);
    editModal.show();
}

// Search table
document.getElementById('search-table').addEventListener('keyup', function() {
    let search = this.value.toLowerCase();
    let table = document.getElementById('barang-table');
    let rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        let barcode = rows[i].cells[0].textContent.toLowerCase();
        let nama = rows[i].cells[1].textContent.toLowerCase();
        let rak = rows[i].cells[3].textContent.toLowerCase();
        
        if (barcode.includes(search) || nama.includes(search) || rak.includes(search)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
});
</script>
@endsection
