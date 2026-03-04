<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SIMPEL</title>

    @vite('resources/css/style.css')
</head>

<body>

    <div class="wrapper">


        <!-- FORM CARD -->
        <div class="container">
            <div class="card">


                <!-- LOGO DI DALAM CARD -->
                <div class="logo">
                    <img src="{{ asset('images/logo_posyandu.png') }}" alt="Logo Posyandu">
                </div>

                <!-- JUDUL DI DALAM CARD -->
                <h2 class="title-main">Daftar akun SIMPEL</h2>
                <h4 class="title">SISTEM INFORMASI PEDULI LANSIA</h3>


                    <form>

                        <label>Nama Lengkap</label>
                        <input type="text" placeholder="Masukkan nama lengkap">

                        <label>Nomor Induk Kependudukan (NIK)</label>
                        <input type="text" maxlength="16" placeholder="16 digit NIK">

                        <label>Nomor WhatsApp</label>
                        <input type="text" placeholder="08xxxxxxxxxx">

                        <label>Jabatan</label>
                        <select>
                            <option>Pilih Jabatan</option>
                            <option>Kader</option>
                            <option>Petugas</option>
                            <option>Admin</option>
                        </select>

                        <label>Wilayah Posyandu</label>
                        <select>
                            <option>Pilih Wilayah Kerja</option>
                            <option>Wilayah 1</option>
                            <option>Wilayah 2</option>
                        </select>

                        <label>Kata Sandi</label>
                        <input type="password" placeholder="********">

                        <label>Konfirmasi Kata Sandi</label>
                        <input type="password" placeholder="********">

                        <button type="submit" class="btn-primary">
                            Daftar Sekarang
                        </button>

                        <label>Sudah punya akun?</label>
                        <a href="{{ route('login') }}" class="btn-secondary">
                            Masuk


                    </form>
            </div>

</body>