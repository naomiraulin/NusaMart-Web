@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Checkout</x-slot>

    @php
        /**
         * $groupedItems  = Collection per store: ['idStore' => Collection<CartItem>]
         * $addresses     = Collection<UserAddress>
         * $couriers      = Collection<CourierOption>
         * $methods       = Collection<PaymentMethod>
         * $cartItemIds   = array of idCartItem yang dipilih
         * $defaultAddress= UserAddress|null
         */

        $SERVICE_FEE = 2500; // flat service fee (Rp)

        // Kelompokkan item yang dipilih berdasarkan toko
        $groupedItems = $selectedItems->groupBy(
            fn($item) => $item->productItem->product->idStore ?? 'unknown'
        );

        $defaultAddress = $addresses->firstWhere('isDefault', true) ?? $addresses->first();

        // Subtotal produk semua item yang dipilih
        $productSubtotal = $selectedItems->sum(
            fn($item) => ($item->productItem?->price ?? 0) * $item->quantity
        );
    @endphp

    {{-- Alpine state untuk seluruh halaman checkout --}}
    <div
        x-data="{
            selectedAddressId: '{{ $defaultAddress?->idAddress ?? '' }}',
            selectedMethodId: '',

            // Per-store state: courier selection & buyer note
            stores: {
                @foreach ($groupedItems as $idStore => $items)
                '{{ $idStore }}': {
                    courierId: '',
                    shippingCost: 0,
                    buyerNote: '',
                },
                @endforeach
            },

            // Hitung total ongkir semua toko
            get totalShipping() {
                return Object.values(this.stores).reduce((sum, s) => sum + (s.shippingCost || 0), 0);
            },

            get serviceFee() {
                return {{ $SERVICE_FEE }};
            },

            get productSubtotal() {
                return {{ $productSubtotal }};
            },

            get grandTotal() {
                return this.productSubtotal + this.totalShipping + this.serviceFee;
            },

            setCourier(storeId, courierId, cost) {
                this.stores[storeId].courierId  = courierId;
                this.stores[storeId].shippingCost = parseFloat(cost) || 0;
            },

            isValid() {
                if (!this.selectedAddressId) return false;
                if (!this.selectedMethodId) return false;
                const allStorePicked = Object.values(this.stores).every(s => s.courierId !== '');
                return allStorePicked;
            },

            formatRp(n) {
                return 'Rp' + Math.round(n).toLocaleString('id-ID');
            }
        }"
    >
        <h1 class="text-xl font-bold text-gray-800 mb-5">Checkout</h1>

        {{-- PERUBAHAN ADA DI SINI: Form action otomatis menyesuaikan Beli Langsung atau Keranjang --}}
        <form action="{{ isset($isDirect) && $isDirect ? route('buyer.orders.placeOrderDirect') : route('buyer.orders.placeOrder') }}" method="POST" id="checkout-form">
            @csrf

            @if(isset($isDirect) && $isDirect)
                {{-- Hidden: Data untuk Beli Langsung --}}
                <input type="hidden" name="direct_item_id" value="{{ $directItemId }}">
                <input type="hidden" name="direct_qty" value="{{ $directQty }}">
            @else
                {{-- Hidden: cart item ids untuk Keranjang --}}
                @foreach ($cartItemIds as $cid)
                    <input type="hidden" name="cart_item_ids[]" value="{{ $cid }}">
                @endforeach
            @endif

            {{-- Hidden: fields yang nilainya dari Alpine --}}
            <input type="hidden" name="id_address" :value="selectedAddressId">
            <input type="hidden" name="id_method"  :value="selectedMethodId">
            <input type="hidden" name="service_price" value="{{ $SERVICE_FEE }}">

            {{-- Per-store hidden inputs --}}
            @foreach ($groupedItems as $idStore => $items)
                <input type="hidden" name="stores[{{ $idStore }}][id_store]"      value="{{ $idStore }}">
                <input type="hidden" name="stores[{{ $idStore }}][id_courier]"    :value="stores['{{ $idStore }}'].courierId">
                <input type="hidden" name="stores[{{ $idStore }}][shipping_cost]" :value="stores['{{ $idStore }}'].shippingCost">
                <input type="hidden" name="stores[{{ $idStore }}][buyer_note]"    :value="stores['{{ $idStore }}'].buyerNote">
            @endforeach

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-5 items-start">

                {{-- ─── KOLOM KIRI ─── --}}
                <div class="space-y-4">

                    {{-- Alamat Pengiriman --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <h2 class="flex items-center gap-2 text-sm font-semibold text-nusa mb-3">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Alamat Pengiriman
                        </h2>

                        @if ($addresses->isEmpty())
                            <p class="text-sm text-gray-500">Kamu belum punya alamat. <a href="#" class="text-nusa font-medium">Tambah alamat</a></p>
                        @else
                            {{-- Alamat aktif --}}
                            <template x-for="addr in {{ $addresses->toJson() }}" :key="addr.idAddress">
                                <div
                                    x-show="selectedAddressId === addr.idAddress"
                                    class="flex items-start justify-between gap-4"
                                >
                                    <div>
                                        <p class="text-sm font-semibold text-gray-800" x-text="addr.receiver + ' | ' + addr.phone"></p>
                                        <p class="text-sm text-gray-500 mt-0.5" x-text="addr.completeAddress + ', ' + addr.city + ', ' + addr.province + ', ' + addr.postalCode"></p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="document.getElementById('modal-address').showModal()"
                                        class="flex-shrink-0 text-xs font-medium border border-gray-300 text-gray-600 hover:border-nusa hover:text-nusa rounded px-3 py-1 transition">
                                        Ubah
                                    </button>
                                </div>
                            </template>

                            {{-- Modal pilih alamat --}}
                            <dialog id="modal-address" class="rounded-xl shadow-xl p-0 w-full max-w-lg backdrop:bg-black/40">
                                <div class="p-5">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-sm font-semibold text-gray-800">Pilih Alamat</h3>
                                        <button type="button" onclick="document.getElementById('modal-address').close()"
                                            class="text-gray-400 hover:text-gray-600">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="space-y-3 max-h-80 overflow-y-auto">
                                        @foreach ($addresses as $addr)
                                            <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition"
                                                :class="selectedAddressId === '{{ $addr->idAddress }}'
                                                    ? 'border-nusa bg-nusa/5'
                                                    : 'border-gray-200 hover:border-gray-300'">
                                                <input type="radio"
                                                    name="_address_radio"
                                                    value="{{ $addr->idAddress }}"
                                                    x-model="selectedAddressId"
                                                    @change="document.getElementById('modal-address').close()"
                                                    class="mt-0.5 text-nusa focus:ring-nusa">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-800">
                                                        {{ $addr->receiver }} | {{ $addr->phone }}
                                                        @if ($addr->isDefault)
                                                            <span class="ml-1 text-xs font-normal bg-nusa/10 text-nusa rounded px-1.5 py-0.5">Utama</span>
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500 mt-0.5">
                                                        {{ $addr->completeAddress }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postalCode }}
                                                    </p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </dialog>
                        @endif
                    </div>

                    {{-- ─── Item per Toko ─── --}}
                    @foreach ($groupedItems as $idStore => $items)
                        @php
                            $store = $items->first()->productItem->product->store ?? null;
                        @endphp

                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">

                            {{-- Header toko --}}
                            <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                                <span class="text-sm font-semibold text-gray-700">{{ $store->name ?? 'Toko Tidak Diketahui' }}</span>
                            </div>

                            {{-- Item-item --}}
                            <div class="divide-y divide-gray-100">
                                @foreach ($items as $cartItem)
                                    @php
                                        $productItem    = $cartItem->productItem;
                                        $product        = $productItem?->product;
                                        $primaryImage   = $product?->productImages->firstWhere('isPrimary', true)
                                                            ?? $product?->productImages->first();
                                        $variationLabel = $productItem?->productVariations
                                            ->map(fn($v) => $v->value)
                                            ->implode(', ');
                                        $subtotal = ($productItem?->price ?? 0) * $cartItem->quantity;
                                    @endphp

                                    <div class="flex items-center gap-4 px-5 py-4">
                                        <div class="w-14 h-14 rounded-md bg-gray-100 overflow-hidden flex-shrink-0">
                                            @if ($primaryImage)
                                                <img src="{{ asset('storage/' . $primaryImage->imageURL) }}" alt="{{ $product->productName ?? '' }}"
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-800 font-medium truncate">{{ $product->productName ?? 'Produk tidak ditemukan' }}</p>
                                            @if ($variationLabel)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ $variationLabel }}</p>
                                            @endif
                                            <p class="text-xs text-gray-400 mt-0.5">Jumlah: {{ $cartItem->quantity }}</p>
                                        </div>

                                        <div class="text-sm font-semibold text-gray-800 flex-shrink-0">
                                            Rp{{ number_format($subtotal, 0, ',', '.') }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Pesan & Pilih Kurir --}}
                            <div class="px-5 py-4 border-t border-gray-100 flex flex-col sm:flex-row gap-3">

                                {{-- Input pesan untuk penjual --}}
                                <div class="flex-1 relative">
                                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                    <input
                                        type="text"
                                        x-model="stores['{{ $idStore }}'].buyerNote"
                                        placeholder="Beri pesan untuk penjual (Opsional)"
                                        maxlength="500"
                                        class="w-full pl-9 pr-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-nusa focus:border-nusa text-gray-700 placeholder-gray-400">
                                </div>

                                {{-- Dropdown pilih kurir --}}
                                <div class="flex-shrink-0 w-full sm:w-64">
                                    <div class="relative">
                                        <select
                                            class="w-full appearance-none text-sm border border-gray-200 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-1 focus:ring-nusa focus:border-nusa text-gray-700 bg-white"
                                            @change="
                                                const opt = $event.target.selectedOptions[0];
                                                setCourier('{{ $idStore }}', opt.value, opt.dataset.cost)
                                            ">
                                            <option value="" data-cost="0">Pilih pengiriman</option>
                                            @foreach ($couriers as $courier)
                                                <option
                                                    value="{{ $courier->idCourier }}"
                                                    data-cost="{{ $courier->price ?? 0 }}">
                                                    {{ $courier->courierName }} – {{ $courier->serviceType }}
                                                    ({{ $courier->timeEstimation ? 'Est. '.$courier->timeEstimation.' hari' : '' }})
                                                    – Rp{{ number_format($courier->price ?? 0, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <svg class="pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- ─── Metode Pembayaran ─── --}}
                    <div class="bg-white border border-gray-200 rounded-lg p-5">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-semibold text-gray-700">Metode Pembayaran</h2>
                        </div>

                        <div class="space-y-2">
                            @foreach ($methods as $method)
                                <label
                                    class="flex items-center justify-between gap-3 p-3 rounded-lg border cursor-pointer transition"
                                    :class="selectedMethodId === '{{ $method->idMethod }}'
                                        ? 'border-nusa bg-nusa/5'
                                        : 'border-gray-200 hover:border-gray-300'">

                                    <div class="flex items-center gap-3">
                                        {{-- Icon berdasarkan kategori --}}
                                        @if (str_contains(strtolower($method->methodName), 'qris') || str_contains(strtolower($method->category ?? ''), 'digital'))
                                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-sm font-medium text-gray-800">{{ $method->methodName }}</p>
                                            @if ($method->description)
                                                <p class="text-xs text-gray-400">{{ $method->description }}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <input type="radio"
                                        name="_method_radio"
                                        value="{{ $method->idMethod }}"
                                        x-model="selectedMethodId"
                                        class="text-nusa focus:ring-nusa">
                                </label>
                            @endforeach
                        </div>
                    </div>

                </div>

                {{-- ─── KOLOM KANAN: Rincian Pembayaran ─── --}}
                <div class="bg-white border border-gray-200 rounded-lg p-5 sticky top-4 space-y-4">

                    <h3 class="text-sm font-semibold text-gray-700">Rincian Pembayaran</h3>

                    <div class="space-y-2 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Subtotal Produk</span>
                            <span class="font-medium text-gray-800" x-text="formatRp(productSubtotal)"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Subtotal Pengiriman</span>
                            <span class="font-medium text-gray-800" x-text="formatRp(totalShipping)"></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-500">Biaya Layanan</span>
                            <span class="font-medium text-gray-800" x-text="formatRp(serviceFee)"></span>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-3 flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-800">Total Pembayaran</span>
                        <span class="text-base font-bold text-nusa" x-text="formatRp(grandTotal)"></span>
                    </div>

                    {{-- Validasi error --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p>• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <button
                        type="submit"
                        form="checkout-form"
                        :disabled="!isValid()"
                        class="w-full bg-nusa hover:bg-nusa-dark text-white text-sm font-semibold py-2.5 rounded-md transition disabled:opacity-50 disabled:cursor-not-allowed">
                        Beli
                    </button>

                    <p x-show="!isValid()" class="text-xs text-center text-gray-400">
                        Lengkapi alamat, kurir, dan metode pembayaran.
                    </p>
                </div>

            </div>
        </form>
    </div>
</x-dynamic-component>