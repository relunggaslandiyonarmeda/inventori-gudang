@extends('layouts.main')

@section('title', 'Barang Rusak')

@section('content')
<!-- Page Header -->
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Barang Rusak</li>
                </ol>
            </nav>
            <h1 class="page-title">Barang Rusak</h1>
            <p class="page-subtitle">Kelola data barang rusak</p>
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

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" id="barangRusakTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tambah-tab-btn" data-bs-toggle="tab" data-bs-target="#tambah" type="button">
            <i class="bi bi-plus-circle me-2"></i>Tambah Data
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="list-tab-btn" data-bs-toggle="tab" data-bs-target="#list" type="button">
            <i class="bi bi-list-ul me-2"></i>Daftar Barang Rusak
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="barangRusakTabContent">
    <!-- Tambah Tab -->
    <div class="tab-pane fade show active" id="tambah">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle text-primary me-2"></i>
                    Tambah Barang Rusak
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('barang.rusak.store') }}" method="POST" enctype="multipart/form-data" id="barang-rusak-form">
                    @csrf
                    <div class="row g-4">
                        <!-- Row 1 -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-car-front text-primary me-1"></i>
                                Vehicle Group Code
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-car-front"></i></span>
                                <input type="text" name="vehicle_group_code" class="form-control" list="vehicle-group-list" required placeholder="Masukkan atau pilih kode">
                                <datalist id="vehicle-group-list">
                                    @foreach($vehicleGroups as $vg)
                                    <option value="{{ $vg->kode }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-geo-alt text-primary me-1"></i>
                                Lokasi Unit
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="lokasi_unit" class="form-control" list="lokasi-list" required placeholder="Masukkan atau pilih lokasi">
                                <datalist id="lokasi-list">
                                    @foreach($lokasiUnits as $lu)
                                    <option value="{{ $lu->lokasi }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-text-paragraph text-primary me-1"></i>
                                Description
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                <textarea name="description" class="form-control" rows="2" placeholder="Masukkan deskripsi"></textarea>
                            </div>
                        </div>

                        <!-- Row 3 -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar text-primary me-1"></i>
                                Tahun Perolehan
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                <input type="number" name="tahun_perolehan" class="form-control" required min="1900" placeholder="Masukkan tahun perolehan">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-tag text-primary me-1"></i>
                                Merek
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                <input type="text" name="merek" class="form-control" list="merek-list-add" required placeholder="Masukkan atau pilih merek">
                                <datalist id="merek-list-add">
                                    @foreach($masterBarangs as $mb)
                                    <option value="{{ $mb->nama_barang }}">
                                    @endforeach
                                </datalist>
                            </div>
                        </div>

                        <!-- Row 4 -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-camera text-primary me-1"></i>
                                Foto (Kamera/Galeri)
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-camera"></i></span>
                                <input type="file" name="foto" class="form-control" accept="image/*" capture="environment">
                            </div>
                            <small class="text-muted">Bisa mengambil foto langsung atau memilih dari galeri</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-power text-primary me-1"></i>
                                Kondisi Unit
                            </label>
                            <div class="d-flex gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi_unit" id="kondisi_hidup" value="hidup" required>
                                    <label class="form-check-label" for="kondisi_hidup">
                                        <span class="badge bg-success"><i class="bi bi-power me-1"></i>Hidup</span>
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="kondisi_unit" id="kondisi_mati" value="mati" required>
                                    <label class="form-check-label" for="kondisi_mati">
                                        <span class="badge bg-danger"><i class="bi bi-power me-1"></i>Mati</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Row 5 -->
                        <div class="col-12">
                            <label class="form-label">
                                <i class="bi bi-card-text text-primary me-1"></i>
                                Keterangan
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                <textarea name="keterangan" class="form-control" rows="2" placeholder="Masukkan keterangan (opsional)"></textarea>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-save me-2"></i>Simpan Barang Rusak
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- List Tab -->
    <div class="tab-pane fade" id="list">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Daftar Barang Rusak
                </h5>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 200px;">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="search-table" placeholder="Cari..." class="form-control">
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="barang-rusak-table">
                        <thead>
                            <tr>
                                <th class="text-center"><i class="bi bi-hash me-1"></i>No</th>
                                <th><i class="bi bi-upc me-1"></i>Nomor</th>
                                <th><i class="bi bi-car-front me-1"></i>Vehicle Group Code</th>
                                <th><i class="bi bi-text-paragraph me-1"></i>Description</th>
                                <th class="text-center"><i class="bi bi-calendar me-1"></i>Tahun</th>
                                <th><i class="bi bi-tag me-1"></i>Merek</th>
                                <th class="text-center"><i class="bi bi-image me-1"></i>Foto</th>
                                <th><i class="bi bi-geo-alt me-1"></i>Lokasi Unit</th>
                                <th class="text-center"><i class="bi bi-power me-1"></i>Kondisi</th>
                                <th><i class="bi bi-card-text me-1"></i>Keterangan</th>
                                <th class="text-center"><i class="bi bi-gear me-1"></i>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($barangRusaks as $index => $br)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <code class="bg-warning-subtle text-warning px-2 py-1 rounded">{{ $br->nomor }}</code>
                                </td>
                                <td>{{ $br->vehicle_group_code }}</td>
                                <td>{{ $br->description ?? '-' }}</td>
                                <td class="text-center">{{ $br->tahun_perolehan }}</td>
                                <td>{{ $br->merek }}</td>
                                <td class="text-center">
                                    @if($br->foto)
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $br->id }}">
                                        <i class="bi bi-image"></i> Lihat
                                    </button>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>{{ $br->lokasi_unit }}</td>
                                <td class="text-center">
                                    @if($br->kondisi_unit == 'hidup')
                                    <span class="badge bg-success-subtle text-success px-3 py-2">
                                        <i class="bi bi-power me-1"></i>Hidup
                                    </span>
                                    @else
                                    <span class="badge bg-danger-subtle text-danger px-3 py-2">
                                        <i class="bi bi-power me-1"></i>Mati
                                    </span>
                                    @endif
                                </td>
                                <td>{{ $br->keterangan ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $br->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $br->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Foto Modal -->
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

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal{{ $br->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-pencil text-warning me-2"></i>
                                                Edit {{ $br->nomor }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('barang.rusak.update', $br->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            <i class="bi bi-car-front text-primary me-1"></i>
                                                            Vehicle Group Code
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-car-front"></i></span>
                                                            <input type="text" name="vehicle_group_code" class="form-control" value="{{ $br->vehicle_group_code }}" list="vehicle-group-list-edit-{{ $br->id }}" required>
                                                            <datalist id="vehicle-group-list-edit-{{ $br->id }}">
                                                                @foreach($vehicleGroups as $vg)
                                                                <option value="{{ $vg->kode }}">
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            <i class="bi bi-geo-alt text-primary me-1"></i>
                                                            Lokasi Unit
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                                            <input type="text" name="lokasi_unit" class="form-control" value="{{ $br->lokasi_unit }}" list="lokasi-list-edit-{{ $br->id }}" required>
                                                            <datalist id="lokasi-list-edit-{{ $br->id }}">
                                                                @foreach($lokasiUnits as $lu)
                                                                <option value="{{ $lu->lokasi }}">
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">
                                                            <i class="bi bi-text-paragraph text-primary me-1"></i>
                                                            Description
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                                            <textarea name="description" class="form-control" rows="2">{{ $br->description }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            <i class="bi bi-calendar text-primary me-1"></i>
                                                            Tahun Perolehan
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                                            <input type="number" name="tahun_perolehan" class="form-control" value="{{ $br->tahun_perolehan }}" required min="1900" placeholder="Masukkan tahun perolehan">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            <i class="bi bi-tag text-primary me-1"></i>
                                                            Merek
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                                            <input type="text" name="merek" class="form-control" value="{{ $br->merek }}" list="merek-list" required>
                                                            <datalist id="merek-list">
                                                                @foreach($masterBarangs as $mb)
                                                                <option value="{{ $mb->nama_barang }}">
                                                                @endforeach
                                                            </datalist>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            <i class="bi bi-image text-primary me-1"></i>
                                                            Foto
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-image"></i></span>
                                                            <input type="file" name="foto" class="form-control" accept="image/*">
                                                        </div>
                                                        @if($br->foto)
                                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                                        @endif
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            <i class="bi bi-power text-primary me-1"></i>
                                                            Kondisi Unit
                                                        </label>
                                                        <div class="d-flex gap-4 mt-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_unit" id="kondisi_hidup_edit{{ $br->id }}" value="hidup" {{ $br->kondisi_unit == 'hidup' ? 'checked' : '' }} required>
                                                                <label class="form-check-label" for="kondisi_hidup_edit{{ $br->id }}">
                                                                    <span class="badge bg-success"><i class="bi bi-power me-1"></i>Hidup</span>
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio" name="kondisi_unit" id="kondisi_mati_edit{{ $br->id }}" value="mati" {{ $br->kondisi_unit == 'mati' ? 'checked' : '' }} required>
                                                                <label class="form-check-label" for="kondisi_mati_edit{{ $br->id }}">
                                                                    <span class="badge bg-danger"><i class="bi bi-power me-1"></i>Mati</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <label class="form-label">
                                                            <i class="bi bi-card-text text-primary me-1"></i>
                                                            Keterangan
                                                        </label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                                                            <textarea name="keterangan" class="form-control" rows="2">{{ $br->keterangan }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-warning">
                                                    <i class="bi bi-save me-2"></i>Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $br->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="bi bi-exclamation-triangle text-danger me-2"></i>
                                                Hapus {{ $br->nomor }}?
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah Anda yakin ingin menghapus data barang rusak <strong>{{ $br->nomor }}</strong>?</p>
                                            <p class="text-muted mb-0">Tindakan ini tidak dapat dibatalkan.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="{{ route('barang.rusak.destroy', $br->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="bi bi-trash me-2"></i>Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">
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
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Simple search functionality
    document.getElementById('search-table').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const table = document.getElementById('barang-rusak-table');
        const rows = table.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endsection
