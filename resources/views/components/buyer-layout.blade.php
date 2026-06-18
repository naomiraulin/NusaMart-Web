<!DOCTYPE html>
<html lang="id">
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
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- NAVBAR --}}
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between gap-4">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="text-2xl font-bold text-nusa shrink-0">
                NusaMart
            </a>

            {{-- Search bar --}}
            <form action="{{ route('products.search') }}" method="GET" class="flex-1 max-w-xl">
                <div class="flex items-center gap-x-2 w-full">

                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari produk..."
                        class="flex-1 px-4 py-2.5 text-sm border border-gray-300 rounded-full outline-none bg-white focus:border-nusa focus:ring-1 focus:ring-nusa transition">

                    <button type="submit" class="bg-[#008080] hover:bg-nusa-dark px-5 py-2.5 rounded-full text-white transition flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>

                </div>
            </form>

            {{-- Icon actions --}}
            <div class="flex items-center gap-3 shrink-0">

                {{-- Cart --}}
                <a href="{{ route('buyer.cart.index') }}" class="relative p-2 text-gray-600 hover:text-nusa transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    {{-- Badge jumlah item cart --}}
                    @php
                        $cartCount = auth()->check()
                            ? \App\Models\CartItem::whereHas('cart', fn($q) => $q->where('idUser', auth()->user()->idUser))->count()
                            : 0;
                    @endphp
                    @if($cartCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $cartCount > 9 ? '9+' : $cartCount }}
                        </span>
                    @endif
                </a>

                {{-- Notifikasi --}}
                <a href="{{ route('notifications.index') }}" class="relative p-2 text-gray-600 hover:text-nusa transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @php
                        $unreadNotif = auth()->check()
                            ? \App\Models\Notification::where('idUser', auth()->user()->idUser)->where('isRead', false)->count()
                            : 0;
                    @endphp
                    @if($unreadNotif > 0)
                        <span class="absolute -top-0.5 -right-0.5 bg-red-500 text-white text-xs font-bold w-4 h-4 rounded-full flex items-center justify-center">
                            {{ $unreadNotif > 9 ? '9+' : $unreadNotif }}
                        </span>
                    @endif
                </a>

                {{-- Dropdown user --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 p-1 rounded-full hover:bg-gray-100 transition">
                        <div class="w-8 h-8 rounded-full bg-nusa-light flex items-center justify-center text-nusa font-bold text-sm">
                            {{ strtoupper(substr(auth()->user()->username ?? 'U', 0, 1)) }}
                        </div>
                    </button>

                    <div x-show="open" @click.outside="open = false"
                        class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-50">
                        <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Profil Saya</a>
                        <a href="{{ route('buyer.orders.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Pesanan Saya</a>
                        <a href="{{ route('chat.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Chat</a>
                        <hr class="my-1 border-gray-100">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </nav>

    @if(session('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition.opacity
            class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-green-600 text-white text-sm rounded-lg shadow-lg px-4 py-3 flex items-center gap-4 max-w-md w-[90%]"
        >
            <span class="flex-1">{{ session('success') }}</span>
            <button @click="show = false" class="text-green-200 hover:text-white flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000)"
            x-transition.opacity
            class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-red-600 text-white text-sm rounded-lg shadow-lg px-4 py-3 flex items-center gap-4 max-w-md w-[90%]"
        >
            <span class="flex-1">{{ session('error') }}</span>
            <button @click="show = false" class="text-red-200 hover:text-white flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- KONTEN --}}
    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-6">
        {{ $slot }}
    </main>

    {{-- FOOTER --}}
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto px-6 py-6 flex flex-col md:flex-row items-center justify-between gap-2 text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} NusaMart. All rights reserved.</p>
            <div class="flex gap-4">
                <a href="#" class="hover:text-nusa transition">Tentang Kami</a>
                <a href="#" class="hover:text-nusa transition">Kebijakan Privasi</a>
                <a href="#" class="hover:text-nusa transition">Bantuan</a>
            </div>
        </div>
    </footer>

    {{-- Alpine.js untuk dropdown & toast --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

</body>
</html>