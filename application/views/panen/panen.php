<?php
function renderPanenTable($dataset, $labelTanggal) {
    ?>
    <div class="table-responsive">
        <table class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <?php foreach ($dataset['title'] as $title): ?>
                        <th><?= $title; ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dataset['data'])): ?>
                    <tr>
                        <td colspan="<?= count($dataset['title']); ?>" class="text-center">
                            Data pemanenan belum pernah ditambahkan, silahkan tambahkan melalui aplikasi mobile.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($dataset['data'] as $data): ?>
                        <tr>
                            <?php foreach ($dataset['title'] as $title): ?>
                                <?php if (!empty($data[$title])): ?>
                                    <?php if ($title == $labelTanggal): ?>
                                        <td>
                                            <?= $labelTanggal == 'Tanggal Panen' ? date('d M Y', strtotime($data[$title])) :
                                                ($labelTanggal == 'Bulan Panen' ? date('M Y', strtotime($data[$title] . '-01')) :
                                                $data[$title]); ?>
                                        </td>
                                    <?php else: ?>
                                        <td><?= $data[$title]; ?> Kg</td>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <td>-</td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php } ?>

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
                        <!-- Tabs -->
                        <ul class="nav nav-tabs nav-primary" role="tablist">
                            <?php
                            $tabs = [
                                'panenTanggal' => ['label' => 'Harian', 'icon' => 'bx-calendar-event', 'active' => true],
                                'panenBulan' => ['label' => 'Bulanan', 'icon' => 'bx-calendar', 'active' => false],
                                'panenTahun' => ['label' => 'Tahunan', 'icon' => 'bx-calendar-check', 'active' => false],
                            ];
                            foreach ($tabs as $id => $tab): ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link <?= $tab['active'] ? 'active' : '' ?>" data-bs-toggle="tab" href="#<?= $id ?>" role="tab">
                                        <div class="d-flex align-items-center">
                                            <div class="tab-icon"><i class="bx <?= $tab['icon'] ?> font-18 me-1"></i></div>
                                            <div class="tab-title"><?= $tab['label'] ?></div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <!-- Tab Content -->
                        <div class="tab-content py-3">
                            <div class="tab-pane fade show active" id="panenTanggal" role="tabpanel">
                                <?php renderPanenTable($panenPerTanggal, 'Tanggal Panen'); ?>
                            </div>
                            <div class="tab-pane fade" id="panenBulan" role="tabpanel">
                                <?php renderPanenTable($panenPerBulan, 'Bulan Panen'); ?>
                            </div>
                            <div class="tab-pane fade" id="panenTahun" role="tabpanel">
                                <?php renderPanenTable($panenPerTahun, 'Tahun Panen'); ?>
                            </div>
                        </div>

                    </div> <!-- end card-body -->
                </div> <!-- end card -->
            </div>
        </div>
    </div>
</div>
