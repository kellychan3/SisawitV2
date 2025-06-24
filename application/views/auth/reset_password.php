<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <style>
        input.form-control {
            height: 45px;
        }

        input.form-control:focus {
            height: 45px;
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
            display: none;
        }

        input:invalid + .invalid-feedback {
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

                                    <!-- Flash message -->
                                    <?php if ($this->session->flashdata('error')): ?>
                                        <div class="alert alert-danger"><?= $this->session->flashdata('error') ?></div>
                                    <?php endif; ?>
                                    <?php if ($this->session->flashdata('success')): ?>
                                        <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
                                    <?php endif; ?>

                                    <div class="form-body">
                                        <form class="row g-3 needs-validation" method="post" action="<?= site_url('lupa_sandi/submit_reset_password'); ?>" novalidate>
                                            <div class="col-12">
                                                <label for="password">Kata Sandi Baru</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan Kata Sandi Baru" required>
                                                    <i class="bx bx-show password-toggle-icon" onclick="togglePassword('password', this)"></i>
                                                </div>
                                                <div class="invalid-feedback">Kolom ini wajib diisi.</div>
                                            </div>
                                            <div class="col-12">
                                                <label for="password_konfirmasi">Ulangi Kata Sandi Baru</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control" name="password_konfirmasi" id="password_konfirmasi" placeholder="Masukkan Kembali Kata Sandi Baru" required>
                                                    <i class="bx bx-show password-toggle-icon" onclick="togglePassword('password_konfirmasi', this)"></i>
                                                </div>
                                                <div class="invalid-feedback">Kolom ini wajib diisi.</div>
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

    <!-- Bootstrap & JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script>
        // Bootstrap validation script
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Toggle show/hide password
        function togglePassword(id, icon) {
            const input = document.getElementById(id);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            } else {
                input.type = "password";
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            }
        }
    </script>
</body>

</html>
