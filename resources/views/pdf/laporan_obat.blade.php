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

.section-content{
    padding-left:12px;
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

<div class="section-content">

<table style="border:none;width:100%;">

<tr>
    <td style="border:none;width:120px;">Nama Kegiatan</td>
    <td style="border:none;width:10px;">:</td>
    <td style="border:none;">{{ $jadwal->tema }}</td>
</tr>

<tr>
    <td style="border:none;">Tanggal Kegiatan</td>
    <td style="border:none;">:</td>
    <td style="border:none;">
        {{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d-m-Y') }}
    </td>
</tr>

<tr>
    <td style="border:none;">Lokasi</td>
    <td style="border:none;">:</td>
    <td style="border:none;">
        {{ $jadwal->lokasi ?? '-' }}
    </td>
</tr>

<tr>
    <td style="border:none;">Nama Petugas</td>
    <td style="border:none;">:</td>
    <td style="border:none;">
        {{ $petugas }}
    </td>
</tr>

</table>

</div>

<p class="section-title">
B. DATA OBAT KELUAR
</p>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Nama Obat</th>
    <th>Tipe Obat</th>
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

    <td>{{ $item->keluar }}</td>
<td>{{ $item->sisa_stok }}</td>
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