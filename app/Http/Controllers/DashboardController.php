<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $produks = Produksi::all();
        if (Auth::user()) {
            return view('dashboard/dashboard', compact('produks'));
        } else {
            return view('dashboard/dashboardGuest', compact('produks'));
        }
    }
}
