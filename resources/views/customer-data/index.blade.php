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
            <th>BLOB?</th>
            <th>PATH?</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($customers as $c)
            <tr>
              <td>{{ $c->id }}</td>
              <td>{{ $c->nama }}</td>
              <td>{{ $c->email }}</td>
              <td>{{ $c->foto_blob ? 'ada' : '-' }}</td>
              <td>{{ $c->foto_path ? $c->foto_path : '-' }}</td>
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