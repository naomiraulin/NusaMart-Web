<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService      $paymentService,
        private NotificationService $notificationService,
    ) {}

    /**
     * Daftar semua pembayaran.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['paymentMethod']);

        if ($request->filled('status')) {
            $query->where('paymentStatus', $request->input('status'));
        }

        $payments = $query->latest('paymentTime')->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Detail pembayaran.
     */
    public function show(string $id): View
    {
        $payment = $this->paymentService->findById($id);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Konfirmasi pembayaran manual (bukti transfer).
     */
    public function confirm(string $id): RedirectResponse
    {
        $payment = $this->paymentService->confirm($id);

        // Ambil order terkait untuk notifikasi
        $order = \App\Models\Order::where('paymentId', $id)->first();
        if ($order) {
            $this->notificationService->sendOrderNotif(
                $order->idUser, $order->idOrder, 'PROCESSED'
            );
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    /**
     * Batalkan pembayaran.
     */
    public function cancel(string $id): RedirectResponse
    {
        $this->paymentService->cancel($id);

        return back()->with('success', 'Pembayaran berhasil dibatalkan.');
    }
}