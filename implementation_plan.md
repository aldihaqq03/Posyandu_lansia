# Redesign Halaman Monitoring Lansia

Ini adalah rencana implementasi untuk mendesain ulang halaman monitoring kesehatan lansia agar lebih modern, rapi, dan informatif.

## Goal Description
Mengubah tata letak grafik yang saat ini memanjang ke bawah menjadi sistem Tab, memperbaiki tampilan "Empty State" agar tidak bertumpuk dengan grafik, menambahkan fitur "Detail Data" berupa modal tabel riwayat untuk masing-masing grafik, serta mempercantik UI/UX keluhan dan saran dengan tema clean medical dashboard.

## User Review Required
> [!IMPORTANT]  
> Mohon konfirmasi apakah desain komponen Modal untuk "Detail Data" sudah sesuai, dan apakah urutan Tab grafik (Tekanan Darah, Gula Darah, Kolesterol, Berat Badan, Lingkar Perut) sudah benar.

## Proposed Changes

### Komponen View (Blade)
#### [MODIFY] `resources/views/lansia/monitoringKesehatan.blade.php`
- **Sistem Tab Grafik**: Mengelompokkan semua card grafik (`Tekanan Darah`, `Gula Darah`, `Kolesterol`, `Berat Badan`, `Lingkar Perut`) ke dalam sebuah sistem Tab. Hanya satu grafik yang tampil dalam satu waktu.
- **Tombol Detail**: Menambahkan tombol "Lihat Detail" ber-icon tabel pada setiap card grafik.
- **Modal Detail Data**: Menambahkan komponen Modal tersembunyi di bawah untuk menampilkan riwayat data tabular. Modal akan memiliki overlay blur dan animasi transisi.
- **Empty State**: Memperbarui struktur elemen empty state dengan ikon modern dan dua baris teks (Judul "Belum ada data" dan Subtitle "Data pemeriksaan belum tersedia").
- **Riwayat Keluhan & Saran**: Merapikan struktur HTML untuk keluhan dan saran menggunakan card modern dan tombol action berupa ikon bulat (icon-only).

---

### Komponen Gaya (CSS)
#### [MODIFY] `resources/css/cssAdmin/monitoring.css`
- **Tab Layout**: Menambahkan kelas untuk `.mon-tabs`, `.mon-tab-btn`, `.mon-tab-btn.active`, dan `.mon-tab-content`.
- **Empty State**: Menambahkan gaya `flex-column`, `center-aligned`, dengan ikon yang lebih besar dan warna soft.
- **Modal Styles**: Menambahkan kelas `.mon-modal-overlay`, `.mon-modal-content`, `.mon-modal-header`, `.mon-table`, dengan efek backdrop-filter blur, transisi fade/scale, dan sticky header tabel.
- **Animasi & Hover**: Menambahkan micro-animations untuk perpindahan tab dan efek hover yang halus pada tombol dan card keluhan/saran.

---

### Komponen Logika (JavaScript)
#### [MODIFY] `resources/js/jsAdmin/monitoring.js`
- **Tab Logic**: Menambahkan fungsi untuk mengatur klik tab (menambahkan class active, menampilkan konten tab terkait dengan animasi ringan).
- **Conditional Rendering Empty State**: Memastikan fungsi `setChartState` secara ketat mengatur *display*:
  - Jika ada data -> chart `display: block`, empty `display: none`
  - Jika kosong -> chart `display: none`, empty `display: flex`
- **Detail Modal Logic**: 
  - Saat tombol "Lihat Detail" diklik, ambil data dari array global riwayat kesehatan, lalu buat baris-baris tabel dinamis (Sesuai jenis grafik yang sedang aktif: misalnya Tekanan Darah menampilkan Sistolik & Diastolik).
  - Mengelola open/close modal dan efek klik overlay.
- Tidak ada perubahan pada pemanggilan API atau struktur database.

## Verification Plan

### Automated Tests
- Menjalankan vite build (`npm run build`) untuk memastikan tidak ada error pada sintaks CSS dan JS.
- Menjalankan artisan serve untuk memastikan halaman load tanpa error Blade.

### Manual Verification
- Buka halaman Monitoring Lansia.
- Verifikasi bahwa kelima grafik sekarang berada di dalam Tab dan perpindahannya *smooth*.
- Verifikasi *Empty State* muncul HANYA ketika grafik tidak memiliki data sama sekali (tidak ada teks kosong yang tumpang tindih dengan canvas).
- Klik tombol "Lihat Detail" untuk memastikan Modal Tabel muncul dan memuat riwayat data dengan benar.
- Verifikasi desain UI Keluhan dan Saran agar tampak rapi dan ikon tombol action sejajar serta modern.
