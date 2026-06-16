<?php

namespace App\Services;

use App\Models\Product;
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

    /**
     * Ambil daftar produk dengan filter.
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->productRepository->findAll($filters);
    }

    /**
     * Ambil detail produk.
     */
    public function getById(string $id): Product
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            abort(404, 'Produk tidak ditemukan.');
        }

        return $product;
    }

    /**
     * Ambil produk milik store tertentu.
     */
    public function getByStore(string $storeId): LengthAwarePaginator
    {
        return $this->productRepository->findByStore($storeId);
    }

    /**
     * Buat produk baru beserta gambar dan subkategori.
     */
    public function create(string $storeId, array $data, array $images = [], array $subCategoryIds = []): Product
    {
        return DB::transaction(function () use ($storeId, $data, $images, $subCategoryIds) {
            // 1. Buat produk
            $product = $this->productRepository->create([
                'idProduct'     => $this->idGenerator->generate('PRD', Product::class, 'idProduct'),
                'idStore'       => $storeId,
                'productName'   => $data['product_name'],
                'description'   => $data['description'] ?? null,
                'weightGram'    => $data['weight_gram'],
                'productStatus' => $data['product_status'] ?? 'ACTIVE',
            ]);

            // 2. Upload & simpan gambar
            if (!empty($images)) {
                $this->saveImages($product->idProduct, $images);
            }

            // 3. Attach subkategori
            if (!empty($subCategoryIds)) {
                $product->subCategories()->attach($subCategoryIds);
            }

            return $product->load(['productImages', 'subCategories']);
        });
    }

    /**
     * Update produk.
     */
    public function update(string $id, array $data, array $newImages = [], array $subCategoryIds = []): Product
    {
        return DB::transaction(function () use ($id, $data, $newImages, $subCategoryIds) {
            $updateData = array_filter([
                'productName'   => $data['product_name'] ?? null,
                'description'   => $data['description'] ?? null,
                'weightGram'    => $data['weight_gram'] ?? null,
                'productStatus' => $data['product_status'] ?? null,
            ]);

            $product = $this->productRepository->update($id, $updateData);

            if (!empty($newImages)) {
                $this->saveImages($product->idProduct, $newImages);
            }

            if (!empty($subCategoryIds)) {
                $product->subCategories()->sync($subCategoryIds);
            }

            return $product->load(['productImages', 'subCategories']);
        });
    }

    /**
     * Hapus produk.
     */
    public function delete(string $id): bool
    {
        // Hapus gambar dari storage dulu
        $product = $this->productRepository->findById($id);

        if ($product) {
            foreach ($product->productImages as $image) {
                Storage::disk('public')->delete($image->imageURL);
            }
        }

        return $this->productRepository->delete($id);
    }

    /**
     * Upload dan simpan gambar produk.
     * Maks 10 gambar per produk.
     */
    private function saveImages(string $productId, array $images): void
    {
        foreach ($images as $index => $image) {
            if (!$image instanceof UploadedFile) continue;

            $path = $image->store("products/{$productId}", 'public');

            \App\Models\ProductImage::create([
                'idProduct' => $productId,
                'imageURL'  => $path,
                'isPrimary' => $index === 0, // gambar pertama jadi primary
            ]);
        }
    }
}