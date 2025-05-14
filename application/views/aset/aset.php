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
								<li class="breadcrumb-item active" aria-current="page">Data Aset</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				
				<button type="button" class="btn btn-primary px-5 d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addAssetModal">
					<i class='bx bx-plus me-2 align-middle'></i>Tambah Data
				</button>

				<!-- Modal Tambah Aset -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title text-primary">Tambah Aset</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form class="row g-3" method="post" action="<?= base_url('Aset/addAset'); ?>">
          <div class="col-md-6">
            <label for="namaaset" class="form-label">Nama Aset</label>
            <input type="text" class="form-control" id="namaaset" name="namaaset" required>
          </div>
          <div class="col-md-6">
            <label for="jenisaset" class="form-label">Jenis Aset</label>
            <input type="text" class="form-control" id="jenisaset" name="jenisaset" required>
          </div>
          <div class="col-md-6">
            <label for="jumlahaset" class="form-label">Jumlah Aset</label>
            <input type="number" class="form-control" id="jumlahaset" name="jumlahaset" required>
          </div>
          <div class="col-md-6">
            <label for="lokasiaset" class="form-label">Kebun</label>
            <input type="text" class="form-control" id="lokasiaset" name="lokasiaset" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- TABEL DATA -->
<div class="card mt-4">
  <div class="card-body">
    <div class="table-responsive">
      <table id="aset" class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>Nama Aset</th>
            <th>Jumlah Aset</th>
            <th>Jenis Aset</th>
            <th>Kebun</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($asset as $a): ?>
            <tr>
              <td><?= $a['namaaset']; ?></td>
              <td><?= $a['jumlahaset']; ?></td>
              <td><?= $a['jumlahaset']; ?></td>
              <!-- <td><?= $a['jenisaset']; ?></td> -->
              <td><?= $a['lokasiaset']; ?></td>
              <td>
                <a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAssetModal<?= $a['id']; ?>">Ubah</a>
                <a class="btn btn-danger" href="<?= base_url('Aset/deleteAset/') . $a['id']; ?>">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Ubah Aset (DI LUAR TABLE) -->
<?php foreach ($asset as $a): ?>
  <div class="modal fade" id="editAssetModal<?= $a['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title text-primary">Ubah Aset</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" method="post" action="<?= base_url('Aset/editAset'); ?>">
            <input type="hidden" name="id" value="<?= $a['id']; ?>">

            <div class="col-md-6">
              <label for="namaaset<?= $a['id']; ?>" class="form-label">Nama Aset</label>
              <input type="text" class="form-control" id="namaaset<?= $a['id']; ?>" name="namaaset" value="<?= $a['namaaset']; ?>" required>
            </div>

            <!-- <div class="col-md-6">
              <label for="jenisaset<?= $a['id']; ?>" class="form-label">Jenis Aset</label>
              <input type="text" class="form-control" id="jenisaset<?= $a['id']; ?>" name="jenisaset" value="<?= $a['jenisaset']; ?>" required>
            </div> -->

            <div class="col-md-6">
              <label for="jumlahaset<?= $a['id']; ?>" class="form-label">Jumlah Aset</label>
              <input type="number" class="form-control" id="jumlahaset<?= $a['id']; ?>" name="jumlahaset" value="<?= $a['jumlahaset']; ?>" required>
            </div>

            <div class="col-md-6">
              <label for="lokasiaset<?= $a['id']; ?>" class="form-label">Kebun</label>
              <input type="text" class="form-control" id="lokasiaset<?= $a['id']; ?>" name="lokasiaset" value="<?= $a['lokasiaset']; ?>" required>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>