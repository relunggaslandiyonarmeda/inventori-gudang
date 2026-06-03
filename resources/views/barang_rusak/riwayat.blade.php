@extends('layouts.main')

@section('title', 'Riwayat Barang Rusak')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('barang.rusak') }}">Barang Rusak</a></li>
                    <li class="breadcrumb-item active">Riwayat</li>
                </ol>
            </nav>
            <h1 class="page-title">Riwayat Barang Rusak</h1>
            <p class="page-subtitle">Histori pencatatan barang rusak</p>
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

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="border-left: 4px solid #ef4444;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label mb-1">Total Rusak</p>
                        <h3 class="stat-value mb-0">{{ $rusak->count() }}</h3>
                    </div>
                    <div class="stat-icon" style="background: #fef2f2; color: #ef4444;">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="border-left: 4px solid #10b981;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label mb-1">Hidup</p>
                        <h3 class="stat-value mb-0">{{ $rusak->where('kondisi_unit', 'hidup')->count() }}</h3>
                    </div>
                    <div class="stat-icon success">
                        <i class="bi bi-power"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="border-left: 4px solid #6b7280;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label mb-1">Mati</p>
                        <h3 class="stat-value mb-0">{{ $rusak->where('kondisi_unit', 'mati')->count() }}</h3>
                    </div>
                    <div class="stat-icon" style="background: #f3f4f6; color: #6b7280;">
                        <i class="bi bi-power"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0">
            <i class="bi bi-clock-history text-danger me-2"></i>
            Riwayat Pencatatan
        </h5>
        <form action="{{ route('barang.rusak.riwayat') }}" method="GET" class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari vehicle group atau deskripsi..." class="form-control">
                @if(isset($search) && $search)
                <a href="{{ route('barang.rusak.riwayat') }}" class="btn btn-outline-secondary" title="Clear">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th><i class="bi bi-car-front me-1"></i>Vehicle Group</th>
                        <th><i class="bi bi-card-text me-1"></i>Description</th>
                        <th class="text-center"><i class="bi bi-calendar me-1"></i>Tahun</th>
                        <th><i class="bi bi-tag me-1"></i>Merek</th>
                        <th><i class="bi bi-geo-alt me-1"></i>Lokasi</th>
                        <th class="text-center"><i class="bi bi-lightning me-1"></i>Kondisi</th>
                        <th><i class="bi bi-person me-1"></i>User</th>
                        <th><i class="bi bi-chat-text me-1"></i>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rusak as $index => $item)
                    <tr>
                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-semibold">{{ $item->vehicle_group_code }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $item->description ?? '-' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $item->tahun_perolehan }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $item->merek }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $item->lokasi_unit }}</span>
                        </td>
                        <td class="text-center">
                            @if($item->kondisi_unit == 'hidup')
                            <span class="badge bg-success">Hidup</span>
                            @else
                            <span class="badge bg-danger">Mati</span>
                            @endif
                        </td>
                        <td>
                            @if($item->createdBy)
                            <span class="badge bg-primary-subtle text-primary">
                                <i class="bi bi-person me-1"></i>{{ $item->createdBy->name ?? '-' }}
                            </span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $item->keterangan ?? '-' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <h5>Tidak ada riwayat</h5>
                                <p class="mb-0">Belum ada pencatatan barang rusak</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
