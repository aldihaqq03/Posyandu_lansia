<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil — SIMPEL</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/auth.css'])
</head>

<body>

    <div class="shape-1"></div>
    <div class="shape-2"></div>

    <div class="auth-wrapper wide">

        {{-- Brand --}}
        <a href="{{ url('/') }}" class="auth-brand">
            <x-logo style="height: 44px; width: 44px; color: #0ea5e9;" />
            <span class="auth-brand-name">SIM<span>PEL</span></span>
        </a>

        <div class="auth-card">

            <span class="auth-badge">
                <i class="fa-solid fa-clock-rotate-left"></i>
                Satu langkah lagi
            </span>

            <p class="auth-card-title">Lengkapi profil Anda</p>
            <p class="auth-card-sub">Data ini dibutuhkan sebelum Anda dapat mengakses sistem</p>

            {{-- Info jabatan --}}
            <div class="auth-alert">
                <i class="fa-solid fa-circle-info"></i>
                Jabatan Anda telah ditetapkan oleh admin dan tidak dapat diubah melalui halaman ini.
            </div>

            {{-- Error validasi --}}
            @if ($errors->any())
                <div class="auth-alert error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>
                        <strong style="display: block; margin-bottom: 4px;">Mohon periksa kembali:</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('profile.lengkapi.update') }}" enctype="multipart/form-data">
                @csrf

                {{-- Nama & NIK --}}
                <div class="auth-field-row">
                    <div class="auth-field">
                        <label for="nama">Nama lengkap</label>
                        <input
                            id="nama"
                            type="text"
                            name="nama"
                            value="{{ old('nama', $petugas?->nama) }}"
                            placeholder="Nama lengkap Anda"
                            class="{{ $errors->has('nama') ? 'is-invalid' : '' }}"
                            required
                        />
                        @error('nama')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="nik">NIK (16 digit)</label>
                        <input
                            id="nik"
                            type="text"
                            name="nik"
                            value="{{ old('nik', $petugas?->nik) }}"
                            placeholder="3501xxxxxxxxxxxxxxx"
                            maxlength="16"
                            class="{{ $errors->has('nik') ? 'is-invalid' : '' }}"
                            required
                        />
                        @error('nik')
                            <span class="field-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                {{-- WhatsApp --}}
                <div class="auth-field">
                    <label for="whatsapp">Nomor WhatsApp</label>
                    <input
                        id="whatsapp"
                        type="tel"
                        name="whatsapp"
                        value="{{ old('whatsapp', $user->whatsapp) }}"
                        placeholder="08xxxxxxxxxx"
                        maxlength="15"
                        class="{{ $errors->has('whatsapp') ? 'is-invalid' : '' }}"
                        required
                    />
                    @error('whatsapp')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Foto (opsional) --}}
                <div class="auth-field">
                    <label>Foto profil <span style="color: #475569; font-weight: 400;">(opsional)</span></label>

                    <label for="foto" class="foto-upload" id="foto-label">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <div class="foto-label">Klik untuk unggah foto</div>
                        <div class="foto-hint">JPG, JPEG, PNG — maksimal 2MB</div>
                        <input id="foto" type="file" name="foto" accept="image/jpg,image/jpeg,image/png" />
                    </label>

                    <div id="foto-preview">
                        <img id="foto-img" src="#" alt="Preview foto" />
                        <div style="font-size: 0.78rem; color: #475569; margin-top: 0.4rem;" id="foto-name"></div>
                    </div>

                    @error('foto')
                        <span class="field-error">{{ $message }}</span>
                    @enderror
                </div>

                <hr class="auth-divider">

                <button type="submit" class="auth-btn">
                    <i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i>
                    Simpan & Lanjutkan ke Dashboard
                </button>

            </form>

        </div>

        {{-- Logout --}}
        <p class="auth-footer-text">
            Bukan akun Anda?
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Keluar
            </a>
        </p>

        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
        </form>

    </div>

    <script>
        // Preview foto sebelum upload
        document.getElementById('foto').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const preview = document.getElementById('foto-preview');
            const img = document.getElementById('foto-img');
            const name = document.getElementById('foto-name');

            const reader = new FileReader();
            reader.onload = function (ev) {
                img.src = ev.target.result;
                name.textContent = file.name;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    </script>

</body>
</html> 