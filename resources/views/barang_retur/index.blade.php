@extends('layouts.main')

@section('title', 'Barang Retur')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Barang Retur</li>
                </ol>
            </nav>
            <h1 class="page-title">Barang Retur</h1>
            <p class="page-subtitle">Kembalikan barang yang tidak jadi diambil ke stok</p>
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

@if(Session::has('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-2"></i>
    {{ Session::get('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<!-- Form Card -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-arrow-return-left text-warning me-2"></i>
            Catat Retur Barang
        </h5>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.retur.store') }}" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-box-seam text-primary me-1"></i>
                        Pilih Barang Keluar
                    </label>
                    <select name="barang_keluar_id" id="barang_keluar_id" class="form-select select2" required>
                        <option value="">-- Pilih barang yang akan diretur --</option>
                        @foreach($barangKeluar as $bk)
                            @php
                                $remaining = isset($remainingQty[$bk->id]) ? $remainingQty[$bk->id] : $bk->jumlah_keluar;
                            @endphp
                            @if($remaining > 0)
                                <option value="{{ $bk->id }}" data-barcode="{{ $bk->barcode }}" data-sisa="{{ $remaining }}">
                                    {{ $bk->barcode }} - {{ $bk->masterBarang->nama_barang ?? '-' }} | Keluar: {{ $bk->jumlah_keluar }} | Sisa: {{ $remaining }} | Tgl: {{ $bk->tanggal }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="bi bi-plus-circle text-success me-1"></i>
                        Jumlah Retur
                    </label>
                    <input type="number" name="jumlah_retur" id="jumlah_retur" class="form-control" required min="1" placeholder="Jumlah">
                </div>
                
                <div class="col-md-3">
                    <label class="form-label">
                        <i class="bi bi-calendar text-info me-1"></i>
                        Tanggal Retur
                    </label>
                    <input type="date" name="tanggal_retur" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                
                <div class="col-12">
                    <label class="form-label">
                        <i class="bi bi-chat-text text-secondary me-1"></i>
                        Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Alasan retur..."></textarea>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-arrow-return-left me-2"></i>Simpan Retur
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="returTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="riwayat-tab-btn" data-bs-toggle="tab" data-bs-target="#riwayat" type="button">
            <i class="bi bi-clock-history me-2"></i>Riwayat Retur
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tersedia-tab-btn" data-bs-toggle="tab" data-bs-target="#tersedia" type="button">
            <i class="bi bi-box-seam me-2"></i>Barang Bisa Diretur
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="returTabContent">
    <!-- Riwayat Retur -->
    <div class="tab-pane fade show active" id="riwayat">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history text-info me-2"></i>
                    Riwayat Retur
                </h5>
                <span class="badge bg-primary">{{ count($retur) }} retur</span>
            </div>
            <div class="card-body">
                @if(count($retur) > 0)
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="table-retur">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Retur</th>
                                <th>Tanggal Retur</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($retur as $index => $r)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $r->barcode }}</span>
                                </td>
                                <td>{{ $r->masterBarang->nama_barang ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-success">{{ $r->jumlah_retur }}</span>
                                </td>
                                <td>{{ $r->tanggal_retur->format('d/m/Y') }}</td>
                                <td>{{ $r->keterangan ?? '-' }}</td>
                                <td>
                                    <form action="{{ route('barang.retur.destroy', $r->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus retur ini? Stok akan dikurangi kembali.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Belum ada riwayat retur</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Barang Bisa Diretur -->
    <div class="tab-pane fade" id="tersedia">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-box-seam text-warning me-2"></i>
                    Daftar Barang Keluar yang Bisa Diretur
                </h5>
            </div>
            <div class="card-body">
                @if(count($barangKeluar) > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="table-bisa-retur">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Barcode</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Keluar</th>
                                <th>Sisa Bisa Retur</th>
                                <th>Tanggal Keluar</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barangKeluar as $index => $bk)
                            @php
                                $remaining = isset($remainingQty[$bk->id]) ? $remainingQty[$bk->id] : $bk->jumlah_keluar;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $bk->barcode }}</span>
                                </td>
                                <td>{{ $bk->masterBarang->nama_barang ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-danger">{{ $bk->jumlah_keluar }}</span>
                                </td>
                                <td>
                                    @if($remaining > 0)
                                        <span class="badge bg-success">{{ $remaining }}</span>
                                    @else
                                        <span class="badge bg-secondary">0</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($bk->tanggal)->format('d/m/Y') }}</td>
                                <td>{{ $bk->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-3">Tidak ada barang keluar</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 45px;
        padding: 10px 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        top: 10px;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "-- Pilih barang yang akan diretur --",
            allowClear: true,
            width: '100%'
        });
        
        // Validasi jumlah retur tidak melebihi sisa
        $('#barang_keluar_id').on('change', function() {
            const option = $(this).find('option:selected');
            const sisa = option.data('sisa');
            $('#jumlah_retur').attr('max', sisa);
        });
    });
</script>
@endpush
@endsection
