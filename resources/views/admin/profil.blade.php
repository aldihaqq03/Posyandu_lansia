@extends('layout.sidebar')

@section('title', 'Profil Saya')

@push('styles')
<style>
    .profil-wrapper {
        max-width: 920px;
        margin: 0 auto;
    }

    .page-header { margin-bottom: 28px; }

    .page-header h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 4px;
    }

    .page-header p {
        font-size: 14px;
        color: #718096;
    }

    .alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 13px 16px;
        border-radius: 10px;
        font-size: 14px;
        margin-bottom: 22px;
    }

    .alert-success {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534;
    }

    .alert-error {
        background: #fff5f5;
        border: 1px solid #fed7d7;
        color: #c53030;
    }

    .profil-body {
        display: grid;
        grid-template-columns: 230px 1fr;
        gap: 22px;
        align-items: start;
    }

    /* ===== CARD KIRI ===== */
    .card-avatar {
        background: #fff;
        border-radius: 16px;
        padding: 26px 18px;
        text-align: center;
        box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    }

    .avatar-wrap {
        position: relative;
        display: inline-block;
        margin-bottom: 14px;
    }

    .avatar-wrap img {
        width: 88px;
        height: 88px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e2e8f0;
    }

    .avatar-edit {
        position: absolute;
        bottom: 2px; right: 2px;
        width: 26px; height: 26px;
        background: #129481;
        border: 2px solid #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #fff;
        font-size: 10px;
    }

    .av-name {
        font-size: 15px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 3px;
    }

    .av-jabatan {
        font-size: 12px;
        color: #718096;
        margin-bottom: 16px;
    }

    .stats-row {
        display: flex;
        justify-content: center;
        gap: 20px;
        border-top: 1px solid #f0f0f0;
        padding-top: 14px;
        margin-bottom: 16px;
    }

    .stat-num {
        font-size: 20px;
        font-weight: 700;
        color: #1a202c;
        line-height: 1;
    }

    .stat-lbl {
        font-size: 10px;
        color: #a0aec0;
        margin-top: 3px;
        letter-spacing: 0.04em;
    }

    .badge-ok {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        padding: 11px 12px;
        display: flex;
        gap: 9px;
        align-items: flex-start;
        text-align: left;
    }

    .badge-ok .bi { color: #22c55e; font-size: 15px; margin-top: 1px; }
    .badge-ok .bt { font-size: 12px; font-weight: 700; color: #166534; margin-bottom: 2px; }
    .badge-ok .bd { font-size: 11px; color: #4ade80; line-height: 1.4; }

    /* ===== CARD KANAN ===== */
    .card-form {
        background: #fff;
        border-radius: 16px;
        padding: 28px;
        box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    }

    .sec-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sec-title i { color: #129481; }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 24px;
    }

    .fg { display: flex; flex-direction: column; gap: 5px; }
    .fg.span2 { grid-column: 1 / -1; }

    .fg label {
        font-size: 11px;
        font-weight: 600;
        color: #4a5568;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .fg input {
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 13px;
        font-size: 14px;
        color: #1a202c;
        font-family: inherit;
        transition: border-color 0.2s;
        outline: none;
        background: #fff;
        width: 100%;
        box-sizing: border-box;
    }

    .fg input:focus {
        border-color: #129481;
        box-shadow: 0 0 0 3px rgba(18,148,129,0.1);
    }

    .pw-wrap { position: relative; }
    .pw-wrap input { padding-right: 40px; }

    .pw-toggle {
        position: absolute;
        right: 11px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #a0aec0;
        font-size: 13px;
        cursor: pointer;
    }

    .divider {
        border: none;
        border-top: 1px solid #f0f0f0;
        margin: 4px 0 20px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 4px;
    }

    .btn-batal {
        padding: 10px 22px;
        border: 1.5px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #4a5568;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
    }

    .btn-batal:hover { border-color: #cbd5e0; background: #f7fafc; }

    .btn-simpan {
        padding: 10px 22px;
        border: none;
        border-radius: 8px;
        background: #129481;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: background 0.2s;
    }

    .btn-simpan:hover { background: #0e7a6a; }
</style>
@endpush

@section('content')
<div class="profil-wrapper">

    <div class="page-header">
        <h1>Profil Saya</h1>
        <p>Kelola informasi pribadi dan pengaturan akun petugas Anda</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-circle-check"></i> {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-error">
        <i class="fas fa-circle-exclamation"></i>
        <div>
            @foreach($errors->all() as $err)
                {{ $err }}<br>
            @endforeach
        </div>
    </div>
    @endif

    <div class="profil-body">

        {{-- KIRI --}}
        <div class="card-avatar">
            <div class="avatar-wrap">
                <img id="preview-avatar"
                    src="{{ Auth::user()->avatar
                        ? asset('storage/' . Auth::user()->avatar)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=129481&color=fff&size=200' }}"
                    alt="Foto Profil">
                <label class="avatar-edit" for="avatar-file" title="Ganti Foto">
                    <i class="fas fa-camera"></i>
                </label>
            </div>

            <p class="av-name">{{ Auth::user()->name }}</p>
            <p class="av-jabatan">
                {{ Auth::user()->jabatan ?? Auth::user()->role }}
                @if(Auth::user()->wilayah_posyandu)
                    — {{ Auth::user()->wilayah_posyandu }}
                @endif
            </p>

            <div class="stats-row">
                <div>
                    <div class="stat-num">{{ $totalPemeriksaan ?? 0 }}</div>
                    <div class="stat-lbl">TUGAS SELESAI</div>
                </div>
                <div>
                    <div class="stat-num">{{ $totalLansia ?? 0 }}</div>
                    <div class="stat-lbl">LANSIA BINAAN</div>
                </div>
            </div>

            <div class="badge-ok">
                <span class="bi"><i class="fas fa-shield-halved"></i></span>
                <div>
                    <p class="bt">Akun Terverifikasi</p>
                    <p class="bd">Status akun Anda aktif dan memiliki akses penuh ke fitur pencatatan Posyandu.</p>
                </div>
            </div>
        </div>

        {{-- KANAN --}}
        <div class="card-form">
            <form action="/profil" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="file" id="avatar-file" name="avatar" accept="image/*" style="display:none">

                <p class="sec-title"><i class="fas fa-user"></i> Detail Pribadi</p>

                <div class="form-grid">
                    <div class="fg">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name"
                            value="{{ old('name', Auth::user()->name) }}" required>
                    </div>
                    <div class="fg">
                        <label>Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" name="nik" maxlength="16"
                            value="{{ old('nik', Auth::user()->nik ?? '') }}"
                            placeholder="16 digit NIK">
                    </div>
                    <div class="fg">
                        <label>Jabatan</label>
                        <input type="text" name="jabatan"
                            value="{{ old('jabatan', Auth::user()->jabatan ?? '') }}"
                            placeholder="Jabatan Anda">
                    </div>
                    <div class="fg">
                        <label>Wilayah Posyandu</label>
                        <input type="text" name="wilayah_posyandu"
                            value="{{ old('wilayah_posyandu', Auth::user()->wilayah_posyandu ?? '') }}"
                            placeholder="Nama Posyandu / RW">
                    </div>
                    <div class="fg span2">
                        <label>Nomor WhatsApp</label>
                        <input type="text" name="no_whatsapp"
                            value="{{ old('no_whatsapp', Auth::user()->no_whatsapp ?? '') }}"
                            placeholder="+62 8xx-xxxx-xxxx">
                    </div>
                </div>

                <hr class="divider">

                <p class="sec-title"><i class="fas fa-gear"></i> Pengaturan Akun</p>

                <div class="form-grid">
                    <div class="fg span2">
                        <label>Email Utama</label>
                        <input type="email" name="email"
                            value="{{ old('email', Auth::user()->email) }}" required>
                    </div>
                    <div class="fg">
                        <label>Kata Sandi Baru</label>
                        <div class="pw-wrap">
                            <input type="password" name="password" id="pw1"
                                placeholder="Kosongkan jika tidak diubah">
                            <button type="button" class="pw-toggle" onclick="togglePw('pw1', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="fg">
                        <label>Konfirmasi Kata Sandi</label>
                        <div class="pw-wrap">
                            <input type="password" name="password_confirmation" id="pw2"
                                placeholder="Ulangi kata sandi baru">
                            <button type="button" class="pw-toggle" onclick="togglePw('pw2', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-batal" onclick="batalkan()">Batalkan</button>
                    <button type="submit" class="btn-simpan">
                        <i class="fas fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/profil.js') }}"></script>
@endpush