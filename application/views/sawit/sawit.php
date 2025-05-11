<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Master Data</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Blok Sawit</li>
                    </ol>
                </nav>
            </div>
        </div>
		<a class="btn btn-warning" href="<?= base_url('KebunPdf'); ?>">Generate PDF Kebun</a>
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
                                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#profilKebunModal<?php echo $k['id_kebun']; ?>">Profil</a>
                                <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#pohonKebunModal<?php echo $k['id_kebun']; ?>">Monitoring</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="profilKebunModal<?php echo $k['id_kebun']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bx-data me-1 font-22 text-primary"></i>
                                    </div>
                                    <h6 class="mb-0 text-primary">Detail Kebun</h6>
                                </div>
                                <hr>
                                <input type="hidden" name="id" id="id" value="<?= $k['id_kebun']; ?>">
                                <forms class="row g-3" method="post" action="<?= base_url('Asset/editAsset'); ?>">
									<a class="btn btn-warning ajax-link" href="<?= base_url('PohonPdf?id_kebun=' . $k['id_kebun']); ?>">Generate PDF Pohon Kebun</a>
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
                                        <label for="" class="form-label">Tahun Tanam</label>
                                        <input type="text" class="form-control" id="" value="<?php echo $k['tahun_tanam']; ?>" name="" readonly>
                                    </div>
                                    <br>
                                    <div class="col-12">
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
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </forms>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="pohonKebunModal<?php echo $k['id_kebun']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="card-title d-flex align-items-center">
                                    <div><i class="bx bx-data me-1 font-22 text-primary"></i>
                                    </div>
                                    <h6 class="mb-0 text-primary">Data Pohon Kebun <?= $k['nama']; ?></h6>
                                </div>
                                <hr>
								<a class="btn btn-warning" href="<?= base_url('PohonPdf?id_kebun=' . $k['id_kebun']); ?>">Generate PDF Pohon Kebun</a>
								<br>
								<br>
                                <?php
                                $i = 1;
                                foreach ($pohon as $p) :
                                ?>
                                    <?php if ($k['id_kebun'] == $p['id_kebun']) { ?>
                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $i; ?>" aria-expanded="false" aria-controls="collapse">
                                                        <?php echo $p['nama']; ?>
                                                    </button>
                                                </h2>
                                                <div id="collapse<?= $i; ?>" class="accordion-collapse collapse hide" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <!-- SECTION TANGGAL PEMERIKSAAN -->
                                                    <?php
                                                    $j = 1;
                                                    foreach ($pohon_log as $pl) :
                                                    ?>
                                                        <?php if ($p['id_pohon'] == $pl['id_pohon']) { ?>
                                                            <div class="accordion-body">
                                                                <div class="accordion" id="accordionDetail">
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="headingOne">
                                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDetail<?= $i; ?>" aria-expanded="true" aria-controls="collapse">
                                                                                Tanggal Pemeriksaan : <?php echo $pl['tanggal_pemeriksaan']; ?>
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapseDetail<?= $i; ?>" class="accordion-collapse collapse hide" aria-labelledby="headingOne" data-bs-parent="#accordionDetail">
                                                                            <div class="accordion-body">
                                                                                <!-- TABEL DETAIL PEMERIKSAAN -->
                                                                                <forms class="row g-3">
                                                                                    <div class="col-md-6">
                                                                                        <label for="" class="form-label">Jumlah Buah Tandan</label>
                                                                                        <input type="text" class="form-control" id="" value="<?php echo $pl['buah_tandan']; ?>" name="" readonly>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="" class="form-label">Tandan Mentah</label>
                                                                                        <input type="text" class="form-control" id="" value="<?php echo $pl['buah_tandan_mentah']; ?>" name="" readonly>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="" class="form-label">Tandan Matang</label>
                                                                                        <input type="text" class="form-control" id="" value="<?php echo $pl['buah_tandan_matang']; ?>" name="" readonly>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="" class="form-label">Tandan Segera Matang</label>
                                                                                        <input type="text" class="form-control" id="" value="<?php echo $pl['buah_tandan_segera_matang']; ?>" name="" readonly>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="" class="form-label">Kondisi Daun</label>
                                                                                        <input type="text" class="form-control" id="" value="<?php if ($pl['kondisi_daun'] == 1) {
                                                                                                                                                    echo "Baik";
                                                                                                                                                } else {
                                                                                                                                                    echo "Tidak Baik";
                                                                                                                                                } ?>" name="" readonly>
                                                                                    </div>
                                                                                    <div class="col-md-6">
                                                                                        <label for="" class="form-label">Kondisi Batang</label>
                                                                                        <input type="text" class="form-control" id="" value="<?php if ($pl['kondisi_batang'] == 1) {
                                                                                                                                                    echo "Baik";
                                                                                                                                                } else {
                                                                                                                                                    echo "Tidak Baik";
                                                                                                                                                } ?>" name="" readonly>
                                                                                    </div>
                                                                                    <div class="col-12">
                                                                                        <label for="" class="form-label">Tanggal Pemeriksaan</label>
                                                                                        <input type="text" class="form-control" id="" value="<?php echo $pl['tanggal_pemeriksaan']; ?>" name="" readonly>
                                                                                    </div>
                                                                                </forms>
                                                                                <!-- END OF SECTION DETAIL PEMERIKSAAN -->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php }
                                                        $j++;
                                                        ?>
                                                    <?php endforeach; ?>
                                                    <!-- END OF SECTION TANGGAL PEMERIKSAAN -->
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                    $i++; ?>
                                <?php endforeach; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
