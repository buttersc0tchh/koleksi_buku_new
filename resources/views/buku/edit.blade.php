@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">Edit Data Buku</h3>
</div>

<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Formulir Edit Buku</h4>
                <p class="card-description">Ubah data di bawah ini</p>

                <form id="formEditBuku" action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Kategori <span class="text-danger">*</span></label>
                        <select class="form-control" name="idkategori" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $kat)
                                <option value="{{ $kat->id }}" {{ $buku->idkategori == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kode Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="kode" value="{{ $buku->kode }}" required>
                    </div>

                    <div class="form-group">
                        <label>Judul Buku <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="judul" value="{{ $buku->judul }}" required>
                    </div>

                    <div class="form-group">
                        <label>Pengarang <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="pengarang" value="{{ $buku->pengarang }}" required>
                    </div>

                    <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
                </form>

                {{-- Button di luar form --}}
                <div class="mt-2">
                    <button type="button" id="btnUpdateBuku" class="btn btn-gradient-primary">
                        Update
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
    $('#btnUpdateBuku').on('click', function () {
        var form = document.getElementById('formEditBuku');

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        $('#btnUpdateBuku').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Menyimpan...'
        );

        form.submit();
    });
});
</script>
@endpush