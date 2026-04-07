@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-warning text-white me-2">
            <i class="mdi mdi-cart-outline"></i>
        </span> Checkout
    </h3>
</div>

<div class="row">
    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Ringkasan Pesanan</h4>
                <p class="text-muted">Pemesan: <strong>{{ $namaCustomer }}</strong></p>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Menu</th>
                                <th>Harga Satuan</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $item)
                            <tr>
                                <td>{{ $item['menu']->nama_menu }}</td>
                                <td>Rp {{ number_format($item['menu']->harga, 0, ',', '.') }}</td>
                                <td>{{ $item['jumlah'] }}</td>
                                <td>Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-active">
                                <th colspan="3" class="text-end">Total</th>
                                <th class="text-success">Rp {{ number_format($total, 0, ',', '.') }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Pilih Metode Pembayaran</h4>
                <form action="{{ route('customer.bayar') }}" method="POST">
                    @csrf
                    {{-- Kirim ulang items ke form bayar --}}
                    @foreach($cartItems as $item)
                        <input type="hidden" name="items[{{ $item['menu']->id_menu }}]" value="{{ $item['jumlah'] }}">
                    @endforeach

                    <div class="mb-3">
                        <label class="form-label fw-bold">Metode Bayar</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode_bayar"
                                   id="va" value="Virtual Account" required>
                            <label class="form-check-label" for="va">
                                <i class="mdi mdi-bank me-1 text-primary"></i> Virtual Account
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode_bayar"
                                   id="qris" value="QRIS">
                            <label class="form-check-label" for="qris">
                                <i class="mdi mdi-qrcode me-1 text-success"></i> QRIS
                            </label>
                        </div>
                        @error('metode_bayar')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="mdi mdi-cash-check me-1"></i>
                            Bayar Rp {{ number_format($total, 0, ',', '.') }}
                        </button>
                        <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
