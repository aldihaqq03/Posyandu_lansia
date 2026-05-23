@extends('layout.sidebar')

@section('title', 'Pengaturan')

@push('styles')
    @vite('resources/css/cssAdmin/pengaturan.css')
@endpush

@section('content')
<div class="pengaturan-wrapper">
    <div class="pengaturan-header">
        <h1>Pengaturan Akun</h1>
        <p>Kelola informasi profil dan keamanan akun Anda</p>
    </div>



    <!-- TABS -->
    <div class="tabs">
        <button class="tab-btn active" onclick="openTab(event, 'tabProfil')">
            <i class="fa-solid fa-user-pen"></i> Profil Pengguna
        </button>
        <button class="tab-btn" onclick="openTab(event, 'tabPassword')">
            <i class="fa-solid fa-lock"></i> Ganti Password
        </button>
    </div>

    <!-- TAB: PROFIL -->
    <div id="tabProfil" class="tab-content active">
        @php
            $userName = trim($user->nama ?? 'User');
            $nameParts = preg_split('/\s+/', $userName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
            $userInitials = collect($nameParts)
                ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
                ->take(2)
                ->implode('');
            if ($userInitials === '') {
                $userInitials = strtoupper(substr($userName, 0, 2));
            }
            $petugasPhoto = $user->petugas?->foto;
        @endphp
        <form action="{{ route('pengaturan.profil') }}" method="POST" enctype="multipart/form-data" id="form-profil-pengguna">
            @csrf
            <div class="profile-edit-toolbar">
                <button type="button" class="btn-edit-profile" id="btn-toggle-edit-profil">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                </button>
            </div>

            <div class="profile-photo-card">
                <div class="profile-photo-zone" id="profile-photo-zone">
                    <div class="profile-photo-preview" id="profile-photo-preview">
                        @if($petugasPhoto)
                            <img src="{{ asset('storage/' . $petugasPhoto) }}" alt="Foto Profil">
                        @else
                            <div class="profile-photo-fallback">{{ $userInitials }}</div>
                        @endif
                    </div>
                    <div class="profile-photo-copy">
                        <strong>Foto Profil</strong>
                        <p>Klik area foto untuk mengganti foto profil.</p>
                    </div>
                    <input type="file" name="foto" id="foto" accept="image/*" hidden>
                </div>
                @error('foto') <small style="color:var(--danger); display:block; margin-top:8px;">{{ $message }}</small> @enderror
            </div>

            <div class="form-grid profile-form-grid">
                <div class="form-group full">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" data-profile-field required readonly>
                    @error('nama') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" data-profile-field required readonly>
                    @error('email') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Nomor Telepon / WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $user->whatsapp) }}" data-profile-field readonly>
                    @error('whatsapp') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}" data-profile-field readonly>
                    @error('nik') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $user->jabatan) }}" required readonly style="background:#f3f4f6; cursor:not-allowed;">
                    <small style="color:#6b7280; font-size:12px;">Jabatan tidak dapat diubah secara mandiri.</small>
                </div>
            </div>

            <button type="button" class="btn-submit" id="btn-save-profil">
                <i class="fa-solid fa-pen-to-square"></i> Edit Profil
            </button>
        </form>
    </div>

    <!-- TAB: PASSWORD -->
    <div id="tabPassword" class="tab-content">
        <form action="{{ route('pengaturan.password') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Password Lama</label>
                    <input type="password" name="current_password" class="form-control" required placeholder="Masukkan password saat ini">
                    @error('current_password') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>
                
                <div class="form-group">
                    <label>Password Baru</label>
                    <input type="password" name="new_password" class="form-control" required placeholder="Minimal 6 karakter">
                    @error('new_password') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Ulangi password baru">
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-key"></i> Update Password
            </button>
        </form>
    </div>

</div>

@push('scripts')
<script>
    const profileForm = document.getElementById('form-profil-pengguna');
    const btnSaveProfil = document.getElementById('btn-save-profil');
    const profilePhotoZone = document.getElementById('profile-photo-zone');
    const profilePhotoInput = document.getElementById('foto');
    const profilePhotoPreview = document.getElementById('profile-photo-preview');
    const profileFields = document.querySelectorAll('[data-profile-field]');
    const profileTabHasErrors = {{ $errors->any() ? 'true' : 'false' }};
    let profileEditMode = false;

    function setProfileEditMode(enabled) {
        profileEditMode = enabled;

        profileFields.forEach((field) => {
            field.readOnly = !enabled;
        });

        if (profilePhotoInput) {
            profilePhotoInput.disabled = !enabled;
        }

        profilePhotoZone?.classList.toggle('is-editing', enabled);
        btnSaveProfil.innerHTML = enabled
            ? '<i class="fa-solid fa-floppy-disk"></i> Simpan Profil'
            : '<i class="fa-solid fa-pen-to-square"></i> Edit Profil';
    }

    btnSaveProfil?.addEventListener('click', function () {
        if (!profileEditMode) {
            setProfileEditMode(true);
            return;
        }

        profileForm?.submit();
    });

    profilePhotoZone?.addEventListener('click', function () {
        if (!profileEditMode || !profilePhotoInput) return;
        profilePhotoInput.click();
    });

    profilePhotoInput?.addEventListener('change', function () {
        const file = this.files && this.files[0];
        if (!file || !profilePhotoPreview) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            profilePhotoPreview.innerHTML = `<img src="${e.target.result}" alt="Foto Profil">`;
        };
        reader.readAsDataURL(file);
    });

    function openTab(evt, tabName) {
        // Hide all tab contents
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        
        // Remove active class from all tab buttons
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        
        // Show current tab, and add active class to button
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    setProfileEditMode(profileTabHasErrors);
</script>
@endpush
@endsection
