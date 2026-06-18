<x-seller-layout>
    
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang, {{ Auth::user()->username ?? 'Seller' }}!</h2>
        <p class="text-gray-500">Pantau performa tokomu.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <x-nusa-ui type="card" class="border-l-4 border-l-nusa">
            <h3 class="text-gray-500 text-sm font-medium">Total Penjualan</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">Rp 0</p>
        </x-nusa-ui>

        <x-nusa-ui type="card" class="border-l-4 border-l-blue-500">
            <h3 class="text-gray-500 text-sm font-medium">Pesanan Baru</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">0</p>
        </x-nusa-ui>

        <x-nusa-ui type="card" class="border-l-4 border-l-orange-500">
            <h3 class="text-gray-500 text-sm font-medium">Produk Aktif</h3>
            <p class="text-3xl font-bold text-gray-800 mt-2">0</p>
        </x-nusa-ui>

    </div>

    <div class="mt-8 bg-white p-8 rounded-lg shadow-sm border border-gray-100 text-center">
        <h3 class="text-lg font-medium text-gray-700 mb-2">Belum ada aktivitas pesanan</h3>
        <p class="text-gray-500 mb-4">Mulai tambahkan produk pertamamu agar pembeli bisa mulai berbelanja.</p>
        <x-nusa-ui type="button">
            + Tambah Produk Baru
        </x-nusa-ui>
    </div>

</x-seller-layout>