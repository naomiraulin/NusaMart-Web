<?php

namespace App\Http\Controllers\Web\Buyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\ConfirmPaymentRequest;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService      $paymentService,
        private NotificationService $notificationService,
    ) {}

    /**
     * Halaman detail pembayaran.
     */
    public function show(string $paymentId): View
    {
        $payment = $this->paymentService->findById($paymentId);

        return view('buyer.payments.show', compact('payment'));
    }

    /**
     * Upload bukti transfer (untuk payment manual).
     */
    public function confirm(ConfirmPaymentRequest $request, string $paymentId): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->hasFile('proof_image')) {
            $this->paymentService->uploadProof($paymentId, $request->file('proof_image'));
        }

        return back()->with('success', 'Bukti pembayaran berhasil dikirim. Menunggu konfirmasi admin.');
    }
}