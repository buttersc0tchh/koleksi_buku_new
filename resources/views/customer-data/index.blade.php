@extends('layouts.master')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="card-title mb-0">Data Customer</h4>
      <div>
        <a href="{{ route('admin.customer.createBlob') }}" class="btn btn-sm btn-primary">Tambah (BLOB)</a>
        <a href="{{ route('admin.customer.createPath') }}" class="btn btn-sm btn-secondary">Tambah (PATH)</a>
      </div>
    </div>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Foto BLOB</th>
            <th>Foto PATH</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($customers as $c)
            <tr>
              <td>{{ $c->id }}</td>
              <td>{{ $c->nama }}</td>
              <td>{{ $c->email ?: '-' }}</td>
              <td>
                @if ($c->foto_blob)
                  <img
                    src="{{ $c->foto_blob }}"
                    alt="Foto BLOB {{ $c->nama }}"
                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                  >
                @else
                  -
                @endif
              </td>
              <td>
                @if ($c->foto_path)
                  <img
                    src="{{ asset('storage/' . $c->foto_path) }}"
                    alt="Foto PATH {{ $c->nama }}"
                    style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;"
                  >
                @else
                  -
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">Belum ada data customer</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
