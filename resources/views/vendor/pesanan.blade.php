@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-receipt"></i>
        </span> Pesanan Lunas
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Pesanan dengan Status Lunas</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Pemesan</th>
                                <th>Waktu</th>
                                <th>Metode Bayar</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesanan as $i => $p)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $p->nama }}</td>
                                <td>{{ $p->timestamp }}</td>
                                <td>{{ $p->metode_bayar }}</td>
                                <td>Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-success text-white">Lunas</span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="collapse"
                                            data-bs-target="#detail-{{ $p->id_pesanan }}">
                                        <i class="mdi mdi-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="detail-{{ $p->id_pesanan }}">
                                <td colspan="7">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>Menu</th>
                                                <th>Jumlah</th>
                                                <th>Harga Satuan</th>
                                                <th>Subtotal</th>
                                                <th>Catatan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($p->detail as $d)
                                            <tr>
                                                <td>{{ $d->menu->nama_menu ?? '-' }}</td>
                                                <td>{{ $d->jumlah }}</td>
                                                <td>Rp {{ number_format($d->harga, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
                                                <td>{{ $d->catatan ?? '-' }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada pesanan lunas</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
