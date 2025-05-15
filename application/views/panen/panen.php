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
        <div class="row row-cols-1 row-cols-md-1 row-cols-lg-1 row-cols-xl-1">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#panenTanggal" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-cloud-drizzle font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Daily</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#panenBulan" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-cloud-light-rain font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Monthly</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#panenTahun" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-cloud-lightning font-18 me-1'></i>
                                        </div>
                                        <div class="tab-title">Yearly</div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="panenTanggal" role="tabpanel">
                                <div class="table-responsive">
                                    <table id="tanggal" class="table table-striped table-bordered" data-searching="true" data-ordering="false" style="width:100%">
                                        <thead>
                                            <tr>
                                                <?php foreach ($panenPerTanggal['title'] as $title) : ?>
                                                    <th><?= $title; ?></th>
                                                <?php endforeach ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($panenPerTanggal['data'] as $data) : ?>
                                                <tr>
                                                    <?php foreach ($panenPerTanggal['title'] as $title) : ?>
                                                        <?php if ($data[$title]) : ?>
                                                            <?php if ($title == 'Tanggal Panen') : ?>
                                                                <th><?= date('d M Y', strtotime($data[$title])); ?></th>
                                                            <?php else : ?>
                                                                <th><?= $data[$title]; ?></th>
                                                            <?php endif ?>
                                                        <?php else : ?>
                                                            <td>-</td>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="panenBulan" role="tabpanel">
                                <div class="table-responsive">
                                    <table id="bulan" class="table table-striped table-bordered" data-searching="true" data-ordering="false" style="width:100%">
                                        <thead>
                                            <tr>
                                                <?php foreach ($panenPerBulan['title'] as $title) : ?>
                                                    <th><?= $title; ?></th>
                                                <?php endforeach ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($panenPerBulan['data'] as $data) : ?>
                                                <tr>
                                                    <?php foreach ($panenPerBulan['title'] as $title) : ?>
                                                        <?php if ($data[$title]) : ?>
                                                            <?php if ($title == 'Bulan Panen') : ?>
                                                                <th><?= date('M Y', strtotime($data[$title])); ?></th>
                                                            <?php else : ?>
                                                                <th><?= $data[$title]; ?></th>
                                                            <?php endif ?>
                                                        <?php else : ?>
                                                            <td>-</td>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="panenTahun" role="tabpanel">
                                <div class="table-responsive">
                                    <table id="tahun" class="table table-striped table-bordered" data-searching="true" data-ordering="false" style="width:100%">
                                        <thead>
                                            <tr>
                                                <?php foreach ($panenPerTahun['title'] as $title) : ?>
                                                    <th><?= $title; ?></th>
                                                <?php endforeach ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($panenPerTahun['data'] as $data) : ?>
                                                <tr>
                                                    <?php foreach ($panenPerTahun['title'] as $title) : ?>
                                                        <?php if ($data[$title]) : ?>
                                                            <th><?= $data[$title]; ?></th>
                                                        <?php else : ?>
                                                            <td>-</td>
                                                        <?php endif ?>
                                                    <?php endforeach ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>