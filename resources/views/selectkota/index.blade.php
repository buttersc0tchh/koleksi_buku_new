@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker"></i>
        </span> Select Kota
    </h3>
</div>

<div class="row">

    {{-- Card 1: Select Biasa --}}
    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select</h4>

                <div class="mb-3">
                    <label class="font-weight-bold">Tambah Kota</label>
                    <div class="input-group">
                        <input type="text" id="input_kota1" class="form-control" placeholder="Nama kota baru...">
                        <button class="btn btn-primary" id="btnTambahKota1">
                            <i class="mdi mdi-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Pilih Kota</label>
                    <select id="select1" class="form-control">
                        <option value="">-- Pilih Kota --</option>
                        <option value="Surabaya">Surabaya</option>
                        <option value="Jakarta">Jakarta</option>
                        <option value="Bandung">Bandung</option>
                        <option value="Yogyakarta">Yogyakarta</option>
                        <option value="Semarang">Semarang</option>
                    </select>
                </div>

                <div class="mt-3 p-3 bg-light rounded">
                    <strong>Kota Terpilih:</strong>
                    <span id="kotaTerpilih1" class="text-primary ms-2">-</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Card 2: Select2 --}}
    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select 2</h4>

                <div class="mb-3">
                    <label class="font-weight-bold">Tambah Kota</label>
                    <div class="input-group">
                        <input type="text" id="input_kota2" class="form-control" placeholder="Nama kota baru...">
                        <button class="btn btn-success" id="btnTambahKota2">
                            <i class="mdi mdi-plus"></i> Tambah
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Pilih Kota</label>
                    <select id="select2kota" class="form-control" style="width:100%">
                        <option value="">-- Pilih Kota --</option>
                        <option value="Surabaya">Surabaya</option>
                        <option value="Jakarta">Jakarta</option>
                        <option value="Bandung">Bandung</option>
                        <option value="Yogyakarta">Yogyakarta</option>
                        <option value="Semarang">Semarang</option>
                    </select>
                </div>

                <div class="mt-3 p-3 bg-light rounded">
                    <strong>Kota Terpilih:</strong>
                    <span id="kotaTerpilih2" class="text-success ms-2">-</span>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .select2-container .select2-selection--single {
        height: calc(1.5em + 0.75rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: calc(1.5em + 0.75rem + 2px);
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 1.5;
        padding-left: 0;
        color: #495057;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {

    // Init Select2
    $('#select2kota').select2({
        placeholder: '-- Pilih Kota --',
        allowClear: true
    });

    // Select biasa - tampil kota terpilih
    $('#select1').on('change', function () {
        var val = $(this).val();
        $('#kotaTerpilih1').text(val || '-');
    });

    // Select2 - tampil kota terpilih
    $('#select2kota').on('change', function () {
        var val = $(this).val();
        $('#kotaTerpilih2').text(val || '-');
    });

    // Tambah kota ke select biasa
    $('#btnTambahKota1').on('click', function () {
        var kota = $('#input_kota1').val().trim();
        if (kota === '') {
            $('#input_kota1').addClass('is-invalid');
            return;
        }
        $('#input_kota1').removeClass('is-invalid');
        $('#select1').append('<option value="' + kota + '">' + kota + '</option>');
        $('#input_kota1').val('');
    });

    // Tambah kota ke select2
    $('#btnTambahKota2').on('click', function () {
        var kota = $('#input_kota2').val().trim();
        if (kota === '') {
            $('#input_kota2').addClass('is-invalid');
            return;
        }
        $('#input_kota2').removeClass('is-invalid');

        // Tambah option ke select2
        var newOption = new Option(kota, kota, false, false);
        $('#select2kota').append(newOption).trigger('change');

        $('#input_kota2').val('');
    });

    $('#input_kota1').on('input', function () { $(this).removeClass('is-invalid'); });
    $('#input_kota2').on('input', function () { $(this).removeClass('is-invalid'); });

});
</script>
@endpush