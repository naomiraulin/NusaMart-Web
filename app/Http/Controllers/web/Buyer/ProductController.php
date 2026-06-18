<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\ReviewService;
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