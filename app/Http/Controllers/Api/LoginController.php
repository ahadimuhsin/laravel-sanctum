<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //function untuk login
    public function index (Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        //mencari data email sesuai inputan
        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password))
        {
            return response([
                'success' => false,
                'message' => ['Akun tidak ditemukan di database kami']
            ], 404);
        }

        $token = $user->createToken('ApiToken')->plainTextToken;

        $response= [
            'success' => true,
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    //function logout
    public function logout()
    {
        auth()->logout();
        return response()->json([
            'success' => true
        ], 200);

    }
}
