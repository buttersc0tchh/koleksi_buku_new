@extends('layouts.guest')

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
                <h4 class="card-title">Data & Metode Pembayaran</h4>
                <form action="{{ route('customer.bayar') }}" method="POST">
                    @csrf
                    {{-- Kirim ulang items ke form bayar --}}
                    @foreach($cartItems as $item)
                        <input type="hidden" name="items[{{ $item['menu']->id_menu }}]" value="{{ $item['jumlah'] }}">
                    @endforeach

                    {{-- Data Customer --}}
                    <div class="mb-3">
                        <label for="customer_name" class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                               id="customer_name" name="customer_name"
                               value="{{ old('customer_name') }}" placeholder="Masukkan nama lengkap" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror"
                               id="customer_email" name="customer_email"
                               value="{{ old('customer_email') }}" placeholder="email@contoh.com" required>
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="customer_phone" class="form-label fw-bold">No. HP <span class="text-danger">*</span></label>
                        <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror"
                               id="customer_phone" name="customer_phone"
                               value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Metode Bayar --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Metode Bayar <span class="text-danger">*</span></label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode_bayar"
                                   id="qris" value="qris" required>
                            <label class="form-check-label" for="qris">
                                <i class="mdi mdi-qrcode me-1 text-success"></i> QRIS
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode_bayar"
                                   id="va" value="virtual_account">
                            <label class="form-check-label" for="va">
                                <i class="mdi mdi-bank me-1 text-primary"></i> Virtual Account (BCA)
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
