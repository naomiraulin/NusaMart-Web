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
     * Buat payment baru.
     *
     * Sebelumnya hanya menerima $orderId, sekarang menerima userId dan totalAmount
     * secara eksplisit karena satu payment bisa mencakup beberapa order (multi-store).
     */
    public function create(string $userId, string $methodId, float $totalAmount): Payment
    {
        return $this->paymentRepository->create([
            'idPayment'            => $this->idGenerator->generate('PAY', Payment::class, 'idPayment'),
            'idUser'               => $userId,
            'idMethod'             => $methodId,
            'totalAmount'          => $totalAmount,
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

            // Ambil semua order yang terkait payment ini
            $orders = \App\Models\Order::where('idPayment', $paymentId)->get();

            foreach ($orders as $order) {
                $this->orderRepository->updateStatus($order->idOrder, 'PROCESSED');

                $wallet = $this->walletRepository->findByStore($order->idStore);

                if ($wallet) {
                    $this->walletRepository->updateBalance(
                        $wallet->idWallet,
                        $wallet->activeBalance,
                        $wallet->outstandingBalance + $order->grandTotal,
                    );

                    $this->walletRepository->addTransaction([
                        'idWallet'     => $wallet->idWallet,
                        'mutationType' => 'IN',
                        'nominal'      => $order->grandTotal,
                        'description'  => "Pembayaran order {$order->invoiceNumber}",
                        'referenceId'  => $order->idOrder,
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
        $payment->update(['imageURL' => $path]);

        return $payment->fresh();
    }

    public function findById(string $id): Payment
    {
        $payment = $this->paymentRepository->findById($id);

        if (!$payment) {
            abort(404, 'Pembayaran tidak ditemukan.');
        }

        return $payment;
    }
}