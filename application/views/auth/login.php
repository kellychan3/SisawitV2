<body class="bg-login">
    <!--wrapper-->
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
                                        <h3 class="">Sisawit V2 - Masuk</h3>
                                    </div>

                                    <!-- Flash message dari server -->
                                    <?= $this->session->flashdata('message'); ?>

                                    <div class="form-body">
                                        <form id="loginForm" class="row g-3" method="POST" action="<?= base_url('authentication/ceklogin'); ?>">

                                            <div class="col-12">
                                                <label for="email" class="form-label">Email/No.Hp</label>
                                                <div id="emailAlert" class="alert alert-danger py-1 px-2 mb-2" style="display: none;">Email/No.HP tidak boleh kosong!</div>
                                                <input type="text" class="form-control" name="email" id="email" placeholder="Masukkan Email/No. HP">
                                            </div>

                                            <div class="col-12">
                                                <label for="password" class="form-label">Kata Sandi</label>
                                                <div id="passwordAlert" class="alert alert-danger py-1 px-2 mb-2" style="display: none;">Kata sandi tidak boleh kosong!</div>
                                                <div class="input-group" id="password-group">
                                                    <input type="password" class="form-control border-end-0" name="password" id="password" placeholder="Masukkan Kata Sandi">
                                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                                </div>
                                            </div>

                                            <!-- Lupa Password -->
                                            <a href="<?= site_url('lupa_sandi'); ?>">
                                                <label class="text-end d-block" style="cursor:pointer; color:black;">Lupa Kata Sandi?</label>
                                            </a>

                                            <!-- Submit Button -->
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i> Masuk</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <!-- Validasi form sebelum submit -->
    <script>
        document.getElementById('loginForm').addEventListener('submit', function (e) {
            let email = document.getElementById('email').value.trim();
            let password = document.getElementById('password').value.trim();
            let emailAlert = document.getElementById('emailAlert');
            let passwordAlert = document.getElementById('passwordAlert');

            emailAlert.style.display = 'none';
            passwordAlert.style.display = 'none';

            if (!email || !password) {
                e.preventDefault(); // Hentikan submit ke server

                if (!email) emailAlert.style.display = 'block';
                if (!password) passwordAlert.style.display = 'block';
            }
        });
    </script>
</body>
