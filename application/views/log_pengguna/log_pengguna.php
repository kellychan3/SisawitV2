<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Data Pengguna</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Log Aktivitas Pengguna</li>
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
                        <?php if ($SystemLog) : ?>
                            <thead>
                                <th>Tanggal Log</th>
                                <th>Nama Log</th>
                            </thead>
                            <?php foreach ($SystemLog as $sl) : ?>
                                <tr>
                                    <td><?= $sl['date']; ?></td>
                                    <td><?= $sl['value']; ?></td>
                                <?php endforeach; ?>
                                </tr>
                            <?php else : ?>
                                <tr style="text-align:center">
                                    <th>Data Log Tidak Tersedia</th>
                                </tr>
                            <?php endif ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!--end page wrapper -->
<!--start overlay-->
<div class="overlay toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
</div>