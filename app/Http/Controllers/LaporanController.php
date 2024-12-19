<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.laporanProduksi');

    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'produk.*' => 'required|exists:produk,id',
                'jumlahProduksi.*' => 'required|integer|min:1',
            ]);

            DB::beginTransaction();

            $createdReports = [];

            foreach ($request->produk as $index => $productId) {
                $laporan = Laporan::create([
                    'id_produk' => $productId,
                    'jumlahProduksi' => $request->jumlahProduksi[$index],
                    'hasilProduksi' => 0,
                    'statusProduksi' => 0,
                ]);

                $createdReports[] = $laporan;
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Rencana produksi berhasil ditambahkan',
                'data' => $createdReports,
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menyimpan data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function rencanaProduksi()
    {
        $stats = [
            'totalRencana' => Laporan::count(),
            'dalamProses' => Laporan::where('statusProduksi', 1)->count(),
            'selesai' => Laporan::where('statusProduksi', 2)->count(),
        ];

        return view('laporan.rencanaProduksi', $stats);
    }

    public function apiData(Request $request, $status = '')
    {
        $query = DB::table('laporan')
            ->join('produk', 'laporan.id_produk', '=', 'produk.id')
            ->select(
                'laporan.*',
                'produk.namaProduk as nama_produk'
            )
            ->where('statusProduksi', $status)
            ->orderBy('laporan.created_at', 'desc');

        switch ($request->period) {
            case '1':
                $query->where('laporan.created_at', '>=', now()->subWeek());
                break;
            case '2':
                $query->where('laporan.created_at', '>=', now()->subWeeks(2));
                break;
            case '3':
                $query->where('laporan.created_at', '>=', now()->subWeeks(3));
                break;
            case '4':
                $query->where('laporan.created_at', '>=', now()->subWeeks(4));
                break;
            case 'month':
                $query->whereMonth('laporan.created_at', now()->month)
                    ->whereYear('laporan.created_at', now()->year);
                break;
        }
        if ($status !== '') {

            $query->where('statusProduksi', $status)
                ->orderBy('laporan.created_at', 'desc')
                ->get();

        } else {
            $query->where('statusProduksi', 0);

        }
        $data = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'hasilProduksi' => 'required|integer|min:0',
            ]);

            $laporan = Laporan::findOrFail($id);
            $laporan->hasilProduksi = $validated['hasilProduksi'];

            // Update status based on production progress
            if ($validated['hasilProduksi'] >= $laporan->jumlahProduksi) {
                $laporan->statusProduksi = 2; // Completed
            } elseif ($validated['hasilProduksi'] > 0) {
                $laporan->statusProduksi = 1; // In Progress
            }

            $laporan->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Data produksi berhasil diupdate',
                'data' => $laporan,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengupdate data produksi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

}
