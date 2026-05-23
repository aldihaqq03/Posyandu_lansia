<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Posyandu</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:10px;
        }

        table, th, td{
            border:1px solid #000;
        }

        th, td{
            padding:6px;
        }

        h2{
            text-align:center;
        }
    </style>
</head>
<body>

<h2>LAPORAN POSYANDU LANSIA</h2>

<p>
    <strong>Nama Kegiatan :</strong>
    {{ $jadwal->tema ?? '-' }}
</p>

<p>
    <strong>Tanggal :</strong>
    {{ $jadwal->tanggal_pelaksanaan ?? '-' }}
</p>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Lansia</th>
        </tr>
    </thead>

    <tbody>
        @foreach($lansia as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $item->nama_lansia }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>