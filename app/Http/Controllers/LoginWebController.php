<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginWebController extends Controller
{
    // Tampilkan form login
    public function showLoginForm()
    {
        return view('login.createlogin'); // sesuaikan dengan path view kamu
    }

    public function ceklogin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan name (atau bisa juga pakai email jika perlu)
        $user = \App\Models\User::where('name', $request->name)->first();

        if ($user) {
            // Jika bukan admin, tolak login
            if ($user->status !== 'admin') {
                return back()->with('msg', 'Hanya admin yang dapat login, customer tidak diizinkan.');
            }

            // Jika admin, cek kredensial password
            if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {
                $request->session()->regenerate();
                return redirect()->intended('/dashboard'); // arahkan sesuai halaman dashboard admin
            } else {
                return back()->with('msg', 'Password salah.');
            }
        }

        // Jika user tidak ditemukan
        return back()->with('msg', 'Akun tidak ditemukan.');
    }


    // Logout method (di luar resource pattern, bisa pakai route biasa)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
