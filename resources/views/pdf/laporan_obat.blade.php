<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body{
    font-family:"Times New Roman", serif;
    font-size:12px;
    margin:30px;
}

.judul{
    text-align:center;
    line-height:1.7;
}

.garis{
    border-top:2px solid #000;
    margin-top:10px;
    margin-bottom:10px;
}

.section-title{
    font-weight:bold;
    margin-top:10px;
    margin-bottom:5px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    border:1px solid #000;
    padding:5px;
    font-size:11px;
}

.identitas td{
    border:none;
    padding:2px;
}

.ttd{
    margin-top:70px;
    text-align:right;
}
</style>

</head>
<body>

<div class="judul">
    <strong>LAPORAN OBAT KELUAR POSYANDU LANSIA</strong><br>
    POSYANDU PEGAGAN<br>
    DESA PONCOGATI KECAMATAN CURAHDAMI
</div>

<div class="garis"></div>

<p class="section-title">
A. IDENTITAS KEGIATAN
</p>

<table class="identitas">

<tr>
    <td width="150">Nama Kegiatan</td>
    <td width="10">:</td>
    <td>{{ $jadwal->tema }}</td>
</tr>

<tr>
    <td>Tanggal Posyandu</td>
    <td>:</td>
    <td>
        {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d-m-Y') }}
    </td>
</tr>

<tr>
    <td>Lokasi</td>
    <td>:</td>
    <td>{{ $jadwal->lokasi ?? '-' }}</td>
</tr>

<tr>
    <td>Nama Petugas</td>
    <td>:</td>
    <td>{{ $petugas }}</td>
</tr>

</table>

<p class="section-title">
C. DATA OBAT KELUAR
</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Obat</th>
    <th>Tipe Obat</th>
    <th>Satuan</th>
    <th>Jumlah Keluar</th>
    <th>Sisa Stok</th>
</tr>
</thead>

<tbody>
@foreach($obat as $item)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->nama_obat }}</td>
    <td>{{ $item->tipe_obat ?? '-' }}</td>
    <td>{{ $item->satuan ?? 'Tablet' }}</td>
    <td>{{ $item->jumlah_obat }}</td>
    <td>{{ $item->stock }}</td>
</tr>
@endforeach
</tbody>
</table>

<div class="ttd">

<div class="ttd-kanan">

Bondowoso,
{{ now()->format('d-m-Y') }}

<br>

Mengetahui,
<br>
Ketua Posyandu Lansia

<br><br><br><br><br>

(Indri)

</div>

</div>