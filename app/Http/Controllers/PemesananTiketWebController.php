<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan_Tiket;
use App\Models\Detail_Pemesanan_Tiket;
use App\Models\Customer;
use App\Models\Wahana;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class PemesananTiketWebController extends Controller
{
    // Tampilkan semua pemesanan
    public function index()
    {
        $pemesananTikets = Pemesanan_Tiket::with(['customer', 'user', 'detailPemesanan.wahana'])->get();
        return view('transaksi.indexpemesanantiket', compact('pemesananTikets'));
    }

    public function fakturPdf($id)
    {
        $pemesanan = Pemesanan_Tiket::with(['user', 'customer', 'detailPemesanan.wahana'])->findOrFail($id);

        $pdf = Pdf::loadView('transaksi.faktur', compact('pemesanan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Faktur_Pemesanan_' . $pemesanan->kode_pemesanan . '.pdf');
    }

    // Form pemesanan baru
    public function create()
    {

        // $customers = Customer::all();
        // $wahanas = Wahana::all();
        // return view('transaksi.create', compact('customers', 'wahanas'));
        // $kode_pemesanan = 'TKT' . now()->format('Ymd') . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);


        // Ambil tanggal hari ini dalam format YYYY-MM-DD (untuk filter tanggal)
        $tanggalHariIni = now()->format('Y-m-d');

        // Hitung jumlah pemesanan tiket yang dibuat hari ini (berdasarkan created_at)
        $jumlahHariIni = \App\Models\Pemesanan_Tiket::whereDate('created_at', $tanggalHariIni)->count();

        // Tambah 1 dari jumlah hari ini untuk membuat nomor urut
        // Gunakan str_pad agar hasilnya selalu 3 digit (misal: 001, 002, 010, 123)
        $nomorUrut = str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);

        // Gabungkan prefix 'TKT' + tanggal hari ini (format Ymd) + nomor urut tadi
        // Hasil akhir contoh: TKT20250717001
        $kode_pemesanan = 'TKT' . now()->format('Ymd') . $nomorUrut;

        return view('transaksi.create', [
            'kode_pemesanan' => $kode_pemesanan,
            'customers' => Customer::all(),
            'wahanas' => Wahana::all(),

        ]);
    }

    public function show($id)
    {
        $pemesananTikets = Pemesanan_Tiket::with(['customer', 'user', 'detailPemesanan.wahana'])->get();
        return view('transaksi.indexpemesanantiket', compact('pemesananTikets'));
    }

    // Simpan pemesanan baru
    public function store(Request $request)
    {
        // ✅ Validasi input
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|integer|exists:users,id',
            'tanggal_kunjungan' => 'required|date',
            'wahana_id' => 'required|array',
            'wahana_id.*' => 'exists:wahanas,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'harga' => 'required|array',
            'subtotal' => 'required|array',
            'total_harga' => 'required|numeric',
            'status' => 'required|in:pending,selesai,batal',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // 🧠 Generate kode pemesanan otomatis per hari
        $tanggalHariIni = now()->format('Y-m-d');
        $jumlahHariIni = Pemesanan_Tiket::whereDate('created_at', $tanggalHariIni)->count();
        $nomorUrut = str_pad($jumlahHariIni + 1, 3, '0', STR_PAD_LEFT);
        $kode_pemesanan = 'TKT' . now()->format('Ymd') . $nomorUrut;

        // 📂 Simpan file bukti pembayaran jika ada
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        // 💾 Simpan data utama ke tabel `pemesanan_tikets`
        $pemesanan = Pemesanan_Tiket::create([
            'kode_pemesanan'     => $kode_pemesanan,
            'customer_id'        => $request->customer_id,
            'user_id'            => $request->user_id,  // ✅ gunakan ID user, bukan nama!
            'tanggal_pemesanan'  => now(),
            'tanggal_kunjungan'  => $request->tanggal_kunjungan,
            'total_tiket'        => array_sum($request->jumlah),
            'total_harga'        => $request->total_harga,
            'status'             => $request->status,
            'bukti_pembayaran'   => $buktiPath,
        ]);

        // 🔁 Simpan detail untuk setiap wahana yang dipilih
        foreach ($request->wahana_id as $index => $wahana_id) {
            Detail_Pemesanan_Tiket::create([
                'pemesanan_tiket_id' => $pemesanan->id,
                'wahana_id'          => $wahana_id,
                'jumlah'       => $request->jumlah[$index],
                'harga'       => $request->harga[$index],
                'subtotal'           => $request->subtotal[$index],
            ]);
        }

        // ✅ Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pemesanantikets.index')->with('success', 'Pemesanan tiket berhasil disimpan.');
    }


    // Tampilkan form edit
    public function edit($id)
    {
        $pemesanan = Pemesanan_Tiket::with('detailPemesanan')->findOrFail($id);
        $customers = Customer::all();
        $wahanas = Wahana::all();
        return view('transaksi.edit', compact('pemesanan', 'customers', 'wahanas'));
    }

    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan_Tiket::findOrFail($id);

        // ✅ Validasi data yang dikirim dari form
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'user_id' => 'required|exists:users,id',
            'tanggal_kunjungan' => 'required|date',
            'wahana_id' => 'required|array',
            'wahana_id.*' => 'exists:wahanas,id',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1',
            'harga' => 'required|array',
            'harga.*' => 'numeric|min:0',
            'subtotal' => 'required|array',
            'subtotal.*' => 'numeric|min:0',
            'total_harga' => 'required|numeric|min:0',
            'status' => 'required|in:pending,selesai,batal',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // 📝 Update data utama
        $pemesanan->customer_id = $request->customer_id;
        $pemesanan->user_id = $request->user_id;
        $pemesanan->tanggal_kunjungan = $request->tanggal_kunjungan;
        $pemesanan->total_tiket = array_sum($request->jumlah);
        $pemesanan->total_harga = $request->total_harga;
        $pemesanan->status = $request->status;

        // 📤 Ganti file bukti jika ada upload baru
        if ($request->hasFile('bukti_pembayaran')) {
            // Hapus file lama
            if ($pemesanan->bukti_pembayaran && Storage::disk('public')->exists($pemesanan->bukti_pembayaran)) {
                Storage::disk('public')->delete($pemesanan->bukti_pembayaran);
            }
            // Upload file baru
            $pemesanan->bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        }

        $pemesanan->save();

        // 🔄 Hapus semua detail lama, lalu simpan ulang
        $pemesanan->detailPemesanan()->delete();
        foreach ($request->wahana_id as $index => $wahana_id) {
            Detail_Pemesanan_Tiket::create([
                'pemesanan_tiket_id' => $pemesanan->id,
                'wahana_id' => $wahana_id,
                'jumlah' => $request->jumlah[$index],
                'harga' => $request->harga[$index],
                'subtotal' => $request->subtotal[$index],
            ]);
        }

        return redirect()->route('pemesanantikets.index')->with('success', 'Pemesanan berhasil diperbarui.');
    }

    // Hapus pemesanan
    public function destroy($id)
    {
        $pemesanan = Pemesanan_Tiket::findOrFail($id);

        // Hapus bukti pembayaran
        if ($pemesanan->bukti_pembayaran && Storage::disk('public')->exists($pemesanan->bukti_pembayaran)) {
            Storage::disk('public')->delete($pemesanan->bukti_pembayaran);
        }

        // Hapus relasi detail
        $pemesanan->detailPemesanan()->delete();

        $pemesanan->delete();

        return redirect()->route('pemesanantikets.index')->with('success', 'Pemesanan berhasil dihapus.');
    }
}
