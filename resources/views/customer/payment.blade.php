@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-credit-card-outline"></i>
        </span> Halaman Pembayaran
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-header bg-gradient-primary text-white">
                <h5 class="mb-0">
                    <i class="mdi mdi-receipt me-2"></i>
                    Pesanan #{{ $pesanan->id_pesanan }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Nama Pemesan</div>
                    <div class="col-sm-8">{{ $pesanan->customer_name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Email</div>
                    <div class="col-sm-8">{{ $pesanan->customer_email }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">No. HP</div>
                    <div class="col-sm-8">{{ $pesanan->customer_phone }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Total Pembayaran</div>
                    <div class="col-sm-8 text-success fw-bold fs-5">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 fw-bold">Metode Bayar</div>
                    <div class="col-sm-8">
                        @if($pesanan->metode_bayar === 'qris')
                            <span class="badge bg-success fs-6"><i class="mdi mdi-qrcode me-1"></i> QRIS</span>
                        @else
                            <span class="badge bg-primary fs-6"><i class="mdi mdi-bank me-1"></i> Virtual Account BCA</span>
                        @endif
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="text-center mb-4">
                    <div id="statusBadge">
                        @if($pesanan->status_bayar == 1)
                            <span class="badge bg-success fs-5 px-4 py-2">✅ LUNAS</span>
                        @elseif($pesanan->status_bayar == 2)
                            <span class="badge bg-secondary fs-5 px-4 py-2">⏰ EXPIRED</span>
                        @else
                            <span class="badge bg-warning text-dark fs-5 px-4 py-2">⏳ MENUNGGU PEMBAYARAN</span>
                        @endif
                    </div>
                </div>

                {{-- QRIS Display --}}
                @if($pesanan->metode_bayar === 'qris')
                <div class="text-center mb-4" id="qrisSection">
                    <h5 class="mb-3"><i class="mdi mdi-qrcode me-2"></i> Scan QR Code Berikut</h5>
                    @if($pesanan->qr_url)
                        <img src="{{ $pesanan->qr_url }}" alt="QR Code Pembayaran"
                             class="img-fluid border rounded p-2"
                             style="max-width: 280px;">
                        <p class="text-muted mt-2 small">Scan dengan aplikasi mobile banking atau e-wallet Anda</p>
                    @else
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            QR Code sedang diproses. Silakan muat ulang halaman atau hubungi admin.
                        </div>
                    @endif
                </div>
                @endif

                {{-- Virtual Account Display --}}
                @if($pesanan->metode_bayar === 'virtual_account')
                <div class="text-center mb-4" id="vaSection">
                    <h5 class="mb-3"><i class="mdi mdi-bank me-2"></i> Transfer ke Virtual Account BCA</h5>
                    @if($pesanan->va_number)
                        <div class="card bg-light border-primary mx-auto" style="max-width: 400px;">
                            <div class="card-body">
                                <p class="mb-1 text-muted">Nomor Virtual Account</p>
                                <h3 class="fw-bold text-primary letter-spacing-1" id="vaNumber">
                                    {{ $pesanan->va_number }}
                                </h3>
                                <button class="btn btn-outline-primary btn-sm mt-2"
                                        onclick="copyVA()">
                                    <i class="mdi mdi-content-copy me-1"></i> Salin Nomor
                                </button>
                            </div>
                        </div>
                        <p class="text-muted mt-2 small">Transfer tepat sesuai nominal untuk verifikasi otomatis</p>
                    @else
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            Nomor Virtual Account sedang diproses. Silakan muat ulang halaman atau hubungi admin.
                        </div>
                    @endif
                </div>
                @endif

                {{-- Countdown Timer --}}
                @if($pesanan->status_bayar == 0)
                <div class="text-center mb-3">
                    <p class="text-muted mb-1">Selesaikan pembayaran dalam:</p>
                    <h4 class="text-danger fw-bold" id="countdown">24:00:00</h4>
                </div>
                @endif

                <hr>

                {{-- Detail Pesanan --}}
                <h6 class="mb-2">Detail Pesanan</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pesanan->detail as $d)
                            <tr>
                                <td>{{ $d->menu->nama_menu ?? '-' }}</td>
                                <td>{{ $d->jumlah }}</td>
                                <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3 text-center">
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Menu
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const pesananId     = {{ $pesanan->id_pesanan }};
    const statusBayar   = {{ $pesanan->status_bayar }};
    const statusUrl     = "{{ route('payment.status', $pesanan->id_pesanan) }}";
    const successUrl    = "{{ route('customer.status', $pesanan->id_pesanan) }}";

    // Polling status setiap 5 detik jika masih pending
    if (statusBayar === 0) {
        const pollingInterval = setInterval(function () {
            fetch(statusUrl)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.status_label === 'lunas') {
                        clearInterval(pollingInterval);
                        clearInterval(countdownInterval);
                        document.getElementById('statusBadge').innerHTML =
                            '<span class="badge bg-success fs-5 px-4 py-2">✅ LUNAS - Mengalihkan...</span>';
                        setTimeout(function () {
                            window.location.href = successUrl;
                        }, 1500);
                    } else if (data.status_label === 'expired') {
                        clearInterval(pollingInterval);
                        clearInterval(countdownInterval);
                        document.getElementById('statusBadge').innerHTML =
                            '<span class="badge bg-secondary fs-5 px-4 py-2">⏰ EXPIRED</span>';
                    }
                })
                .catch(function (err) { console.error('Polling error:', err); });
        }, 5000);

        // Countdown timer (24 jam default)
        let totalSeconds = 24 * 60 * 60;
        const countdownEl = document.getElementById('countdown');
        const countdownInterval = setInterval(function () {
            if (totalSeconds <= 0) {
                clearInterval(countdownInterval);
                countdownEl.textContent = 'EXPIRED';
                return;
            }
            totalSeconds--;
            const h = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
            const m = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
            const s = String(totalSeconds % 60).padStart(2, '0');
            countdownEl.textContent = h + ':' + m + ':' + s;
        }, 1000);
    }

    function copyVA() {
        const va = document.getElementById('vaNumber').innerText.trim();
        navigator.clipboard.writeText(va).then(function () {
            alert('Nomor VA berhasil disalin: ' + va);
        });
    }
</script>
@endpush
