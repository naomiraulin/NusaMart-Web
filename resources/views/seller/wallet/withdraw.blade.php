<x-seller-layout>
@section('page-title', 'Tarik Saldo')

{{-- ===================== CSS ===================== --}}
<style>
    .form-label  { display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 4px; }
    .form-input  { width: 100%; border: 1px solid #D1D5DB; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; outline: none; transition: border-color .15s, box-shadow .15s; }
    .form-input:focus { border-color: #008B81; box-shadow: 0 0 0 2px #E0F2F1; }
    .form-error  { font-size: 0.75rem; color: #DC2626; margin-top: 3px; }
    .balance-box { background: linear-gradient(135deg, #008B81, #00736B); color: white; border-radius: 12px; padding: 20px; }
    .quick-btn   { border: 1px solid #D1D5DB; border-radius: 8px; padding: 8px 16px; font-size: 0.8125rem; color: #374151; cursor: pointer; transition: all .15s; }
    .quick-btn:hover { border-color: #008B81; color: #008B81; background: #E0F2F1; }
    .info-box    { background: #F0FDF4; border: 1px solid #BBF7D0; border-radius: 8px; padding: 14px; font-size: 0.8125rem; color: #166534; }
</style>

{{-- ===================== HTML ===================== --}}

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
    <a href="{{ route('seller.wallet.index') }}" class="hover:text-nusa transition">Wallet</a>
    <span>/</span>
    <span class="text-gray-800 font-medium">Tarik Saldo</span>
</div>

<div class="max-w-xl">

    {{-- Info saldo --}}
    <div class="balance-box mb-5">
        <p class="text-xs opacity-75 mb-1">Saldo Aktif Tersedia</p>
        <p class="text-2xl font-bold">Rp {{ number_format($wallet->activeBalance, 0, ',', '.') }}</p>
        <p class="text-xs mt-2 opacity-70">Hanya saldo aktif yang bisa ditarik</p>
    </div>

    {{-- Form withdraw --}}
    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h2 class="font-semibold text-gray-800 mb-5">Form Penarikan Saldo</h2>

        <form action="{{ route('seller.wallet.withdraw.store') }}" method="POST" id="withdrawForm">
            @csrf

            {{-- Nominal --}}
            <div class="mb-4">
                <label class="form-label">Nominal Penarikan <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-medium">Rp</span>
                    <input type="number" name="amount" id="amountInput"
                        value="{{ old('amount') }}"
                        placeholder="0"
                        min="10000"
                        max="{{ $wallet->activeBalance }}"
                        class="form-input pl-10 @error('amount') border-red-400 @enderror">
                </div>
                @error('amount')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Minimal penarikan Rp 10.000</p>
            </div>

            {{-- Quick amount --}}
            <div class="mb-5">
                <p class="text-xs text-gray-500 mb-2">Nominal cepat:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach([50000, 100000, 250000, 500000, 1000000] as $quick)
                        @if($quick <= $wallet->activeBalance)
                            <button type="button" class="quick-btn" onclick="setAmount({{ $quick }})">
                                Rp {{ number_format($quick, 0, ',', '.') }}
                            </button>
                        @endif
                    @endforeach
                    <button type="button" class="quick-btn" onclick="setAmount({{ $wallet->activeBalance }})">
                        Semua
                    </button>
                </div>
            </div>

            {{-- Info rekening seller --}}
            @if($store->seller)
                <div class="info-box mb-5">
                    <p class="font-medium mb-1">Dana akan dikirim ke:</p>
                    <p>Bank: <strong>{{ $store->seller->bankName }}</strong></p>
                    <p>No. Rekening: <strong>{{ $store->seller->accountNumber }}</strong></p>
                </div>
            @endif

            {{-- Info proses --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-5 text-xs text-blue-700">
                Proses penarikan membutuhkan waktu 1-3 hari kerja. Bukti penarikan akan tersedia setelah pengajuan berhasil.
            </div>

            {{-- Tombol --}}
            <button type="submit" id="submitBtn"
                class="w-full bg-nusa hover:bg-nusa-dark text-white py-2.5 rounded-lg text-sm font-semibold transition">
                Ajukan Penarikan
            </button>
            <a href="{{ route('seller.wallet.index') }}"
                class="block text-center w-full mt-2 py-2.5 text-sm text-gray-500 hover:text-gray-700 transition">
                Batal
            </a>
        </form>
    </div>
</div>

{{-- ===================== JS ===================== --}}
<script>
    const maxBalance = {{ $wallet->activeBalance }};

    function setAmount(amount) {
        document.getElementById('amountInput').value = amount;
        validateAmount();
    }

    function validateAmount() {
        const input  = document.getElementById('amountInput');
        const btn    = document.getElementById('submitBtn');
        const val    = parseFloat(input.value) || 0;

        if (val < 10000) {
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        } else if (val > maxBalance) {
            input.value = maxBalance;
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    document.getElementById('amountInput').addEventListener('input', validateAmount);

    // Konfirmasi sebelum submit
    document.getElementById('withdrawForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const amount = parseFloat(document.getElementById('amountInput').value) || 0;
        const formatted = new Intl.NumberFormat('id-ID').format(amount);
        if (confirm(`Tarik saldo sebesar Rp ${formatted}?\n\nPastikan nominal sudah benar.`)) {
            this.submit();
        }
    });

    // Disable tombol awal
    validateAmount();
</script>

</x-seller-layout>