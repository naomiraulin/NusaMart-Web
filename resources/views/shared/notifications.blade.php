@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Notifikasi</x-slot>

    <style>
        .notif-page {
            max-width: 760px;
            margin: 0 auto;
        }

        .notif-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .notif-header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .notif-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
        }

        .notif-badge-count {
            background: #ef4444;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.15rem 0.55rem;
            border-radius: 9999px;
            line-height: 1.4;
        }

        .btn-mark-all {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: #008B81;
            background: #E0F2F1;
            border: none;
            border-radius: 0.375rem;
            padding: 0.45rem 0.9rem;
            cursor: pointer;
            transition: background 0.15s;
            text-decoration: none;
        }

        .btn-mark-all:hover {
            background: #b2dfdb;
        }

        .notif-tabs {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .tab-btn {
            font-size: 0.8rem;
            font-weight: 500;
            padding: 0.4rem 1rem;
            border-radius: 9999px;
            border: 1px solid #e5e7eb;
            background: #fff;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.15s;
        }

        .tab-btn:hover,
        .tab-btn.active {
            background: #008B81;
            color: #fff;
            border-color: #008B81;
        }

        .notif-list {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .notif-item {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.625rem;
            padding: 0.875rem 1rem;
            transition: box-shadow 0.15s, border-color 0.15s;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            position: relative;
        }

        .notif-item:hover {
            box-shadow: 0 2px 8px rgba(0,139,129,0.1);
            border-color: #b2dfdb;
        }

        .notif-item.unread {
            background: #f0faf9;
            border-color: #b2dfdb;
        }

        .notif-icon {
            flex-shrink: 0;
            width: 2.25rem;
            height: 2.25rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notif-icon.order   { background: #dbeafe; color: #2563eb; }
        .notif-icon.sistem  { background: #f3f4f6; color: #6b7280; }
        .notif-icon.payment { background: #d1fae5; color: #059669; }

        .notif-icon svg {
            width: 1rem;
            height: 1rem;
        }

        .notif-content {
            flex: 1;
            min-width: 0;
        }

        .notif-item-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.15rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notif-item.unread .notif-item-title {
            color: #00736B;
        }

        .notif-body {
            font-size: 0.8rem;
            color: #6b7280;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .notif-time {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 0.3rem;
        }

        .notif-dot {
            flex-shrink: 0;
            width: 0.5rem;
            height: 0.5rem;
            background: #008B81;
            border-radius: 50%;
            margin-top: 0.375rem;
        }

        .notif-empty {
            text-align: center;
            padding: 4rem 1rem;
            color: #9ca3af;
        }

        .notif-empty svg {
            width: 3.5rem;
            height: 3.5rem;
            margin: 0 auto 1rem;
            color: #d1d5db;
        }

        .notif-empty p {
            font-size: 0.875rem;
        }

        .notif-pagination {
            margin-top: 1.25rem;
            display: flex;
            justify-content: center;
        }

        .mark-read-form { display: none; }
    </style>

    <div class="notif-page">

        {{-- Header --}}
        <div class="notif-header">
            <div class="notif-header-left">
                <span class="notif-title">Semua Notifikasi</span>
                @if($unreadCount > 0)
                    <span class="notif-badge-count">{{ $unreadCount }} baru</span>
                @endif
            </div>

            @if($unreadCount > 0)
                <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-mark-all">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:0.9rem;height:0.9rem">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Tandai semua dibaca
                    </button>
                </form>
            @endif
        </div>

        {{-- Filter tabs --}}
        <div class="notif-tabs">
            <button class="tab-btn active" data-filter="all">Semua</button>
            <button class="tab-btn" data-filter="ORDER">Pesanan</button>
            <button class="tab-btn" data-filter="SISTEM">Sistem</button>
        </div>

        {{-- Notification list --}}
        <div class="notif-list" id="notifList">
            @forelse($notifications as $notif)
                <div class="notif-item {{ !$notif->isRead ? 'unread' : '' }}"
                     data-type="{{ $notif->type }}"
                     data-id="{{ $notif->idNotif }}"
                     onclick="handleNotifClick(this)">

                    {{-- Icon --}}
                    <div class="notif-icon {{ strtolower($notif->type) }}">
                        @if($notif->type === 'ORDER')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        @elseif($notif->type === 'PAYMENT')
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        @else
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="notif-content">
                        <div class="notif-item-title">{{ $notif->title }}</div>
                        <div class="notif-body">{{ $notif->body }}</div>
                        <div class="notif-time">
                            {{ \Carbon\Carbon::parse($notif->createAt)->diffForHumans() }}
                        </div>
                    </div>

                    {{-- Unread dot --}}
                    @if(!$notif->isRead)
                        <div class="notif-dot"></div>
                    @endif

                    {{-- Hidden mark-as-read form --}}
                    <form class="mark-read-form"
                          action="{{ route('notifications.markAsRead', $notif->idNotif) }}"
                          method="POST">
                        @csrf
                    </form>
                </div>
            @empty
                <div class="notif-empty">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <p>Belum ada notifikasi</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="notif-pagination">
                {{ $notifications->links() }}
            </div>
        @endif

    </div>

    {{-- JS dikumpul di bawah --}}
    <script>
        const tabs  = document.querySelectorAll('.tab-btn');
        const items = document.querySelectorAll('.notif-item');

        tabs.forEach(tab => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                tab.classList.add('active');

                const filter = tab.dataset.filter;
                items.forEach(item => {
                    item.style.display =
                        (filter === 'all' || item.dataset.type === filter) ? 'flex' : 'none';
                });
            });
        });

        function handleNotifClick(el) {
            if (!el.classList.contains('unread')) return;

            const form      = el.querySelector('.mark-read-form');
            const csrfToken = form.querySelector('input[name="_token"]').value;

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            }).then(() => {
                el.classList.remove('unread');
                const dot = el.querySelector('.notif-dot');
                if (dot) dot.remove();
                updateUnreadBadge(-1);
            }).catch(() => {
                form.style.display = 'block';
                form.submit();
            });
        }

        function updateUnreadBadge(delta) {
            const badge = document.querySelector('.notif-badge-count');
            if (!badge) return;

            let current = parseInt(badge.textContent) || 0;
            current += delta;

            if (current <= 0) {
                badge.remove();
                document.querySelector('.btn-mark-all')?.closest('form')?.remove();
            } else {
                badge.textContent = `${current} baru`;
            }
        }
    </script>

</x-dynamic-component>