<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\OrderItem;
use App\Models\Review;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products → semua produk aktif dengan rating & terjual dinamis
    public function index()
    {
        $products = Product::with(['productItems', 'productImages'])
            ->where('productStatus', 'ACTIVE')
            ->get()
            ->map(function ($product) {
                $itemIds = $product->productItems->pluck('idItem');

                // 1. Hitung Terjual Dinamis
                $soldCount = OrderItem::whereIn('idItem', $itemIds)
                    ->whereHas('order', function ($query) {
                        $query->whereNotIn('orderStatus', ['PENDING', 'CANCELLED', 'FAILED']);
                    })->sum('quantity');

                // 2. Hitung Rating Dinamis dari Review
                $orderItemIds = OrderItem::whereIn('idItem', $itemIds)->pluck('idOrderItem');
                $avgRating = Review::whereIn('idOrderItem', $orderItemIds)
                    ->where('isHidden', false)
                    ->avg('rating');

                $product->setAttribute('soldCount', (int) $soldCount);
                $product->setAttribute('avgRating', $avgRating ? round($avgRating, 1) : ($product->avgRating ?? 0.0));

                return $product;
            });

        return response()->json($products);
    }

    // GET /api/products/{id} → detail produk + items + images + variasi + terjual & rating dinamis
    public function show(string $id)
    {
        $product = Product::where('idProduct', $id)->firstOrFail();

        $items = $product->productItems()->with('productVariations')->where('isActive', true)->get();

        $itemsWithVariations = $items->map(function ($item) {
            $item->variations = $item->productVariations;
            unset($item->productVariations); 
            return $item;
        });

        $itemIds = $items->pluck('idItem');

        // 1. Hitung Terjual Dinamis
        $soldCount = OrderItem::whereIn('idItem', $itemIds)
            ->whereHas('order', function ($query) {
                $query->whereNotIn('orderStatus', ['PENDING', 'CANCELLED', 'FAILED']);
            })->sum('quantity');

        // 2. Hitung Rating Dinamis dari Review
        $orderItemIds = OrderItem::whereIn('idItem', $itemIds)->pluck('idOrderItem');
        $avgRating = Review::whereIn('idOrderItem', $orderItemIds)
            ->where('isHidden', false)
            ->avg('rating');

        $product->setAttribute('soldCount', (int) $soldCount);
        $product->setAttribute('avgRating', $avgRating ? round($avgRating, 1) : ($product->avgRating ?? 0.0));

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

    // GET /api/products/store/{storeId} → produk by toko dengan rating & terjual dinamis
    public function byStore(string $storeId)
    {
        $products = Product::with(['productItems', 'productImages'])
            ->where('idStore', $storeId)
            ->get()
            ->map(function ($product) {
                $itemIds = $product->productItems->pluck('idItem');

                $soldCount = OrderItem::whereIn('idItem', $itemIds)
                    ->whereHas('order', function ($query) {
                        $query->whereNotIn('orderStatus', ['PENDING', 'CANCELLED', 'FAILED']);
                    })->sum('quantity');

                $orderItemIds = OrderItem::whereIn('idItem', $itemIds)->pluck('idOrderItem');
                $avgRating = Review::whereIn('idOrderItem', $orderItemIds)
                    ->where('isHidden', false)
                    ->avg('rating');

                $product->setAttribute('soldCount', (int) $soldCount);
                $product->setAttribute('avgRating', $avgRating ? round($avgRating, 1) : ($product->avgRating ?? 0.0));

                return $product;
            });

        return response()->json($products);
    }

    // GET /api/products/search?q=keyword → search produk dengan rating & terjual dinamis
    public function search(Request $request)
    {
        $keyword = $request->query('q');

        $products = Product::with(['productItems', 'productImages'])
            ->where('productStatus', 'ACTIVE')
            ->where('productName', 'like', '%' . $keyword . '%')
            ->get()
            ->map(function ($product) {
                $itemIds = $product->productItems->pluck('idItem');

                $soldCount = OrderItem::whereIn('idItem', $itemIds)
                    ->whereHas('order', function ($query) {
                        $query->whereNotIn('orderStatus', ['PENDING', 'CANCELLED', 'FAILED']);
                    })->sum('quantity');

                $orderItemIds = OrderItem::whereIn('idItem', $itemIds)->pluck('idOrderItem');
                $avgRating = Review::whereIn('idOrderItem', $orderItemIds)
                    ->where('isHidden', false)
                    ->avg('rating');

                $product->setAttribute('soldCount', (int) $soldCount);
                $product->setAttribute('avgRating', $avgRating ? round($avgRating, 1) : ($product->avgRating ?? 0.0));

                return $product;
            });

        return response()->json($products);
    }
}
