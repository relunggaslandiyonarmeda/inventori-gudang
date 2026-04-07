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

<!-- Rest of dashboard content remains below -->

@endsection
