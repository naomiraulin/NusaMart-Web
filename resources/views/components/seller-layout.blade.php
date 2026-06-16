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

    <aside class="w-64 bg-white shadow-md flex flex-col justify-between">
        <div>
            <div class="h-16 flex items-center justify-center border-b border-gray-200">
                <h1 class="text-2xl font-bold text-nusa">NusaMart <span class="text-sm font-normal text-gray-500">Seller</span></h1>
            </div>
            <nav class="p-4 space-y-2">
                <a href="{{ route('seller.dashboard') }}" class="block px-4 py-2 rounded-md {{ request()->routeIs('seller.dashboard') ? 'bg-nusa-light text-nusa font-medium' : 'hover:bg-gray-100 text-gray-700' }}">
                    Dashboard
                </a>
                <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100 text-gray-700">Kelola Produk</a>
                <a href="#" class="block px-4 py-2 rounded-md hover:bg-gray-100 text-gray-700">Pesanan Masuk</a>
            </nav>
        </div>
        <div class="p-4 border-t border-gray-200">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 rounded-md text-red-600 hover:bg-red-50 font-medium">Logout</button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-8">
        {{ $slot }}
    </main>

</body>
</html>