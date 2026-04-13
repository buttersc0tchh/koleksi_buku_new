@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-store"></i>
        </span> Manajemen Vendor
    </h3>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row">
    <div class="col-md-4 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Vendor</h4>
                <form action="{{ route('vendor.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Vendor</label>
                        <input type="text" name="nama_vendor" class="form-control @error('nama_vendor') is-invalid @enderror"
                               placeholder="Nama vendor..." value="{{ old('nama_vendor') }}" required>
                        @error('nama_vendor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Tambah Vendor
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Daftar Vendor</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nama Vendor</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($vendors as $i => $vendor)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $vendor->nama_vendor }}</td>
                                <td>
                                    <a href="{{ route('vendor.menu', $vendor->id_vendor) }}" class="btn btn-sm btn-info">
                                        <i class="mdi mdi-hanger"></i> Kelola Barang
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada vendor</td>
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
