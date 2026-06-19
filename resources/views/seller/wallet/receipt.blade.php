<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Penarikan - NusaMart</title>

    {{-- ===================== CSS ===================== --}}
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #F3F4F6;
            color: #1F2937;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 32px 16px;
        }

        /* Tombol aksi — tidak muncul saat print */
        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #008B81;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-print:hover { background: #00736B; }
        .btn-back {
            background: white;
            color: #374151;
            border: 1px solid #D1D5DB;
            padding: 10px 24px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-back:hover { background: #F9FAFB; }

        /* Kartu receipt */
        .receipt-card {
            background: white;
            width: 100%;
            max-width: 480px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        /* Header */
        .receipt-header {
            background: linear-gradient(135deg, #008B81, #00736B);
            color: white;
            padding: 28px 24px;
            text-align: center;
        }
        .receipt-header .logo { font-size: 1.5rem; font-weight: 700; margin-bottom: 4px; }
        .receipt-header .subtitle { font-size: 0.8rem; opacity: .8; }
        .receipt-header .title { font-size: 1rem; font-weight: 600; margin-top: 16px; }

        /* Status badge */
        .status-badge {
            display: inline-block;
            margin-top: 10px;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            background: rgba(255,255,255,.2);
            color: white;
            letter-spacing: .5px;
        }

        /* Nominal besar */
        .receipt-amount {
            text-align: center;
            padding: 24px;
            border-bottom: 1px dashed #E5E7EB;
        }
        .receipt-amount .label { font-size: 0.75rem; color: #9CA3AF; margin-bottom: 4px; }
        .receipt-amount .amount { font-size: 2rem; font-weight: 800; color: #008B81; }

        /* Detail rows */
        .receipt-body { padding: 20px 24px; }
        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid #F3F4F6;
            font-size: 0.875rem;
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-row .key { color: #6B7280; }
        .detail-row .val { font-weight: 500; color: #1F2937; text-align: right; max-width: 60%; }

        /* Footer */
        .receipt-footer {
            background: #F9FAFB;
            border-top: 1px solid #E5E7EB;
            padding: 16px 24px;
            text-align: center;
            font-size: 0.75rem;
            color: #9CA3AF;
            line-height: 1.6;
        }

        /* Garis putus */
        .divider {
            border: none;
            border-top: 1px dashed #E5E7EB;
            margin: 4px 0;
        }

        /* ===== PRINT STYLES ===== */
        @media print {
            body { background: white; padding: 0; }
            .action-bar { display: none !important; }
            .receipt-card { box-shadow: none; border-radius: 0; max-width: 100%; }
        }
    </style>
</head>
<body>

    {{-- ===================== HTML ===================== --}}

    {{-- Tombol aksi (tidak muncul saat print) --}}
    <div class="action-bar">
        <button class="btn-print" onclick="window.print()">
            🖨️ Cetak Bukti
        </button>
        <a href="{{ route('seller.wallet.index') }}" class="btn-back">
            ← Kembali ke Wallet
        </a>
    </div>

    {{-- Kartu receipt --}}
    <div class="receipt-card">

        {{-- Header --}}
        <div class="receipt-header">
            <div class="logo">NusaMart</div>
            <div class="subtitle">Platform UMKM Digital Indonesia</div>
            <div class="title">Bukti Penarikan Saldo</div>
            <span class="status-badge">
                {{ strtoupper($withdrawal->status) }}
            </span>
        </div>

        {{-- Nominal --}}
        <div class="receipt-amount">
            <div class="label">Jumlah Penarikan</div>
            <div class="amount">Rp {{ number_format($withdrawal->nominal, 0, ',', '.') }}</div>
        </div>

        {{-- Detail --}}
        <div class="receipt-body">
            <div class="detail-row">
                <span class="key">ID Transaksi</span>
                <span class="val" style="font-family: monospace; font-size: 0.8rem;">{{ $withdrawal->idWithdrawal }}</span>
            </div>
            <div class="detail-row">
                <span class="key">Tanggal Pengajuan</span>
                <span class="val">{{ $withdrawal->createAt ? \Carbon\Carbon::parse($withdrawal->createAt)->format('d M Y, H:i') : '-' }} WIB</span>
            </div>
            <div class="detail-row">
                <span class="key">Nama Toko</span>
                <span class="val">{{ $store->name }}</span>
            </div>
            @if($store->seller)
                <div class="detail-row">
                    <span class="key">Bank Tujuan</span>
                    <span class="val">{{ $store->seller->bankName }}</span>
                </div>
                <div class="detail-row">
                    <span class="key">No. Rekening</span>
                    <span class="val">{{ $store->seller->accountNumber }}</span>
                </div>
            @endif
            <div class="detail-row">
                <span class="key">Biaya Layanan</span>
                <span class="val">Rp {{ number_format($withdrawal->serviceCost, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="key">Total Diterima</span>
                <span class="val" style="color:#008B81; font-weight:700; font-size:1rem;">
                    Rp {{ number_format($withdrawal->nominal - $withdrawal->serviceCost, 0, ',', '.') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="key">Status</span>
                <span class="val">
                    @if($withdrawal->status === 'PENDING')
                        <span style="color:#B45309; font-weight:600;">⏳ Menunggu Proses</span>
                    @elseif($withdrawal->status === 'PROCESSING')
                        <span style="color:#1D4ED8; font-weight:600;">🔄 Sedang Diproses</span>
                    @elseif($withdrawal->status === 'DONE')
                        <span style="color:#065F46; font-weight:600;">✅ Selesai</span>
                    @else
                        <span style="color:#991B1B; font-weight:600;">❌ Gagal</span>
                    @endif
                </span>
            </div>
        </div>

        {{-- Footer --}}
        <div class="receipt-footer">
            <p>Dokumen ini adalah bukti pengajuan penarikan saldo resmi dari NusaMart.</p>
            <p>Proses pencairan membutuhkan 1–3 hari kerja.</p>
            <p style="margin-top:8px; color:#D1D5DB;">Dicetak pada {{ now()->format('d M Y, H:i') }} WIB</p>
        </div>
    </div>

    {{-- ===================== JS ===================== --}}
    <script>
        // Auto print kalau dari redirect setelah withdraw berhasil
        @if(session('success'))
            window.onload = function() {
                // Delay sedikit biar halaman render dulu
                setTimeout(() => window.print(), 500);
            }
        @endif
    </script>

</body>
</html>