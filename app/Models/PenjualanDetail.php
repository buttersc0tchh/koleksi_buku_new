<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenjualanDetail extends Model
{
    protected $table      = 'penjualan_detail';
    protected $primaryKey = 'idpenjualan_detail';
    public $timestamps    = false;
    protected $guarded    = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }
}