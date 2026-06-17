<?php

namespace App\Http\Controllers\Web\Shared;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function __construct(
        private AuthService $authService,
    ) {}

    /**
     * Halaman profil user.
     */
    public function show(): View
    {
        $user = Auth::user();

        return view('shared.profile', compact('user'));
    }

    /**
     * Update profil user.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'username'  => ['sometimes', 'string', 'max:100'],
            'phone'     => ['sometimes', 'string', 'max:20'],
            'password'  => ['sometimes', 'confirmed', 'min:8'],
            'image'     => ['sometimes', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->only(['username', 'phone', 'password']);

        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store(
                'users/' . Auth::id(), 'public'
            );
        }

        $this->authService->updateProfile(Auth::id(), $data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}