<x-admin-layout>
    <x-slot name="pageTitle">Dashboard Utama</x-slot>

    <div class="p-6 max-w-7xl mx-auto space-y-6">

        {{-- Welcome Banner --}}
        <div class="relative bg-gradient-to-r from-nusa to-nusa-dark rounded-3xl p-8 sm:p-10 text-white overflow-hidden shadow-lg shadow-nusa/20">
            {{-- Dekorasi Abstrak Latar Belakang --}}
            <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="absolute bottom-0 right-32 -mb-16 w-40 h-40 rounded-full bg-white/10 blur-2xl"></div>

            <div class="relative z-10 max-w-2xl">
                <h2 class="text-3xl sm:text-4xl font-black mb-3">Selamat Datang, Admin!</h2>
                <p class="text-nusa-light text-base sm:text-lg mb-8 leading-relaxed">
                    Pusat kendali NusaMart ada di tanganmu. Pantau aktivitas pengguna, setujui verifikasi toko lokal, dan pastikan ekosistem platform berjalan dengan lancar hari ini.
                </p>
                <a href="{{ route('admin.verification.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-nusa hover:bg-gray-50 text-sm font-bold rounded-xl transition-all duration-200 shadow-sm active:scale-95 group">
                    <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                    Tinjau Verifikasi Toko
                </a>
            </div>
        </div>

        {{-- Quick Stats (Kartu Ringkasan) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Card 1: Total Pengguna (Placeholder) --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Pengguna</p>
                    <h3 class="text-2xl font-black text-gray-800">
                        {{ \App\Models\User::count() ?? 0 }}
                    </h3>
                </div>
            </div>

            {{-- Card 2: Toko Terdaftar (Placeholder) --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                <div class="w-14 h-14 rounded-full bg-nusa/10 text-nusa flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Toko Terdaftar</p>
                    <h3 class="text-2xl font-black text-gray-800">
                        {{ \App\Models\Store::count() ?? 0 }}
                    </h3>
                </div>
            </div>

            {{-- Card 3: Menunggu Verifikasi --}}
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
                @php
                    $pendingVerif = \App\Models\BadgeVerification::where('status', 'PENDING')->count();
                @endphp
                <div class="w-14 h-14 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Menunggu Verifikasi</p>
                    <h3 class="text-2xl font-black text-gray-800">{{ $pendingVerif }}</h3>
                </div>
            </div>

        </div>

    </div>
</x-admin-layout>