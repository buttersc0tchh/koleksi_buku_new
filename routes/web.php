<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\BarangController;

// --- PUBLIC ROUTES (Tanpa Login) ---
Route::get('/', function () {
    return redirect()->route('login');
});

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

// OTP
Route::get('/verify-otp', [OtpController::class, 'showVerifyForm'])->name('verify.otp');
Route::post('/verify-otp', [OtpController::class, 'verify'])->name('verify.otp.submit');
Route::post('/resend-otp', [OtpController::class, 'resend'])->name('verify.otp.resend');

Auth::routes();

// --- PROTECTED ROUTES (Harus Login) ---
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // Fitur Utama Modul 1
    Route::resource('kategori', KategoriController::class);
    Route::resource('buku', BukuController::class);

    // Tag Harga
    Route::get('barang/cetak-pdf', [BarangController::class, 'cetakPdf'])->name('barang.cetakPdf');
    Route::resource('barang', BarangController::class);

    // Soal 2 - Form Barang Tanpa DB
    Route::get('/form-barang', function () {return view('formbarang.index');})->name('formbarang.index');
    Route::get('/form-barang-dt', function () {return view('formbarang.index_dt');})->name('formbarang.indexDt');

    // Soal 4 - Select Kota
    Route::get('/select-kota', function () {return view('selectkota.index');})->name('selectkota.index');

    // Fitur Dokumen (Editor & PDF)
    Route::prefix('dokumen')->group(function () {
        Route::get('/undangan', [DokumenController::class, 'undangan'])->name('undangan.index');
        Route::get('/sertifikat', [DokumenController::class, 'sertifikat'])->name('sertifikat.index');
        Route::get('/undangan/download', [PdfController::class, 'generateUndangan'])->name('pdf.undangan');
        Route::get('/sertifikat/download', [PdfController::class, 'generateSertifikat'])->name('pdf.sertifikat');
    });
});

// Debug SMTP
Route::get('/test-email', function () {
    Mail::raw('Tes OTP Email', function ($m) {
        $m->to('finaaidaysf@gmail.com')->subject('Tes SMTP Laravel');
    });
    return 'Cek inbox / spam.';
});