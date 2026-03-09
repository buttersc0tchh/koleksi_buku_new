@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-table"></i>
        </span> Form Barang - Table Biasa
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Barang</h4>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="font-weight-bold">Nama Barang <span class="text-danger">*</span></label>
                        <input type="text" id="nama_barang" class="form-control" placeholder="Nama Barang">
                    </div>
                    <div class="col-md-4">
                        <label class="font-weight-bold">Harga <span class="text-danger">*</span></label>
                        <input type="number" id="harga" class="form-control" placeholder="Harga" min="0">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="button" id="btnTambah" class="btn btn-primary w-100">
                            <i class="mdi mdi-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr id="emptyRow">
                                <td colspan="3" class="text-center text-muted">Belum ada data</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="font-weight-bold">ID Barang</label>
                    <input type="text" id="edit_id" class="form-control" readonly>
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold">Nama Barang <span class="text-danger">*</span></label>
                    <input type="text" id="edit_nama" class="form-control" placeholder="Nama Barang">
                </div>
                <div class="mb-3">
                    <label class="font-weight-bold">Harga <span class="text-danger">*</span></label>
                    <input type="number" id="edit_harga" class="form-control" placeholder="Harga" min="0">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnHapus" class="btn btn-danger">
                    <i class="mdi mdi-delete"></i> Hapus
                </button>
                <button type="button" id="btnUbah" class="btn btn-primary">
                    <i class="mdi mdi-check"></i> Ubah
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    var counter = 1;
    var selectedRow = null;

    // Tambah data
    $('#btnTambah').on('click', function () {
        var nama  = $('#nama_barang').val().trim();
        var harga = $('#harga').val().trim();

        if (nama === '' || harga === '') {
            if (nama === '') $('#nama_barang').addClass('is-invalid');
            if (harga === '') $('#harga').addClass('is-invalid');
            return;
        }

        $('#nama_barang').removeClass('is-invalid');
        $('#harga').removeClass('is-invalid');

        $('#btnTambah').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm"></span> Menambah...'
        );

        setTimeout(function () {
            $('#emptyRow').remove();

            var hargaFormat = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
            var idBarang    = 'BRG' + String(counter).padStart(3, '0');
            counter++;

            var row = $('<tr style="cursor:pointer"></tr>')
                .data('id', idBarang)
                .data('nama', nama)
                .data('harga', harga)
                .append('<td>' + idBarang + '</td>')
                .append('<td>' + nama + '</td>')
                .append('<td>' + hargaFormat + '</td>');

            $('#tableBody').append(row);

            $('#nama_barang').val('');
            $('#harga').val('');

            $('#btnTambah').prop('disabled', false).html('<i class="mdi mdi-plus"></i> Tambah');
        }, 800);
    });

    // Klik baris → buka modal
    $('#tableBody').on('click', 'tr', function () {
        selectedRow = $(this);
        var id    = selectedRow.data('id');
        var nama  = selectedRow.data('nama');
        var harga = selectedRow.data('harga');

        $('#edit_id').val(id);
        $('#edit_nama').val(nama);
        $('#edit_harga').val(harga);

        var modal = new bootstrap.Modal(document.getElementById('modalEdit'));
        modal.show();
    });

    // Ubah data
    $('#btnUbah').on('click', function () {
        var nama  = $('#edit_nama').val().trim();
        var harga = $('#edit_harga').val().trim();

        if (nama === '' || harga === '') {
            if (nama === '') $('#edit_nama').addClass('is-invalid');
            if (harga === '') $('#edit_harga').addClass('is-invalid');
            return;
        }

        var hargaFormat = 'Rp ' + parseInt(harga).toLocaleString('id-ID');

        selectedRow.data('nama', nama);
        selectedRow.data('harga', harga);
        selectedRow.find('td:eq(1)').text(nama);
        selectedRow.find('td:eq(2)').text(hargaFormat);

        bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
    });

    // Hapus data
    $('#btnHapus').on('click', function () {
        selectedRow.remove();
        bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();

        if ($('#tableBody tr').length === 0) {
            $('#tableBody').append('<tr id="emptyRow"><td colspan="3" class="text-center text-muted">Belum ada data</td></tr>');
        }
    });

    $('#nama_barang, #harga').on('input', function () {
        $(this).removeClass('is-invalid');
    });
    $('#edit_nama, #edit_harga').on('input', function () {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endpush