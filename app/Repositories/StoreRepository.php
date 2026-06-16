<?php

namespace App\Repositories;

use App\Models\Store;
use Illuminate\Pagination\LengthAwarePaginator;

class StoreRepository
{
    /**
     * Ambil detail store berdasarkan ID.
     */
    public function findById(string $id): ?Store
    {
        return Store::with(['seller', 'badgeVerifications'])
            ->where('idStore', $id)
            ->first();
    }

    /**
     * Ambil store milik seller tertentu.
     */
    public function findBySeller(string $sellerId): ?Store
    {
        return Store::where('idSeller', $sellerId)->first();
    }

    /**
     * Ambil semua store aktif (untuk halaman eksplorasi toko).
     */
    public function findAll(array $filters = []): LengthAwarePaginator
    {
        $query = Store::with(['badgeVerifications'])
            ->where('isActive', true);

        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%');
        }

        if (!empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        return $query->orderBy('storeRating', 'desc')
            ->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Buat store baru.
     */
    public function create(array $data): Store
    {
        return Store::create($data);
    }

    /**
     * Update data store.
     */
    public function update(string $id, array $data): Store
    {
        $store = Store::where('idStore', $id)->firstOrFail();
        $store->update($data);

        return $store->fresh();
    }

    /**
     * Aktifkan / nonaktifkan store.
     */
    public function setActive(string $id, bool $isActive): Store
    {
        $store = Store::where('idStore', $id)->firstOrFail();
        $store->update(['isActive' => $isActive]);

        return $store->fresh();
    }
}