<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private ProductService $productService,
    ) {}

    /**
     * Halaman utama — listing produk, search, filter kategori.
     * Bisa diakses guest maupun buyer yang sudah login.
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'search', 'category', 'subCategory', 'sort_by', 'sort_dir', 'per_page'
        ]);

        $products   = $this->productService->getAll($filters);
        $categories = Category::with('subCategories')
            ->where('isActive', true)
            ->get();

        return view('buyer.home', compact('products', 'categories', 'filters'));
    }
}