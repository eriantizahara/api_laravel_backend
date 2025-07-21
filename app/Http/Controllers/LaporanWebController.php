<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Pemesanan_Tiket;
use Carbon\Carbon;

class LaporanWebController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan_Tiket::with([
            'user',
            'detailPemesanan.wahana'
        ]);

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal_pemesanan', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $pemesanans = $query->orderBy('tanggal_pemesanan', 'asc')->get();

        return view('laporan.laporan_index', compact('pemesanans'));
    }

    public function cetakPDF(Request $request)
    {
        $query = Pemesanan_Tiket::with([
            'user',
            'detailPemesanan.wahana'
        ]);

        if ($request->tanggal_awal && $request->tanggal_akhir) {
            $query->whereBetween('tanggal_pemesanan', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $pemesanans = $query->orderBy('tanggal_pemesanan', 'asc')->get();

        $pdf = Pdf::loadView('laporan.cetaklaporan', compact('pemesanans'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('laporan_pemesanan_tiket.pdf');
    }
}
