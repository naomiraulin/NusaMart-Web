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

            {{-- Auth buttons --}}
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 text-sm font-medium text-nusa border border-nusa rounded-lg hover:bg-nusa-light transition">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 text-sm font-medium text-white bg-nusa hover:bg-nusa-dark rounded-lg transition">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    {{-- KONTEN --}}
    <main class="flex-1">
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

</body>
</html>