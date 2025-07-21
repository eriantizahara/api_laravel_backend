<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan_Tiket;
use App\Models\User;
use App\Models\Wahana;
use Illuminate\Http\Request;

class DashboardWebController extends Controller
{
    public function index()
    {
        $totalPemesanan = Pemesanan_Tiket::count();
        $totalUser = User::count();
        $totalWahana = Wahana::count();
        $totalPendapatan = Pemesanan_Tiket::sum('total_harga');

        return view('layouts.formdashboard', compact(
            'totalPemesanan',
            'totalUser',
            'totalWahana',
            'totalPendapatan'
        ));
    }
}
