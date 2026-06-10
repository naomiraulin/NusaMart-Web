<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products → semua produk aktif
    public function index()
    {
        $products = Product::where('productStatus', 'ACTIVE')->get();
        return response()->json($products);
    }

    // GET /api/products/{id} → detail produk + items + images + variasi
    public function show(string $id)
    {
        $product = Product::where('idProduct', $id)->firstOrFail();

        $items = $product->productItems()->where('isActive', true)->get();

        // Attach variasi ke setiap item
        $itemsWithVariations = $items->map(function ($item) {
            $item->variations = $item->productVariations;
            return $item;
        });

        return response()->json([
            'product'    => $product,
            'items'      => $itemsWithVariations,
            'images'     => $product->productImages,
            'categories' => $product->subCategories,
        ]);
    }

    // GET /api/categories → semua kategori aktif
    public function categories()
    {
        $categories = Category::where('isActive', true)->get();
        return response()->json($categories);
    }

    // GET /api/categories/{id}/subcategories → subkategori by kategori
    public function subCategories(string $id)
    {
        $subCategories = SubCategory::where('idCategory', $id)->get();
        return response()->json($subCategories);
    }

    // GET /api/products/store/{storeId} → produk by toko
    public function byStore(string $storeId)
    {
        $products = Product::where('idStore', $storeId)->get();
        return response()->json($products);
    }

    // GET /api/products/search?q=keyword → search produk
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $products = Product::where('productStatus', 'ACTIVE')
            ->where('productName', 'like', '%' . $keyword . '%')
            ->get();

        return response()->json($products);
    }
}