<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Vendor;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Auto-login sebagai Guest_000001 jika belum login (ditangani di middleware)
        $namaCustomer = Auth::user()->name ?? 'Guest_000001';

        $vendors = Vendor::with('menu')->get();

        // Ambil status pembayaran pesanan terbaru user ini
        $pesananTerbaru = Pesanan::where('nama', $namaCustomer)
            ->orderByDesc('timestamp')
            ->first();

        return view('customer.index', compact('vendors', 'namaCustomer', 'pesananTerbaru'));
    }

    public function checkout(Request $request)
    {
        $namaCustomer = Auth::user()->name ?? 'Guest_000001';
        $items        = $request->input('items', []);

        if (empty($items)) {
            return redirect()->route('customer.index')->with('error', 'Pilih menu terlebih dahulu!');
        }

        // Hitung total
        $total = 0;
        $cartItems = [];
        foreach ($items as $id_menu => $jumlah) {
            $jumlah = (int) $jumlah;
            if ($jumlah <= 0) continue;

            $menu = Menu::find($id_menu);
            if (!$menu) continue;

            $subtotal    = $menu->harga * $jumlah;
            $total      += $subtotal;
            $cartItems[] = [
                'menu'     => $menu,
                'jumlah'   => $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($cartItems)) {
            return redirect()->route('customer.index')->with('error', 'Pilih menu terlebih dahulu!');
        }

        return view('customer.checkout', compact('cartItems', 'total', 'namaCustomer'));
    }

    public function bayar(Request $request)
    {
        $namaCustomer = Auth::user()->name ?? 'Guest_000001';

        $request->validate([
            'metode_bayar' => 'required|in:Virtual Account,QRIS',
            'items'        => 'required|array',
        ]);

        $total = 0;
        $cartItems = [];
        foreach ($request->items as $id_menu => $jumlah) {
            $jumlah = (int) $jumlah;
            if ($jumlah <= 0) continue;

            $menu = Menu::find($id_menu);
            if (!$menu) continue;

            $subtotal    = $menu->harga * $jumlah;
            $total      += $subtotal;
            $cartItems[] = [
                'menu'     => $menu,
                'jumlah'   => $jumlah,
                'subtotal' => $subtotal,
            ];
        }

        if (empty($cartItems)) {
            return redirect()->route('customer.index')->with('error', 'Tidak ada item valid!');
        }

        // Simpan pesanan (status_bayar = 1 karena ini simulasi pembayaran langsung)
        $pesanan = Pesanan::create([
            'nama'         => $namaCustomer,
            'timestamp'    => now(),
            'total'        => $total,
            'metode_bayar' => $request->metode_bayar,
            'status_bayar' => 1, // langsung lunas
        ]);

        // Simpan detail pesanan
        foreach ($cartItems as $item) {
            DetailPesanan::create([
                'id_menu'   => $item['menu']->id_menu,
                'id_pesanan'=> $pesanan->id_pesanan,
                'jumlah'    => $item['jumlah'],
                'harga'     => $item['menu']->harga,
                'subtotal'  => $item['subtotal'],
                'timestamp' => now(),
                'catatan'   => null,
            ]);
        }

        return redirect()->route('customer.status', $pesanan->id_pesanan)
            ->with('success', 'Pembayaran berhasil!');
    }

    public function status($id_pesanan)
    {
        $namaCustomer = Auth::user()->name ?? 'Guest_000001';

        $pesanan = Pesanan::where('id_pesanan', $id_pesanan)
            ->where('nama', $namaCustomer)
            ->with('detail.menu')
            ->firstOrFail();

        return view('customer.status', compact('pesanan', 'namaCustomer'));
    }
}
