// ============================================================
// profil.js — JavaScript untuk halaman Profil Saya
// Taruh file ini di: public/js/profil.js
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

    // ----------------------------------------------------------
    // 1. Preview foto avatar sebelum diupload
    // ----------------------------------------------------------
    const avatarInput   = document.getElementById('avatar-file');
    const avatarPreview = document.getElementById('preview-avatar');

    if (avatarInput && avatarPreview) {
        avatarInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) return;

            // Validasi ukuran file max 2MB
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto maksimal 2MB.');
                this.value = '';
                return;
            }

            // Validasi tipe file
            const allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!allowed.includes(file.type)) {
                alert('Format foto harus JPG, PNG, atau WEBP.');
                this.value = '';
                return;
            }

            // Tampilkan preview
            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    // ----------------------------------------------------------
    // 2. Auto hide alert setelah 4 detik
    // ----------------------------------------------------------
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity    = '0';
            setTimeout(function () {
                alert.style.display = 'none';
            }, 500);
        }, 4000);
    });

});

// ----------------------------------------------------------
// 3. Toggle show/hide password
// ----------------------------------------------------------
function togglePw(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// ----------------------------------------------------------
// 4. Batalkan — kembali ke halaman sebelumnya
// ----------------------------------------------------------
function batalkan() {
    window.history.back();
}