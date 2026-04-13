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
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerDataController;

// --- PUBLIC ROUTES (Tanpa Login) ---
Route::get('/', function () {
    return redirect()->route('customer.index');
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
    Route::get('/form-barang', function () { return view('formbarang.index'); })->name('formbarang.index');
    Route::get('/form-barang-dt', function () { return view('formbarang.index_dt'); })->name('formbarang.indexDt');

    // Soal 4 - Select Kota
    Route::get('/select-kota', function () { return view('selectkota.index'); })->name('selectkota.index');

    // Fitur Dokumen (Editor & PDF)
    Route::prefix('dokumen')->group(function () {
        Route::get('/undangan', [DokumenController::class, 'undangan'])->name('undangan.index');
        Route::get('/sertifikat', [DokumenController::class, 'sertifikat'])->name('sertifikat.index');
        Route::get('/undangan/download', [PdfController::class, 'generateUndangan'])->name('pdf.undangan');
        Route::get('/sertifikat/download', [PdfController::class, 'generateSertifikat'])->name('pdf.sertifikat');
    });

    // Studi Kasus 1 - Wilayah AJAX
    Route::get('/wilayah', [WilayahController::class, 'index'])->name('wilayah.index');
    Route::get('/wilayah/kota', [WilayahController::class, 'getKota'])->name('wilayah.kota');
    Route::get('/wilayah/kecamatan', [WilayahController::class, 'getKecamatan'])->name('wilayah.kecamatan');
    Route::get('/wilayah/kelurahan', [WilayahController::class, 'getKelurahan'])->name('wilayah.kelurahan');

    // Studi Kasus 2 - POS/Kasir
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/pos/cari-barang', [PosController::class, 'cariBarang'])->name('pos.cariBarang');
    Route::post('/pos/bayar', [PosController::class, 'bayar'])->name('pos.bayar');

    // Payment Gateway - Vendor
    Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');
    Route::post('/vendor', [VendorController::class, 'storeVendor'])->name('vendor.store');
    Route::get('/vendor/pesanan-lunas', [VendorController::class, 'pesananLunas'])->name('vendor.pesanan');
    Route::get('/vendor/{id_vendor}/menu', [VendorController::class, 'menu'])->name('vendor.menu');
    Route::post('/vendor/{id_vendor}/menu', [VendorController::class, 'tambahMenu'])->name('vendor.menu.store');
    Route::delete('/vendor/menu/{id_menu}', [VendorController::class, 'hapusMenu'])->name('vendor.menu.hapus');

    // Customer Data
    Route::get('/customer-data', [CustomerDataController::class, 'index'])->name('customer-data.index');
    Route::get('/customer-data/tambah-blob', [CustomerDataController::class, 'createBlob'])->name('customer-data.create-blob');
    Route::post('/customer-data/tambah-blob', [CustomerDataController::class, 'storeBlob'])->name('customer-data.store-blob');
    Route::get('/customer-data/tambah-path', [CustomerDataController::class, 'createPath'])->name('customer-data.create-path');
    Route::post('/customer-data/tambah-path', [CustomerDataController::class, 'storePath'])->name('customer-data.store-path');
});

// Payment Gateway - Customer (Tanpa Login)
Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::post('/customer/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
Route::post('/customer/bayar', [CustomerController::class, 'generatePayment'])->name('customer.bayar');
Route::get('/payment/{id_pesanan}', [CustomerController::class, 'showPayment'])->name('payment.show');
Route::get('/payment-status/{id_pesanan}', [CustomerController::class, 'paymentStatus'])->name('payment.status');
Route::get('/customer/status/{id_pesanan}', [CustomerController::class, 'status'])->name('customer.status');

// Midtrans Webhook
Route::post('/webhook/midtrans', [CustomerController::class, 'paymentCallback'])->name('webhook.midtrans');

// Debug SMTP
Route::get('/test-email', function () {
    Mail::raw('Tes OTP Email', function ($m) {
        $m->to('finaaidaysf@gmail.com')->subject('Tes SMTP Laravel');
    });
    return 'Cek inbox / spam.';
});