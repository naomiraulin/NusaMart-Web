<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\SubCategory;
use App\Services\ProductService;
use App\Services\StoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private StoreService   $storeService,
    ) {}

    /**
     * Daftar produk milik seller.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $store    = $this->storeService->getBySeller($user->idUser);
        $products = $this->productService->getByStore($store->idStore);

        return view('seller.products.index', compact('products'));
    }

    /**
     * Form tambah produk baru.
     */
    public function create(): View
    {
        $subCategories = SubCategory::with('category')->get();

        return view('seller.products.create', compact('subCategories'));
    }

    /**
     * Simpan produk baru.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        $this->productService->create(
            $store->idStore,
            $request->validated(),
            $request->file('images', []),
            $request->input('sub_category_ids', []),
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Form edit produk.
     */
    public function edit(string $id): View
    {
        $product       = $this->productService->getById($id);
        $subCategories = SubCategory::with('category')->get();

        return view('seller.products.edit', compact('product', 'subCategories'));
    }

    /**
     * Update produk.
     */
    public function update(UpdateProductRequest $request, string $id): RedirectResponse
    {
        $this->productService->update(
            $id,
            $request->validated(),
            $request->file('images', []),
            $request->input('sub_category_ids', []),
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Hapus produk.
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->productService->delete($id);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}