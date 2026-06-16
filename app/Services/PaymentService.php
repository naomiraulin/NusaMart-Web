<?php

namespace App\Services;

use App\Models\Payment;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Repositories\WalletRepository;
use App\Services\IdGeneratorService;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public function __construct(
        private PaymentRepository  $paymentRepository,
        private OrderRepository    $orderRepository,
        private WalletRepository   $walletRepository,
        private IdGeneratorService $idGenerator,
    ) {}

    /**
     * Buat payment baru untuk order.
     */
    public function create(string $orderId, string $methodId): Payment
    {
        return $this->paymentRepository->create([
            'idPayment'            => $this->idGenerator->generate('PAY', Payment::class, 'idPayment'),
            'idMethod'             => $methodId,
            'transactionIdGateway' => null,
            'snapToken'            => null,
            'paymentStatus'        => 'PENDING',
        ]);
    }

    /**
     * Update snap token dari Midtrans.
     */
    public function setSnapToken(string $paymentId, string $snapToken): Payment
    {
        return $this->paymentRepository->updateSnapToken($paymentId, $snapToken);
    }

    /**
     * Konfirmasi pembayaran berhasil.
     * Otomatis update status order & tambah saldo wallet seller.
     */
    public function confirm(string $paymentId): Payment
    {
        return DB::transaction(function () use ($paymentId) {
            $payment = $this->paymentRepository->updateStatus($paymentId, 'APPROVED');

            // Ambil order terkait
            $order = $this->orderRepository->findById(
                \App\Models\Order::where('paymentId', $paymentId)->value('idOrder')
            );

            if ($order) {
                // Update status order jadi PROCESSED
                $this->orderRepository->updateStatus($order->idOrder, 'PROCESSED');

                // Tambah outstanding balance wallet seller
                $wallet = $this->walletRepository->findByStore($order->idStore);

                if ($wallet) {
                    $this->walletRepository->updateBalance(
                        $wallet->idWallet,
                        $wallet->activeBalance,
                        $wallet->outstandingBalance + $order->grandTotal,
                    );

                    $this->walletRepository->addTransaction([
                        'idWallet'    => $wallet->idWallet,
                        'mutationType'=> 'IN',
                        'nominal'     => $order->grandTotal,
                        'description' => "Pembayaran order {$order->invoiceNumber}",
                        'referenceId' => $order->idOrder,
                    ]);
                }
            }

            return $payment;
        });
    }

    /**
     * Batalkan pembayaran.
     */
    public function cancel(string $paymentId): Payment
    {
        return $this->paymentRepository->updateStatus($paymentId, 'CANCELED');
    }

    /**
     * Upload bukti transfer (untuk payment manual).
     */
    public function uploadProof(string $paymentId, \Illuminate\Http\UploadedFile $image): Payment
    {
        $path = $image->store("payments/{$paymentId}", 'public');

        $payment = $this->paymentRepository->findById($paymentId);
        $payment->update(['imageurl' => $path]);

        return $payment->fresh();
    }
}