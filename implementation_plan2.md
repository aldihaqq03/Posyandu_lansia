# Refactoring Parameter Kesehatan Menggunakan `HealthRiskAssessor`

Implementasi kelas PHP `App\Services\HealthRiskAssessor` untuk melakukan standarisasi penilaian status risiko kesehatan lansia (Normal, Waspada, Perlu Tindak Lanjut). Refactoring ini akan membersihkan duplikasi kode di backend, menyatukan aturan klasifikasi medis, dan memperbarui visualisasi data pada tabel serta grafik monitoring.

---

## User Review Required

> [!IMPORTANT]
> **Perubahan Parameter Sengaja Dilakukan:**
> Perubahan batasan parameter Gula Darah dan Kolesterol **bukan merupakan bug**, melainkan perubahan yang disengaja karena standar lama tidak sesuai dengan Kartu Skrining Posbindu PTM resmi.
> - Parameter Gula Darah diubah dari standar Gula Darah Puasa (70-100 Normal) menjadi **Gula Darah Sewaktu** (80-144 Normal).
> - Parameter Kolesterol disesuaikan menjadi **Normal < 150 mg/dL** (dari sebelumnya < 200).
> Hal ini menyebabkan status beberapa lansia akan terupdate secara otomatis dan akurat sesuai Kartu Skrining terbaru.

> [!WARNING]
> **JANGAN UBAH `App\Helpers\SkriningHelper`:**
> Berkas `App\Helpers\SkriningHelper` tidak boleh diubah sama sekali. Class `HealthRiskAssessor` dibuat terpisah dan mandiri.

---

## Edge Case & Null Handling

1.  **Tanpa Record Skrining:**
    *   Jika lansia belum pernah melakukan skrining sama sekali, method `assess()` akan mengembalikan `null`.
    *   Di halaman Blade, kita akan menggunakan pengecekan `@if($riskLevel)` sebelum menampilkan badge risiko agar tidak memunculkan badge kosong atau abu-abu (di sistem jika null memang tidak ditampilkan sama sekali).
2.  **Record Skrining Ada, namun Field Kosong (Null):**
    *   Jika field parameter bernilai `null` (misal: hanya tensi yang diukur, sedangkan gula darah kosong), parameter yang kosong tersebut akan dilewati (*skip*).
    *   Penilaian status risiko akhir hanya dihitung berdasarkan parameter-parameter yang terisi saja.

---

## Proposed Changes

### Backend (Services & Controllers)

#### [NEW] [HealthRiskAssessor.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/app/Services/HealthRiskAssessor.php)
*   Membuat berkas service baru untuk menampung seluruh logika kalkulasi parameter kesehatan lansia.
*   Menyediakan fungsi penilai per parameter (`sistolik`, `diastolik`, `gulaDarah`, `kolesterol`, `imt`, `lingkarPerut`).
*   Menyediakan fungsi penilai keseluruhan (`assess`) dengan prioritas: `Perlu Tindak Lanjut > Waspada > Normal`. Jika tidak ada parameter yang terisi, kembalikan `null`.
*   Menyediakan fungsi utility untuk label status dan class badge Tailwind.

#### [MODIFY] [LansiaController.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/app/Http/Controllers/LansiaController.php)
*   **Method `index()`:** Mengganti logika pengecekan `if-else` manual yang panjang dengan panggilan ke `HealthRiskAssessor::assess()`. Ini berlaku baik pada transformasi koleksi data tabel lansia maupun pada perulangan penghitung statistik card di atas tabel.
*   **Method `healthSummary()`:** Mengirimkan hasil kalkulasi detail (`HealthRiskAssessor::detail()`) bersama dengan data nilai parameter mentah agar dapat dikonsumsi langsung oleh JS frontend.

---

### Frontend (Views & Javascript)

#### [MODIFY] [data_lansia.blade.php](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/views/admin/data_lansia.blade.php)
*   Menambahkan pengecekan `@if($lansia->risk_level)` sebelum merender badge risiko agar lansia tanpa skrining tidak menampilkan badge kosong.

#### [MODIFY] [data_lansia.js](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/js/jsADMIN/data_lansia.js)
*   Memperbarui fungsi `fetchHealthSummary()` agar membaca status detail yang dikirim oleh backend sehingga warna card kesehatan (Normal, Waspada, Tinggi) selalu selaras dengan backend tanpa melakukan pengecekan `if-else` manual di JS.

#### [MODIFY] [monitoring.js](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/resources/js/jsADMIN/monitoring.js)
*   Memperbarui konstanta batas referensi (`refLine`) pada grafik Chart.js untuk Tensi, Gula Darah, Kolesterol, dan IMT agar sesuai dengan standar baru di [new_parameter.md](file:///d:/semester%204/frame%20wrok%20mobile/Posyandu_lansia/new_parameter.md).
*   Menyesuaikan pewarnaan baris pada modal detail riwayat pemeriksaan kesehatan di `openDetailModal()`.

---

## Verification Plan

### Skenario Verifikasi Manual
1.  **Lansia Tanpa Skrining (Baru):** Tambahkan lansia baru atau pilih lansia yang belum memiliki data skrining. Pastikan di kolom "Risiko" tabel tidak memunculkan badge apa pun (bersih), dan stat card tidak bertambah secara keliru.
2.  **Lansia dengan Parameter Parsial (Edge Case Null):** Buat skrining untuk seorang lansia dengan hanya mengisi Tekanan Darah (misal: 145/95 mmHg) dan mengosongkan Gula Darah serta Kolesterol. Pastikan tingkat risikonya terdeteksi sebagai "Perlu Tindak Lanjut" (berdasarkan tensi) dan parameter yang kosong terlewati tanpa error.
3.  **Verifikasi Tabel & Stat Cards:** Pastikan total angka pada *stat cards* (Kondisi Normal, Waspada, Perlu Perhatian) di bagian atas sinkron dengan badge risiko di kolom tabel lansia.
4.  **Verifikasi Halaman Monitoring:** Periksa apakah garis putus-putus batas normal/waspada pada grafik sudah bergeser sesuai standar baru dan tooltip menampilkan status yang benar.
