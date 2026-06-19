@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Keranjang Saya</x-slot>

    @php
        // Map idCartItem => subtotal (price * quantity), dikirim ke Alpine
        // supaya total di "Ringkasan Belanja" bisa dihitung ulang secara
        // reaktif berdasarkan item yang sedang dicentang, bukan total
        // statis dari seluruh isi cart.
        $itemPriceMap = $cart->cartItems->mapWithKeys(function ($item) {
            $subtotal = ($item->productItem?->price ?? 0) * $item->quantity;
            return [$item->idCartItem => $subtotal];
        });
    @endphp

    <div
        x-data="{
            // Berisi idCartItem yang sedang dicentang
            checked: [],

            // Map idCartItem => subtotal, dipakai untuk hitung total terpilih
            priceMap: {{ $itemPriceMap->toJson() }},

            toggleItem(id) {
                if (this.checked.includes(id)) {
                    this.checked = this.checked.filter(i => i !== id);
                } else {
                    this.checked.push(id);
                }
            },

            toggleStore(storeItemIds) {
                const allChecked = storeItemIds.every(id => this.checked.includes(id));
                if (allChecked) {
                    this.checked = this.checked.filter(id => !storeItemIds.includes(id));
                } else {
                    storeItemIds.forEach(id => {
                        if (!this.checked.includes(id)) this.checked.push(id);
                    });
                }
            },

            toggleAll(allItemIds) {
                const allChecked = allItemIds.every(id => this.checked.includes(id));
                this.checked = allChecked ? [] : [...allItemIds];
            },

            isStoreChecked(storeItemIds) {
                return storeItemIds.length > 0 && storeItemIds.every(id => this.checked.includes(id));
            },

            isAllChecked(allItemIds) {
                return allItemIds.length > 0 && allItemIds.every(id => this.checked.includes(id));
            },

            // Total dihitung ulang otomatis setiap kali `checked` berubah,
            // hanya menjumlahkan subtotal item yang dicentang.
            get selectedTotal() {
                return this.checked.reduce((sum, id) => sum + (this.priceMap[id] || 0), 0);
            },
        }"
    >

        {{-- Pilih Semua (global) --}}
        @php
            $allItemIds = $cart->cartItems->pluck('idCartItem')->toArray();
        @endphp

        @if (count($allItemIds) > 0)
            <div class="bg-white border border-gray-200 rounded-lg px-4 py-3 mb-4 flex items-center gap-3">
                <input type="checkbox"
                    :checked="isAllChecked({{ json_encode($allItemIds) }})"
                    @change="toggleAll({{ json_encode($allItemIds) }})"
                    class="w-4 h-4 rounded border-gray-300 text-nusa focus:ring-nusa">
                <span class="text-sm font-medium text-gray-700">
                    Pilih Semua (<span x-text="checked.length"></span>)
                </span>
            </div>
        @endif

        
        @if ($cart->cartItems->isEmpty())
            {{-- Empty state: di luar grid supaya bisa full width & centered --}}
            <div class="bg-white border border-gray-200 rounded-lg flex flex-col items-center justify-center py-20 text-center">
                <div class="w-14 h-14 rounded-full bg-nusa-light flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-nusa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.5 5M16 21a1 1 0 100-2 1 1 0 000 2zM8 21a1 1 0 100-2 1 1 0 000 2z" />
                    </svg>
                </div>
                <p class="text-gray-700 font-medium">Keranjang kamu masih kosong</p>
                <p class="text-sm text-gray-400 mt-1">Yuk mulai belanja produk lokal favoritmu.</p>
                <a href="{{ route('home') }}" class="mt-4 text-sm font-semibold text-nusa hover:text-nusa-dark">
                    Lihat Produk &rarr;
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-5 items-start">
                {{-- KOLOM KIRI: daftar item per toko --}}
                <div class="space-y-4">
                    @if ($cart->cartItems->isEmpty())
                        {{-- Empty state --}}
                        <div class="bg-white border border-gray-200 rounded-lg flex flex-col items-center justify-center py-20 text-center">
                            <div class="w-14 h-14 rounded-full bg-nusa-light flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-nusa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.5 5M16 21a1 1 0 100-2 1 1 0 000 2zM8 21a1 1 0 100-2 1 1 0 000 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-700 font-medium">Keranjang kamu masih kosong</p>
                            <p class="text-sm text-gray-400 mt-1">Yuk mulai belanja produk lokal favoritmu.</p>
                            <a href="{{ route('home') }}" class="mt-4 text-sm font-semibold text-nusa hover:text-nusa-dark">
                                Lihat Produk &rarr;
                            </a>
                        </div>
                    @else
                        @php
                            // Kelompokkan item cart berdasarkan toko (idStore lewat productItem -> product -> store)
                            $groupedByStore = $cart->cartItems->groupBy(fn($item) => $item->productItem->product->idStore ?? 'unknown');
                        @endphp

                        @foreach ($groupedByStore as $idStore => $items)
                            @php
                                $store = $items->first()->productItem->product->store ?? null;
                                $storeItemIds = $items->pluck('idCartItem')->toArray();
                            @endphp

                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">

                                {{-- Header toko --}}
                                <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 bg-gray-50">
                                    <input type="checkbox"
                                        :checked="isStoreChecked({{ json_encode($storeItemIds) }})"
                                        @change="toggleStore({{ json_encode($storeItemIds) }})"
                                        class="w-4 h-4 rounded border-gray-300 text-nusa focus:ring-nusa">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $store->name ?? 'Toko Tidak Diketahui' }}
                                    </span>
                                </div>

                                {{-- Item per toko --}}
                                <div class="divide-y divide-gray-100">
                                    @foreach ($items as $cartItem)
                                        @php
                                            $productItem = $cartItem->productItem;
                                            $product = $productItem?->product;
                                            $primaryImage = $product?->productImages->firstWhere('isPrimary', true)
                                                            ?? $product?->productImages->first();
                                            $variationLabel = $productItem?->productVariations
                                                ->map(fn($v) => $v->value)
                                                ->implode(', ');
                                            $subtotal = ($productItem?->price ?? 0) * $cartItem->quantity;
                                        @endphp

                                        <div class="flex items-center gap-4 px-4 py-4">
                                            <input type="checkbox"
                                                :checked="checked.includes('{{ $cartItem->idCartItem }}')"
                                                @change="toggleItem('{{ $cartItem->idCartItem }}')"
                                                class="w-4 h-4 rounded border-gray-300 text-nusa focus:ring-nusa flex-shrink-0">

                                            {{-- Gambar produk --}}
                                            <div class="w-16 h-16 rounded-md bg-gray-100 overflow-hidden flex-shrink-0">
                                                @if ($primaryImage)
                                                    <img src="{{ $primaryImage->imageURL }}" alt="{{ $product->productName }}"
                                                        class="w-full h-full object-cover">
                                                @endif
                                            </div>

                                            {{-- Info produk --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm text-gray-800 truncate">{{ $product->productName ?? 'Produk tidak ditemukan' }}</p>
                                                @if ($variationLabel)
                                                    <p class="text-xs text-gray-400 mt-0.5">{{ $variationLabel }}</p>
                                                @endif
                                            </div>

                                            {{-- Harga --}}
                                            <div class="text-sm font-semibold text-gray-800 flex-shrink-0 w-28 text-right">
                                                Rp{{ number_format($subtotal, 0, ',', '.') }}
                                            </div>

                                            {{-- Quantity & hapus --}}
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <form action="{{ route('buyer.cart.remove', $cartItem->idCartItem) }}" method="POST"
                                                    onsubmit="return confirm('Hapus item ini dari keranjang?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>

                                                <form action="{{ route('cart.update', $cartItem->idCartItem) }}" method="POST"
                                                    class="flex items-center border border-gray-200 rounded-md overflow-hidden">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" name="quantity" value="{{ max(1, $cartItem->quantity - 1) }}"
                                                        class="px-2 py-1 text-gray-500 hover:bg-gray-50">&minus;</button>
                                                    <span class="px-3 py-1 text-sm border-x border-gray-200">{{ $cartItem->quantity }}</span>
                                                    <button type="submit" name="quantity" value="{{ $cartItem->quantity + 1 }}"
                                                        class="px-2 py-1 text-gray-500 hover:bg-gray-50">+</button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- KOLOM KANAN: ringkasan belanja --}}
                @if ($cart->cartItems->isNotEmpty())
                    <div class="bg-white border border-gray-200 rounded-lg p-5 sticky top-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4">Ringkasan Belanja</h3>

                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-500">Total</span>
                            {{-- Sebelumnya pakai {{ $total }} dari PHP (total SELURUH cart,
                                tidak peduli dicentang atau tidak). Sekarang dihitung reaktif
                                dari item yang dicentang lewat getter Alpine `selectedTotal`. --}}
                            <span class="font-bold text-nusa" x-text="'Rp' + selectedTotal.toLocaleString('id-ID')"></span>
                        </div>
                        <p class="text-xs text-gray-400 mb-4">
                            <span x-text="checked.length"></span> Produk dipilih
                        </p>

                        {{-- TODO: belum ada route checkout, form diarahkan ke '#' sementara.
                            Idealnya kirim daftar idCartItem yang dicentang (state Alpine `checked`)
                            sebagai input tersembunyi sebelum submit ke route checkout. --}}
                        <form action="#" method="POST" @submit="if (checked.length === 0) $event.preventDefault()">
                            @csrf
                            <button type="submit"
                                class="w-full bg-nusa hover:bg-nusa-dark text-white text-sm font-semibold py-2.5 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed"
                                :disabled="checked.length === 0">
                                Beli
                            </button>
                        </form>
                    </div>
                @endif

            </div>
        @endif
    </div>
</x-dynamic-component>