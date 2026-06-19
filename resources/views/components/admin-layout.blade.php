<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - NusaMart</title>
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
                    NusaMart <span class="text-sm font-normal text-gray-500">Admin</span>
                </h1>
            </div>

            {{-- Navigasi --}}
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('admin.dashboard') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>

                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('admin.users.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Kelola User
                </a>

                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('admin.stores.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kelola Toko
                </a>

                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('admin.content.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    Moderasi Konten
                </a>

                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('admin.payments.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Monitor Pembayaran
                    @php
                        $pendingPayments = \App\Models\Payment::where('paymentStatus', 'PENDING')->count();
                    @endphp
                    @if($pendingPayments > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingPayments }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.verification.index') }}"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('admin.verifications.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Verifikasi Toko
                    @php
                        $pendingVerif = \App\Models\BadgeVerification::where('status', 'PENDING')->count();
                    @endphp
                    @if($pendingVerif > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingVerif }}
                        </span>
                    @endif
                </a>

                <a href="#"
                    class="flex items-center gap-2 px-4 py-2 rounded-md text-sm {{ request()->routeIs('admin.reports.*') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Laporan & Keluhan
                    @php
                        $openReports = \App\Models\Report::where('status', 'OPEN')->count();
                    @endphp
                    @if($openReports > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $openReports }}
                        </span>
                    @endif
                </a>
            </nav>
        </div>

        {{-- Info admin + Logout --}}
        <div class="p-4 border-t border-gray-200 space-y-2">
            <div class="flex items-center gap-2 px-2">
                <div class="w-8 h-8 rounded-full bg-nusa-light flex items-center justify-center text-nusa font-bold text-sm">
                    {{ strtoupper(substr(auth()->user()->username ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-700">{{ auth()->user()->username ?? 'Admin' }}</p>
                    <p class="text-xs text-gray-400">Administrator</p>
                </div>
            </div>
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
                @yield('page-title', 'Dashboard')
            </h2>
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