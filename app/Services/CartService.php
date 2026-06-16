<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductItem;
use App\Repositories\CartRepository;
use App\Services\IdGeneratorService;
use Illuminate\Validation\ValidationException;

class CartService
{
    public function __construct(
        private CartRepository     $cartRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Ambil isi cart user, buat cart baru kalau belum ada.
     */
    public function getCart(string $userId): Cart
    {
        $cart = $this->cartRepository->findByUser($userId);

        if (!$cart) {
            $cart = $this->cartRepository->createCart($userId);
        }

        return $cart;
    }

    /**
     * Tambah item ke cart.
     * Kalau item sudah ada, update quantity-nya.
     */
    public function addItem(string $userId, string $itemId, int $quantity): Cart
    {
        // Cek stok tersedia
        $productItem = ProductItem::where('idItem', $itemId)->firstOrFail();

        if (!$productItem->isActive) {
            throw ValidationException::withMessages([
                'item' => ['Produk ini sudah tidak tersedia.'],
            ]);
        }

        if ($productItem->stock < $quantity) {
            throw ValidationException::withMessages([
                'quantity' => ["Stok tidak cukup. Stok tersedia: {$productItem->stock}."],
            ]);
        }

        $cart = $this->getCart($userId);

        // Kalau item sudah ada di cart, tambah quantity-nya
        $existingItem = $this->cartRepository->findItem($cart->idCart, $itemId);

        if ($existingItem) {
            $newQty = $existingItem->quantity + $quantity;

            if ($productItem->stock < $newQty) {
                throw ValidationException::withMessages([
                    'quantity' => ["Stok tidak cukup. Stok tersedia: {$productItem->stock}."],
                ]);
            }

            $this->cartRepository->updateQuantity($existingItem->idCartItem, $newQty);
        } else {
            $this->cartRepository->addItem($cart->idCart, $itemId, $quantity);
        }

        return $this->cartRepository->findByUser($userId);
    }

    /**
     * Update quantity item di cart.
     */
    public function updateQuantity(string $userId, string $cartItemId, int $quantity): Cart
    {
        if ($quantity <= 0) {
            return $this->removeItem($userId, $cartItemId);
        }

        $this->cartRepository->updateQuantity($cartItemId, $quantity);

        return $this->cartRepository->findByUser($userId);
    }

    /**
     * Hapus item dari cart.
     */
    public function removeItem(string $userId, string $cartItemId): Cart
    {
        $this->cartRepository->removeItem($cartItemId);

        return $this->cartRepository->findByUser($userId);
    }

    /**
     * Kosongkan cart setelah checkout.
     */
    public function clearCart(string $userId): void
    {
        $cart = $this->cartRepository->findByUser($userId);

        if ($cart) {
            $this->cartRepository->clearCart($cart->idCart);
        }
    }

    /**
     * Hitung total harga semua item di cart.
     */
    public function calculateTotal(string $userId): float
    {
        $cart = $this->cartRepository->findByUser($userId);

        if (!$cart) return 0;

        return $cart->cartItems->sum(fn($item) =>
            $item->productItem->price * $item->quantity
        );
    }
}