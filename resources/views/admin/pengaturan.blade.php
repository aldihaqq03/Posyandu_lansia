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

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fa-solid fa-triangle-exclamation"></i>
            Terdapat kesalahan pada isian form Anda. Mohon periksa kembali.
        </div>
    @endif

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
        <form action="{{ route('pengaturan.profil') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group full">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                    @error('nama') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>
                
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    @error('email') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Nomor Telepon / WhatsApp</label>
                    <input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp', $user->whatsapp) }}">
                    @error('whatsapp') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ old('nik', $user->nik) }}">
                    @error('nik') <small style="color:var(--danger)">{{ $message }}</small> @enderror
                </div>

                <div class="form-group">
                    <label>Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $user->jabatan) }}" required readonly style="background:#f3f4f6; cursor:not-allowed;">
                    <small style="color:#6b7280; font-size:12px;">Jabatan tidak dapat diubah secara mandiri.</small>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Profil
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
</script>
@endpush
@endsection
