@extends('layouts.main')

@section('title', 'Laporan Barang Masuk')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Barang Masuk</li>
                </ol>
            </nav>
            <h1 class="page-title">Laporan Barang Masuk</h1>
            <p class="page-subtitle">Periode: {{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.masuk.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-danger">
                <i class="bi bi-file-pdf me-1"></i>PDF
            </a>
            <a href="{{ route('laporan.masuk.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-success">
                <i class="bi bi-file-excel me-1"></i>Excel
            </a>
            <a href="{{ route('laporan.masuk.csv', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-primary">
                <i class="bi bi-file-spreadsheet me-1"></i>CSV
            </a>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('laporan.masuk') }}" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="bi bi-calendar text-primary me-1"></i>
                        Bulan
                    </label>
                    <select name="bulan" class="form-select">
                        @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="bi bi-calendar3 text-primary me-1"></i>
                        Tahun
                    </label>
                    <select name="tahun" class="form-select">
                        @for($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
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
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card" style="border-left: 4px solid #10b981;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label mb-1">Total Transaksi</p>
                        <h3 class="stat-value mb-0">{{ $barangMasuks->count() }}</h3>
                    </div>
                    <div class="stat-icon success">
                        <i class="bi bi-receipt"></i>
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
                        <p class="stat-label mb-1">Total Barang Masuk</p>
                        <h3 class="stat-value mb-0">{{ number_format($totalMasuk) }}</h3>
                    </div>
                    <div class="stat-icon primary">
                        <i class="bi bi-box-seam"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card" style="border-left: 4px solid #f59e0b;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="stat-label mb-1">Rata-rata per Transaksi</p>
                        <h3 class="stat-value mb-0">{{ $barangMasuks->count() > 0 ? round($totalMasuk / $barangMasuks->count()) : 0 }}</h3>
                    </div>
                    <div class="stat-icon warning">
                        <i class="bi bi-calculator"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-table text-primary me-2"></i>
            Detail Transaksi
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 60px;">No</th>
                        <th>Tanggal</th>
                        <th>Barcode</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangMasuks as $index => $item)
                    <tr>
                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-light text-dark">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </span>
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $item->barcode }}</code>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $item->masterBarang->nama_barang ?? '-' }}</span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-success-subtle text-success px-3 py-2">
                                <i class="bi bi-plus me-1"></i>
                                {{ number_format($item->jumlah_masuk) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $item->keterangan ?? '-' }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                                <h5>Tidak ada data</h5>
                                <p class="mb-0">Tidak ada transaksi barang masuk pada periode ini</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($barangMasuks->count() > 0)
                <tfoot>
                    <tr class="table-light">
                        <td colspan="4" class="text-end fw-bold">TOTAL</td>
                        <td class="text-center">
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-plus me-1"></i>
                                {{ number_format($totalMasuk) }}
                            </span>
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
