<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthControllerApi extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        // Cari user berdasarkan name
        $user = User::where('name', $request->name)->first();

        // Validasi login
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Nama atau password salah'
            ], 401);
        }

        // Generate token menggunakan Sanctum
        $token = $user->createToken('mobile')->plainTextToken;

        // Return response lengkap
        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'nohp' => $user->nohp,
                'alamat' => $user->alamat,
                'status' => $user->status,
            ],
            'token' => $token
        ]);
    }
}
