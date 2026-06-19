<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductItem;
use App\Models\ProductSubcategory;
use App\Models\ProductVariation;
use App\Repositories\ProductRepository;
use App\Services\IdGeneratorService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function __construct(
        private ProductRepository  $productRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->findAll($filters);
    }

    public function getById(string $id): Product
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            abort(404, 'Produk tidak ditemukan.');
        }

        return $product;
    }

    public function getByStore(string $storeId, array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->findByStore($storeId, $filters);
    }

    public function create(string $storeId, array $data, array $images = [], array $subCategoryIds = [], array $variants = []): Product
    {
        return DB::transaction(function () use ($storeId, $data, $images, $subCategoryIds, $variants) {
            $product = $this->productRepository->create([
                'idProduct'     => $this->idGenerator->generate('PRD', Product::class, 'idProduct'),
                'idStore'       => $storeId,
                'productName'   => $data['product_name'],
                'description'   => $data['description'] ?? null,
                'weightGram'    => $data['weight_gram'],
                'productStatus' => $data['product_status'] ?? 'ACTIVE',
            ]);

            if (!empty($images)) {
                $this->saveImages($product->idProduct, $images);
            }

            if (!empty($subCategoryIds)) {
                $this->attachSubCategories($product->idProduct, $subCategoryIds);
            }

            if (!empty($variants)) {
                $this->saveVariants($product->idProduct, $variants);
            }

            return $product->load(['productImages', 'subCategories', 'productItems.productVariations']);
        });
    }

    public function update(
        string $id,
        array  $data,
        array  $newImages      = [],
        array  $subCategoryIds = [],
        array  $deleteImageIds = [],
        array  $existingVariants = [],
        array  $newVariants    = [],
    ): Product {
        return DB::transaction(function () use ($id, $data, $newImages, $subCategoryIds, $deleteImageIds, $existingVariants, $newVariants) {
            // 1. Update field dasar produk
            $updateData = [];
            if (isset($data['product_name']))   $updateData['productName']   = $data['product_name'];
            if (isset($data['description']))     $updateData['description']   = $data['description'];
            if (isset($data['weight_gram']))     $updateData['weightGram']    = $data['weight_gram'];
            if (isset($data['product_status']))  $updateData['productStatus'] = $data['product_status'];

            $product = $this->productRepository->update($id, $updateData);

            // 2. Hapus gambar yang dicentang seller
            if (!empty($deleteImageIds)) {
                $toDelete = ProductImage::where('idProduct', $product->idProduct)
                    ->whereIn('idImage', $deleteImageIds)
                    ->get();

                foreach ($toDelete as $img) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $img->imageURL)); // ← sama
                    $img->delete();
                }

                // Jika gambar utama ikut dihapus, jadikan gambar pertama yang tersisa sebagai utama
                $stillHasPrimary = ProductImage::where('idProduct', $product->idProduct)
                    ->where('isPrimary', true)
                    ->exists();

                if (!$stillHasPrimary) {
                    $first = ProductImage::where('idProduct', $product->idProduct)->first();
                    $first?->update(['isPrimary' => true]);
                }
            }

            // 3. Tambah gambar baru dengan mematuhi batas maksimal 10
            if (!empty($newImages)) {
                $existingCount = ProductImage::where('idProduct', $product->idProduct)->count();
                $sisa          = 10 - $existingCount;

                if ($sisa > 0) {
                    $this->saveImages($product->idProduct, array_slice($newImages, 0, $sisa), $existingCount === 0);
                }
            }

            // 4. Sinkronisasi subkategori
            if (!empty($subCategoryIds)) {
                ProductSubcategory::where('idProduct', $product->idProduct)->delete();
                $this->attachSubCategories($product->idProduct, $subCategoryIds);
            }

            // 5. Update varian yang sudah ada
            foreach ($existingVariants as $itemId => $variantData) {
                $item = ProductItem::where('idItem', $itemId)
                    ->where('idProduct', $product->idProduct) // pastikan milik produk ini
                    ->first();

                if (!$item) continue;

                $item->update([
                    'price'    => $variantData['price']    ?? $item->price,
                    'stock'    => $variantData['stock']    ?? $item->stock,
                    'sku'      => $variantData['sku']      ?? $item->sku,
                    'isActive' => isset($variantData['is_active']) ? true : false,
                ]);

                // Update variasi (type & value)
                $variation = $item->productVariations()->first();
                if ($variation) {
                    $variation->update([
                        'typeVariation' => $variantData['type']  ?? $variation->typeVariation,
                        'value'         => $variantData['value'] ?? $variation->value,
                    ]);
                }
            }

            // 6. Tambah varian baru
            if (!empty($newVariants)) {
                $this->saveVariants($product->idProduct, $newVariants);
            }

            return $product->load(['productImages', 'subCategories', 'productItems.productVariations']);
        });
    }

    public function delete(string $id): bool
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            abort(404, 'Produk tidak ditemukan.');
        }

        foreach ($product->productImages as $image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $image->imageURL)); // ← strip storage/ dulu
        }

        return $this->productRepository->delete($id);
    }

    private function saveImages(string $productId, array $images, bool $firstAsPrimary = true): void
    {
        foreach ($images as $index => $image) {
            if (!$image instanceof UploadedFile) continue;

            $path = $image->store("products/{$productId}", 'public');

            ProductImage::create([
                'idImage'   => $this->idGenerator->generate('IMG', ProductImage::class, 'idImage'),
                'idProduct' => $productId,
                'imageURL'  => 'storage/' . $path, // ← tambah prefix storage/
                'isPrimary' => $firstAsPrimary && $index === 0,
            ]);
        }
    }

    private function attachSubCategories(string $productId, array $subCategoryIds): void
    {
        foreach ($subCategoryIds as $subCatId) {
            ProductSubcategory::create([
                'idProductSubCat' => $this->idGenerator->generate('PSC', ProductSubcategory::class, 'idProductSubCat'),
                'idProduct'       => $productId,
                'idSubCategory'   => $subCatId,
            ]);
        }
    }

    private function saveVariants(string $productId, array $variants): void
    {
        foreach ($variants as $variantData) {
            // Harga dan stok wajib ada, skip jika tidak
            if (!isset($variantData['price']) || !isset($variantData['stock'])) continue;

            $item = ProductItem::create([
                'idItem'     => $this->idGenerator->generate('ITM', ProductItem::class, 'idItem'),
                'idProduct'  => $productId,
                'sku'        => $variantData['sku']      ?? null,
                'stock'      => $variantData['stock'],
                'price'      => $variantData['price'],
                'isActive'   => isset($variantData['is_active']) ? true : true, // default aktif saat dibuat
            ]);

            // Simpan variasi hanya jika type & value diisi
            if (!empty($variantData['type']) && !empty($variantData['value'])) {
                ProductVariation::create([
                    'idVariation'   => $this->idGenerator->generate('VAR', ProductVariation::class, 'idVariation'),
                    'idItem'        => $item->idItem,
                    'typeVariation' => $variantData['type'],
                    'value'         => $variantData['value'],
                ]);
            }
        }
    }

    public function searchProducts(array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->findAll($filters);
    }
}