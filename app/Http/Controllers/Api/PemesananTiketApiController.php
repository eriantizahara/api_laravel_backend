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
            'status'            => 'required|in:pending,selesai,batal',
            'bukti_pembayaran'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }

        try {
            // ✅ Generate kode pemesanan unik
            $prefix = 'TKT' . now()->format('Ymd');
            $latest = Pemesanan_Tiket::where('kode_pemesanan', 'like', $prefix . '%')
                ->orderByDesc('kode_pemesanan')
                ->first();

            if ($latest) {
                $lastNumber = (int) substr($latest->kode_pemesanan, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }

            $kode_pemesanan = $prefix . $newNumber;

            // ✅ Upload bukti pembayaran jika ada
            if ($request->hasFile('bukti_pembayaran') && $request->file('bukti_pembayaran')->isValid()) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            } else {
                $buktiPath = null;
            }

            // ✅ Simpan data utama pemesanan
            $pemesanan = Pemesanan_Tiket::create([
                'kode_pemesanan'    => $kode_pemesanan,
                'user_id'           => $request->user()->id,
                'tanggal_pemesanan' => now(),
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'total_tiket'       => array_sum($request->jumlah),
                'total_harga'       => $request->total_harga,
                'status'            => $request->status,
                'bukti_pembayaran'  => $buktiPath,
            ]);

            // ✅ Simpan detail wahana yang dipesan
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
        } catch (\Illuminate\Database\QueryException $e) {
            // ✅ Tangani error duplikat kode pemesanan
            if ($e->errorInfo[1] == 1062) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode pemesanan sudah digunakan. Silakan coba lagi.',
                ], 500);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server: ' . $e->getMessage(),
            ], 500);
        }
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
