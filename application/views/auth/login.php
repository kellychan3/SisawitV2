<style>
.invalid-feedback {
    display: none;
    color: #dc3545;
    font-size: 0.875em;
}
.form-control.is-invalid ~ .invalid-feedback {
    display: block;
}
</style>

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

                                    <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success') ?></div>
<?php endif; ?>


                                    <div class="form-body">
                                        <form id="loginForm" class="row g-3" method="POST" action="<?= base_url('authentication/ceklogin'); ?>">

    <div class="col-12">
        <label for="email" class="form-label">Email/No.Hp</label>
        <input type="text" class="form-control" name="email" id="email" placeholder="Masukkan Email/No. HP">
        <div id="emailAlert" class="invalid-feedback">Email/No.HP tidak boleh kosong!</div>
    </div>

    <div class="col-12">
        <label for="password" class="form-label">Kata Sandi</label>
        <div class="input-group">
            <input type="password" class="form-control border-end-0" name="password" id="password" placeholder="Masukkan Kata Sandi">
            <span class="input-group-text bg-transparent" id="togglePassword" style="cursor:pointer;">
                <i class='bx bx-hide'></i>
            </span>
        </div>
        <div id="passwordAlert" class="invalid-feedback">Kata sandi tidak boleh kosong!</div>
    </div>

    <a href="<?= site_url('lupa_sandi'); ?>">
        <label class="text-end d-block" style="cursor:pointer; color:black;">Lupa Kata Sandi?</label>
    </a>

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
</body>

<script>
document.getElementById('loginForm').addEventListener('submit', function (e) {
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const emailAlert = document.getElementById('emailAlert');
    const passwordAlert = document.getElementById('passwordAlert');

    let valid = true;

    emailAlert.style.display = 'none';
    passwordAlert.style.display = 'none';
    email.classList.remove('is-invalid');
    password.classList.remove('is-invalid');

    if (!email.value.trim()) {
        email.classList.add('is-invalid');
        emailAlert.style.display = 'block';
        valid = false;
    }

    if (!password.value.trim()) {
        password.classList.add('is-invalid');
        passwordAlert.style.display = 'block';
        valid = false;
    }

    if (!valid) {
        e.preventDefault();
    }
});

// Toggle mata password
document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('i');
    const isVisible = passwordInput.type === 'text';

    passwordInput.type = isVisible ? 'password' : 'text';
    icon.classList.toggle('bx-show', !isVisible);
    icon.classList.toggle('bx-hide', isVisible);
});
</script>

