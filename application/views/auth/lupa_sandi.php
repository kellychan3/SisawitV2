<style>
    .invalid-feedback {
        display: none;
        color: #dc3545;
        font-size: 0.875em;
    }
    .was-validated input:invalid ~ .invalid-feedback {
        display: block;
    }
</style>

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
                                        <h3 class="">Lupa Kata Sandi</h3>
                                    </div>
                                    <div class="form-body">
                                       <form class="row g-3 needs-validation" method="post" action="<?= site_url('lupa_sandi/request_otp'); ?>" novalidate>
                                            <label class="text-center d-block">Silakan Masukkan Email Anda</label>
                                            <div class="col-12">
                                                <?php if ($this->session->flashdata('error')): ?>
                                                    <div class="alert alert-danger py-1 px-2" style="font-size: 0.9rem;">
                                                        <?= $this->session->flashdata('error'); ?>
                                                    </div>
                                                <?php endif; ?>

                                                <input type="text" class="form-control" name="email" id="email" placeholder="Masukkan Email">
<div id="emailEmptyFeedback" class="invalid-feedback">Kolom ini wajib diisi.</div>
<div id="emailFormatFeedback" class="invalid-feedback">Masukkan email yang valid.</div>

                                            </div>

                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i> Ubah Kata Sandi</button>
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

<script>
    function validateEmailField() {
    const emailInput = document.getElementById('email');
    const value = emailInput.value.trim();
    const emptyFeedback = document.getElementById('emailEmptyFeedback');
    const formatFeedback = document.getElementById('emailFormatFeedback');

    // Reset state
    emailInput.classList.remove('is-invalid');
    emptyFeedback.style.display = 'none';
    formatFeedback.style.display = 'none';

    if (!value) {
        emailInput.classList.add('is-invalid');
        emptyFeedback.style.display = 'block';
        return false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
        emailInput.classList.add('is-invalid');
        formatFeedback.style.display = 'block';
        return false;
    }

    return true;
}

    document.querySelector('form').addEventListener('submit', function (e) {
    if (!validateEmailField()) {
        e.preventDefault();
    }
});

</script>

</body>
