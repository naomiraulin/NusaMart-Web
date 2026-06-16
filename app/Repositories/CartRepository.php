<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Collection;

class CartRepository
{
    /**
     * Ambil cart milik user beserta item-itemnya.
     */
    public function findByUser(string $userId): ?Cart
    {
        return Cart::with([
            'cartItems.productItem.product.productImages',
            'cartItems.productItem.productVariations',
        ])->where('idUser', $userId)->first();
    }

    /**
     * Cek apakah item tertentu sudah ada di cart.
     */
    public function findItem(string $cartId, string $itemId): ?CartItem
    {
        return CartItem::where('idCart', $cartId)
            ->where('idItem', $itemId)
            ->first();
    }

    /**
     * Buat cart baru untuk user.
     */
    public function createCart(string $userId): Cart
    {
        return Cart::create(['idUser' => $userId]);
    }

    /**
     * Tambah item ke cart.
     */
    public function addItem(string $cartId, string $itemId, int $quantity): CartItem
    {
        return CartItem::create([
            'idCart'    => $cartId,
            'idItem'    => $itemId,
            'quantity'  => $quantity,
        ]);
    }

    /**
     * Update quantity item di cart.
     */
    public function updateQuantity(string $cartItemId, int $quantity): CartItem
    {
        $cartItem = CartItem::where('idCartItem', $cartItemId)->firstOrFail();
        $cartItem->update(['quantity' => $quantity]);

        return $cartItem->fresh();
    }

    /**
     * Hapus satu item dari cart.
     */
    public function removeItem(string $cartItemId): bool
    {
        return CartItem::where('idCartItem', $cartItemId)->delete();
    }

    /**
     * Kosongkan semua item di cart (dipanggil setelah checkout).
     */
    public function clearCart(string $cartId): bool
    {
        return CartItem::where('idCart', $cartId)->delete();
    }
}