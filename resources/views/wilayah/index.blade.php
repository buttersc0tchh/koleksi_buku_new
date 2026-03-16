@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-map-marker"></i>
        </span> Wilayah Indonesia - AJAX
    </h3>
</div>

<div class="row">
    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select Wilayah (jQuery AJAX)</h4>

                <div class="mb-3">
                    <label class="font-weight-bold">Provinsi</label>
                    <select id="provinsi" class="form-control">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Kota</label>
                    <select id="kota" class="form-control" disabled>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Kecamatan</label>
                    <select id="kecamatan" class="form-control" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Kelurahan</label>
                    <select id="kelurahan" class="form-control" disabled>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 grid-margin">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Select Wilayah (Axios)</h4>

                <div class="mb-3">
                    <label class="font-weight-bold">Provinsi</label>
                    <select id="provinsi2" class="form-control">
                        <option value="">-- Pilih Provinsi --</option>
                        @foreach($provinces as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Kota</label>
                    <select id="kota2" class="form-control" disabled>
                        <option value="">-- Pilih Kota --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Kecamatan</label>
                    <select id="kecamatan2" class="form-control" disabled>
                        <option value="">-- Pilih Kecamatan --</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="font-weight-bold">Kelurahan</label>
                    <select id="kelurahan2" class="form-control" disabled>
                        <option value="">-- Pilih Kelurahan --</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function () {

    // =====================
    // VERSI JQUERY AJAX
    // =====================

    // Provinsi berubah → load kota
    $('#provinsi').on('change', function () {
        var province_id = $(this).val();

        // Reset bawahnya
        $('#kota').html('<option value="">-- Pilih Kota --</option>').prop('disabled', true);
        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);

        if (province_id === '') return;

        $.ajax({
            url: "{{ route('wilayah.kota') }}",
            type: "GET",
            data: { province_id: province_id },
            success: function (response) {
                if (response.status === 'success') {
                    var options = '<option value="">-- Pilih Kota --</option>';
                    $.each(response.data, function (i, kota) {
                        options += '<option value="' + kota.id + '">' + kota.name + '</option>';
                    });
                    $('#kota').html(options).prop('disabled', false);
                }
            },
            error: function () {
                alert('Gagal mengambil data kota!');
            }
        });
    });

    // Kota berubah → load kecamatan
    $('#kota').on('change', function () {
        var regency_id = $(this).val();

        $('#kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);

        if (regency_id === '') return;

        $.ajax({
            url: "{{ route('wilayah.kecamatan') }}",
            type: "GET",
            data: { regency_id: regency_id },
            success: function (response) {
                if (response.status === 'success') {
                    var options = '<option value="">-- Pilih Kecamatan --</option>';
                    $.each(response.data, function (i, kec) {
                        options += '<option value="' + kec.id + '">' + kec.name + '</option>';
                    });
                    $('#kecamatan').html(options).prop('disabled', false);
                }
            },
            error: function () {
                alert('Gagal mengambil data kecamatan!');
            }
        });
    });

    // Kecamatan berubah → load kelurahan
    $('#kecamatan').on('change', function () {
        var district_id = $(this).val();

        $('#kelurahan').html('<option value="">-- Pilih Kelurahan --</option>').prop('disabled', true);

        if (district_id === '') return;

        $.ajax({
            url: "{{ route('wilayah.kelurahan') }}",
            type: "GET",
            data: { district_id: district_id },
            success: function (response) {
                if (response.status === 'success') {
                    var options = '<option value="">-- Pilih Kelurahan --</option>';
                    $.each(response.data, function (i, kel) {
                        options += '<option value="' + kel.id + '">' + kel.name + '</option>';
                    });
                    $('#kelurahan').html(options).prop('disabled', false);
                }
            },
            error: function () {
                alert('Gagal mengambil data kelurahan!');
            }
        });
    });

    // =====================
    // VERSI AXIOS
    // =====================

    // Provinsi berubah → load kota
    document.getElementById('provinsi2').addEventListener('change', function () {
        var province_id = this.value;

        resetSelect('kota2', '-- Pilih Kota --');
        resetSelect('kecamatan2', '-- Pilih Kecamatan --');
        resetSelect('kelurahan2', '-- Pilih Kelurahan --');

        if (province_id === '') return;

        axios.get("{{ route('wilayah.kota') }}", { params: { province_id: province_id } })
        .then(function (response) {
            if (response.data.status === 'success') {
                fillSelect('kota2', response.data.data, '-- Pilih Kota --');
            }
        })
        .catch(function () {
            alert('Gagal mengambil data kota!');
        });
    });

    // Kota berubah → load kecamatan
    document.getElementById('kota2').addEventListener('change', function () {
        var regency_id = this.value;

        resetSelect('kecamatan2', '-- Pilih Kecamatan --');
        resetSelect('kelurahan2', '-- Pilih Kelurahan --');

        if (regency_id === '') return;

        axios.get("{{ route('wilayah.kecamatan') }}", { params: { regency_id: regency_id } })
        .then(function (response) {
            if (response.data.status === 'success') {
                fillSelect('kecamatan2', response.data.data, '-- Pilih Kecamatan --');
            }
        })
        .catch(function () {
            alert('Gagal mengambil data kecamatan!');
        });
    });

    // Kecamatan berubah → load kelurahan
    document.getElementById('kecamatan2').addEventListener('change', function () {
        var district_id = this.value;

        resetSelect('kelurahan2', '-- Pilih Kelurahan --');

        if (district_id === '') return;

        axios.get("{{ route('wilayah.kelurahan') }}", { params: { district_id: district_id } })
        .then(function (response) {
            if (response.data.status === 'success') {
                fillSelect('kelurahan2', response.data.data, '-- Pilih Kelurahan --');
            }
        })
        .catch(function () {
            alert('Gagal mengambil data kelurahan!');
        });
    });

    // Helper functions
    function resetSelect(id, placeholder) {
        var sel = document.getElementById(id);
        sel.innerHTML = '<option value="">' + placeholder + '</option>';
        sel.disabled = true;
    }

    function fillSelect(id, data, placeholder) {
        var sel = document.getElementById(id);
        var options = '<option value="">' + placeholder + '</option>';
        data.forEach(function (item) {
            options += '<option value="' + item.id + '">' + item.name + '</option>';
        });
        sel.innerHTML = options;
        sel.disabled = false;
    }
});
</script>
@endpush