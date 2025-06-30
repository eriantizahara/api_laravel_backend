<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wahana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WahanaControllerApi extends Controller
{
    // Tampilkan semua wahana
    public function index()
    {
        $data = Wahana::all()->map(function ($wahana) {
            $wahana->foto = $wahana->foto ? asset('storage/' . $wahana->foto) : null;
            return $wahana;
        });

        return response()->json($data);
    }

    // Simpan wahana baru
    public function store(Request $request)
    {
        $request->headers->set('Accept', 'application/json');

        try {
            // Validasi input
            $validatedData = $request->validate([
                'kode_wahana'  => 'required|unique:wahanas',
                'nama_wahana'  => 'required|string|max:255',
                'deskripsi'    => 'nullable|string',
                'harga_tiket'  => 'required|numeric',
                'foto'         => 'nullable|image|mimes:jpeg,png,jpg|max:40960',
            ]);

            $data = $request->only(['kode_wahana', 'nama_wahana', 'deskripsi', 'harga_tiket']);

            if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
                $data['foto'] = $request->file('foto')->store('foto_wahana', 'public');
            } else {
                $data['foto'] = null;
            }

            $wahana = Wahana::create($data);

            if ($wahana->foto) {
                $wahana->foto = asset('storage/' . $wahana->foto);
            }

            return response()->json([
                'message' => 'Data wahana berhasil disimpan.',
                'data' => $wahana
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tampilkan detail wahana
    public function show($id)
    {
        $wahana = Wahana::findOrFail($id);
        $wahana->foto = $wahana->foto ? asset('storage/' . $wahana->foto) : null;

        return response()->json($wahana, 200);
    }

    // Update wahana
    public function update(Request $request, Wahana $wahana)
    {
        $request->validate([
            'kode_wahana' => 'required|unique:wahanas,kode_wahana,' . $wahana->id,
            'nama_wahana' => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'harga_tiket' => 'required|numeric',
            'foto'        => 'nullable|image|mimes:jpeg,png,jpg|max:40960',
        ]);

        $path = $wahana->foto;
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
            $path = $request->file('foto')->store('foto_wahana', 'public');
        }

        $wahana->update([
            'kode_wahana' => $request->kode_wahana,
            'nama_wahana' => $request->nama_wahana,
            'deskripsi'   => $request->deskripsi,
            'harga_tiket' => $request->harga_tiket,
            'foto'        => $path
        ]);

        if ($wahana->foto) {
            $wahana->foto = asset('storage/' . $wahana->foto);
        }

        return response()->json([
            'message' => 'Data wahana berhasil diupdate.',
            'data' => $wahana
        ]);
    }

    // Hapus wahana
    public function destroy(Wahana $wahana)
    {
        if ($wahana->foto && Storage::disk('public')->exists($wahana->foto)) {
            Storage::disk('public')->delete($wahana->foto);
        }
        $wahana->delete();

        return response()->json([
            'message' => 'Data wahana berhasil dihapus.'
        ]);
    }
}
