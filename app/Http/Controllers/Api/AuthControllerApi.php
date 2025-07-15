<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class AuthControllerApi extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $customer = Customer::where('username', $request->username)->first();

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json([
                'message' => 'Username atau password salah'
            ], 401);
        }

        $token = $customer->createToken('mobile')->plainTextToken;

        return response()->json([
            'customer' => [
                'id' => $customer->id,
                'namacustomer' => $customer->namacustomer,
                'username' => $customer->username,
                'email' => $customer->email,
                'nohp' => $customer->nohp,
                'alamat' => $customer->alamat,
            ],
            'token' => $token,
        ]);
    }
}
