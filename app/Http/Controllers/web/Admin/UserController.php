<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private StoreService $storeService,
    ) {}

    /**
     * Daftar semua user.
     */
    public function index(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('username', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('email', 'like', '%' . $request->input('search') . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->latest('createAt')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Detail user.
     */
    public function show(string $id): View
    {
        $user = User::with(['seller.store'])->where('idUser', $id)->firstOrFail();

        return view('admin.users.show', compact('user'));
    }

    /**
     * Nonaktifkan / ban user.
     * Implementasi sederhana — bisa dikembangkan dengan field isBanned di tabel users.
     */
    public function ban(string $id): RedirectResponse
    {
        $user = User::where('idUser', $id)->firstOrFail();

        // Jika seller, nonaktifkan tokonya juga
        if ($user->role === 'SELLER') {
            $store = $this->storeService->getBySeller($id);
            if ($store) {
                $this->storeService->deactivate($store->idStore);
            }
        }

        // TODO: tambah field isBanned ke tabel users jika belum ada
        $user->update(['role' => 'BANNED']);

        return redirect()->route('admin.users.index')
            ->with('success', "User {$user->username} berhasil dinonaktifkan.");
    }

    /**
     * Aktifkan kembali user.
     */
    public function unban(string $id): RedirectResponse
    {
        $user = User::where('idUser', $id)->firstOrFail();
        $user->update(['role' => 'BUYER']);

        return redirect()->route('admin.users.show', $id)
            ->with('success', "User {$user->username} berhasil diaktifkan kembali.");
    }
}