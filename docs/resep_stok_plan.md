# Rencana Implementasi (Revisi): Resep & Mutasi Stok

Tujuan singkat

- Terapkan perubahan berikut:
    1. HENTIKAN semua penggunaan `frekuensi_per_hari` di kode dan migrasi.
    2. Gunakan hanya kolom `frekuensi` bertipe UNSIGNED INTEGER dengan makna:
        - `jenis_jadwal` = `harian` → `frekuensi` = kali per hari
        - `jenis_jadwal` = `hari_tertentu` → `frekuensi` = kali per minggu
    3. Gunakan nama `jumlah` untuk mutasi stok, bukan `qty`.
    4. Skema `mutasi_stok` sederhana (tidak polymorphic) dengan `id_resep` nullable.
    5. Default `jumlah_obat = 1` hanya di level form/UI (tidak set default di DB migration).

Prasyarat & catatan penting

- Pastikan backup DB sebelum migrasi apapun yang mengubah atau menghapus data.
- Cari & hapus semua referensi `frekuensi_per_hari` di: migration, model, validation, controller, blade, API responses, dan JavaScript.

Spesifikasi schema dan migrasi

1. `detail_resep`
    - Pastikan kolom berikut ada dan tipe sesuai:
        - `jenis_jadwal` enum('harian','hari_tertentu')
        - `frekuensi` UNSIGNED INTEGER NOT NULL
        - `hari_konsumsi` JSON nullable (array of strings) — diisi saat `hari_tertentu`.
        - `jumlah_obat` unsigned integer nullable (JANGAN set default di migration)

    - Jika saat ini `frekuensi` tersimpan sebagai string (mis. "3x1 sehari"), buat migrasi transformasi yang memetakan nilai ke integer (contoh SQL/logic untuk extract angka pertama).

2. `mutasi_stok` (new)
    - Fields:
        - `id_mutasi` (PK)
        - `id_obat` (FK)
        - `id_resep` nullable (FK ke resep)
        - `tipe` enum('masuk','keluar')
        - `jumlah` integer
        - `keterangan` text nullable
        - timestamps
    - Tidak pakai `referensi_type`/`referensi_id` polymorphic.

Backend — aturan & contoh

- Model `DetailResep`:
    - `protected $casts = ['hari_konsumsi' => 'array', 'frekuensi' => 'integer'];`
    - `protected $fillable` include `jenis_jadwal`, `frekuensi`, `hari_konsumsi`, `jumlah_obat`.

- Validation (contoh rules di controller):
    - `jenis_jadwal` => `required|in:harian,hari_tertentu`
    - `frekuensi` => `required|integer|min:1`
        - `hari_konsumsi` => `required_if:jenis_jadwal,hari_tertentu|array|min:1`
        - `hari_konsumsi.*` => `in:senin,selasa,rabu,kamis,jumat,sabtu,minggu`
        - `jumlah_obat` => `required|integer|min:1`

    - Tambahan: setelah validasi di controller, pastikan:

        ```php
        if ($request->input('jenis_jadwal') === 'harian') {
                $hari_konsumsi = null; // jangan simpan hari_konsumsi untuk jadwal harian
        }
        ```

- Stock mutation methods (di `Obat` model):
    - `public function decreaseStock(int $jumlah, ?int $id_resep = null, string $keterangan = null)`
        - Lakukan `DB::transaction()`:
            - cek `if ($this->stok < $jumlah) throw \Illuminate\Validation\ValidationException::withMessages(['stok' => ['stok tidak cukup']]);`
            - `$this->stok -= $jumlah; $this->save();`
            - create `MutasiStok::create([... 'jumlah' => $jumlah, 'tipe' => 'keluar', 'id_resep' => $id_resep, ...])`
    - `increaseStock` serupa dengan `tipe = 'masuk'`.

Frontend / UI

- Form resep (`resources/views/admin/skrining/_resep_row.blade.php`):
    - Gunakan `input type="number" name="frekuensi[]"` untuk frekuensi.
    - Radio/select `jenis_jadwal` toggles checkbox group hari (senin..minggu).
    - `jumlah_obat` input di-form set `value="1" min="1"` oleh JS saat baris dirender.
    - Client-side validation: when `jenis_jadwal` == `hari_tertentu`, require min 1 checkbox selected.

REST API & endpoints

- GET `/api/obat/{id}/restock-history` → returns array of `mutasi_stok` where `id_obat` = id and `tipe` = 'masuk'.
- POST `/obat/{id}/restock` → request `jumlah` (integer > 0), buat mutasi tipe 'masuk', update `obat.stok`.

Testing & safety

- Semua operasi yang mengubah stok dan membuat mutasi harus berada di `DB::transaction()`.
- Jika stok kurang, kembalikan error dan jangan buat mutasi atau ubah stok.
- Tambahkan unit/feature tests untuk:
    - submit resep sukses mengurangi stok dan membuat mutasi
    - submit resep gagal (stok kurang) rollback tanpa pembuatan mutasi
    - restock menambah stok dan membuat mutasi masuk

Checklist tugas (JSON)

```
[
  {"id":1,"title":"Backup DB sebelum migrasi","files":[],"estimated_hours":1,"priority":"high"},
  {"id":2,"title":"Transform frekuensi ke UNSIGNED INT (migrasi data) jika perlu","files":["database/migrations/"],"estimated_hours":2,"priority":"high"},
  {"id":3,"title":"Buat migrasi create_mutasi_stok_table","files":["database/migrations/"],"estimated_hours":2,"priority":"high"},
  {"id":4,"title":"Hapus semua referensi frekuensi_per_hari","files":["app/","resources/views/","public/js/"],"estimated_hours":2,"priority":"high"},
  {"id":5,"title":"Implement increase/decrease stock di app/Models/Obat.php","files":["app/Models/Obat.php","app/Models/MutasiStok.php"],"estimated_hours":2,"priority":"high"},
  {"id":6,"title":"Update SkriningController untuk validasi dan mutasi stok","files":["app/Http/Controllers/SkriningController.php"],"estimated_hours":3,"priority":"high"},
  {"id":7,"title":"Update UI (frekuensi as INT, jumlah_obat default UI=1)","files":["resources/views/admin/skrining/_resep_row.blade.php","resources/views/admin/skrining/index.blade.php"],"estimated_hours":2,"priority":"medium"},
  {"id":8,"title":"Implement restock endpoints + modal history","files":["routes/api.php","app/Http/Controllers/","resources/views/"],"estimated_hours":3,"priority":"medium"},
  {"id":9,"title":"Tests: unit & feature stok/mutasi","files":["tests/"],"estimated_hours":3,"priority":"high"}
]
```

Catatan akhir

- Saya sudah menyesuaikan rencana supaya konsisten dengan aturan Anda: `frekuensi` UNSIGNED INT, hapus `frekuensi_per_hari`, gunakan `jumlah` di `mutasi_stok`, dan default `jumlah_obat` hanya di UI.
- Mau saya lanjutkan dengan membuat migrasi `mutasi_stok` dan contoh method `decreaseStock` sekarang? Jika ya, saya akan buat file migrasi dan patch model/controller contoh.
