@extends('layouts.main')

@section('title', 'Laporan')

@section('content')
<div class="text-center mb-4 sm:mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Laporan</h1>
    <p class="text-gray-600 text-sm sm:text-base">Pilih jenis laporan yang ingin dilihat</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
    <!-- Laporan Masuk -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="text-center">
            <div class="bg-green-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <i class="fas fa-arrow-down text-green-600 text-xl sm:text-2xl"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-bold mb-2">Laporan Barang Masuk</h3>
            <p class="text-gray-500 text-xs sm:text-sm mb-4">Lihat dan download laporan barang masuk perbulan</p>
            
            <form action="{{ route('laporan.masuk') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <select name="bulan" class="border rounded px-2 py-1 text-sm">
                        @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('F') }}
                        </option>
                        @endfor
                    </select>
                    <select name="tahun" class="border rounded px-2 py-1 text-sm">
                        @for($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </button>
            </form>
        </div>
    </div>

    <!-- Laporan Keluar -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="text-center">
            <div class="bg-red-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <i class="fas fa-arrow-up text-red-600 text-xl sm:text-2xl"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-bold mb-2">Laporan Barang Keluar</h3>
            <p class="text-gray-500 text-xs sm:text-sm mb-4">Lihat dan download laporan barang keluar perbulan</p>
            
            <form action="{{ route('laporan.keluar') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <select name="bulan" class="border rounded px-2 py-1 text-sm">
                        @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('F') }}
                        </option>
                        @endfor
                    </select>
                    <select name="tahun" class="border rounded px-2 py-1 text-sm">
                        @for($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded text-sm sm:text-base">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </button>
            </form>
        </div>
    </div>

    <!-- Laporan Gabungan -->
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="text-center">
            <div class="bg-blue-100 w-12 h-12 sm:w-16 sm:h-16 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                <i class="fas fa-exchange-alt text-blue-600 text-xl sm:text-2xl"></i>
            </div>
            <h3 class="text-lg sm:text-xl font-bold mb-2">Laporan Gabungan</h3>
            <p class="text-gray-500 text-sm mb-4">Lihat dan download laporan transaksi perbulan</p>
            
            <form action="{{ route('laporan.gabungan') }}" method="GET" class="mb-4">
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <select name="bulan" class="border rounded px-2 py-1 text-sm">
                        @for($i=1; $i<=12; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ date('m') == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('F') }}
                        </option>
                        @endfor
                    </select>
                    <select name="tahun" class="border rounded px-2 py-1 text-sm">
                        @for($y = date('Y'); $y >= date('Y')-5; $y--)
                        <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-eye"></i> Lihat Laporan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
