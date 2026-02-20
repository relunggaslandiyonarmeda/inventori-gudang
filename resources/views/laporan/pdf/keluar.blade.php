<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Keluar</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; }
        .header p { font-size: 14px; color: #666; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f44336; color: white; }
        .total { font-weight: bold; background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="{{ route('laporan.keluar', ['bulan' => $bulan, 'tahun' => $tahun]) }}" style="color: #2196F3; text-decoration: none;">
            ← Kembali ke Laporan
        </a>
    </div>
    <div class="header">
        <h1>LAPORAN BARANG KELUAR</h1>
        <p>Gudang IT - {{ \Carbon\Carbon::createFromDate($tahun, (int)$bulan)->format('F') }} {{ $tahun }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 100px;">Barcode</th>
                <th>Nama Barang</th>
                <th style="width: 60px; text-align: center;">Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barangKeluars as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                <td style="text-align: center;">{{ $item->jumlah_keluar }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
            <tr class="total">
                <td colspan="4" style="text-align: right;">TOTAL</td>
                <td style="text-align: center;">{{ $totalKeluar }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
