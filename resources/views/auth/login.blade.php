<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - NusaMart</title>
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
    <style>
        /* Matikan ikon "show password" bawaan browser (Edge/Chrome) yang
           muncul otomatis di pojok kanan input type="password" begitu user
           mulai mengetik. Tanpa ini, ikon bawaan itu tampil berdampingan
           dengan ikon mata custom kita - jadi terlihat dua ikon bertumpuk. */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input::-webkit-credentials-auto-fill-button,
        input::-webkit-strong-password-auto-fill-button {
            display: none !important;
            visibility: hidden;
            pointer-events: none;
        }
    </style>
</head>
<body class="bg-white min-h-screen font-sans">

    {{-- HEADER --}}
    <header class="bg-white border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-8 h-16 flex items-center justify-center">
            <a href="/" class="text-2xl font-bold text-nusa tracking-tight">NusaMart</a>
        </div>
    </header>

    {{-- KONTEN UTAMA --}}
    <main class="flex flex-col items-center justify-center px-6 py-16 md:py-24">
        <div class="w-full max-w-4xl flex flex-col md:flex-row items-center justify-between gap-12">

            {{-- ILUSTRASI + TAGLINE --}}
            <div class="flex flex-col items-center text-center md:items-start md:text-left">
                <img src="{{ asset('image/auth_illustration.png') }}" alt="Ilustrasi NusaMart" class="w-72 h-72 object-contain">
                <p class="mt-2 text-xl font-bold text-nusa">Dukung Produk Lokal Kebanggaanmu!</p>
            </div>

            {{-- CARD LOGIN --}}
            <div class="w-full max-w-md bg-white p-10 rounded-2xl shadow-lg border border-gray-100">
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">Log In</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-nusa font-semibold hover:text-nusa-dark">Daftar</a>
                    </p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 text-red-600 py-2 px-4 rounded-md text-sm mb-4 text-center">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="space-y-6">
                    @csrf

                    <x-nusa-ui
                        type="input"
                        name="emailOrUsername"
                        placeholder="Email atau Username"
                        required
                    />

                    <div class="relative w-full">
                        <input
                            type="password"
                            id="passwordInput"
                            name="password"
                            placeholder="Password"
                            required
                            autocomplete="current-password"
                            class="w-full border border-gray-300 rounded-md px-4 py-3 pr-12 focus:ring-teal-500 focus:border-teal-500 outline-none text-sm">

                        <button
                            type="button"
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-nusa-dark focus:outline-none z-10 cursor-pointer">

                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 pointer-events-none">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </button>
                    </div>

                    <div class="pt-8">
                        <x-nusa-ui
                            type="button"
                            class="w-full py-3 text-base"
                        >
                            Log In
                        </x-nusa-ui>
                    </div>
                </form>
            </div>

        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="text-center text-sm text-gray-400 py-8">
        &copy; NusaMart {{ date('Y') }}
    </footer>

    <script>
        const eyeOpenPath = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        `;

        const eyeClosedPath = `
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88" />
        `;

        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';

            eyeIcon.innerHTML = isHidden ? eyeClosedPath : eyeOpenPath;
        }
    </script>

</body>
</html>