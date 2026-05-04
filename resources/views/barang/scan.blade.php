@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-barcode-scan"></i>
        </span> Scanner Barcode Barang
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title mb-3">Arahkan Kamera ke Barcode</h4>

                <div id="reader" style="width:100%; border-radius:10px; overflow:hidden;"></div>

                <div id="status" class="mt-3 text-muted">Menunggu scan...</div>

                <div id="hasil" class="mt-4" style="display:none;">
                    <div class="alert alert-success text-start">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th style="width:150px">ID Barang</th>
                                <td id="res-id">-</td>
                            </tr>
                            <tr>
                                <th>Nama Barang</th>
                                <td id="res-nama">-</td>
                            </tr>
                            <tr>
                                <th>Harga</th>
                                <td id="res-harga">-</td>
                            </tr>
                            <tr>
                                <th>Satuan</th>
                                <td id="res-satuan">-</td>
                            </tr>
                        </table>
                    </div>
                    <button class="btn btn-primary mt-2" id="btnScanLagi">
                        <i class="mdi mdi-refresh"></i> Scan Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<audio id="beep" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>
@endsection

@push('scripts')
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
let scanner = null;
let sudahScan = false;

function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

function startScanner() {
    sudahScan = false;
    document.getElementById('hasil').style.display = 'none';
    document.getElementById('status').textContent = 'Menunggu scan...';

    scanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: { width: 300, height: 150 },
        formatsToSupport: [
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.CODE_39,
            Html5QrcodeSupportedFormats.EAN_13,
        ]
    });

    scanner.render(onScanSuccess, onScanError);
}

function onScanSuccess(decodedText) {
    if (sudahScan) return;
    sudahScan = true;

    document.getElementById('beep').play();
    scanner.clear();
    document.getElementById('status').textContent = 'Barcode terbaca: ' + decodedText;

    fetch(`{{ url('/barang/cari') }}/${decodedText}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('status').textContent = '❌ ' + data.error;
                document.getElementById('hasil').style.display = 'block';
                return;
            }
            document.getElementById('res-id').textContent    = data.id_barang;
            document.getElementById('res-nama').textContent  = data.nama_barang;
            document.getElementById('res-harga').textContent = formatRupiah(data.harga);
            document.getElementById('res-satuan').textContent = data.satuan;
            document.getElementById('hasil').style.display   = 'block';
        })
        .catch(() => {
            document.getElementById('status').textContent = '❌ Gagal mengambil data barang.';
        });
}

function onScanError(err) {}

document.getElementById('btnScanLagi').addEventListener('click', function () {
    startScanner();
});

startScanner();
</script>
@endpush