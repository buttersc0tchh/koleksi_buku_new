<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::orderBy('id_barang')->get();
        return view('barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'satuan'      => 'nullable|string|max:30',
        ]);

        Barang::create([
            'id_barang'   => '',
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'satuan'      => $request->satuan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function show($id) {}

    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:100',
            'harga'       => 'required|numeric|min:0',
            'satuan'      => 'nullable|string|max:30',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama_barang' => $request->nama_barang,
            'harga'       => $request->harga,
            'satuan'      => $request->satuan,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus!');
    }

    public function cetakPdf(Request $request)
    {
        $request->validate([
            'selected'    => 'required|array|min:1',
            'selected.*'  => 'string',
            'koordinat_x' => 'required|integer|min:1|max:5',
            'koordinat_y' => 'required|integer|min:1|max:8',
        ], [
            'selected.required' => 'Pilih minimal 1 barang untuk dicetak!',
        ]);

        $selected = $request->input('selected');
        $x        = (int) $request->input('koordinat_x');
        $y        = (int) $request->input('koordinat_y');
        $barangs  = Barang::whereIn('id_barang', $selected)->orderBy('id_barang')->get();
        $startPos = ($y - 1) * 5 + ($x - 1);

        $pdf = Pdf::loadView('barang.cetak', compact('barangs', 'startPos'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('tag-harga.pdf');
    }
}