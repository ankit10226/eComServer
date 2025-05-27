<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->cookie('token'); 

        if (!$token) {
            return response()->json(['message' => 'No Token Provided!'], 403);
        }

        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
 
            $request->merge([
                'user' => [
                    'userId' => $decoded->sub ?? null,
                    'email' => $decoded->email ?? null,
                    'name'  => $decoded->name ?? null,
                    'role'  => $decoded->role ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        return $next($request);
    }
}
