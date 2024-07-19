<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign_user;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'user_type' => 'required|string|max:255',
                'election_type' => 'required|string|max:255',
                'election_date' => 'required|date_format:m/d/Y',
                'email' => 'required|string|email|max:255|unique:campaign_users,email_id',
                'phone_number' => 'required|string|max:15|unique:campaign_users,telephone',
                'pass' => 'required|string|confirmed|min:8',
            ]);

            $username = $validatedData['first_name'] . time();
            $user = Campaign_user::create([
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'user_type' => $validatedData['user_type'],
                'election_type' => $validatedData['election_type'],
                'election_date' => $validatedData['election_date'],
                'email_id' => $validatedData['email'],
                'telephone' => $validatedData['phone_number'],
                'pass' => bcrypt($validatedData['pass']),
                'username' =>  $username,
                'created_at' => NOW(),
                'updated_at' => NOW(),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'token' => $token
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'pass' => 'required|string',
            ]);

            $user = Campaign_user::where('email_id', $credentials['email'])->first();

            if (!$user || !Hash::check($credentials['pass'], $user->pass)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'token' => $token
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
