@extends('layouts.main')

@section('title', 'Profil Saya - Inventori Gudang')

@section('content')
<!-- Popup/Toast Notification -->
@if(session('success'))
<div class="toast-container position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="5000" style="min-width: 300px;">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="bi bi-person-circle me-2"></i>
                        Profil Saya
                    </h5>
                </div>
                <div class="card-body">
                    @if($isAdmin)
                        <!-- Admin Info -->
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Akun Admin</strong> - Ini adalah akun administrator sistem. 
                            Beberapa fitur tidak tersedia untuk akun ini.
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 120px; height: 120px;">
                                    <i class="bi bi-person-fill text-white" style="font-size: 48px;"></i>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="140"><strong>Nama</strong></td>
                                        <td>: Administrator</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Username</strong></td>
                                        <td>: admin</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role</strong></td>
                                        <td>: <span class="badge bg-danger">Admin</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    @else
                        <!-- User Info -->
                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/profile_photos/' . $user->profile_photo) }}" 
                                         alt="Foto Profil" 
                                         class="rounded-circle mb-3"
                                         style="width: 150px; height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                         style="width: 150px; height: 150px;">
                                        <span class="text-white" style="font-size: 48px;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                @endif
                                
                                <!-- Photo Upload Form -->
                                <form action="{{ route('profile.update.photo') }}" method="POST" 
                                      enctype="multipart/form-data" class="mt-2">
                                    @csrf
                                    <div class="mb-2">
                                        <input type="file" name="profile_photo" 
                                               class="form-control form-control-sm" 
                                               accept="image/*" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-upload me-1"></i> Ganti Foto
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="140"><strong>Nama</strong></td>
                                        <td>: {{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Username</strong></td>
                                        <td>: {{ $user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td>: {{ $user->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role</strong></td>
                                        <td>: <span class="badge bg-info">User</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Change Password Form -->
                        <hr>
                        <h6 class="mb-3">
                            <i class="bi bi-key me-2"></i>Ubah Password
                        </h6>
                        <form action="{{ route('profile.update.password') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Password Saat Ini</label>
                                    <input type="password" name="current_password" 
                                           class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <input type="password" name="new_password" 
                                           class="form-control" required minlength="6">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Konfirmasi Password</label>
                                    <input type="password" name="new_password_confirmation" 
                                           class="form-control" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-key me-1"></i> Ubah Password
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Popup Notification for Password Change Success -->
@if(session('success'))
<div class="toast-container position-fixed top-50 start-50 translate-middle" style="z-index: 9999;">
    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="4000">
        <div class="toast-header bg-success text-white">
            <i class="bi bi-check-circle-fill me-2"></i>
            <strong class="me-auto">Berhasil</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-light">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<!-- Auto-show toast on page load -->
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const toastEl = document.querySelector('.toast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
});
</script>
@endif
@endsection
