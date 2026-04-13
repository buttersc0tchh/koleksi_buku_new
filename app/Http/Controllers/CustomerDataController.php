<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerDataController extends Controller
{
    // Data Customer
    public function index()
    {
        $customers = Customer::all();
        return view('customer-data.index', compact('customers'));
    }

    // Tambah Customer 1 - Blob
    public function createBlob()
    {
        return view('customer-data.create_blob');
    }

    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'nullable|email',
            'foto'  => 'required|string', // base64
        ]);

        Customer::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'foto_blob' => $request->foto,
        ]);

        return redirect()->route('customer-data.index')->with('success', 'Customer berhasil ditambahkan!');
    }

    // Tambah Customer 2 - File Path
    public function createPath()
    {
        return view('customer-data.create_path');
    }

    public function storePath(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:255',
            'email' => 'nullable|email',
            'foto'  => 'required|string', // base64 dari kamera
        ]);

        // Simpan file ke storage
        $base64  = $request->foto;
        $image   = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
        $filename = 'customer_' . time() . '.png';
        $path = public_path('uploads/customers/');

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        file_put_contents($path . $filename, $image);

        Customer::create([
            'nama'      => $request->nama,
            'email'     => $request->email,
            'foto_path' => 'uploads/customers/' . $filename,
        ]);

        return redirect()->route('customer-data.index')->with('success', 'Customer berhasil ditambahkan!');
    }
}