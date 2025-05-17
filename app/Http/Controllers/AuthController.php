<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'user' => $user,
            'success' => "User created successfully"
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $request->email)->first();

        $user->tokens()->delete();

        $token = $user->createToken('token')->plainTextToken;

        $cookie = cookie(
            'auth_token', 
            $token,     
            60 * 24 * 1, 
            null,         
            null,       
            true,         
            true,         
            false,       
            'Strict'     
        );

        return response()->json([
            'user' => $user,
            'success' => "User login successfully",
        ], 200)->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out']);
    }


    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
