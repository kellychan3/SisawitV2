<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Panen</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
        <hr />
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <?php foreach ($panen['title'] as $title) : ?>
                                    <th><?= $title; ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($panen as $p) : ?>
                                <tr>
                                        <td><?= $p['tanggal_panen']; ?>
                                        <td><?= $p['k1']; ?>
                                        <td><?= $p['k2']; ?></td>
                                        <td><?= $p['k3']; ?>
                                        <td><?= $p['k5']; ?>
                                        <td><?= $p['k24']; ?>
                                        <td>
                                            <a class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailPanenModal<?php echo $p['id_panen']; ?>">Pemanen</a>
                                            <a class="btn btn-danger" href="<?= base_url('Panen/deleteData/') . $p['id_panen']; ?>">Hapus Data</a>
                                        </td>
                                </tr>
                                <div class="modal fade" id="detailPanenModal<?php echo $p['id_panen']; ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="card-title d-flex align-items-center">
                                                    <div><i class="bx bx-data me-1 font-22 text-primary"></i>
                                                    </div>
                                                    <h6 class="mb-0 text-primary">Pemanen</h6>
                                                </div>
                                                <hr>
                                                <input type="hidden" name="id_panen" id="id_panen" value="<?= $p['id_panen']; ?>">
                                                <forms class="row g-3">
                                                    <div class="col-12">
                                                        <label for="name" class="form-label">Petani yang melakukan panen</label>
                                                        <input type="text" class="form-control" id="name" value="<?php echo $p['id_user']; ?>" name="name" required readonly>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </forms>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>