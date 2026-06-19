<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Http\Requests\Cart\UpdateCartItemRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private CartService $cartService,
    ) {}

    /**
     * Halaman keranjang belanja.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $cart  = $this->cartService->getCart($user->idUser);
        $total = $this->cartService->calculateTotal($user->idUser);

        return view('buyer.cart', compact('cart', 'total'));
    }

    /**
     * Tambah item ke keranjang.
     */
    public function add(AddCartItemRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->cartService->addItem(
            $user->idUser,
            $request->input('item_id'),
            $request->input('quantity'),
        );

        return redirect()->route('buyer.cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update quantity item di keranjang.
     */
    public function update(UpdateCartItemRequest $request, string $cartItemId): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->cartService->updateQuantity(
            $user->idUser,
            $cartItemId,
            $request->input('quantity'),
        );

        return back()->with('success', 'Keranjang berhasil diperbarui.');
    }

    /**
     * Hapus item dari keranjang.
     */
    public function remove(string $cartItemId): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $this->cartService->removeItem($user->idUser, $cartItemId);

        return back()->with('success', 'Item berhasil dihapus dari keranjang.');
    }
}