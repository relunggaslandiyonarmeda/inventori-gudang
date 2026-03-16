<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang per Rak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10pt;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #4f46e5;
        }
        .header h1 {
            font-size: 18pt;
            color: #4f46e5;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10pt;
            color: #666;
        }
        .summary {
            display: block;
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .summary-item {
            text-align: left;
            margin-bottom: 5px;
        }
        .summary-item:last-child {
            margin-bottom: 0;
        }
        .summary-item .label {
            font-size: 10pt;
            color: #333;
            display: inline-block;
            width: 120px;
        }
        .summary-item .value {
            font-size: 11pt;
            font-weight: bold;
            color: #4f46e5;
        }
        .rak-section {
            margin-bottom: 30px;
        }
        .rak-title {
            background: #4f46e5;
            color: white;
            padding: 10px 12px;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px 8px;
            text-align: left;
        }
        th {
            background: #f1f5f9;
            font-weight: bold;
            font-size: 9pt;
        }
        td {
            font-size: 9pt;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 8pt;
        }
        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8pt;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Barang per Rak</h1>
        <p>Inventori Gudang IT | Dicetak: {{ \Carbon\Carbon::now()->format('d F Y H:i') }}</p>
        @if($rak !== 'all')
        <p><strong>Filter: Rak {{ $rak }}</strong></p>
        @endif
    </div>

    <div class="summary">
        <div class="summary-item">
            <span class="label">Total Rak =</span>
            <span class="value">{{ $barangs->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="label">Total Barang =</span>
            <span class="value">{{ number_format($totalBarang) }}</span>
        </div>
        <div class="summary-item">
            <span class="label">Total Stok =</span>
            <span class="value">{{ number_format($totalStok) }}</span>
        </div>
    </div>

    @foreach($barangs as $rakName => $items)
    <div class="rak-section no-break">
        <div class="rak-title">
            Rak {{ $rakName }} - {{ $items->count() }} Barang (Stok: {{ number_format($items->sum('stok')) }})
        </div>
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 40px;">No</th>
                    <th>Barcode</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Stok</th>
                </tr>
            </thead>
            <tbody>
                @php $no = 1; @endphp
                @foreach($items as $item)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td><code>{{ $item->barcode }}</code></td>
                    <td>{{ $item->nama_barang }}</td>
                    <td class="text-center">{{ number_format($item->stok) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f8f9fa;">
                    <td colspan="3" class="text-right"><strong>Total Rak {{ $rakName }}:</strong></td>
                    <td class="text-center"><strong>{{ number_format($items->sum('stok')) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endforeach

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Inventori Gudang IT</p>
    </div>
</body>
</html>
