<x-seller-layout>
    <x-slot name="pageTitle">Input Pengiriman</x-slot>

    <style>
        .shipping-wrap { max-width: 640px; margin: 0 auto; }

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

        .card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
        }

        .order-summary {
            background: #f9fafb;
            border-radius: 0.625rem;
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .order-summary .invoice { font-size: 0.85rem; font-weight: 600; color: #1f2937; }
        .order-summary .total { font-size: 0.95rem; font-weight: 700; color: #008B81; }

        .form-label {
            font-size: 0.82rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.4rem;
            display: block;
        }

        .form-group { margin-bottom: 1.25rem; }

        .courier-options {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .courier-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.625rem;
            padding: 0.8rem 1rem;
            cursor: pointer;
            transition: border-color 0.15s, background 0.15s;
        }

        .courier-option:hover { border-color: #b2dfdb; }

        .courier-option input[type="radio"] {
            accent-color: #008B81;
            width: 1rem;
            height: 1rem;
            flex-shrink: 0;
        }

        .courier-option input[type="radio"]:checked ~ .courier-info .courier-name {
            color: #008B81;
        }

        .courier-option:has(input:checked) {
            border-color: #008B81;
            background: #f0faf9;
        }

        .courier-info { flex: 1; }
        .courier-name { font-size: 0.85rem; font-weight: 600; color: #1f2937; }
        .courier-service { font-size: 0.72rem; color: #9ca3af; margin-top: 0.1rem; }
        .courier-eta { font-size: 0.72rem; color: #6b7280; }

        .input-field {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.6rem 0.85rem;
            font-size: 0.85rem;
            color: #1f2937;
        }
        .input-field:focus {
            outline: none;
            border-color: #008B81;
            box-shadow: 0 0 0 2px rgba(0,139,129,0.15);
        }

        .input-prefix-wrap {
            position: relative;
        }
        .input-prefix-wrap span {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.85rem;
            color: #9ca3af;
        }
        .input-prefix-wrap input { padding-left: 2.1rem; }

        .help-text { font-size: 0.74rem; color: #9ca3af; margin-top: 0.3rem; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.65rem 1.25rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
        }
        .btn-primary { background: #008B81; color: #fff; width: 100%; }
        .btn-primary:hover { background: #00736B; }

        .error-text {
            font-size: 0.76rem;
            color: #dc2626;
            margin-top: 0.3rem;
        }

        .empty-courier {
            text-align: center;
            padding: 2rem 1rem;
            color: #9ca3af;
            font-size: 0.85rem;
        }
    </style>

    <div class="shipping-wrap">
        <a href="{{ route('seller.orders.show', $order->idOrder) }}" class="back-link">
            <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Detail Pesanan
        </a>

        <div class="card">
            {{-- Ringkasan order --}}
            <div class="order-summary">
                <span class="invoice">{{ $order->invoiceNumber }}</span>
                <span class="total">Rp{{ number_format($order->grandTotal, 0, ',', '.') }}</span>
            </div>

            @if (session('error'))
                <div style="background:#fef2f2; border:1px solid #fecaca; color:#dc2626; border-radius:0.5rem; padding:0.7rem 1rem; font-size:0.8rem; margin-bottom:1.25rem;">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('seller.orders.shipping.store', $order->idOrder) }}" method="POST">
                @csrf

                {{-- Pilih kurir --}}
                <div class="form-group">
                    <label class="form-label">Pilih Kurir</label>

                    @if ($couriers->isEmpty())
                        <div class="empty-courier">Tidak ada opsi kurir aktif saat ini.</div>
                    @else
                        <div class="courier-options">
                            @foreach ($couriers as $courier)
                                <label class="courier-option">
                                    <input type="radio" name="id_courier" value="{{ $courier->idCourier }}"
                                        {{ old('id_courier') === $courier->idCourier ? 'checked' : '' }}
                                        required>
                                    <div class="courier-info">
                                        <div class="courier-name">{{ $courier->courierName }}</div>
                                        <div class="courier-service">{{ $courier->serviceType }}</div>
                                    </div>
                                    @if ($courier->timeEstimation)
                                        <div class="courier-eta">Estimasi {{ $courier->timeEstimation }} hari</div>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    @endif

                    @error('id_courier')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Ongkos kirim --}}
                <div class="form-group">
                    <label class="form-label" for="shipping_price">Ongkos Kirim</label>
                    <div class="input-prefix-wrap">
                        <span>Rp</span>
                        <input type="number" name="shipping_price" id="shipping_price" class="input-field"
                            min="0" step="500" placeholder="0"
                            value="{{ old('shipping_price', $order->shippingCost) }}" required>
                    </div>
                    <p class="help-text">Sesuaikan dengan tarif kurir yang dipilih. Nomor resi akan dibuat otomatis.</p>
                    @error('shipping_price')
                        <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <svg style="width:0.9rem;height:0.9rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan & Kirim Pesanan
                </button>
            </form>
        </div>
    </div>
</x-seller-layout>