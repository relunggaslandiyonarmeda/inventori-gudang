@extends('layouts.main')

@section('title', 'Backup Database')

@section('content')
<div class="page-header">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
        <div>
            <h1 class="page-title">
                <i class="bi bi-hdd-network"></i>
                Backup Database
            </h1>
            <p class="page-subtitle">Kelola backup database inventori gudang dan lihat waktu pembuatan backup.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <form action="{{ route('backup.database.create') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i>
                    Buat Backup Sekarang
                </button>
            </form>
            @if($latestBackup ?? null)
                <a href="{{ route('backup.database.download', $latestBackup['filename']) }}" class="btn btn-success">
                    <i class="bi bi-download"></i>
                    Unduh Backup Terakhir
                </a>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill alert-icon"></i>
    <div>
        <span class="alert-title">Berhasil</span>
        {{ session('success') }}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill alert-icon"></i>
    <div>
        <span class="alert-title">Gagal</span>
        {{ session('error') }}
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="stat-icon primary">
                    <i class="bi bi-clock-history"></i>
                </div>
                <div class="stat-label">Backup Terakhir</div>
                <div class="stat-value" style="font-size: 1.5rem;">
                    {{ $latestBackup['created_at'] ?? 'Belum ada' }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="stat-icon success">
                    <i class="bi bi-file-earmark-sql"></i>
                </div>
                <div class="stat-label">Total File Backup</div>
                <div class="stat-value" style="font-size: 1.5rem;">{{ count($backups) }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4">
        <div class="card">
            <div class="card-body">
                <div class="stat-icon warning">
                    <i class="bi bi-database"></i>
                </div>
                <div class="stat-label">Driver Database</div>
                <div class="stat-value" style="font-size: 1.5rem;">{{ strtoupper(config('database.default')) }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="bi bi-list-check"></i>
        Riwayat Backup Database
    </div>
    <div class="table-responsive">
        <table class="table table-align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama File</th>
                    <th>Waktu Dibuat</th>
                    <th>Ukuran</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($backups as $backup)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <div class="fw-semibold text-truncate" style="max-width: 360px;" title="{{ $backup['filename'] }}">
                            {{ $backup['filename'] }}
                        </div>
                    </td>
                    <td>{{ $backup['created_at'] }}</td>
                    <td><span class="badge bg-light text-dark">{{ $backup['size'] }}</span></td>
                    <td>
                        <div class="d-flex justify-content-end gap-2 flex-wrap">
                            <a href="{{ route('backup.database.download', $backup['filename']) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-download"></i>
                                Unduh
                            </a>
                            <form action="{{ route('backup.database.destroy', $backup['filename']) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus backup database ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                        <div class="mt-3">Belum ada backup database.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
