@extends('layouts.guest')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-check-circle"></i>
        </span> Status Pembayaran
    </h3>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-8 grid-margin">
        <div class="card border-{{ $pesanan->status_bayar == 1 ? 'success' : 'warning' }}">
            <div class="card-header bg-{{ $pesanan->status_bayar == 1 ? 'success' : 'warning' }} text-white">
                <h5 class="mb-0">
                    @if($pesanan->status_bayar == 1)
                        <i class="mdi mdi-check-circle me-2"></i> Pembayaran Lunas
                    @else
                        <i class="mdi mdi-clock-outline me-2"></i> Menunggu Pembayaran
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">ID Pesanan</div>
                    <div class="col-sm-8">#{{ $pesanan->id_pesanan }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Nama Pemesan</div>
                    <div class="col-sm-8">{{ $pesanan->customer_name ?? $pesanan->nama }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Waktu Pesanan</div>
                    <div class="col-sm-8">{{ $pesanan->timestamp }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Metode Bayar</div>
                    <div class="col-sm-8">{{ $pesanan->metode_bayar }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4 fw-bold">Total</div>
                    <div class="col-sm-8 text-success fw-bold">
                        Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-sm-4 fw-bold">Status</div>
                    <div class="col-sm-8">
                        @if($pesanan->status_bayar == 1)
                            <span class="badge bg-success fs-6">✅ Lunas</span>
                        @else
                            <span class="badge bg-warning text-dark fs-6">⏳ Belum Lunas</span>
                        @endif
                    </div>
                </div>

                <h5>Detail Pesanan</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th>Menu</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
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
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-success">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($pesanan->status_bayar == 1 && $qrCode)
                <div class="text-center my-4">
                     <h6 class="mb-2">QR Code Pesanan</h6>
                    <img src="data:image/png;base64,{{ $qrCode }}"
                    alt="QR Code" style="width:150px; height:150px;">
                    <p class="text-muted small mt-1">ID Pesanan: #{{ $pesanan->id_pesanan }}</p>
                </div>
                @endif

        <div class="mt-3 d-flex gap-2">
             @if($pesanan->status_bayar == 0)
            <button onclick="cekStatus()" class="btn btn-warning">
               <i class="mdi mdi-refresh"></i> Cek Status Pembayaran
            </button>
             @endif
             <a href="{{ route('customer.index') }}" class="btn btn-primary">
                 <i class="mdi mdi-arrow-left me-1"></i> Pesan Lagi
            </a>
         </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cekStatus() {
    fetch("{{ route('customer.status', $pesanan->id_pesanan) }}")
        .then(res => res.json())
        .then(data => {
            if (data.status_label === 'lunas') {
                window.location.reload();
            } else {
                alert('Status masih: ' + data.status_label + '. Tunggu beberapa saat lagi.');
            }
        });
}
</script>
@endpush
