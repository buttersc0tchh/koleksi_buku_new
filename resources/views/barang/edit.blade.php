@extends('layouts.master')

@section('title', 'Edit Barang')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-warning text-white me-2">
            <i class="mdi mdi-pencil"></i>
        </span> Edit Barang
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Formulir Edit Barang</h4>
                <hr>

                <form id="formEdit" action="{{ route('barang.update', $barang->id_barang) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="font-weight-bold">ID Barang</label>
                        <input type="text" class="form-control" value="{{ $barang->id_barang }}" readonly disabled>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" name="nama_barang"
                               class="form-control @error('nama_barang') is-invalid @enderror"
                               value="{{ old('nama_barang', $barang->nama_barang) }}"
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
                                   value="{{ old('harga', $barang->harga) }}" placeholder="0" min="0" required>
                        </div>
                        @error('harga')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Satuan</label>
                        <input type="text" name="satuan"
                               class="form-control @error('satuan') is-invalid @enderror"
                               value="{{ old('satuan', $barang->satuan) }}"
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
                    </div>
                </form>

                {{-- Button di luar form --}}
                <div class="d-flex justify-content-end mt-2">
                    <button type="button" id="btnUpdate" class="btn btn-warning">
                        <i class="mdi mdi-content-save"></i> Update
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('#btnUpdate').on('click', function () {
        var form = document.getElementById('formEdit');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $('#btnUpdate').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...'
        );

        form.submit();
    });
});
</script>
@endpush