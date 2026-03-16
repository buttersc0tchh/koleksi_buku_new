@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-success text-white me-2">
            <i class="mdi mdi-cart"></i>
        </span> Point of Sales - Kasir
    </h3>
</div>

<div class="row">
    <div class="col-12 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Input Barang</h4>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold">Kode Barang</label>
                        <input type="text" id="kode_barang" class="form-control" placeholder="Scan/ketik kode barang...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold">Nama Barang</label>
                        <input type="text" id="nama_barang" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="font-weight-bold">Harga Barang</label>
                        <input type="text" id="harga_barang" class="form-control bg-light" readonly>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="font-weight-bold">Jumlah</label>
                        <input type="number" id="jumlah" class="form-control" value="1" min="1">
                    </div>
                    <div class="col-md-1 mb-3 d-flex align-items-end">
                        <button type="button" id="btnTambah" class="btn btn-success w-100" disabled>
                            <i class="mdi mdi-plus"></i>
                        </button>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <tr id="emptyRow">
                                <td colspan="6" class="text-center text-muted">Belum ada barang</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" class="text-right font-weight-bold">TOTAL</td>
                                <td colspan="2" class="font-weight-bold text-success" id="totalHarga">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="text-right mt-3">
                    <button type="button" id="btnBayar" class="btn btn-primary btn-lg" disabled>
                        <i class="mdi mdi-cash"></i> Bayar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function () {

    var barangDitemukan = false;
    var hargaSatuan     = 0;
    var idBarang        = '';
    var totalTransaksi  = 0;

    // Tekan Enter di kode barang → cari barang (jQuery AJAX)
    $('#kode_barang').on('keypress', function (e) {
        if (e.which === 13) {
            var kode = $(this).val().trim();
            if (kode === '') return;

            // Reset dulu
            barangDitemukan = false;
            hargaSatuan     = 0;
            idBarang        = '';
            $('#nama_barang').val('').removeClass('is-valid is-invalid');
            $('#harga_barang').val('').removeClass('is-valid is-invalid');
            $('#jumlah').val(1);
            $('#btnTambah').prop('disabled', true);

            $.ajax({
                url  : "{{ route('pos.cariBarang') }}",
                type : "GET",
                data : { kode: kode },
                success: function (response) {
                    if (response.status === 'success') {
                        barangDitemukan = true;
                        hargaSatuan     = response.data.harga;
                        idBarang        = response.data.id_barang;

                        $('#nama_barang').val(response.data.nama_barang).addClass('is-valid');
                        $('#harga_barang').val('Rp ' + parseInt(hargaSatuan).toLocaleString('id-ID')).addClass('is-valid');
                        $('#jumlah').val(1);
                        $('#btnTambah').prop('disabled', false);
                    } else {
                        $('#nama_barang').val('Barang tidak ditemukan!').addClass('is-invalid');
                        $('#harga_barang').val('').addClass('is-invalid');
                        $('#btnTambah').prop('disabled', true);
                    }
                },
                error: function () {
                    Swal.fire('Error!', 'Gagal mencari barang!', 'error');
                }
            });
        }
    });

    // Jumlah berubah → cek
    $('#jumlah').on('input', function () {
        if (barangDitemukan && parseInt($(this).val()) > 0) {
            $('#btnTambah').prop('disabled', false);
        } else {
            $('#btnTambah').prop('disabled', true);
        }
    });

    // Tambah ke tabel
    $('#btnTambah').on('click', function () {
        var jumlah   = parseInt($('#jumlah').val());
        var subtotal = hargaSatuan * jumlah;
        var namaBarang = $('#nama_barang').val();

        // Cek apakah kode sudah ada di tabel
        var existingRow = $('#tableBody tr[data-id="' + idBarang + '"]');
        if (existingRow.length > 0) {
            // Update jumlah dan subtotal
            var jumlahLama    = parseInt(existingRow.find('.col-jumlah').val());
            var jumlahBaru    = jumlahLama + jumlah;
            var subtotalBaru  = hargaSatuan * jumlahBaru;

            existingRow.find('.col-jumlah').val(jumlahBaru);
            existingRow.find('.col-subtotal').text('Rp ' + subtotalBaru.toLocaleString('id-ID'));
            existingRow.data('subtotal', subtotalBaru);
        } else {
            // Tambah row baru
            $('#emptyRow').remove();

            var row = '<tr data-id="' + idBarang + '" data-harga="' + hargaSatuan + '" data-subtotal="' + subtotal + '">' +
                '<td>' + idBarang + '</td>' +
                '<td>' + namaBarang + '</td>' +
                '<td>Rp ' + parseInt(hargaSatuan).toLocaleString('id-ID') + '</td>' +
                '<td><input type="number" class="form-control form-control-sm col-jumlah" value="' + jumlah + '" min="1" style="width:80px"></td>' +
                '<td class="col-subtotal">Rp ' + subtotal.toLocaleString('id-ID') + '</td>' +
                '<td><button class="btn btn-danger btn-sm btnHapus"><i class="mdi mdi-delete"></i></button></td>' +
                '</tr>';

            $('#tableBody').append(row);
        }

        updateTotal();

        // Reset form
        $('#kode_barang').val('').focus();
        $('#nama_barang').val('').removeClass('is-valid is-invalid');
        $('#harga_barang').val('').removeClass('is-valid is-invalid');
        $('#jumlah').val(1);
        $('#btnTambah').prop('disabled', true);
        barangDitemukan = false;
    });

    // Edit jumlah di tabel
    $('#tableBody').on('input', '.col-jumlah', function () {
        var row      = $(this).closest('tr');
        var harga    = parseInt(row.data('harga'));
        var jumlah   = parseInt($(this).val());

        if (jumlah < 1 || isNaN(jumlah)) return;

        var subtotal = harga * jumlah;
        row.find('.col-subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
        row.data('subtotal', subtotal);
        updateTotal();
    });

    // Hapus baris
    $('#tableBody').on('click', '.btnHapus', function () {
        $(this).closest('tr').remove();
        if ($('#tableBody tr').length === 0) {
            $('#tableBody').append('<tr id="emptyRow"><td colspan="6" class="text-center text-muted">Belum ada barang</td></tr>');
        }
        updateTotal();
    });

    // Update total
    function updateTotal() {
        totalTransaksi = 0;
        $('#tableBody tr[data-id]').each(function () {
            totalTransaksi += parseInt($(this).data('subtotal'));
        });
        $('#totalHarga').text('Rp ' + totalTransaksi.toLocaleString('id-ID'));
        $('#btnBayar').prop('disabled', totalTransaksi === 0);
    }

    // Bayar - pakai Axios
    $('#btnBayar').on('click', function () {
        var items = [];
        $('#tableBody tr[data-id]').each(function () {
            items.push({
                id_barang : $(this).data('id'),
                jumlah    : parseInt($(this).find('.col-jumlah').val()),
                subtotal  : parseInt($(this).data('subtotal')),
            });
        });

        // Spinner
        $('#btnBayar').prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...'
        );

        axios.post("{{ route('pos.bayar') }}", {
            items : items,
            total : totalTransaksi,
            _token: "{{ csrf_token() }}"
        })
        .then(function (response) {
            if (response.data.status === 'success') {
                Swal.fire({
                    icon : 'success',
                    title: 'Pembayaran Berhasil!',
                    text : 'Transaksi berhasil disimpan!',
                }).then(function () {
                    // Reset semua
                    $('#tableBody').html('<tr id="emptyRow"><td colspan="6" class="text-center text-muted">Belum ada barang</td></tr>');
                    $('#totalHarga').text('Rp 0');
                    $('#kode_barang').val('').focus();
                    $('#btnBayar').prop('disabled', true).html('<i class="mdi mdi-cash"></i> Bayar');
                    totalTransaksi = 0;
                });
            }
        })
        .catch(function () {
            Swal.fire('Error!', 'Gagal menyimpan transaksi!', 'error');
            $('#btnBayar').prop('disabled', false).html('<i class="mdi mdi-cash"></i> Bayar');
        });
    });

});
</script>
@endpush