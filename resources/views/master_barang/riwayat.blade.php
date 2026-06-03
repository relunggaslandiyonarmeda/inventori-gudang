@extends('layouts.main')

@section('title', 'Riwayat Master Barang')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('master.barang') }}">Master Barang</a></li>
                    <li class="breadcrumb-item active">Riwayat</li>
                </ol>
            </nav>
            <h1 class="page-title">Riwayat Master Barang</h1>
            <p class="page-subtitle">Histori perubahan data barang</p>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-2"></i>
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Data Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h5 class="mb-0">
            <i class="bi bi-clock-history text-info me-2"></i>
            Riwayat Perubahan
        </h5>
        <form action="{{ route('master.barang.riwayat') }}" method="GET" class="d-flex flex-wrap gap-2">
            <select name="filter" class="form-select form-select-sm" style="width: 150px;" onchange="this.form.submit()">
                <option value="semua" {{ $filter == 'semua' ? 'selected' : '' }}>Semua</option>
                <option value="dibuat" {{ $filter == 'dibuat' ? 'selected' : '' }}>Dibuat</option>
                <option value="diupdate" {{ $filter == 'diupdate' ? 'selected' : '' }}>Diupdate</option>
            </select>
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari barcode atau nama..." class="form-control">
                @if(isset($search) && $search || isset($filter) && $filter != 'semua')
                <a href="{{ route('master.barang.riwayat') }}" class="btn btn-outline-secondary" title="Clear">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="barang-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th><i class="bi bi-upc me-1"></i>Barcode</th>
                        <th><i class="bi bi-box me-1"></i>Nama Barang</th>
                        <th class="text-center"><i class="bi bi-archive me-1"></i>Stok</th>
                        <th class="text-center"><i class="bi bi-geo-alt me-1"></i>Rak</th>
                        <th class="text-center"><i class="bi bi-clock-history me-1"></i>Aksi</th>
                        <th><i class="bi bi-person me-1"></i>User</th>
                        <th><i class="bi bi-calendar me-1"></i>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr>
                        <td class="text-center text-muted">{{ $loop->iteration }}</td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $barang->barcode }}</code>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $barang->nama_barang }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-secondary">{{ $barang->stok }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary">{{ $barang->lokasi_rak }}</span>
                        </td>
                        <td class="text-center">
                            @if($barang->createdBy && $barang->created_by == $barang->updated_by)
                            <span class="badge bg-success-subtle text-success">
                                <i class="bi bi-plus-circle me-1"></i>Dibuat
                            </span>
                            @elseif($barang->updatedBy)
                            <span class="badge bg-warning-subtle text-warning">
                                <i class="bi bi-pencil me-1"></i>Update
                            </span>
                            @else
                            <span class="badge bg-success-subtle text-success">
                                <i class="bi bi-plus-circle me-1"></i>Dibuat
                            </span>
                            @endif
                            @if($barang->trashed())
                            <br>
                            <span class="badge bg-danger-subtle text-danger mt-1">
                                <i class="bi bi-trash me-1"></i>Terbuang
                            </span>
                            @endif
                        </td>
                        <td>
                            @if($barang->updatedBy)
                            <span class="text-primary">{{ $barang->updatedBy->name ?? '-' }}</span>
                            @elseif($barang->createdBy)
                            <span class="text-primary">{{ $barang->createdBy->name ?? '-' }}</span>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                            @if($barang->trashed())
                            <br>
                            <form action="{{ route('master.barang.restore', $barang->barcode) }}" method="POST" class="d-inline mt-1" onsubmit="return confirm('Pulihkan barang ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Pulihkan">
                                    <i class="bi bi-arrow-counterclockwise"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted small">
                                {{ $barang->updated_at ? $barang->updated_at->format('d M Y H:i') : ($barang->created_at ? $barang->created_at->format('d M Y H:i') : '-') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <h5>Tidak ada riwayat</h5>
                                <p class="mb-0">Belum ada perubahan data barang</p>
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
                {{ $barangs->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
