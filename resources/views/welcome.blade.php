<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Inventori Gudang IT') }}</title>

        <!-- Bootstrap 5 CSS (Local) -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.min.css') }}">
        <!-- Bootstrap Icons (Local) -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-icons.css') }}">
    </head>
    <body class="bg-light">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h1 class="mb-4">Inventori Gudang IT</h1>
                    <p class="lead">Sistem Manajemen Inventori Gudang</p>
                    <hr>
                    <div class="mt-4">
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap 5 JS (Local) -->
        <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
