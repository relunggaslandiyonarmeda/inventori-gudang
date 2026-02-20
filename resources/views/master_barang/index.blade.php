@extends('layouts.main')

@section('title', 'Master Barang')

@section('content')
<!-- Back Button -->
<div class="mb-4">
    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
    </a>
</div>

<div class="text-center mb-4 sm:mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Master Barang</h1>
    <p class="text-gray-600 text-sm sm:text-base">Kelola data barang dengan scan barcode</p>
</div>

@if(Session::has('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ Session::get('success') }}
</div>
@endif

<!-- Tabs -->
<div class="mb-4">
    <div class="flex border-b overflow-x-auto">
        <button onclick="showTab('scan')" id="tab-scan" class="px-3 sm:px-4 py-2 border-b-2 border-blue-500 text-blue-600 font-semibold whitespace-nowrap text-sm sm:text-base">
            <i class="fas fa-camera"></i> <span class="hidden sm:inline">Scan Barcode</span>
        </button>
        <button onclick="showTab('list')" id="tab-list" class="px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap text-sm sm:text-base">
            <i class="fas fa-list"></i> <span class="hidden sm:inline">Daftar Barang</span>
        </button>
    </div>
</div>

<!-- Scan Tab -->
<div id="scan-tab" class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
    <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Tambah Barang Baru (Scan Barcode)</h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <!-- Scanner -->
        <div>
            <div class="scanner-container mb-3 sm:mb-4" id="scanner-container">
                <div id="interactive" class="viewport"></div>
            </div>
            <button onclick="startScanner()" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded mb-2">
                <i class="fas fa-play"></i> Mulai Scanner
            </button>
            <button onclick="stopScanner()" class="w-full bg-red-500 hover:bg-red-600 text-white py-2 rounded">
                <i class="fas fa-stop"></i> Stop Scanner
            </button>
            <p class="text-xs sm:text-sm text-gray-500 mt-2 text-center">Arahkan kamera ke barcode</p>
        </div>

        <!-- Form -->
        <div>
            <form action="{{ route('master.barang.store') }}" method="POST" id="barang-form">
                @csrf
                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Barcode</label>
                    <div class="flex gap-2">
                        <input type="text" name="barcode" id="barcode-input" required
                            class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 input-mobile"
                            placeholder="Scan atau ketik barcode">
                        <button type="button" onclick="generateBarcode()" 
                            class="bg-purple-500 hover:bg-purple-600 text-white px-3 sm:px-4 py-2 rounded whitespace-nowrap">
                            <i class="fas fa-barcode"></i> Generate
                        </button>
                    </div>
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Nama Barang</label>
                    <input type="text" name="nama_barang" required
                        class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 input-mobile"
                        placeholder="Masukkan nama barang">
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Stok Awal</label>
                    <input type="number" name="stok" value="0" min="0" required
                        class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 input-mobile"
                        placeholder="Masukkan stok awal">
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Lokasi Rak</label>
                    <select name="lokasi_rak" required
                        class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Lokasi Rak</option>
                        <option value="A">Rak A</option>
                        <option value="B">Rak B</option>
                        <option value="C">Rak C</option>
                        <option value="D">Rak D</option>
                        <option value="E">Rak E</option>
                        <option value="F">Rak F</option>
                        <option value="G">Rak G</option>
                        <option value="H">Rak H</option>
                        <option value="O">Rak O (Outdoor)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save"></i> Simpan Barang
                </button>
            </form>
        </div>
    </div>
</div>

<!-- List Tab -->
<div id="list-tab" class="hidden">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4">
            <h2 class="text-lg sm:text-xl font-bold">Daftar Semua Barang</h2>
            <input type="text" id="search-table" placeholder="Cari..." 
                class="w-full sm:w-auto px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto text-sm" id="barang-table">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm">Barcode</th>
                        <th class="px-2 sm:px-4 py-2 text-left text-xs sm:text-sm">Nama Barang</th>
                        <th class="px-2 sm:px-4 py-2 text-center text-xs sm:text-sm">Stok</th>
                        <th class="px-2 sm:px-4 py-2 text-center text-xs sm:text-sm">Rak</th>
                        <th class="px-2 sm:px-4 py-2 text-center text-xs sm:text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barangs as $barang)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-2 sm:px-4 py-2 font-mono text-xs sm:text-sm">{{ $barang->barcode }}</td>
                        <td class="px-2 sm:px-4 py-2 text-xs sm:text-sm">{{ $barang->nama_barang }}</td>
                        <td class="px-2 sm:px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded text-xs sm:text-sm {{ $barang->stok > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $barang->stok }}
                            </span>
                        </td>
                        <td class="px-2 sm:px-4 py-2 text-center text-xs sm:text-sm">Rak {{ $barang->lokasi_rak }}</td>
                        <td class="px-2 sm:px-4 py-2 text-center">
                            <button onclick="printBarcode('{{ $barang->barcode }}', '{{ $barang->nama_barang }}')"
                                class="bg-purple-500 hover:bg-purple-600 text-white px-2 sm:px-3 py-1 rounded text-xs sm:text-sm mb-1 sm:mb-0">
                                <i class="fas fa-barcode"></i>
                            </button>
                            <button onclick="editBarang('{{ $barang->barcode }}', '{{ $barang->nama_barang }}', {{ $barang->stok }}, '{{ $barang->lokasi_rak }}')"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 sm:px-3 py-1 rounded text-xs sm:text-sm mb-1 sm:mb-0">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('master.barang.destroy', $barang->barcode) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-2 sm:px-3 py-1 rounded text-xs sm:text-sm"
                                    onclick="return confirm('Yakin hapus barang ini?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-2"></i>
                            <p>Belum ada barang</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4">
        <h2 class="text-xl font-bold mb-4">Edit Barang</h2>
        <form id="edit-form" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Barcode</label>
                <input type="text" id="edit-barcode" disabled
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                <input type="text" name="nama_barang" id="edit-nama" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Stok</label>
                <input type="number" name="stok" id="edit-stok" min="0" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Lokasi Rak</label>
                <select name="lokasi_rak" id="edit-rak" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="A">Rak A</option>
                    <option value="B">Rak B</option>
                    <option value="C">Rak C</option>
                    <option value="D">Rak D</option>
                    <option value="E">Rak E</option>
                    <option value="F">Rak F</option>
                    <option value="G">Rak G</option>
                    <option value="H">Rak H</option>
                    <option value="O">Rak O</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
                <button type="button" onclick="closeModal()" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
let scannerActive = false;

function showTab(tab) {
    if (tab === 'scan') {
        document.getElementById('scan-tab').classList.remove('hidden');
        document.getElementById('list-tab').classList.add('hidden');
        document.getElementById('tab-scan').classList.add('border-blue-500', 'text-blue-600');
        document.getElementById('tab-scan').classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-list').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('tab-list').classList.remove('border-blue-500', 'text-blue-600');
    } else {
        document.getElementById('scan-tab').classList.add('hidden');
        document.getElementById('list-tab').classList.remove('hidden');
        document.getElementById('tab-list').classList.add('border-blue-500', 'text-blue-600');
        document.getElementById('tab-list').classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-scan').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('tab-scan').classList.remove('border-blue-500', 'text-blue-600');
    }
}

// Generate random barcode
function generateBarcode() {
    const timestamp = Date.now().toString();
    const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
    const barcode = 'BRG' + timestamp.slice(-8) + random;
    document.getElementById('barcode-input').value = barcode;
}

// Print barcode function - 50mm x 20mm label size
function printBarcode(barcode, namaBarang) {
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Cetak Barcode - ${barcode}</title>
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"><\/script>
            <style>
                @page { size: 50mm 20mm; margin: 0; }
                @media print { body { margin: 0; padding: 0; } .no-print { display: none !important; } }
                body { width: 50mm; height: 20mm; margin: 0; padding: 1mm; font-family: Arial, sans-serif; box-sizing: border-box; }
                .barcode-container { display: flex; flex-direction: row; align-items: center; justify-content: flex-start; height: 100%; gap: 2mm; }
                .barcode-info { flex: 1; overflow: hidden; }
                .barcode-info .nama { font-size: 6pt; font-weight: bold; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 22mm; }
                .barcode-info .code { font-size: 5pt; margin: 0; font-family: 'Courier New', monospace; }
                .barcode-svg { width: 28mm; height: 14mm; }
                .no-print { position: fixed; top: 10px; left: 10px; z-index: 9999; }
                .no-print button { padding: 6px 12px; margin: 3px; border: none; border-radius: 3px; cursor: pointer; font-size: 11px; }
                .btn-print { background: #2563eb; color: white; }
                .btn-close { background: #6b7280; color: white; }
            </style>
        </head>
        <body>
            <div class="no-print">
                <button class="btn-print" onclick="window.print()"><i class="fas fa-print"></i> Cetak</button>
                <button class="btn-close" onclick="window.close()">Tutup</button>
            </div>
            <div class="barcode-container">
                <div class="barcode-info">
                    <p class="nama">${namaBarang}</p>
                    <p class="code">${barcode}</p>
                </div>
                <svg id="barcode" class="barcode-svg"></svg>
            </div>
            <script> JsBarcode("#barcode", "${barcode}", { format: "CODE128", width: 1, height: 25, displayValue: false, margin: 0 }); <\/script>
        </body>
        </html>
    `);
    printWindow.document.close();
}

function startScanner() {
    if (scannerActive) return;
    
    // Remove previous event listener to prevent duplicates
    Quagga.offDetected();
    
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#interactive'),
            constraints: {
                facingMode: "environment",
                width: { min: 640, ideal: 1280, max: 1920 },
                height: { min: 480, ideal: 720, max: 1080 }
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: true
        },
        numOfWorkers: navigator.hardwareConcurrency || 4,
        decoder: {
            readers: ["code_128_reader", "ean_reader", "ean_8_reader", "code_39_reader", "upc_reader", "upc_e_reader", "code_93_reader"]
        },
        locate: true
    }, function(err) {
        if (err) {
            console.log(err);
            alert('Gagal memulai kamera: ' + err.message + '\n\nPastikan kamera diizinkan dan tidak digunakan aplikasi lain.');
            return;
        }
        
        // Register detection handler BEFORE starting
        Quagga.onDetected(function(result) {
            if (result.codeResult && result.codeResult.code) {
                const code = result.codeResult.code;
                document.getElementById('barcode-input').value = code;
                document.getElementById('barcode-input').focus();
                
                // Play beep sound
                let audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2teleRYAOpjf38mWXB8dPnb08euSXy4SO4Lm6OK2Xx8VQXni7e2wYyEQPIbm6+uuZCEPIHzn7uypYyEPHn7o7eqoZCEPJH/m7eqnYyAO');
                audio.play().catch(() => {});
                
                // Auto-close camera after successful scan
                stopScanner();
            }
        });
        
        Quagga.start();
        scannerActive = true;
    });
}

function stopScanner() {
    if (!scannerActive) return;
    Quagga.stop();
    scannerActive = false;
}

function editBarang(barcode, nama, stok, rak) {
    document.getElementById('edit-barcode').value = barcode;
    document.getElementById('edit-nama').value = nama;
    document.getElementById('edit-stok').value = stok;
    document.getElementById('edit-rak').value = rak;
    document.getElementById('edit-form').action = '/master-barang/' + barcode;
    document.getElementById('edit-modal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('edit-modal').classList.add('hidden');
}

// Search table
document.getElementById('search-table').addEventListener('keyup', function() {
    let search = this.value.toLowerCase();
    let table = document.getElementById('barang-table');
    let rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    for (let i = 0; i < rows.length; i++) {
        let barcode = rows[i].cells[0].textContent.toLowerCase();
        let nama = rows[i].cells[1].textContent.toLowerCase();
        let rak = rows[i].cells[3].textContent.toLowerCase();
        
        if (barcode.includes(search) || nama.includes(search) || rak.includes(search)) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }
    }
});
</script>
@endsection
