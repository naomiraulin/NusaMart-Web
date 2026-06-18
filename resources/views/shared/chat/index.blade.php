@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Pesan</x-slot>

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white px-4 py-6 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-4xl">

            {{-- Header --}}
            <div class="mb-6">
                <div class="inline-flex items-center gap-2 rounded-full bg-indigo-50 px-4 py-2 text-sm font-medium text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-500"></span>
                    Chat
                </div>
                <h1 class="mt-3 text-2xl font-bold tracking-tight text-gray-900">Pesan</h1>
                <p class="mt-1 text-sm text-gray-500">{{ $rooms->count() }} percakapan aktif</p>
            </div>

            {{-- Container --}}
            <div class="overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm">
                @if ($rooms->isEmpty())
                    <div class="flex min-h-[520px] flex-col items-center justify-center px-6 py-20 text-center">
                        <div class="mb-5 flex h-18 w-18 items-center justify-center rounded-full bg-indigo-50">
                            <svg class="h-9 w-9 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                        </div>
                        <h2 class="text-base font-semibold text-gray-800">Belum ada percakapan</h2>
                        <p class="mt-1 text-sm text-gray-500">Pesan dari pembeli akan muncul di sini.</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-100">
                        @foreach ($rooms as $room)
                            @php
                                $authId = auth()->id();
                                $other = $room->idUser1 === $authId ? $room->user2 : $room->user1;
                                $unreadCount = $room->chats
                                    ->where('senderId', '!=', $authId)
                                    ->where('isRead', false)
                                    ->count();
                            @endphp

                            <a href="{{ route('chat.show', $room->idRoom) }}"
                               class="group flex items-center gap-4 px-5 py-4 transition-all hover:bg-gray-50 sm:px-6">

                                {{-- Avatar --}}
                                <div class="relative flex-shrink-0">
                                    @if ($other?->imageURL)
                                        <img src="{{ $other->imageURL }}" alt="{{ $other->username }}"
                                             class="h-13 w-13 rounded-full object-cover ring-2 ring-white shadow-sm">
                                    @else
                                        <div class="flex h-13 w-13 items-center justify-center rounded-full bg-indigo-100 text-base font-semibold uppercase text-indigo-600 ring-2 ring-white shadow-sm">
                                            {{ mb_substr($other?->username ?? '?', 0, 1) }}
                                        </div>
                                    @endif

                                    @if ($unreadCount > 0)
                                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-indigo-500 px-1 text-[10px] font-bold text-white ring-2 ring-white">
                                            {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Info --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-semibold text-gray-900 group-hover:text-indigo-600">
                                                {{ $other?->username ?? 'Pengguna Dihapus' }}
                                            </p>
                                            <p class="mt-1 truncate text-sm {{ $unreadCount > 0 ? 'font-medium text-gray-800' : 'text-gray-500' }}">
                                                {{ $room->lastMessage ?? 'Belum ada pesan' }}
                                            </p>
                                        </div>

                                        <div class="flex flex-shrink-0 flex-col items-end gap-1">
                                            <span class="text-xs text-gray-400">
                                                {{ $room->updateAt ? \Carbon\Carbon::parse($room->updateAt)->diffForHumans(null, true) : '' }}
                                            </span>

                                            @if ($unreadCount > 0)
                                                <span class="rounded-full bg-indigo-50 px-2.5 py-1 text-[10px] font-semibold text-indigo-600">
                                                    Baru
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-dynamic-component>