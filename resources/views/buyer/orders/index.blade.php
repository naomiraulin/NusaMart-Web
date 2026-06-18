<x-buyer-layout>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ activeTab: 'semua' }">
        
        <div class="mb-8">
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Pesanan Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Pantau status pesanan dan riwayat belanja kamu di sini.</p>
        </div>

        {{-- TABS NAVIGATION --}}
        <div class="bg-white rounded-t-2xl border-b border-gray-200 sticky top-0 z-10">
            <div class="flex overflow-x-auto scrollbar-none gap-6 px-6">
                @php
                    $tabs = [
                        'semua' => 'Semua',
                        'pending' => 'Belum Bayar',
                        'processed' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan'
                    ];
                @endphp

                @foreach($tabs as $key => $label)
                    <button @click="activeTab = '{{ $key }}'" 
                            :class="activeTab === '{{ $key }}' ? 'border-nusa text-nusa font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium'"
                            class="py-4 border-b-2 whitespace-nowrap text-sm transition-colors duration-200 focus:outline-none">
                        {{ $label }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- LIST PESANAN --}}
        <div class="mt-6 space-y-4">
            @forelse($orders as $order)
                @php
                    $status = strtolower($order->orderStatus);
                    // Menentukan warna badge berdasarkan status
                    $badgeColors = [
                        'pending'   => 'bg-amber-100 text-amber-800 border-amber-200',
                        'processed' => 'bg-blue-100 text-blue-800 border-blue-200',
                        'shipped'   => 'bg-purple-100 text-purple-800 border-purple-200',
                        'completed' => 'bg-green-100 text-green-800 border-green-200',
                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                    ];
                    $badgeColor = $badgeColors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                    $statusLabel = $tabs[$status] ?? $order->orderStatus;
                @endphp

                {{-- Card Pesanan (Hanya tampil jika activeTab === 'semua' atau activeTab === status) --}}
                <div x-show="activeTab === 'semua' || activeTab === '{{ $status }}'" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200" style="display: none;">
                    
                    {{-- Header Card --}}
                    <div class="px-6 py-4 border-b border-gray-50 flex flex-wrap items-center justify-between gap-4 bg-gray-50/50">
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">{{ \Carbon\Carbon::parse($order->createAt)->format('d M Y') }}</span>
                            <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-nusa" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span class="text-sm font-bold text-gray-800">{{ $order->store->name ?? 'Toko Tidak Ditemukan' }}</span>
                            </div>
                        </div>
                        <div class="px-3 py-1 text-[11px] font-bold uppercase tracking-wider rounded-md border {{ $badgeColor }}">
                            {{ $statusLabel }}
                        </div>
                    </div>

                    {{-- Body Card (Item Produk Pertama) --}}
                    @php
                        $firstItem = $order->orderItems->first();
                        $totalItems = $order->orderItems->count();
                        
                        // Menarik gambar produk (Jika relasi productItem -> product -> productImages tersedia)
                        $imageURL = null;
                        if ($firstItem && $firstItem->productItem && $firstItem->productItem->product) {
                            $imageURL = $firstItem->productItem->product->productImages->first()->imageURL ?? null;
                        }
                    @endphp

                    @if($firstItem)
                        <div class="p-6 flex flex-col md:flex-row gap-6 items-start md:items-center">
                            {{-- Gambar --}}
                            <div class="w-20 h-20 shrink-0 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden">
                                @if($imageURL)
                                    <img src="{{ $imageURL }}" alt="Produk" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                            </div>

                            {{-- Detail Item --}}
                            <div class="flex-grow">
                                <h3 class="text-sm font-bold text-gray-900 line-clamp-1">{{ $firstItem->nameSnapshot }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $firstItem->quantity }} barang x Rp{{ number_format($firstItem->priceSnapshot, 0, ',', '.') }}</p>
                                
                                @if($totalItems > 1)
                                    <p class="text-xs font-semibold text-nusa mt-2">+{{ $totalItems - 1 }} produk lainnya</p>
                                @endif
                            </div>

                            {{-- Total Harga Vertikal Line Pembatas (Desktop Mode) --}}
                            <div class="hidden md:block w-px h-16 bg-gray-100 mx-4"></div>

                            {{-- Total Harga --}}
                            <div class="shrink-0 w-full md:w-auto flex flex-row md:flex-col justify-between md:justify-center items-center md:items-end pt-4 md:pt-0 border-t md:border-none border-gray-100">
                                <span class="text-xs text-gray-500 font-medium">Total Belanja</span>
                                <span class="text-base font-black text-gray-900">Rp{{ number_format($order->grandTotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @endif

                    {{-- Footer Card (Action Buttons) --}}
                    <div class="px-6 py-4 bg-gray-50/30 border-t border-gray-50 flex flex-wrap justify-end gap-3">
                        <a href="{{ route('buyer.orders.show', $order->idOrder) }}" class="px-5 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 hover:text-nusa hover:border-nusa transition-colors duration-200">
                            Lihat Detail Pesanan
                        </a>
                        
                        @if($status === 'pending')
                            <a href="#" class="px-5 py-2 bg-nusa text-white text-sm font-bold rounded-lg hover:bg-nusa-dark shadow-sm shadow-nusa/20 transition-all duration-200">
                                Bayar Sekarang
                            </a>
                        @elseif($status === 'shipped')
                            <form action="#" method="POST">
                                @csrf
                                <button type="submit" class="px-5 py-2 bg-nusa text-white text-sm font-bold rounded-lg hover:bg-nusa-dark shadow-sm shadow-nusa/20 transition-all duration-200">
                                    Pesanan Diterima
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Belum ada pesanan</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm mx-auto">Sepertinya kamu belum membuat pesanan apa pun. Yuk, mulai belanja dan temukan produk menarik!</p>
                    <a href="{{ route('home') }}" class="mt-6 px-6 py-2.5 bg-nusa text-white text-sm font-bold rounded-xl hover:bg-nusa-dark shadow-sm transition-all duration-200">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>

    </div>
</x-buyer-layout>