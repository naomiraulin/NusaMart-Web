<x-admin-layout>
    <x-slot name="pageTitle">Detail Verifikasi</x-slot>

    <div class="p-6 max-w-4xl mx-auto pb-12">
        
        {{-- Back & Header --}}
        <div class="mb-8">
            <a href="{{ route('admin.verification.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-500 hover:text-nusa transition-colors mb-4 group">
                <svg class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar
            </a>
            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Tinjau Pengajuan</h2>
            <p class="text-gray-500 text-sm mt-1">ID Pengajuan: <span class="font-bold text-nusa">{{ $verification->idBadge }}</span></p>
        </div>

        {{-- Info Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 mb-8 relative overflow-hidden">
            {{-- Dekorasi Latar Belakang --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-nusa/5 rounded-bl-[100px] -mr-4 -mt-4 pointer-events-none"></div>

            <h3 class="text-sm font-black uppercase tracking-wider text-gray-400 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Informasi Toko
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Nama Toko</p>
                    <p class="text-lg font-black text-gray-900">{{ $verification->store->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Pemilik (Seller)</p>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold text-xs">
                            {{ mb_substr($verification->store->seller->user->name ?? '?', 0, 1) }}
                        </div>
                        <p class="text-base font-semibold text-gray-800">{{ $verification->store->seller->user->name ?? '-' }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Tipe Badge Diminta</p>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-nusa-light text-nusa text-sm font-bold">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ $verification->badgeType ?? 'VERIFIED LOCAL' }}
                    </div>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Status Saat Ini</p>
                    @if($verification->status === 'PENDING')
                        <span class="inline-flex px-3 py-1 rounded-md text-sm font-bold bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20">PENDING</span>
                    @elseif($verification->status === 'APPROVED')
                        <span class="inline-flex px-3 py-1 rounded-md text-sm font-bold bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">APPROVED</span>
                    @else
                        <span class="inline-flex px-3 py-1 rounded-md text-sm font-bold bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">REJECTED</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Area Aksi (Hanya muncul jika status PENDING) --}}
        @if($verification->status === 'PENDING')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Card Setujui --}}
                <div class="bg-white rounded-2xl shadow-sm border border-green-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-green-100 text-green-600 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Setujui Verifikasi</h3>
                        <p class="text-sm text-gray-500 mb-6">Toko ini memenuhi semua syarat dan berhak mendapatkan badge verifikasi lokal.</p>
                    </div>
                    <form action="{{ route('admin.verification.approve', $verification->idBadge) }}" method="POST">
                        @csrf
                        <button type="submit" onclick="return confirm('Yakin ingin menyetujui toko ini?')" 
                            class="w-full py-3 bg-green-500 hover:bg-green-600 active:bg-green-700 text-white font-bold rounded-xl transition-all duration-200 shadow-sm hover:shadow-md hover:shadow-green-500/20">
                            Terima & Berikan Badge
                        </button>
                    </form>
                </div>

                {{-- Card Tolak --}}
                <div class="bg-gray-50 rounded-2xl border border-gray-200 p-6 flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Tolak Verifikasi</h3>
                        <p class="text-sm text-gray-500 mb-4">Tolak pengajuan jika data tidak valid atau toko tidak memenuhi standar.</p>
                    </div>
                    <form action="{{ route('admin.verification.reject', $verification->idBadge) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="notes" class="block text-xs font-bold text-gray-700 uppercase mb-1.5">Alasan Penolakan <span class="text-red-500">*</span></label>
                            <textarea name="notes" id="notes" rows="3" required
                                class="w-full border-gray-300 rounded-xl p-3 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white" 
                                placeholder="Jelaskan alasan penolakan secara spesifik agar seller dapat memperbaikinya..."></textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs font-medium mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" onclick="return confirm('Yakin ingin menolak pengajuan ini?')" 
                            class="w-full py-3 bg-white border-2 border-red-500 text-red-600 hover:bg-red-50 active:bg-red-100 font-bold rounded-xl transition-all duration-200">
                            Tolak Pengajuan
                        </button>
                    </form>
                </div>

            </div>
        @endif
    </div>
</x-admin-layout>