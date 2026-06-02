@extends('layout.sidebar')

@section('title', 'Data Petugas')

@push('styles')
    @vite('resources/css/app.css')
    @vite('resources/css/cssAdmin/data_petugas.css')
    <style>
        /* Password toggle di dalam field */
        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 45px;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #94a3b8;
            font-size: 1.2rem;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
        }

        .toggle-password:hover {
            color: #3b82f6;
        }
    </style>
@endpush

@section('content')

    @php
        $currentRole = strtolower(Auth::user()->jabatan ?? '');
        $isSuperAdmin = $currentRole === 'super_admin';
    @endphp

    <div class="petugas-page">
        <div class="container">

            <div class="page-header">
                <div class="header-left">
                    <h1 class="page-title">Data Petugas</h1>
                    <p class="page-subtitle">Kelola data seluruh petugas posyandu yang terdaftar dalam sistem.</p>
                </div>
                <button type="button" class="btn-primary" id="btn-tambah-petugas">
                    <i class="fa fa-user-plus"></i> Tambah Petugas
                </button>
            </div>

            <!-- STATISTIK -->
            <div class="stats-grid">
                <div class="stat-card border-primary">
                    <span class="stat-label">TOTAL PETUGAS</span>
                    <div class="stat-content">
                        <span class="stat-number">{{ $total }}</span>
                        <i class="fa fa-users stat-icon-fa color-primary"></i>
                    </div>
                </div>
                <div class="stat-card border-success">
                    <span class="stat-label">AKTIF</span>
                    <div class="stat-content">
                        <span class="stat-number color-success">{{ $aktif }}</span>
                        <i class="fa fa-check-circle stat-icon-fa color-success"></i>
                    </div>
                </div>
                <div class="stat-card border-warning">
                    <span class="stat-label">PENDING</span>
                    <div class="stat-content">
                        <span class="stat-number color-warning">{{ $pending }}</span>
                        <i class="fa fa-clock stat-icon-fa color-warning"></i>
                    </div>
                </div>
            </div>

            <!-- TABEL -->
            <div class="table-container">
                <div class="table-header-actions">
                    <div class="search-wrapper">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" id="searchPetugas" placeholder="Cari nama petugas...">
                    </div>
                    <select id="filterStatus" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="aktif">Aktif</option>
                        <option value="pending">Pending</option>
                    </select>
                </div>
                <div class="table-scroll">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Petugas</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($petugas as $p)
                                <tr data-nama="{{ strtolower($p->nama) }}" data-status="{{ strtolower($p->status) }}">
                                    <td class="user-cell">
                                        @php
                                            $petugasName = trim($p->nama ?? 'Petugas');
                                            $petugasParts =
                                                preg_split('/\s+/', $petugasName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                                            $petugasInitials = collect($petugasParts)
                                                ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                                ->take(2)
                                                ->implode('');
                                            if ($petugasInitials === '') {
                                                $petugasInitials = strtoupper(substr($petugasName, 0, 2));
                                            }
                                        @endphp
                                        @if ($p->foto)
                                            <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto {{ $p->nama }}">
                                        @else
                                            <div class="user-avatar-fallback">{{ $petugasInitials }}</div>
                                        @endif
                                        <div class="user-cell-copy">
                                            <strong>{{ $p->nama }}</strong>
                                            <small class="user-nik">NIK: {{ $p->nik }}</small>
                                            <small class="user-email">{{ $p->email }}</small>
                                        </div>
                                    </td>
                                    <td><span class="badge blue">{{ str_replace('_', ' ', $p->jabatan) }}</span></td>
                                    <td>
                                        @if ($p->status == 'aktif')
                                            <span class="status aktif">● Aktif</span>
                                        @else
                                            <span class="status pending">● Pending</span>
                                        @endif
                                    </td>
                                    <td class="aksi">
                                        <div class="aksi-wrap">
                                            <button type="button" class="btn-open-edit-petugas"
                                                data-petugas-id="{{ $p->id_petugas }}"
                                                data-petugas-nama="{{ e($p->nama) }}"
                                                data-petugas-nik="{{ e($p->nik) }}"
                                                data-petugas-jabatan="{{ e($p->jabatan) }}"
                                                data-petugas-no-hp="{{ e($p->no_hp) }}"
                                                data-petugas-email="{{ e($p->email) }}"
                                                data-update-url-template="{{ url('/petugas/update/__ID__') }}"
                                                style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; background: white; color: #0F766E; border: 1px solid #0F766E; cursor: pointer; border-radius: 4px; height: 32px;">
                                                <i class="fa-solid fa-pen-to-square"></i> Edit
                                            </button>
                                            <form action="{{ route('petugas.destroy', $p->id_petugas) }}" method="POST"
                                                style="display:inline; margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button onclick="return confirm('Hapus data petugas?')"
                                                    style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; font-size: 12px; background-color: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; cursor: pointer; border-radius: 4px; height: 32px;">
                                                    <i class="fa-solid fa-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" style="text-align:center; padding:20px;">Belum ada data petugas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- MODAL TAMBAH PETUGAS -->
        <div class="modal-overlay" id="modalTambahPetugas" data-current-role="{{ $currentRole }}">
            <div class="petugas-modal">
                <div class="petugas-modal-header">
                    <div>
                        <h3>Tambah Petugas</h3>
                        <p>Daftarkan petugas baru untuk sistem SIMPEL.</p>
                    </div>
                    <button type="button" class="btn-close-modal" id="btn-close-tambah-petugas" aria-label="Tutup modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="{{ route('petugas.store') }}" method="POST" enctype="multipart/form-data"
                    id="form-tambah-petugas" novalidate>
                    @csrf
                    <div class="petugas-modal-body">
                        <div class="form-grid-modal">
                            <!-- Foto preview -->
                            @php
                                $petugasName = trim(old('nama') ?: 'Petugas');
                                $petugasParts = preg_split('/\s+/', $petugasName, -1, PREG_SPLIT_NO_EMPTY) ?: [];
                                $petugasInitials = collect($petugasParts)
                                    ->map(fn($part) => strtoupper(substr($part, 0, 1)))
                                    ->take(2)
                                    ->implode('');
                                if ($petugasInitials === '') {
                                    $petugasInitials = strtoupper(substr($petugasName, 0, 2));
                                }
                            @endphp
                            <div class="form-group-modal full-width">
                                <label>Foto <span class="optional-label">(opsional)</span></label>
                                <div class="photo-zone" id="petugas-photo-zone">
                                    <div class="photo-preview" id="petugas-photo-preview">
                                        <div class="photo-preview-fallback" id="petugas-photo-fallback">
                                            {{ $petugasInitials }}</div>
                                    </div>
                                    <div class="photo-zone-copy">
                                        <strong>Klik untuk unggah foto</strong>
                                        <p>Gunakan JPG, PNG, atau WEBP maksimal 2MB.</p>
                                    </div>
                                    <input type="file" name="foto" accept="image/*" id="foto" hidden>
                                </div>
                                <small class="field-error" id="error-foto"></small>
                            </div>

                            <div class="form-group-modal">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" placeholder="Masukkan nama lengkap"
                                    value="{{ old('nama') }}" required>
                                <small class="field-error" id="error-nama">
                                    @error('nama')
                                        {{ $message }}
                                    @enderror
                                </small>
                            </div>

                            <div class="form-group-modal">
                                <label>NIK</label>
                                <input type="text" name="nik" id="nik" placeholder="16 digit NIK"
                                    value="{{ old('nik') }}" required>
                                <small class="field-error" id="error-nik">
                                    @error('nik')
                                        {{ $message }}
                                    @enderror
                                </small>
                            </div>

                            <div class="form-group-modal">
                                <label>Jabatan</label>
                                <select name="jabatan" id="jabatan" required>
                                    <option value="">Pilih Jabatan</option>
                                    <option value="kader" {{ old('jabatan') === 'kader' ? 'selected' : '' }}>kader
                                    </option>
                                    <option value="kepala_kader"
                                        {{ old('jabatan') === 'kepala_kader' ? 'selected' : '' }}>kepala_kader</option>
                                </select>
                                <small class="field-error" id="error-jabatan">
                                    @error('jabatan')
                                        {{ $message }}
                                    @enderror
                                </small>
                            </div>

                            <div class="form-group-modal">
                                <label>Nomor WhatsApp</label>
                                <input type="text" name="no_hp" id="no_hp" placeholder="+62"
                                    value="{{ old('no_hp') }}" required>
                                <small class="field-error" id="error-no_hp">
                                    @error('no_hp')
                                        {{ $message }}
                                    @enderror
                                </small>
                            </div>

                            <div class="form-group-modal">
                                <label>Email</label>
                                <input type="email" name="email" id="email" placeholder="nama@gmail.com"
                                    value="{{ old('email') }}" required>
                                <small class="field-error" id="error-email">
                                    @error('email')
                                        {{ $message }}
                                    @enderror
                                </small>
                            </div>

                            <!-- PASSWORD DENGAN TOGGLE MATA -->
                            <div class="form-group-modal">
                                <label>Kata Sandi</label>
                                <div class="password-wrapper">
                                    <input type="password" name="password" id="password"
                                        placeholder="Minimal 8 karakter" required>
                                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                                        <i class="fa fa-eye-slash" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                <small class="field-error" id="error-password">
                                    @error('password')
                                        {{ $message }}
                                    @enderror
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="petugas-modal-footer">
                        <button type="button" class="btn-outline-modal" id="btn-batal-tambah-petugas">Batal</button>
                        <button type="submit" class="btn-primary" id="btn-submit-tambah-petugas">
                            <i class="fa fa-save"></i> Simpan Petugas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL EDIT PETUGAS -->
        <div class="modal-overlay" id="modalEditPetugas" data-current-role="{{ $currentRole }}">
            <div class="petugas-modal">
                <div class="petugas-modal-header">
                    <div>
                        <h3>Edit Petugas</h3>
                        <p>Perbarui data petugas langsung dari tabel.</p>
                    </div>
                    <button type="button" class="btn-close-modal" id="btn-close-edit-petugas" aria-label="Tutup modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>

                <form action="" method="POST" id="form-edit-petugas" novalidate>
                    @csrf
                    @method('PUT')
                    <div class="petugas-modal-body">
                        <div class="form-grid-modal">
                            <div class="form-group-modal">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" id="edit-nama" placeholder="Masukkan nama lengkap"
                                    required>
                                <small class="field-error" id="error-edit-nama"></small>
                            </div>

                            <div class="form-group-modal">
                                <label>NIK</label>
                                <input type="text" name="nik" id="edit-nik" placeholder="16 digit NIK" required>
                                <small class="field-error" id="error-edit-nik"></small>
                            </div>

                            <div class="form-group-modal">
                                <label>Jabatan</label>
                                @if ($currentRole === 'super_admin')
                                    <select name="jabatan" id="edit-jabatan" required>
                                        <option value="kader">kader</option>
                                        <option value="kepala_kader">kepala_kader</option>
                                    </select>
                                @else
                                    <select name="jabatan" id="edit-jabatan" required>
                                        <option value="kader" selected>kader</option>
                                    </select>
                                @endif
                                <small class="field-error" id="error-edit-jabatan"></small>
                            </div>

                            <div class="form-group-modal">
                                <label>Nomor WhatsApp</label>
                                <input type="text" name="no_hp" id="edit-no_hp" placeholder="+62" required>
                                <small class="field-error" id="error-edit-no_hp"></small>
                            </div>

                            <div class="form-group-modal full-width">
                                <label>Email</label>
                                <input type="email" name="email" id="edit-email" placeholder="nama@gmail.com"
                                    required>
                                <small class="field-error" id="error-edit-email"></small>
                            </div>
                        </div>
                    </div>

                    <div class="petugas-modal-footer">
                        <button type="button" class="btn-outline-modal" id="btn-batal-edit-petugas">Batal</button>
                        <button type="submit" class="btn-primary" id="btn-submit-edit-petugas">
                            <i class="fa fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($errors->any())
            <div id="petugas-form-errors" data-has-errors="1" style="display:none;"></div>
        @endif
    </div>

@endsection

@push('scripts')
    @vite('resources/js/jsADMIN/data_petugas.js')
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
@endpush
