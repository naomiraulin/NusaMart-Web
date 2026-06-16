<?php

namespace App\Repositories;

use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;

class ReviewRepository
{
    /**
     * Ambil semua review untuk satu produk.
     */
    public function findByProduct(string $productId): LengthAwarePaginator
    {
        return Review::with(['user', 'reviewImages'])
            ->whereHas('orderItem.productItem.product', fn($q) =>
                $q->where('idProduct', $productId)
            )
            ->where('isHidden', false)
            ->orderBy('createAt', 'desc')
            ->paginate(10);
    }

    /**
     * Ambil semua review yang ditulis user.
     */
    public function findByUser(string $userId): LengthAwarePaginator
    {
        return Review::with(['reviewImages', 'orderItem.productItem.product'])
            ->where('idUser', $userId)
            ->orderBy('createAt', 'desc')
            ->paginate(10);
    }

    /**
     * Cek apakah user sudah review order item ini.
     */
    public function findByOrderItem(string $orderItemId, string $userId): ?Review
    {
        return Review::where('idOrderItem', $orderItemId)
            ->where('idUser', $userId)
            ->first();
    }

    /**
     * Buat review baru.
     */
    public function create(array $data): Review
    {
        return Review::create($data);
    }

    /**
     * Sembunyikan review (oleh admin).
     */
    public function hide(string $id): Review
    {
        $review = Review::where('idReview', $id)->firstOrFail();
        $review->update(['isHidden' => true]);

        return $review->fresh();
    }
}