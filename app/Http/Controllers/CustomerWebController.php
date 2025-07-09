<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerWebController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'namacustomer' => 'required',
            'username'     => 'required|unique:customers,username',
            'password'     => 'nullable|min:6',
            'email'        => 'required|email|unique:customers,email',
            'nohp'        => 'nullable',
            'alamat'       => 'nullable',
        ]);

        $data = [
            'namacustomer' => $request->namacustomer,
            'username'     => $request->username,
            'email'        => $request->email,
            'nohp'        => $request->nohp,
            'alamat'       => $request->alamat,
        ];

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        Customer::create($data);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil ditambahkan');
    }

    public function show(Customer $customer)
    {
        return view('customer.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'namacustomer' => 'required',
            'username'     => 'required|unique:customers,username,' . $customer->id,
            'password'     => 'nullable|min:6',
            'email'        => 'required|email|unique:customers,email,' . $customer->id,
            'nohp'        => 'nullable',
            'alamat'       => 'nullable',
        ]);

        $customer->update([
            'namacustomer' => $request->namacustomer,
            'username'     => $request->username,
            'email'        => $request->email,
            'nohp'        => $request->nohp,
            'alamat'       => $request->alamat,
            'password'     => $request->filled('password') ? bcrypt($request->password) : $customer->password,
        ]);

        return redirect()->route('customers.index')->with('success', 'Customer berhasil diupdate');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')->with('success', 'Customer berhasil dihapus');
    }
}
