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
        // Gunakan Eager Loading (with) untuk mengambil items dan images sekaligus
        $products = Product::with(['productItems', 'productImages'])
                           ->where('productStatus', 'ACTIVE')
                           ->get();
        return response()->json($products);
    }

    // GET /api/products/{id} → detail produk + items + images + variasi
    public function show(string $id)
    {
        $product = Product::where('idProduct', $id)->firstOrFail();

        // Eager Load 'productVariations' agar tidak terjadi N+1 tersembunyi di dalam loop Laravel
        $items = $product->productItems()->with('productVariations')->where('isActive', true)->get();

        // Attach variasi ke setiap item
        $itemsWithVariations = $items->map(function ($item) {
            $item->variations = $item->productVariations;
            unset($item->productVariations); // Opsional: bersihkan field duplikat
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
        // Terapkan Eager Loading juga di sini agar halaman toko tidak lag
        $products = Product::with(['productItems', 'productImages'])
                           ->where('idStore', $storeId)
                           ->get();
        return response()->json($products);
    }

    // GET /api/products/search?q=keyword → search produk
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        // Terapkan Eager Loading juga di sini agar hasil pencarian langsung muncul
        $products = Product::with(['productItems', 'productImages'])
            ->where('productStatus', 'ACTIVE')
            ->where('productName', 'like', '%' . $keyword . '%')
            ->get();

        return response()->json($products);
    }
}