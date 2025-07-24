<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan_Tiket;
use App\Models\Detail_Pemesanan_Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PemesananTiketApiController extends Controller
{
    // ✅ GET semua pemesanan user tertentu (history)
    public function index(Request $request)
    {
        $user = $request->user(); // Authenticated user via Sanctum or token

        $pemesanans = Pemesanan_Tiket::with('detailPemesanan.wahana')
            ->where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $pemesanans
        ]);
    }

    // ✅ POST pemesanan tiket
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_kunjungan' => 'required|date',
            'wahana_id'         => 'required|array',
            'wahana_id.*'       => 'exists:wahanas,id',
            'jumlah'            => 'required|array',
            'jumlah.*'          => 'integer|min:1',
            'harga'             => 'required|array',
            'subtotal'          => 'required|array',
            'total_harga'       => 'required|numeric',
            'bukti_pembayaran'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }

        // Generate kode pemesanan
        $today = now()->format('Y-m-d');
        $jumlahHariIni = Pemesanan_Tiket::whereDate('created_at', $today)->count();
        $kode_pemesanan = 'TKT' . now()->format('Ymd') . str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);

        // Upload bukti
        $buktiPath = $request->hasFile('bukti_pembayaran')
            ? $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public')
            : null;

        // Simpan pemesanan
        $pemesanan = Pemesanan_Tiket::create([
            'kode_pemesanan'    => $kode_pemesanan,
            'user_id'           => $request->user()->id,
            'tanggal_pemesanan' => now(),
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'total_tiket'       => array_sum($request->jumlah),
            'total_harga'       => $request->total_harga,
            'status'            => 'pending',
            'bukti_pembayaran'  => $buktiPath,
        ]);

        // Simpan detail pemesanan
        foreach ($request->wahana_id as $i => $wahanaId) {
            Detail_Pemesanan_Tiket::create([
                'pemesanan_tiket_id' => $pemesanan->id,
                'wahana_id'          => $wahanaId,
                'jumlah'             => $request->jumlah[$i],
                'harga'              => $request->harga[$i],
                'subtotal'           => $request->subtotal[$i],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pemesanan berhasil disimpan.',
            'data'    => $pemesanan
        ]);
    }

    // ✅ GET detail satu pemesanan
    public function show($id)
    {
        $pemesanan = Pemesanan_Tiket::with('detailPemesanan.wahana')->find($id);

        if (!$pemesanan) {
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $pemesanan]);
    }
}
