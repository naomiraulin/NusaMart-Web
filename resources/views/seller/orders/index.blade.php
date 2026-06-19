<x-seller-layout>
    <x-slot name="pageTitle">Pesanan Masuk</x-slot>

    <style>
        .order-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.25rem;
            overflow-x: auto;
            padding-bottom: 2px;
        }

        .order-tab {
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.45rem 1.1rem;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.15s;
            white-space: nowrap;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .order-tab:hover {
            border-color: #b2dfdb;
            color: #008B81;
        }

        .order-tab.active {
            background: #008B81;
            color: #fff;
            border-color: #008B81;
        }

        .tab-count {
            background: rgba(0,0,0,0.08);
            border-radius: 9999px;
            padding: 0.05rem 0.45rem;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .order-tab.active .tab-count {
            background: rgba(255,255,255,0.25);
        }

        .order-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem;
            transition: box-shadow 0.15s, border-color 0.15s;
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .order-card:hover {
            box-shadow: 0 2px 10px rgba(0,139,129,0.1);
            border-color: #b2dfdb;
        }

        .order-card-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.6rem;
        }

        .order-invoice {
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
        }

        .order-date {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .status-badge {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .status-PENDING   { background: #fef3c7; color: #b45309; }
        .status-PROCESSED  { background: #dbeafe; color: #2563eb; }
        .status-SHIPPED    { background: #e0e7ff; color: #4f46e5; }
        .status-DELIVERED  { background: #d1fae5; color: #059669; }
        .status-CANCELLED  { background: #fee2e2; color: #dc2626; }

        .order-card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .order-items-preview {
            font-size: 0.825rem;
            color: #4b5563;
            flex: 1;
            min-width: 0;
        }

        .order-items-preview .item-line {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .order-total {
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
            flex-shrink: 0;
            text-align: right;
        }

        .order-total small {
            display: block;
            font-size: 0.7rem;
            color: #9ca3af;
            font-weight: 400;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 1rem;
            color: #9ca3af;
        }

        .empty-state svg {
            width: 3.5rem;
            height: 3.5rem;
            margin: 0 auto 1rem;
            color: #d1d5db;
        }

        .order-pagination {
            margin-top: 1.25rem;
            display: flex;
            justify-content: center;
        }
    </style>

    <div style="max-width: 880px; margin: 0 auto;">

        {{-- Tab Filter --}}
        <div class="order-tabs">
            @php
                $statusTabs = [
                    ''           => 'Semua',
                    'PENDING'    => 'Menunggu Konfirmasi',
                    'PROCESSED'  => 'Diproses',
                    'SHIPPED'    => 'Dikirim',
                    'DELIVERED'  => 'Selesai',
                    'CANCELLED'  => 'Dibatalkan',
                ];
                $currentStatus = request('status', '');
                $totalAll = array_sum($statusCounts);
            @endphp

            @foreach ($statusTabs as $value => $label)
                @php
                    $count = $value === '' ? $totalAll : ($statusCounts[$value] ?? 0);
                @endphp
                <a href="{{ route('seller.orders.index', $value ? ['status' => $value] : []) }}"
                    class="order-tab {{ $currentStatus === $value ? 'active' : '' }}">
                    {{ $label }}
                    @if ($count > 0)
                        <span class="tab-count">{{ $count }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- List Order --}}
        @forelse ($orders as $order)
            <a href="{{ route('seller.orders.show', $order->idOrder) }}" class="order-card">
                <div class="order-card-top">
                    <div>
                        <span class="order-invoice">{{ $order->invoiceNumber }}</span>
                        <span class="order-date">
                            &nbsp;&middot;&nbsp;{{ \Carbon\Carbon::parse($order->orderDate)->translatedFormat('d M Y, H:i') }}
                        </span>
                    </div>
                    <span class="status-badge status-{{ $order->orderStatus }}">
                        {{ str_replace('_', ' ', $order->orderStatus) }}
                    </span>
                </div>

                <div class="order-card-body">
                    <div class="order-items-preview">
                        @php $items = $order->orderItems; @endphp
                        <div class="item-line">
                            {{ $items->first()?->nameSnapshot ?? '-' }}
                            @if ($items->count() > 1)
                                <span style="color:#9ca3af;">+{{ $items->count() - 1 }} produk lainnya</span>
                            @endif
                        </div>
                        <div style="font-size:0.75rem; color:#9ca3af; margin-top:0.15rem;">
                            {{ $items->sum('quantity') }} barang
                        </div>
                    </div>

                    <div class="order-total">
                        Rp{{ number_format($order->grandTotal, 0, ',', '.') }}
                        <small>Total Pesanan</small>
                    </div>
                </div>
            </a>
        @empty
            <div class="empty-state">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p>Belum ada pesanan masuk.</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if ($orders->hasPages())
            <div class="order-pagination">
                {{ $orders->appends(['status' => $currentStatus])->links() }}
            </div>
        @endif
    </div>
</x-seller-layout>