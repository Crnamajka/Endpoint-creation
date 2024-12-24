<?php

namespace App\Http\Controllers\Api\v1;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShoppingCart;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        try {
            $validatedData = $request->validate([
                'Username' => 'required|string|max:255',
                'Email' => 'required|string|email|max:255|unique:users',
                'PasswordHash' => 'required|string|min:8'
            ]);

            $user = User::create([
                'Username' => $validatedData['Username'],
                'Email' => $validatedData['Email'],
                'PasswordHash' => Hash::make($validatedData['PasswordHash']),
                'CreatedDate' => now()
            ]);

            ShoppingCart::create([
                'UserID' => $user->UserID,
                'CreatedDate' => now(),
                'Status' => 'open'
            ]);

            return response()->json([
                'user' => $user,
                'message' => 'User Registered Successfully'
            ], 201);
        } catch (Exception $e) {
            // Capturar excepciones y devolver error
            return response()->json([
                'message' => 'Error during registration. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request) 
    {
        try {

            $validatedData = $request->validate([
                'Email' => 'required|email',
                'PasswordHash' => 'required'
            ]);

            $user = User::where('Email', $validatedData['Email'])->first();

            if (!$user || !Hash::check($validatedData['PasswordHash'], $user->PasswordHash)) {
                return response()->json(['message' => 'The provided credentials are incorrect'], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            $shoppingCart = ShoppingCart::firstOrCreate([
                'UserID' => $user->UserID,
                'Status' => 'open'
            ], ['CreatedDate' => now()]);

            return response()->json([
                'user' => [
                    'id' => $user->UserID,
                    'Username' => $user->Username,
                    'Email' => $user->Email,
                ],
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred during login. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function me(Request $request) 
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    public function logout(Request $request) 
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json(['message' => 'Logged out successfully']);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while logging out. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
