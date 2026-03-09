<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE barang RESTART IDENTITY CASCADE');

        $data = [
            ['nama_barang' => 'Mie Goreng Indomie',   'harga' => 3500,  'satuan' => 'pcs'],
            ['nama_barang' => 'Kopi Kapal Api',        'harga' => 2000,  'satuan' => 'sachet'],
            ['nama_barang' => 'Gula Pasir',            'harga' => 15000, 'satuan' => 'kg'],
            ['nama_barang' => 'Minyak Goreng',         'harga' => 18000, 'satuan' => 'liter'],
            ['nama_barang' => 'Tepung Terigu',         'harga' => 12000, 'satuan' => 'kg'],
            ['nama_barang' => 'Beras Premium',         'harga' => 65000, 'satuan' => '5kg'],
            ['nama_barang' => 'Sabun Cuci Piring',     'harga' => 8000,  'satuan' => 'botol'],
            ['nama_barang' => 'Shampo Sunsilk',        'harga' => 5000,  'satuan' => 'sachet'],
            ['nama_barang' => 'Teh Celup Sariwangi',   'harga' => 10000, 'satuan' => 'box'],
            ['nama_barang' => 'Susu Ultra Milk',       'harga' => 6500,  'satuan' => 'kotak'],
            ['nama_barang' => 'Kecap Manis ABC',       'harga' => 12000, 'satuan' => 'botol'],
            ['nama_barang' => 'Saus Sambal',           'harga' => 9000,  'satuan' => 'botol'],
            ['nama_barang' => 'Deterjen Rinso',        'harga' => 20000, 'satuan' => 'kg'],
            ['nama_barang' => 'Sabun Mandi Lifebuoy',  'harga' => 4500,  'satuan' => 'pcs'],
            ['nama_barang' => 'Pasta Gigi Pepsodent',  'harga' => 11000, 'satuan' => 'pcs'],
            ['nama_barang' => 'Biskuit Oreo',          'harga' => 7000,  'satuan' => 'pcs'],
            ['nama_barang' => 'Susu Kental Manis',     'harga' => 14000, 'satuan' => 'kaleng'],
            ['nama_barang' => 'Margarin Blue Band',    'harga' => 9500,  'satuan' => 'pcs'],
            ['nama_barang' => 'Sirup Marjan',          'harga' => 22000, 'satuan' => 'botol'],
            ['nama_barang' => 'Minyak Kayu Putih',     'harga' => 16000, 'satuan' => 'botol'],
            ['nama_barang' => 'Tisu Paseo',            'harga' => 8500,  'satuan' => 'pack'],
            ['nama_barang' => 'Pembalut Wanita',       'harga' => 13000, 'satuan' => 'pack'],
            ['nama_barang' => 'Popok Bayi',            'harga' => 45000, 'satuan' => 'pack'],
            ['nama_barang' => 'Vitamin C 1000mg',      'harga' => 25000, 'satuan' => 'botol'],
            ['nama_barang' => 'Obat Batuk OBH',        'harga' => 18000, 'satuan' => 'botol'],
            ['nama_barang' => 'Plester Hansaplast',    'harga' => 6000,  'satuan' => 'pack'],
            ['nama_barang' => 'Baterai ABC AA',        'harga' => 7500,  'satuan' => 'pack'],
            ['nama_barang' => 'Lilin Lebah',           'harga' => 5000,  'satuan' => 'pack'],
            ['nama_barang' => 'Korek Api Gas',         'harga' => 3000,  'satuan' => 'pcs'],
            ['nama_barang' => 'Kantong Plastik',       'harga' => 4000,  'satuan' => 'pack'],
            ['nama_barang' => 'Tali Rafia',            'harga' => 8000,  'satuan' => 'roll'],
            ['nama_barang' => 'Lem Kertas',            'harga' => 5500,  'satuan' => 'pcs'],
            ['nama_barang' => 'Pensil 2B',             'harga' => 3000,  'satuan' => 'pcs'],
            ['nama_barang' => 'Pulpen Pilot',          'harga' => 4000,  'satuan' => 'pcs'],
            ['nama_barang' => 'Buku Tulis',            'harga' => 5000,  'satuan' => 'pcs'],
            ['nama_barang' => 'Penggaris 30cm',        'harga' => 6000,  'satuan' => 'pcs'],
            ['nama_barang' => 'Gunting Kecil',         'harga' => 12000, 'satuan' => 'pcs'],
            ['nama_barang' => 'Selotip Bening',        'harga' => 7000,  'satuan' => 'roll'],
            ['nama_barang' => 'Amplop Putih',          'harga' => 5000,  'satuan' => 'pack'],
            ['nama_barang' => 'Kertas HVS A4',        'harga' => 45000, 'satuan' => 'rim'],
        ];

        foreach ($data as $item) {
            DB::table('barang')->insert($item);
        }
    }
}