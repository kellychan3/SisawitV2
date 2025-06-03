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
                        <li class="breadcrumb-item active" aria-current="page">Data Panen</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- End Breadcrumb -->

        <hr />

        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#panenTanggal" role="tab" aria-selected="true">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-calendar-event font-18 me-1'></i></div>
                                        <div class="tab-title">Harian</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#panenBulan" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-calendar font-18 me-1'></i></div>
                                        <div class="tab-title">Bulanan</div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#panenTahun" role="tab" aria-selected="false">
                                    <div class="d-flex align-items-center">
                                        <div class="tab-icon"><i class='bx bx-calendar-check font-18 me-1'></i></div>
                                        <div class="tab-title">Tahunan</div>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content py-3">
                            <!-- Tab Harian -->
                            <div class="tab-pane fade show active" id="panenTanggal" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <?php foreach ($panenPerTanggal['title'] as $title): ?>
                                                    <th><?= $title; ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($panenPerTanggal['data'] as $data): ?>
                                                <tr>
                                                    <?php foreach ($panenPerTanggal['title'] as $title): ?>
                                                        <?php if ($data[$title]): ?>
                                                            <?php if ($title == 'Tanggal Panen'): ?>
                                                                <td><?= date('d M Y', strtotime($data[$title])); ?></td>
                                                            <?php else: ?>
                                                                <td><?= $data[$title]; ?></td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td>-</td>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Bulanan -->
                            <div class="tab-pane fade" id="panenBulan" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <?php foreach ($panenPerBulan['title'] as $title): ?>
                                                    <th><?= $title; ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($panenPerBulan['data'] as $data): ?>
                                                <tr>
                                                    <?php foreach ($panenPerBulan['title'] as $title): ?>
                                                        <?php if ($data[$title]): ?>
                                                            <?php if ($title == 'Bulan Panen'): ?>
                                                                <td><?= date('M Y', strtotime($data[$title])); ?></td>
                                                            <?php else: ?>
                                                                <td><?= $data[$title]; ?></td>
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <td>-</td>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Tab Tahunan -->
                            <div class="tab-pane fade" id="panenTahun" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <?php foreach ($panenPerTahun['title'] as $title): ?>
                                                    <th><?= $title; ?></th>
                                                <?php endforeach; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($panenPerTahun['data'] as $data): ?>
                                                <tr>
                                                    <?php foreach ($panenPerTahun['title'] as $title): ?>
                                                        <td><?= $data[$title] ?? '-'; ?></td>
                                                    <?php endforeach; ?>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div> <!-- end tab-content -->
                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
</div>

