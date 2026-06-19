<x-seller-layout>
@section('page-title', 'Wallet')

{{-- ===================== CSS ===================== --}}
<style>
    .balance-card   { background: linear-gradient(135deg, #008B81, #00736B); color: white; border-radius: 16px; padding: 24px; }
    .balance-label  { font-size: 0.75rem; opacity: .75; margin-bottom: 4px; }
    .balance-amount { font-size: 1.75rem; font-weight: 700; letter-spacing: -.5px; }
    .stat-card      { background: white; border: 1px solid #E5E7EB; border-radius: 12px; padding: 18px; }
    .mutation-in    { background: #E1F5EE; color: #085041; }
    .mutation-out   { background: #FCEBEB; color: #791F1F; }
    .status-pending  { background: #FAEEDA; color: #633806; }
    .status-done     { background: #E1F5EE; color: #085041; }
    .status-failed   { background: #FCEBEB; color: #791F1F; }
    .empty-state    { text-align: center; padding: 40px; color: #9CA3AF; }
</style>

{{-- ===================== HTML ===================== --}}

{{-- Kartu saldo --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    {{-- Saldo aktif --}}
    <div class="balance-card md:col-span-2">
        <p class="balance-label">Saldo Aktif</p>
        <p class="balance-amount">Rp {{ number_format($wallet->activeBalance, 0, ',', '.') }}</p>
        <p class="text-xs mt-3 opacity-70">Dana dari pesanan yang sudah selesai & siap ditarik</p>
        <a href="{{ route('seller.wallet.withdraw') }}"
            class="inline-block mt-4 bg-white text-nusa font-semibold text-sm px-5 py-2 rounded-lg hover:bg-nusa-light transition">
            Tarik Saldo
        </a>
    </div>

    {{-- Saldo tertahan --}}
    <div class="stat-card flex flex-col justify-between">
        <div>
            <p class="text-xs text-gray-500 mb-1">Saldo Tertahan</p>
            <p class="text-2xl font-bold text-gray-800">
                Rp {{ number_format($wallet->outstandingBalance, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-2">
                Dana dari pesanan yang belum selesai. Akan cair otomatis setelah pesanan diterima pembeli.
            </p>
        </div>
    </div>
</div>

{{-- Riwayat transaksi --}}
<div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="font-semibold text-gray-800">Riwayat Transaksi</h2>
        <span class="text-xs text-gray-400">{{ $transactions->total() }} transaksi</span>
    </div>

    @if($transactions->isEmpty())
        <div class="empty-state">
            <p class="text-sm">Belum ada transaksi</p>
        </div>
    @else
        <div class="divide-y divide-gray-100">
            @foreach($transactions as $trx)
                <div class="px-5 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $trx->mutationType === 'IN' ? 'mutation-in' : 'mutation-out' }}">
                            {{ $trx->mutationType === 'IN' ? 'Masuk' : 'Keluar' }}
                        </span>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $trx->description ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $trx->createAt ? \Carbon\Carbon::parse($trx->createAt)->format('d M Y, H:i') : '-' }}</p>
                        </div>
                    </div>
                    <p class="font-semibold {{ $trx->mutationType === 'IN' ? 'text-green-600' : 'text-red-500' }}">
                        {{ $trx->mutationType === 'IN' ? '+' : '-' }}Rp {{ number_format($trx->nominal, 0, ',', '.') }}
                    </p>
                </div>
            @endforeach
        </div>

        @if($transactions->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $transactions->links() }}
            </div>
        @endif
    @endif
</div>

{{-- ===================== JS ===================== --}}
<script>
    // kosong — tidak ada interaksi JS di halaman ini
</script>

</x-seller-layout>