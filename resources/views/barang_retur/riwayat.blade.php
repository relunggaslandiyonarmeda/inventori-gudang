@extends('layouts.main')

@section('title', 'Riwayat Barang Retur')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('barang.retur') }}">Barang Retur</a></li>
                    <li class="breadcrumb-item active">Riwayat</li>
                </ol>
            </nav>
            <h1 class="page-title">Riwayat Barang Retur</h1>
            <p class="page-subtitle">Histori transaksi retur barang</p>
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
        <div class="card stat-card" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label mb-1">Total Retur</p>
                        <h3 class="stat-value mb-0">{{ $retur->count() }}</h3>
                    </div>
                    <div class="stat-icon warning">
                        <i class="bi bi-arrow-return-left"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="border-left: 4px solid #4f46e5;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label mb-1">Total Dikembalikan</p>
                        <h3 class="stat-value mb-0">{{ number_format($totalQty) }}</h3>
                    </div>
                    <div class="stat-icon primary">
                        <i class="bi bi-box-seam"></i>
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
                        <p class="stat-label mb-1">User Aktif</p>
                        <h3 class="stat-value mb-0">{{ $totalUser }}</h3>
                    </div>
                    <div class="stat-icon success">
                        <i class="bi bi-people"></i>
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
            <i class="bi bi-clock-history text-warning me-2"></i>
            Riwayat Transaksi
        </h5>
        <form action="{{ route('barang.retur.riwayat') }}" method="GET" class="d-flex flex-wrap gap-2">
            <div class="input-group input-group-sm" style="width: 250px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari barcode atau nama..." class="form-control">
                @if(isset($search) && $search)
                <a href="{{ route('barang.retur.riwayat') }}" class="btn btn-outline-secondary" title="Clear">
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
                        <th><i class="bi bi-calendar me-1"></i>Tanggal</th>
                        <th><i class="bi bi-upc me-1"></i>Barcode</th>
                        <th><i class="bi bi-box me-1"></i>Nama Barang</th>
                        <th class="text-center"><i class="bi bi-arrow-return-left me-1"></i>Jumlah</th>
                        <th><i class="bi bi-person me-1"></i>User</th>
                        <th><i class="bi bi-chat-text me-1"></i>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($retur as $index => $item)
                    <tr>
                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal_retur)->format('d M Y') }}
                            </span>
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $item->barcode }}</code>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $item->masterBarang->nama_barang ?? '-' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-warning-subtle text-warning px-3 py-2">
                                <i class="bi bi-arrow-return-left me-1"></i>
                                {{ number_format($item->jumlah_retur) }}
                            </span>
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
                        <td colspan="7" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <h5>Tidak ada riwayat</h5>
                                <p class="mb-0">Belum ada transaksi retur barang</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($retur->count() > 0)
                <tfoot>
                    <tr class="table-light">
                        <td colspan="4" class="text-end fw-bold">TOTAL</td>
                        <td class="text-center">
                            <span class="badge bg-warning px-3 py-2">
                                <i class="bi bi-arrow-return-left me-1"></i>
                                {{ number_format($totalQty) }}
                            </span>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection