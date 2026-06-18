@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Chat</x-slot>

    @php
        $authId = auth()->id();
        $room = \App\Models\RoomChat::find($roomId);
        $other = null;

        if ($room) {
            $other = $room->idUser1 === $authId ? $room->user2 : $room->user1;
        }
    @endphp

    <div class="flex h-[calc(100vh-64px)] flex-col bg-slate-50">

        {{-- Header --}}
        <div class="flex-shrink-0 border-b border-gray-200 bg-white px-4 py-3 shadow-sm">
            <div class="mx-auto flex max-w-4xl items-center gap-3">
                <a href="{{ route('chat.index') }}"
                   class="flex h-10 w-10 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 hover:text-gray-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                @if ($other?->imageURL)
                    <img src="{{ $other->imageURL }}" alt="{{ $other->username }}"
                         class="h-10 w-10 flex-shrink-0 rounded-full object-cover ring-2 ring-white shadow-sm">
                @else
                    <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-sm font-semibold uppercase text-emerald-600 ring-2 ring-white shadow-sm">
                        {{ mb_substr($other?->username ?? '?', 0, 1) }}
                    </div>
                @endif

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <p class="truncate text-sm font-semibold text-gray-900">
                            {{ $other?->username ?? 'Pengguna Dihapus' }}
                        </p>

                        <span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[11px] font-semibold text-emerald-700">
                            {{ auth()->user()->role === 'BUYER' ? 'Penjual' : 'Pembeli' }}
                        </span>
                    </div>
                </div>

                <button type="button" class="flex h-9 w-9 items-center justify-center rounded-full text-gray-500 transition hover:bg-gray-100 hover:text-gray-800">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="5" r="1.8"></circle>
                        <circle cx="12" cy="12" r="1.8"></circle>
                        <circle cx="12" cy="19" r="1.8"></circle>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto bg-slate-50 px-4 py-5">
            <div class="mx-auto flex max-w-4xl flex-col gap-3" id="chat-messages">

                @if ($messages->hasMorePages())
                    <div class="pb-2 text-center">
                        <a href="?page={{ $messages->currentPage() + 1 }}"
                           class="inline-flex items-center rounded-full border border-emerald-200 bg-white px-4 py-2 text-xs font-medium text-emerald-600 shadow-sm transition hover:bg-emerald-50">
                            Muat pesan sebelumnya
                        </a>
                    </div>
                @endif

                @foreach ($messages->getCollection()->reverse() as $chat)
                    @php $isMine = $chat->senderId === $authId; @endphp

                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} gap-2">
                        @if (!$isMine)
                            @if ($other?->imageURL)
                                <img src="{{ $other->imageURL }}" alt="{{ $other->username }}"
                                     class="mt-auto h-7 w-7 flex-shrink-0 rounded-full object-cover">
                            @else
                                <div class="mt-auto flex h-7 w-7 flex-shrink-0 items-center justify-center rounded-full bg-emerald-100 text-[11px] font-bold uppercase text-emerald-600">
                                    {{ mb_substr($other?->username ?? '?', 0, 1) }}
                                </div>
                            @endif
                        @endif

                        <div class="max-w-[72%] sm:max-w-[60%]">
                            <div
                                class="break-words rounded-2xl px-3 py-2 text-[14px] leading-relaxed shadow-sm
                                {{ $isMine
                                    ? 'rounded-br-sm bg-emerald-500 text-white'
                                    : 'rounded-bl-sm border border-gray-100 bg-white text-gray-800' }}">
                                {{ $chat->messageText }}
                            </div>

                            <div class="mt-1 text-[10px] text-gray-400 {{ $isMine ? 'text-right' : 'text-left' }}">
                                {{ \Carbon\Carbon::parse($chat->createAt)->format('H:i') }}
                                @if ($isMine)
                                    <span class="ml-1">
                                        @if ($chat->isRead)
                                            <span class="text-emerald-400">✓✓</span>
                                        @else
                                            <span class="text-gray-300">✓</span>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                <div id="chat-bottom"></div>
            </div>
        </div>

        {{-- Input --}}
        <div class="flex-shrink-0 border-t border-gray-200 bg-white px-4 py-3 shadow-[0_-1px_8px_rgba(0,0,0,0.03)]">
            <div class="mx-auto max-w-4xl">
                <form action="{{ route('chat.send', $roomId) }}" method="POST" class="flex items-end gap-2" id="chat-form">
                    @csrf

                    <button type="button"
                        class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                  d="M14.5 4.5l5 5M4 20l6.5-1.5L20 9a3.536 3.536 0 00-5-5l-9.5 9.5L4 20z" />
                        </svg>
                    </button>

                    <div class="relative flex-1">
                        <textarea
                            name="message"
                            id="message-input"
                            rows="1"
                            maxlength="1000"
                            placeholder="Tulis pesan..."
                            class="max-h-[120px] w-full resize-none rounded-full border border-gray-200 bg-gray-50 px-4 py-3 pr-12 text-sm text-gray-800 placeholder:text-gray-400 outline-none transition focus:border-transparent focus:ring-2 focus:ring-emerald-300"
                            onkeydown="handleEnterKey(event)"></textarea>

                        <span class="absolute bottom-2.5 right-4 text-[10px] text-gray-300" id="char-count">0/1000</span>
                    </div>

                    <button
                        type="submit"
                        class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white shadow-sm transition hover:bg-emerald-600 active:bg-emerald-700 disabled:opacity-50"
                        id="send-btn">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </form>

                @error('message')
                    <p class="mt-1 ml-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>

    <script>
        document.getElementById('chat-bottom')?.scrollIntoView({ behavior: 'instant' });

        const textarea = document.getElementById('message-input');
        const charCount = document.getElementById('char-count');

        textarea?.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
            charCount.textContent = textarea.value.length + '/1000';
        });

        function handleEnterKey(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const val = document.getElementById('message-input')?.value.trim();
                if (val) document.getElementById('chat-form')?.submit();
            }
        }
    </script>
</x-dynamic-component>