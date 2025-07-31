<style>
    /* Hilangkan spinner di input number untuk Chrome, Safari, Edge, Opera */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Hilangkan spinner di Firefox */
input[type=number] {
    -moz-appearance: textfield;
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
                                        <h3 class="">Verifikasi OTP</h3>
                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3 needs-validation" method="post" action="<?= site_url('lupa_sandi/submit_otp'); ?>" novalidate>
                                            <label class="text-center d-block">Silakan Masukkan OTP yang Anda dapat dari email</label>
                                            <div class="col-12">
                                                <?php if ($this->session->flashdata('error')): ?>
                                                    <div class="alert alert-danger py-1 px-2" style="font-size: 0.9rem;">
                                                        <?= $this->session->flashdata('error'); ?>
                                                    </div>
                                                <?php endif; ?>

                                                <input type="number" class="form-control" name="otp" id="otp" placeholder="Masukkan OTP" required>
                                                <div class="invalid-feedback">
                                                    Kode OTP wajib diisi.
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i> Verifikasi</button>
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
        function validateOtpForm(form) {
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return false;
            }
            return true;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const forms = document.querySelectorAll('.needs-validation');
            forms.forEach(form => {
                form.addEventListener('submit', function (event) {
                    if (!validateOtpForm(form)) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                });
            });
        });
    </script>

</body>
