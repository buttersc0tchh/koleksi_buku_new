@extends('layouts.master')

@section('content')
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-book-open-page-variant"></i>
    </span> Data Buku
  </h3>
  <nav aria-label="breadcrumb">
    <ul class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">
        <a href="{{ route('buku.create') }}" class="btn btn-gradient-primary btn-fw">
            <i class="mdi mdi-plus"></i> Tambah Buku
        </a>
      </li>
    </ul>
  </nav>
</div>

<div class="row">
  <div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Daftar Pustaka</h4>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th> No </th>
                <th> Kategori </th>
                <th> Kode </th>
                <th> Judul </th>
                <th> Pengarang </th>
                <th> Aksi </th>
              </tr>
            </thead>
            <tbody>
              @foreach($buku as $item)
              <tr>
                <td> {{ $loop->iteration }} </td>
                <td> 
                    {{-- Mengecek nama_kategori dari relasi --}}
                    @if($item->kategori && $item->kategori->nama_kategori == 'Novel')
                        <label class="badge badge-gradient-danger">Novel</label>
                    @elseif($item->kategori && $item->kategori->nama_kategori == 'Biografi')
                        <label class="badge badge-gradient-success">Biografi</label>
                    @elseif($item->kategori && $item->kategori->nama_kategori == 'Komik')
                        <label class="badge badge-gradient-warning">Komik</label>
                    @else
                        <label class="badge badge-gradient-info">{{ $item->kategori->nama_kategori ?? 'Tanpa Kategori' }}</label>
                    @endif
                </td>
                <td> {{ $item->kode }} </td>
                <td> {{ $item->judul }} </td>
                <td> {{ $item->pengarang }} </td>
                <td>
                    {{-- FIX: Menggunakan idbuku sesuai Model dan Modul 1 --}}
                    <a href="{{ route('buku.edit', $item->idbuku) }}" class="btn btn-inverse-info btn-icon btn-sm" style="display:inline-flex; align-items:center; justify-content:center;">
                        <i class="mdi mdi-pencil"></i>
                    </a>

                    <form action="{{ route('buku.destroy', $item->idbuku) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin ingin menghapus?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-inverse-danger btn-icon btn-sm">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection