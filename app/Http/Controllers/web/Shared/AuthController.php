<?php

namespace App\Http\Controllers\Web\Shared;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ) {}

    // -------------------------
    // Form halaman
    // -------------------------

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    // -------------------------
    // Aksi
    // -------------------------

    public function login(LoginRequest $request): RedirectResponse
    {
        dd('controller login reached', $request->validated());
        $result = $this->authService->login($request->validated());

        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = $result['user'];
        Auth::login($user);
        $request->session()->regenerate();
        return $this->redirectByRole($user->role);
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $role = $request->input('role');

        if ($role === 'SELLER') {
            $result = $this->authService->registerSeller($request->validated());
        } else {
            $result = $this->authService->registerBuyer($request->validated());
        }

        Auth::login($result['user']);

        $request->session()->regenerate();

        return $this->redirectByRole($result['user']->role);
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    // -------------------------
    // Helper
    // -------------------------

    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'SELLER' => redirect()->route('seller.dashboard'),
            'ADMIN'  => redirect()->route('admin.dashboard'),
            default  => redirect()->route('home'),
        };
    }
}