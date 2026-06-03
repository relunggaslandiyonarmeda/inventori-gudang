@extends('layouts.main')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="bi bi-people"></i>
            </span>
            Kelola Pengguna
        </h3>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Daftar Pengguna</h4>
                        <button type="button" class="btn btn-gradient-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                            <i class="bi bi-plus-lg"></i> Tambah User
                        </button>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, username, atau email..." value="{{ $search ?? '' }}">
                            </div>
                            <div class="col-md-3">
                                <select name="role" class="form-select">
                                    <option value="">Semua Role</option>
                                    <option value="admin" {{ ($role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ ($role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-secondary w-100">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Nama</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $key => $user)
                                <tr>
                                    <td>{{ $users->firstItem() + $key }}</td>
                                    <td>
                                        @if($user->profile_photo)
                                        <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" 
                                             alt="Foto" class="rounded-circle" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                        <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <span class="text-white small">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                        </div>
                                        @endif
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email ?? '-' }}</td>
                                    <td>
                                        @if($user->role === 'admin')
                                        <span class="badge bg-danger">Admin</span>
                                        @else
                                        <span class="badge bg-info">User</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#hapusUserModal{{ $user->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Edit Modal -->
                                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form method="POST" action="{{ route('users.update', $user->id) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama</label>
                                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Username</label>
                                                        <input type="text" name="username" class="form-control" value="{{ $user->username }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Email</label>
                                                        <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Password (Kosongkan jika tidak diganti)</label>
                                                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Password Konfirmasi</label>
                                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi password">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Role</label>
                                                        <select name="role" class="form-select" required onchange="toggleEditMenuPermissions('editUserModal{{ $user->id }}', this.value)">
                                                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3 edit-menu-section" style="display: {{ $user->role === 'user' ? 'block' : 'none' }};">
                                                        <label class="form-label">Akses Menu</label>
                                                        <div class="border rounded p-3">
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" name="menu_permissions[]" value="master_barang" class="form-check-input" id="edit_checkMasterBarang{{ $user->id }}" {{ in_array('master_barang', $user->menu_permissions ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="edit_checkMasterBarang{{ $user->id }}">
                                                                    <i class="bi bi-box-seam me-1"></i> Master Barang
                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" name="menu_permissions[]" value="barang_masuk" class="form-check-input" id="edit_checkBarangMasuk{{ $user->id }}" {{ in_array('barang_masuk', $user->menu_permissions ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="edit_checkBarangMasuk{{ $user->id }}">
                                                                    <i class="bi bi-arrow-down-circle me-1"></i> Barang Masuk
                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" name="menu_permissions[]" value="barang_keluar" class="form-check-input" id="edit_checkBarangKeluar{{ $user->id }}" {{ in_array('barang_keluar', $user->menu_permissions ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="edit_checkBarangKeluar{{ $user->id }}">
                                                                    <i class="bi bi-arrow-up-circle me-1"></i> Barang Keluar
                                                                </label>
                                                            </div>
                                                            <div class="form-check mb-2">
                                                                <input type="checkbox" name="menu_permissions[]" value="barang_retur" class="form-check-input" id="edit_checkBarangRetur{{ $user->id }}" {{ in_array('barang_retur', $user->menu_permissions ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="edit_checkBarangRetur{{ $user->id }}">
                                                                    <i class="bi bi-arrow-return-left me-1"></i> Barang Retur
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input type="checkbox" name="menu_permissions[]" value="barang_rusak" class="form-check-input" id="edit_checkBarangRusak{{ $user->id }}" {{ in_array('barang_rusak', $user->menu_permissions ?? []) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="edit_checkBarangRusak{{ $user->id }}">
                                                                    <i class="bi bi-exclamation-triangle me-1"></i> Barang Rusak
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted">Pilih menu yang dapat diakses oleh user</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete Modal -->
                                <div class="modal fade" id="hapusUserModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Hapus User</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menghapus user <strong>{{ $user->name }}</strong>?</p>
                                                <p class="text-danger">Tindakan ini tidak dapat dibatalkan.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Tidak ada data user</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambah User Modal -->
<div class="modal fade" id="tambahUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email (Opsional)</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required id="addRoleSelect" onchange="toggleMenuPermissions()">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3" id="menuPermissionsSection" style="display: none;">
                        <label class="form-label">Akses Menu</label>
                        <div class="border rounded p-3">
                            <div class="form-check mb-2">
                                <input type="checkbox" name="menu_permissions[]" value="master_barang" class="form-check-input" id="checkMasterBarang">
                                <label class="form-check-label" for="checkMasterBarang">
                                    <i class="bi bi-box-seam me-1"></i> Master Barang
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="menu_permissions[]" value="barang_masuk" class="form-check-input" id="checkBarangMasuk">
                                <label class="form-check-label" for="checkBarangMasuk">
                                    <i class="bi bi-arrow-down-circle me-1"></i> Barang Masuk
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="menu_permissions[]" value="barang_keluar" class="form-check-input" id="checkBarangKeluar">
                                <label class="form-check-label" for="checkBarangKeluar">
                                    <i class="bi bi-arrow-up-circle me-1"></i> Barang Keluar
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input type="checkbox" name="menu_permissions[]" value="barang_retur" class="form-check-input" id="checkBarangRetur">
                                <label class="form-check-label" for="checkBarangRetur">
                                    <i class="bi bi-arrow-return-left me-1"></i> Barang Retur
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="menu_permissions[]" value="barang_rusak" class="form-check-input" id="checkBarangRusak">
                                <label class="form-check-label" for="checkBarangRusak">
                                    <i class="bi bi-exclamation-triangle me-1"></i> Barang Rusak
                                </label>
                            </div>
                        </div>
                        <small class="text-muted">Pilih menu yang dapat diakses oleh user</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleMenuPermissions() {
    var roleSelect = document.getElementById('addRoleSelect');
    var menuSection = document.getElementById('menuPermissionsSection');
    if (roleSelect.value === 'user') {
        menuSection.style.display = 'block';
    } else {
        menuSection.style.display = 'none';
    }
}

function toggleEditMenuPermissions(modalId, role) {
    var modal = document.getElementById(modalId);
    var menuSection = modal.querySelector('.edit-menu-section');
    if (role === 'user') {
        menuSection.style.display = 'block';
    } else {
        menuSection.style.display = 'none';
    }
}
</script>
@endsection
