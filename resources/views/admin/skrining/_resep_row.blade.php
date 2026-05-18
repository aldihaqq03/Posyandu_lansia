{{-- resources/views/admin/skrining/_resep_row.blade.php --}}
{{-- Digunakan untuk old() repopulate dan JS dynamic row --}}
<div class="resep-row" style="margin-bottom: 12px; border-bottom: 1px dashed #ccc; padding-bottom: 12px;">
    <div class="resep-row-inner" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; align-items: flex-end;">
        <div style="grid-column: span 2;">
            <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Pilih Obat</label>
            <select name="resep[{{ $i }}][id_obat]" class="form-control" style="width: 100%;">
                <option value="">-- Pilih Obat --</option>
                @foreach($obat as $o)
                    <option value="{{ $o->id_obat }}" {{ ($r['id_obat'] ?? '') == $o->id_obat ? 'selected' : '' }}>
                        {{ $o->nama_obat }} (Stok: {{ $o->stock }})
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Dosis</label>
            <input type="text" name="resep[{{ $i }}][dosis]" class="form-control" placeholder="cth: 500mg"
                value="{{ $r['dosis'] ?? '' }}" style="width: 100%;">
        </div>
        
        <div>
            <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Frekuensi</label>
            <input type="number" min="1" name="resep[{{ $i }}][frekuensi]" class="form-control input-frekuensi" placeholder="Jml"
                value="{{ $r['frekuensi'] ?? '' }}" style="width: 100%;">
        </div>
        
        <div>
            <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Durasi Hari</label>
            <input type="number" min="1" name="resep[{{ $i }}][durasi_hari]" class="form-control" placeholder="Hari"
                value="{{ $r['durasi_hari'] ?? '' }}" style="width: 100%;">
        </div>
        
        <div>
            <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Jenis Jadwal</label>
            <select name="resep[{{ $i }}][jenis_jadwal]" class="form-control select-jenis-jadwal" style="width: 100%;">
                <option value="harian" {{ ($r['jenis_jadwal'] ?? 'harian') == 'harian' ? 'selected' : '' }}>Harian</option>
                <option value="hari_tertentu" {{ ($r['jenis_jadwal'] ?? '') == 'hari_tertentu' ? 'selected' : '' }}>Hari Tertentu</option>
            </select>
        </div>
            
        <div>
            <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Jml Obat</label>
            <input type="number" min="1" name="resep[{{ $i }}][jumlah_obat]" class="form-control" placeholder="Jml"
                value="{{ $r['jumlah_obat'] ?? 1 }}" style="width: 100%;">
        </div>
            
        <div style="grid-column: span 2;">
            <label style="display: block; font-size: 11px; margin-bottom: 4px; color: #666; font-weight: 600;">Keterangan</label>
            <input type="text" name="resep[{{ $i }}][keterangan]" class="form-control" placeholder="Opsional"
                value="{{ $r['keterangan'] ?? '' }}" style="width: 100%;">
        </div>
            
        <div style="display: flex; justify-content: flex-end;">
            <button type="button" class="btn-remove-resep" style="padding: 8px 10px; background: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; border-radius: 6px; cursor: pointer; width: 100%;">
                <i class="fa-solid fa-trash"></i> Hapus Baris
            </button>
        </div>
    </div>
    
    <div class="hari-konsumsi-group" style="display: {{ ($r['jenis_jadwal'] ?? 'harian') == 'hari_tertentu' ? 'flex' : 'none' }}; gap: 10px; margin-top: 10px; flex-wrap: wrap; background: #f8fafc; padding: 10px; border-radius: 6px; border: 1px solid #e2e8f0;">
        <div style="width: 100%; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px;">Pilih Hari Konsumsi:</div>
        @php
            $hariOrder = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];
            $selectedHari = $r['hari_konsumsi'] ?? [];
        @endphp
        @foreach($hariOrder as $h)
            <label class="checkbox-item" style="font-size: 0.85rem; display: flex; align-items: center; gap: 4px; margin-bottom: 0;">
                <input type="checkbox" name="resep[{{ $i }}][hari_konsumsi][]" value="{{ $h }}" class="chk-hari" {{ in_array($h, $selectedHari) ? 'checked' : '' }}>
                {{ ucfirst($h) }}
            </label>
        @endforeach
    </div>
</div>