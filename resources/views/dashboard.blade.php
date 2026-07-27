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
    @if(auth()->user()->role === 'admin')
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

<!-- Panda PRJ 777 Scanner Barang Keluar -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">
            <i class="bi bi-upc-scan me-2"></i>Scanner Barang Keluar (Panda PRJ 777)
        </h5>
    </div>
    <div class="card-body">
        <div class="text-center mb-4">
            <div class="scanner-icon mb-3">
                <i class="bi bi-upc-scan text-primary" style="font-size: 4rem;"></i>
            </div>
            <h5 class="text-primary">Scanner Panda PRJ 777 Ready</h5>
            <p class="text-muted mb-0">Scan barcode barang, tambah keterangan, lalu klik "Proses" untuk mengurangi stok</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-upc text-primary me-1"></i>
                        Barcode (Scan dengan Panda PRJ 777)
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-upc-scan"></i></span>
                        <input type="text" id="scanner-input" class="form-control" 
                               placeholder="Scan barcode dengan Panda PRJ 777..." 
                               autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false">
                    </div>
                    <small class="text-muted">Tekan Enter atau gunakan tombol scan pada perangkat</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">
                        <i class="bi bi-upc text-primary me-1"></i>
                        Keterangan untuk Semua (Opsional)
                    </label>
                    <textarea id="global-keterangan" class="form-control" rows="2" placeholder="Keterangan untuk semua barang yang discan..."></textarea>
                    <small class="text-muted">Kosongkan jika ingin keterangan otomatis per barang</small>
                </div>

                <div id="pending-items" class="list-group mb-3" style="max-height: 200px; overflow-y: auto;">
                    <div class="list-group-item text-muted text-center">
                        Belum ada barang yang discan
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button onclick="processAllScans()" class="btn btn-success" id="process-btn" disabled>
                        <i class="bi bi-check-circle me-2"></i>Proses Semua Scan
                    </button>
                    <button onclick="clearPendingItems()" class="btn btn-outline-danger">
                        <i class="bi bi-trash me-2"></i>Bersihkan Daftar
                    </button>
                </div>
            </div>
            
            <div class="col-lg-6">
                <h6><i class="bi bi-clock-history me-2"></i>Riwayat Scan Terbaru</h6>
                <div id="scanner-history" class="list-group" style="max-height: 300px; overflow-y: auto;">
                    <div class="list-group-item text-muted text-center">
                        Belum ada data scan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let lastScannedCodes = {};
const SCAN_COOLDOWN = 1500;
let pendingItems = {};

const scannerHistory = [];

function addToHistory(barcode, nama_barang, success) {
    const time = new Date().toLocaleTimeString('id-ID');
    scannerHistory.unshift({
        barcode,
        nama_barang,
        success,
        time
    });
    
    if (scannerHistory.length > 10) scannerHistory.pop();
    
    updateHistoryDisplay();
}

function updateHistoryDisplay() {
    const container = document.getElementById('scanner-history');
    if (scannerHistory.length === 0) {
        container.innerHTML = '<div class="list-group-item text-muted text-center">Belum ada data scan</div>';
        return;
    }
    
    let html = '';
    scannerHistory.forEach(item => {
        const statusClass = item.success ? 'text-success' : 'text-danger';
        const statusIcon = item.success ? 'bi-check-circle' : 'bi-x-circle';
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>${item.nama_barang || item.barcode}</strong>
                    <br>
                    <small class="text-muted">
                        <code>${item.barcode}</code> | ${item.time}
                    </small>
                </div>
                <i class="bi ${statusIcon} ${statusClass}"></i>
            </div>
        `;
    });
    container.innerHTML = html;
}

document.getElementById('scanner-input').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const barcode = this.value.trim();
        if (barcode.length >= 2) {
            addToPendingItem(barcode);
        }
        this.value = '';
    }
});

function addToPendingItem(barcode) {
    const now = Date.now();
    
    if (lastScannedCodes[barcode] && (now - lastScannedCodes[barcode]) < SCAN_COOLDOWN) {
        return;
    }
    
    lastScannedCodes[barcode] = now;
    
    fetch('{{ url("/search-barang") }}?q=' + encodeURIComponent(barcode))
        .then(response => response.json())
        .then(data => {
            const item = data.find(i => i.barcode === barcode);
            if (item) {
                if (pendingItems[barcode]) {
                    pendingItems[barcode].quantity++;
                } else {
                    pendingItems[barcode] = {
                        barcode: barcode,
                        quantity: 1,
                        nama_barang: item.nama_barang,
                        stok: item.stok,
                        keterangan: ''
                    };
                }
                updatePendingItemsDisplay();
            }
        })
        .catch(() => {});
}

function updatePendingItemsDisplay() {
    const container = document.getElementById('pending-items');
    const processBtn = document.getElementById('process-btn');
    
    if (Object.keys(pendingItems).length === 0) {
        container.innerHTML = '<div class="list-group-item text-muted text-center">Belum ada barang yang discan</div>';
        processBtn.disabled = true;
        return;
    }
    
    processBtn.disabled = false;
    
    let html = '';
    for (const [barcode, item] of Object.entries(pendingItems)) {
        const stockStatus = item.stok >= item.quantity ? 'text-success' : 'text-danger';
        const stockIcon = item.stok >= item.quantity ? 'bi-check-circle' : 'bi-exclamation-triangle';
        html += `
            <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <strong>${item.nama_barang}</strong>
                        <br>
                        <small class="text-muted">
                            <code>${barcode}</code> | Qty: ${item.quantity}
                        </small>
                    </div>
                    <div class="text-end">
                        <span class="${stockStatus}">
                            <i class="bi ${stockIcon} me-1"></i>
                            Stok: ${item.stok}
                        </span>
                        <br>
                        <button onclick="removePendingItem('${barcode}')" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <input type="text" class="form-control form-control-sm" placeholder="Keterangan untuk ${item.nama_barang}..." value="${item.keterangan || ''}" onchange="pendingItems['${barcode}'].keterangan = this.value">
            </div>
        `;
    }
    container.innerHTML = html;
}

function removePendingItem(barcode) {
    delete pendingItems[barcode];
    updatePendingItemsDisplay();
}

function clearPendingItems() {
    pendingItems = {};
    updatePendingItemsDisplay();
}

function processAllScans() {
    if (Object.keys(pendingItems).length === 0) return;
    
    // Check stock
    const insufficient = Object.values(pendingItems).filter(item => item.stok < item.quantity);
    if (insufficient.length > 0) {
        if (!confirm('Beberapa barang stoknya tidak cukup:\n' + 
            insufficient.map(i => `${i.nama_barang}: stok ${i.stok}, butuh ${i.quantity}`).join('\n') + 
            '\n\nLanjutkan?')) {
            return;
        }
    }
    
    const processBtn = document.getElementById('process-btn');
    processBtn.disabled = true;
    processBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Menyimpan...';
    
    fetch('{{ route("barang.keluar.quick.scan") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            items: Object.values(pendingItems).map(item => ({
                barcode: item.barcode,
                quantity: item.quantity,
                keterangan: item.keterangan || document.getElementById('global-keterangan').value || ''
            }))
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Object.values(pendingItems).forEach(item => {
                addToHistory(item.barcode, item.nama_barang, true);
            });
            pendingItems = {};
            updatePendingItemsDisplay();
            processBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Berhasil!';
            setTimeout(() => {
                location.reload();
            }, 1000);
        } else {
            processBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Proses Semua Scan';
            processBtn.disabled = false;
            alert('Error: ' + data.message);
        }
    })
    .catch(() => {
        processBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Proses Semua Scan';
        processBtn.disabled = false;
    });
}

document.getElementById('scanner-input').focus();
</script>
@endsection
