<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - NusaMart</title>
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
                            muted: '#B2DFDB',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col font-sans antialiased">

    {{-- ====== HEADER ====== --}}
    <header class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-8 h-16 flex items-center justify-center">
            {{-- Ganti dengan:
                <img src="{{ asset('images/logo.png') }}"
                     alt="NusaMart"
                     class="h-8 object-contain">
            --}}
            <a href="/" class="text-2xl font-bold text-nusa tracking-tight">NusaMart</a>
        </div>
    </header>

    {{-- ====== MAIN CONTENT ====== --}}
    <main class="flex-1 max-w-7xl w-full mx-auto px-8 py-12 flex items-start justify-between gap-12">

        {{-- ===== KOLOM KIRI: Ilustrasi & Copy ===== --}}
        <div class="hidden lg:flex flex-col justify-center flex-1 pt-8 sticky top-24">
            <div class="mb-8">
                <span class="text-xs font-semibold tracking-widest text-nusa uppercase">Marketplace Lokal #1</span>
                <h2 class="text-4xl font-bold text-gray-900 mt-3 leading-snug">
                    Dukung Produk<br>Lokal Kebanggaanmu!
                </h2>
                <p class="text-gray-500 mt-4 text-base leading-relaxed max-w-xs">
                    Bergabung dengan ribuan pembeli dan penjual yang memperkuat ekonomi lokal Indonesia.
                </p>
            </div>

            {{-- Ganti dengan: <img src="{{ asset('images/ilustrasi-register.svg') }}" ...> --}}
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

        {{-- ===== KOLOM KANAN: Form ===== --}}
        <div class="w-full lg:w-[480px] flex-shrink-0">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

                <div class="mb-7">
                    <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-nusa font-semibold hover:underline">Login</a>
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

                {{-- ====== ROLE TOGGLE ====== --}}
                <div class="mb-6">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Daftar sebagai</p>
                    <div class="grid grid-cols-2 gap-2 bg-gray-100 rounded-xl p-1">
                        <button type="button" id="tab-buyer" onclick="switchRole('BUYER')"
                            class="role-tab flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 bg-white text-nusa shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Pembeli (Buyer)
                        </button>
                        <button type="button" id="tab-seller" onclick="switchRole('SELLER')"
                            class="role-tab flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition-all duration-200 text-gray-500 hover:text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            Penjual (Seller)
                        </button>
                    </div>
                </div>

                {{-- ====== FORM ====== --}}
                <form id="registerForm" action="{{ route('register') }}" method="POST" class="space-y-4" novalidate>
                    @csrf
                    <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'BUYER') }}">

                    <div class="input-wrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="contoh: budi_santoso" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white">
                        <p class="error-text hidden text-red-500 text-xs mt-1 font-medium">Username wajib diisi.</p>
                    </div>

                    <div class="input-wrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="email@contoh.com" required
                            class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white">
                        <p class="error-text hidden text-red-500 text-xs mt-1 font-medium">Email wajib diisi.</p>
                    </div>

                    <div class="input-wrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor HP <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <span class="flex items-center px-3 bg-gray-100 border border-gray-200 rounded-lg text-sm text-gray-500 font-medium">+62</span>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="812-3456-7890" required
                                class="flex-1 border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white">
                        </div>
                        <p class="error-text hidden text-red-500 text-xs mt-1 font-medium">Nomor HP wajib diisi.</p>
                    </div>

                    <div class="input-wrapper">
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <div class="relative w-full">
                            <input type="password" id="passwordInput" name="password" placeholder="Minimal 8 karakter" required
                                class="w-full border border-gray-200 rounded-lg px-4 py-2.5 pr-12 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white">
                            <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-nusa-dark focus:outline-none z-10 cursor-pointer">
                                <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        <p class="error-text hidden text-red-500 text-xs mt-1 font-medium">Password wajib diisi.</p>
                    </div>

                    {{-- ====== SELLER FIELDS ====== --}}
                    <div id="sellerFields" class="hidden">
                        <div class="border-t border-dashed border-gray-200 my-5"></div>
                        <div class="flex items-center gap-2 mb-4">
                            <div class="h-px flex-1 bg-gray-200"></div>
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Toko</span>
                            <div class="h-px flex-1 bg-gray-200"></div>
                        </div>
                        <div class="space-y-4">
                            <div class="input-wrapper">
                                <label class="block text-sm font-medium text-gray-700 mb-1.5">NIK <span class="text-gray-400 font-normal">(16 digit)</span> <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" value="{{ old('nik') }}" placeholder="3271xxxxxxxxxxxx" maxlength="16"
                                    class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white">
                                <p class="error-text hidden text-red-500 text-xs mt-1 font-medium">NIK wajib diisi.</p>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="input-wrapper">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Bank <span class="text-red-500">*</span></label>
                                    <input type="text" name="bankName" value="{{ old('bankName') }}" placeholder="BCA, Mandiri..."
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white">
                                    <p class="error-text hidden text-red-500 text-xs mt-1 font-medium">Bank wajib diisi.</p>
                                </div>
                                <div class="input-wrapper">
                                    <label class="block text-sm font-medium text-gray-700 mb-1.5">No. Rekening <span class="text-red-500">*</span></label>
                                    <input type="text" name="accountNumber" value="{{ old('accountNumber') }}" placeholder="0123456789"
                                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:ring-2 focus:ring-nusa/30 focus:border-nusa outline-none transition bg-gray-50 focus:bg-white">
                                    <p class="error-text hidden text-red-500 text-xs mt-1 font-medium">Rekening wajib diisi.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-nusa hover:bg-nusa-dark text-white font-semibold py-3 rounded-xl transition-colors duration-200 text-sm tracking-wide">
                            Buat Akun
                        </button>
                    </div>
                </form>

            </div>

            <p class="text-center text-xs text-gray-400 mt-6">© NusaMart 2026 · Platform Produk Lokal Indonesia</p>
        </div>

    </main>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');

            console.log("Tombol mata diklik! Tipe saat ini:", passwordInput.type);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        }

        function switchRole(role) {
            const roleInput    = document.getElementById('roleInput');
            const sellerFields = document.getElementById('sellerFields');
            const tabBuyer     = document.getElementById('tab-buyer');
            const tabSeller    = document.getElementById('tab-seller');
            const sellerInputs = sellerFields.querySelectorAll('input');

            roleInput.value = role;

            if (role === 'SELLER') {
                tabSeller.classList.add('bg-white', 'text-nusa', 'shadow-sm');
                tabSeller.classList.remove('text-gray-500', 'hover:text-gray-700');
                tabBuyer.classList.remove('bg-white', 'text-nusa', 'shadow-sm');
                tabBuyer.classList.add('text-gray-500', 'hover:text-gray-700');

                sellerFields.classList.remove('hidden');
                sellerInputs.forEach(input => input.setAttribute('required', 'true'));
            } else {
                tabBuyer.classList.add('bg-white', 'text-nusa', 'shadow-sm');
                tabBuyer.classList.remove('text-gray-500', 'hover:text-gray-700');
                tabSeller.classList.remove('bg-white', 'text-nusa', 'shadow-sm');
                tabSeller.classList.add('text-gray-500', 'hover:text-gray-700');

                sellerFields.classList.add('hidden');
                sellerInputs.forEach(input => input.removeAttribute('required'));
            }
        }

        window.addEventListener('DOMContentLoaded', function () {
            const savedRole = document.getElementById('roleInput').value || 'BUYER';
            switchRole(savedRole);
        });

        // Script untuk validasi form sebelum dikirim
        window.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('registerForm');

            form.addEventListener('submit', function (event) {
                let isValid = true;
                
                const requiredInputs = form.querySelectorAll('input[required]');

                form.querySelectorAll('.error-text').forEach(el => el.classList.add('hidden'));
                form.querySelectorAll('input').forEach(input => {
                    input.classList.remove('border-red-500', 'bg-red-50');
                });

                // Cek satu per satu input yang wajib diisi
                requiredInputs.forEach(input => {
                    if (input.value.trim() === '') {
                        isValid = false;
                        input.classList.add('border-red-500', 'bg-red-50');
                        
                        const wrapper = input.closest('.input-wrapper');
                        if (wrapper) {
                            const errorText = wrapper.querySelector('.error-text');
                            if (errorText) errorText.classList.remove('hidden');
                        }
                    }
                });

                if (!isValid) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>