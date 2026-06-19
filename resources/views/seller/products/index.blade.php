<x-seller-layout>
@section('page-title', 'Kelola Produk')

{{-- ===================== CSS ===================== --}}
<style>
    .status-active  { background: #E1F5EE; color: #085041; }
    .status-inactive { background: #FCEBEB; color: #791F1F; }
    .btn-primary    { background: #008B81; color: white; }
    .btn-primary:hover { background: #00736B; }
    .btn-danger     { background: #FEE2E2; color: #991B1B; }
    .btn-danger:hover { background: #FECACA; }
    .btn-secondary  { background: #F3F4F6; color: #374151; }
    .btn-secondary:hover { background: #E5E7EB; }
    .table-row:hover { background: #F9FAFB; }
    .search-input:focus { border-color: #008B81; outline: none; box-shadow: 0 0 0 2px #E0F2F1; }
    .empty-state    { text-align: center; padding: 60px 20px; color: #9CA3AF; }
</style>

{{-- ===================== HTML ===================== --}}

{{-- Header halaman --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-semibold text-gray-800">Kelola Produk</h1>
        <p class="text-sm text-gray-500 mt-0.5">Total {{ $products->total() }} produk</p>
    </div>
    <a href="{{ route('seller.products.create') }}"
        class="btn-primary flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Produk
    </a>
</div>

{{-- Filter & search --}}
<div class="bg-white rounded-xl border border-gray-200 p-4 mb-4 flex items-center gap-3 flex-wrap">
    <input type="text" id="searchInput" placeholder="Cari nama produk..."
        class="search-input border border-gray-300 rounded-lg px-3 py-2 text-sm w-64 transition">

    <select id="statusFilter"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-600 focus:border-nusa outline-none">
        <option value="">Semua Status</option>
        <option value="ACTIVE">Aktif</option>
        <option value="INACTIVE">Nonaktif</option>
    </select>

    <span class="text-sm text-gray-400 ml-auto" id="filteredCount">
        Menampilkan {{ $products->count() }} produk
    </span>
</div>

{{-- Tabel produk --}}
<div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
    @if($products->isEmpty())
        <div class="empty-state">
            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <p class="text-base font-medium text-gray-500 mb-1">Belum ada produk</p>
            <p class="text-sm text-gray-400 mb-4">Mulai tambahkan produk pertamamu</p>
            <a href="{{ route('seller.products.create') }}"
                class="btn-primary inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition">
                + Tambah Produk
            </a>
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Produk</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Kategori</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Stok / Harga</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Rating</th>
                    <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody id="productTable" class="divide-y divide-gray-100">
                @foreach($products as $product)
                    <tr class="table-row transition" data-name="{{ strtolower($product->productName) }}" data-status="{{ $product->productStatus }}">

                        {{-- Produk --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @php $primaryImage = $product->productImages->where('isPrimary', true)->first() ?? $product->productImages->first(); @endphp
                                @if($primaryImage)
                                    <img src="{{ asset ($primaryImage->imageURL) }}" alt="{{ $product->productName }}"
                                        class="w-12 h-12 rounded-lg object-cover border border-gray-100">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-medium text-gray-800">{{ $product->productName }}</p>
                                    <p class="text-xs text-gray-400">{{ $product->weightGram }}g &bull; Terjual {{ $product->sold }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Kategori --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->subCategories->take(2) as $sub)
                                    <span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-600 rounded-full">
                                        {{ $sub->subCategoryName }}
                                    </span>
                                @endforeach
                                @if($product->subCategories->count() > 2)
                                    <span class="text-xs text-gray-400">+{{ $product->subCategories->count() - 2 }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- Stok / Harga --}}
                        <td class="px-5 py-4">
                            @if($product->productItems->isNotEmpty())
                                <p class="font-medium text-gray-800">
                                    Rp {{ number_format($product->productItems->min('price'), 0, ',', '.') }}
                                    @if($product->productItems->count() > 1)
                                        <span class="text-gray-400">— {{ number_format($product->productItems->max('price'), 0, ',', '.') }}</span>
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">Stok: {{ $product->productItems->sum('stock') }}</p>
                            @else
                                <span class="text-xs text-gray-400">Belum ada varian</span>
                            @endif
                        </td>

                        {{-- Rating --}}
                        <td class="px-5 py-4">
                            @if($product->avgRating)
                                <span class="text-yellow-500">★</span>
                                <span class="text-sm font-medium text-gray-700">{{ number_format($product->avgRating, 1) }}</span>
                            @else
                                <span class="text-xs text-gray-400">Belum ada</span>
                            @endif
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $product->productStatus === 'ACTIVE' ? 'status-active' : 'status-inactive' }}">
                                {{ $product->productStatus === 'ACTIVE' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2 justify-end">
                                <a href="{{ route('seller.products.edit', $product->idProduct) }}"
                                    class="btn-secondary px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                    Edit
                                </a>
                                <button onclick="confirmDelete('{{ $product->idProduct }}', '{{ $product->productName }}')"
                                    class="btn-danger px-3 py-1.5 rounded-lg text-xs font-medium transition">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $products->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Modal konfirmasi hapus --}}
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-base font-semibold text-gray-800 mb-2">Hapus Produk?</h3>
        <p class="text-sm text-gray-500 mb-5">
            Produk <strong id="deleteProductName"></strong> akan dihapus permanen dan tidak bisa dikembalikan.
        </p>
        <div class="flex gap-3 justify-end">
            <button onclick="closeDeleteModal()"
                class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium transition">
                Batal
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ===================== JS ===================== --}}
<script>
    // Filter & search client-side
    const searchInput   = document.getElementById('searchInput');
    const statusFilter  = document.getElementById('statusFilter');
    const rows          = document.querySelectorAll('#productTable tr');
    const filteredCount = document.getElementById('filteredCount');

    function applyFilter() {
        const search = searchInput.value.toLowerCase();
        const status = statusFilter.value;
        let count = 0;

        rows.forEach(row => {
            const name       = row.dataset.name || '';
            const rowStatus  = row.dataset.status || '';
            const matchSearch = name.includes(search);
            const matchStatus = status === '' || rowStatus === status;

            if (matchSearch && matchStatus) {
                row.style.display = '';
                count++;
            } else {
                row.style.display = 'none';
            }
        });

        filteredCount.textContent = `Menampilkan ${count} produk`;
    }

    searchInput.addEventListener('input', applyFilter);
    statusFilter.addEventListener('change', applyFilter);

    // Modal hapus
    function confirmDelete(id, name) {
        document.getElementById('deleteProductName').textContent = name;
        document.getElementById('deleteForm').action = `/seller/products/${id}`;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Tutup modal kalau klik backdrop
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
</script>

</x-seller-layout>