@php
    $layout = auth()->check() ? 'buyer-layout' : 'guest-layout';
    
    $hasVariations = $product->productItems->count() > 1 || 
                     ($product->productItems->count() == 1 && 
                      $product->productItems->first()->productVariations
                          ->filter(fn($v) => !empty(trim($v->value)) && strtolower(trim($v->value)) !== 'default')
                          ->isNotEmpty());

    $isStoreVerified = $product->store && $product->store->badgeVerifications && $product->store->badgeVerifications->where('status', 'APPROVED')->isNotEmpty();
@endphp

<x-dynamic-component :component="$layout">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
         x-data="{
            mainImage: '{{ $product->productImages->first() ? asset($product->productImages->first()->imageURL) : 'https://placehold.co/600x600?text=No+Image' }}',
            
            items: {{ 
                $product->productItems->map(fn($item) => [
                    'id'       => $item->idItem,
                    'price'    => (float) $item->price,
                    'stock'    => $item->stock ?? 0,
                    'label'    => $item->productVariations->map(fn($v) => $v->value)->filter()->implode(' / ') ?: 'Default',
                    'imageURL' => $item->imageURL ?? null,
                ])->toJson()
            }},
            
            selectedItem: null,
            qty: 1,

            init() {
                if (this.items.length > 0) {
                    this.selectedItem = this.items[0];
                }
            },

            selectVariation(item) {
                this.selectedItem = item;
                this.qty = 1;
                if (item.imageURL) {
                    this.mainImage = item.imageURL;
                }
            }
         }">

        {{-- BREADCRUMB --}}
        <nav class="flex items-center gap-2 text-xs font-medium tracking-wide text-gray-400 uppercase mb-8">
            <a href="{{ route('home') }}" class="hover:text-nusa transition-colors duration-200">Beranda</a>
            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            @if($product->subCategories->isNotEmpty())
                <span class="text-gray-600 font-semibold">{{ $product->subCategories->first()->category->categoryName ?? 'Kategori' }}</span>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            @endif
            <span class="text-gray-400 truncate max-w-[200px]">{{ $product->productName }}</span>
        </nav>

        {{-- MAIN PRODUCT SECTION --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

            {{-- KIRI: GALERI GAMBAR --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="relative aspect-square rounded-2xl overflow-hidden bg-white border border-gray-100 shadow-sm group">
                    <img :src="mainImage" alt="{{ $product->productName }}"
                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                </div>

                <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-none">
                    @foreach($product->productImages as $image)
                        <button @click="mainImage = '{{ asset($image->imageURL) }}'"
                                :class="mainImage === '{{ asset($image->imageURL) }}' ? 'border-nusa ring-2 ring-nusa/20 scale-95' : 'border-gray-200 opacity-70 hover:opacity-100'"
                                class="w-20 h-20 shrink-0 rounded-xl overflow-hidden border-2 bg-white transition-all duration-300 focus:outline-none transform">
                            <img src="{{ asset($image->imageURL) }}" alt="Thumbnail" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- TENGAH: INFO DETAIL PRODUK --}}
            <div class="lg:col-span-4 space-y-6">
                <div>
                    <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight leading-tight">
                        {{ $product->productName }}
                    </h1>

                    <div class="flex items-center gap-3 mt-4 text-xs font-medium text-gray-500">
                        <div class="flex items-center gap-1 bg-amber-50 text-amber-700 px-2 py-1 rounded-md border border-amber-100">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span class="font-bold">{{ number_format($product->avgRating, 1) }}</span>
                        </div>
                        <span>•</span>
                        <span>Terjual <strong class="text-gray-800">{{ $product->sold }}</strong></span>
                        <span>•</span>
                        <span>Berat <strong class="text-gray-800">{{ $product->weightGram }}g</strong></span>
                    </div>
                </div>

                <div class="bg-gray-50/50 rounded-2xl p-5 border border-gray-100">
                    <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">Harga</p>
                    <div class="text-3xl font-black text-gray-900 tracking-tight"
                         x-text="selectedItem ? 'Rp' + new Intl.NumberFormat('id-ID').format(selectedItem.price) : 'Rp 0'">
                    </div>
                </div>

                {{-- VARIANT CHIPS --}}
                @if($hasVariations)
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Pilih Variasi</label>
                        <div class="flex flex-wrap gap-2.5">
                            <template x-for="item in items" :key="item.id">
                                <button @click="selectVariation(item)"
                                        :class="selectedItem && selectedItem.id === item.id ? 'border-nusa bg-nusa/5 text-nusa font-bold shadow-sm shadow-nusa/10' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-400'"
                                        class="px-4 py-2.5 text-sm rounded-xl border-2 transition-all duration-200 focus:outline-none tracking-wide">
                                    <span x-text="item.label"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                @endif

                <div class="space-y-3 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Deskripsi Produk</h3>
                    <p class="text-gray-600 text-sm leading-relaxed whitespace-pre-line font-normal">
                        {{ $product->description ?? 'Tidak ada deskripsi untuk produk ini.' }}
                    </p>
                </div>

                {{-- INFO TOKO & TOMBOL CHAT --}}
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="flex flex-col items-center gap-1.5 shrink-0">
                            <a href="{{ route('store.detail', $product->store->idStore ?? '') }}" class="group">
                                <div class="w-12 h-12 bg-gradient-to-tr from-nusa to-nusa-dark text-white rounded-xl flex items-center justify-center font-bold text-lg shadow-sm group-hover:scale-105 transition-transform">
                                    {{ strtoupper(substr($product->store->name ?? 'T', 0, 1)) }}
                                </div>
                            </a>

                            @if($isStoreVerified)
                                <span class="bg-nusa/10 text-nusa text-[8px] tracking-widest font-bold uppercase px-1.5 py-0.5 rounded-md flex items-center gap-0.5 border border-nusa/20 whitespace-nowrap">
                                    <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                    Verified Local
                                </span>
                            @endif
                        </div>

                        <div>
                            <a href="{{ route('store.detail', $product->store->idStore ?? '') }}" class="hover:underline decoration-nusa decoration-2 underline-offset-2">
                                <h4 class="font-bold text-gray-900 text-sm leading-tight hover:text-nusa transition-colors">{{ $product->store->name ?? 'Toko Tidak Ditemukan' }}</h4>
                            </a>
                            <p class="text-xs text-gray-400 font-medium mt-0.5">{{ $product->store->location ?? 'Lokasi tidak diketahui' }}</p>
                        </div>
                    </div>

                    {{-- TOMBOL CHAT --}}
                    @auth
                        @if($product->store && auth()->user()->idUser != $product->store->idSeller)
                            <form action="{{ route('chat.openWithSeller', $product->store->idSeller) }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->idProduct }}">
                                <button type="submit" class="px-3.5 py-2 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-nusa hover:border-nusa/30 transition-all duration-200 font-semibold text-xs flex items-center gap-2 shadow-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                    Chat
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-2 border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 hover:text-nusa hover:border-nusa/30 transition-all duration-200 font-semibold text-xs flex items-center gap-2 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            Chat
                        </a>
                    @endauth
                </div>
            </div>

            {{-- KANAN: ACTION BOX & KERANJANG --}}
            <div class="lg:col-span-3 lg:sticky lg:top-24">
                <div class="border border-gray-100 rounded-2xl p-6 shadow-md shadow-gray-100/50 bg-white space-y-6">
                    <h3 class="font-bold text-gray-900 text-sm uppercase tracking-wider">Atur Pembelian</h3>

                    <div class="space-y-2">
                        <label class="text-xs font-semibold text-gray-400">Jumlah Pesanan</label>
                        <div class="flex items-center gap-3">
                            <div class="flex items-center border border-gray-200 rounded-xl bg-gray-50/50 p-1">
                                <button @click="if(qty > 1) qty--" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-white hover:text-nusa rounded-lg transition-all font-bold text-lg focus:outline-none">-</button>
                                <input type="number" x-model="qty" min="1" class="w-10 text-center bg-transparent border-none focus:ring-0 appearance-none text-sm font-bold text-gray-800" readonly>
                                <button @click="if(selectedItem && qty < selectedItem.stock) qty++" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-white hover:text-nusa rounded-lg transition-all font-bold text-lg focus:outline-none">+</button>
                            </div>
                            <div class="text-xs text-gray-400 font-medium">
                                Stok: <span class="font-bold text-gray-700" x-text="selectedItem ? selectedItem.stock : 0"></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-end pt-4 border-t border-gray-100">
                        <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Subtotal</span>
                        <span class="text-xl font-black text-nusa tracking-tight"
                              x-text="selectedItem ? 'Rp' + new Intl.NumberFormat('id-ID').format(qty * selectedItem.price) : 'Rp 0'">
                        </span>
                    </div>

                    <div class="space-y-2.5 pt-2">
                        @auth
                            <form action="{{ route('buyer.cart.add') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="item_id" :value="selectedItem ? selectedItem.id : ''">
                                <input type="hidden" name="quantity" :value="qty">
                                <button type="submit" :disabled="!selectedItem" class="w-full py-3 px-4 bg-white border-2 border-nusa text-nusa rounded-xl font-bold hover:bg-nusa/5 transition-all duration-200 text-sm flex justify-center items-center gap-2 shadow-sm disabled:opacity-50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    + Keranjang
                                </button>
                            </form>

                            <form action="{{ route('buyer.orders.directCheckout') }}" method="POST" class="w-full">
                                @csrf
                                <input type="hidden" name="item_id" :value="selectedItem ? selectedItem.id : ''">
                                <input type="hidden" name="quantity" :value="qty">
                                <button type="submit" :disabled="!selectedItem" class="w-full py-3 px-4 bg-gradient-to-r from-nusa to-nusa-dark text-white rounded-xl font-bold hover:shadow-lg hover:shadow-nusa/20 active:scale-[0.98] transition-all duration-200 text-sm shadow-sm disabled:opacity-50">
                                    Beli Langsung
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block w-full py-3 text-center bg-gradient-to-r from-nusa to-nusa-dark text-white rounded-xl font-bold hover:shadow-lg hover:shadow-nusa/20 transition-all duration-200 text-sm shadow-sm">
                                Masuk untuk Membeli
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        {{-- REVIEWS SECTION --}}
        <div class="mt-20 border-t border-gray-100 pt-12">
            <div class="flex items-center gap-3 mb-8">
                <h2 class="text-xl font-black text-gray-900 tracking-tight">Ulasan Pembeli</h2>
                <span class="bg-gray-100 text-gray-600 font-bold px-2.5 py-0.5 text-xs rounded-full">{{ $reviews->count() ?? 0 }}</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($reviews as $review)
                    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200/60 transition-all duration-200 space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="flex gap-0.5 text-amber-400">
                                @for($i = 0; $i < $review->rating; $i++)
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                @endfor
                            </div>
                            <span class="text-[11px] font-semibold text-gray-400">Terverifikasi</span>
                        </div>
                        <p class="text-sm text-gray-600 leading-relaxed font-normal italic">
                            "{{ $review->comment }}"
                        </p>
                        <div class="flex items-center gap-2 pt-2">
                            <div class="w-5 h-5 rounded-full bg-gradient-to-tr from-gray-200 to-gray-300 flex items-center justify-center text-[9px] font-bold text-gray-600">
                                {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-xs text-gray-500 font-bold">{{ $review->user->name ?? 'Anonim' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center text-gray-400 bg-gray-50/50 rounded-2xl border-2 border-gray-100 border-dashed flex flex-col items-center justify-center gap-2">
                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                        <p class="text-sm font-semibold">Belum ada ulasan untuk produk ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-dynamic-component>