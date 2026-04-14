{{-- resources/views/customer-data/create_blob.blade.php --}}
@extends('layouts.master')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title">Tambah Customer 1 (BLOB)</h4>

    @if ($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.customer.storeBlob') }}" enctype="multipart/form-data">
      @csrf

      <div class="mb-3">
        <label class="form-label">Nama</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Email (opsional)</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Foto (akan disimpan BLOB)</label>
        <input id="foto" type="file" name="foto" class="form-control" accept="image/*" capture="environment" required>
        <small class="text-muted">Di HP akan muncul opsi kamera.</small>
      </div>

      <div class="mb-3">
        <img id="preview" src="" alt="" style="display:none; max-width:220px; border-radius:8px;">
      </div>

      <button class="btn btn-gradient-primary">Simpan</button>
      <a href="{{ route('admin.customer.index') }}" class="btn btn-light">Kembali</a>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const input = document.getElementById('foto');
  const preview = document.getElementById('preview');

  if (input) {
    input.addEventListener('change', function (e) {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      preview.src = URL.createObjectURL(file);
      preview.style.display = 'block';
    });
  }
</script>
@endpush
