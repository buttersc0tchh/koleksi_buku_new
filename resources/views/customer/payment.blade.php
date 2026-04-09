@extends('layouts.guest')

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
                <div class="row mb-4">
                    <div class="col-sm-4 fw-bold">Total Pembayaran</div>
                    <div class="col-sm-8 text-success fw-bold fs-5">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
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

                {{-- Pay Button (only if still pending and snap token available) --}}
                @if($pesanan->status_bayar == 0)
                <div class="text-center mb-4">
                    @if($pesanan->midtrans_token)
                        <button id="pay-button" class="btn btn-success btn-lg px-5">
                            <i class="mdi mdi-cash-check me-2"></i>
                            BAYAR SEKARANG
                        </button>
                        <p class="text-muted mt-2 small">
                            <i class="mdi mdi-information-outline me-1"></i>
                            Pilih metode pembayaran (QRIS / Virtual Account) di jendela berikutnya
                        </p>
                    @else
                        <div class="alert alert-warning">
                            <i class="mdi mdi-alert-circle me-2"></i>
                            Token pembayaran belum tersedia. Silakan muat ulang halaman atau hubungi admin.
                        </div>
                        <button onclick="window.location.reload()" class="btn btn-outline-warning">
                            <i class="mdi mdi-refresh me-1"></i> Muat Ulang
                        </button>
                    @endif
                </div>

                {{-- Countdown Timer --}}
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
{{-- Midtrans Snap.js --}}
<script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>

<script>
    const statusBayar   = {{ $pesanan->status_bayar }};
    const statusUrl     = "{{ route('payment.status', $pesanan->id_pesanan) }}";
    const successUrl    = "{{ route('customer.status', $pesanan->id_pesanan) }}";
    @if($pesanan->midtrans_token)
    const snapToken     = @json($pesanan->midtrans_token);
    @else
    const snapToken     = null;
    @endif

    // Trigger Snap modal on button click
    const payButton = document.getElementById('pay-button');
    if (payButton && snapToken) {
        payButton.addEventListener('click', function () {
            snap.pay(snapToken, {
                onSuccess: function (result) {
                    document.getElementById('statusBadge').innerHTML =
                        '<span class="badge bg-success fs-5 px-4 py-2">✅ LUNAS - Mengalihkan...</span>';
                    payButton.disabled = true;
                    setTimeout(function () {
                        window.location.href = successUrl;
                    }, 1500);
                },
                onPending: function (result) {
                    document.getElementById('statusBadge').innerHTML =
                        '<span class="badge bg-warning text-dark fs-5 px-4 py-2">⏳ MENUNGGU KONFIRMASI</span>';
                },
                onError: function (result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                },
                onClose: function () {
                    // User closed the modal without completing payment
                }
            });
        });
    }

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

        // Countdown timer: 24 jam dari waktu buat pesanan
        const createdAt         = new Date("{{ $pesanan->timestamp }}").getTime();
        const expiresAt         = createdAt + (24 * 60 * 60 * 1000);
        const countdownEl       = document.getElementById('countdown');
        const countdownInterval = setInterval(function () {
            const remaining = Math.max(0, Math.floor((expiresAt - Date.now()) / 1000));
            if (remaining <= 0) {
                clearInterval(countdownInterval);
                if (countdownEl) countdownEl.textContent = 'EXPIRED';
                return;
            }
            const h = String(Math.floor(remaining / 3600)).padStart(2, '0');
            const m = String(Math.floor((remaining % 3600) / 60)).padStart(2, '0');
            const s = String(remaining % 60).padStart(2, '0');
            if (countdownEl) countdownEl.textContent = h + ':' + m + ':' + s;
        }, 1000);
    }
</script>
@endpush
