<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user (password auto-hashed by User model's casts)
        $user = User::create($validated);


        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
            'token' => $token,
        ], 201);
    }


    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Use Auth::attempt instead of manual Hash::check
        if (!\Auth::attempt($validated)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $validated['email'])->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }

    // Get current logged-in user info

    // public = anyone can call this method via HTTP request, but only authenticated user can get the user info, because we will protect this route with auth:sanctum middleware
    // function user = Method name.
    // Request $request = Laravel injects the current HTTP request object that contains headers, body, authenticated user info, etc.
    // $request->user() = This retrieves the currently authenticated user. (is logged valid with valid token? and route protect by middleweare is auth:sanctum or auth.)
    public function user(Request $request)
    {
        return response()->json($request->user(), 200);
    }
}
