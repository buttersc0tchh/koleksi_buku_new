<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'nama',
        'email',
        'alamat',
        'provinsi',
        'kota',
        'kecamatan',
        'kodepos_kelurahan',
        'foto_blob',
        'foto_path',
    ];
}