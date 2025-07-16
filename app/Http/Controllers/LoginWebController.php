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

    // Proses login
    public function ceklogin(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('name', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // Atur sesuai halaman tujuan
        }

        return back()->with('msg', 'Username atau Password salah.');
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
