<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'message' => 'User saved successfully',
                'user' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function login(Request $request)
    {
        try { 
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
 
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['message' => 'User Not Found!'], 404);
            } 
            if (!Hash::check($request->password, $user->password)) {
                return response()->json(['message' => 'Invalid Password!'], 400);
            }
 
            $payload = [
                'iss' => 'laravel-api',
                'sub' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role ?? 'user',
                'iat' => time(),
                'exp' => time() + (60 * 60) 
            ];

            $token = JWT::encode($payload, env('JWT_SECRET'), 'HS256');

            return response()
                ->json([
                    'message' => 'Login Successful',
                    'user' => [
                        'userId' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role ?? 'user'
                    ]
                ])
                ->cookie(
                    'token',
                    $token,
                    60,  
                    '/',
                    null,
                    false,  
                    true,   
                    false   
                );

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function verifyUser(Request $request)
    {
        return response()->json([
            'user' => $request->user
        ], 200);
    }

    public function logout(Request $request)
    {
        return response()
            ->json(['message' => 'Logout successful'], 200)
            ->withoutCookie('token', '/', null, false, true, false, 'None');
    }
}
