<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\StoreReviewRequest;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService,
    ) {}

    /**
     * Form tulis ulasan untuk order item tertentu.
     */
    public function create(string $orderItemId): View
    {
        return view('buyer.reviews.create', compact('orderItemId'));
    }

    /**
     * Simpan ulasan baru.
     */
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->reviewService->create(
            $user->idUser,
            $request->input('order_item_id'),
            $request->validated(),
            $request->file('images', []),
        );

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }
}