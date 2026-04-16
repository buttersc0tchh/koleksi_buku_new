@extends('layouts.master')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-camera"></i>
        </span> Tambah Customer 1 - Blob
    </h3>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Tambah Customer dengan Foto (Blob)</h4>
                <form action="{{ route('customer-data.store-blob') }}" method="POST" id="formCustomer">
                    @csrf

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    {{-- Alamat --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <input type="text" name="alamat" class="form-control" required>
                    </div>

                    {{-- Provinsi --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Provinsi</label>
                        <input type="text" name="provinsi" class="form-control" required>
                    </div>

                    {{-- Kota --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kota</label>
                        <input type="text" name="kota" class="form-control" required>
                    </div>

                    {{-- Kecamatan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kecamatan</label>
                        <input type="text" name="kecamatan" class="form-control" required>
                    </div>

                    {{-- Kodepos - Kelurahan --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Kodepos - Kelurahan</label>
                        <input type="text" name="kodepos_kelurahan" class="form-control" required>
                    </div>

                    {{-- Foto --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto</label>
                        <div class="text-center border rounded p-4" style="background:#f8f9fa;">
                            <img id="preview" style="display:none;width:100%;max-height:300px;border-radius:8px;margin-bottom:10px;">
                            <p id="noPreview" class="text-muted">Foto akan ditampilkan di sini</p>
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#cameraModal">
                                <i class="mdi mdi-camera"></i> Ambil Foto
                            </button>
                        </div>
                        <input type="hidden" name="foto" id="fotoBlob" required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save"></i> Simpan Data
                        </button>
                        <a href="{{ route('customer-data.index') }}" class="btn btn-secondary ms-2">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kamera -->
<div class="modal fade" id="cameraModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ambil Foto Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <video id="video" width="100%" height="400" autoplay style="background:#000;border-radius:8px;transform:scaleX(-1);"></video>
                <canvas id="canvas" style="display:none;"></canvas>
                <img id="snapshot" style="display:none;width:100%;border-radius:8px;margin-top:10px;">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="btnSnapshot">
                    <i class="mdi mdi-camera"></i> Ambil Foto
                </button>
                <button type="button" class="btn btn-warning" id="btnRetake" style="display:none;">
                    <i class="mdi mdi-refresh"></i> Ulangi
                </button>
                <button type="button" class="btn btn-success" id="btnSave" style="display:none;">
                    <i class="mdi mdi-check"></i> Simpan Foto
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// (script kamera tidak berubah, sama persis dengan yang sudah kamu punya)
const video     = document.getElementById('video');
const canvas    = document.getElementById('canvas');
const snapshot  = document.getElementById('snapshot');
const preview   = document.getElementById('preview');
const noPreview = document.getElementById('noPreview');
const fotoBlob  = document.getElementById('fotoBlob');
const cameraModal = document.getElementById('cameraModal');

const btnSnapshot = document.getElementById('btnSnapshot');
const btnRetake   = document.getElementById('btnRetake');
const btnSave     = document.getElementById('btnSave');

let stream = null;

document.querySelector('[data-bs-target="#cameraModal"]').addEventListener('click', function() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
        .then(s => {
            stream = s;
            video.srcObject = stream;
            video.style.display    = 'block';
            snapshot.style.display = 'none';
            btnSnapshot.style.display = 'inline-block';
            btnRetake.style.display   = 'none';
            btnSave.style.display     = 'none';
        })
        .catch(err => {
            alert('Akses kamera ditolak atau tidak tersedia.');
            console.error(err);
        });
});

btnSnapshot.addEventListener('click', function() {
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.scale(-1, 1);
    ctx.drawImage(video, -canvas.width, 0);
    const dataUrl = canvas.toDataURL('image/png');

    snapshot.src           = dataUrl;
    snapshot.style.display = 'block';
    video.style.display    = 'none';
    btnSnapshot.style.display = 'none';
    btnRetake.style.display   = 'inline-block';
    btnSave.style.display     = 'inline-block';

    fotoBlob.value = dataUrl;
});

btnRetake.addEventListener('click', function() {
    snapshot.style.display    = 'none';
    video.style.display       = 'block';
    btnSnapshot.style.display = 'inline-block';
    btnRetake.style.display   = 'none';
    btnSave.style.display     = 'none';
    fotoBlob.value = '';
});

btnSave.addEventListener('click', function() {
    preview.src            = fotoBlob.value;
    preview.style.display  = 'block';
    noPreview.style.display = 'none';

    const modal = bootstrap.Modal.getInstance(cameraModal);
    modal.hide();

    if (stream) stream.getTracks().forEach(track => track.stop());
});
</script>
@endpush