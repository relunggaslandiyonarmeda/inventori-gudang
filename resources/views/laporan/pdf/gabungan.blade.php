<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi Gabungan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; }
        .header p { font-size: 14px; color: #666; margin: 5px 0; }
        .summary { display: flex; justify-content: center; gap: 30px; margin: 20px 0; }
        .summary-box { padding: 10px 20px; border-radius: 5px; }
        .summary-box.green { background-color: #e8f5e9; border: 1px solid #4CAF50; }
        .summary-box.red { background-color: #ffebee; border: 1px solid #f44336; }
        .summary-box .label { font-size: 12px; }
        .summary-box .value { font-size: 18px; font-weight: bold; }
        .green .value { color: #4CAF50; }
        .red .value { color: #f44336; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #2196F3; color: white; }
        .masuk { background-color: #e8f5e9; }
        .keluar { background-color: #ffebee; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    
    <div class="header">
        <h1>LAPORAN TRANSAKSI GABUNGAN</h1>
        <p>Gudang IT - {{ \Carbon\Carbon::createFromDate($tahun, (int)$bulan)->format('F') }} {{ $tahun }}</p>
    </div>

    <div class="summary">
        <div class="summary-box green">
            <div class="label">TOTAL MASUK</div>
            <div class="value">{{ $totalMasuk }}</div>
        </div>
        <div class="summary-box red">
            <div class="label">TOTAL KELUAR</div>
            <div class="value">{{ $totalKeluar }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 80px;">Tanggal</th>
                <th style="width: 60px;">Jenis</th>
                <th style="width: 100px;">Barcode</th>
                <th>Nama Barang</th>
                <th style="width: 60px; text-align: center;">Jumlah</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($barangMasuks as $item)
            <tr class="masuk">
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td style="text-align: center;">MASUK</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                <td style="text-align: center;">{{ $item->jumlah_masuk }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
            @foreach($barangKeluars as $item)
            <tr class="keluar">
                <td>{{ $no++ }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                <td style="text-align: center;">KELUAR</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->masterBarang->nama_barang ?? '-' }}</td>
                <td style="text-align: center;">{{ $item->jumlah_keluar }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
