@extends('layouts.main')

@section('title', 'Laporan Barang Rusak')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('laporan.index') }}">Laporan</a></li>
                    <li class="breadcrumb-item active">Barang Rusak</li>
                </ol>
            </nav>
            <h1 class="page-title">Laporan Barang Rusak</h1>
            <p class="page-subtitle">Data peralatan IT diajukan untuk discrap</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('laporan.rusak.pdf') }}" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf me-2"></i>PDF
            </a>
            <a href="{{ route('laporan.rusak.excel') }}" class="btn btn-success">
                <i class="bi bi-file-earmark-excel me-2"></i>Excel
            </a>
            <a href="{{ route('laporan.rusak.csv') }}" class="btn btn-warning">
                <i class="bi bi-file-earmark-spreadsheet me-2"></i>CSV
            </a>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-4">
        <div class="card bg-warning-subtle border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon warning me-3">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $barangRusaks->count() }}</h4>
                        <small class="text-muted">Total Barang Rusak</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card bg-success-subtle border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon success me-3">
                        <i class="bi bi-power"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $barangRusaks->where('kondisi_unit', 'hidup')->count() }}</h4>
                        <small class="text-muted">Kondisi Hidup</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card bg-danger-subtle border-0">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stat-icon danger me-3">
                        <i class="bi bi-power"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">{{ $barangRusaks->where('kondisi_unit', 'mati')->count() }}</h4>
                        <small class="text-muted">Kondisi Mati</small>
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
            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
            Daftar Barang Rusak
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Vehicle Group Code</th>
                        <th>Description</th>
                        <th class="text-center">Tahun Perolehan</th>
                        <th>Merek</th>
                        <th class="text-center">Foto</th>
                        <th>Lokasi Unit</th>
                        <th class="text-center">Kondisi Unit</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangRusaks as $index => $br)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $br->vehicle_group_code }}</td>
                        <td>{{ $br->description ?? '-' }}</td>
                        <td class="text-center">{{ $br->tahun_perolehan }}</td>
                        <td>{{ $br->merek }}</td>
                        <td class="text-center">
                            @if($br->foto)
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $br->id }}">
                                <i class="bi bi-image"></i>
                            </button>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $br->lokasi_unit }}</td>
                        <td class="text-center">
                            @if($br->kondisi_unit == 'hidup')
                            <span class="badge bg-success">Hidup</span>
                            @else
                            <span class="badge bg-danger">Mati</span>
                            @endif
                        </td>
                        <td>{{ $br->keterangan ?? '-' }}</td>
                    </tr>

                    @if($br->foto)
                    <div class="modal fade" id="fotoModal{{ $br->id }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Foto {{ $br->nomor }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset('storage/' . $br->foto) }}" alt="Foto {{ $br->nomor }}" class="img-fluid rounded" style="max-height: 400px; object-fit: contain;">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="text-muted">
                                <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem;"></i>
                                <span>Belum ada data barang rusak</span>
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
