<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\User;

class AuthController extends Controller
{
    public function login(Request $request) {
        $data = $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])->with('role')->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response([
                'message' => 'Username or password is incorrect',
            ], 401);
        }

        $token = Str::random(80);
        $user->api_token = $token;
        $user->save();

        return response([
            'token' => base64_encode($token),
            'user' => $user,
        ]);
    }

    public function logout(Request $request) {
        $user = Auth::user();
        
        $user->api_token = null;
        $user->save();

        return response([
            'message' => 'User logged out successfully',
        ]);
    }
}
