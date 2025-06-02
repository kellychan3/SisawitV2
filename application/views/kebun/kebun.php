<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Perkebunan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item">
                            <a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Data Kebun</li>
                    </ol>
                </nav>
            </div>
        </div>

        <a class="btn btn-warning mb-3" href="<?= base_url('KebunPdf'); ?>" target="_blank">Cetak File PDF</a>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-5">
            <?php foreach ($kebun as $k) : ?>
                <div class="col mb-4">
                    <div class="card border-primary border-bottom border-3 border-0">
                        <img src="<?= base_url('assets/images/palm-oil.png'); ?>" style="padding:25px" class="card-img-top" alt="Kebun">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary"><?= html_escape($k['nama_kebun']); ?></h5>
                            <hr>
                            <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#profilKebunModal<?= $k['id']; ?>">
                                Detail Kebun
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Kebun -->
                <div class="modal fade" id="profilKebunModal<?= $k['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="card-title d-flex align-items-center">
                                    <h6 class="mb-0 text-primary">Detail Kebun</h6>
                                </div>
                                <hr>
                                <input type="hidden" name="id" value="<?= $k['id']; ?>">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">Nama Kebun</label>
                                        <input type="text" class="form-control" value="<?= html_escape($k['nama_kebun']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Luas Kebun (Ha)</label>
                                        <input type="text" class="form-control" value="<?= html_escape($k['luas_kebun_ha']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Jenis Tanah</label>
                                        <input type="text" class="form-control" value="<?= html_escape($k['jenis_tanah']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Desa</label>
                                        <input type="text" class="form-control" value="<?= html_escape($k['desa']); ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Kecamatan</label>
                                        <input type="text" class="form-control" value="<?= html_escape($k['kecamatan']); ?>" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Kabupaten</label>
                                        <input type="text" class="form-control" value="<?= html_escape($k['kabupaten_kota']); ?>" readonly>
                                    </div>
                                </div>
                                <div class="modal-footer mt-3">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
