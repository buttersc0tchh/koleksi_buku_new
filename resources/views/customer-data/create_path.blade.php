{{-- resources/views/customer-data/create_path.blade.php --}}
@extends('layouts.master')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Tambah Customer 2 (PATH)</h4>

    <form method="POST" action="{{ route('admin.customer.storePath') }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Foto (akan disimpan PATH)</label>
        <input type="file" name="foto" class="form-control" accept="image/*" capture="environment" required>
      </div>

      <button class="btn btn-gradient-primary">Simpan</button>
    </form>
  </div>
</div>
@endsection