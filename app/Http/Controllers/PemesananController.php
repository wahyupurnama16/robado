<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Pemesanan;
use App\Models\Produksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PemesananController extends Controller
{

    public function sendLaporanOwner(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'waktu' => 'required|in:05:00:00,18:00:00',
        ]);

        $pemesanan = Pemesanan::where('tanggalPengiriman', $request->tanggal)
            ->where('jamPengiriman', $request->waktu)
            ->update([
                'statusLaporan' => 1,
            ]);

        Alert::success('Berhasil', 'Berhasil Kirim Laporan');
        return redirect()->back();
    }

    public function laporanOwner()
    {
        $currentTime = Carbon::now();
        $currentHour = $currentTime->hour;

        // Initialize query builder
        $query = DB::table('pemesanan')
            ->select(
                'produk.namaProduk as namaProduk',
                DB::raw('SUM(pemesanan.jumlahPemesanan) as total_pesanan'),
                DB::raw('SUM(pemesanan.jumlahPemesanan * pemesanan.harga) as total_nilai')
            )
            ->join('produk', 'pemesanan.id_produk', '=', 'produk.id')
            ->where('pemesanan.statusLaporan', 1);

        if ($currentHour >= 5 && $currentHour < 16) { // Antara jam 5 pagi - 4 sore
            $today = Carbon::today();
            $query->where(function ($q) use ($today) {
                $q->whereDate('pemesanan.tanggalPengiriman', $today->format('Y-m-d'))
                    ->whereTime('pemesanan.jamPengiriman', '18:00:00');
            });
        } else { // Antara jam 4.01 sore - 4.59 pagi
            $tomorrow = Carbon::tomorrow();
            $query->where(function ($q) use ($tomorrow) {
                $q->whereDate('pemesanan.tanggalPengiriman', $tomorrow->format('Y-m-d'))
                    ->whereTime('pemesanan.jamPengiriman', '05:00:00');
            });
        }

        $summaryByProduct = $query->groupBy('produk.id', 'produk.namaProduk')
            ->get();

        $products = Produksi::all();

        return view('laporan.laporanOwner', compact('summaryByProduct', 'products'));
    }

    public function getDailyReport()
    {
        $currentTime = Carbon::now();
        $currentHour = $currentTime->hour;

        // Initialize query builder
        $query = DB::table('pemesanan')
            ->select(
                'pemesanan.*',
                'users.nama',
                'produk.namaProduk as namaProduk'
            )
            ->leftJoin('users', 'pemesanan.id_user', '=', 'users.id')
            ->join('produk', 'pemesanan.id_produk', '=', 'produk.id')
            ->where('pemesanan.statusLaporan', 1);

        if ($currentHour >= 5 && $currentHour < 16) { // Antara jam 5 pagi - 4 sore
            $today = Carbon::today();
            $query->where(function ($q) use ($today) {
                $q->whereDate('pemesanan.tanggalPengiriman', $today->format('Y-m-d'))
                    ->whereTime('pemesanan.jamPengiriman', '18:00:00');
            });
        } else { // Antara jam 4.01 sore - 4.59 pagi
            $tomorrow = Carbon::tomorrow();
            $query->where(function ($q) use ($tomorrow) {
                $q->whereDate('pemesanan.tanggalPengiriman', $tomorrow->format('Y-m-d'))
                    ->whereTime('pemesanan.jamPengiriman', '05:00:00');
            });
        }

        $orders = $query->orderBy('pemesanan.tanggalPengiriman')->get();
        return response()->json([
            'status' => 'success',
            'data' => $orders,
        ]);
    }

    public function store(Request $request)
    {

        if ($request->type === 'berlanggan') {
            $validated = $request->validate([
                'date' => 'required|date',
                'jam' => 'required|string',
            ]);

            $dataPesanan = json_decode($request->dataPesanan);
            $result = collect($dataPesanan)->map(function ($item) use ($request) {
                Cart::where('id_user', Auth::user()->id)->where('id_produk', $item->id)->delete();
                return [
                    'id_user' => Auth::user()->id,
                    'id_produk' => $item->id,
                    'harga' => $item->price,
                    'jumlahPemesanan' => $item->qty,
                    'tanggalPengiriman' => $request->date,
                    'jamPengiriman' => $request->jam . ":00:00",
                    'totalHarga' => $item->qty * $item->price,
                ];
            })->toArray();

        } else {

            $validated = $request->validate([
                'nama' => 'required|string',
                'noWa' => 'required|string',
            ]);

            $dataPesanan = json_decode($request->dataPesanan);
            $result = collect($dataPesanan)->map(function ($item) use ($request) {
                return [
                    'nama' => $request->nama,
                    'noWa' => $request->noWa,
                    'id_produk' => $item->id,
                    'harga' => $item->price,
                    'jumlahPemesanan' => $item->qty,
                    'totalHarga' => $item->qty * $item->price,
                ];
            })->toArray();
        }
        $res = Pemesanan::insert($result);
        return $res;
    }

    public function riwayat()
    {
        return view('riwayat.riwayat');
    }

    public function updateStatus($status, $value, $up)
    {
        $statusUpdate = $up == 0 ? 1 : 0;
        if ($status === 'bayar') {
            $riwayat = Pemesanan::where('id', $value)->with('user')->where('statusPembayaran', $statusUpdate)->first();
            $riwayat->statusPembayaran = (int) $up;
            $status = $riwayat->save();

            if ($status) {
                if ($riwayat->user == null) {
                    $produk = Produksi::where('id', $riwayat->id_produk)->decrement('jumlahProduk', $riwayat->jumlahPemesanan);
                }
            }
        } else {
            $riwayat = Pemesanan::where('id', $value)->where('statusPengiriman', $statusUpdate)->first();
            $riwayat->statusPengiriman = (int) $up;
            $riwayat->save();
        }

        Alert::success('Berhasil', 'Berhasil Di update');
        return redirect(route('pemesanan.riwayat', absolute: false));

    }

    public function updatePesananDetails(Request $request, $id)
    {
        $validated = $request->validate([
            "jumlahPesanan" => "required|integer",
            "totalHarga" => "required|integer",
            "tanggalPengiriman" => "required|date",
            "jamPengiriman" => "required",
        ]);

        $res = Pemesanan::find($id);
        $res->jumlahPemesanan = $request->jumlahPesanan;
        $res->totalHarga = $request->totalHarga;
        $res->tanggalPengiriman = $request->tanggalPengiriman;
        $res->jamPengiriman = $request->jamPengiriman;

        $result = $res->save();

        if ($result) {
            Alert::success('Berhasil', 'Berhasil Di update');
        } else {
            Alert::error('Gagal', 'Gagal Di update');
        }
        return redirect(route('pemesanan.detail', $id));

    }

    public function getRiwayat($id)
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'owner') {
            $riwayat = Pemesanan::with(['produk', 'user'])
                ->where(function ($query) {
                    $query->where('statusPembayaran', 0)
                        ->orWhere('statusPengiriman', 0);
                })
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function ($item) {
                    return [
                        'pemesanan_id' => $item->id,
                        'namaProduk' => $item->produk->namaProduk,
                        'namaUsaha' => $item->user ? $item->user->nama : $item->nama,
                        'harga' => $item->harga,
                        'jumlahPemesanan' => $item->jumlahPemesanan,
                        'harga' => $item->harga,
                        'tanggalPengiriman' => $item->tanggalPengiriman . ' ' . $item->jamPengiriman,
                        'statusPembayaran' => $item->statusPembayaran,
                        'statusUser' => $item->user ? $item->user->status : 0,
                        'statusPengiriman' => $item->statusPengiriman,
                    ];
                });
        } else {
            $riwayat = Pemesanan::where('id_user', $id)
                ->with(['produk', 'user'])
                ->get()
                ->map(function ($item) {
                    return [
                        'pemesanan_id' => $item->id,
                        'namaProduk' => $item->produk->namaProduk,
                        'namaUsaha' => $item->user ? $item->user->nama : $item->nama,
                        'harga' => $item->harga,
                        'jumlahPemesanan' => $item->jumlahPemesanan,
                        'harga' => $item->harga,
                        'tanggalPengiriman' => $item->tanggalPengiriman . ' ' . $item->jamPengiriman,
                        'statusPembayaran' => $item->statusPembayaran,
                        'statusUser' => $item->user->status,
                        'statusPengiriman' => $item->statusPengiriman,
                    ];
                });
        }

        return response()->json(['data' => $riwayat]);

    }

    public function getRiwayatDashboard($id)
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'owner') {
            $riwayat = Pemesanan::with(['produk', 'user'])
                ->where(function ($query) {
                    $query->where('statusPembayaran', 0)
                        ->orWhere('statusPengiriman', 0);
                })
                ->where('id_user', null)
                ->where('tanggalPengiriman', date('Y-m-d'))
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function ($item) {
                    return [
                        'pemesanan_id' => $item->id,
                        'namaProduk' => $item->produk->namaProduk,
                        'namaUsaha' => $item->user ? $item->user->nama : $item->nama,
                        'harga' => $item->harga,
                        'jumlahPemesanan' => $item->jumlahPemesanan,
                        'harga' => $item->harga,
                        'tanggalPengiriman' => $item->tanggalPengiriman . ' ' . $item->jamPengiriman,
                        'statusPembayaran' => $item->statusPembayaran,
                        'statusUser' => $item->user ? $item->user->status : 0,
                        'statusPengiriman' => $item->statusPengiriman,
                    ];
                });
        }

        return response()->json(['data' => $riwayat]);

    }

    public function getRiwayatPesanan($id)
    {
        if (Auth::user()->role == 'admin' || Auth::user()->role == 'owner') {
            $riwayat = Pemesanan::with(['produk', 'user'])
                ->where(function ($query) {
                    $query->where('statusPembayaran', 1)
                        ->Where('statusPengiriman', 1);
                })
                ->orderBy('created_at', 'DESC')
                ->get()
                ->map(function ($item) {
                    return [
                        'pemesanan_id' => $item->id,
                        'namaProduk' => $item->produk->namaProduk,
                        'namaUsaha' => $item->user ? $item->user->nama : $item->nama,
                        'harga' => $item->harga,
                        'jumlahPemesanan' => $item->jumlahPemesanan,
                        'harga' => $item->harga,
                        'tanggalPengiriman' => $item->tanggalPengiriman . ' ' . $item->jamPengiriman,
                        'statusPembayaran' => $item->statusPembayaran,
                        'statusUser' => $item->user ? $item->user->status : 0,
                        'statusPengiriman' => $item->statusPengiriman,
                    ];
                });
        } else {
            $riwayat = Pemesanan::where('id_user', $id)
                ->with(['produk', 'user'])
                ->where(function ($query) {
                    $query->where('statusPembayaran', 1)
                        ->orWhere('statusPengiriman', 1);
                })
                ->get()
                ->map(function ($item) {
                    return [
                        'pemesanan_id' => $item->id,
                        'namaProduk' => $item->produk->namaProduk,
                        'namaUsaha' => $item->user ? $item->user->nama : $item->nama,
                        'harga' => $item->harga,
                        'jumlahPemesanan' => $item->jumlahPemesanan,
                        'harga' => $item->harga,
                        'tanggalPengiriman' => $item->tanggalPengiriman . ' ' . $item->jamPengiriman,
                        'statusPembayaran' => $item->statusPembayaran,
                        'statusUser' => $item->user->status,
                        'statusPengiriman' => $item->statusPengiriman,
                    ];
                });
        }

        return response()->json(['data' => $riwayat]);

    }

    public function detail($id)
    {
        $pemesanan = Pemesanan::with(['produk', 'user'])->find($id);
        return view('riwayat.details', compact('pemesanan'));
    }

    public function destroy(Request $request, $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Cek status pembayaran
        if ($pemesanan->statusPembayaran == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus pesanan yang sudah dibayar',
            ], 403);
        }

        // Cek status pengiriman
        if ($pemesanan->statusPengiriman == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus pesanan yang sudah dikirim',
            ], 403);
        }

        // Hapus pemesanan
        $pemesanan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus',
        ]);

    }

    public function riwayatPesanan()
    {
        return view('riwayat.riwayatPesanan');
    }

}
