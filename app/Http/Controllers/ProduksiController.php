<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class ProduksiController extends Controller
{
    public function index()
    {
        return view('produk.index');
    }

    public function getData()
    {
        $produks = Produksi::all();
        return response()->json(['data' => $produks]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'namaProduk' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'jumlahProduk' => 'required|numeric',
            'hargaProduk' => 'required|numeric',
            'deskripsi' => 'required',
        ]);

        if ($request->hasFile('gambar')) {
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $cek = $image->move(public_path('storage/produk'), $imageName);
        }

        $produk = Produksi::create([
            'namaProduk' => $request->namaProduk,
            'gambar' => $imageName ?? null,
            'jumlahProduk' => $request->jumlahProduk,
            'hargaProduk' => $request->hargaProduk,
            'deskripsi' => $request->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan',
            'data' => $produk,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'namaProduk' => 'required',
            'jumlahProduk' => 'required|numeric',
            'hargaProduk' => 'required|numeric',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'required',
        ]);

        $produk = Produksi::findOrFail($id);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if ($produk->gambar) {
                Storage::delete('public/produk/' . $produk->gambar);
            }

            // Upload gambar baru
            $image = $request->file('gambar');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $cek = $image->move(public_path('storage/produk'), $imageName);
            $produk->gambar = $imageName;
        }

        $produk->namaProduk = $request->namaProduk;
        $produk->jumlahProduk = $request->jumlahProduk;
        $produk->hargaProduk = $request->hargaProduk;
        $produk->deskripsi = $request->deskripsi;
        $produk->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $produk,
        ]);
    }

    public function destroy($id)
    {
        $produk = Produksi::findOrFail($id);

        if ($produk->gambar) {
            Storage::delete('public/produk/' . $produk->gambar);
        }

        $produk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus',
        ]);
    }
}
