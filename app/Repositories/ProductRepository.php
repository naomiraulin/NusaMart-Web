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
            ->whereHas('store', fn($q) => $q->where('isActive', true))
            ->where('productStatus', 'ACTIVE'); // Konsisten: listing publik hanya tampil produk aktif

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

        // Filter status hanya berlaku untuk konteks non-publik (misal dashboard admin/seller)
        // Untuk findAll publik, status sudah dikunci ACTIVE di atas.

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
     * Semua status ditampilkan (ACTIVE & INACTIVE) agar seller bisa kelola.
     */
    public function findByStore(string $storeId, array $filters = []): LengthAwarePaginator
    {
        $query = Product::with(['productImages', 'productItems'])
            ->where('idStore', $storeId);

        // Seller bisa filter berdasarkan status di dashboard-nya sendiri
        if (!empty($filters['status'])) {
            $query->where('productStatus', $filters['status']);
        }

        if (!empty($filters['search'])) {
            $query->where('productName', 'like', '%' . $filters['search'] . '%');
        }

        $query->orderBy('createAt', 'desc');

        return $query->paginate($filters['per_page'] ?? 10);
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
     * Pastikan produk benar-benar milik store yang diberikan sebelum memanggil ini.
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
        return (bool) Product::where('idProduct', $id)->delete();
    }
}