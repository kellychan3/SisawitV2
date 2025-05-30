		<div class="page-wrapper">
			<div class="page-content">
				<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
					<div class="breadcrumb-title pe-3">Data Perkebunan</div>
					<div class="ps-3">
						<nav aria-label="breadcrumb">
							<ol class="breadcrumb mb-0 p-0">
								<li class="breadcrumb-item"><a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-home-alt"></i></a>
								</li>
								<li class="breadcrumb-item active" aria-current="page">Data Penanaman</li>
							</ol>
						</nav>
					</div>
				</div>
				
<!-- TABEL DATA -->
<div class="card mt-4">
  <div class="card-body">
    <div class="table-responsive">
      <table id="aset" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Kebun</th>
            <th>Jenis Bibit</th>
            <th>Jumlah Bibit</th>
            <th>Tanggal Tanam</th>
            <th>Ditambahkan Oleh</th>
          </tr>
        </thead>
        <tbody>
          <!-- <?php foreach ($asset as $a): ?>
            <tr>
              <td><?= $a['namaaset']; ?></td>
              <td><?= $a['jumlahaset']; ?></td>
              <td><?= $a['jumlahaset']; ?></td>
              <!-- <td><?= $a['jenisaset']; ?></td> -->
              <td><?= $a['lokasiaset']; ?></td>
            </tr>
          <?php endforeach; ?> -->
        </tbody>
      </table>
    </div>
  </div>
</div>