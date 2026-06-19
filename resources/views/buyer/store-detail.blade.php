@php
    $layout = auth()->check() ? 'buyer-layout' : 'guest-layout';
    
    // Mengecek apakah toko memiliki badge yang disetujui
    $isVerified = $store->badgeVerifications && $store->badgeVerifications->where('status', 'APPROVED')->isNotEmpty();
@endphp

<x-dynamic-component :component="$layout">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        {{-- BREADCRUMB --}}
        <nav class="flex items-center gap-2 text-xs font-medium tracking-wide text-gray-400 uppercase mb-8">
            <a href="{{ route('home') }}" class="hover:text-nusa transition-colors duration-200">Beranda</a>
            <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 font-semibold">Profil Toko</span>
        </nav>

        {{-- HEADER TOKO --}}
        <div class="bg-white rounded-3xl p-6 lg:p-8 border border-gray-100 shadow-sm mb-10 relative overflow-hidden">
            {{-- Elemen Dekorasi Latar Belakang --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-nusa/10 to-transparent rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row gap-6 lg:gap-8 items-start relative z-10">
                {{-- Logo Toko --}}
                <div class="shrink-0 group">
                    @if($store->logoURL)
                        <img src="{{ Storage::url($store->logoURL) }}" alt="{{ $store->name }}" 
                             class="w-28 h-28 lg:w-36 lg:h-36 rounded-2xl object-cover border-4 border-white shadow-md group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-28 h-28 lg:w-36 lg:h-36 rounded-2xl bg-gradient-to-tr from-nusa to-nusa-dark flex items-center justify-center text-white font-black text-5xl border-4 border-white shadow-md group-hover:scale-105 transition-transform duration-300">
                            {{ strtoupper(substr($store->name, 0, 1)) }}
                        </div>
                    @endif
                </div>

                {{-- Detail Informasi Toko --}}
                <div class="flex-grow space-y-4 w-full">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h1 class="text-2xl lg:text-3xl font-extrabold text-gray-900 tracking-tight">{{ $store->name }}</h1>
                                @if($isVerified)
                                    <span class="bg-nusa/10 text-nusa text-[10px] tracking-widest font-bold uppercase px-2.5 py-1 rounded-md border border-nusa/20 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        Verified
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex items-center gap-2 mt-2 text-sm font-medium text-gray-500">
                                <span class="flex items-center gap-1 text-amber-500">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="font-bold text-gray-800">{{ $store->storeRating > 0 ? number_format($store->storeRating, 1) : '-' }}</span>
                                </span>
                                <span>•</span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $store->location ?? 'Lokasi tidak diketahui' }}
                                </span>
                                <span>•</span>
                                <span><strong class="text-gray-800">{{ $store->products->count() }}</strong> Produk</span>
                            </div>
                        </div>

                        {{-- Tombol Aksi (Chat) --}}
                        <div class="shrink-0">
                            @auth
                                @if(auth()->user()->idUser != $store->idSeller)
                                    <form action="{{ route('chat.openWithSeller', $store->idSeller) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-gradient-to-r from-nusa to-nusa-dark text-white rounded-xl font-bold hover:shadow-lg hover:shadow-nusa/20 active:scale-[0.98] transition-all duration-200 text-sm shadow-sm flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                            Chat Toko
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="w-full md:w-auto px-6 py-2.5 border-2 border-nusa text-nusa rounded-xl font-bold hover:bg-nusa/5 transition-all duration-200 text-sm flex items-center justify-center gap-2">
                                    Login untuk Chat
                                </a>
                            @endauth
                        </div>
                    </div>

                    @if($store->description)
                        <div class="pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-600 leading-relaxed max-w-3xl">
                                {{ $store->description }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- DAFTAR PRODUK TOKO --}}
        <div class="mb-6 flex justify-between items-end">
            <div>
                <h2 class="text-xl font-black text-gray-900 tracking-tight">Etalase Toko</h2>
                <p class="text-sm text-gray-500 mt-1">Produk pilihan dari {{ $store->name }}</p>
            </div>
        </div>

        @if($store->products->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                @foreach($store->products as $product)
                    @php
                        // Mengambil harga dari variasi pertama (productItems)
                        $firstItem = $product->productItems->first();
                        $price = $firstItem ? $firstItem->price : 0;
                        
                        // Mengambil gambar pertama
                        $firstImage = $product->productImages->first();
                        $imageURL = $firstImage ? $firstImage->imageURL : null;
                    @endphp
                    
                    {{-- BUNGKUS KESELURUHAN KARTU DENGAN TAG <a> --}}
                    <a href="{{ route('product.detail', $product->idProduct) }}" class="group bg-white rounded-2xl border border-gray-100 overflow-hidden hover:border-nusa/30 hover:shadow-xl hover:shadow-nusa/5 transition-all duration-300 flex flex-col h-full transform hover:-translate-y-1">
                        
                        {{-- Gambar Produk --}}
                        <div class="relative aspect-square bg-gray-50 overflow-hidden">
                            @if($imageURL)
                                <img src="{{ asset($imageURL) }}" alt="{{ $product->productName }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-100">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                        </div>

                        {{-- Info Produk --}}
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-sm font-semibold text-gray-800 leading-tight mb-2 line-clamp-2 group-hover:text-nusa transition-colors">
                                {{ $product->productName }}
                            </h3>
                            
                            <div class="mt-auto space-y-2">
                                <div class="text-base font-black text-gray-900 tracking-tight">
                                    Rp {{ number_format($price, 0, ',', '.') }}
                                </div>
                                
                                <div class="flex items-center gap-2 text-[11px] font-medium text-gray-500">
                                    <div class="flex items-center gap-0.5">
                                        <svg class="w-3 h-3 text-amber-500 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        {{ number_format($product->avgRating, 1) }}
                                    </div>
                                    <span>|</span>
                                    <span>Terjual {{ $product->sold }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            {{-- Empty State jika toko belum punya produk --}}
            <div class="py-16 text-center text-gray-400 bg-white rounded-2xl border border-gray-100 shadow-sm flex flex-col items-center justify-center gap-3">
                <svg class="w-12 h-12 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <div>
                    <h3 class="text-gray-900 font-bold text-lg">Belum Ada Produk</h3>
                    <p class="text-sm mt-1">Toko ini masih merapikan etalasenya. Coba kembali lagi nanti ya!</p>
                </div>
            </div>
        @endif

    </div>
</x-dynamic-component>