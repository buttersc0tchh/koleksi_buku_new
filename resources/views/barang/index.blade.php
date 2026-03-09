@extends('layouts.master')

@section('title', 'Tag Harga - Data Barang')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">
                        <i class="mdi mdi-tag text-primary"></i> Data Barang & Tag Harga
                    </h4>
                    <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">
                        <i class="mdi mdi-plus"></i> Tambah Barang
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        {{ $errors->first() }}
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                    </div>
                @endif

                <form id="formCetak" action="{{ route('barang.cetakPdf') }}" method="GET" target="_blank">

                    {{-- Panel Koordinat --}}
                    <div class="card border mb-3" style="background:#f8f9fa;">
                        <div class="card-body py-3">
                            <h6 class="mb-2 font-weight-bold">
                                <i class="mdi mdi-printer"></i> Pengaturan Cetak Tag Harga
                            </h6>
                            <p class="text-muted small mb-3">
                                Kertas label <strong>Tom n Jerry No. 108</strong> berukuran 5 kolom × 8 baris (40 label/halaman).<br>
                                Masukkan koordinat posisi <strong>awal</strong> pencetakan jika beberapa label sudah terpakai.
                            </p>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Kolom X <small class="text-muted">(1–5)</small></label>
                                    <input type="number" name="koordinat_x" id="koordinat_x"
                                           class="form-control" value="1" min="1" max="5" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="font-weight-bold">Baris Y <small class="text-muted">(1–8)</small></label>
                                    <input type="number" name="koordinat_y" id="koordinat_y"
                                           class="form-control" value="1" min="1" max="8" required>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-success w-100" id="btnCetak">
                                        <i class="mdi mdi-file-pdf-box"></i> Cetak PDF
                                    </button>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="button" class="btn btn-outline-secondary w-100" id="btnPreview">
                                        <i class="mdi mdi-eye"></i> Preview Grid
                                    </button>
                                </div>
                            </div>

                            <div id="previewGrid" class="mt-3" style="display:none;">
                                <hr>
                                <p class="font-weight-bold mb-1">Preview Posisi Label</p>
                                <p class="text-muted small mb-2">🟦 Mulai cetak &nbsp;|&nbsp; 🟩 Tersedia &nbsp;|&nbsp; ⬜ Sudah terpakai</p>
                                <div id="gridContainer"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel DataTables --}}
                    <div class="table-responsive">
                        <table id="tableBarang" class="table table-bordered table-hover table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="40" class="text-center">
                                        <input type="checkbox" id="checkAll">
                                    </th>
                                    <th>ID Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Satuan</th>
                                    <th width="130" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangs as $b)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" name="selected[]"
                                               value="{{ $b->id_barang }}" class="checkItem">
                                    </td>
                                    <td><span class="badge badge-secondary">{{ $b->id_barang }}</span></td>
                                    <td>{{ $b->nama_barang }}</td>
                                    <td>Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                                    <td>{{ $b->satuan }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('barang.edit', $b->id_barang) }}"
                                           class="btn btn-warning btn-sm">
                                            <i class="mdi mdi-pencil"></i>
                                        </a>
                                        <form action="{{ route('barang.destroy', $b->id_barang) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin hapus barang ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="mdi mdi-delete"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-2">
                        <span id="infoSelected" class="text-muted small">0 barang dipilih</span>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
<style>
    .grid-label {
        display: inline-block;
        width: 52px; height: 36px;
        border: 1px solid #aaa;
        margin: 2px;
        line-height: 36px;
        text-align: center;
        font-size: 10px;
        border-radius: 4px;
        font-weight: bold;
        cursor: default;
    }
    .grid-used      { background: #f0f0f0; color: #bbb; }
    .grid-available { background: #d4edda; color: #155724; }
    .grid-start     { background: #cce5ff; color: #004085; border: 2px solid #004085; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {

    // Init DataTables - tampilkan semua data sekaligus
    var table = $('#tableBarang').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        columnDefs: [{ orderable: false, targets: [0, 5] }],
        pageLength: -1,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]]
    });

    // Checkbox Pilih Semua (semua halaman)
    $('#checkAll').on('change', function () {
        var checked = this.checked;
        table.rows().every(function () {
            $(this.node()).find('.checkItem').prop('checked', checked);
        });
        updateInfo();
    });

    $(document).on('change', '.checkItem', function () {
        var total   = table.rows().count();
        var checked = $('.checkItem:checked').length;
        $('#checkAll').prop('checked', total === checked);
        updateInfo();
    });

    function updateInfo() {
        var count = $('.checkItem:checked').length;
        $('#infoSelected').text(count + ' barang dipilih');
    }

    // Tombol Cetak PDF
    $('#btnCetak').on('click', function () {
        if ($('.checkItem:checked').length === 0) {
            alert('Pilih minimal 1 barang yang akan dicetak!');
            return false;
        }
        $('#formCetak').submit();
    });

    // Preview Grid
    $('#btnPreview').on('click', function () {
        renderGrid();
        $('#previewGrid').show();
    });

    $('#koordinat_x, #koordinat_y').on('change', function () {
        if ($('#previewGrid').is(':visible')) renderGrid();
    });

    function renderGrid() {
        var x        = parseInt($('#koordinat_x').val()) || 1;
        var y        = parseInt($('#koordinat_y').val()) || 1;
        var startPos = (y - 1) * 5 + (x - 1);
        var html     = '';

        for (var i = 0; i < 40; i++) {
            if (i % 5 === 0 && i !== 0) html += '<br>';
            var col = i % 5 + 1;
            var row = Math.floor(i / 5) + 1;
            if (i < startPos) {
                html += '<span class="grid-label grid-used" title="Baris ' + row + ', Kolom ' + col + '">✕</span>';
            } else if (i === startPos) {
                html += '<span class="grid-label grid-start" title="Mulai cetak di sini">▶ ' + row + ',' + col + '</span>';
            } else {
                html += '<span class="grid-label grid-available" title="Baris ' + row + ', Kolom ' + col + '">' + row + ',' + col + '</span>';
            }
        }
        $('#gridContainer').html(html);
    }

});
</script>
@endpush