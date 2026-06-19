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
        // Logika dipindah dari View ke Controller
        $orderItem = \App\Models\OrderItem::with('productItem.product.productImages')->findOrFail($orderItemId);
        
        $productName = $orderItem->nameSnapshot ?? 'Produk Tidak Diketahui';
        $imageURL = null;
        
        if ($orderItem && $orderItem->productItem && $orderItem->productItem->product) {
            $imageURL = $orderItem->productItem->product->productImages->first()->imageURL ?? null;
        }

        // Passing data ke view
        return view('buyer.review', compact('orderItemId', 'productName', 'imageURL'));
    }

    /**
     * Simpan ulasan baru.
     */
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Simpan review
        $review = $this->reviewService->create(
            $user->idUser,
            $request->input('order_item_id'),
            $request->validated(),
            $request->file('images', []),
        );

        // Arahkan kembali ke halaman detail pesanan
        $orderId = $review->orderItem->idOrder;
        
        return redirect()->route('buyer.orders.show', $orderId)
            ->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }
}