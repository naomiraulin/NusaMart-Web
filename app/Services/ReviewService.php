<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewImage;
use App\Repositories\ProductRepository;
use App\Repositories\ReviewRepository;
use App\Services\IdGeneratorService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReviewService
{
    public function __construct(
        private ReviewRepository   $reviewRepository,
        private ProductRepository  $productRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Ambil semua review untuk satu produk.
     */
    public function getByProduct(string $productId): LengthAwarePaginator
    {
        return $this->reviewRepository->findByProduct($productId);
    }

    /**
     * Ambil review yang ditulis user.
     */
    public function getByUser(string $userId): LengthAwarePaginator
    {
        return $this->reviewRepository->findByUser($userId);
    }

    /**
     * Buat review baru.
     * User hanya bisa review orderItem yang sudah DELIVERED dan belum pernah direview.
     */
    public function create(string $userId, string $orderItemId, array $data, array $images = []): Review
    {
        // Cek sudah pernah review atau belum
        $existing = $this->reviewRepository->findByOrderItem($orderItemId, $userId);

        if ($existing) {
            throw ValidationException::withMessages([
                'review' => ['Kamu sudah memberikan ulasan untuk produk ini.'],
            ]);
        }

        // Cek order item milik user & sudah delivered
        $orderItem = \App\Models\OrderItem::with('order')
            ->where('idOrderItem', $orderItemId)
            ->whereHas('order', fn($q) =>
                $q->where('idUser', $userId)->where('orderStatus', 'DELIVERED')
            )->first();

        if (!$orderItem) {
            throw ValidationException::withMessages([
                'order_item' => ['Kamu hanya bisa mengulas produk yang sudah diterima.'],
            ]);
        }

        return DB::transaction(function () use ($userId, $orderItemId, $data, $images, $orderItem) {
            // Buat review
            $review = $this->reviewRepository->create([
                'idReview'    => $this->idGenerator->generate('REV', Review::class, 'idReview'),
                'idOrderItem' => $orderItemId,
                'idUser'      => $userId,
                'rating'      => $data['rating'],
                'comment'     => $data['comment'] ?? null,
                'isHidden'    => false,
            ]);

            // Upload gambar review jika ada
            if (!empty($images)) {
                foreach ($images as $image) {
                    $path = $image->store("reviews/{$review->idReview}", 'public');
                    ReviewImage::create([
                        'idReview' => $review->idReview,
                        'urlImage' => $path,
                    ]);
                }
            }

            // Recalculate avgRating produk
            $this->recalculateProductRating($orderItem->productItem->idProduct);

            return $review->load('reviewImages');
        });
    }

    /**
     * Sembunyikan review (oleh admin).
     */
    public function hide(string $reviewId): Review
    {
        return $this->reviewRepository->hide($reviewId);
    }

    /**
     * Hitung ulang rata-rata rating produk setelah ada review baru.
     */
    private function recalculateProductRating(string $productId): void
    {
        $avgRating = Review::whereHas(
            'orderItem.productItem',
            fn($q) => $q->where('idProduct', $productId)
        )->where('isHidden', false)->avg('rating');

        $this->productRepository->update($productId, [
            'avgRating' => round($avgRating, 2),
        ]);
    }
}