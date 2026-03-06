document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("registerForm");

    const email = document.getElementById("email");
    const nik = document.getElementById("nik");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");

    const emailError = document.getElementById("emailError");
    const nikError = document.getElementById("nikError");
    const passError = document.getElementById("passError");
    const confirmError = document.getElementById("confirmError");

    // ===============================
    // SHOW / HIDE PASSWORD
    // ===============================
    window.togglePassword = function (id, icon) {

        const input = document.getElementById(id);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }

    };

    // ===============================
    // VALIDASI FORM
    // ===============================
    form.addEventListener("submit", function (e) {

        let valid = true;

        // reset error
        emailError.textContent = "";
        nikError.textContent = "";
        passError.textContent = "";
        confirmError.textContent = "";

        // ===============================
        // VALIDASI EMAIL
        // ===============================
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailRegex.test(email.value)) {
            emailError.textContent = "Email tidak valid";
            valid = false;
        }

        // ===============================
        // VALIDASI NIK
        // ===============================
        if (nik.value.length !== 16 || isNaN(nik.value)) {
            nikError.textContent = "NIK harus 16 digit angka";
            valid = false;
        }

        // ===============================
        // VALIDASI PASSWORD
        // ===============================
        if (password.value.length < 8) {
            passError.textContent = "Password minimal 8 karakter";
            valid = false;
        }

        // ===============================
        // KONFIRMASI PASSWORD
        // ===============================
        if (password.value !== confirmPassword.value) {
            confirmError.textContent = "Password tidak sama";
            valid = false;
        }

        // jika tidak valid
        if (!valid) {
            e.preventDefault();
        }

    });

});