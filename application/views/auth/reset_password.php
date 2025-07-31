<!-- reset_password.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <style>
        input.form-control {
            height: 45px;
        }

        input.form-control:focus {
            box-shadow: none;
        }

        .position-relative {
            position: relative;
        }

        .password-toggle-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 20px;
            color: #6c757d;
        }

        .invalid-feedback {
            display: block;
        }
    </style>
</head>

<body class="bg-login">
    <div class="wrapper">
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        <div class="mb-4 text-center">
                            <img src="" width="180" alt="" />
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3>Ubah Kata Sandi</h3>
                                    </div>

                                    <?php if ($this->session->flashdata('error')): ?>
                                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('success')): ?>
                                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                                    <?php endif; ?>

                                    <div class="form-body">
                                        <form id="resetPasswordForm" class="row g-3" method="post" action="<?= site_url('lupa_sandi/submit_reset_password'); ?>">
                                            <div class="col-12">
                                                <label for="password">Kata Sandi Baru</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Kata Sandi Baru">
                                                    <i class="bx bx-show password-toggle-icon" onclick="togglePassword('password', this)"></i>
                                                </div>
                                                <div class="invalid-feedback" id="passwordError"></div>
                                            </div>
                                            <div class="col-12">
                                                <label for="password_konfirmasi">Ulangi Kata Sandi Baru</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" name="password_konfirmasi" id="password_konfirmasi" placeholder="Masukkan Kembali Kata Sandi Baru">
                                                    <i class="bx bx-show password-toggle-icon" onclick="togglePassword('password_konfirmasi', this)"></i>
                                                </div>
                                                <div class="invalid-feedback" id="confirmError"></div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i> Ubah Kata Sandi</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div><!-- end form-body -->

                                </div>
                            </div>
                        </div>
                    </div><!-- end col -->
                </div><!-- end row -->
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
    function togglePassword(id, icon) {
        const input = document.getElementById(id);
        const isPassword = input.type === "password";
        input.type = isPassword ? "text" : "password";
        icon.classList.toggle('bx-show', !isPassword);
        icon.classList.toggle('bx-hide', isPassword);
    }

    function validateResetPasswordForm() {
        let isValid = true;

        const password = document.getElementById('password');
        const confirm = document.getElementById('password_konfirmasi');
        const passwordError = document.getElementById('passwordError');
        const confirmError = document.getElementById('confirmError');

        // Reset state
        [password, confirm].forEach(el => el.classList.remove('is-invalid'));
        passwordError.textContent = '';
        confirmError.textContent = '';

        // Validasi Password
        if (!password.value.trim()) {
            password.classList.add('is-invalid');
            passwordError.textContent = 'Kolom kata sandi wajib diisi.';
            isValid = false;
        } else if (password.value.length < 6) {
            password.classList.add('is-invalid');
            passwordError.textContent = 'Kata sandi minimal 6 karakter.';
            isValid = false;
        }

        // Validasi Konfirmasi
        if (!confirm.value.trim()) {
            confirm.classList.add('is-invalid');
            confirmError.textContent = 'Kolom konfirmasi wajib diisi.';
            isValid = false;
        } else if (confirm.value !== password.value) {
            confirm.classList.add('is-invalid');
            confirmError.textContent = 'Konfirmasi kata sandi tidak cocok.';
            isValid = false;
        }

        return isValid;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('resetPasswordForm');
        form.addEventListener('submit', function (event) {
            if (!validateResetPasswordForm()) {
                event.preventDefault();
            }
        });
    });
</script>

</body>

</html>
