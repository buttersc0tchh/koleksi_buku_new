<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WilayahController extends Controller
{
    public function index()
    {
        $provinces = DB::table('reg_provinces')->orderBy('name')->get();
        return view('wilayah.index', compact('provinces'));
    }

    public function getKota(Request $request)
    {
        $kota = DB::table('reg_regencies')
            ->where('province_id', $request->province_id)
            ->orderBy('name')
            ->get();
        return response()->json(['status' => 'success', 'data' => $kota]);
    }

    public function getKecamatan(Request $request)
    {
        $kecamatan = DB::table('reg_districts')
            ->where('regency_id', $request->regency_id)
            ->orderBy('name')
            ->get();
        return response()->json(['status' => 'success', 'data' => $kecamatan]);
    }

    public function getKelurahan(Request $request)
    {
        $kelurahan = DB::table('reg_villages')
            ->where('district_id', $request->district_id)
            ->orderBy('name')
            ->get();
        return response()->json(['status' => 'success', 'data' => $kelurahan]);
    }
}