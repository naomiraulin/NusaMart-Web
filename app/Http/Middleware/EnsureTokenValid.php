<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTokenValid
{
    /**
     * Handle an incoming request.
     * Middleware ini dipakai di route API untuk memastikan token Sanctum
     * ada, valid, dan belum expired.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak ditemukan. Silakan login terlebih dahulu.',
            ], 401);
        }

        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah expired. Silakan login ulang.',
            ], 401);
        }

        // Cek apakah token sudah expired (opsional, jika set tokenExpiration di config)
        $accessToken = $request->user()->currentAccessToken();

        if (
            $accessToken
            && config('sanctum.expiration')
            && $accessToken->created_at->addMinutes(config('sanctum.expiration'))->isPast()
        ) {
            $accessToken->delete();

            return response()->json([
                'success' => false,
                'message' => 'Sesi kamu sudah berakhir. Silakan login ulang.',
            ], 401);
        }

        return $next($request);
    }
}