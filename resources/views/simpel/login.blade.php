<!DOCTYPE html>
<htm
l lang="id">

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
                <h2 class="title-main">SIMPEL</h2>
                <h4 class="title">SISTEM INFORMASI PEDULI LANSIA</h3>


                    <form>

                        <label>Nama Lengkap</label>
                        <input type="text" placeholder="Masukkan nama lengkap">

                        <label>Kata Sandi</label>
                        <input type="password" placeholder="********">

                        <button type="login" class="btn-primary">
                            login
                        </button>

                        <label>Sudah punya akun?</label>
                        <a href="{{ route('register') }}" class="btn-secondary">
                            Masuk




                    </form>
            </div>

</body>