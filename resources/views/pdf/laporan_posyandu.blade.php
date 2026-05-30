<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Laporan Kegiatan Posyandu Lansia</title>
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    body {
        font-family: 'Inter', 'Times New Roman', serif;
        font-size: 12px;
        background: #f8fafc;
        padding: 30px;
    }
    .laporan-container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 24px 28px;
    }
    .judul {
        text-align: center;
        line-height: 1.5;
        margin-bottom: 16px;
    }
    .judul strong {
        font-size: 18px;
    }
    .garis {
        border-top: 2px solid #1e293b;
        margin: 16px 0;
    }
    .section-title {
        font-weight: 700;
        font-size: 14px;
        margin: 20px 0 8px 0;
        color: #0f172a;
        border-left: 4px solid #3b82f6;
        padding-left: 10px;
    }
    .section-content {
        margin-left: 16px;
        margin-bottom: 16px;
    }
    .info-table {
        width: 100%;
        border-collapse: collapse;
    }
    .info-table td {
        padding: 6px 8px;
        vertical-align: top;
        border: none;
    }
    .info-table td:first-child {
        width: 140px;
        font-weight: 600;
        color: #475569;
    }
    .info-table td:nth-child(2) {
        width: 20px;
        text-align: center;
    }
    /* Scrollable table wrapper - seperti di halaman Data Obat */
    .table-scroll-wrapper {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 450px; /* scroll vertikal muncul jika baris banyak */
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        margin: 16px 0;
    }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        min-width: 900px;
    }
    .custom-table th {
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 700;
        font-size: 11px;
        padding: 12px 10px;
        border-bottom: 2px solid #cbd5e1;
        text-align: center;
        position: sticky;
        top: 0;
        z-index: 10;
    }
    .custom-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #e2e8f0;
        text-align: center;
        color: #334155;
    }
    .custom-table tbody tr:hover {
        background: #f8fafc;
    }
    .ttd {
        margin-top: 60px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
    }
    .ttd-kanan {
        text-align: center;
        width: 250px;
    }
    .ttd-kanan p {
        margin: 4px 0;
    }
    .sign-line {
        margin-top: 40px;
        margin-bottom: 8px;
        border-top: 1px solid #000;
        width: 200px;
        display: inline-block;
    }
    @media print {
        body {
            background: white;
            padding: 0;
        }
        .laporan-container {
            box-shadow: none;
            padding: 20px;
        }
        .table-scroll-wrapper {
            max-height: none;
            overflow: visible;
        }
        .custom-table th {
            background: #f1f5f9;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
</head>
<body>
<div class="laporan-container">
    <div class="judul">
        <strong>LAPORAN KEGIATAN POSYANDU LANSIA</strong><br>
        POSYANDU PEGAGAN<br>
        DESA PONCOGATI KECAMATAN CURAHDAMI
    </div>
    <div class="garis"></div>

    <div class="section-title">A. IDENTITAS KEGIATAN</div>
    <div class="section-content">
        <table class="info-table">
            <tr><td>Nama Kegiatan</td><td>:</td><td>{{ $jadwal->tema }}</td></tr>
            <tr><td>Tanggal Kegiatan</td><td>:</td><td>{{ \Carbon\Carbon::parse($jadwal->tanggal_pelaksanaan)->format('d-m-Y') }}</td></tr>
            <tr><td>Lokasi</td><td>:</td><td>{{ $jadwal->lokasi ?? '-' }}</td></tr>
        </table>
    </div>

    <div class="section-title">B. RINGKASAN KEGIATAN</div>
    <div class="section-content">
        <table class="info-table">
            <tr><td>Jumlah Kehadiran Lansia</td><td>:</td><td>{{ count($lansia) }} Orang</td></tr>
            <tr><td>1. Perempuan</td><td>:</td><td>{{ $lansia->where('jenis_kelamin','P')->count() }} Orang</td></tr>
            <tr><td>2. Laki-laki</td><td>:</td><td>{{ $lansia->where('jenis_kelamin','L')->count() }} Orang</td></tr>
            <tr><td>Nama Petugas Kesehatan</td><td>:</td><td>
                @foreach($petugas as $p)
                    {{ $loop->iteration }}. {{ $p->nama }}<br>
                @endforeach
            </td></tr>
        </table>
    </div>

    <div class="section-title">C. DATA PELAYANAN LANSIA</div>
    <div class="table-scroll-wrapper">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Lansia</th>
                    <th>NIK</th>
                    <th>JK</th>
                    <th>Umur</th>
                    <th>Alamat</th>
                    <th>Diagnosis</th>
                    <th>Obat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lansia as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama_lansia }}</td>
                    <td>{{ $item->nik ?? '-' }}</td>
                    <td>{{ $item->jenis_kelamin ?? '-' }}</td>
                    <td>{{ $item->umur ?? '-' }}</td>
                    <td>{{ $item->alamat ?? '-' }}</td>
                    <td>{{ $item->diagnosis ?? '-' }}</td>
                    <td>{{ $item->obat ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;">Tidak ada data lansia</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="ttd">
        <div class="ttd-kanan">
            <p>Bondowoso, {{ now()->format('d-m-Y') }}</p>
            <p>Mengetahui,<br>Ketua Posyandu Lansia</p>
            <div class="sign-line"></div>
            <p><strong>(Indri)</strong></p>
        </div>
    </div>
</div>
</body>
</html>