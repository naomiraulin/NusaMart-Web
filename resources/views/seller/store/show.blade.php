<x-seller-layout>
    <x-slot name="pageTitle">Toko Saya</x-slot>

    {{-- CSS Kustom di bagian atas --}}
    <style>
        .badge-status-pending { background-color: #FEF3C7; color: #92400E; }
        .badge-status-approved { background-color: #D1FAE5; color: #065F46; }
        .badge-status-rejected { background-color: #FEE2E2; color: #991B1B; }
        .badge-status-expired { background-color: #E5E7EB; color: #374151; }
        .store-logo { transition: transform 0.3s ease; }
        .store-logo:hover { transform: scale(1.05); }
    </style>

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Card Utama Detail Toko --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col md:flex-row gap-6 items-start">
            
            {{-- Logo Toko --}}
            <div class="flex-shrink-0">
                @if($store->logoURL)
                    <img src="{{ Storage::url($store->logoURL) }}" alt="Logo {{ $store->name }}" 
                         class="w-32 h-32 rounded-full object-cover border-4 border-nusa-light store-logo shadow-sm">
                @else
                    <div class="w-32 h-32 rounded-full bg-nusa-light flex items-center justify-center text-nusa font-bold text-4xl border-4 border-white shadow-sm store-logo">
                        {{ strtoupper(substr($store->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            {{-- Informasi Toko --}}
            <div class="flex-grow space-y-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $store->name }}</h3>
                        <div class="flex items-center gap-2 mt-1 text-sm">
                            <span class="px-2 py-0.5 rounded-full {{ $store->isActive ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $store->isActive ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <span class="text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $store->storeRating > 0 ? number_format($store->storeRating, 1) : 'Belum ada rating' }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('seller.store.edit') }}" class="px-4 py-2 bg-nusa text-white text-sm font-medium rounded-md hover:bg-nusa-dark transition">
                        Edit Profil
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Lokasi</p>
                        <p class="text-sm text-gray-800">{{ $store->location ?? 'Belum diatur' }}</p>
                        @if($store->urlLocation)
                            <a href="{{ $store->urlLocation }}" target="_blank" class="text-nusa text-sm hover:underline flex items-center gap-1 mt-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Buka di Maps
                            </a>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Deskripsi</p>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $store->description ?? 'Toko ini belum memiliki deskripsi.' }}</p>
                </div>
            </div>
        </div>

        {{-- Card Status Verifikasi --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Verifikasi Toko</h4>
            
            @php
                // Mengambil request verifikasi terakhir dengan pengecekan null-safe
                $latestBadge = $store->badgeVerifications ? $store->badgeVerifications->last() : null;
            @endphp

            @if(!$latestBadge)
                <div class="bg-gray-50 p-4 rounded-md border border-gray-200 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-800">Toko belum diverifikasi.</p>
                        <p class="text-xs text-gray-500 mt-1">Ajukan verifikasi (Badge VERIFIED) agar pembeli lebih percaya.</p>
                    </div>
                    <form action="{{ route('seller.store.verify') }}" method="POST">
                        @csrf
                        <button type="submit" id="btn-verify" class="px-4 py-2 border border-nusa text-nusa bg-white text-sm font-medium rounded-md hover:bg-nusa-light transition">
                            Ajukan Verifikasi
                        </button>
                    </form>
                </div>
            @else
                {{-- Box Status --}}
                <div class="p-4 rounded-md border space-y-2
                    {{ strtolower($latestBadge->status) === 'pending' ? 'badge-status-pending border-yellow-200' : '' }}
                    {{ strtolower($latestBadge->status) === 'approved' ? 'badge-status-approved border-green-200' : '' }}
                    {{ strtolower($latestBadge->status) === 'rejected' ? 'badge-status-rejected border-red-200' : '' }}
                    {{ strtolower($latestBadge->status) === 'expired' ? 'badge-status-expired border-gray-300' : '' }}
                ">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            {{-- Menampilkan badgeType --}}
                            <p class="text-sm font-semibold uppercase tracking-wide">
                                Tipe Badge: <span class="underline">{{ $latestBadge->badgeType }}</span> 
                            </p>
                            <p class="text-xs mt-1">
                                Tanggal Pengajuan: {{ \Carbon\Carbon::parse($latestBadge->requestDate)->format('d M Y H:i') }} 
                            </p>
                            {{-- Menampilkan endDate (jika ada/setelah disetujui admin) --}}
                            @if($latestBadge->endDate) 
                                <p class="text-xs font-medium mt-0.5">
                                    Masa Berlaku Hingga: <span class="font-bold text-red-700">{{ \Carbon\Carbon::parse($latestBadge->endDate)->format('d M Y H:i') }}</span> 
                                </p>
                            @endif
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-white/60 shadow-sm border border-black/5">
                            Status: {{ $latestBadge->status }} 
                        </span>
                    </div>
                    
                    @if($latestBadge->notes) 
                        <p class="text-xs mt-2 pt-2 border-t border-black/10"><strong>Catatan Admin:</strong> {{ $latestBadge->notes }}</p> 
                    @endif
                </div>
                
                @if(strtolower($latestBadge->status) === 'rejected' || strtolower($latestBadge->status) === 'expired') 
                    <form action="{{ route('seller.store.verify') }}" method="POST" class="mt-4">
                        @csrf
                        <button type="submit" class="px-4 py-2 border border-nusa text-nusa bg-white text-sm font-medium rounded-md hover:bg-nusa-light transition">
                            Ajukan Ulang Verifikasi
                        </button>
                    </form>
                @endif
            @endif
        </div>
    </div>

    {{-- JS di bagian bawah --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnVerify = document.getElementById('btn-verify');
            if(btnVerify) {
                btnVerify.addEventListener('click', function(e) {
                    if(!confirm('Apakah Anda yakin ingin mengajukan verifikasi toko sekarang?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    </script>
</x-seller-layout>