<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Center - NusaMart</title>
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
<body class="bg-gray-50 flex h-screen overflow-hidden text-gray-800">

    {{-- SIDEBAR --}}
    <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
        <div>
            {{-- Logo --}}
            <div class="h-16 flex items-center justify-center border-b border-gray-200">
                <h1 class="text-2xl font-bold text-nusa">
                    NusaMart <span class="text-sm font-normal text-gray-500">Seller</span>
                </h1>
            </div>

            {{-- Info toko --}}
            @if(auth()->check() && auth()->user()->seller?->store)
                @php $store = auth()->user()->seller->store; @endphp
                <div class="px-4 py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        @if($store->logoURL)
                            <img src="{{ Storage::url($store->logoURL) }}" class="w-9 h-9 rounded-full object-cover">
                        @else
                            <div class="w-9 h-9 rounded-full bg-nusa-light flex items-center justify-center text-nusa font-bold text-sm">
                                {{ strtoupper(substr($store->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="text-sm font-medium text-gray-800 truncate w-36">{{ $store->name }}</p>
                            <p class="text-xs text-gray-400">{{ $store->isActive ? 'Aktif' : 'Nonaktif' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Navigasi --}}
            <nav class="p-4 space-y-1">
                <a href="{{ route('seller.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('seller.dashboard') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="{{ route('seller.store.show') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('seller.store.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Toko Saya
                </a>

                <a href="{{ route('seller.products.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('seller.products.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Kelola Produk
                </a>

                <a href="{{ route('seller.orders.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('seller.orders.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Pesanan Masuk
                    {{-- Badge order pending --}}
                    @php
                        $pendingCount = auth()->check() && isset($store)
                            ? \App\Models\Order::where('idStore', $store->idStore)->where('orderStatus', 'PENDING')->count()
                            : 0;
                    @endphp
                    @if($pendingCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('seller.wallet.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('seller.wallet.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Wallet
                </a>

                <a href="{{ route('chat.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('chat.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 3H3v13h5l4 5 4-5h5V3z"/></svg>
                    Chat
                </a>

                <a href="{{ route('notifications.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('notifications.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Notifikasi
                </a>

                <a href="{{ route('profile.show') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('profile.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil
                </a>
            </nav>
        </div>

        {{-- Logout --}}
        <div class="p-4 border-t border-gray-200">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2 px-4 py-2 rounded-md text-red-600 hover:bg-red-50 font-medium text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">

        {{-- Header atas --}}
        <div class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8">
            <h2 class="text-lg font-semibold text-gray-700">
                {{ isset($pageTitle) ? $pageTitle : 'Dashboard' }}
            </h2>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                {{ auth()->user()->username ?? '' }}
            </div>
        </div>

        {{-- Flash message --}}
        @if(session('success'))
            <div class="mx-8 mt-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-md text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-8 mt-4 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-md text-sm">
                {{ session('error') }}
            </div>
        @endif

        <div class="p-8">
            {{ $slot }}
        </div>
    </main>

</body>
</html>