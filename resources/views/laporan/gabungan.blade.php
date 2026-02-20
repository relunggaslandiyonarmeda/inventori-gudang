@extends('layouts.main')

@section('title', 'Laporan Transaksi Gabungan')

@section('content')
<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('laporan.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Laporan
    </a>
</div>

<div class="text-center mb-4 sm:mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Laporan Transaksi Gabungan</h1>
    <p class="text-gray-600 text-sm sm:text-base">{{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}</p>
</div>

<div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
    <!-- Filter -->
    <form action="{{ route('laporan.gabungan') }}" method="GET" class="mb-4">
        <div class="flex flex-wrap gap-2 sm:gap-4 items-end">
            <div>
                <label class="block text-xs sm:text-sm text-gray-600 mb-1">Bulan</label>
                <select name="bulan" class="border rounded px-2 sm:px-3 py-2 text-sm">
                    @for($i=1; $i<=12; $i++)
                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $bulan == $i ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::createFromFormat('m', str_pad($i, 2, '0', STR_PAD_LEFT))->format('F') }}
                    </option>
                    @endfor
                </select>
            </div>
            <div>
                <label class="block text-xs sm:text-sm text-gray-600 mb-1">Tahun</label>
                <select name="tahun" class="border rounded px-2 sm:px-3 py-2 text-sm">
                    @for($y = date('Y'); $y >= date('Y')-5; $y--)
                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 sm:px-4 py-2 rounded text-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>

    <!-- Download Buttons -->
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('laporan.gabungan.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
            class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded text-sm">
            <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">PDF</span>
        </a>
        <a href="{{ route('laporan.gabungan.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
            class="bg-green-500 hover:bg-green-600 text-white px-3 sm:px-4 py-2 rounded text-sm">
            <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Excel</span>
        </a>
        <a href="{{ route('laporan.gabungan.csv', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
            class="bg-blue-400 hover:bg-blue-500 text-white px-3 sm:px-4 py-2 rounded text-sm">
            <i class="fas fa-file-csv"></i> <span class="hidden sm:inline">CSV</span>
        </a>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-2 gap-2 sm:gap-4 mb-4 sm:mb-6">
        <div class="bg-green-100 rounded-lg p-3 sm:p-4 text-center">
            <div class="text-green-600 font-bold text-sm sm:text-lg">Total Masuk</div>
            <div class="text-green-700 text-2xl sm:text-3xl font-bold">{{ $totalMasuk }}</div>
        </div>
        <div class="bg-red-100 rounded-lg p-4 text-center">
            <div class="text-red-600 font-bold text-lg">Total Keluar</div>
            <div class="text-red-700 text-3xl font-bold">{{ $totalKeluar }}</div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-center">Jenis</th>
                    <th class="px-4 py-2 text-left">Barcode</th>
                    <th class="px-4 py-2 text-left">Nama Barang</th>
                    <th class="px-4 py-2 text-center">Jumlah</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @forelse($barangMasuks as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $no++ }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">MASUK</span>
                    </td>
                    <td class="px-4 py-2 font-mono">{{ $item->barcode }}</td>
                    <td class="px-4 py-2">{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                    <td class="px-4 py-2 text-center font-bold text-green-600">{{ $item->jumlah_masuk }}</td>
                    <td class="px-4 py-2">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @endforelse
                @forelse($barangKeluars as $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $no++ }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td class="px-4 py-2 text-center">
                        <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-sm">KELUAR</span>
                    </td>
                    <td class="px-4 py-2 font-mono">{{ $item->barcode }}</td>
                    <td class="px-4 py-2">{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                    <td class="px-4 py-2 text-center font-bold text-red-600">{{ $item->jumlah_keluar }}</td>
                    <td class="px-4 py-2">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @empty
                @if($no == 1)
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endif
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
