<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->hasRole('kolektor')) {
                $token = $user->createToken('api-token')->plainTextToken;

                return response()->json([
                    'success' => true,
                    'data' => [
                        'token' => $token,
                        'user' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'email' => $user->email,
                            'roles' => $user->getRoleNames(), // Get roles as an array
                            'pasars' => $user->pasars->map(function ($pasar) {
                                return [
                                    'id' => $pasar->id,
                                    'name' => $pasar->name,
                                ];
                            }),
                        ],
                    ],
                    'message' => 'Login successful',
                ], 200);
            }

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Unauthorized. Only kolektor can access the API.',
            ], 403);
        }

        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Invalid credentials',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'data' => null,
            'message' => 'Logged out successfully',
        ], 200);
    }

    public function user(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->getRoleNames(), // Include roles here as well
                    'pasars' => $user->pasars->map(function ($pasar) {
                        return [
                            'id' => $pasar->id,
                            'name' => $pasar->name,
                        ];
                    }),
                ],
            ],
            'message' => 'User  data retrieved successfully',
        ], 200);
    }
}
