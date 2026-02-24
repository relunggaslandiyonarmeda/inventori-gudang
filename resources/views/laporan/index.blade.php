@extends('layouts.main')

@section('title', 'Laporan')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </nav>
            <h1 class="page-title">Laporan</h1>
            <p class="page-subtitle">Pilih jenis laporan yang ingin dilihat</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Laporan Masuk -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100" style="border-top: 4px solid #10b981;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon success me-3">
                        <i class="bi bi-arrow-down-circle"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Laporan Barang Masuk</h5>
                        <small class="text-muted">Laporan penerimaan barang</small>
                    </div>
                </div>
                
                <form action="{{ route('laporan.masuk') }}" method="GET">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Bulan</label>
                            <select name="bulan" class="form-select">
                                @for($i=1; $i<=12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('M') }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Tahun</label>
                            <select name="tahun" class="form-select">
                                @for($y = date('Y'); $y >= date('Y')-5; $y--)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-eye me-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Keluar -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100" style="border-top: 4px solid #ef4444;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon danger me-3">
                        <i class="bi bi-arrow-up-circle"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Laporan Barang Keluar</h5>
                        <small class="text-muted">Laporan pengeluaran barang</small>
                    </div>
                </div>
                
                <form action="{{ route('laporan.keluar') }}" method="GET">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Bulan</label>
                            <select name="bulan" class="form-select">
                                @for($i=1; $i<=12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('M') }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Tahun</label>
                            <select name="tahun" class="form-select">
                                @for($y = date('Y'); $y >= date('Y')-5; $y--)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-eye me-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Gabungan -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100" style="border-top: 4px solid #4f46e5;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon primary me-3">
                        <i class="bi bi-arrow-left-right"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Laporan Gabungan</h5>
                        <small class="text-muted">Laporan semua transaksi</small>
                    </div>
                </div>
                
                <form action="{{ route('laporan.gabungan') }}" method="GET">
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small">Bulan</label>
                            <select name="bulan" class="form-select">
                                @for($i=1; $i<=12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('M') }}
                                </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small">Tahun</label>
                            <select name="tahun" class="form-select">
                                @for($y = date('Y'); $y >= date('Y')-5; $y--)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-eye me-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan per Rak -->
    <div class="col-12 col-md-6 col-xl-4">
        <div class="card h-100" style="border-top: 4px solid #f59e0b;">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="stat-icon warning me-3">
                        <i class="bi bi-boxes"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0">Laporan Barang per Rak</h5>
                        <small class="text-muted">Stok barang berdasarkan rak</small>
                    </div>
                </div>
                
                <form action="{{ route('laporan.rak') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label small">Filter Rak</label>
                        <select name="rak" class="form-select">
                            <option value="all">Semua Rak</option>
                            @php $rakOptions = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'O']; @endphp
                            @foreach($rakOptions as $rak)
                            <option value="{{ $rak }}">Rak {{ $rak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="bi bi-eye me-2"></i>Lihat Laporan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Info Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon info me-3">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Format Export Tersedia</h6>
                        <p class="text-muted mb-0 small">
                            Semua laporan dapat di-export dalam format 
                            <span class="badge bg-danger-subtle text-danger">PDF</span>, 
                            <span class="badge bg-success-subtle text-success">Excel</span>, dan 
                            <span class="badge bg-primary-subtle text-primary">CSV</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
