<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Monitoring</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Forecast</li>
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
                        <div class="tab-content py-3">
                            <div class="table-responsive">
                                <table id="month" class="table table-striped table-bordered" data-searching="true" data-ordering="false" style="width:100%">
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
                        <form class="row g-3" method="post" action="<?= base_url('Forecast/Predict'); ?>">
                            <div class="col-md-6">
                                <label for="kebun" class="form-label">Pilih Kebun</label>
                                <select name="kebun" id="kebun" class="form-control" required>
                                    <option value="">Nama Kebun</option>
                                    <?php foreach ($kebun as $k) : ?>
                                        <option value="<?= $k['nama']; ?>"><?= $k['nama']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="suhu" class="form-label">Suhu</label>
                                <input type="text" class="form-control" id="suhu" name="suhu" placeholder="<?= $temperatur; ?>">
                            </div>
                            <div class="col-md-2">
                                <label for="kelembapan" class="form-label">Kelembapan</label>
                                <input type="text" class="form-control" id="kelembapan" name="kelembapan" placeholder="<?= $kelembapan; ?>" >
                            </div>
                            <div class="col-md-2">
                                <label for="totalpanen" class="form-label">Total Panen</label>
                                <input type="number" class="form-control" id="totalpanen" name="totalpanen" required>
                            </div>
                            <div class="col-md-6">
                                <label for="start_month" class="form-label">Predict Start</label>
                                <input type="date" class="form-control" id="start_month" name="start_month" required>
                            </div>
                            <div class="col-md-6">
                                <label for="end_month" class="form-label">Predict End</label>
                                <input type="date" class="form-control" id="end_month" name="end_month" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Predict</button>
                            <?php $month = 0; ?>
                            <?php foreach ($predictions as $prediction) : ?>
                                <div class="col-md-12">
                                    <label for="end_month" class="form-label">Hasil Prediksi Bulan <?= $month; ?> = </label>
                                    <?="<tr><td><b> " . $prediction[0] . " Kg</b></td></tr>"; ?>
                                </div>
                                <?php $month+=1 ; ?>
                            <?php endforeach; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>