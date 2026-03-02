@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title"> Tambah Buku Baru </h3>
</div>

<div class="row">
  <div class="col-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Formulir Buku</h4>
        <p class="card-description"> Masukkan detail sesuai database </p>
        
        <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
          @csrf
          
          <div class="form-group">
            <label>Kategori</label>
            <select class="form-control" name="kategori_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($kategori as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>
          </div>

          <div class="form-group">
            <label>Kode Buku</label>
            <input type="text" class="form-control" name="kode" placeholder="Contoh: NV-01" required>
          </div>

          <div class="form-group">
            <label>Judul Buku</label>
            <input type="text" class="form-control" name="judul" placeholder="Judul Buku" required>
          </div>

          <div class="form-group">
            <label>Pengarang</label>
            <input type="text" class="form-control" name="pengarang" placeholder="Nama Pengarang" required>
          </div>

          <button type="submit" class="btn btn-gradient-primary me-2">Simpan</button>
          <a href="{{ route('buku.index') }}" class="btn btn-light">Batal</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection