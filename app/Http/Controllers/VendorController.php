<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $vendors = Vendor::all();
        return view('vendor.index', compact('vendors'));
    }

    public function menu($id_vendor)
    {
        $vendor = Vendor::findOrFail($id_vendor);
        $menus  = Menu::where('id_vendor', $id_vendor)->get();
        return view('vendor.menu', compact('vendor', 'menus'));
    }

    public function tambahMenu(Request $request, $id_vendor)
    {
        $vendor = Vendor::findOrFail($id_vendor);

        $request->validate([
            'nama_menu'   => 'required|string|max:255',
            'harga'       => 'required|integer|min:0',
            'path_gambar' => 'nullable|string|max:255',
        ]);

        Menu::create([
            'nama_menu'   => $request->nama_menu,
            'harga'       => $request->harga,
            'path_gambar' => $request->path_gambar,
            'id_vendor'   => $id_vendor,
        ]);

        return redirect()->route('vendor.menu', $id_vendor)
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    public function hapusMenu($id_menu)
    {
        $menu = Menu::findOrFail($id_menu);
        $id_vendor = $menu->id_vendor;
        $menu->delete();

        return redirect()->route('vendor.menu', $id_vendor)
            ->with('success', 'Menu berhasil dihapus!');
    }

    public function pesananLunas()
    {
        $pesanan = Pesanan::where('status_bayar', 1)
            ->with('detail.menu')
            ->orderBy('timestamp', 'asc')
            ->get();

        return view('vendor.pesanan', compact('pesanan'));
    }

    public function storeVendor(Request $request)
    {
        $request->validate([
            'nama_vendor' => 'required|string|max:255',
        ]);

        Vendor::create(['nama_vendor' => $request->nama_vendor]);

        return redirect()->route('vendor.index')
            ->with('success', 'Vendor berhasil ditambahkan!');
    }
}
