<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerDataController extends Controller
{
    // =============================================
    // Data Customer
    // =============================================
    public function index()
    {
        $customers = Customer::all();
        return view('customer-data.index', compact('customers'));
    }

    // =============================================
    // Tambah Customer 1 - Blob (Camera)
    // =============================================
    public function createBlob()
    {
        return view('customer-data.create_blob');
    }

    public function storeBlob(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'email'             => 'nullable|email',
            'alamat'            => 'required|string',
            'provinsi'          => 'required|string|max:100',
            'kota'              => 'required|string|max:100',
            'kecamatan'         => 'required|string|max:100',
            'kodepos_kelurahan' => 'required|string|max:100',
            'foto'              => 'required|string', // base64 dari canvas
        ]);

        Customer::create([
            'nama'              => $request->nama,
            'email'             => $request->email,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'foto_blob'         => $request->foto,
        ]);

        return redirect()->route('customer-data.index')
                         ->with('success', 'Customer berhasil ditambahkan (BLOB)!');
    }

    // =============================================
    // Tambah Customer 2 - File Path (Upload)
    // =============================================
    public function createPath()
    {
        return view('customer-data.create_path');
    }

    public function storePath(Request $request)
    {
        $request->validate([
            'nama'              => 'required|string|max:255',
            'email'             => 'nullable|email',
            'alamat'            => 'required|string',
            'provinsi'          => 'required|string|max:100',
            'kota'              => 'required|string|max:100',
            'kecamatan'         => 'required|string|max:100',
            'kodepos_kelurahan' => 'required|string|max:100',
            'foto_file'         => 'required|image|mimes:jpeg,png,gif|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto_file')) {
            $file     = $request->file('foto_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/customers'), $filename);
            $path = 'uploads/customers/' . $filename;
        }

        Customer::create([
            'nama'              => $request->nama,
            'email'             => $request->email,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
            'foto_path'         => $path,
        ]);

        return redirect()->route('customer-data.index')
                         ->with('success', 'Data Customer berhasil ditambahkan!');
    }

    // =============================================
    // Edit & Update Customer
    // =============================================
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer-data.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update([
            'nama'              => $request->nama,
            'email'             => $request->email,
            'alamat'            => $request->alamat,
            'provinsi'          => $request->provinsi,
            'kota'              => $request->kota,
            'kecamatan'         => $request->kecamatan,
            'kodepos_kelurahan' => $request->kodepos_kelurahan,
        ]);

        return redirect()->route('customer-data.index')
                         ->with('success', 'Data customer berhasil diupdate!');
    }
}