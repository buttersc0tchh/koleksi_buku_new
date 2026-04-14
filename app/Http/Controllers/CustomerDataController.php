<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $file = $request->file('foto');
        $base64 = 'data:'.$file->getMimeType().';base64,'.
            base64_encode(file_get_contents($file->getRealPath()));

        Customer::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'foto_blob' => $base64,
        ]);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Customer berhasil ditambahkan (BLOB/base64)!');
    }

    // Tambah Customer 2 - File Path
    public function createPath()
    {
        return view('customer-data.create_path');
    }

    public function storePath(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = $request->file('foto')->store('customers', 'public');

        Customer::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'] ?? null,
            'foto_path' => $path,
        ]);

        return redirect()
            ->route('admin.customer.index')
            ->with('success', 'Customer berhasil ditambahkan (PATH)!');
    }
}
