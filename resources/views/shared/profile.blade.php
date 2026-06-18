@php $layout = auth()->user()->role === 'SELLER' ? 'seller-layout' : 'buyer-layout'; @endphp

<x-dynamic-component :component="$layout">
    <x-slot name="pageTitle">Profil Saya</x-slot>

    <style>
        .profile-page {
            max-width: 680px;
            margin: 0 auto;
        }

        /* ── Avatar section ── */
        .avatar-section {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .avatar-wrapper {
            position: relative;
            flex-shrink: 0;
        }

        .avatar-img {
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #E0F2F1;
        }

        .avatar-placeholder {
            width: 5rem;
            height: 5rem;
            border-radius: 50%;
            background: #E0F2F1;
            color: #008B81;
            font-size: 1.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #b2dfdb;
        }

        .avatar-upload-btn {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 1.6rem;
            height: 1.6rem;
            background: #008B81;
            border-radius: 50%;
            border: 2px solid #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.15s;
        }

        .avatar-upload-btn:hover { background: #00736B; }

        .avatar-upload-btn svg {
            width: 0.75rem;
            height: 0.75rem;
            color: #fff;
        }

        .avatar-info h3 {
            font-size: 1rem;
            font-weight: 600;
            color: #111827;
            margin-bottom: 0.2rem;
        }

        .avatar-info p {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 0.6rem;
        }

        .avatar-hint {
            font-size: 0.72rem;
            color: #9ca3af;
        }

        /* ── Card section ── */
        .profile-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.25rem;
        }

        .profile-card-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .profile-card-title svg {
            width: 1rem;
            height: 1rem;
            color: #008B81;
        }

        /* ── Form grid ── */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-grid.single { grid-template-columns: 1fr; }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .form-group.full { grid-column: 1 / -1; }

        .form-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: #374151;
        }

        .form-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.6rem 0.875rem;
            font-size: 0.875rem;
            color: #111827;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
            box-sizing: border-box;
        }

        .form-input:focus {
            border-color: #008B81;
            box-shadow: 0 0 0 3px rgba(0,139,129,0.1);
        }

        .form-input.readonly {
            background: #f9fafb;
            color: #6b7280;
            cursor: not-allowed;
        }

        .form-hint {
            font-size: 0.7rem;
            color: #9ca3af;
        }

        /* ── Password toggle ── */
        .input-wrapper {
            position: relative;
        }

        .input-wrapper .form-input {
            padding-right: 2.5rem;
        }

        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            padding: 0;
            display: flex;
        }

        .toggle-password svg { width: 1rem; height: 1rem; }
        .toggle-password:hover { color: #6b7280; }

        /* ── Error ── */
        .form-error {
            font-size: 0.72rem;
            color: #ef4444;
        }

        /* ── Footer buttons ── */
        .form-footer {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.25rem;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: #008B81;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.55rem 1.25rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: background 0.15s;
        }

        .btn-save:hover { background: #00736B; }
        .btn-save svg { width: 0.9rem; height: 0.9rem; }

        .btn-save:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* ── Seller info badge ── */
        .seller-info-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .seller-info-row:last-child { border-bottom: none; }

        .seller-info-label {
            font-size: 0.8rem;
            color: #6b7280;
        }

        .seller-info-value {
            font-size: 0.875rem;
            font-weight: 500;
            color: #111827;
        }

        /* ── Loading spinner ── */
        .spinner {
            display: none;
            width: 0.9rem;
            height: 0.9rem;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>

    <div class="profile-page">

        {{-- ── Avatar & nama ── --}}
        <div class="avatar-section">
            <div class="avatar-wrapper">
                @if(auth()->user()->imageURL)
                    <img src="{{ Storage::url(auth()->user()->imageURL) }}"
                         class="avatar-img" id="avatarPreview" alt="Foto profil">
                @else
                    <div class="avatar-placeholder" id="avatarPlaceholder">
                        {{ strtoupper(substr(auth()->user()->username, 0, 1)) }}
                    </div>
                    <img src="" class="avatar-img" id="avatarPreview"
                         style="display:none" alt="Foto profil">
                @endif

                <label for="imageInput" class="avatar-upload-btn" title="Ganti foto">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </label>
            </div>

            <div class="avatar-info">
                <h3>{{ auth()->user()->username }}</h3>
                <p>{{ auth()->user()->email }}</p>
                <span class="avatar-hint">JPG, PNG, atau WEBP. Maks. 2MB.</span>
            </div>
        </div>

        {{-- ── Form info akun ── --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
            @csrf
            @method('PUT')

            {{-- Input file tersembunyi --}}
            <input type="file" id="imageInput" name="image" accept="image/jpg,image/jpeg,image/png,image/webp"
                   class="hidden" style="display:none">

            {{-- ── Informasi Akun ── --}}
            <div class="profile-card">
                <div class="profile-card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Akun
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-input"
                               value="{{ old('username', auth()->user()->username) }}"
                               placeholder="Username kamu">
                        @error('username')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input readonly" readonly
                               value="{{ auth()->user()->email }}">
                        <span class="form-hint">Email tidak dapat diubah.</span>
                    </div>

                    <div class="form-group full">
                        <label class="form-label">Nomor HP</label>
                        <input type="text" name="phone" class="form-input"
                               value="{{ old('phone', auth()->user()->phone) }}"
                               placeholder="08xxxxxxxxxx">
                        @error('phone')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ── Ganti Password ── --}}
            <div class="profile-card">
                <div class="profile-card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Ganti Password
                </div>

                <div class="form-grid single">
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="passwordInput"
                                   class="form-input" placeholder="Min. 8 karakter">
                            <button type="button" class="toggle-password" onclick="togglePass('passwordInput', this)">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" id="eyeIcon1">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="passwordConfirmInput"
                                   class="form-input" placeholder="Ulangi password baru">
                            <button type="button" class="toggle-password" onclick="togglePass('passwordConfirmInput', this)">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Info Seller (readonly, hanya tampil) ── --}}
            @if(auth()->user()->role === 'SELLER' && auth()->user()->seller)
                <div class="profile-card">
                    <div class="profile-card-title">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Info Seller
                        <span style="font-size:0.72rem;color:#9ca3af;font-weight:400;margin-left:0.25rem">
                            — hubungi admin untuk mengubah
                        </span>
                    </div>

                    <div>
                        <div class="seller-info-row">
                            <span class="seller-info-label">NIK</span>
                            <span class="seller-info-value">
                                {{ substr(auth()->user()->seller->nik, 0, 4) . '••••••••••••' }}
                            </span>
                        </div>
                        <div class="seller-info-row">
                            <span class="seller-info-label">Bank</span>
                            <span class="seller-info-value">{{ auth()->user()->seller->bankName }}</span>
                        </div>
                        <div class="seller-info-row">
                            <span class="seller-info-label">No. Rekening</span>
                            <span class="seller-info-value">{{ auth()->user()->seller->accountNumber }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ── Tombol simpan ── --}}
            <div class="form-footer">
                <button type="submit" class="btn-save" id="saveBtn">
                    <div class="spinner" id="spinner"></div>
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" id="saveIcon">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>

    {{-- JS dikumpul di bawah --}}
    <script>
        /* ── Preview foto sebelum upload ── */
        const imageInput    = document.getElementById('imageInput');
        const avatarPreview = document.getElementById('avatarPreview');
        const avatarPlaceholder = document.getElementById('avatarPlaceholder');

        imageInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            // Validasi ukuran di sisi client
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file maksimal 2MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreview.src = e.target.result;
                avatarPreview.style.display = 'block';
                if (avatarPlaceholder) avatarPlaceholder.style.display = 'none';
            };
            reader.readAsDataURL(file);
        });

        /* ── Toggle show/hide password ── */
        function togglePass(inputId, btn) {
            const input = document.getElementById(inputId);
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';

            // Ganti ikon
            btn.innerHTML = isHidden
                ? `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7
                              a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243
                              M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29
                              m7.532 7.532l3.29 3.29M3 3l3.59 3.59
                              m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7
                              a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                   </svg>`
                : `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:1rem;height:1rem">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                           d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7
                              -1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                   </svg>`;
        }

        /* ── Loading state saat submit ── */
        document.getElementById('profileForm').addEventListener('submit', function () {
            const btn     = document.getElementById('saveBtn');
            const spinner = document.getElementById('spinner');
            const icon    = document.getElementById('saveIcon');

            btn.disabled       = true;
            spinner.style.display = 'block';
            icon.style.display    = 'none';
        });
    </script>

</x-dynamic-component>