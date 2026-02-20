@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="text-center mb-6 sm:mb-8">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-600 text-sm sm:text-base">Inventori Gudang IT</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
    <!-- Total Barang -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Total Jenis Barang</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $totalBarang }}</p>
            </div>
            <div class="bg-blue-100 p-2 sm:p-4 rounded-full">
                <i class="fas fa-box text-blue-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Total Stok -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Total Stok Tersedia</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $totalStok }}</p>
            </div>
            <div class="bg-green-100 p-2 sm:p-4 rounded-full">
                <i class="fas fa-cubes text-green-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Barang Masuk Hari Ini -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-yellow-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Masuk Hari Ini</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $barangMasukHariIni }}</p>
            </div>
            <div class="bg-yellow-100 p-2 sm:p-4 rounded-full">
                <i class="fas fa-arrow-down text-yellow-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Barang Keluar Hari Ini -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 border-l-4 border-red-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-xs sm:text-sm">Keluar Hari Ini</p>
                <p class="text-2xl sm:text-3xl font-bold text-gray-800">{{ $barangKeluarHariIni }}</p>
            </div>
            <div class="bg-red-100 p-2 sm:p-4 rounded-full">
                <i class="fas fa-arrow-up text-red-600 text-xl sm:text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-6">
    <a href="{{ route('master.barang') }}" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
        <div class="text-center">
            <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <i class="fas fa-box text-blue-600 text-xl sm:text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-sm sm:text-base">Master Barang</h3>
            <p class="text-gray-500 text-xs sm:text-sm">Kelola data barang</p>
        </div>
    </a>

    <a href="{{ route('barang.masuk') }}" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
        <div class="text-center">
            <div class="bg-green-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <i class="fas fa-arrow-down text-green-600 text-xl sm:text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-sm sm:text-base">Barang Masuk</h3>
            <p class="text-gray-500 text-xs sm:text-sm">Tambah stok barang</p>
        </div>
    </a>

    <a href="{{ route('barang.keluar') }}" class="bg-white rounded-lg shadow-md p-4 sm:p-6 hover:shadow-lg transition">
        <div class="text-center">
            <div class="bg-red-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <i class="fas fa-arrow-up text-red-600 text-xl sm:text-2xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-sm sm:text-base">Barang Keluar</h3>
            <p class="text-gray-500 text-xs sm:text-sm">Kurangi stok barang</p>
        </div>
    </a>
</div>
@endsection
