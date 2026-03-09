@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">Tambah Buku Baru</h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Formulir Buku</h4>
                <p class="card-description">Masukkan detail sesuai database</p>

                <form id="formTambahBuku" action="{{ route('buku.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select class="form-control" name="idkategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kode Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="kode" placeholder="Contoh: NV-01" required>
                    </div>

                    <div class="form-group">
                        <label>Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul" placeholder="Judul Buku" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pengarang" placeholder="Nama Pengarang" required>
                    </div>

                    <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                </form>

                {{-- Button di luar form --}}
                <div class="mt-2">
                    <button type="button" id="btnSimpanBuku" class="btn btn-gradient-primary">
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
    $('#btnSimpanBuku').on('click', function () {
        var form = document.getElementById('formTambahBuku');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $('#btnSimpanBuku').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...'
        );

        form.submit();
    });
});
</script>
@endpush