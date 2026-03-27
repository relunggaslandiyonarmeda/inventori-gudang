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

<!-- Quick Actions -->
<div class="row g-4">
    <div class="col-12">
        <h5 class="fw-bold mb-3">
            <i class="bi bi-lightning-charge text-warning me-2"></i>
            Aksi Cepat
        </h5>
    </div>
    
    @if(Session::get('user_role') === 'admin')
    <div class="col-12 col-md-6 col-xl-3">
        <a href="{{ route('master.barang') }}" class="text-decoration-none">
            <div class="card h-100" style="border-left: 4px solid #4f46e5;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon primary me-3">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Master Barang</h6>
                            <p class="text-muted small mb-0">Kelola data barang</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endif

    <div class="col-12 col-md-6 col-xl-3">
        <a href="{{ route('barang.masuk') }}" class="text-decoration-none">
            <div class="card h-100" style="border-left: 4px solid #10b981;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon success me-3">
                            <i class="bi bi-arrow-down-circle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Barang Masuk</h6>
                            <p class="text-muted small mb-0">Tambah stok barang</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <a href="{{ route('barang.keluar') }}" class="text-decoration-none">
            <div class="card h-100" style="border-left: 4px solid #ef4444;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon danger me-3">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Barang Keluar</h6>
                            <p class="text-muted small mb-0">Kurangi stok barang</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-12 col-md-6 col-xl-3">
        <a href="{{ route('laporan.index') }}" class="text-decoration-none">
            <div class="card h-100" style="border-left: 4px solid #f59e0b;">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon warning me-3">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Laporan</h6>
                            <p class="text-muted small mb-0">Lihat laporan transaksi</p>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Info Section -->
<div class="row g-4 mt-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);">
            <div class="card-body text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h5 class="fw-bold mb-2">
                            <i class="bi bi-lightbulb me-2"></i>
                            Tips Penggunaan
                        </h5>
                        <p class="mb-0 opacity-75">
                            Gunakan fitur scan barcode untuk mempercepat proses input data. 
                            Pastikan barcode barang terdaftar di Master Barang sebelum melakukan transaksi masuk atau keluar.
                        </p>
                    </div>
                    @if(Session::get('user_role') === 'admin')
                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                        <a href="{{ route('master.barang') }}" class="btn btn-light">
                            <i class="bi bi-plus-circle me-1"></i>
                            Tambah Barang Baru
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
