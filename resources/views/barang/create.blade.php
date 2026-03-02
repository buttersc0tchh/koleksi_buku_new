@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">
                    <i class="mdi mdi-plus-circle text-primary"></i> Tambah Barang Baru
                </h4>
                <hr>
                <form action="{{ route('barang.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang"
                               class="form-control @error('nama_barang') is-invalid @enderror"
                               value="{{ old('nama_barang') }}"
                               placeholder="Contoh: Mie Goreng Indomie" required>
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Harga <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" name="harga"
                                   class="form-control @error('harga') is-invalid @enderror"
                                   value="{{ old('harga') }}" placeholder="0" min="0" required>
                        </div>
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Satuan</label>
                        <input type="text" name="satuan"
                               class="form-control @error('satuan') is-invalid @enderror"
                               value="{{ old('satuan') }}"
                               placeholder="Contoh: pcs, kg, liter, sachet">
                        @error('satuan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection