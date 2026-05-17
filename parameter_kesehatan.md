# PERBAIKAN UI FITUR DATA LANSIA & DETAIL SKRINING

## TUJUAN
# PARAMETER PENENTU STATUS RISIKO KESEHATAN LANSIA

## Parameter yang Digunakan

Sistem menentukan status risiko kesehatan berdasarkan parameter berikut:

| Parameter | Satuan |
|---|---|
| IMT (Indeks Massa Tubuh) | kg/m² |
| Tekanan Darah Sistolik | mmHg |
| Tekanan Darah Diastolik | mmHg |
| Kolesterol Total | mg/dL |
| Gula Darah Puasa | mg/dL |

---

# STANDAR PARAMETER

## 1. IMT (Indeks Massa Tubuh)

| Status | Nilai |
|---|---|
| ✅ Normal | 22.0 – 27.0 |
| ⚠️ Waspada | 18.5 – 21.9 atau 27.1 – 29.9 |
| ❌ Perlu Tindak Lanjut | <18.5 atau ≥30 |

---

## 2. Tekanan Darah Sistolik

| Status | Nilai |
|---|---|
| ✅ Normal | <130 mmHg |
| ⚠️ Waspada | 130 – 139 mmHg |
| ❌ Perlu Tindak Lanjut | ≥140 mmHg |

---

## 3. Tekanan Darah Diastolik

| Status | Nilai |
|---|---|
| ✅ Normal | <85 mmHg |
| ⚠️ Waspada | 85 – 89 mmHg |
| ❌ Perlu Tindak Lanjut | ≥90 mmHg |

---

## 4. Kolesterol Total

| Status | Nilai |
|---|---|
| ✅ Normal | <200 mg/dL |
| ⚠️ Waspada | 200 – 239 mg/dL |
| ❌ Perlu Tindak Lanjut | ≥240 mg/dL |

---

## 5. Gula Darah Puasa

| Status | Nilai |
|---|---|
| ✅ Normal | 70 – 100 mg/dL |
| ⚠️ Waspada | 101 – 125 mg/dL |
| ❌ Perlu Tindak Lanjut | ≥126 mg/dL |

---

# RULE PENENTUAN STATUS

## ✅ Normal
Jika semua parameter berada pada kategori normal.

---

## ⚠️ Waspada
Jika:
- minimal 1 parameter kategori waspada,
- dan tidak ada parameter kategori perlu tindak lanjut.

---

## ❌ Perlu Tindak Lanjut
Jika:
- minimal 1 parameter kategori perlu tindak lanjut.

---

# PRIORITAS STATUS

```text id="b7ntjlwm"
Perlu Tindak Lanjut > Waspada > Normal
Rapikan tampilan fitur:

- Data Lansia
- Detail Lansia
- Modal Detail Skrining
-icon icon juga harus

Tanpa merombak keseluruhan desain aplikasi.

Fokus hanya pada:

- struktur informasi,
- keterbacaan,
- spacing,
- status kesehatan,
- dan tampilan detail data kesehatan.

Pertahankan:

- layout utama aplikasi,
- sidebar,
- navbar,
- warna utama aplikasi,
- dan struktur halaman yang sudah ada.

---

# 1. TABEL DATA LANSIA

## Tambahkan Kolom Risiko

Tambahkan kolom:

- Risiko

Struktur tabel:

- Nama
- Umur
- Alamat
- Risiko
- Nomor HP
- Aksi

---

## STATUS RISIKO

Gunakan 3 kategori:

| Status                 | Tampilan    |
| ---------------------- | ----------- |
| ✅ Normal              | Hijau soft  |
| ⚠️ Waspada             | Orange soft |
| ❌ Perlu Tindak Lanjut | Merah soft  |

Badge:

- rounded,
- kecil,
- clean,
- mudah dibaca,
- tidak terlalu mencolok.

---

## RAPIIKAN TABEL

Perbaiki:

- alignment kolom,
- padding row,
- ukuran font,
- hover row,
- selected row.

Jangan ubah layout utama tabel.

---

# 2. DETAIL LANSIA

Rapikan section detail lansia agar lebih terstruktur.

Tetap gunakan layout sekarang, tetapi:

- spacing diperjelas,
- hierarchy informasi dibuat lebih jelas,
- card kesehatan dibuat lebih rapi.

---

## CARD KESEHATAN

Card:

- Tensi Sistolik
- Tensi Diastolik
- Gula Darah
- Kolesterol
- IMT

Perbaiki:

- label lebih jelas,
- value lebih besar,
- satuan lebih kecil,
- spacing lebih rapi.

Tambahkan warna soft berdasarkan status.

---

# 3. MODAL DETAIL SKRINING

Fokus utama:

- memperjelas label dan hasil,
- membuat struktur lebih mudah dibaca,
- mengurangi kesan penuh dan membingungkan.

---

## STRUKTUR MODAL

Gunakan section:

- Faktor Risiko
- Pemeriksaan Fisik
- Pemeriksaan Laboratorium
- Gejala

Jangan tampilkan semua data tanpa pemisah section.

---

## FORMAT ITEM

Gunakan format:

Label:

```text
Tekanan Darah Sistolik
```
