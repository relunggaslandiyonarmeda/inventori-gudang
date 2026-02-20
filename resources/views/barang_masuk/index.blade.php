@extends('layouts.main')

@section('title', 'Barang Masuk')

@section('content')
<div class="text-center mb-4 sm:mb-6">
    <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Barang Masuk</h1>
    <p class="text-gray-600 text-sm sm:text-base">Tambah stok barang dengan scan barcode</p>
</div>

@if(Session::has('success'))
<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
    {{ Session::get('success') }}
</div>
@endif

@if(Session::has('error'))
<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
    {{ Session::get('error') }}
</div>
@endif

<!-- Tabs -->
<div class="mb-4">
    <div class="flex border-b overflow-x-auto">
        <button onclick="showTab('scan')" id="tab-scan" class="px-3 sm:px-4 py-2 border-b-2 border-blue-500 text-blue-600 font-semibold whitespace-nowrap text-sm sm:text-base">
            <i class="fas fa-camera"></i> <span class="hidden sm:inline">Scan Barcode</span>
        </button>
        <button onclick="showTab('manual')" id="tab-manual" class="px-3 sm:px-4 py-2 border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap text-sm sm:text-base">
            <i class="fas fa-search"></i> <span class="hidden sm:inline">Manual Search</span>
        </button>
    </div>
</div>

<!-- Scan Tab -->
<div id="scan-tab" class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-4 sm:mb-6">
    <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Tambah Stok (Scan Barcode)</h2>
    
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
            <form action="{{ route('barang.masuk.store') }}" method="POST">
                @csrf
                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Barcode</label>
                    <input type="text" name="barcode" id="barcode-input" required
                        class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 input-mobile"
                        placeholder="Scan atau ketik barcode">
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Jumlah Masuk</label>
                    <input type="number" name="jumlah_masuk" min="1" value="1" required
                        class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 input-mobile"
                        placeholder="Masukkan jumlah">
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                        class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-3 sm:mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-1 sm:mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="2"
                        class="w-full px-3 sm:px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Tambahkan keterangan"></textarea>
                </div>

                <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus"></i> Tambah Stok
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Manual Tab -->
<div id="manual-tab" class="hidden">
    <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
        <h2 class="text-lg sm:text-xl font-bold mb-3 sm:mb-4">Tambah Stok (Manual Search)</h2>
        
        <!-- Search -->
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">Cari Barang</label>
            <input type="text" id="search-input" placeholder="Ketik nama atau barcode..."
                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                onkeyup="searchBarang()">
            
            <div id="search-results" class="mt-2 max-h-48 overflow-y-auto border rounded hidden">
                <!-- Search results will be here -->
            </div>
        </div>

        <form action="{{ route('barang.masuk.manual') }}" method="POST" id="manual-form">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Barcode</label>
                <input type="text" name="barcode_manual" id="barcode-manual" required readonly
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Barang</label>
                <input type="text" id="nama-manual" disabled
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Stok Saat Ini</label>
                <input type="text" id="stok-manual" disabled
                    class="w-full px-4 py-2 border rounded-lg bg-gray-100">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Masuk</label>
                <input type="number" name="jumlah_masuk" min="1" value="1" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Masukkan jumlah">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal</label>
                <input type="date" name="tanggal" value="{{ date('Y-m-d') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="2"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Tambahkan keterangan"></textarea>
            </div>

            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-plus"></i> Tambah Stok
            </button>
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
        document.getElementById('manual-tab').classList.add('hidden');
        document.getElementById('tab-scan').classList.add('border-blue-500', 'text-blue-600');
        document.getElementById('tab-scan').classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-manual').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('tab-manual').classList.remove('border-blue-500', 'text-blue-600');
    } else {
        document.getElementById('scan-tab').classList.add('hidden');
        document.getElementById('manual-tab').classList.remove('hidden');
        document.getElementById('tab-manual').classList.add('border-blue-500', 'text-blue-600');
        document.getElementById('tab-manual').classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-scan').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('tab-scan').classList.remove('border-blue-500', 'text-blue-600');
    }
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

let searchTimeout;
function searchBarang() {
    clearTimeout(searchTimeout);
    let query = document.getElementById('search-input').value;
    
    if (query.length < 2) {
        document.getElementById('search-results').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(function() {
        fetch('/search-barang?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                let html = '';
                if (data.length > 0) {
                    data.forEach(item => {
                        html += `<div class="p-3 border-b hover:bg-gray-50 cursor-pointer" onclick="selectBarang('${item.barcode}', '${item.nama_barang}', ${item.stok})">
                            <div class="font-bold">${item.nama_barang}</div>
                            <div class="text-sm text-gray-500">Barcode: ${item.barcode} | Stok: ${item.stok} | Rak: ${item.lokasi_rak}</div>
                        </div>`;
                    });
                    document.getElementById('search-results').innerHTML = html;
                    document.getElementById('search-results').classList.remove('hidden');
                } else {
                    document.getElementById('search-results').classList.add('hidden');
                }
            });
    }, 300);
}

function selectBarang(barcode, nama, stok) {
    document.getElementById('barcode-manual').value = barcode;
    document.getElementById('nama-manual').value = nama;
    document.getElementById('stok-manual').value = stok;
    document.getElementById('search-results').classList.add('hidden');
    document.getElementById('search-input').value = '';
}
</script>
@endsection
