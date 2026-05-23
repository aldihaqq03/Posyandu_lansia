<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Obat</title>
</head>
<body>

<h2>Laporan Obat Keluar</h2>

<p>
Tanggal :
{{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d-m-Y') }}
</p>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Obat</th>
            <th>Jumlah Keluar</th>
        </tr>
    </thead>

    <tbody>
        @foreach($obat as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_obat }}</td>
            <td>{{ $item->jumlah_obat }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>