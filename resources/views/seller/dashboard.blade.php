<x-seller-layout>

{{-- ===================== CSS ===================== --}}
<style>
    .stat-card      { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 20px; }
    .stat-icon      { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .stat-value     { font-size: 1.5rem; font-weight: 700; color: #1F2937; margin: 6px 0 2px; }
    .stat-label     { font-size: 0.75rem; color: #9CA3AF; }
    .stat-sub       { font-size: 0.75rem; color: #6B7280; margin-top: 4px; }

    .status-pending   { background: #FAEEDA; color: #633806; }
    .status-processed { background: #EEF2FF; color: #3730A3; }
    .status-shipped   { background: #E0F2FE; color: #0369A1; }
    .status-delivered { background: #E1F5EE; color: #085041; }
    .status-cancelled { background: #FCEBEB; color: #791F1F; }

    .order-row:hover  { background: #F9FAFB; }
    .empty-state      { text-align: center; padding: 40px; color: #9CA3AF; }
    .balance-gradient { background: linear-gradient(135deg, #008B81, #00736B); }
</style>

{{-- ===================== HTML ===================== --}}

{{-- Greeting --}}
<div class="mb-6">
    <h1 class="text-xl font-semibold text-gray-800">
        Selamat datang, {{ auth()->user()->username }}! 
    </h1>
    <p class="text-sm text-gray-500 mt-0.5">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">

    {{-- Total Order --}}
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Total Pesanan</p>
                <p class="stat-value">{{ $stats['total_orders'] }}</p>
                <p class="stat-sub">Semua waktu</p>
            </div>
            <div class="stat-icon bg-blue-50">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Saldo Aktif --}}
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Saldo Aktif</p>
                <p class="stat-value text-nusa">Rp {{ number_format($stats['active_balance'], 0, ',', '.') }}</p>
                <p class="stat-sub">Siap ditarik</p>
            </div>
            <div class="stat-icon bg-teal-50">
                <svg class="w-5 h-5 text-nusa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Saldo Tertahan --}}
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Saldo Tertahan</p>
                <p class="stat-value text-yellow-600">Rp {{ number_format($stats['outstanding'], 0, ',', '.') }}</p>
                <p class="stat-sub">Menunggu pesanan selesai</p>
            </div>
            <div class="stat-icon bg-yellow-50">
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    {{-- Rating Toko --}}
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="stat-label">Rating Toko</p>
                <p class="stat-value">
                    {{ $stats['store_rating'] > 0 ? number_format($stats['store_rating'], 1) : '-' }}
                    @if($stats['store_rating'] > 0)
                        <span class="text-yellow-400 text-xl">★</span>
                    @endif
                </p>
                <p class="stat-sub">Rata-rata ulasan</p>
            </div>
            <div class="stat-icon bg-yellow-50">
                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

{{-- Status summary --}}
@php $statusCounts = $statusCounts ?? []; @endphp
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-6">
    @foreach([
        'PENDING'   => ['label' => 'Menunggu', 'class' => 'status-pending'],
        'PROCESSED' => ['label' => 'Diproses', 'class' => 'status-processed'],
        'SHIPPED'   => ['label' => 'Dikirim',  'class' => 'status-shipped'],
        'DELIVERED' => ['label' => 'Selesai',  'class' => 'status-delivered'],
        'CANCELLED' => ['label' => 'Dibatal',  'class' => 'status-cancelled'],
    ] as $status => $config)
        <a href="{{ route('seller.orders.index') }}?status={{ $status }}"
            class="bg-white border border-gray-200 rounded-xl p-4 text-center hover:shadow-sm transition">
            <p class="text-2xl font-bold text-gray-800">{{ $statusCounts[$status] ?? 0 }}</p>
            <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full {{ $config['class'] }}">
                {{ $config['label'] }}
            </span>
        </a>
    @endforeach
</div>

{{-- Tabel order terbaru --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Pesanan Terbaru</h2>
        <a href="{{ route('seller.orders.index') }}"
            class="text-sm text-nusa hover:underline">Lihat semua →</a>
    </div>

    @if($recentOrders->isEmpty())
        <div class="empty-state">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm">Belum ada pesanan masuk</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Invoice</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                        <th class="text-left px-5 py-3 text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($recentOrders->take(8) as $order)
                        <tr class="order-row transition">
                            <td class="px-5 py-3 font-mono text-xs text-gray-600">
                                {{ $order->invoiceNumber }}
                            </td>
                            <td class="px-5 py-3 text-gray-700">
                                {{ $order->user->username ?? '-' }}
                            </td>
                            <td class="px-5 py-3 font-medium text-gray-800">
                                Rp {{ number_format($order->grandTotal, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-3 text-gray-500 text-xs">
                                {{ \Carbon\Carbon::parse($order->orderDate)->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $statusMap = [
                                        'PENDING'   => ['label' => 'Menunggu',  'class' => 'status-pending'],
                                        'PROCESSED' => ['label' => 'Diproses',  'class' => 'status-processed'],
                                        'SHIPPED'   => ['label' => 'Dikirim',   'class' => 'status-shipped'],
                                        'DELIVERED' => ['label' => 'Selesai',   'class' => 'status-delivered'],
                                        'CANCELLED' => ['label' => 'Dibatalkan','class' => 'status-cancelled'],
                                    ];
                                    $s = $statusMap[$order->orderStatus] ?? ['label' => $order->orderStatus, 'class' => ''];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $s['class'] }}">
                                    {{ $s['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <a href="{{ route('seller.orders.show', $order->idOrder) }}"
                                    class="text-xs text-nusa hover:underline">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- ===================== JS ===================== --}}
<script>
    // kosong — tidak ada interaksi JS di halaman ini
</script>

</x-seller-layout>