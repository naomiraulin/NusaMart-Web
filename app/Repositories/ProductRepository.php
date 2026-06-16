<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    /**
     * Ambil semua produk dengan filter opsional.
     * Dipakai di halaman listing produk (guest & buyer bisa akses).
     */
    public function findAll(array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['store', 'productImages', 'subCategories'])
            ->whereHas('store', fn($q) => $q->where('isActive', true));

        if (!empty($filters['search'])) {
            $query->where('productName', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['category'])) {
            $query->whereHas('subCategories.category', fn($q) =>
                $q->where('idCategory', $filters['category'])
            );
        }

        if (!empty($filters['subCategory'])) {
            $query->whereHas('subCategories', fn($q) =>
                $q->where('idSubCategory', $filters['subCategory'])
            );
        }

        if (!empty($filters['status'])) {
            $query->where('productStatus', $filters['status']);
        }

        $sortBy  = $filters['sort_by']  ?? 'createAt';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Ambil detail satu produk lengkap dengan semua relasinya.
     */
    public function findById(string $id): ?Product
    {
        return Product::with([
            'store',
            'productImages',
            'subCategories.category',
            'productItems.productVariations',
        ])->where('idProduct', $id)->first();
    }

    /**
     * Ambil semua produk milik satu store (untuk dashboard seller).
     */
    public function findByStore(string $storeId): LengthAwarePaginator
    {
        return Product::with(['productImages', 'productItems'])
            ->where('idStore', $storeId)
            ->orderBy('createAt', 'desc')
            ->paginate(10);
    }

    /**
     * Buat produk baru.
     */
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Update produk berdasarkan ID.
     */
    public function update(string $id, array $data): Product
    {
        $product = Product::where('idProduct', $id)->firstOrFail();
        $product->update($data);

        return $product->fresh();
    }

    /**
     * Hapus produk berdasarkan ID.
     */
    public function delete(string $id): bool
    {
        return Product::where('idProduct', $id)->delete();
    }
}