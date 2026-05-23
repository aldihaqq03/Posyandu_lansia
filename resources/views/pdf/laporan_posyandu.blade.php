<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
body{
    font-family: "Times New Roman", serif;
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
    margin-left:13px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th,
table td{
    border:1px solid #000;
    padding:4px;
    font-size:11px;
}

.ttd{
    margin-top:80px;
    width:100%;
}

.ttd-kanan{
    float:right;
    text-align:center;
}
</style>

</head>
<body>

<div class="judul">
    <strong>LAPORAN KEGIATAN POSYANDU LANSIA</strong><br>
    POSYANDU PEGAGAN<br>
    DESA PONCOGATI KECAMATAN CURAHDAMI
</div>

<div class="garis"></div>

<p class="section-title">
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
    <td style="border:none;">{{ $jadwal->lokasi ?? '-' }}</td>
</tr>

</table>

</div>

<p class="section-title">
B. RINGKASAN KEGIATAN
</p>

<div class="section-content">

<table style="border:none;width:100%;">

<tr>
    <td style="border:none;width:120px;">Jumlah Kehadiran Lansia</td>
    <td style="border:none;width:10px;">:</td>
    <td style="border:none;">{{ count($lansia) }} Orang</td>
</tr>

<tr>
    <td style="border:none;">1. Perempuan</td>
    <td style="border:none;">:</td>
    <td style="border:none;">
        {{ $lansia->where('jenis_kelamin','P')->count() }} Orang
    </td>
</tr>

<tr>
    <td style="border:none;">2. Laki-laki</td>
    <td style="border:none;">:</td>
    <td style="border:none;">
        {{ $lansia->where('jenis_kelamin','L')->count() }} Orang
    </td>
</tr>

<tr>
    <td style="border:none;vertical-align:top;">
        Nama Petugas Kesehatan
    </td>

    <td style="border:none;vertical-align:top;">
        :
    </td>

    <td style="border:none;">
        <span style="display:block;">
            @foreach($petugas as $p)
                {{ $loop->iteration }}. {{ $p->nama }}<br>
            @endforeach
        </span>
    </td>
</tr>
</table>

</div>

<p class="section-title">
C. DATA PELAYANAN LANSIA
</p>

<table>
<thead>
<tr>
<th>No</th>
<th>Nama Lansia</th>
<th>NIK</th>
<th>JK</th>
<th>Umur</th>
<th>Alamat</th>
<th>Diagnosa</th>
<th>Obat</th>
</tr>
</thead>

<tbody>
@foreach($lansia as $item)
<tr>
<td>{{ $loop->iteration }}</td>
<td>{{ $item->nama_lansia }}</td>
<td>{{ $item->nik ?? '-' }}</td>
<td>{{ $item->jenis_kelamin ?? '-' }}</td>
<td>{{ $item->umur ?? '-' }}</td>
<td>{{ $item->alamat ?? '-' }}</td>
<td>{{ $item->diagnosa ?? '-' }}</td>
<td>{{ $item->obat ?? '-' }}</td>
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

</body>
</html>