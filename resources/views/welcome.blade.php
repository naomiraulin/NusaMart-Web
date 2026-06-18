@php
    // Homepage ini dipakai baik untuk BUYER (sudah login) maupun GUEST (belum login).
    // Layout-nya beda: buyer-layout punya navbar dengan cart/notif/dropdown user,
    // guest-layout cuma punya tombol Masuk/Daftar. Seller tidak lewat sini sama sekali
    // (punya dashboard sendiri di route /seller/... dengan seller-layout).
    $layout = auth()->check() ? 'buyer-layout' : 'guest-layout';
@endphp

<x-dynamic-component :component="$layout">

    {{-- BANNER PROMOSI --}}
    <div class="bg-nusa-light rounded-2xl px-10 py-12 mb-10 flex items-center justify-between overflow-hidden">
        <div>
            <h1 class="text-4xl font-extrabold text-nusa-dark mb-2">Beli Produk Lokal di NusaMart!</h1>
            <p class="text-lg text-gray-500">Pilihan lengkap dan hemat</p>
        </div>
        <img src="{{ asset('images/banner-illustration.png') }}" alt="Ilustrasi belanja online" class="w-80 h-auto object-contain hidden md:block">
    </div>

    {{-- GRID PRODUK --}}
    @if($products->isEmpty())
        <p class="text-center text-gray-400 py-16">Belum ada produk yang tersedia saat ini.</p>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5">
            @foreach($products as $product)
                @php
                    $primaryImage = $product->productImages->firstWhere('isPrimary', true)
                                    ?? $product->productImages->first();
                    $cheapestPrice = $product->productItems->min('price');
                @endphp

                <a href="{{ route('product.detail', $product->idProduct) }}" class="group">
                    <div class="aspect-square w-full rounded-lg overflow-hidden bg-gray-100 mb-2">
                        @if($primaryImage)
                            <img src="{{ $primaryImage->imageURL }}" alt="{{ $product->productName }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300 text-xs">
                                Tidak ada gambar
                            </div>
                        @endif
                    </div>

                    <p class="text-sm text-gray-700 leading-snug line-clamp-2">{{ $product->productName }}</p>

                    @if($cheapestPrice !== null)
                        <p class="font-bold text-nusa mt-1">Rp{{ number_format($cheapestPrice, 0, ',', '.') }}</p>
                    @endif

                    <p class="text-xs text-gray-400 mt-0.5">{{ $product->store->city ?? '' }}</p>
                </a>
            @endforeach
        </div>
    @endif

</x-dynamic-component>