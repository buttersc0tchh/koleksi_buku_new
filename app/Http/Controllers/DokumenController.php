<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DokumenController extends Controller
{
    public function undangan()
    {
        // Menampilkan halaman form undangan yang ada di folder views/dokumen
        return view('dokumen.undangan');
    }

    public function sertifikat()
    {
        // Menampilkan halaman sertifikat (kalau nanti mau dibikin fitur serupa)
        return view('dokumen.sertifikat');
    }
}