<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use HasFactory;

    // 1. Paksa Laravel pakai nama tabel 'kategori' (tanpa s) sesuai rename kamu di pgAdmin
    protected $table = 'kategori';

    // 2. Tentukan primary key-nya adalah 'id' sesuai yang ada di pgAdmin kamu
    protected $primaryKey = 'id';

    // 3. Daftarkan kolom yang bisa diisi sesuai Modul 1
    protected $fillable = ['nama_kategori'];

    // 4. Relasi ke Model Buku (Satu kategori punya banyak buku)
    public function buku()
    {
        // 'idkategori' di tabel buku nyambung ke 'id' di tabel ini
        return $this->hasMany(Buku::class, 'idkategori', 'id');
    }
}