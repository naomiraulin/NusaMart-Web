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
                foreach ($subCategoryIds as $subCatId) {
                    \App\Models\ProductSubcategory::create([
                        'idProductSubCat' => $this->idGenerator->generate('PSC', \App\Models\ProductSubcategory::class, 'idProductSubCat'),
                        'idProduct'       => $product->idProduct,
                        'idSubCategory'   => $subCatId,
                    ]);
                }
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
            // Hapus yang lama dulu
            \App\Models\ProductSubcategory::where('idProduct', $product->idProduct)->delete();
            
            // Insert yang baru
            foreach ($subCategoryIds as $subCatId) {
                \App\Models\ProductSubcategory::create([
                    'idProductSubCat' => $this->idGenerator->generate('PSC', \App\Models\ProductSubcategory::class, 'idProductSubCat'),
                    'idProduct'       => $product->idProduct,
                    'idSubCategory'   => $subCatId,
                ]);
            }
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
                'idImage'   => $this->idGenerator->generate('IMG', \App\Models\ProductImage::class, 'idImage'),
                'idProduct' => $productId,
                'imageURL'  => $path,
                'isPrimary' => $index === 0,
            ]);
        }
    }

    /**
     * Mencari produk berdasarkan kata kunci dan filter (sort, min_price, max_price).
     * Diperuntukkan untuk halaman pencarian publik.
     */
    public function searchProducts(array $filters): LengthAwarePaginator
    {
        // 1. Inisialisasi query dengan Eager Loading untuk mencegah N+1 query problem
        $query = Product::with(['productImages', 'productItems', 'store']);

        // Pastikan hanya mencari produk yang statusnya ACTIVE
        // Catatan: Jika saat testing data tidak muncul, pastikan isi kolom productStatus
        // di database kamu benar-benar huruf kapital 'ACTIVE'. Jika tidak, komentari baris ini sementara.
        $query->where('productStatus', 'ACTIVE'); 

        // 2. Filter Kata Kunci Pencarian
        if (!empty($filters['search'])) {
            $query->where('productName', 'like', '%' . $filters['search'] . '%');
        }

        // 3. Filter Harga Minimum
        // Menggunakan whereHas untuk mencari harga di dalam tabel relasi productItems
        if (isset($filters['min_price']) && $filters['min_price'] !== '') {
            $query->whereHas('productItems', function ($q) use ($filters) {
                $q->where('price', '>=', $filters['min_price']);
            });
        }

        // 4. Filter Harga Maksimum
        if (isset($filters['max_price']) && $filters['max_price'] !== '') {
            $query->whereHas('productItems', function ($q) use ($filters) {
                $q->where('price', '<=', $filters['max_price']);
            });
        }

        // 5. Sorting (Pengurutan)
        $sort = $filters['sort'] ?? 'semua';
        
        if ($sort === 'termurah') {
            $query->withMin('productItems', 'price')->orderBy('product_items_min_price', 'asc');
        } elseif ($sort === 'termahal') {
            $query->withMax('productItems', 'price')->orderBy('product_items_max_price', 'desc');
        } else {
            // FIX: Ganti 'created_at' menjadi kolom yang pasti ada di tabel products kamu.
            // Misalnya kita urutkan berdasarkan produk terbaru dari ID-nya:
            $query->orderBy('idProduct', 'desc'); 
        }
        
        return $query->paginate(12);
        
    }
}