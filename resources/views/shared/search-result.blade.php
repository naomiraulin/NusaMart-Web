@php
    // Logika layout dinamis yang sama seperti di homepage
    $layout = auth()->check() ? 'buyer-layout' : 'guest-layout';
@endphp

<x-dynamic-component :component="$layout">

    {{-- HEADER HASIL PENCARIAN --}}
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-gray-900">
                Hasil Pencarian: <span class="text-nusa font-bold">"{{ $search }}"</span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Menemukan {{ $products->total() ?? $products->count() }} produk yang sesuai.
            </p>
        </div>
        <a href="{{ route('home') }}" class="text-sm text-nusa hover:text-nusa-dark font-medium transition">
            &larr; Kembali
        </a>
    </div>

    {{-- GRID PRODUK --}}
    @if($products->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p class="text-gray-800 font-semibold text-lg">Produk "{{ $search }}" tidak ditemukan</p>
            <p class="text-sm text-gray-500 mt-1">Coba gunakan kata kunci lain yang lebih umum atau periksa ejaanmu.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
            @foreach($products as $product)
                @php
                    $primaryImage = $product->productImages->firstWhere('isPrimary', true)
                                    ?? $product->productImages->first();
                    $cheapestPrice = $product->productItems->min('price');
                @endphp

                <a href="{{ route('product.detail', $product->idProduct) }}" class="group bg-white p-3 rounded-xl border border-gray-100 hover:shadow-md transition-all duration-300 flex flex-col h-full">
                    <div class="aspect-square w-full rounded-lg overflow-hidden bg-gray-100 mb-3 relative shrink-0">
                        @if($primaryImage)
                            <img src="{{ $primaryImage->imageURL }}" alt="{{ $product->productName }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>

                    <div class="flex flex-col flex-grow">
                        <p class="text-sm text-gray-700 leading-snug line-clamp-2 group-hover:text-nusa transition-colors">
                            {{ $product->productName }}
                        </p>

                        <div class="mt-auto pt-2">
                            @if($cheapestPrice !== null)
                                <p class="font-bold text-nusa">Rp{{ number_format($cheapestPrice, 0, ',', '.') }}</p>
                            @endif

                            <div class="flex items-center gap-1 mt-1 text-gray-400">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-xs truncate">{{ $product->store->city ?? 'Lokasi tidak diketahui' }}</p>
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- PAGINATION --}}
        @if(method_exists($products, 'links'))
            <div class="mt-10">
                {{ $products->appends(['search' => $search])->links() }}
            </div>
        @endif
    @endif

</x-dynamic-component>