# UI Bug Fix & Consistency Plan — SIMPEL Posyandu (Revised)

## Proposed Changes

### 1. Global Styles (`sidebar.css`) — Central Spacing & Card Consistency

#### [MODIFY] [sidebar.css](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/css/sidebar.css)

- Tambahkan style global untuk `.stats-grid` dan `.stat-card` untuk menjamin konsistensi tinggi (min-height), hover effect, border-left, flex behavior, dan spacing di semua halaman (Dashboard, Petugas, Lansia).
- Tambahkan utilitas kelas spacing seragam:
  - `.page-header` { `margin-bottom: 20px !important;` }
  - `.stats-grid` { `margin-bottom: 16px !important; gap: 16px !important; align-items: stretch !important;` }
  - `.table-action-bar` { `margin-bottom: 16px !important;` }
- Definisikan `.stat-card` dengan `min-height: 82px; display: flex; flex-direction: column; justify-content: space-between;` agar tinggi seragam dan responsif jika teks panjang.

---

### 2. `laporan.blade.php` — Fix Bug Kritis "@endsection"

#### [MODIFY] [laporan.blade.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/laporan.blade.php)

- Ubah baris 308 dari `@endsection` kembali menjadi `@endpush` untuk menutup `@push('styles')` dengan benar.

---

### 3. `obat/index.blade.php` & `obat.css` — Hapus Card, Atur Spacing, & Fix Scroll Tabel

#### [MODIFY] [index.blade.php (obat)](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/obat/index.blade.php)

- Hapus blok `@php` statistik (baris 29-34) dan kontainer `.obat-stats-grid` (baris 35-64).
- Bungkus tombol "Tambah Obat" dalam `<div class="table-action-bar">` untuk mengatur margin yang konsisten.
- Gunakan inline layout yang rapi untuk `.obat-page`.

#### [MODIFY] [obat.css](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/css/cssAdmin/obat.css)

- Perbarui `.obat-page .obat-table-wrap` menjadi `flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden;` agar mengisi sisa halaman.
- Perbarui `.obat-scroll-body` menjadi `flex: 1; min-height: 0; overflow-y: auto; overflow-x: auto;` agar scroll vertikal hanya terjadi di area tabel jika baris data melebihi layar.

---

### 4. `jadwal_posyandu.blade.php` & `jadwal_posyandu.css` — Atur Spacing & Tombol Tambah

#### [MODIFY] [jadwal_posyandu.blade.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/petugas/jadwal_posyandu.blade.php)

- Hapus sisa tag breadcrumb jika ada.
- Pindahkan tombol "Tambah Jadwal" ke dalam `<div class="table-action-bar" style="display: flex; justify-content: flex-end;">` di bawah `.page-header`.

#### [MODIFY] [jadwal_posyandu.css](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/css/cssAdmin/jadwal_posyandu.css)

- Bersihkan margin kustom pada header agar konsisten menggunakan utilitas global.

---

### 5. `petugas/index.blade.php` & `data_petugas.css` — Pindahkan Tombol Tambah & Fix Scroll Tabel

#### [MODIFY] [index.blade.php (petugas)](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/petugas/index.blade.php)

- Pindahkan tombol "Tambah Petugas" dari `.page-header` ke bawah `.stats-grid`.
- Bungkus tombol dalam `<div class="table-action-bar" style="display: flex; justify-content: flex-end;">`.
- Hapus header tabel lama `<div class="table-header">` jika tidak diperlukan atau ubah stylenya agar tidak menambah scroll.

#### [MODIFY] [data_petugas.css](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/css/cssAdmin/data_petugas.css)

- Bersihkan CSS kustom `.stats-grid` dan `.stat-card` lama karena sudah didefinisikan secara global.
- Perbarui `.petugas-page .table-container` menjadi `flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden;` agar mengisi sisa halaman.
- Perbarui `.table-scroll` menjadi `flex: 1; min-height: 0; overflow-y: auto;` agar scroll hanya ada di area tabel.

---

### 6. `data_lansia.blade.php` & `data_lansia.css` — Split-View Detail Layout, Tombol Tambah, & Fix Scroll

#### [MODIFY] [data_lansia.blade.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/data_lansia.blade.php)

- Pindahkan tombol "Tambah Lansia" ke bawah `.stats-grid`, dibungkus dalam `<div class="table-action-bar" style="display: flex; justify-content: flex-end;">`.
- Bungkus `.table-container` dan `#detail-panel` ke dalam sebuah kontainer layout split-view baru:
  ```html
  <div class="lansia-content-layout">
      {{-- Tabel Container (left side, flex: 1) --}}
      <section class="table-container card"> ... </section>

      {{-- Detail Container (right side, width: 450px, scrollable) --}}
      <section class="detail-container card" id="detail-panel" style="display:none;"> ... </section>
  </div>
  ```
- Ubah markup card statistik lansia agar strukturnya sama persis dengan Dashboard (gunakan `span.stat-label`, letakkan `stat-number` lalu `stat-icon-fa` secara berurutan, dan hilangkan `.icon-wrapper`).

#### [MODIFY] [data_lansia.css](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/css/cssAdmin/data_lansia.css)

- Tambahkan CSS untuk `.lansia-content-layout`:
  ```css
  .lansia-content-layout {
      display: flex;
      gap: 16px;
      flex: 1;
      min-height: 0;
      overflow: hidden;
      width: 100%;
  }
  ```
- Atur `.lansia-page .table-container.card` agar `flex: 1; min-height: 0; display: flex; flex-direction: column; overflow: hidden;`.
- Atur `.lansia-table-scroll` agar `flex: 1; min-height: 0; overflow-y: auto; overflow-x: auto;`.
- Atur `.lansia-page .detail-container` (Split-View Sidebar) agar:
  ```css
  .lansia-page .detail-container {
      width: 450px;
      flex-shrink: 0;
      margin-top: 0;
      margin-bottom: 0;
      max-height: 100%;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      box-sizing: border-box;
  }
  ```
- Bersihkan `.stats-grid` dan `.stat-card` kustom lama agar menggunakan standarisasi global. Hapus rule `.icon-wrapper` yang sudah tidak dipakai.

---

### 7. `skrining/index.blade.php` — Bersihkan Judul & Breadcrumb

#### [MODIFY] [index.blade.php (skrining)](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/skrining/index.blade.php)

- Hapus `<nav class="breadcrumb">`.
- Ubah title `<h1>Sekarang di Input Skrining</h1>` → `<h1 class="page-title">Input Skrining</h1>` (rata kiri).

---

## Verification Plan

### Manual Verification
- Buka halaman **Laporan** untuk memastikan tidak ada error parser.
- Pastikan di **Data Petugas**, **Data Lansia**, dan **Data Obat** tabel dapat memuat semua data dengan benar. Scroll vertikal hanya muncul pada area body tabel, dan body utama halaman tidak dapat di-scroll (`overflow: hidden`).
- Klik salah satu baris lansia di **Data Lansia**: pastikan panel detail muncul di sisi kanan (Split-View) dengan lebar 450px. Halaman utama tidak boleh bergeser ke bawah atau memunculkan scroll vertikal halaman baru.
- Verifikasi tinggi `.stat-card` di **Dashboard**, **Data Petugas**, dan **Data Lansia** adalah seragam dan tidak terpotong.
- Verifikasi jarak vertikal di semua halaman konsisten:
  - Judul ke elemen berikutnya (Card/Action Bar): `20px`
  - Gap antar card: `16px`
  - Card ke Tombol Tambah: `16px`
  - Tombol Tambah ke Tabel: `16px`
