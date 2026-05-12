{{-- resources/views/skrining/_resep_row.blade.php --}}
{{-- Digunakan untuk old() repopulate dan JS dynamic row --}}
<div class="resep-row">
    <div class="resep-row-inner">
        <select name="resep[{{ $i }}][id_obat]" class="form-control" required>
            <option value="">-- Pilih Obat --</option>
            @foreach($obat as $o)
                <option value="{{ $o->id_obat }}"
                    {{ ($r['id_obat'] ?? '') == $o->id_obat ? 'selected' : '' }}>
                    {{ $o->nama_obat }}
                </option>
            @endforeach
        </select>
        <input type="text" name="resep[{{ $i }}][dosis]" class="form-control"
            placeholder="Dosis (cth: 500mg)" value="{{ $r['dosis'] ?? '' }}" required>
        <input type="text" name="resep[{{ $i }}][frekuensi]" class="form-control"
            placeholder="Frekuensi (cth: 3x1)" value="{{ $r['frekuensi'] ?? '' }}" required>
        <input type="text" name="resep[{{ $i }}][keterangan]" class="form-control"
            placeholder="Keterangan (opsional)" value="{{ $r['keterangan'] ?? '' }}">
        <button type="button" class="btn-remove-resep">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
</div>