<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductItem;
use App\Models\ProductImage;
use App\Models\ProductSubCategory;
use App\Models\ProductVariation;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class SellerProductController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // POST /api/seller/products → tambah produk baru
    public function store(Request $request)
    {
        $request->validate([
            'productName'    => 'required|string',
            'description'    => 'sometimes|string',
            'weightGram'     => 'required|integer',
            'subCategoryIds' => 'sometimes|array',
            'basePrice'      => 'required|numeric',
            'baseStock'      => 'required|integer',
        ]);

        $store = \App\Models\Store::where('idSeller', $request->user()->idUser)->firstOrFail();

        // Buat produk
        $product = Product::create([
            'idProduct'     => $this->idGenerator->generate('PRD', Product::class, 'idProduct'),
            'idStore'       => $store->idStore,
            'productName'   => $request->productName,
            'description'   => $request->description,
            'weightGram'    => $request->weightGram,
            'productStatus' => 'ACTIVE',
            'avgRating'     => null,
            'sold'          => 0,
            'createAt'      => now(),
            'updateAt'      => now(),
        ]);

        // Simpan relasi subkategori
        if ($request->subCategoryIds) {
            foreach ($request->subCategoryIds as $subCatId) {
                ProductSubCategory::create([
                    'idProductSubCat' => $this->idGenerator->generate('PSC', ProductSubCategory::class, 'idProductSubCat'),
                    'idProduct'       => $product->idProduct,
                    'idSubCategory'   => $subCatId,
                ]);
            }
        }

        // Simpan base item
        $item = ProductItem::create([
            'idItem'    => $this->idGenerator->generate('ITM', ProductItem::class, 'idItem'),
            'idProduct' => $product->idProduct,
            'sku'       => 'SKU-' . $product->idProduct,
            'stock'     => $request->baseStock,
            'price'     => $request->basePrice,
            'isActive'  => true,
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'product' => $product,
            'item'    => $item,
        ], 201);
    }

    // PUT /api/seller/products/{id} → update produk
    public function update(Request $request, string $id)
    {
        $store = \App\Models\Store::where('idSeller', $request->user()->idUser)->firstOrFail();

        $product = Product::where('idProduct', $id)
            ->where('idStore', $store->idStore)
            ->firstOrFail();

        $product->update([
            'productName'   => $request->productName   ?? $product->productName,
            'description'   => $request->description   ?? $product->description,
            'weightGram'    => $request->weightGram     ?? $product->weightGram,
            'productStatus' => $request->productStatus  ?? $product->productStatus,
            'updateAt'      => now(),
        ]);

        return response()->json([
            'message' => 'Produk berhasil diupdate',
            'product' => $product,
        ]);
    }

    // DELETE /api/seller/products/{id} → hapus produk
    public function destroy(Request $request, string $id)
    {
        $store = \App\Models\Store::where('idSeller', $request->user()->idUser)->firstOrFail();

        $product = Product::where('idProduct', $id)
            ->where('idStore', $store->idStore)
            ->firstOrFail();

        $product->update(['productStatus' => 'INACTIVE', 'updateAt' => now()]);

        return response()->json(['message' => 'Produk berhasil dinonaktifkan']);
    }

    // POST /api/seller/products/{id}/variations → tambah variasi
    public function addVariation(Request $request, string $id)
    {
        $request->validate([
            'idItem'        => 'required|string',
            'typeVariation' => 'required|string',
            'value'         => 'required|string',
        ]);

        $variation = ProductVariation::create([
            'idVariation'   => $this->idGenerator->generate('VAR', ProductVariation::class, 'idVariation'),
            'idItem'        => $request->idItem,
            'typeVariation' => $request->typeVariation,
            'value'         => $request->value,
        ]);

        return response()->json([
            'message'   => 'Variasi berhasil ditambahkan',
            'variation' => $variation,
        ], 201);
    }
}