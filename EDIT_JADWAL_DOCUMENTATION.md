# 📝 DOKUMENTASI PERUBAHAN: EDIT JADWAL POSYANDU

**Tanggal:** 10 Maret 2026  
**Feature:** Edit Jadwal Posyandu  
**Business Logic:** Edit terbatas dengan validasi ketat

---

## 🎯 BUSINESS LOGIC YANG DITERAPKAN

### ✅ Aturan Edit Jadwal

| Kondisi | Boleh Edit? | Alasan |
|---------|-------------|--------|
| Status = 1 (Terjadwal) | ✅ Ya | Jadwal belum mulai, boleh diubah |
| Status = 2 (Berlangsung) | ❌ Tidak | Sedang berlangsung, tidak boleh diubah |
| Status = 3 (Selesai) | ❌ Tidak | Sudah selesai, tidak boleh diubah |
| Status = 4 (Dibatalkan) | ❌ Tidak | Sudah batal, tidak boleh diubah |

### ✅ Aturan Perubahan Tanggal

```
Rumus: Tanggal Baru > Tanggal Lama + 1 Hari

Contoh:
┌────────────────────────────────────────────────┐
│ Jadwal Awal:  1 Maret 2026                     │
│ Minimal Edit: 2 Maret 2026 (H+1)               │
│                                                  │
│ ❌ Tidak Bisa: 1 Maret 2026 (hari yang sama)   │
│ ❌ Tidak Bisa: 29 Februari 2026 (sebelumnya)   │
│ ✅ Bisa: 2 Maret 2026 atau setelahnya          │
└────────────────────────────────────────────────┘
```

### ✅ Status Tetap "Terjadwal"

- Edit **TIDAK BOLEH** mengubah status
- Status selalu = 1 (Terjadwal) setelah edit
- Dropdown status **dihapus** dari modal edit
- User hanya bisa mengedit: tanggal, tema, lokasi, kegiatan, catatan

---

## 📊 PERUBAHAN FILE

### 1. **View Blade** - Tombol Edit

**File:** `resources/views/admin/jadwal_posyandu.blade.php`

**Perubahan:**
```blade
{{-- SEBELUM --}}
@if($item->status != 3 && $item->status != 4)
    <button>Edit</button>
@endif

{{-- SETELAH --}}
@if($item->status == 1)
    <button>Edit</button>
@endif
```

**Dampak:**
- Tombol Edit **hanya muncul** untuk jadwal dengan status = 1 (Terjadwal)
- Status 2, 3, 4 → tombol Edit tidak muncul

---

### 2. **Modal Edit** - Hapus Dropdown Status

**File:** `resources/views/modal/M_editJadwal.blade.php`

**Perubahan:**
```blade
{{-- SEBELUM: Dropdown Status --}}
<select class="form-control" id="edit-status" name="status">
    <option value="1">Terjadwal</option>
    <option value="2">Berlangsung</option>
    <option value="3">Selesai</option>
    <option value="4">Dibatalkan</option>
</select>

{{-- SETELAH: Readonly Info --}}
<div class="status-readonly-info">
    <span class="status-badge status-1">Terjadwal</span>
    <span class="status-info-text">Status tidak dapat diubah saat edit jadwal</span>
</div>
<input type="hidden" id="edit-status" value="1">
```

**Dampak:**
- User tidak bisa mengubah status
- Status selalu = 1 (Terjadwal)
- Tampilan lebih jelas dengan info readonly

---

### 3. **Modal Edit** - Batasan Tanggal

**File:** `resources/views/modal/M_editJadwal.blade.php`

**Perubahan:**
```blade
{{-- SEBELUM --}}
<input type="date" class="form-control" id="edit-tanggal" name="tanggal_pelaksanaan">

{{-- SETELAH --}}
<input type="date" class="form-control" id="edit-tanggal" 
       name="tanggal_pelaksanaan" min="" data-min-date>
<small class="form-hint" id="edit-tanggal-hint" style="display: none;"></small>
```

**Dampak:**
- Browser disable tanggal yang kurang dari minimal
- Hint ditampilkan untuk user

---

### 4. **CSS** - Styling Status Readonly

**File:** `resources/css/cssAdmin/jadwal_posyandu.css`

**Tambahan:**
```css
/* Status Readonly Info - untuk modal edit */
.status-readonly-info {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    background: var(--primary-light);
    border-radius: 8px;
    border: 1px solid var(--border);
}

.status-badge.status-1 {
    background: var(--primary);
    color: var(--white);
    padding: 4px 12px;
    border-radius: 6px;
    font-weight: 600;
    font-size: 13px;
    white-space: nowrap;
}

.status-info-text {
    color: var(--text-mid);
    font-size: 13px;
    font-style: italic;
}
```

**Dampak:**
- Status readonly tampil dengan badge hijau
- Info text italic untuk keterangan

---

### 5. **JavaScript** - Hitung Minimal Tanggal

**File:** `resources/js/jsADMIN/jadwal_posyandu.js`

**Helper Function (Baru):**
```javascript
// Helper function: Format tanggal ke Bahasa Indonesia
function formatDateIndo(dateStr) {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return `${date.getDate()} ${months[date.getMonth()]} ${date.getFullYear()}`;
}
```

**Update di Event Listener Edit:**
```javascript
// ✅ HITUNG minimal tanggal (tanggal lama + 1 hari)
const tanggalLama = data.tanggal_pelaksanaan;
const minDate = new Date(tanggalLama);
minDate.setDate(minDate.getDate() + 1);
const minDateStr = minDate.toISOString().split('T')[0];

// ✅ SET minimal date di input
const inputTanggal = document.getElementById('edit-tanggal');
inputTanggal.min = minDateStr;
inputTanggal.dataset.minDate = minDateStr;

// ✅ Tampilkan hint untuk user
const hintEl = document.getElementById('edit-tanggal-hint');
hintEl.textContent = `Minimal tanggal: ${formatDateIndo(minDateStr)} (H+1 dari jadwal semula)`;
hintEl.style.display = 'block';
```

**Dampak:**
- Minimal tanggal otomatis dihitung saat modal dibuka
- Browser disable tanggal yang tidak valid
- User dapat feedback visual

---

### 6. **JavaScript** - Validasi Tanggal

**File:** `resources/js/jsADMIN/jadwal_posyandu.js`

**Validasi di Tombol Update:**
```javascript
// ✅ VALIDASI tanggal baru > minimal tanggal (H+1)
const minDate = document.getElementById('edit-tanggal').dataset.minDate;
if (!tanggal || tanggal <= minDate) {
    alert(`Tanggal harus lebih dari ${formatDateIndo(minDate)} (H+1 dari jadwal semula)`);
    return;
}
```

**Payload Update (Tanpa Status):**
```javascript
const payload = {
    _method              : 'PUT',
    tanggal_pelaksanaan  : tanggal,
    tema                 : tema,
    lokasi               : lokasi,
    // ❌ TIDAK KIRIM status - status tetap dari database
    ada_skrining_utama   : ...,
    ada_skrining_ppok    : ...,
    kegiatan             : kegiatan,
    keterangan           : ...,
};
```

**Dampak:**
- Validasi client-side sebelum kirim ke server
- Status tidak dikirim (tetap dari database)

---

### 7. **Controller** - Validasi Server-Side

**File:** `app/Http/Controllers/jadwalPosyanduController.php`

**Validasi Status:**
```php
// 1. Ambil data lama dari database
$jadwalLama = DB::table('jadwal_posyandu')
    ->where('id_jadwal', $id)
    ->first();

// 2. Validasi hanya status 1 (Terjadwal) yang bisa diedit
if (!$jadwalLama || $jadwalLama->status != 1) {
    if ($request->expectsJson()) {
        return response()->json([
            'error' => 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit'
        ], 403);
    }
    return redirect()->route('jadwal_posyandu.index')
        ->with('error', 'Hanya jadwal dengan status "Terjadwal" yang boleh diedit');
}
```

**Validasi Tanggal:**
```php
// 3. Validasi tanggal baru > tanggal lama + 1 hari
$minDate = date('Y-m-d', strtotime($jadwalLama->tanggal_pelaksanaan . ' +1 day'));

$request->validate([
    'tanggal_pelaksanaan' => [
        'required',
        'date',
        'after:' . $minDate
    ],
    // ... validasi lainnya ...
], [
    'tanggal_pelaksanaan.after' => "Tanggal harus lebih dari {$minDate} (H+1 dari jadwal semula)",
]);
```

**Update Tanpa Status:**
```php
// 4. Update TANPA mengubah status (status tetap = 1 dari database)
DB::table('jadwal_posyandu')->where('id_jadwal', $id)->update([
    'tanggal_pelaksanaan' => $request->tanggal_pelaksanaan,
    'tema'                => $request->tema,
    'lokasi'              => $request->lokasi,
    'kegiatan'            => $kegiatan,
    'keterangan'          => $request->keterangan,
    // Status TIDAK di-update - tetap dari database
    'updated_at'          => now(),
]);
```

**Dampak:**
- Validasi server-side untuk keamanan
- Status tidak pernah berubah saat edit
- Error message jelas untuk user

---

## 🔄 FLOW EDIT YANG BARU

```
┌─────────────────────────────────────────────────────────────────┐
│                    FLOW EDIT JADWAL POSYANDU                    │
└─────────────────────────────────────────────────────────────────┘

User klik Edit (hanya muncul jika status = 1)
     │
     ▼
Fetch data jadwal dari database (/jadwal_posyandu/{id})
     │
     ▼
Hitung minimal tanggal = tanggal_lama + 1 hari
     │
     ▼
Modal terbuka dengan:
- Tanggal (min = H+1, browser disable tanggal sebelumnya)
- Tema, Lokasi (bisa diubah)
- Status (readonly badge "Terjadwal")
- Kegiatan (bisa diubah)
- Hint: "Minimal tanggal: X (H+1 dari jadwal semula)"
     │
     ▼
User ubah data & klik "Simpan Perubahan"
     │
     ▼
JavaScript validasi (client-side):
- tanggal_baru > min_date ✅
- tema & lokasi tidak kosong ✅
     │
     ▼
Fetch POST ke /jadwal_posyandu/{id}
 dengan payload (TANPA status)
     │
     ▼
Controller validasi (server-side):
1. Cek status = 1? → 403 jika tidak ✅
2. Cek tanggal_baru > min_date? → Validation error jika tidak ✅
     │
     ▼
Update database:
- tanggal_pelaksanaan = baru
- tema = baru
- lokasi = baru
- kegiatan = baru
- keterangan = baru
- status = TETAP (tidak di-update)
     │
     ▼
Reload halaman → Data baru muncul
```

---

## 🧪 TESTING CHECKLIST

### Test Case 1: Edit Jadwal Status Terjadwal
- [ ] Buat jadwal baru (status otomatis = 1)
- [ ] Klik tombol Edit → Modal terbuka
- [ ] Cek hint tanggal minimal muncul
- [ ] Coba pilih tanggal yang sama → Browser disable
- [ ] Coba pilih tanggal sebelumnya → Browser disable
- [ ] Pilih tanggal H+1 → Bisa
- [ ] Ubah tema/lokasi
- [ ] Klik "Simpan Perubahan" → Berhasil
- [ ] Cek database → Status tetap = 1

### Test Case 2: Edit Jadwal Status Berlangsung
- [ ] Update status jadwal ke 2 (via database/tools)
- [ ] Refresh halaman
- [ ] Tombol Edit **TIDAK MUNCUL** ✅

### Test Case 3: Edit Jadwal Status Selesai
- [ ] Update status jadwal ke 3 (via database/tools)
- [ ] Refresh halaman
- [ ] Tombol Edit **TIDAK MUNCUL** ✅

### Test Case 4: Edit Jadwal Status Dibatalkan
- [ ] Update status jadwal ke 4 (via database/tools)
- [ ] Refresh halaman
- [ ] Tombol Edit **TIDAK MUNCUL** ✅

### Test Case 5: Validasi Tanggal di Server
- [ ] Buka browser DevTools → Network tab
- [ ] Edit jadwal, pilih tanggal valid
- [ ] Intercept request, ubah tanggal jadi tidak valid
- [ ] Send request → Server reject dengan error 422 ✅

### Test Case 6: Validasi Status di Server
- [ ] Buka browser DevTools → Network tab
- [ ] Edit jadwal status = 1
- [ ] Intercept request, ubah payload tambah `"status": 2`
- [ ] Send request → Server ignore, status tetap = 1 ✅

---

## 📋 RINGKASAN PERUBAHAN

| File | Baris Diubah | Perubahan Utama |
|------|--------------|-----------------|
| `jadwal_posyandu.blade.php` | 185 | Tombol Edit hanya untuk status = 1 |
| `M_editJadwal.blade.php` | 44-52 | Dropdown status → Readonly info |
| `M_editJadwal.blade.php` | 28 | Input tanggal + atribut min |
| `jadwal_posyandu.css` | 4-28 | CSS status readonly |
| `jadwal_posyandu.js` | 4-11 | Helper function formatDateIndo |
| `jadwal_posyandu.js` | 68-85 | Hitung & set minimal tanggal |
| `jadwal_posyandu.js` | 115-120 | Validasi tanggal client-side |
| `jadwal_posyandu.js` | 135-140 | Payload tanpa status |
| `jadwalPosyanduController.php` | 107-165 | Validasi status & tanggal server-side |

**Total:** 9 file, ~60 baris perubahan

---

## 🔐 KEAMANAN

### Validasi Client-Side (JavaScript)
- ✅ Browser disable tanggal tidak valid
- ✅ Alert jika tanggal tidak valid
- ✅ **Bisa di-bypass** oleh user advanced

### Validasi Server-Side (Controller)
- ✅ Cek status = 1 sebelum edit
- ✅ Validasi tanggal > min_date
- ✅ Status tidak di-update
- ✅ **Tidak bisa di-bypass**

### Best Practice
> **Always validate on server!** Client-side validation adalah UX enhancement, bukan keamanan.

---

## 🎨 UX IMPROVEMENTS

### Sebelum
- ❌ Dropdown status membingungkan
- ❌ Tidak ada hint tanggal minimal
- ❌ Bisa pilih tanggal yang sama
- ❌ Tidak jelas kenapa edit gagal

### Setelah
- ✅ Status readonly dengan badge jelas
- ✅ Hint "Minimal tanggal: X (H+1 dari jadwal semula)"
- ✅ Browser disable tanggal tidak valid
- ✅ Error message jelas dan spesifik

---

## 🚀 NEXT STEPS (OPSIONAL)

1. **Tambah notifikasi real-time** saat user pilih tanggal valid
2. **Warna merah** di input date jika tanggal tidak valid
3. **Disable tombol "Simpan"** jika form tidak valid
4. **Konfirmasi sebelum edit** dengan modal "Are you sure?"
5. **Log audit trail** siapa yang edit jadwal kapan

---

**Dokumentasi ini dibuat untuk referensi pengembangan future**  
*Simpan untuk maintenance dan enhancement selanjutnya*
