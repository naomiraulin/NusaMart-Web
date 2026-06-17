<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\StoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function __construct(
        private StoreService $storeService,
    ) {}

    /**
     * Daftar semua toko.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'location']);
        $stores  = $this->storeService->getAll($filters);

        return view('admin.stores.index', compact('stores'));
    }

    /**
     * Detail toko.
     */
    public function show(string $id): View
    {
        $store = $this->storeService->getById($id);

        return view('admin.stores.show', compact('store'));
    }

    /**
     * Nonaktifkan toko.
     */
    public function deactivate(string $id): RedirectResponse
    {
        $this->storeService->deactivate($id);

        return back()->with('success', 'Toko berhasil dinonaktifkan.');
    }

    /**
     * Aktifkan kembali toko.
     */
    public function activate(string $id): RedirectResponse
    {
        $this->storeService->activate($id);

        return back()->with('success', 'Toko berhasil diaktifkan.');
    }
}