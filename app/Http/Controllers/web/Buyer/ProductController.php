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
    /**
     * Halaman hasil pencarian produk dengan filter.
     */
    public function search(SearchProductRequest $request): View
    {
        // 1. Ambil semua input dari form (termasuk sort, harga, dll) dalam bentuk ARRAY
        $filters = $request->validated();

        // 2. Ambil kata kuncinya saja untuk ditampilkan di teks "Hasil Pencarian: ..." pada Blade
        $search = $filters['search'] ?? '';

        // 3. PANGGIL SERVICE DENGAN VARIABEL $filters (Ini yang memperbaiki error di baris 43)
        $products = $this->productService->searchProducts($filters);

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