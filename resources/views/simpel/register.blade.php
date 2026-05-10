<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIMPEL Register</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite([
        'resources/css/register.css',
        'resources/js/register.js'
    ])

</head>

<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <x-logo style="width: 80px; height: 80px; color: #0ea5e9; margin: 0 auto; display: block;" />
            </div>

            <h2 class="title-main">Daftar Akun SIMPEL</h2>
            <h4 class="title">SISTEM INFORMASI PEDULI LANSIA</h4>

            <form id="registerForm" action="{{ route('proses_register') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <div class="input-wrapper">
                        <i class="fa fa-user"></i>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required>
                    </div>
                    @error('nama')
                        <span class="error" style="color: #ef4444; font-size: 0.875rem;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <i class="fa fa-envelope"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                        <span class="error" style="color: #ef4444; font-size: 0.875rem;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>NIK</label>
                    <div class="input-wrapper">
                        <i class="fa fa-id-card"></i>
                        <input type="text" id="nik" name="nik" value="{{ old('nik') }}" maxlength="16" required>
                    </div>
                    @error('nik')
                        <span class="error" style="color: #ef4444; font-size: 0.875rem;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Nomor WhatsApp</label>
                    <div class="input-wrapper">
                        <i class="fa fa-phone"></i>
                        <input type="text" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="62812xxxxx" required>
                    </div>
                    @error('whatsapp')
                        <span class="error" style="color: #ef4444; font-size: 0.875rem;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Jabatan</label>
                    <div class="input-wrapper">
                        <i class="fa fa-user-tie"></i>
                        <select name="jabatan" required>
                            <option value="">Pilih Jabatan</option>
                            <option value="kader" @selected(old('jabatan') === 'kader')>Kader</option>
                            <option value="kepala_kader" @selected(old('jabatan') === 'kepala_kader')>Kepala Kader</option>
                        </select>
                    </div>
                    @error('jabatan')
                        <span class="error" style="color: #ef4444; font-size: 0.875rem;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-wrapper">
                        <i class="fa fa-lock"></i>
                        <input type="password" id="password" name="password" required>
                        <i class="fa fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
                    </div>
                    @error('password')
                        <span class="error" style="color: #ef4444; font-size: 0.875rem;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <div class="input-wrapper">
                        <i class="fa fa-lock"></i>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                        <i class="fa fa-eye password-toggle" onclick="togglePassword('password_confirmation', this)"></i>
                    </div>
                    @error('password_confirmation')
                        <span class="error" style="color: #ef4444; font-size: 0.875rem;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">Daftar Sekarang</button>

                <div class="login-link">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#0ea5e9'
            });
        @endif

        function togglePassword(fieldId, icon) {
            const input = document.getElementById(fieldId);
            if (!input) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>

