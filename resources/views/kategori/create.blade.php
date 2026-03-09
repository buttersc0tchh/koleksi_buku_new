@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">Tambah Kategori</h3>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Kategori</a></li>
            <li class="breadcrumb-item active">Tambah</li>
        </ol>
    </nav>
</div>

<div class="row">
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Formulir Kategori Baru</h4>
                <p class="card-description">Masukkan nama kategori buku</p>

                <form id="formTambahKategori" action="{{ route('kategori.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="nama_kategori">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text"
                               class="form-control @error('nama_kategori') is-invalid @enderror"
                               id="nama_kategori" name="nama_kategori"
                               placeholder="Contoh: Fiksi Ilmiah"
                               value="{{ old('nama_kategori') }}" required>
                        @error('nama_kategori')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <a href="{{ route('kategori.index') }}" class="btn btn-light">Batal</a>
                </form>

                {{-- Button di luar form --}}
                <div class="mt-2">
                    <button type="button" id="btnSimpanKategori" class="btn btn-gradient-primary">
                        Simpan
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
    $('#btnSimpanKategori').on('click', function () {
        var form = document.getElementById('formTambahKategori');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $('#btnSimpanKategori').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...'
        );

        form.submit();
    });
});
</script>
@endpush