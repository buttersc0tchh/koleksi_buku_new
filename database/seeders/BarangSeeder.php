<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        // PostgreSQL pakai TRUNCATE ... RESTART IDENTITY CASCADE
        DB::statement('TRUNCATE TABLE barang RESTART IDENTITY CASCADE');

        $data = [
            ['nama_barang' => 'Mie Goreng Indomie',  'harga' => 3500,  'satuan' => 'pcs'],
            ['nama_barang' => 'Kopi Kapal Api',       'harga' => 2000,  'satuan' => 'sachet'],
            ['nama_barang' => 'Gula Pasir',           'harga' => 15000, 'satuan' => 'kg'],
            ['nama_barang' => 'Minyak Goreng',        'harga' => 18000, 'satuan' => 'liter'],
            ['nama_barang' => 'Tepung Terigu',        'harga' => 12000, 'satuan' => 'kg'],
            ['nama_barang' => 'Beras Premium',        'harga' => 65000, 'satuan' => '5kg'],
            ['nama_barang' => 'Sabun Cuci Piring',    'harga' => 8000,  'satuan' => 'botol'],
            ['nama_barang' => 'Shampo Sunsilk',       'harga' => 5000,  'satuan' => 'sachet'],
            ['nama_barang' => 'Teh Celup Sariwangi',  'harga' => 10000, 'satuan' => 'box'],
            ['nama_barang' => 'Susu Ultra Milk',      'harga' => 6500,  'satuan' => 'kotak'],
        ];

        // INSERT satu-satu agar trigger berjalan tiap row
        foreach ($data as $item) {
            DB::table('barang')->insert($item);
        }
    }
}