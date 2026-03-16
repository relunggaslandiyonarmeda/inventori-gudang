@extends('layouts.main')

@section('title', 'Laporan Barang per Rak')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Barang per Rak</li>
                </ol>
            </nav>
            <h1 class="page-title">Laporan Barang per Rak</h1>
            <p class="page-subtitle">Kelompokkan barang berdasarkan lokasi rak</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.rak.pdf', ['rak' => $rak]) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('laporan.rak.excel', ['rak' => $rak]) }}" class="btn btn-success">
                <i class="bi bi-file-excel me-1"></i>Excel
            </a>
            <a href="{{ route('laporan.rak.csv', ['rak' => $rak]) }}" class="btn btn-primary">
                <i class="bi bi-file-spreadsheet me-1"></i>CSV
            </a>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.rak') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-box-seam text-primary me-1"></i>
                        Filter Rak
                    </label>
                    <select name="rak" class="form-select">
                        <option value="all" {{ $rak === 'all' ? 'selected' : '' }}>Semua Rak</option>
                        @foreach($rakOptions as $rakOpt)
                        <option value="{{ $rakOpt }}" {{ $rak === $rakOpt ? 'selected' : '' }}>
                            Rak {{ $rakOpt }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Stats -->
<div class="card mb-4">
    <div class="card-body">
        <div class="d-flex gap-4 flex-wrap">
            <div class="text-start">
                <p class="text-muted mb-1">Total Rak</p>
                <h4 class="mb-0 text-primary">{{ $barangs->count() }}</h4>
            </div>
            <div class="text-start">
                <p class="text-muted mb-1">Total Barang</p>
                <h4 class="mb-0 text-success">{{ number_format($totalBarang) }}</h4>
            </div>
            <div class="text-start">
                <p class="text-muted mb-1">Total Stok</p>
                <h4 class="mb-0 text-warning">{{ number_format($totalStok) }}</h4>
            </div>
        </div>
    </div>
</div>

<!-- Data per Rak -->
@foreach($barangs as $rakName => $items)
<div class="card mb-4">
    <div class="card-header bg-white py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <span class="badge bg-primary fs-6 me-2">
                    <i class="bi bi-geo-alt me-1"></i>Rak {{ $rakName }}
                </span>
                <span class="text-muted fs-6">{{ $items->count() }} barang</span>
            </h5>
            <span class="badge bg-light text-dark">
                Stok: {{ number_format($items->sum('stok')) }}
            </span>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach($items as $item)
                    <tr>
                        <td class="text-center text-muted">{{ $no++ }}</td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $item->barcode }}</code>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $item->nama_barang }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $item->stok > 10 ? 'bg-success-subtle text-success' : ($item->stok > 0 ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }} px-3 py-2">
                                {{ number_format($item->stok) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr class="fw-semibold">
                        <td colspan="3" class="text-end">Total Stok Rak {{ $rakName }}:</td>
                        <td class="text-center">
                            <span class="badge bg-primary px-3 py-2">
                                {{ number_format($items->sum('stok')) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endforeach

@if($barangs->count() === 0)
<div class="card">
    <div class="card-body py-5 text-center">
        <div class="text-muted">
            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
            <h5>Tidak ada data</h5>
            <p class="mb-0">Tidak ada barang ditemukan</p>
        </div>
    </div>
</div>
@endif
@endsection
