<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Services\ProductService;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function __construct(
        private ProductService $productService,
        private ReviewService  $reviewService,
    ) {}

    /**
     * Daftar semua produk — moderasi konten.
     */
    public function products(Request $request): View
    {
        $filters  = $request->only(['search', 'status', 'category']);
        $products = $this->productService->getAll($filters);

        return view('admin.content.products', compact('products'));
    }

    /**
     * Nonaktifkan produk.
     */
    public function deactivateProduct(string $id): RedirectResponse
    {
        $this->productService->update($id, ['product_status' => 'INACTIVE']);

        return back()->with('success', 'Produk berhasil dinonaktifkan.');
    }

    /**
     * Hapus produk.
     */
    public function deleteProduct(string $id): RedirectResponse
    {
        $this->productService->delete($id);

        return redirect()->route('admin.content.products')
            ->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Sembunyikan ulasan yang melanggar.
     */
    public function hideReview(string $id): RedirectResponse
    {
        $this->reviewService->hide($id);

        return back()->with('success', 'Ulasan berhasil disembunyikan.');
    }
}