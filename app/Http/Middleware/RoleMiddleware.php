<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage di route: middleware('role:seller') atau middleware('role:admin')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return $this->unauthorizedResponse($request);
        }

        if (!in_array($request->user()->role, $roles)) {
            return $this->forbiddenResponse($request);
        }

        return $next($request);
    }

    private function unauthorizedResponse(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return redirect()->route('login');
    }

    private function forbiddenResponse(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success'  => false,
                'message'  => 'Akses ditolak. Kamu tidak memiliki izin untuk mengakses halaman ini.',
            ], 403);
        }

        abort(403, 'Akses ditolak.');
    }
}