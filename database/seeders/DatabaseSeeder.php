<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Isi Kategori
        DB::table('kategori')->insert([
            ['nama_kategori' => 'Novel'],
            ['nama_kategori' => 'Biografi'],
            ['nama_kategori' => 'Komik'],
        ]);

        // Isi Buku (Pastikan idkategori sesuai dengan urutan id di tabel kategori)
        DB::table('buku')->insert([
            [
                'kode' => 'NV-01',
                'judul' => 'Home Sweet Loan',
                'pengarang' => 'Almira Bastari',
                'idkategori' => 1, // 1 biasanya ID untuk Novel
            ],
            [
                'kode' => 'BO-01',
                'judul' => 'Mohammad Hatta, Untuk Negeriku',
                'pengarang' => 'Taufik Abdullah',
                'idkategori' => 2, // 2 untuk Biografi
            ],
            [
                'kode' => 'NV-02',
                'judul' => 'Keajaiban Toko Kelontong Namiya',
                'pengarang' => 'Keigo Higashino',
                'idkategori' => 1,
            ],
        ]);
    }
}