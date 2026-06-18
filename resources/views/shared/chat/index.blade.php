@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Chat</x-slot>

    <div class="max-w-3xl mx-auto">

        {{-- Card daftar percakapan --}}
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">

            @if ($rooms->isEmpty())
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-14 h-14 rounded-full bg-nusa-light flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-nusa" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 font-medium">Belum ada percakapan</p>
                    <p class="text-sm text-gray-400 mt-1">Pesan dari pembeli akan muncul di sini.</p>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach ($rooms as $room)
                        @php
                            $authId = auth()->id();
                            // Tentukan lawan bicara
                            $other = $room->idUser1 === $authId ? $room->user2 : $room->user1;
                            // Hitung pesan belum dibaca dari lawan bicara
                            $unreadCount = $room->chats
                                ->where('senderId', '!=', $authId)
                                ->where('isRead', false)
                                ->count();
                        @endphp

                        <a href="{{ route('chat.show', $room->idRoom) }}"
                            class="flex items-center gap-4 px-5 py-4 hover:bg-gray-50 transition-colors group">

                            {{-- Avatar --}}
                            <div class="relative flex-shrink-0">
                                @if ($other?->imageURL)
                                    <img src="{{ $other->imageURL }}" alt="{{ $other->username }}"
                                        class="w-11 h-11 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-11 h-11 rounded-full bg-nusa-light flex items-center justify-center text-nusa font-semibold text-base uppercase">
                                        {{ mb_substr($other?->username ?? '?', 0, 1) }}
                                    </div>
                                @endif

                                {{-- Unread badge --}}
                                @if ($unreadCount > 0)
                                    <span
                                        class="absolute -top-0.5 -right-0.5 w-5 h-5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center">
                                        {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                    </span>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span
                                        class="text-sm font-semibold text-gray-800 truncate group-hover:text-nusa transition-colors">
                                        {{ $other?->username ?? 'Pengguna Dihapus' }}
                                    </span>
                                    <span class="text-xs text-gray-400 flex-shrink-0">
                                        {{ $room->updateAt ? \Carbon\Carbon::parse($room->updateAt)->diffForHumans(null, true) : '' }}
                                    </span>
                                </div>
                                <p
                                    class="text-sm mt-0.5 truncate {{ $unreadCount > 0 ? 'text-gray-800 font-medium' : 'text-gray-400' }}">
                                    {{ $room->lastMessage ?? 'Belum ada pesan' }}
                                </p>
                            </div>

                            {{-- Arrow --}}
                            <svg class="w-4 h-4 text-gray-300 flex-shrink-0 group-hover:text-nusa transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</x-dynamic-component>