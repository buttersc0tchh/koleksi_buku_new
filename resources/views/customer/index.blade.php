@extends('layouts.guest')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-warning text-white me-2">
            <i class="mdi mdi-account"></i>
        </span> Halaman Customer
    </h3>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('customer.checkout') }}" method="POST" id="checkoutForm">
    @csrf
    @foreach($vendors as $vendor)
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="mdi mdi-store me-2"></i>{{ $vendor->nama_vendor }}</h5>
                </div>
                <div class="card-body">
                    @if($vendor->menu->count() > 0)
                    <div class="row">
                        @foreach($vendor->menu as $menu)
                        <div class="col-md-3 mb-3">
                            <div class="card h-100 border">
                                @if($menu->path_gambar)
                                <img src="{{ $menu->path_gambar }}" class="card-img-top"
                                     alt="{{ $menu->nama_menu }}" style="height:120px;object-fit:cover;">
                                @else
                                <div class="bg-light d-flex align-items-center justify-content-center"
                                     style="height:120px;">
                                    <i class="mdi mdi-food mdi-48px text-muted"></i>
                                </div>
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-1">{{ $menu->nama_menu }}</h6>
                                    <p class="text-success mb-2">
                                        <strong>Rp {{ number_format($menu->harga, 0, ',', '.') }}</strong>
                                    </p>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text">Qty</span>
                                        <input type="number" name="items[{{ $menu->id_menu }}]"
                                               class="form-control" value="0" min="0" max="99">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-muted">Belum ada menu tersedia.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($vendors->count() > 0)
    <div class="row">
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="mdi mdi-cart-outline me-2"></i> Lanjut ke Checkout
            </button>
        </div>
    </div>
    @else
    <div class="alert alert-warning">
        Belum ada vendor dan menu tersedia. Hubungi admin.
    </div>
    @endif
</form>
@endsection
