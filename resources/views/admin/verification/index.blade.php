<x-admin-layout>
    <x-slot name="pageTitle">Verifikasi Toko</x-slot>

    <div class="p-6 max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-2xl font-black text-gray-900 tracking-tight">Pengajuan Verifikasi</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola dan tinjau status verifikasi badge toko lokal.</p>
            </div>
        </div>

        {{-- Pesan Sukses --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tabel Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100 text-xs uppercase tracking-wider font-bold text-gray-500">
                            <th class="p-5">ID Badge</th>
                            <th class="p-5">Informasi Toko</th>
                            <th class="p-5">Tanggal Pengajuan</th>
                            <th class="p-5">Status</th>
                            <th class="p-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-50">
                        @forelse ($verification as $verif)
                            <tr class="hover:bg-gray-50/50 transition-colors duration-200 group">
                                <td class="p-5 font-bold text-gray-900">{{ $verif->idBadge }}</td>
                                <td class="p-5">
                                    <div class="font-semibold text-gray-800">{{ $verif->store->name ?? 'Toko Tidak Ditemukan' }}</div>
                                    <div class="text-xs text-gray-400 mt-0.5">{{ $verif->badgeType ?? 'Verifikasi Lokal' }}</div>
                                </td>
                                <td class="p-5 text-gray-600 font-medium">{{ \Carbon\Carbon::parse($verif->requestDate)->translatedFormat('d M Y') }}</td>
                                <td class="p-5">
                                    @if($verif->status === 'PENDING')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 mr-1.5"></span> PENDING
                                        </span>
                                    @elseif($verif->status === 'APPROVED')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> APPROVED
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> REJECTED
                                        </span>
                                    @endif
                                </td>
                                <td class="p-5 text-right">
                                    <a href="{{ route('admin.verification.show', $verif->idBadge) }}" 
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-nusa/10 hover:bg-nusa hover:text-white text-nusa rounded-lg text-xs font-bold transition-all duration-200">
                                        Tinjau
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-sm font-medium text-gray-500">Belum ada pengajuan verifikasi saat ini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $verification->links() }}
        </div>
    </div>
</x-admin-layout>