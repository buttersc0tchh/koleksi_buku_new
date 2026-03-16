<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('pos.index');
    }

    public function cariBarang(Request $request)
    {
        $barang = Barang::where('id_barang', $request->kode)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Barang tidak ditemukan!'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $barang
        ]);
    }

    public function bayar(Request $request)
    {
        $items = $request->items;
        $total = $request->total;

        // Simpan penjualan
        $penjualan = Penjualan::create([
            'timestamp' => now(),
            'total'     => $total,
        ]);

        // Simpan detail
        foreach ($items as $item) {
            PenjualanDetail::create([
                'id_penjualan' => $penjualan->id_penjualan,
                'id_barang'    => $item['id_barang'],
                'jumlah'       => $item['jumlah'],
                'subtotal'     => $item['subtotal'],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Transaksi berhasil disimpan!',
            'data'    => $penjualan
        ]);
    }
}