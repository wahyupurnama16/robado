<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Produksi;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $produks = Produksi::all();
        $terkirim = Pemesanan::where("statusPengiriman", 1)->where("tanggalPengiriman", date('Y-m-d'))->count();
        $belumTerkirim = Pemesanan::where("statusPengiriman", 0)->where("tanggalPengiriman", date('Y-m-d'))->count();
        $pesanan = Pemesanan::where("tanggalPengiriman", date('Y-m-d'))->count();
        $totalPesananBulan = Pemesanan::whereMonth('tanggalPengiriman', now()->month)
            ->whereYear('tanggalPengiriman', now()->year)
            ->where('statusPengiriman', 1)
            ->where('statusPembayaran', 1)
            ->count();

        $totalPendapatan = Pemesanan::whereMonth('tanggalPengiriman', now()->month)
            ->whereYear('tanggalPengiriman', now()->year)
            ->where('statusPengiriman', 1)
            ->where('statusPembayaran', 1)
            ->sum('totalHarga');
        if (Auth::user()) {
            return view('dashboard/dashboard', compact('produks', 'terkirim', 'belumTerkirim', 'pesanan', 'totalPesananBulan', 'totalPendapatan'));
        } else {
            return view('dashboard/dashboardGuest', compact('produks', 'terkirim', 'belumTerkirim', 'pesanan', 'totalPesananBulan', 'totalPendapatan'));
        }
    }

    public function aturanBerlangganan()
    {
        return view('aturanBerlangganan.index');
    }

    public function tentangKami()
    {
        return view('tentangKami.index');

    }
}
