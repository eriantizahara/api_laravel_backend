<?php

namespace App\Http\Controllers;

use App\Models\Wahana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WahanaWebController extends Controller
{
    // Tampilkan semua data wahana
    public function index()
    {
        $wahanas = Wahana::all(); // Ambil semua data tanpa pagination
        return view('wahana.index', compact('wahanas'));
    }

    // Form tambah data
    public function create()
    {
        return view('wahana.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_wahana'   => 'required|unique:wahanas,kode_wahana',
            'nama_wahana'   => 'required',
            'deskripsi'     => 'required',
            'harga_tiket'   => 'required|numeric',
            'foto'          => 'required|image|max:2048'
        ]);

        $path = null;

        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $path = $request->file('foto')->store('foto_wahana', 'public');
        }

        Wahana::create([
            'kode_wahana'   => $request->kode_wahana,
            'nama_wahana'   => $request->nama_wahana,
            'deskripsi'     => $request->deskripsi,
            'harga_tiket'   => $request->harga_tiket,
            'foto'          => $path
        ]);

        return redirect()->route('wahanas.index')->with('success', 'Data Wahana berhasil ditambahkan');
    }

    // Form edit
    public function edit(Wahana $wahana)
    {
        return view('wahana.edit', compact('wahana'));
    }

    // Simpan perubahan edit
    public function update(Request $request, Wahana $wahana)
    {
        $request->validate([
            'kode_wahana'   => 'required|unique:wahanas,kode_wahana,' . $wahana->id,
            'nama_wahana'   => 'required',
            'deskripsi'     => 'required',
            'harga_tiket'   => 'required|numeric',
            'foto'          => 'nullable|image|max:2048'
        ]);

        $path = $wahana->foto;

        if ($request->hasFile('foto')) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            $path = $request->file('foto')->store('foto_wahana', 'public');
        }

        $wahana->update([
            'kode_wahana'   => $request->kode_wahana,
            'nama_wahana'   => $request->nama_wahana,
            'deskripsi'     => $request->deskripsi,
            'harga_tiket'   => $request->harga_tiket,
            'foto'          => $path
        ]);

        return redirect()->route('wahanas.index')->with('success', 'Data Wahana berhasil diupdate');
    }

    // Hapus data
    public function destroy(Wahana $wahana)
    {
        if ($wahana->foto && Storage::disk('public')->exists($wahana->foto)) {
            Storage::disk('public')->delete($wahana->foto);
        }

        $wahana->delete();

        return redirect()->route('wahanas.index')->with('success', 'Data Wahana berhasil dihapus');
    }
}
