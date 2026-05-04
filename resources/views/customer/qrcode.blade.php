@extends('layouts.guest')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-qrcode"></i>
        </span> QR Code Pesanan
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-5 grid-margin">
        <div class="card text-center">
            <div class="card-body py-5">
                <h5 class="mb-1">Pesanan #{{ $pesanan->id_pesanan }}</h5>
                <p class="text-muted mb-4">{{ $pesanan->customer_name }}</p>

                @if($pesanan->status_bayar == 1 && $qrCode)
                    <div class="mb-3">
                        <img src="data:image/png;base64,{{ $qrCode }}"
                             alt="QR Code" style="width:220px; height:220px;">
                    </div>
                    <span class="badge bg-success fs-6 px-3 py-2 mb-3">✅ Lunas</span>
                    <p class="text-muted small">Tunjukkan QR code ini ke vendor</p>
                @else
                    <div class="alert alert-warning">
                        <i class="mdi mdi-alert-circle me-2"></i>
                        QR Code hanya tersedia setelah pembayaran lunas.
                    </div>
                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">⏳ Belum Lunas</span>
                @endif

                <div class="mt-4 d-flex gap-2 justify-content-center">
                    <a href="{{ route('customer.status', $pesanan->id_pesanan) }}" class="btn btn-outline-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i> Detail Pesanan
                    </a>
                    <a href="{{ route('customer.index') }}" class="btn btn-primary">
                        <i class="mdi mdi-cart me-1"></i> Pesan Lagi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    // Simpan id_pesanan ke localStorage saat halaman QR dibuka
    localStorage.setItem('last_qrcode_id', '{{ $pesanan->id_pesanan }}');
</script>
@endpush
@endsection