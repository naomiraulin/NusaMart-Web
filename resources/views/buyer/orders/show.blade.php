<x-buyer-layout>
    @php
        $status = strtolower($order->orderStatus);
        
        $badgeColors = [
            'pending'   => 'bg-amber-100 text-amber-800 border-amber-200',
            'processed' => 'bg-blue-100 text-blue-800 border-blue-200',
            'shipped'   => 'bg-purple-100 text-purple-800 border-purple-200',
            'delivered' => 'bg-green-100 text-green-800 border-green-200',
            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
        ];
        
        $statusLabels = [
            'pending'   => 'Belum Bayar',
            'processed' => 'Diproses',
            'shipped'   => 'Dikirim',
            'delivered' => 'Selesai',
            'cancelled' => 'Dibatalkan'
        ];

        $badgeColor = $badgeColors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
        $statusLabel = $statusLabels[$status] ?? $order->orderStatus;
    @endphp

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- BACK BUTTON & BREADCRUMB --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('buyer.orders.index') }}" class="p-2 bg-white border border-gray-200 rounded-xl text-gray-500 hover:text-nusa hover:border-nusa hover:bg-nusa-light transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-gray-900 tracking-tight">Detail Pesanan</h1>
                <p class="text-sm text-gray-500 mt-0.5">No. Invoice: <span class="font-bold text-nusa">{{ $order->invoiceNumber ?? 'INV-PROCESSING' }}</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
            
            {{-- KIRI: INFORMASI UTAMA PESANAN --}}
            <div class="lg:col-span-2 space-y-6">
                
                {{-- STATUS CARD --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Status Pesanan</p>
                        <h2 class="text-lg font-black text-gray-900">{{ $statusLabel }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Tanggal Pembelian</p>
                        <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($order->createAt)->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                </div>

                {{-- PRODUK CARD --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-nusa" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            <span class="text-sm font-bold text-gray-900">{{ $order->store->name ?? 'Toko' }}</span>
                        </div>
                        <a href="{{ route('store.detail', $order->idStore) }}" class="text-xs font-bold text-nusa hover:text-nusa-dark">Kunjungi Toko</a>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        @foreach($order->orderItems as $item)
                            @php
                                $imageURL = null;
                                if ($item->productItem && $item->productItem->product) {
                                    $imageURL = $item->productItem->product->productImages->first()->imageURL ?? null;
                                }

                                // CEK APAKAH BARANG INI SUDAH DIREVIEW
                                // Kita mengecek langsung melalui relasi atau query sederhana
                                $hasReviewed = \App\Models\Review::where('idOrderItem', $item->idOrderItem)
                                                    ->where('idUser', auth()->id())
                                                    ->exists();
                            @endphp
                            
                            <div class="flex flex-col sm:flex-row gap-4 border-b border-gray-50 pb-4 last:border-0 last:pb-0">
                                {{-- Gambar dan Detail Barang (Sama seperti sebelumnya) --}}
                                <div class="flex gap-4 flex-1">
                                    <div class="w-20 h-20 shrink-0 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden">
                                        @if($imageURL)
                                            <img src="{{ $imageURL }}" alt="Produk" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow flex flex-col justify-center">
                                        <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $item->nameSnapshot }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">{{ $item->quantity }} x Rp{{ number_format($item->priceSnapshot, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="shrink-0 flex flex-col justify-center items-end">
                                        <p class="text-xs text-gray-500 font-medium mb-0.5">Total Harga</p>
                                        <p class="text-sm font-black text-gray-900">Rp{{ number_format($item->quantity * $item->priceSnapshot, 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                {{-- BAGIAN TOMBOL REVIEW (Akan berada di bawah detail barang pada mobile, di samping pada desktop) --}}
                                @if($status === 'delivered')
                                    <div class="sm:border-l sm:border-gray-100 sm:pl-4 flex items-center justify-end sm:justify-center shrink-0 min-w-[140px]">
                                        @if($hasReviewed)
                                            <span class="text-xs font-bold text-green-600 flex items-center gap-1 bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Sudah Diulas
                                            </span>
                                        @else
                                            {{-- Tombol menuju form ulasan, mengirimkan ID Order Item --}}
                                            <a href="{{ route('buyer.reviews.create', $item->idOrderItem) }}" class="text-xs font-bold text-nusa hover:text-white border border-nusa hover:bg-nusa transition-colors px-4 py-2 rounded-lg">
                                                Tulis Ulasan
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- INFO PENGIRIMAN --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-3">Info Pengiriman</h3>
                    
                    <div class="space-y-5">
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Alamat Penerima</p>
                                <p class="text-sm font-bold text-gray-900">{{ $order->address->receiver ?? 'Penerima' }} <span class="font-normal text-gray-500 ml-1">({{ $order->address->phone ?? '-' }})</span></p>
                                <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                    {{ $order->address->completeAddress ?? '' }}<br>
                                    {{ $order->address->city ?? '' }}, {{ $order->address->province ?? '' }} {{ $order->address->postalCode ?? '' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <div class="w-full">
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-1">Kurir & Resi</p>
                                @if($order->shipping)
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900">{{ $order->shipping->courier->courierName ?? 'Kurir Reguler' }}</p>
                                        <p class="text-sm font-semibold text-nusa">{{ $order->shipping->resi ?? 'Belum ada resi' }}</p>
                                    </div>
                                    @if($order->shipping->shippingTrackings && $order->shipping->shippingTrackings->isNotEmpty())
                                        <div class="mt-3 p-3 bg-gray-50 rounded-xl">
                                            @php $lastTrack = $order->shipping->shippingTrackings->last(); @endphp
                                            <p class="text-xs font-semibold text-gray-800">{{ $lastTrack->packetLocation }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $lastTrack->description }}</p>
                                            <p class="text-[10px] text-gray-400 mt-1">{{ \Carbon\Carbon::parse($lastTrack->createAt)->format('d M Y, H:i') }}</p>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-sm text-gray-600">Menunggu penjual mengatur pengiriman.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KANAN: RINCIAN PEMBAYARAN & AKSI --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- RINCIAN BIAYA --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <h3 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-4 border-b border-gray-100 pb-3">Rincian Pembayaran</h3>
                    
                    <div class="space-y-3 mb-4">
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <span>Subtotal Produk</span>
                            <span class="font-medium text-gray-900">Rp{{ number_format($order->productTotalPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <span>Total Ongkos Kirim</span>
                            <span class="font-medium text-gray-900">Rp{{ number_format($order->shippingCost, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm text-gray-600">
                            <span>Biaya Layanan</span>
                            <span class="font-medium text-gray-900">Rp{{ number_format($order->servicePrice, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4 mb-4">
                        <div class="flex justify-between items-end">
                            <span class="text-sm font-bold text-gray-800">Total Belanja</span>
                            <span class="text-xl font-black text-nusa">Rp{{ number_format($order->grandTotal, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-3 flex justify-between items-center">
                        <div class="flex items-center gap-2 text-sm text-gray-600 font-medium">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            Metode Pembayaran
                        </div>
                        <div class="text-sm font-bold text-gray-900 text-right">
                            @if($order->payment && $order->payment->paymentMethod)
                                {{ $order->payment->paymentMethod->methodName }}<br>
                                <span class="text-[10px] font-medium text-gray-500 uppercase">{{ $order->payment->paymentMethod->provider }}</span>
                            @else
                                Belum dipilih
                            @endif
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-3">
                    @if($status === 'pending')
                        <a href="#" class="block w-full py-3 px-4 bg-nusa text-white text-center text-sm font-bold rounded-xl hover:bg-nusa-dark shadow-sm shadow-nusa/20 transition-all duration-200">
                            Bayar Sekarang
                        </a>
                        <form action="{{ route('buyer.orders.cancel', $order->idOrder) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')" class="w-full py-3 px-4 bg-white border border-gray-300 text-red-600 text-sm font-bold rounded-xl hover:bg-red-50 hover:border-red-200 transition-all duration-200">
                                Batalkan Pesanan
                            </button>
                        </form>
                    @elseif($status === 'shipped')
                        <form action="#" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 bg-nusa text-white text-sm font-bold rounded-xl hover:bg-nusa-dark shadow-sm shadow-nusa/20 transition-all duration-200">
                                Pesanan Diterima
                            </button>
                        </form>
                    @elseif($status === 'delivered')
                        <a href="{{ route('store.detail', $order->idStore) }}" class="block w-full py-3 px-4 bg-white border-2 border-nusa text-nusa text-center text-sm font-bold rounded-xl hover:bg-nusa/5 transition-all duration-200">
                            Beli Lagi
                        </a>
                    @else
                        <a href="{{ route('home') }}" class="block w-full py-3 px-4 bg-white border border-gray-300 text-gray-700 text-center text-sm font-bold rounded-xl hover:bg-gray-50 transition-all duration-200">
                            Kembali Belanja
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-buyer-layout>