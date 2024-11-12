<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class LaporanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => 'required|exists:produksis,id',
            'jumlahProduksi' => 'required|integer|min:1',
        ]);

        $laporan = Laporan::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Rencana produksi berhasil ditambahkan',
            'data' => $laporan,
        ]);
    }
}
