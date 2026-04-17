@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Selamat datang di Sistem Inventori Gudang IT</p>
        </div>
        <div class="d-none d-md-block">
            <span class="badge bg-light text-dark px-3 py-2">
                <i class="bi bi-calendar3 me-1"></i>
                {{ \Carbon\Carbon::now()->format('d M Y') }}
            </span>
        </div>
    </div>
</div>

<!-- Stock Empty Warning Alert -->
@if($totalStokHabis > 0)
<div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
        </div>
        <div>
            <h5 class="alert-heading mb-1">⚠️ PERINGATAN STOK HABIS!</h5>
            <p class="mb-0">Terdapat <strong>{{ $totalStokHabis }}</strong> jenis barang yang stoknya sudah habis (0). Segera lakukan pengadaan barang!</p>
        </div>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
</div>
@endif

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    @if(Session::get('user_role') === 'admin')
    <!-- Total Barang -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-1">Total Jenis Barang</p>
                        <h2 class="stat-value mb-0">{{ $totalBarang }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-box-seam me-1"></i>Item terdaftar
                        </small>
                    </div>
                    <div class="stat-icon primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Empty Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100 border-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-1 text-danger">Barang Stok Habis</p>
                        <h2 class="stat-value mb-0 text-danger">{{ $totalStokHabis }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-exclamation-triangle me-1"></i>Perlu diorder
                        </small>
                    </div>
                    <div class="stat-icon danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Total Stok -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-1">Total Stok Tersedia</p>
                        <h2 class="stat-value mb-0">{{ number_format($totalStok) }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-boxes me-1"></i>Unit barang
                        </small>
                    </div>
                    <div class="stat-icon success">
                        <i class="bi bi-boxes"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barang Masuk Hari Ini -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-1">Barang Masuk Hari Ini</p>
                        <h2 class="stat-value mb-0">{{ number_format($barangMasukHariIni) }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-arrow-down-circle me-1"></i>Unit masuk
                        </small>
                    </div>
                    <div class="stat-icon warning">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barang Keluar Hari Ini -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label mb-1">Barang Keluar Hari Ini</p>
                        <h2 class="stat-value mb-0">{{ number_format($barangKeluarHariIni) }}</h2>
                        <small class="text-muted">
                            <i class="bi bi-arrow-up-circle me-1"></i>Unit keluar
                        </small>
                    </div>
                    <div class="stat-icon danger">
                        <i class="bi bi-arrow-up-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Empty Stock Table -->
@if($totalStokHabis > 0)
<div class="card mb-4 border-danger">
    <div class="card-header bg-danger text-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-exclamation-triangle me-2"></i>
            Daftar Barang Yang Stoknya Habis
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th>Lokasi Rak</th>
                        <th class="text-center">Status Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangStokHabis as $barang)
                    <tr>
                        <td><code>{{ $barang->barcode }}</code></td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td><span class="badge bg-secondary">{{ $barang->lokasi_rak }}</span></td>
                        <td class="text-center">
                            <span class="badge bg-danger">
                                <i class="bi bi-x-circle me-1"></i>
                                HABIS
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif

<!-- Quick Scanner Barang Keluar -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-camera me-2"></i>Quick Scan Barang Keluar
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <!-- Scanner Section -->
            <div class="col-lg-6">
                <div class="scanner-container mb-3" id="dashboard-scanner-container">
                    <div id="dashboard-interactive" class="viewport"></div>
                    <div class="scanner-overlay"></div>
                    <div class="scanner-hint">
                        <i class="bi bi-camera-video me-1"></i>
                        Arahkan kamera ke barcode
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button onclick="startDashboardScanner()" class="btn btn-primary">
                        <i class="bi bi-play-fill me-2"></i>Mulai Scanner
                    </button>
                    <button onclick="stopDashboardScanner()" class="btn btn-outline-danger">
                        <i class="bi bi-stop-fill me-2"></i>Stop Scanner
                    </button>
                </div>
            </div>

            <!-- Scanned Items Section -->
            <div class="col-lg-6">
                <h6>Barang yang di-scan:</h6>
                <div id="scanned-items" class="list-group mb-3" style="max-height: 300px; overflow-y: auto;">
                    <div class="list-group-item text-muted text-center">
                        Belum ada barang yang di-scan
                    </div>
                </div>
                <div class="d-grid">
                    <button onclick="submitScannedItems()" class="btn btn-success" id="submit-scan-btn" disabled>
                        <i class="bi bi-check-circle me-2"></i>Simpan Semua Scan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let dashboardScannerActive = false;
let scannedItems = {};

function startDashboardScanner() {
    if (dashboardScannerActive) return;

    Quagga.offDetected();

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#dashboard-interactive'),
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
                addScannedItem(code);

                let audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleRYAOpjf38mWXB8dPnb08euSXy4SO4Lm6OK2Xx8VQXni7e2wYyEQPIbm6+uuZCEPJH/m7eqnYyAO');
                audio.play().catch(() => {});
            }
        });

        Quagga.start();
        dashboardScannerActive = true;
    });
}

function stopDashboardScanner() {
    if (!dashboardScannerActive) return;
    Quagga.stop();
    dashboardScannerActive = false;
}

function addScannedItem(barcode) {
    if (scannedItems[barcode]) {
        scannedItems[barcode].quantity++;
        updateScannedItemsDisplay();
    } else {
        fetch('{{ url("/search-barang") }}?q=' + encodeURIComponent(barcode))
            .then(response => response.json())
            .then(data => {
                const item = data.find(i => i.barcode === barcode);
                if (item) {
                    scannedItems[barcode] = {
                        barcode: barcode,
                        quantity: 1,
                        name: item.nama_barang,
                        stok: item.stok
                    };
                    updateScannedItemsDisplay();
                    playSuccessSound();
                } else {
                    // Silent - barcode not registered
                }
            })
            .catch(() => {
                // Silent - network error
            });
    }
}

function playSuccessSound() {
    let audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleRYAOpjf38mWXB8dPnb08euSXy4SO4Lm6OK2Xx8VQXni7e2wYyEQPIbm6+uuZCEPIH/m7eqnYyAO');
    audio.play().catch(() => {});
}

function updateScannedItemsDisplay() {
    const container = document.getElementById('scanned-items');
    const submitBtn = document.getElementById('submit-scan-btn');

    if (Object.keys(scannedItems).length === 0) {
        container.innerHTML = '<div class="list-group-item text-muted text-center">Belum ada barang yang di-scan</div>';
        submitBtn.disabled = true;
        return;
    }

    let html = '';
    for (const [barcode, item] of Object.entries(scannedItems)) {
        const stockStatus = item.stok >= item.quantity ? 'text-success' : 'text-danger';
        const stockIcon = item.stok >= item.quantity ? 'bi-check-circle' : 'bi-exclamation-triangle';
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${item.name}</strong><br>
                    <small class="text-muted">Barcode: ${barcode} | Qty: ${item.quantity}</small>
                </div>
                <div class="text-end">
                    <span class="${stockStatus}">
                        <i class="bi ${stockIcon} me-1"></i>
                        Stok: ${item.stok}
                    </span>
                    <button onclick="removeScannedItem('${barcode}')" class="btn btn-sm btn-outline-danger ms-2">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>
        `;
    }
    container.innerHTML = html;
    submitBtn.disabled = false;
}

function removeScannedItem(barcode) {
    delete scannedItems[barcode];
    updateScannedItemsDisplay();
}

function submitScannedItems() {
    if (Object.keys(scannedItems).length === 0) return;

    // Check if any item has insufficient stock
    const insufficientStock = Object.values(scannedItems).filter(item => item.stok < item.quantity);
    if (insufficientStock.length > 0) {
        alert('Beberapa barang memiliki stok tidak cukup:\n' +
              insufficientStock.map(item => `${item.name}: Stok ${item.stok}, Scan ${item.quantity}`).join('\n'));
        return;
    }

    const submitBtn = document.getElementById('submit-scan-btn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';

    fetch('{{ route("barang.keluar.quick.scan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            items: Object.values(scannedItems).map(item => ({
                barcode: item.barcode,
                quantity: item.quantity
            }))
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Barang keluar berhasil disimpan!');
            scannedItems = {};
            updateScannedItemsDisplay();
            // Reload page to update stats
            location.reload();
        } else {
            alert('Error: ' + data.message);
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan Semua Scan';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Simpan Semua Scan';
    });
}
</script>
@endsection
