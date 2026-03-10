<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang Rusak</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 10px; 
            margin: 10px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 15px;
        }
        .header h1 { 
            font-size: 16px; 
            margin: 0;
            font-weight: bold;
        }
        .header p { 
            font-size: 12px; 
            color: #333; 
            margin: 3px 0;
        }
        .sub-header {
            font-size: 11px;
            margin-bottom: 10px;
        }
        
        /* Table styling - clean borders */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
            border: 1px solid #000;
        }
        thead {
            border-bottom: 2px solid #000;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 5px 6px; 
            vertical-align: middle;
            text-align: center;
        }
        th { 
            background-color: #4f46e5; 
            color: white;
            font-weight: bold;
            font-size: 9px;
        }
        
        /* Row styling */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        /* Image styling - larger for visibility */
        .foto {
            width: 70px;
            height: 70px;
            object-fit: contain;
            display: block;
            margin: 0 auto;
        }
        
        /* Badge styling */
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        .badge-hidup { background-color: #10b981; color: white; }
        .badge-mati { background-color: #ef4444; color: white; }
        
        .footer { 
            margin-top: 20px; 
            text-align: right;
            font-size: 10px;
        }
        
        /* Page break handling */
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    
    <div class="header">
        <h1>PT. UNION SAMPOERNA TRIPUTRA PERSADA</h1>
        <p class="sub-header">Lampiran data peralatan IT diajukan untuk discrap</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 60px;">Vehicle Group</th>
                <th style="width: 65px;">Description</th>
                <th style="width: 35px;">Tahun</th>
                <th style="width: 55px;">Merek</th>
                <th style="width: 85px;">Foto</th>
                <th style="width: 55px;">Lokasi</th>
                <th style="width: 40px;">Kondisi</th>
                <th style="width: 80px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($barangRusaks as $index => $br)
            @if($index > 0 && $index % 15 == 0)
        </tbody>
    </table>
    
    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
    
    <div class="page-break"></div>
    
    <div class="header">
        <h1>PT. UNION SAMTOERNA TRIPUTRA PERSADA</h1>
        <p class="sub-header">Lampiran data peralatan IT diajukan untuk discrap (Lanjutan)</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 60px;">Vehicle Group</th>
                <th style="width: 65px;">Description</th>
                <th style="width: 35px;">Tahun</th>
                <th style="width: 55px;">Merek</th>
                <th style="width: 85px;">Foto</th>
                <th style="width: 55px;">Lokasi</th>
                <th style="width: 40px;">Kondisi</th>
                <th style="width: 80px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @endif
            <tr>
                <td>{{ $no++ }}</td>
                <td>{{ $br->vehicle_group_code }}</td>
                <td>{{ $br->description ?? '-' }}</td>
                <td>{{ $br->tahun_perolehan }}</td>
                <td>{{ $br->merek }}</td>
                <td>
                    @if($br->foto)
                    <img src="{{ storage_path('app/public/' . $br->foto) }}" class="foto" alt="Foto">
                    @else
                    -
                    @endif
                </td>
                <td>{{ $br->lokasi_unit }}</td>
                <td>
                    @if($br->kondisi_unit == 'hidup')
                    <span class="badge badge-hidup">Hidup</span>
                    @else
                    <span class="badge badge-mati">Mati</span>
                    @endif
                </td>
                <td>{{ $br->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d-m-Y H:i:s') }}</p>
    </div>
</body>
</html>
