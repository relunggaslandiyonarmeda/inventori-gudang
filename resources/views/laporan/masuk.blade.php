@extends('layouts.main')

@section('title', 'Laporan Barang Masuk')

@section('content')
<div class="text-center mb-4 sm:mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Laporan Barang Masuk</h1>
    <p class="text-gray-600 text-sm sm:text-base">{{ \Carbon\Carbon::createFromFormat('m', $bulan)->format('F') }} {{ $tahun }}</p>
</div>

<div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
    <!-- Filter -->
    <form action="{{ route('laporan.masuk') }}" method="GET" class="mb-4">
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
        <a href="{{ route('laporan.masuk.pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
            class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded text-sm">
            <i class="fas fa-file-pdf"></i> <span class="hidden sm:inline">PDF</span>
        </a>
        <a href="{{ route('laporan.masuk.excel', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
            class="bg-green-500 hover:bg-green-600 text-white px-3 sm:px-4 py-2 rounded text-sm">
            <i class="fas fa-file-excel"></i> <span class="hidden sm:inline">Excel</span>
        </a>
        <a href="{{ route('laporan.masuk.csv', ['bulan' => $bulan, 'tahun' => $tahun]) }}" 
            class="bg-blue-400 hover:bg-blue-500 text-white px-3 sm:px-4 py-2 rounded text-sm">
            <i class="fas fa-file-csv"></i> <span class="hidden sm:inline">CSV</span>
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left">No</th>
                    <th class="px-4 py-2 text-left">Tanggal</th>
                    <th class="px-4 py-2 text-left">Barcode</th>
                    <th class="px-4 py-2 text-left">Nama Barang</th>
                    <th class="px-4 py-2 text-center">Jumlah</th>
                    <th class="px-4 py-2 text-left">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangMasuks as $index => $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $index + 1 }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                    <td class="px-4 py-2 font-mono">{{ $item->barcode }}</td>
                    <td class="px-4 py-2">{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                    <td class="px-4 py-2 text-center font-bold text-green-600">{{ $item->jumlah_masuk }}</td>
                    <td class="px-4 py-2">{{ $item->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr class="bg-gray-200 font-bold">
                    <td colspan="4" class="px-4 py-2 text-right">TOTAL</td>
                    <td class="px-4 py-2 text-center text-green-600">{{ $totalMasuk }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
