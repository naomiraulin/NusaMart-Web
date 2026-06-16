<?php

namespace App\Services;

use App\Models\Store;
use App\Repositories\StoreRepository;
use App\Services\IdGeneratorService;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class StoreService
{
    public function __construct(
        private StoreRepository    $storeRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Ambil semua store aktif.
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return $this->storeRepository->findAll($filters);
    }

    /**
     * Ambil detail store.
     */
    public function getById(string $id): Store
    {
        $store = $this->storeRepository->findById($id);

        if (!$store) {
            abort(404, 'Toko tidak ditemukan.');
        }

        return $store;
    }

    /**
     * Ambil store milik seller yang sedang login.
     */
    public function getBySeller(string $sellerId): ?Store
    {
        return $this->storeRepository->findBySeller($sellerId);
    }

    /**
     * Update profil store.
     */
    public function update(string $storeId, array $data, ?UploadedFile $logo = null): Store
    {
        $updateData = array_filter([
            'name'        => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'location'    => $data['location'] ?? null,
            'urlLocation' => $data['url_location'] ?? null,
        ]);

        // Upload logo baru kalau ada
        if ($logo) {
            $store = $this->storeRepository->findById($storeId);

            // Hapus logo lama
            if ($store->logoURL) {
                Storage::disk('public')->delete($store->logoURL);
            }

            $updateData['logoURL'] = $logo->store("stores/{$storeId}", 'public');
        }

        return $this->storeRepository->update($storeId, $updateData);
    }

    /**
     * Nonaktifkan store (oleh admin).
     */
    public function deactivate(string $storeId): Store
    {
        return $this->storeRepository->setActive($storeId, false);
    }

    /**
     * Aktifkan store kembali (oleh admin).
     */
    public function activate(string $storeId): Store
    {
        return $this->storeRepository->setActive($storeId, true);
    }

    /**
     * Update rating rata-rata store setelah ada review baru.
     * Dipanggil dari ReviewService.
     */
    public function recalculateRating(string $storeId): void
    {
        $avgRating = \App\Models\Review::whereHas(
            'orderItem.order',
            fn($q) => $q->where('idStore', $storeId)
        )->avg('rating');

        $this->storeRepository->update($storeId, [
            'storeRating' => round($avgRating, 2),
        ]);
    }
}