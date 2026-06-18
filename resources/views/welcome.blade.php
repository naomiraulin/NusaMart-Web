<!DOCTYPE html>
<html lang="id">
{{--
    Halaman ini khusus untuk BUYER (sudah login) dan GUEST (belum login).
    Seller punya dashboard sendiri di route /seller/... yang terpisah dari file ini.
--}}
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NusaMart</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        nusa: {
                            light: '#E0F2F1',
                            DEFAULT: '#008B81',
                            dark: '#00736B',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white min-h-screen font-sans text-gray-800">

    {{-- HEADER --}}
    <header class="w-full border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between gap-6">

            <a href="{{ route('home') }}" class="text-2xl font-extrabold text-nusa shrink-0">NusaMart</a>

            {{-- SEARCH BAR --}}
            {{-- TODO: belum ada route pencarian di routes/web.php, action diarahkan ke '#' sementara --}}
            <form action="#" method="GET" class="flex-1 max-w-2xl">
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="q" placeholder="Search"
                        class="w-full border border-nusa/30 rounded-lg pl-10 pr-4 py-2 text-sm focus:border-nusa focus:outline-none">
                </div>
            </form>

            {{-- AKSI KANAN --}}
            <div class="flex items-center gap-5 shrink-0">
                @auth
                    {{-- TODO: belum ada route cart/notifikasi/profile di routes/web.php, href diarahkan ke '#' sementara --}}
                    <a href="#" class="text-gray-600 hover:text-nusa transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.5 5M16 21a1 1 0 100-2 1 1 0 000 2zM8 21a1 1 0 100-2 1 1 0 000 2z" />
                        </svg>
                    </a>
                    <a href="#" class="text-gray-600 hover:text-nusa transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0a3 3 0 11-6 0" />
                        </svg>
                    </a>
                    <div class="h-6 w-px bg-gray-200"></div>

                    {{-- DROPDOWN PROFIL: trigger via hover (CSS group), berisi logout --}}
                    <div class="relative group">
                        <button type="button" class="flex items-center gap-2 text-gray-700 hover:text-nusa transition cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 12a4 4 0 100-8 4 4 0 000 8zM6 20a6 6 0 1112 0" />
                            </svg>
                            <span class="text-sm font-medium">{{ auth()->user()->username }}</span>
                        </button>

                        {{-- Jembatan tak terlihat supaya hover tidak putus saat kursor pindah dari tombol ke menu --}}
                        <div class="absolute right-0 top-full h-2 w-full"></div>

                        <div class="absolute right-0 top-full mt-2 w-48 bg-white border border-gray-100 rounded-lg shadow-lg py-2
                                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-opacity duration-150 z-50">

                            {{-- TODO: belum ada route profile, href diarahkan ke '#' sementara --}}
                            <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-nusa transition">
                                Profil Saya
                            </a>

                            {{-- TODO: belum ada route riwayat pesanan, href diarahkan ke '#' sementara --}}
                            <a href="#" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-nusa transition">
                                Pesanan Saya
                            </a>

                            <hr class="my-1 border-gray-100">

                            {{-- Logout wajib pakai form POST, sesuai route POST /logout di routes/web.php --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition cursor-pointer">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-nusa hover:text-nusa-dark transition">Log In</a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold bg-nusa text-white px-4 py-2 rounded-md hover:bg-nusa-dark transition">Daftar</a>
                @endauth
            </div>

        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-8">

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

    </main>

</body>
</html>