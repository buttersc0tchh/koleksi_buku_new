<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table      = 'menu';
    protected $primaryKey = 'id_menu';
    public $timestamps    = false;
    protected $guarded    = [];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'id_menu', 'id_menu');
    }
}
