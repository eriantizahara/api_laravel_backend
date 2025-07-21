<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan_Tiket;
use App\Models\Customer;
use App\Models\Wahana;
use Illuminate\Http\Request;

class DashboardWebController extends Controller
{
    public function index()
    {
        $totalPemesanan = Pemesanan_Tiket::count();
        $totalCustomer = Customer::count();
        $totalWahana = Wahana::count();
        $totalPendapatan = Pemesanan_Tiket::sum('total_harga');

        return view('layouts.formdashboard', compact(
            'totalPemesanan',
            'totalCustomer',
            'totalWahana',
            'totalPendapatan'
        ));
    }
}
