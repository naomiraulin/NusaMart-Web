<x-buyer-layout>
    <x-slot name="pageTitle">Pembayaran</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-10"
         x-data="{
            // Waktu order ditambah 24 jam (tenggat waktu bayar)
            deadline: new Date('{{ \Carbon\Carbon::parse($order->orderDate)->addDay()->toIso8601String() }}').getTime(),
            timeLeft: { hours: '00', minutes: '00', seconds: '00' },
            
            init() {
                this.updateTimer();
                setInterval(() => this.updateTimer(), 1000);
            },
            
            updateTimer() {
                const now = new Date().getTime();
                const distance = this.deadline - now;
                
                if (distance < 0) {
                    this.timeLeft = { hours: '00', minutes: '00', seconds: '00' };
                    return;
                }
                
                const h = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((distance % (1000 * 60)) / 1000);
                
                this.timeLeft.hours = h.toString().padStart(2, '0');
                this.timeLeft.minutes = m.toString().padStart(2, '0');
                this.timeLeft.seconds = s.toString().padStart(2, '0');
            }
         }">

        @php
            $provider   = $order->payment->paymentMethod->provider ?? 'MANUAL';
            $methodName = $order->payment->paymentMethod->methodName ?? '';

            $bankAccounts = [
                'BCA'     => '1234567890',
                'BRI'     => '0987654321',
                'BNI'     => '1122334455',
                'MANDIRI' => '1370012345678',
            ];

            $selectedBank  = null;
            $accountNumber = null;

            foreach ($bankAccounts as $bankCode => $accNumber) {
                if (str_contains(strtoupper($methodName), $bankCode)) {
                    $selectedBank  = $bankCode;
                    $accountNumber = $accNumber;
                    break;
                }
            }
        @endphp

        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Selesaikan Pembayaran</h1>
            <p class="text-sm text-gray-500 mt-2">Segera lakukan pembayaran sebelum tenggat waktu berakhir agar pesananmu segera diproses.</p>
        </div>

        {{-- BOX TENGGAT WAKTU --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 flex flex-col items-center mb-6 shadow-sm">
            <span class="text-xs font-bold text-amber-700 uppercase tracking-wider mb-2">Batas Waktu Pembayaran</span>
            <div class="flex items-center gap-3 text-3xl font-black text-amber-600">
                <div class="flex flex-col items-center">
                    <span x-text="timeLeft.hours"></span>
                    <span class="text-[10px] font-semibold text-amber-500 uppercase tracking-widest mt-1">Jam</span>
                </div>
                <span class="mb-5">:</span>
                <div class="flex flex-col items-center">
                    <span x-text="timeLeft.minutes"></span>
                    <span class="text-[10px] font-semibold text-amber-500 uppercase tracking-widest mt-1">Menit</span>
                </div>
                <span class="mb-5">:</span>
                <div class="flex flex-col items-center">
                    <span x-text="timeLeft.seconds"></span>
                    <span class="text-[10px] font-semibold text-amber-500 uppercase tracking-widest mt-1">Detik</span>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
            {{-- HEADER INFO --}}
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between gap-4">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Nomor Invoice</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $order->invoiceNumber }}</p>
                </div>
                <div class="md:text-right">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Metode Pembayaran</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $methodName }}</p>
                </div>
            </div>

            {{-- TOTAL BAYAR --}}
            <div class="p-6 border-b border-gray-100 text-center bg-gray-50/50">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Total Tagihan</p>
                <p class="text-4xl font-black text-nusa tracking-tight">Rp{{ number_format($order->grandTotal, 0, ',', '.') }}</p>
            </div>

            @if ($provider === 'MANUAL' && $selectedBank)
                {{-- TRANSFER BANK --}}
                <div class="p-8 flex flex-col items-center">
                    <p class="text-sm text-gray-600 mb-6 text-center">Transfer sesuai nominal di atas ke rekening berikut, lalu upload bukti transfer di bawah.</p>

                    <div class="w-full max-w-sm bg-gray-50 border border-gray-200 rounded-xl p-5 text-center">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Bank {{ $selectedBank }}</p>
                        <p class="text-2xl font-black text-gray-900 tracking-wider mb-1">{{ $accountNumber }}</p>
                        <p class="text-sm text-gray-600">a.n. NUSAMART</p>
                    </div>

                    <p class="text-xs text-gray-400 mt-4 text-center max-w-sm">
                        Pastikan nominal transfer sesuai persis dengan total tagihan agar pembayaran cepat terverifikasi.
                    </p>
                </div>

            @elseif ($provider === 'MIDTRANS')
                {{-- QRIS --}}
                <div class="p-8 flex flex-col items-center">
                    <p class="text-sm text-gray-600 mb-6 text-center">Pindai kode QRIS di bawah ini menggunakan aplikasi M-Banking atau e-Wallet kesayanganmu.</p>

                    <div class="p-4 bg-white border-2 border-nusa rounded-2xl shadow-sm inline-block relative">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=NUSAMART-QRIS-{{ $order->invoiceNumber }}&bgcolor=ffffff"
                             alt="QRIS Code"
                             class="w-56 h-56 rounded-xl object-contain">

                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <div class="bg-white p-1 rounded-md shadow-sm border border-gray-100">
                                <span class="font-black text-[10px] text-nusa tracking-wider">QRIS</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-gray-400 mt-4 text-center max-w-sm">a.n. NUSAMART</p>
                </div>

            @elseif ($provider === 'COD')
                {{-- COD --}}
                <div class="p-8 flex flex-col items-center text-center">
                    <svg class="w-14 h-14 text-nusa mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M3 7v11a2 2 0 002 2h14a2 2 0 002-2V7M3 7l2-4h14l2 4M9 11h6"/>
                    </svg>
                    <p class="text-sm text-gray-700 max-w-sm">
                        Pesanan ini akan dibayar secara <strong>tunai/QRIS langsung ke kurir</strong> saat barang diterima.
                        Tidak perlu transfer atau upload bukti pembayaran sekarang.
                    </p>
                </div>
            @endif
        </div>

        @if ($provider === 'MANUAL' || $provider === 'MIDTRANS')
            {{-- FORM UPLOAD BUKTI TRANSFER --}}
            <div class="mt-8 border-t border-dashed border-gray-300 pt-8">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="font-bold text-gray-800 text-sm mb-1">Upload Bukti Transfer</h3>
                    <p class="text-xs text-gray-500 mb-4">Setelah bukti diupload, pesanan akan otomatis dikonfirmasi sebagai lunas.</p>

                    <form action="{{ route('buyer.orders.confirmPayment', $order->idOrder) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="file" name="proof_image" accept="image/*" required
                               class="block w-full text-sm text-gray-600 border border-gray-300 rounded-lg cursor-pointer mb-2 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-nusa file:text-white file:text-sm file:font-semibold">

                        @error('proof_image')
                            <p class="text-xs text-red-500 mb-3">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-nusa text-white text-sm font-bold rounded-lg shadow-sm hover:opacity-90 transition-colors mt-3">
                            Kirim Bukti & Konfirmasi Pembayaran
                        </button>
                    </form>
                </div>
            </div>

        @elseif ($provider === 'COD')
            {{-- TOMBOL KONFIRMASI COD --}}
            <div class="mt-8 border-t border-dashed border-gray-300 pt-8">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm text-center">
                    <form action="{{ route('buyer.orders.confirmCod', $order->idOrder) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-nusa text-white text-sm font-bold rounded-lg shadow-sm hover:opacity-90 transition-colors">
                            Konfirmasi Pesanan (COD)
                        </button>
                    </form>
                </div>
            </div>
        @endif

    </div>
</x-buyer-layout>