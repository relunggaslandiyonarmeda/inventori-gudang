<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Inventori Gudang IT')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .scanner-container video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }
        .scanner-container {
            width: 100%;
            max-width: 400px;
            height: 300px;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            margin: 0 auto;
        }
        #interactive.viewport {
            width: 100%;
            height: 100%;
        }
        #interactive.viewport canvas, video {
            width: 100%;
            height: 100%;
        }
        /* Mobile optimized */
        @media (max-width: 768px) {
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .btn-mobile-full {
                width: 100%;
                margin-bottom: 0.5rem;
            }
            .card-mobile {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            .text-mobile {
                font-size: 0.875rem;
            }
            .input-mobile {
                font-size: 16px; /* Prevents zoom on iOS */
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    @if(Session::get('admin_logged_in'))
    <!-- Mobile Header -->
    <nav class="bg-blue-600 text-white shadow-lg fixed w-full top-0 z-50">
        <div class="container mx-auto px-2 sm:px-4">
            <div class="flex items-center justify-between py-2 sm:py-4">
                <div class="flex items-center space-x-2">
                    <button id="mobileMenuBtn" class="md:hidden p-2 hover:bg-blue-700 rounded" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-lg sm:text-xl"></i>
                    </button>
                    <a href="{{ route('dashboard') }}" class="text-base sm:text-xl font-bold">
                        <i class="fas fa-warehouse"></i> <span class="hidden sm:inline">Inventori Gudang IT</span><span class="sm:hidden">Inventori</span>
                    </a>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <span class="hidden sm:inline text-sm">{{ Session::get('admin_username') }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 px-2 sm:px-3 py-1 rounded text-sm">
                            <i class="fas fa-sign-out-alt"></i> <span class="hidden sm:inline">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden" onclick="toggleMobileMenu()">
        <div class="bg-white absolute left-0 top-0 h-full w-64 shadow-lg" onclick="event.stopPropagation()">
            <div class="p-4 bg-blue-600 text-white">
                <div class="font-bold text-lg">Menu</div>
                <div class="text-sm opacity-80">{{ Session::get('admin_username') }}</div>
            </div>
            <div class="py-2">
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 hover:bg-gray-100 {{ Request::is('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                    <i class="fas fa-home w-6"></i> Dashboard
                </a>
                <a href="{{ route('master.barang') }}" class="block px-4 py-3 hover:bg-gray-100 {{ Request::is('master-barang*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                    <i class="fas fa-box w-6"></i> Master Barang
                </a>
                <a href="{{ route('barang.masuk') }}" class="block px-4 py-3 hover:bg-gray-100 {{ Request::is('barang-masuk*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                    <i class="fas fa-arrow-down w-6"></i> Barang Masuk
                </a>
                <a href="{{ route('barang.keluar') }}" class="block px-4 py-3 hover:bg-gray-100 {{ Request::is('barang-keluar*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                    <i class="fas fa-arrow-up w-6"></i> Barang Keluar
                </a>
                <a href="{{ route('laporan.index') }}" class="block px-4 py-3 hover:bg-gray-100 {{ Request::is('laporan*') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                    <i class="fas fa-chart-bar w-6"></i> Laporan
                </a>
            </div>
        </div>
    </div>
    
    <!-- Desktop Menu (Hidden on Mobile) -->
    <div class="hidden md:flex space-x-6 bg-blue-700 py-2 px-4 mt-16 rounded">
        <a href="{{ route('dashboard') }}" class="hover:text-blue-200 {{ Request::is('dashboard') ? 'text-blue-200' : '' }}">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="{{ route('master.barang') }}" class="hover:text-blue-200 {{ Request::is('master-barang*') ? 'text-blue-200' : '' }}">
            <i class="fas fa-box"></i> Master Barang
        </a>
        <a href="{{ route('barang.masuk') }}" class="hover:text-blue-200 {{ Request::is('barang-masuk*') ? 'text-blue-200' : '' }}">
            <i class="fas fa-arrow-down"></i> Barang Masuk
        </a>
        <a href="{{ route('barang.keluar') }}" class="hover:text-blue-200 {{ Request::is('barang-keluar*') ? 'text-blue-200' : '' }}">
            <i class="fas fa-arrow-up"></i> Barang Keluar
        </a>
        <a href="{{ route('laporan.index') }}" class="hover:text-blue-200 {{ Request::is('laporan*') ? 'text-blue-200' : '' }}">
            <i class="fas fa-chart-bar"></i> Laporan
        </a>
    </div>
    @endif

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>

    <main class="container mx-auto px-2 sm:px-4 py-4 sm:py-6 mt-16 md:mt-20">
        @yield('content')
    </main>

    @yield('scripts')
</body>
</html>
