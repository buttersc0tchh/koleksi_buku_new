<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table      = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps    = false;
    protected $guarded    = [];

    public function detail()
    {
        return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan');
    }
}
