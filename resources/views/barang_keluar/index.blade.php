@extends('layouts.main')

@section('title', 'Barang Keluar')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Barang Keluar</li>
                </ol>
            </nav>
            <h1 class="page-title">Barang Keluar</h1>
            <p class="page-subtitle">Kurangi stok barang dengan scan barcode</p>
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
<ul class="nav nav-tabs mb-4" id="barangKeluarTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="scan-tab-btn" data-bs-toggle="tab" data-bs-target="#scan" type="button">
            <i class="bi bi-camera me-2"></i>Scan Barcode
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="manual-tab-btn" data-bs-toggle="tab" data-bs-target="#manual" type="button">
            <i class="bi bi-search me-2"></i>Pencarian Manual
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="barangKeluarTabContent">
    <!-- Scan Tab -->
    <div class="tab-pane fade show active" id="scan">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-dash-circle text-danger me-2"></i>
                    Kurangi Stok via Scan
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Scanner -->
                    <div class="col-lg-6">
                        <div class="scanner-container mb-3" id="scanner-container">
                            <div id="interactive" class="viewport"></div>
                            <div class="scanner-overlay"></div>
                            <div class="scanner-line"></div>
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
                        <form action="{{ route('barang.keluar.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="bi bi-upc text-primary me-1"></i>
                                    Barcode
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                                    <input type="text" name="barcode" id="barcode-input" class="form-control" required placeholder="Scan atau ketik barcode">
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-123 text-primary me-1"></i>
                                        Jumlah Keluar
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-dash"></i></span>
                                        <input type="number" name="jumlah_keluar" min="1" value="1" class="form-control" required placeholder="Jumlah">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">
                                        <i class="bi bi-calendar text-primary me-1"></i>
                                        Tanggal
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                        <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 mb-3">
                                <label class="form-label">
                                    <i class="bi bi-chat-text text-primary me-1"></i>
                                    Keterangan (Opsional)
                                </label>
                                <textarea name="keterangan" rows="2" class="form-control" placeholder="Tambahkan keterangan..."></textarea>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-dash-lg me-2"></i>Kurangi Stok
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Manual Tab -->
    <div class="tab-pane fade" id="manual">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-search text-danger me-2"></i>
                    Kurangi Stok via Pencarian
                </h5>
            </div>
            <div class="card-body">
                <!-- Search -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="bi bi-search text-primary me-1"></i>
                        Cari Barang
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="search-input" class="form-control" placeholder="Ketik nama atau barcode..." onkeyup="searchBarang()">
                    </div>
                    <div id="search-results" class="list-group mt-2" style="max-height: 200px; overflow-y: auto;"></div>
                </div>

                <form action="{{ route('barang.keluar.manual') }}" method="POST" id="manual-form">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Barcode</label>
                            <input type="text" name="barcode_manual" id="barcode-manual" class="form-control" required readonly>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" id="nama-manual" class="form-control" disabled>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Stok Saat Ini</label>
                            <input type="text" id="stok-manual" class="form-control" disabled>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-123 text-primary me-1"></i>
                                Jumlah Keluar
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-dash"></i></span>
                                <input type="number" name="jumlah_keluar" min="1" value="1" class="form-control" required placeholder="Jumlah">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar text-primary me-1"></i>
                                Tanggal
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" class="form-control" required>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 mb-3">
                        <label class="form-label">
                            <i class="bi bi-chat-text text-primary me-1"></i>
                            Keterangan (Opsional)
                        </label>
                        <textarea name="keterangan" rows="2" class="form-control" placeholder="Tambahkan keterangan..."></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="bi bi-dash-lg me-2"></i>Kurangi Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(!empty($search) && !empty($barangKeluarList))
<!-- Search Results -->
<div class="mt-4">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="bi bi-search text-primary me-2"></i>
                Hasil Pencarian untuk "{{ $search }}"
            </h5>
        </div>
        <div class="card-body">
            @if($barangKeluarList->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Barcode</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangKeluarList as $index => $item)
                        <tr>
                            <td>{{ $barangKeluarList->firstItem() + $index }}</td>
                            <td><code>{{ $item->barcode }}</code></td>
                            <td>{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                            <td><span class="badge bg-danger">{{ $item->jumlah_keluar }}</span></td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $barangKeluarList->appends(['search' => $search])->links() }}
            </div>
            @else
            <div class="text-center py-4 text-muted">
                <i class="bi bi-inbox fs-1"></i>
                <p class="mt-2">Tidak ada data ditemukan</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
let scannerActive = false;
let lastScannedCode = '';
let lastScanTime = 0;
const SCAN_DELAY = 1500; // 1.5 seconds cooldown

// Security check removed - allow camera access from HTTP/IP

function startScanner() {
    if (scannerActive) return;
    
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
                const now = Date.now();
                
                // Check if same barcode scanned within delay period
                if (code === lastScannedCode && (now - lastScanTime) < SCAN_DELAY) {
                    return; // Ignore duplicate scan
                }
                
                lastScannedCode = code;
                lastScanTime = now;
                
                document.getElementById('barcode-input').value = code;
                document.getElementById('barcode-input').focus();
                
                let audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleRYAOpjf38mWXB8dPnb08euSXy4SO4Lm6OK2Xx8VQXni7e2wYyEQPIbm6+uuZCEPJHzn7uypYyEPHn7o7eqoZCEPJH/m7eqnYyAO');
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

let searchTimeout;
function searchBarang() {
    clearTimeout(searchTimeout);
    let query = document.getElementById('search-input').value;
    
    if (query.length < 2) {
        document.getElementById('search-results').innerHTML = '';
        return;
    }
    
    searchTimeout = setTimeout(function() {
        fetch('{{ url('/search-barang') }}?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        html += `<a href="#" class="list-group-item list-group-item-action" onclick="selectBarang('${item.barcode}', '${item.nama_barang}', ${item.stok})">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-semibold">${item.nama_barang}</span>
                                    <br>
                                    <small class="text-muted">
                                        <code>${item.barcode}</code> | 
                                        Stok: <span class="badge bg-secondary">${item.stok}</span> | 
                                        Rak: <span class="badge bg-primary">${item.lokasi_rak}</span>
                                    </small>
                                </div>
                                <i class="bi bi-chevron-right text-muted"></i>
                            </div>
                        </a>`;
                    });
                    document.getElementById('search-results').innerHTML = html;
                } else {
                    document.getElementById('search-results').innerHTML = '<div class="list-group-item text-muted text-center">Tidak ditemukan</div>';
                }
            });
    }, 300);
}

function selectBarang(barcode, nama, stok) {
    document.getElementById('barcode-manual').value = barcode;
    document.getElementById('nama-manual').value = nama;
    document.getElementById('stok-manual').value = stok;
    document.getElementById('search-results').innerHTML = '';
    document.getElementById('search-input').value = '';
}
</script>
@endsection
