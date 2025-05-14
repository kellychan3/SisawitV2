<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Perkebunan</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Data Kebun</li>
                    </ol>
                </nav>
            </div>
        </div>
		<a class="btn btn-warning" href="<?= base_url('KebunPdf'); ?>">Cetak File PDF</a>
        <hr />

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 row-cols-xl-5">
            <?php foreach ($kebun as $k) : ?>
                <div class="col">
                    <div class="card border-primary border-bottom border-3 border-0">
                        <img src="assets/images/palm-oil.png" style="padding:25px 25px 0px 25px" class="card-img-top" alt="...">
                        <div class="card-body">
                            <h5 class="card-title text-primary text-center">Kebun <?= $k['nama']; ?></h5>
                            <hr>
                            <div class="d-flex align-items-center gap-2 justify-content-center">
                                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#profilKebunModal<?php echo $k['id_kebun']; ?>">Detail Kebun</a>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="profilKebunModal<?php echo $k['id_kebun']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="card-title d-flex align-items-center">
                                    <h6 class="mb-0 text-primary">Detail Kebun</h6>
                                </div>
                                <hr>
                                <input type="hidden" name="id" id="id" value="<?= $k['id_kebun']; ?>">
                                <forms class="row g-3" method="post" action="<?= base_url('Asset/editAsset'); ?>">
									<div class="col-12">
                                        <label for="" class="form-label">Nama Kebun</label>
                                        <input type="text" class="form-control" id="" value="<?php echo $k['nama']; ?>" name="" readonly>
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Luas Kebun (Meter Persegi)</label>
                                        <input type="text" class="form-control" id="" value="<?php echo $k['luas']; ?>" name="" readonly>
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Jenis Tanah</label>
                                        <input type="text" class="form-control" id="" value="<?php echo $k['jenis_tanah']; ?>" name="" readonly>
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Desa</label>
                                        <input type="text" class="form-control" id="" value="<?php echo $k['desa']; ?>" name="" readonly>
                                    </div>
                                    <br>
                                    <div class="col-md-6">
                                        <label for="" class="form-label">Kecamatan</label>
                                        <input type="text" class="form-control" id="" value="<?php echo $k['kecamatan']; ?>" name="" readonly>
                                    </div>
                                    <br>
                                    <div class="col-12">
                                        <label for="" class="form-label">Kabupaten</label>
                                        <input type="text" class="form-control" id="" value="<?php echo $k['kabupaten']; ?>" name="" readonly>
                                    </div>
                                    <br>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </forms>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
    </div>
</div>
