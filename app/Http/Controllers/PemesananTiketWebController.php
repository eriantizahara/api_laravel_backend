<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan_Tiket;
use App\Models\Detail_Pemesanan_Tiket;
use App\Models\User;
use App\Models\Wahana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class PemesananTiketWebController extends Controller
{
    // Tampilkan semua pemesanan
    public function index()
    {
        $pemesanans = Pemesanan_Tiket::with('user', 'detailPemesanan.wahana')->orderBy('id', 'asc')->get();
        return view('transaksi.indexpemesanantiket', compact('pemesanans'));
    }

    public function fakturPdf($id)
    {
        $pemesanan = Pemesanan_Tiket::with(['user', 'detailPemesanan.wahana'])->findOrFail($id);

        $pdf = Pdf::loadView('transaksi.faktur', compact('pemesanan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('Faktur_Pemesanan_' . $pemesanan->kode_pemesanan . '.pdf');
    }

    // Tampilkan form create
    public function create()
    {
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

            // ✅ Kirim data ke view Blade
            return view('transaksi.create', [
                'kode_pemesanan' => $kode_pemesanan,
                'users' => User::where('status', 'customer')->get(),
                'wahanas' => Wahana::all(),
                'user_id' => Auth::id(),
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membuat kode pemesanan: ' . $e->getMessage()]);
        }
    }


    // Simpan data baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id'       => 'required|exists:users,id',
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

        // Generate kode pemesanan otomatis: TKT20250721001
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

        // Upload bukti pembayaran (jika ada)
        $buktiPath = $request->hasFile('bukti_pembayaran')
            ? $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public')
            : null;

        // Simpan ke tabel pemesanan_tikets
        $pemesanan = Pemesanan_Tiket::create([
            'kode_pemesanan'    => $kode_pemesanan,
            'user_id'       => $request->user_id,
            'tanggal_pemesanan' => now(),
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'total_tiket'       => array_sum($request->jumlah),
            'total_harga'       => $request->total_harga,
            'status'            => $request->status,
            'bukti_pembayaran'  => $buktiPath,
        ]);

        // Simpan data ke tabel detail_pemesanan_tikets
        foreach ($request->wahana_id as $i => $wahanaId) {
            Detail_Pemesanan_Tiket::create([
                'pemesanan_tiket_id' => $pemesanan->id,
                'wahana_id'          => $wahanaId,
                'jumlah'             => $request->jumlah[$i],
                'harga'              => $request->harga[$i],
                'subtotal'           => $request->subtotal[$i],
            ]);
        }

        // Redirect dengan pesan sukses
        return redirect()->route('pemesanantikets.index')->with('success', 'Data pemesanan tiket berhasil ditambahkan.');
    }


    // Tampilkan form edit
    public function edit($id)
    {
        // Ambil data pemesanan beserta relasi detail wahana
        $pemesanan = Pemesanan_Tiket::with('detailPemesanan')->findOrFail($id);

        // Ambil semua user dengan status customer
        $users = User::where('status', 'customer')->get();

        // Ambil semua wahana
        $wahanas = Wahana::all();

        // Ambil ID admin yang login
        $user_id = Auth::id();

        return view('transaksi.edit', [
            'pemesanan' => $pemesanan,
            'users' => $users,
            'wahanas' => $wahanas,
            'user_id' => $user_id
        ]);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
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

        $pemesanan = Pemesanan_Tiket::findOrFail($id);

        // Ganti bukti jika ada
        if ($request->hasFile('bukti_pembayaran')) {
            if ($pemesanan->bukti_pembayaran) {
                Storage::disk('public')->delete($pemesanan->bukti_pembayaran);
            }

            $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            $pemesanan->bukti_pembayaran = $buktiPath;
        }

        $pemesanan->update([
            'user_id'           => $request->user_id,
            'tanggal_kunjungan' => $request->tanggal_kunjungan,
            'total_tiket'       => array_sum($request->jumlah),
            'total_harga'       => $request->total_harga,
            'status'            => $request->status,
        ]);

        // Hapus & simpan ulang detail
        $pemesanan->detailPemesanan()->delete();

        foreach ($request->wahana_id as $i => $wahanaId) {
            Detail_Pemesanan_Tiket::create([
                'pemesanan_tiket_id' => $pemesanan->id,
                'wahana_id'          => $wahanaId,
                'jumlah'             => $request->jumlah[$i],
                'harga'              => $request->harga[$i],
                'subtotal'           => $request->subtotal[$i],
            ]);
        }

        return redirect()->route('pemesanantikets.index')->with('success', 'Data pemesanan tiket berhasil diedit.');
    }

    // Hapus data
    public function destroy($id)
    {
        $pemesanan = Pemesanan_Tiket::findOrFail($id);

        // Hapus file
        if ($pemesanan->bukti_pembayaran) {
            Storage::disk('public')->delete($pemesanan->bukti_pembayaran);
        }

        // Hapus detail
        $pemesanan->detailPemesanan()->delete();

        // Hapus utama
        $pemesanan->delete();

        return redirect()->route('pemesanantikets.index')->with('success', 'Pemesanan berhasil dihapus.');
    }
}
