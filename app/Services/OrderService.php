<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;
use App\Repositories\CartRepository;
use App\Repositories\OrderRepository;
use App\Services\IdGeneratorService;
use Illuminate\Support\Collection;
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
    public function getByStore(string $storeId, ?string $status = null)
    {
        return $this->orderRepository->findByStore($storeId, $status);
    }

    /**
     * Ambil rekap jumlah order per status untuk satu store.
     */
    public function getStatusCounts(string $storeId): array
    {
        return $this->orderRepository->countByStatusForStore($storeId);
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
     * Proses checkout multi-store dari item yang dipilih di cart.
     *
     * - Satu checkout bisa menghasilkan lebih dari satu order (satu per toko).
     * - Hanya item dengan idCartItem yang ada di $cartItemIds yang diproses.
     * - Service fee dibagi rata ke setiap order berdasarkan jumlah toko.
     *
     * @param  string   $userId
     * @param  array    $cartItemIds   — idCartItem[] yang dipilih buyer
     * @param  array    $storesData    — keyed by idStore: ['id_store', 'id_courier', 'shipping_cost', 'buyer_note']
     * @param  string   $idAddress
     * @param  float    $servicePrice  — total biaya layanan (dibagi per order)
     * @return array    ['orders' => Collection<Order>, 'grand_total' => float]
     */
    public function checkoutMultiStore(
        string $userId,
        array  $cartItemIds,
        array  $storesData,
        string $idAddress,
        float  $servicePrice = 0,
    ): array {
        return DB::transaction(function () use ($userId, $cartItemIds, $storesData, $idAddress, $servicePrice) {

            $cart = $this->cartRepository->findByUser($userId);

            if (!$cart || $cart->cartItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => ['Keranjang kamu kosong.'],
                ]);
            }

            // Filter hanya item yang dipilih
            $selectedItems = $cart->cartItems->whereIn('idCartItem', $cartItemIds)->values();

            if ($selectedItems->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart_item_ids' => ['Item yang dipilih tidak ditemukan.'],
                ]);
            }

            // Validasi stok semua item yang dipilih sebelum membuat order
            foreach ($selectedItems as $cartItem) {
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

            // Kelompokkan item yang dipilih berdasarkan toko
            $groupedByStore = $selectedItems->groupBy(
                fn($item) => $item->productItem->product->idStore
            );

            $storeCount         = $groupedByStore->count();
            $serviceFeePerStore = $storeCount > 0 ? round($servicePrice / $storeCount) : 0;

            $createdOrders = collect();
            $grandTotal    = 0;

            foreach ($groupedByStore as $idStore => $items) {
                $storeData = $storesData[$idStore] ?? null;

                if (!$storeData) {
                    throw ValidationException::withMessages([
                        'stores' => ["Data pengiriman untuk toko {$idStore} tidak lengkap."],
                    ]);
                }

                $productTotal    = $items->sum(
                    fn($item) => $item->productItem->price * $item->quantity
                );
                $shippingCost    = (float) ($storeData['shipping_cost'] ?? 0);
                $storeGrandTotal = $productTotal + $shippingCost + $serviceFeePerStore;

                // Buat order untuk toko ini
                $order = $this->orderRepository->create([
                    'idOrder'           => $this->idGenerator->generate('ORD', Order::class, 'idOrder'),
                    'idUser'            => $userId,
                    'idStore'           => $idStore,
                    'idAddress'         => $idAddress,
                    'productTotalPrice' => $productTotal,
                    'shippingCost'      => $shippingCost,
                    'servicePrice'      => $serviceFeePerStore,
                    'grandTotal'        => $storeGrandTotal,
                    'orderStatus'       => 'PENDING',
                    'invoiceNumber'     => $this->generateInvoice(),
                    'orderDate'         => now(),
                    'buyerNote'         => $storeData['buyer_note'] ?? null,
                ]);

                // Buat order items + kurangi stok
                foreach ($items as $cartItem) {
                    OrderItem::create([
                        'idOrderItem'   => $this->idGenerator->generate('OIT', OrderItem::class, 'idOrderItem'),
                        'idOrder'       => $order->idOrder,
                        'idItem'        => $cartItem->idItem,
                        'nameSnapshot'  => $cartItem->productItem->product->productName,
                        'priceSnapshot' => $cartItem->productItem->price,
                        'quantity'      => $cartItem->quantity,
                    ]);

                    $cartItem->productItem->decrement('stock', $cartItem->quantity);
                }

                // Buat shipping untuk order ini (kurir sudah dipilih user saat checkout)
                Shipping::create([
                    'idShipping'     => $this->idGenerator->generate('SHP', Shipping::class, 'idShipping'),
                    'idOrder'        => $order->idOrder,
                    'idCourier'      => $storeData['id_courier'],
                    'resi'           => null,
                    'shippingPrice'  => $shippingCost,
                    'shippingStatus' => 'WAITING',
                    'shippingDate'   => null,
                    'deliveredDate'  => null,
                ]);

                $grandTotal += $storeGrandTotal;
                $createdOrders->push($this->orderRepository->findById($order->idOrder));
            }

            // Hapus hanya item yang sudah di-checkout dari cart (bukan seluruh cart)
            $cart->cartItems()
                ->whereIn('idCartItem', $cartItemIds)
                ->delete();

            return [
                'orders'      => $createdOrders,
                'grand_total' => $grandTotal,
            ];
        });
    }

    /**
     * Proses Beli Langsung (Bypass Cart).
     */
    public function checkoutDirect(
        string $userId,
        string $itemId,
        int    $quantity,
        array  $storeData,
        string $idAddress,
        float  $servicePrice = 0
    ): array {
        return DB::transaction(function () use ($userId, $itemId, $quantity, $storeData, $idAddress, $servicePrice) {

            // 1. Validasi Item & Stok
            $productItem = \App\Models\ProductItem::with('product')->findOrFail($itemId);

            if ($productItem->stock < $quantity) {
                throw ValidationException::withMessages([
                    'stock' => ["Stok {$productItem->product->productName} tidak mencukupi. Tersedia: {$productItem->stock}."]
                ]);
            }

            // 2. Kalkulasi Biaya
            $idStore      = $productItem->product->idStore;
            $productTotal = $productItem->price * $quantity;
            $shippingCost = (float) ($storeData['shipping_cost'] ?? 0);
            $grandTotal   = $productTotal + $shippingCost + $servicePrice;

            // 3. Buat Order
            $order = $this->orderRepository->create([
                'idOrder'           => $this->idGenerator->generate('ORD', Order::class, 'idOrder'),
                'idUser'            => $userId,
                'idStore'           => $idStore,
                'idAddress'         => $idAddress,
                'productTotalPrice' => $productTotal,
                'shippingCost'      => $shippingCost,
                'servicePrice'      => $servicePrice,
                'grandTotal'        => $grandTotal,
                'orderStatus'       => 'PENDING',
                'invoiceNumber'     => $this->generateInvoice(),
                'orderDate'         => now(),
                'buyerNote'         => $storeData['buyer_note'] ?? null,
            ]);

            // 4. Buat Order Item & Kurangi Stok
            OrderItem::create([
                'idOrderItem'   => $this->idGenerator->generate('OIT', OrderItem::class, 'idOrderItem'),
                'idOrder'       => $order->idOrder,
                'idItem'        => $itemId,
                'nameSnapshot'  => $productItem->product->productName,
                'priceSnapshot' => $productItem->price,
                'quantity'      => $quantity,
            ]);

            $productItem->decrement('stock', $quantity);

            // 5. Buat Shipping (kurir sudah dipilih user saat checkout)
            Shipping::create([
                'idShipping'     => $this->idGenerator->generate('SHP', Shipping::class, 'idShipping'),
                'idOrder'        => $order->idOrder,
                'idCourier'      => $storeData['id_courier'],
                'resi'           => null,
                'shippingPrice'  => $shippingCost,
                'shippingStatus' => 'WAITING',
                'shippingDate'   => null,
                'deliveredDate'  => null,
            ]);

            // Return format array seperti checkoutMultiStore
            return [
                'orders'      => collect([$this->orderRepository->findById($order->idOrder)]),
                'grand_total' => $grandTotal,
            ];
        });
    }

    /**
     * Proses checkout lama (seluruh cart, satu toko).
     * Dipertahankan agar tidak breaking existing code.
     *
     * @deprecated Gunakan checkoutMultiStore() sebagai gantinya.
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

            $productTotal = $cart->cartItems->sum(
                fn($item) => $item->productItem->price * $item->quantity
            );

            $shippingCost = $data['shipping_cost'] ?? 0;
            $servicePrice = $data['service_price'] ?? 0;
            $grandTotal   = $productTotal + $shippingCost + $servicePrice;

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

            foreach ($cart->cartItems as $cartItem) {
                OrderItem::create([
                    'idOrderItem'   => $this->idGenerator->generate('OIT', OrderItem::class, 'idOrderItem'),
                    'idOrder'       => $order->idOrder,
                    'idItem'        => $cartItem->idItem,
                    'nameSnapshot'  => $cartItem->productItem->product->productName,
                    'priceSnapshot' => $cartItem->productItem->price,
                    'quantity'      => $cartItem->quantity,
                ]);

                $cartItem->productItem->decrement('stock', $cartItem->quantity);
            }

            $this->cartRepository->clearCart($cart->idCart);

            return $this->orderRepository->findById($order->idOrder);
        });
    }

    /**
     * Update status order.
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
     * Selesaikan pesanan: update status ke DELIVERED dan cairkan dana ke penjual.
     */
    public function completeOrder(string $orderId, string $actorRole = 'BUYER'): Order
    {
        return DB::transaction(function () use ($orderId, $actorRole) {
            $order = $this->getById($orderId);

            if ($order->orderStatus !== 'SHIPPED') {
                throw ValidationException::withMessages([
                    'status' => ['Pesanan hanya bisa diselesaikan jika sudah dalam status Dikirim.'],
                ]);
            }

            $this->orderRepository->updateStatus($orderId, 'DELIVERED');

            $revenue = $order->productTotalPrice + $order->shippingCost;

            $wallet = \App\Models\StoreWallet::firstOrCreate(
                ['idStore' => $order->idStore],
                [
                    'idWallet'           => $this->idGenerator->generate('WAL', \App\Models\StoreWallet::class, 'idWallet'),
                    'activeBalance'      => 0,
                    'outstandingBalance' => 0,
                ]
            );

            $wallet->increment('activeBalance', $revenue);

            \App\Models\WalletTransaction::create([
                'idTransaction' => $this->idGenerator->generate('WTX', \App\Models\WalletTransaction::class, 'idTransaction'),
                'idWallet'      => $wallet->idWallet,
                'mutationType'  => 'IN',
                'nominal'       => $revenue,
                'description'   => "Pencairan dana pesanan selesai: {$order->invoiceNumber}",
                'referenceId'   => $order->idOrder,
            ]);

            return $order;
        });
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