<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generateSertifikat()
    {

        $data = [
            'nama' => 'Alfia',
            'peran' => 'WAKIL KETUA PELAKSANA', 
            'acara' => 'Seminar Nasional FIKKIA UNAIR', 
            'tanggal' => '18 Oktober 2025' 
        ];

        // Path: resources/views/dokumen/sertifikat.blade.php
        $pdf = Pdf::loadView('dokumen.sertifikat', $data);
        
        // Format Landscape A4 [cite: 16]
        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('Sertifikat.pdf');
    }

    public function generateUndangan()
    {
        // Data sesuai contoh undangan Fakultas Vokasi [cite: 18, 38, 39]
        $data = [
            'nomor' => '556/B/DST/UN3.FV/TU.01.00/2026',
            'perihal' => 'Undangan Silaturahmi Awal Tahun', 
            'tanggal_surat' => '13 Januari 2026', 
            'hari_tgl' => 'Selasa, 20 Januari 2026', 
            'waktu' => '10.00-13.00 WIB',
            'tempat' => 'Aula Gedung A Lt.3 Fakultas Vokasi' 
        ];

        // Path: resources/views/dokumen/undangan.blade.php
        $pdf = Pdf::loadView('dokumen.undangan', $data);

        // Format Portrait A4 [cite: 17]
        $pdf->setPaper('a4', 'portrait');

        return $pdf->stream('Undangan.pdf');
    }
}