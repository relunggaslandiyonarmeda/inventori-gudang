<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang per Rak</title>
    <style>
        body { font-family: 'Times New Roman', serif; font-size: 12px; color: #000; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; margin: 0; font-weight: bold; }
        .header p { font-size: 14px; color: #666; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; border: 1px solid #000; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        th { background-color: #cccccc; color: #000; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .total { font-weight: bold; background-color: #f2f2f2; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; }
        .company { font-weight: bold; margin-bottom: 10px; }
        @media print { body { margin: 0.5in; } }
    </style>
</head>
<body>

    <div class="header">
        <p class="company">PT. UNION SAMPOERNA TRIPUTRA PERSADA</p>
        <h1>LAPORAN BARANG PER RAK</h1>
        <p>Gudang IT - {{ $rak !== 'all' ? 'Rak ' . $rak : 'Semua Rak' }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th style="width: 40px;">Rak</th>
                <th style="width: 100px;">Barcode</th>
                <th>Nama Barang</th>
                <th style="width: 60px; text-align: center;">Stok</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($barangs as $rakName => $items)
            @foreach($items as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $rakName }}</td>
                <td>{{ $item->barcode }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td style="text-align: center;">{{ number_format($item->stok, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @endforeach
            <tr class="total">
                <td colspan="4" style="text-align: right;">TOTAL</td>
                <td style="text-align: center;">{{ number_format($totalStok, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>PT. UNION SAMPOERNA TRIPUTRA PERSADA</p>
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>