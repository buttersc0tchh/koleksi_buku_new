@extends('layouts.master')

@section('content')
<style>
@media print {
    /* Sembunyikan Sidebar, Navbar, dan Footer dashboard */
    .sidebar, .navbar, .footer, .page-header, .card-title, .card-description, .forms-sample {
        display: none !important;
    }

    /* Hilangkan margin/padding bawaan dashboard agar tidak kosong di atas */
    .content-wrapper {
        padding: 0 !important;
        background: white !important;
    }

    .main-panel {
        width: 100% !important;
    }

    /* Pastikan hanya kotak pratinjau yang tampil dan ukurannya pas satu halaman */
    #printableArea {
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }

    /* Sembunyikan kolom form input (kolom kiri) */
    .col-md-5 {
        display: none !important;
    }

    /* Lebarkan kolom pratinjau jadi satu halaman penuh */
    .col-md-7 {
        width: 100% !important;
        flex: 0 0 100% !important;
        max-width: 100% !important;
    }
}
</style>
<div class="page-header">
  <h3 class="page-title">
    <span class="page-title-icon bg-gradient-primary text-white me-2">
      <i class="mdi mdi-email-edit"></i>
    </span> Generator Undangan
  </h3>
</div>

<div class="row">
  <div class="col-md-5 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Edit Detail Undangan</h4>
        <p class="card-description"> Masukkan detail untuk mengubah tampilan undangan secara langsung. </p>
        
        <form class="forms-sample">
          <div class="form-group">
            <label for="inputNama">Nama Tamu</label>
            <input type="text" class="form-control" id="inputNama" placeholder="Nama Penerima" onkeyup="updatePreview()">
          </div>
          <div class="form-group">
            <label for="inputAcara">Nama Acara</label>
            <input type="text" class="form-control" id="inputAcara" placeholder="Nama Kegiatan" onkeyup="updatePreview()">
          </div>
          <div class="form-group">
            <label for="inputTgl">Tanggal</label>
            <input type="date" class="form-control" id="inputTgl" onchange="updatePreview()">
          </div>
          <div class="form-group">
            <label for="inputLokasi">Lokasi</label>
            <textarea class="form-control" id="inputLokasi" rows="3" onkeyup="updatePreview()"></textarea>
          </div>
          <button type="button" onclick="window.print()" class="btn btn-gradient-primary me-2">Cetak ke PDF</button>
        </form>
      </div>
    </div>
  </div>

  <div class="col-md-7 grid-margin stretch-card">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">Pratinjau Undangan</h4>
        <div id="printableArea" style="border: 2px dashed #b66dff; padding: 40px; border-radius: 15px; background: #fff; min-height: 400px; position: relative;">
            <div class="text-center">
                <h1 style="font-family: 'Georgia', serif; color: #b66dff; border-bottom: 2px solid #b66dff; display: inline-block; padding-bottom: 10px;">UNDANGAN RESMI</h1>
                <p class="mt-4" style="font-size: 1.1rem;">Kepada Yth:</p>
                <h3 id="prevNama" style="font-weight: bold; color: #333;">[Nama Tamu]</h3>
                <hr style="width: 50%; margin: 20px auto;">
                <p>Kami mengharap kehadiran Bapak/Ibu dalam acara:</p>
                <h4 id="prevAcara" style="color: #b66dff; font-weight: bold;">[Nama Acara]</h4>
                <div class="mt-4" style="background: #f8f9fa; padding: 15px; border-radius: 10px;">
                    <p><strong>Waktu:</strong> <span id="prevTgl">YYYY-MM-DD</span></p>
                    <p><strong>Tempat:</strong> <span id="prevLokasi">[Lokasi Acara]</span></p>
                </div>
                <p class="mt-4" style="font-style: italic;">Terima kasih atas perhatiannya.</p>
                <p>Hormat kami, <strong>{{ Auth::user()->name }}</strong></p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    function updatePreview() {
        document.getElementById('prevNama').innerText = document.getElementById('inputNama').value || '[Nama Tamu]';
        document.getElementById('prevAcara').innerText = document.getElementById('inputAcara').value || '[Nama Acara]';
        document.getElementById('prevTgl').innerText = document.getElementById('inputTgl').value || 'YYYY-MM-DD';
        document.getElementById('prevLokasi').innerText = document.getElementById('inputLokasi').value || '[Lokasi Acara]';
    }
</script>
@endsection