@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-upload"></i>
        </span> Tambah Customer 2 - Upload File
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Customer dengan Upload Foto</h4>
                <form action="{{ route('customer-data.store-path') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>

                    {{-- Provinsi --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" required>
                    </div>

                    {{-- Kota --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kota</label>
                        <input type="text" name="kota" class="form-control" required>
                    </div>

                    {{-- Kecamatan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" required>
                    </div>

                    {{-- Kodepos - Kelurahan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kodepos - Kelurahan</label>
                        <input type="text" name="kodepos_kelurahan" class="form-control" required>
                    </div>

                    {{-- Upload Foto --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Upload Foto</label>
                        <input type="file" name="foto_file" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB)</small>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-content-save"></i> Simpan Data
                        </button>
                        <a href="{{ route('customer-data.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection