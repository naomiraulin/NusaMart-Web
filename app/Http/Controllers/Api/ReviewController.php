<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\OrderItem;
use App\Services\IdGeneratorService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    // GET /api/reviews/product/{productId} → ulasan by produk
    public function byProduct(string $productId)
    {
        $orderItemIds = OrderItem::whereHas('productItem', function ($q) use ($productId) {
            $q->where('idProduct', $productId);
        })->pluck('idOrderItem');

        $reviews = Review::whereIn('idOrderItem', $orderItemIds)
            ->where('isHidden', false)
            ->with('reviewImages')
            ->orderByDesc('createAt')
            ->get();

        return response()->json($reviews);
    }

    // POST /api/reviews/items → ulasan by list idOrderItem
    public function byItems(Request $request)
    {
        $request->validate([
            'itemIds'   => 'required|array',
            'itemIds.*' => 'string',
        ]);

        $reviews = Review::whereIn('idOrderItem', $request->itemIds)
            ->where('isHidden', false)
            ->with('reviewImages')
            ->get();

        return response()->json($reviews);
    }

    // POST /api/reviews → buat ulasan baru
    public function store(Request $request)
    {
        $request->validate([
            'idOrderItem' => 'required|string',
            'rating'      => 'required|numeric|min:1|max:5',
            'comment'     => 'sometimes|nullable|string',
            'imageFile'   => 'sometimes|nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek apakah order item milik user yang login
        $orderItem = OrderItem::where('idOrderItem', $request->idOrderItem)
            ->whereHas('order', fn($q) => $q->where('idUser', $request->user()->idUser))
            ->firstOrFail();

        // Cek apakah sudah pernah review
        $existing = Review::where('idOrderItem', $request->idOrderItem)
            ->where('idUser', $request->user()->idUser)
            ->first();

        if ($existing) {
            return response()->json([
                'message' => 'Kamu sudah memberikan ulasan untuk item ini.'
            ], 422);
        }

        $review = Review::create([
            'idReview'    => $this->idGenerator->generate('REV', Review::class, 'idReview'),
            'idOrderItem' => $request->idOrderItem,
            'idUser'      => $request->user()->idUser,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'isHidden'    => false,
            'createAt'    => now(),
        ]);

        // Upload foto ulasan kalau ada
        if ($request->hasFile('imageFile')) {
            $path = $request->file('imageFile')
                ->store('reviews/' . $review->idReview, 'public');

            ReviewImage::create([
                'idRevImage' => $this->idGenerator->generate('RVI', ReviewImage::class, 'idRevImage'),
                'idReview'   => $review->idReview,
                'urlImage'   => asset('storage/' . $path),
            ]);
        }

        return response()->json([
            'message' => 'Ulasan berhasil ditambahkan',
            'review'  => $review->load('reviewImages'),
        ], 201);
    }

    // PUT /api/admin/reviews/{id}/hide → sembunyikan ulasan (Admin)
    public function hide(string $id)
    {
        $review = Review::where('idReview', $id)->firstOrFail();
        $review->update(['isHidden' => true]);

        return response()->json(['message' => 'Ulasan berhasil disembunyikan']);
    }
}