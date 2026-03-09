@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-table-large"></i>
        </span> Form Barang - DataTables
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
                        <button type="button" id="btnTambah" class="btn btn-success w-100">
                            <i class="mdi mdi-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="tableBarang" class="table table-bordered table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
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

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
    var counter = 1;
    var selectedRow = null;
    var selectedDtRow = null;

    var table = $('#tableBarang').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/id.json' },
        data: [],
        columns: [
            { title: 'ID Barang' },
            { title: 'Nama Barang' },
            { title: 'Harga' }
        ]
    });

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
            var hargaFormat = 'Rp ' + parseInt(harga).toLocaleString('id-ID');
            var idBarang    = 'BRG' + String(counter).padStart(3, '0');
            counter++;

            table.row.add([idBarang, nama, hargaFormat]).draw();

            $('#nama_barang').val('');
            $('#harga').val('');

            $('#btnTambah').prop('disabled', false).html('<i class="mdi mdi-plus"></i> Tambah');
        }, 800);
    });

    // Klik baris → buka modal
    $('#tableBarang tbody').on('click', 'tr', function () {
        selectedDtRow = table.row(this);
        var data = selectedDtRow.data();

        $('#edit_id').val(data[0]);
        $('#edit_nama').val(data[1]);
        // Ambil angka dari format Rp
        $('#edit_harga').val(data[2].replace(/[^0-9]/g, ''));

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

        selectedDtRow.data([$('#edit_id').val(), nama, hargaFormat]).draw();

        bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
    });

    // Hapus data
    $('#btnHapus').on('click', function () {
        selectedDtRow.remove().draw();
        bootstrap.Modal.getInstance(document.getElementById('modalEdit')).hide();
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