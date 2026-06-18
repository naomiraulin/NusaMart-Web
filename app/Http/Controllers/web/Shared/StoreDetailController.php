<?php

namespace App\Http\Controllers\Web\Shared;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\View\View;

class StoreDetailController extends Controller
{
    /**
     * Menampilkan detail profil toko untuk publik (Guest & Buyer).
     */
    public function show(string $id): View
    {
        // Mengambil data toko beserta relasi produk dan badge-nya
        $store = Store::with([
            'badgeVerifications', 
            'products.productImages', 
            'products.productItems'
        ])->findOrFail($id);

        
        return view('buyer.store-detail', compact('store'));
    }
}