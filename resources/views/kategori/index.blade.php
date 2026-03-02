@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-format-list-bulleted"></i>
    </span> Data Kategori
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('kategori.create') }}" class="btn btn-gradient-primary btn-fw">
            <i class="mdi mdi-plus"></i> Tambah Kategori
        </a>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Kategori Buku</h4>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th style="width: 10%"> No </th>
                <th> Nama Kategori </th>
                <th style="width: 20%"> Aksi </th>
              </tr>
            </thead>
            <tbody>
              @forelse($kategori as $item)
              <tr>
                <td> {{ $loop->iteration }} </td>
                <td> 
                    <span class="badge badge-outline-primary">{{ $item->nama_kategori }}</span> 
                </td>
                <td>
                    <a href="{{ route('kategori.edit', $item->id) }}" class="btn btn-inverse-info btn-icon btn-sm" style="display:inline-flex; align-items:center; justify-content:center;">
                        <i class="mdi mdi-pencil"></i>
                    </a>

                    <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-inverse-danger btn-icon btn-sm">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </form>
                </td>
              </tr>
              @empty
              <tr>
                  <td colspan="3" class="text-center text-muted">Belum ada data kategori.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection