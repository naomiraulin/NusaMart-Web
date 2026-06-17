<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - NusaMart</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes blob {
            0%   { transform: translate(0px, 0px) scale(1); }
            33%  { transform: translate(30px, -50px) scale(1.1); }
            66%  { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col antialiased">

    {{-- ====== HEADER ====== --}}
    <header class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-8 h-16 flex items-center justify-center">
            {{-- Ganti dengan:
                <img src="{{ asset('images/logo.png') }}" alt="NusaMart" class="h-8 object-contain">
            --}}
            <a href="{{ route('home') }}" class="text-2xl font-bold text-nusa tracking-tight">NusaMart</a>
        </div>
    </header>

    {{-- ====== BACKGROUND BLOBS ====== --}}
    <div class="fixed inset-0 flex justify-center items-center opacity-40 pointer-events-none z-0">
        <div class="absolute top-1/4 left-1/4 w-72 h-72 bg-teal-300 rounded-full mix-blend-multiply filter blur-2xl animate-blob"></div>
        <div class="absolute top-1/4 right-1/4 w-72 h-72 bg-nusa-light rounded-full mix-blend-multiply filter blur-2xl animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/3 w-72 h-72 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl animate-blob animation-delay-4000"></div>
    </div>

    {{-- ====== MAIN CONTENT ====== --}}
    <main class="relative z-10 flex-1 max-w-7xl w-full mx-auto px-8 py-12 flex items-center justify-between gap-12">

        {{-- ===== KOLOM KIRI: Ilustrasi & Copy ===== --}}
        <div class="hidden lg:flex flex-col justify-center flex-1 sticky top-24">
            <div class="mb-8">
                <span class="text-xs font-semibold tracking-widest text-nusa uppercase">Marketplace Lokal #1</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-3 leading-snug">
                    Selamat Datang!
                </h2>
                <p class="text-gray-500 mt-4 text-base leading-relaxed max-w-xs">
                    Masuk dan temukan ribuan produk lokal pilihan.
                </p>
            </div>

            {{-- Ganti dengan: <img src="{{ asset('images/ilustrasi-login.svg') }}" ...> --}}
            <img
                src="https://cdn.jsdelivr.net/gh/twitter/twemoji@14.0.2/assets/svg/1f6d2.svg"
                alt="Ilustrasi Belanja"
                class="w-64 h-64 object-contain opacity-80"
            >

            <div class="mt-8 flex gap-6 text-sm text-gray-400">
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nusa" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Gratis Daftar
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nusa" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Transaksi Aman
                </div>
                <div class="flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-nusa" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Produk Lokal Asli
                </div>
            </div>
        </div>

        {{-- ===== KOLOM KANAN: Form Login ===== --}}
        <div class="w-full lg:w-[480px] flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

                <div class="mb-7">
                    <h1 class="text-2xl font-bold text-gray-900">Login</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-nusa font-semibold hover:underline">Daftar di sini</a>
                    </p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 p-3.5 rounded-lg text-sm mb-6">
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Username atau Email</label>
                        <input
                            type="text"
                            name="emailOrUsername"
                            value="{{ old('emailOrUsername') }}"
                            placeholder="contoh: budi_santoso"
                            required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <a href="#" class="text-xs text-nusa hover:underline">Lupa password?</a>
                        </div>
                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan password"
                            required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white"
                        >
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full bg-nusa hover:bg-nusa-dark text-white font-semibold py-3 rounded-xl transition-colors duration-200 text-sm tracking-wide"
                        >
                            Masuk
                        </button>
                    </div>
                </form>

            </div>

            <p class="text-center text-xs text-gray-400 mt-6">© NusaMart 2026 · Platform Produk Lokal Indonesia</p>
        </div>

    </main>
</body>
</html>