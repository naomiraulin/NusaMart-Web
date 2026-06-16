<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Menampilkan Homepage (Bisa diakses tanpa login)
    public function index()
    {
        // Ambil produk dengan Eager Loading, limit 20 produk untuk homepage
        $products = Product::with(['productItems', 'productImages'])
                           ->where('productStatus', 'ACTIVE')
                           ->latest('createAt')
                           ->take(20)
                           ->get();
                           
        // Arahkan ke resources/views/welcome.blade.php sambil membawa data $products
        return view('welcome', compact('products'));
    }

    // Menampilkan Detail Produk (Bisa diakses tanpa login)
    public function show(string $id)
    {
        $product = Product::with(['productImages', 'subCategories'])->where('idProduct', $id)->firstOrFail();
        
        $items = $product->productItems()->with('productVariations')->where('isActive', true)->get();

        $itemsWithVariations = $items->map(function ($item) {
            $item->variations = $item->productVariations;
            unset($item->productVariations);
            return $item;
        });

        // Arahkan ke resources/views/product/detail.blade.php
        return view('product.detail', [
            'product' => $product,
            'items' => $itemsWithVariations
        ]);
    }
}