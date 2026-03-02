<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;
    protected $table = 'buku';
    
    protected $guarded = [];
    public function kategori()
{
    // Ini supaya Laravel tahu cara narik data nama_kategori dari tabel kategori
    return $this->belongsTo(Kategori::class, 'idkategori', 'id');
}
}