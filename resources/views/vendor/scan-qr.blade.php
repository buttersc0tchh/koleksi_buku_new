@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-qrcode-scan"></i>
        </span> Scan QR Code Pesanan
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body text-center">
                <h4 class="card-title mb-3">Arahkan Kamera ke QR Code Customer</h4>

                <div id="reader" style="width:100%; border-radius:10px; overflow:hidden;"></div>

                <div id="status" class="mt-3 text-muted">Menunggu scan...</div>

                <div id="hasil" class="mt-4" style="display:none;">
                    <div class="alert alert-success text-start">
                        <h5 class="mb-3">Detail Pesanan</h5>
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th style="width:150px">ID Pesanan</th>
                                <td id="res-id">-</td>
                            </tr>
                            <tr>
                                <th>Status Bayar</th>
                                <td id="res-status">-</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td id="res-total">-</td>
                            </tr>
                        </table>
                        <hr>
                        <h6 class="mb-2">Menu Dipesan:</h6>
                        <table class="table table-sm table-bordered mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Menu</th>
                                    <th>Jumlah</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody id="res-detail"></tbody>
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
        qrbox: { width: 250, height: 250 },
    });

    scanner.render(onScanSuccess, onScanError);
}

function onScanSuccess(decodedText) {
    if (sudahScan) return;
    sudahScan = true;

    const beep = document.getElementById('beep');
    beep.currentTime = 0;
    beep.play().catch(e => console.log('beep error:', e));

    scanner.clear();
    document.getElementById('status').textContent = 'QR terbaca: ' + decodedText;

    fetch(`{{ url('/vendor/cari-pesanan') }}/${decodedText}`)
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                document.getElementById('status').textContent = '❌ ' + data.error;
                document.getElementById('hasil').style.display = 'block';
                return;
            }

            document.getElementById('res-id').textContent    = '#' + data.id_pesanan;
            document.getElementById('res-status').innerHTML  = data.status_bayar === 'Lunas'
                ? '<span class="badge badge-success">✅ Lunas</span>'
                : '<span class="badge badge-danger">❌ Belum Lunas</span>';
            document.getElementById('res-total').textContent = formatRupiah(data.total);

            let rows = '';
            data.detail.forEach(d => {
                rows += `<tr>
                    <td>${d.nama_menu}</td>
                    <td>${d.jumlah}</td>
                    <td>${formatRupiah(d.harga)}</td>
                </tr>`;
            });
            document.getElementById('res-detail').innerHTML = rows;
            document.getElementById('hasil').style.display  = 'block';
        })
        .catch(() => {
            document.getElementById('status').textContent = '❌ Gagal mengambil data pesanan.';
        });
}

function onScanError(err) {}

document.getElementById('btnScanLagi').addEventListener('click', function () {
    startScanner();
});

startScanner();
</script>
@endpush