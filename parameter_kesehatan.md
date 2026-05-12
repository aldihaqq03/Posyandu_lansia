# PERBAIKAN UI FITUR DATA LANSIA & DETAIL SKRINING

## TUJUAN

Rapikan tampilan fitur:

- Data Lansia
- Detail Lansia
- Modal Detail Skrining

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
