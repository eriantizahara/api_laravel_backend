<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserControllerApi extends Controller
{
    // Menampilkan semua user (GET /api/users)
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // Menyimpan user baru (POST /api/users)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nohp'     => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'status'   => 'required|in:admin,customer',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'nohp'     => $request->nohp,
            'alamat'   => $request->alamat,
            'status'   => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data'    => $user
        ], 201);
    }

    // Menampilkan detail user (GET /api/users/{id})
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'data' => $user]);
    }

    // Mengupdate user (PUT/PATCH /api/users/{id})
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'nohp'     => 'nullable|string|max:20',
            'alamat'   => 'nullable|string',
            'status'   => 'required|in:admin,customer',
        ]);

        $user->update([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            'nohp'     => $request->nohp,
            'alamat'   => $request->alamat,
            'status'   => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate',
            'data'    => $user
        ]);
    }

    // Menghapus user (DELETE /api/users/{id})
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus'
        ]);
    }
}
