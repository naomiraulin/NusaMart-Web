@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Chat</x-slot>

    @php
        $authId = auth()->id();
        $room = \App\Models\RoomChat::find($roomId);
        $other = null;
        $displayName = 'Pengguna Dihapus';

        if ($room) {
            $other = $room->idUser1 === $authId ? $room->user2 : $room->user1;
            
            // LOGIKA PENENTUAN NAMA TANPA MENGUBAH MODEL:
            if ($other) {
                if ($other->role === 'SELLER') {
                    // Query manual ke tabel Store
                    $store = \App\Models\Store::where('idSeller', $other->idUser)->first();
                    $displayName = $store ? $store->name : $other->username;
                } else {
                    $displayName = $other->username;
                }
            }
        }
    @endphp

    <div class="max-w-3xl mx-auto">

        {{-- Card percakapan --}}
        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden flex flex-col" style="height: 70vh;">

            {{-- Header lawan bicara --}}
            <div class="flex-shrink-0 bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center gap-3">
                {{-- Tombol back --}}
                <a href="{{ route('chat.index') }}"
                    class="p-1.5 rounded-lg text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                {{-- Avatar lawan bicara --}}
                @if ($other?->imageURL)
                    <img src="{{ asset('storage/' . $other->imageURL) }}" alt="{{ $displayName }}"
                        class="w-9 h-9 rounded-full object-cover flex-shrink-0">
                @else
                    <div
                        class="w-9 h-9 rounded-full bg-nusa-light flex items-center justify-center text-nusa font-semibold text-sm uppercase flex-shrink-0">
                        {{ mb_substr($displayName, 0, 1) }}
                    </div>
                @endif

                {{-- Nama --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">
                        {{ $displayName }}
                    </p>
                </div>
            </div>

            {{-- Area pesan --}}
            <div class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-gray-50" id="chat-messages">

                {{-- Load more (pagination) --}}
                @if ($messages->hasMorePages())
                    <div class="text-center">
                        <a href="?page={{ $messages->currentPage() + 1 }}"
                            class="inline-block text-xs text-nusa hover:text-nusa-dark font-medium bg-white border border-nusa/30 rounded-full px-4 py-1.5 hover:bg-nusa-light transition-colors">
                            Muat pesan sebelumnya
                        </a>
                    </div>
                @endif

                {{-- Pesan (dari oldest ke newest: reverse karena query desc) --}}
                @foreach ($messages->getCollection()->reverse() as $chat)
                    @php $isMine = $chat->senderId === $authId; @endphp

                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }} gap-2">
                        {{-- Avatar lawan (hanya tampil untuk pesan dari lawan) --}}
                        @if (!$isMine)
                            @if ($other?->imageURL)
                                <img src="{{ asset('storage/' . $other->imageURL) }}" alt="{{ $displayName }}"
                                    class="w-7 h-7 rounded-full object-cover flex-shrink-0 self-end mb-1">
                            @else
                                <div
                                    class="w-7 h-7 rounded-full bg-nusa-light flex items-center justify-center text-nusa font-bold text-xs uppercase flex-shrink-0 self-end mb-1">
                                    {{ mb_substr($displayName, 0, 1) }}
                                </div>
                            @endif
                        @endif

                        {{-- Bubble --}}
                        <div class="max-w-xs lg:max-w-md group">
                            <div
                                class="px-4 py-2.5 rounded-2xl text-sm leading-relaxed break-words
                                {{ $isMine
                                    ? 'bg-nusa text-white rounded-br-sm'
                                    : 'bg-white text-gray-800 rounded-bl-sm shadow-sm border border-gray-100' }}">
                                {{ $chat->messageText }}
                            </div>
                            <p class="text-[10px] mt-1 text-gray-400 {{ $isMine ? 'text-right' : 'text-left' }}">
                                {{ \Carbon\Carbon::parse($chat->createAt)->format('H:i') }}
                                @if ($isMine)
                                    &nbsp;
                                    @if ($chat->isRead)
                                        <span class="text-nusa">✓✓</span>
                                    @else
                                        <span class="text-gray-300">✓</span>
                                    @endif
                                @endif
                            </p>
                        </div>
                    </div>
                @endforeach

                {{-- Anchor untuk auto-scroll ke bawah --}}
                <div id="chat-bottom"></div>
            </div>

            {{-- Input pesan --}}
            <div class="flex-shrink-0 bg-white border-t border-gray-200 px-4 py-3">
                <form action="{{ route('chat.send', $roomId) }}" method="POST"
                    class="flex items-end gap-2" id="chat-form">
                    @csrf

                    <div class="flex-1 relative">
                        <textarea name="message" id="message-input" rows="1" maxlength="1000"
                            placeholder="Tulis pesan..."
                            class="w-full resize-none rounded-2xl border border-gray-200 bg-gray-50 px-4 py-2.5 pr-12 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-nusa/30 focus:border-nusa transition-all overflow-hidden"
                            style="max-height: 120px;"
                            onkeydown="handleEnterKey(event)"></textarea>
                        <span class="absolute bottom-2.5 right-3 text-[10px] text-gray-300" id="char-count">0/1000</span>
                    </div>

                    <button type="submit"
                        class="flex-shrink-0 w-10 h-10 rounded-full bg-nusa hover:bg-nusa-dark active:bg-nusa-dark text-white flex items-center justify-center transition-colors shadow-sm disabled:opacity-50"
                        id="send-btn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z" />
                        </svg>
                    </button>
                </form>

                @error('message')
                    <p class="text-xs text-red-500 mt-1 ml-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

    </div>

    <script>
        // ── Auto-scroll ke bawah saat halaman load ──
        document.getElementById('chat-bottom')?.scrollIntoView({ behavior: 'instant' });

        // ── Auto-resize textarea ──
        const textarea = document.getElementById('message-input');
        const charCount = document.getElementById('char-count');

        textarea?.addEventListener('input', () => {
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
            charCount.textContent = textarea.value.length + '/1000';
        });

        // ── Kirim dengan Enter (Shift+Enter = newline) ──
        function handleEnterKey(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                const val = document.getElementById('message-input')?.value.trim();
                if (val) document.getElementById('chat-form')?.submit();
            }
        }
    </script>
</x-dynamic-component>