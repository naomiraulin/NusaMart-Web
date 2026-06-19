<?php

namespace App\Http\Controllers\Web\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\SubCategory;
use App\Services\ProductService;
use App\Services\StoreService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private StoreService   $storeService,
    ) {}

    public function index(Request $request): View
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $store = $this->storeService->getBySeller($user->idUser);

        $products = $this->productService->getByStore(
            $store->idStore,
            $request->only(['search', 'status', 'per_page']),
        );

        return view('seller.products.index', compact('store', 'products'));
    }

    public function create(): View
    {
        $subCategories = SubCategory::with('category')->get(); // hapus ->where('isActive', true)

        return view('seller.products.create', compact('subCategories'));
    }

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
            $request->input('variants', []),
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(string $id): View
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $store   = $this->storeService->getBySeller($user->idUser);
        $product = $this->productService->getById($id);

        if ($product->idStore !== $store->idStore) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $subCategories = SubCategory::with('category')->get(); // hapus ->where('isActive', true)

        return view('seller.products.edit', compact('product', 'subCategories'));
    }

    public function update(UpdateProductRequest $request, string $id): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $store   = $this->storeService->getBySeller($user->idUser);
        $product = $this->productService->getById($id);

        if ($product->idStore !== $store->idStore) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $this->productService->update(
            $id,
            $request->validated(),
            $request->file('images', []),
            $request->input('sub_category_ids', []),
            $request->input('delete_images', []),       // ID gambar yang dicentang untuk dihapus
            $request->input('existing_variants', []),   // Varian lama yang diedit
            $request->input('variants', []),            // Varian baru yang ditambahkan
        );

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $store   = $this->storeService->getBySeller($user->idUser);
        $product = $this->productService->getById($id);

        if ($product->idStore !== $store->idStore) {
            abort(403, 'Anda tidak memiliki akses ke produk ini.');
        }

        $this->productService->delete($id);

        return redirect()->route('seller.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}