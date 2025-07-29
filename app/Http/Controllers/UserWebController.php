<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserWebController extends Controller
{
    // Menampilkan semua user
    public function index()
    {
        $users = User::all();
        return view('user.index', compact('users'));
    }

    // Menampilkan form tambah user
    public function create()
    {
        return view('user.create');
    }

    // Proses simpan user baru
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

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'nohp'     => $request->nohp,
            'alamat'   => $request->alamat,
            'status'   => $request->status,
        ]);

        return redirect()->route('users.index')->with('success', 'Data user berhasil ditambahkan');
    }

    // Menampilkan detail user
    public function show(User $user)
    {
        return view('user.show', compact('user'));
    }

    // Menampilkan form edit user
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    // Proses update user
    public function update(Request $request, User $user)
    {
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

        return redirect()->route('users.index')->with('success', 'Data user berhasil diedit');
    }

    // Menghapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
