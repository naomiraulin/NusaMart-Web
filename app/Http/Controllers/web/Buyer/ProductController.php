<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\ReviewService;
use App\Http\Requests\Product\SearchProductRequest;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private ReviewService  $reviewService,
    ) {}

    /**
     * Halaman daftar semua produk (homepage).
     */
    public function index(): View
    {
        $products = $this->productService->getAll();

        return view('welcome', compact('products'));
    }

    /**
     * Halaman hasil pencarian produk.
     * Dapat diakses oleh Guest maupun Buyer.
     */
    public function search(SearchProductRequest $request): View
    {
        $search = $request->input('search');

        // 2. Jika input kosong, kembalikan ke view dengan data kosong
        if (!$search) {
            // 3. Samakan path view menjadi 'shared.search-result'
            return view('shared.search-result', ['products' => collect(), 'search' => $search]);
        }

        // 3. Panggil method pencarian dari ProductService
        $products = $this->productService->searchProducts($search);

        // 4. Lempar data produk dan kata kunci ke view search-result
        return view('shared.search-result', compact('products', 'search'));
    }

    /**
     * Halaman detail produk.
     * Guest bisa akses — tombol keranjang conditional di view (@auth).
     */
    public function show(string $id): View
    {
        $product = $this->productService->getById($id);
        $reviews = $this->reviewService->getByProduct($id);

        return view('buyer.product-detail', compact('product', 'reviews'));
    }
}