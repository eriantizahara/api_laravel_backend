<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerControllerApi extends Controller
{
    // Menampilkan semua customer
    public function index()
    {
        return response()->json(Customer::all(), 200);
    }

    // Menyimpan customer baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'namacustomer' => 'required|string|max:255',
            'username'     => 'required|string|max:50|unique:customers,username',
            'password'     => 'required|string|min:6',
            'email'        => 'required|email|unique:customers,email',
            'nohp'         => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $customer = Customer::create($validated);

        return response()->json($customer, 201);
    }

    // Menampilkan detail customer berdasarkan ID
    public function show($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }

        return response()->json($customer, 200);
    }

    // Memperbarui data customer
    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'namacustomer' => 'required|string|max:255',
            'username'     => 'required|string|max:50|unique:customers,username,' . $customer->id,
            'password'     => 'nullable|string|min:6',
            'email'        => 'required|email|unique:customers,email,' . $customer->id,
            'nohp'         => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $customer->update($validated);

        return response()->json($customer, 200);
    }

    // Menghapus customer
    public function destroy($id)
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json(['message' => 'Customer tidak ditemukan'], 404);
        }

        $customer->delete();

        return response()->json(['message' => 'Customer berhasil dihapus'], 200);
    }
}
