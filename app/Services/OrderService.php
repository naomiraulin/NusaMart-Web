<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Services\IdGeneratorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function __construct(
        private OrderRepository    $orderRepository,
        private CartRepository     $cartRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Ambil riwayat order buyer.
     */
    public function getByUser(string $userId)
    {
        return $this->orderRepository->findByUser($userId);
    }

    /**
     * Ambil order masuk untuk seller.
     */
    public function getByStore(string $storeId)
    {
        return $this->orderRepository->findByStore($storeId);
    }

    /**
     * Ambil detail order.
     */
    public function getById(string $id): Order
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            abort(404, 'Order tidak ditemukan.');
        }

        return $order;
    }

    /**
     * Proses checkout dari cart.
     * Satu checkout = satu order per store.
     */
    public function checkout(string $userId, array $data): Order
    {
        return DB::transaction(function () use ($userId, $data) {
            $cart = $this->cartRepository->findByUser($userId);

            if (!$cart || $cart->cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Keranjang kamu kosong.'],
                ]);
            }

            // Validasi stok semua item sebelum buat order
            foreach ($cart->cartItems as $cartItem) {
                $productItem = $cartItem->productItem;

                if ($productItem->stock < $cartItem->quantity) {
                    throw ValidationException::withMessages([
                        'stock' => [
                            "Stok {$productItem->product->productName} tidak mencukupi. " .
                            "Stok tersedia: {$productItem->stock}."
                        ],
                    ]);
                }
            }

            // Hitung total harga produk
            $productTotal = $cart->cartItems->sum(
                fn($item) => $item->productItem->price * $item->quantity
            );

            $shippingCost = $data['shipping_cost'] ?? 0;
            $servicePrice = $data['service_price'] ?? 0;
            $grandTotal   = $productTotal + $shippingCost + $servicePrice;

            // Buat order
            $order = $this->orderRepository->create([
                'idOrder'           => $this->idGenerator->generate('ORD', Order::class, 'idOrder'),
                'idUser'            => $userId,
                'idStore'           => $data['id_store'],
                'idAddress'         => $data['id_address'],
                'productTotalPrice' => $productTotal,
                'shippingCost'      => $shippingCost,
                'servicePrice'      => $servicePrice,
                'grandTotal'        => $grandTotal,
                'orderStatus'       => 'PENDING',
                'invoiceNumber'     => $this->generateInvoice(),
                'orderDate'         => now(),
                'buyerNote'         => $data['buyer_note'] ?? null,
            ]);

            // Buat order items + kurangi stok
            foreach ($cart->cartItems as $cartItem) {
                \App\Models\OrderItem::create([
                    'idOrderItem'   => $this->idGenerator->generate('OIT', OrderItem::class, 'idOrderItem'),
                    'idOrder'       => $order->idOrder,
                    'idItem'        => $cartItem->idItem,
                    'nameSnapshot'  => $cartItem->productItem->product->productName,
                    'priceSnapshot' => $cartItem->productItem->price,
                    'quantity'      => $cartItem->quantity,
                ]);

                // Kurangi stok
                $cartItem->productItem->decrement('stock', $cartItem->quantity);
            }

            // Kosongkan cart setelah checkout
            $this->cartRepository->clearCart($cart->idCart);

            return $this->orderRepository->findById($order->idOrder);
        });
    }

    /**
     * Update status order.
     * Seller bisa update ke PROCESSED / SHIPPED.
     * Buyer bisa update ke CANCELLED (jika masih PENDING).
     */
    public function updateStatus(string $orderId, string $status, string $actorRole): Order
    {
        $order = $this->getById($orderId);

        $allowedTransitions = [
            'SELLER' => ['PROCESSED', 'SHIPPED', 'CANCELLED'],
            'BUYER'  => ['CANCELLED', 'DELIVERED'],
            'ADMIN'  => ['CANCELLED', 'DELIVERED'],
        ];

        if (!in_array($status, $allowedTransitions[$actorRole] ?? [])) {
            throw ValidationException::withMessages([
                'status' => ['Perubahan status tidak diizinkan.'],
            ]);
        }

        if ($status === 'CANCELLED' && !in_array($order->orderStatus, ['PENDING', 'PROCESSED'])) {
            throw ValidationException::withMessages([
                'status' => ['Order tidak bisa dibatalkan pada status ini.'],
            ]);
        }

        return $this->orderRepository->updateStatus($orderId, $status);
    }

    /**
     * Generate nomor invoice unik.
     */
    private function generateInvoice(): string
    {
        $date = now()->format('Ymd');
        $rand = strtoupper(substr(uniqid(), -5));

        return "INV/{$date}/{$rand}";
    }
}