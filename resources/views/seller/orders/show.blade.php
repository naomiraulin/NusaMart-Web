<x-seller-layout>
    <x-slot name="pageTitle">Detail Pesanan</x-slot>

    <style>
        .order-detail-wrap { max-width: 880px; margin: 0 auto; }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.825rem;
            color: #6b7280;
            text-decoration: none;
            margin-bottom: 1rem;
        }
        .back-link:hover { color: #008B81; }

        .detail-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .invoice-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #111827;
        }

        .invoice-sub {
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 0.15rem;
        }

        .status-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 0.3rem 0.85rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }
        .status-PENDING   { background: #fef3c7; color: #b45309; }
        .status-PROCESSED { background: #dbeafe; color: #2563eb; }
        .status-SHIPPED   { background: #e0e7ff; color: #4f46e5; }
        .status-DELIVERED { background: #d1fae5; color: #059669; }
        .status-CANCELLED { background: #fee2e2; color: #dc2626; }

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.25rem;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-title svg { width: 1rem; height: 1rem; color: #008B81; }

        /* Stepper aksi */
        .action-stepper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            color: #9ca3af;
            white-space: nowrap;
        }

        .step-dot {
            width: 1.4rem;
            height: 1.4rem;
            border-radius: 50%;
            background: #f3f4f6;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.68rem;
            font-weight: 700;
            color: #9ca3af;
        }

        .step.done .step-dot { background: #008B81; border-color: #008B81; color: #fff; }
        .step.done { color: #008B81; font-weight: 500; }
        .step.current .step-dot { border-color: #008B81; color: #008B81; }
        .step.current { color: #008B81; font-weight: 600; }

        .step-line {
            width: 1.5rem;
            height: 2px;
            background: #e5e7eb;
            flex-shrink: 0;
        }
        .step-line.done { background: #008B81; }

        .action-box {
            background: #f0faf9;
            border: 1px solid #b2dfdb;
            border-radius: 0.625rem;
            padding: 1rem;
        }

        .action-box-text {
            font-size: 0.8rem;
            color: #374151;
            margin-bottom: 0.75rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 0.55rem 1.1rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.15s;
        }

        .btn-primary { background: #008B81; color: #fff; }
        .btn-primary:hover { background: #00736B; }

        .btn-danger { background: #fff; color: #dc2626; border: 1px solid #fecaca; }
        .btn-danger:hover { background: #fef2f2; }

        .btn-outline { background: #fff; color: #374151; border: 1px solid #d1d5db; }
        .btn-outline:hover { background: #f9fafb; }

        .action-row { display: flex; gap: 0.6rem; flex-wrap: wrap; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .info-grid { grid-template-columns: 1fr; }
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.825rem;
            padding: 0.45rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #9ca3af; }
        .info-value { color: #1f2937; font-weight: 500; text-align: right; }

        .buyer-block {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.9rem;
            padding-bottom: 0.9rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .buyer-avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 50%;
            background: #E0F2F1;
            color: #008B81;
            font-weight: 700;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .buyer-name { font-size: 0.9rem; font-weight: 600; color: #1f2937; }
        .buyer-phone { font-size: 0.78rem; color: #9ca3af; }

        .address-label-chip {
            display: inline-block;
            font-size: 0.68rem;
            font-weight: 600;
            background: #E0F2F1;
            color: #008B81;
            padding: 0.1rem 0.5rem;
            border-radius: 0.3rem;
            margin-bottom: 0.4rem;
        }

        .address-receiver { font-size: 0.85rem; font-weight: 600; color: #1f2937; }
        .address-phone { font-size: 0.78rem; color: #6b7280; margin-bottom: 0.4rem; }
        .address-text { font-size: 0.82rem; color: #4b5563; line-height: 1.5; }

        /* Item produk */
        .item-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .item-row:last-child { border-bottom: none; }

        .item-img {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            object-fit: cover;
            flex-shrink: 0;
            background: #f3f4f6;
        }

        .item-name { font-size: 0.85rem; font-weight: 500; color: #1f2937; }
        .item-qty { font-size: 0.78rem; color: #9ca3af; margin-top: 0.1rem; }
        .item-price { font-size: 0.85rem; font-weight: 600; color: #1f2937; flex-shrink: 0; text-align: right; }

        .summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 0.825rem;
            padding: 0.35rem 0;
            color: #4b5563;
        }
        .summary-row.total {
            border-top: 1px solid #e5e7eb;
            margin-top: 0.4rem;
            padding-top: 0.6rem;
            font-size: 0.95rem;
            font-weight: 700;
            color: #111827;
        }

        .resi-box {
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 0.75rem;
        }
        .resi-code { font-family: monospace; font-weight: 700; color: #1f2937; letter-spacing: 0.03em; }

        .tracking-item {
            display: flex;
            gap: 0.75rem;
            padding-bottom: 1rem;
            position: relative;
        }
        .tracking-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 5px;
            top: 14px;
            width: 2px;
            height: calc(100% - 6px);
            background: #e5e7eb;
        }
        .tracking-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #008B81;
            flex-shrink: 0;
            margin-top: 3px;
            z-index: 1;
        }
        .tracking-desc { font-size: 0.82rem; color: #1f2937; font-weight: 500; }
        .tracking-loc { font-size: 0.76rem; color: #9ca3af; }
        .tracking-time { font-size: 0.72rem; color: #b0b6bd; margin-top: 0.1rem; }

        textarea.input-field, input.input-field, select.input-field {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.55rem 0.75rem;
            font-size: 0.825rem;
            color: #1f2937;
        }
        textarea.input-field:focus, input.input-field:focus, select.input-field:focus {
            outline: none;
            border-color: #008B81;
            box-shadow: 0 0 0 2px rgba(0,139,129,0.15);
        }
        .form-label {
            font-size: 0.78rem;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 0.3rem;
            display: block;
        }
    </style>

    <div class="order-detail-wrap">

        <a href="{{ route('seller.orders.index') }}" class="back-link">
            <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Pesanan Masuk
        </a>

        {{-- Header --}}
        <div class="detail-header">
            <div>
                <div class="invoice-title">{{ $order->invoiceNumber }}</div>
                <div class="invoice-sub">
                    Dipesan {{ \Carbon\Carbon::parse($order->orderDate)->translatedFormat('d M Y, H:i') }}
                </div>
            </div>
            <span class="status-badge status-{{ $order->orderStatus }}">
                {{ str_replace('_', ' ', $order->orderStatus) }}
            </span>
        </div>

        {{-- ===== AKSI STATUS ===== --}}
        @if (!in_array($order->orderStatus, ['DELIVERED', 'CANCELLED']))
            <div class="card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Status & Aksi Pesanan
                </div>

                {{-- Stepper visual --}}
                @php
                    $steps = ['PENDING' => 'Pesanan Masuk', 'PROCESSED' => 'Diproses', 'SHIPPED' => 'Dikirim', 'DELIVERED' => 'Selesai'];
                    $stepKeys = array_keys($steps);
                    $currentIndex = array_search($order->orderStatus, $stepKeys);
                @endphp
                <div class="action-stepper">
                    @foreach ($steps as $key => $label)
                        @php
                            $idx = array_search($key, $stepKeys);
                            $isDone = $idx < $currentIndex;
                            $isCurrent = $idx === $currentIndex;
                        @endphp
                        <div class="step {{ $isDone ? 'done' : '' }} {{ $isCurrent ? 'current' : '' }}">
                            <span class="step-dot">{{ $isDone ? '✓' : $idx + 1 }}</span>
                            {{ $label }}
                        </div>
                        @if (!$loop->last)
                            <div class="step-line {{ $isDone ? 'done' : '' }}"></div>
                        @endif
                    @endforeach
                </div>

                <div class="action-box">
                    @if ($order->orderStatus === 'PENDING')
                        <p class="action-box-text">
                            Pesanan baru masuk. Konfirmasi untuk mulai memproses pesanan ini, atau batalkan jika stok tidak tersedia.
                        </p>
                        <div class="action-row">
                            <form action="{{ route('seller.orders.confirm', $order->idOrder) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary">
                                    <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Konfirmasi Pesanan
                                </button>
                            </form>
                            <button type="button" class="btn btn-danger" onclick="document.getElementById('cancelModal').showModal()">
                                Batalkan
                            </button>
                        </div>

                    @elseif ($order->orderStatus === 'PROCESSED')
                        <p class="action-box-text">
                            Pesanan sedang disiapkan. Input data pengiriman untuk melanjutkan ke tahap dikirim.
                        </p>
                        <div class="action-row">
                            <a href="{{ route('seller.orders.shipping.create', $order->idOrder) }}" class="btn btn-primary">
                                <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4v10l-9 4-9-4V7z"/></svg>
                                Input Pengiriman
                            </a>
                        </div>

                    @elseif ($order->orderStatus === 'SHIPPED')
                        <p class="action-box-text">
                            Pesanan sudah dikirim. Perbarui status pengiriman sesuai progres kurir.
                        </p>
                        <form action="{{ route('seller.shipping.updateStatus', $order->shipping->idShipping) }}" method="POST" class="action-row" style="align-items:flex-end; flex-wrap:wrap;">
                            @csrf
                            @method('PUT')
                            <div style="flex:1; min-width:160px;">
                                <label class="form-label">Status Pengiriman</label>
                                <select name="status" class="input-field" required>
                                    <option value="WAITING" {{ $order->shipping?->shippingStatus === 'WAITING' ? 'selected' : '' }}>Menunggu Pickup</option>
                                    <option value="PICKED_UP" {{ $order->shipping?->shippingStatus === 'PICKED_UP' ? 'selected' : '' }}>Sudah Diambil Kurir</option>
                                    <option value="IN_TRANSIT" {{ $order->shipping?->shippingStatus === 'IN_TRANSIT' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                    <option value="DELIVERED">Sudah Diterima</option>
                                    <option value="FAILED">Gagal Kirim</option>
                                </select>
                            </div>
                            <div style="flex:1; min-width:160px;">
                                <label class="form-label">Lokasi (opsional)</label>
                                <input type="text" name="location" class="input-field" placeholder="Cth: Gudang Jakarta">
                            </div>
                            <button type="submit" class="btn btn-primary">Perbarui</button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        {{-- ===== INFO PEMBELI & ALAMAT ===== --}}
        <div class="info-grid">
            {{-- Pembeli --}}
            <div class="card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Informasi Pembeli
                </div>
                <div class="buyer-block" style="justify-content: space-between;">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        @if ($order->user?->imageURL)
                            <img src="{{ asset($order->user->imageURL) }}" class="buyer-avatar" style="object-fit:cover;" alt="avatar">
                        @else
                            <div class="buyer-avatar">{{ mb_substr($order->user?->username ?? '?', 0, 1) }}</div>
                        @endif
                        <div>
                            <div class="buyer-name">{{ $order->user?->username ?? 'Pengguna Dihapus' }}</div>
                            <div class="buyer-phone">{{ $order->user?->phone ?? '-' }}</div>
                        </div>
                    </div>

                    @if ($order->user)
                        <form action="{{ route('chat.openWithSeller', $order->user->idUser) }}" method="POST" style="flex-shrink:0;">
                            @csrf
                            <button type="submit" class="btn btn-outline">
                                <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 3H3v13h5l4 5 4-5h5V3z"/></svg>
                                Chat Pembeli
                            </button>
                        </form>
                    @endif
                </div>
                <div class="info-row">
                    <span class="info-label">Catatan Pembeli</span>
                </div>
                <p style="font-size:0.82rem; color:#4b5563; font-style: {{ $order->buyerNote ? 'normal' : 'italic' }};">
                    {{ $order->buyerNote ?? 'Tidak ada catatan dari pembeli.' }}
                </p>
            </div>

            {{-- Alamat Pengiriman --}}
            <div class="card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Alamat Pengiriman
                </div>
                @if ($order->address)
                    <span class="address-label-chip">{{ $order->address->label }}</span>
                    <div class="address-receiver">{{ $order->address->receiver }}</div>
                    <div class="address-phone">{{ $order->address->phone }}</div>
                    <div class="address-text">
                        {{ $order->address->completeAddress }}<br>
                        {{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postalCode }}
                    </div>
                @else
                    <p style="font-size:0.82rem; color:#9ca3af; font-style:italic;">Alamat tidak tersedia.</p>
                @endif
            </div>
        </div>

        {{-- ===== PRODUK DIPESAN ===== --}}
        <div class="card">
            <div class="card-title">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Produk Dipesan ({{ $order->orderItems->sum('quantity') }} barang)
            </div>

            @foreach ($order->orderItems as $item)
                @php $img = $item->productItem?->product?->productImages?->firstWhere('isPrimary', true) ?? $item->productItem?->product?->productImages?->first(); @endphp
                <div class="item-row">
                    <img src="{{ $img?->imageURL ? asset($img->imageURL) : 'https://placehold.co/100x100?text=No+Image' }}" class="item-img" alt="produk">
                    <div style="flex:1; min-width:0;">
                        <div class="item-name">{{ $item->nameSnapshot }}</div>
                        <div class="item-qty">{{ $item->quantity }} x Rp{{ number_format($item->priceSnapshot, 0, ',', '.') }}</div>
                    </div>
                    <div class="item-price">Rp{{ number_format($item->quantity * $item->priceSnapshot, 0, ',', '.') }}</div>
                </div>
            @endforeach

            {{-- Ringkasan biaya --}}
            <div style="margin-top:0.75rem;">
                <div class="summary-row">
                    <span>Subtotal Produk</span>
                    <span>Rp{{ number_format($order->productTotalPrice, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    <span>Rp{{ number_format($order->shippingCost, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Biaya Layanan</span>
                    <span>Rp{{ number_format($order->servicePrice, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row total">
                    <span>Total Pesanan</span>
                    <span>Rp{{ number_format($order->grandTotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        {{-- ===== PENGIRIMAN (jika sudah ada) ===== --}}
        @if ($order->shipping)
            <div class="card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7l9-4 9 4v10l-9 4-9-4V7z"/></svg>
                    Informasi Pengiriman
                </div>
                <div class="info-row">
                    <span class="info-label">Kurir</span>
                    <span class="info-value">{{ $order->shipping->courier?->courierName ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">{{ str_replace('_', ' ', $order->shipping->shippingStatus) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Ongkos Kirim</span>
                    <span class="info-value">Rp{{ number_format($order->shipping->shippingPrice, 0, ',', '.') }}</span>
                </div>

                @if ($order->shipping->resi)
                    <div class="resi-box">
                        <div>
                            <div style="font-size:0.72rem; color:#9ca3af;">No. Resi</div>
                            <div class="resi-code">{{ $order->shipping->resi }}</div>
                        </div>
                    </div>
                @endif

                {{-- Tracking history --}}
                @if ($order->shipping->shippingTrackings && $order->shipping->shippingTrackings->count() > 0)
                    <div style="margin-top:1.25rem;">
                        @foreach ($order->shipping->shippingTrackings->sortByDesc('updateAt') as $track)
                            <div class="tracking-item">
                                <div class="tracking-dot"></div>
                                <div>
                                    <div class="tracking-desc">{{ $track->description ?? '-' }}</div>
                                    @if ($track->packetLocation)
                                        <div class="tracking-loc">{{ $track->packetLocation }}</div>
                                    @endif
                                    <div class="tracking-time">{{ \Carbon\Carbon::parse($track->updateAt)->translatedFormat('d M Y, H:i') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- ===== PEMBAYARAN ===== --}}
        @if ($order->payment)
            <div class="card">
                <div class="card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Informasi Pembayaran
                </div>
                <div class="info-row">
                    <span class="info-label">Metode</span>
                    <span class="info-value">{{ $order->payment->paymentMethod?->methodName ?? '-' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">{{ $order->payment->paymentStatus }}</span>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal konfirmasi batalkan --}}
    <dialog id="cancelModal" style="border:none; border-radius:0.75rem; padding:0; max-width:380px; width:90%;">
        <form action="{{ route('seller.orders.cancel', $order->idOrder) }}" method="POST" style="padding:1.5rem;">
            @csrf
            @method('PUT')
            <h3 style="font-size:1rem; font-weight:700; color:#1f2937; margin-bottom:0.5rem;">Batalkan Pesanan?</h3>
            <p style="font-size:0.825rem; color:#6b7280; margin-bottom:1.25rem;">
                Pesanan akan dibatalkan dan stok produk dikembalikan. Tindakan ini tidak bisa dibatalkan.
            </p>
            <div style="display:flex; gap:0.6rem; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('cancelModal').close()">Batal</button>
                <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
            </div>
        </form>
    </dialog>
</x-seller-layout>