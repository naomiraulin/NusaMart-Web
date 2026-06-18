<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Services\IdGeneratorService;
use Illuminate\Database\Eloquent\Collection;

class CartRepository
{
    public function __construct(
        private IdGeneratorService $idGenerator
    ) {}

    /**
     * Ambil cart milik user beserta item-itemnya.
     */
    public function findByUser(string $userId): ?Cart
    {
        return Cart::with([
            // Ditambahkan 'product.store' supaya $item->productItem->product->store
            // tidak memicu N+1 query saat dipakai untuk grouping per toko di view cart.
            'cartItems.productItem.product.productImages',
            'cartItems.productItem.product.store',
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
        return Cart::create([
            // TODO: ganti prefix 'CRT' kalau project kamu sudah punya konvensi
            // prefix tersendiri untuk cart.
            'idCart' => $this->idGenerator->generate('CRT', Cart::class, 'idCart'),
            'idUser' => $userId,
        ]);
    }

    /**
     * Tambah item ke cart.
     */
    public function addItem(string $cartId, string $itemId, int $quantity): CartItem
    {
        return CartItem::create([
            // Tanpa ini, insert akan gagal dengan error
            // "Field 'idCartItem' doesn't have a default value" -
            // sama seperti kasus 'idChat' sebelumnya, karena kolom
            // ini primary key string custom, bukan auto-increment.
            // TODO: ganti prefix 'CTI' kalau project kamu sudah punya
            // konvensi prefix tersendiri.
            'idCartItem' => $this->idGenerator->generate('CTI', CartItem::class, 'idCartItem'),
            'idCart'     => $cartId,
            'idItem'     => $itemId,
            'quantity'   => $quantity,
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