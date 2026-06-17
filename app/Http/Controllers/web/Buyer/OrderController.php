<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CheckoutRequest;
use App\Models\CourierOption;
use App\Models\PaymentMethod;
use App\Models\UserAddress;
use App\Services\CartService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private OrderService        $orderService,
        private CartService         $cartService,
        private PaymentService      $paymentService,
        private NotificationService $notificationService,
    ) {}

    /**
     * Riwayat semua pesanan buyer.
     */
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user   = Auth::user();
        $orders = $this->orderService->getByUser($user->idUser);

        return view('buyer.orders.index', compact('orders'));
    }

    /**
     * Detail satu pesanan.
     */
    public function show(string $id): View
    {
        $order = $this->orderService->getById($id);

        return view('buyer.orders.show', compact('order'));
    }

    /**
     * Halaman checkout — pilih alamat, kurir, metode pembayaran.
     */
    public function checkout(): View
    {
        /** @var \App\Models\User $user */
        $user      = Auth::user();
        $cart      = $this->cartService->getCart($user->idUser);
        $total     = $this->cartService->calculateTotal($user->idUser);
        $addresses = UserAddress::where('idUser', $user->idUser)->get();
        $couriers  = CourierOption::where('isActive', true)->get();
        $methods   = PaymentMethod::where('isActive', true)->get();

        return view('buyer.orders.checkout', compact(
            'cart', 'total', 'addresses', 'couriers', 'methods'
        ));
    }

    /**
     * Proses checkout.
     */
    public function placeOrder(CheckoutRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user  = Auth::user();
        $order = $this->orderService->checkout($user->idUser, $request->validated());

        // Buat payment
        $payment = $this->paymentService->create($order->idOrder, $request->input('id_method'));

        // Update paymentId di order
        $order->update(['paymentId' => $payment->idPayment]);

        // Kirim notifikasi
        $this->notificationService->sendOrderNotif($user->idUser, $order->idOrder, 'PENDING');

        return redirect()->route('buyer.orders.show', $order->idOrder)
            ->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * Batalkan pesanan.
     */
    public function cancel(string $id): RedirectResponse
    {
        $user = Auth::user();
        $this->orderService->updateStatus($id, 'CANCELLED', 'BUYER');

        return redirect()->route('buyer.orders.show', $id)
            ->with('success', 'Pesanan berhasil dibatalkan.');
    }
}