# VALIDATION INPUT PEKERJAAN
tambahkan input baru ini sudah ada kolom databsenya sudah migrate namun belum utuk di backend samapi front endnya
## Field: Pekerjaan

### Input

Dropdown pilihan pekerjaan:

* 1 = TNI/POLRI
* 2 = PNS
* 3 = Karyawan Swasta
* 4 = Buruh
* 5 = Petani/Nelayan
* 6 = Tidak Bekerja / IRT
* 7 = Lainnya

---

## Validasi

### Pekerjaan

* Wajib dipilih

Error:

```txt id="o9d2ps"
Pilih pekerjaan terlebih dahulu
```

---

## Pekerjaan Lainnya

### Ketentuan

* Input muncul jika memilih "Lainnya"
* Wajib diisi jika memilih "Lainnya"

Placeholder:

```txt id="j1t7fr"
Masukkan pekerjaan lainnya
```

Error:

```txt id="h8u2kw"
Masukkan pekerjaan lainnya
```
# VALIDATION RULES - FORM DATA LANSIA

## Tujuan

Dokumen ini berisi ketentuan validasi realtime untuk form input data lansia agar data lebih konsisten, mudah dipahami user, dan mengurangi kesalahan input. validation sudah ada namun saat user input nanti muncul notif gagal jika ada kesalahan, saya mau anda menggantinya dengan validation realtima dan jika tidak sesuai maka tidak bisa submit 

---

# 1. NIK

## Ketentuan

* Wajib diisi
* Harus 16 digit
* Hanya angka
* Tidak boleh duplikat
* Tidak boleh mengandung spasi atau simbol

## Validasi Realtime

* Validasi saat user selesai mengetik
* Cek otomatis apakah NIK sudah digunakan

## Feedback

```txt
✔ NIK valid
✖ NIK harus terdiri dari 16 digit
✖ NIK hanya boleh angka
✖ NIK sudah terdaftar
```

---

# 2. Nama Lengkap

## Ketentuan

* Wajib diisi
* Minimal 3 karakter
* Maksimal 100 karakter
* Tidak boleh mengandung angka
* Tidak boleh hanya simbol

## Validasi Realtime

* Deteksi karakter tidak valid
* Auto trim spasi berlebih

## Feedback

```txt
✔ Nama valid
✖ Nama terlalu pendek
✖ Nama tidak boleh mengandung angka
```

---

# 3. Tanggal Lahir
sudah saya setting untuk minimal umurnya yait 40 th sudah ada namun maksimalnya belum yaitu 120 tahun
## Ketentuan

* Wajib diisi
* Tidak boleh tanggal masa depan
* Umur harus realistis
* Umur minimal sesuai kategori lansia

## Validasi Realtime

* Hitung umur otomatis


## Feedback
---

# 4. Jenis Kelamin

## Ketentuan

* Wajib dipilih
* Hanya:

  * Laki-laki
  * Perempuan

## Feedback

```txt
✖ Pilih jenis kelamin
```

---

# 5. Tempat Lahir

## Ketentuan

* Wajib diisi
* Minimal  5 karakter
* Tidak boleh hanya angka

## Feedback

```txt
✖ Tempat lahir wajib diisi
✖ Tempat lahir tidak valid
```

---

# 6. No Handphone

## Ketentuan

* Opsional
* Jika diisi:

  * Harus angka
  * Diawali 08
  * Panjang 10-13 digit

## Validasi Realtime

* Format nomor dicek otomatis

## Feedback

```txt
✔ Nomor valid
✖ Nomor harus diawali 08
✖ Nomor terlalu pendek
✖ Nomor tidak valid
```

---

# 7. Status Perkawinan

## Ketentuan

* Wajib dipilih

## Opsi

* Belum Menikah
* Menikah
* Cerai Hidup
* Cerai Mati

## Feedback

```txt
✖ Pilih status perkawinan
```

---

# 8. Riwayat Penyakit

## Ketentuan

* Opsional
* Maksimal 255 karakter



# 9. Alamat

## Ketentuan

* Wajib diisi
* Minimal 10 karakter

## Feedback

```txt
✖ Alamat terlalu pendek
✖ Alamat wajib diisi
```

---

# 10. Keterangan

## Ketentuan

* Opsional
* Maksimal 500 karakter

---

# 11. Email

## Ketentuan

* Opsional
* Harus format email valid

## Feedback

```txt
✔ Email valid
✖ Format email tidak valid
```

---

# 12. Informasi Keluarga

## Ketentuan

* Minimal 1 anggota keluarga
* Nama keluarga wajib diisi

---

## Nama Keluarga

### Ketentuan

* Wajib diisi
* Minimal 3 karakter

### Feedback

```txt
✖ Nama keluarga wajib diisi
```

---

## No Telepon Keluarga

### Ketentuan

* Opsional
* Format nomor Indonesia

### Feedback

```txt
✖ Nomor keluarga tidak valid
```



## Realtime Validation Behavior

### Jangan terlalu agresif

Hindari validasi merah saat user baru mengetik 1 karakter.

### Gunakan:

* `blur validation`
* debounce realtime validation
* validation setelah user berhenti mengetik

--- 



# PRIORITAS VALIDASI

## Critical

* NIK
* Nama
* Tanggal lahir
* Jenis kelamin
* Alamat

## Medium

* Nomor HP
* Email

## Optional

* Keterangan
* Riwayat penyakit

```
```
