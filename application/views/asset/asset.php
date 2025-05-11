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
								<li class="breadcrumb-item active" aria-current="page">Data Aset</li>
							</ol>
						</nav>
					</div>
				</div>
				<!--end breadcrumb-->
				<button type="button" class="btn btn-primary px-5" data-bs-toggle="modal" data-bs-target="#addAssetModal"><i class='bx bx-plus mr-1'></i>Tambah Data Aset</button>
				<div class="modal fade" id="addAssetModal" tabindex="-1" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-body">
								<div class="card-title d-flex align-items-center">
									<div><i class="bx bxs-user me-1 font-22 text-primary"></i>
									</div>
									<h6 class="mb-0 text-primary">Register Asset</h6>
								</div>
								<hr>
								<form class="row g-3" method="post" action="<?= base_url('Asset/addAsset'); ?>">
									<div class="col-12">
										<label for="namaaset" class="form-label">Nama Aset</label>
										<input type="text" class="form-control" id="namaaset" name="namaaset" required>
									</div>
									<div class="col-md-6">
										<label for="lokasiaset" class="form-label">Lokasi Aset</label>
										<input type="text" class="form-control" id="lokasiaset" name="lokasiaset" required>
									</div>
									<div class="col-md-6">
										<label for="jumlahaset" class="form-label">Jumlah Aset</label>
										<input type="number" class="form-control" id="jumlahaset" name="jumlahaset" required>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
										<button type="submit" class="btn btn-primary">Save changes</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="card">
					<div class="card-body">
						<div class="table-responsive">
							<table id="aset" data-searching="true" class="table table-striped table-bordered">
								<thead>
									<tr>
										<th>Nama Aset</th>
										<th>Lokasi Aset</th>
										<th>Jumlah Aset</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($asset as $a) : ?>
										<tr>
											<td><?= $a['namaaset']; ?>
											<td><?= $a['lokasiaset']; ?>
											<td><?= $a['jumlahaset']; ?>
											<td>
												<a class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editAssetModal<?php echo $a['id']; ?>">Edit Asset</a>
												<a class="btn btn-danger" href="<?= base_url('Asset/deleteAsset/') . $a['id']; ?>">Delete Asset</a>
											</td>
										</tr>
										<div class="modal fade" id="editAssetModal<?php echo $a['id']; ?>" tabindex="-1" aria-hidden="true">
											<div class="modal-dialog modal-dialog-centered">
												<div class="modal-content">
													<div class="modal-body">
														<div class="card-title d-flex align-items-center">
															<div><i class="bx bxs-user me-1 font-22 text-primary"></i>
															</div>
															<h6 class="mb-0 text-primary">Edit Asset</h6>
														</div>
														<hr>
														<form class="row g-3" method="post" action="<?= base_url('Asset/editAsset'); ?>">
															<input type="hidden" name="id" id="id" value="<?= $a['id']; ?>">
															<div class="col-12">
																<label for="namaaset" class="form-label">Nama Aset</label>
																<input type="text" class="form-control" id="namaaset" value="<?php echo $a['namaaset']; ?>" name="namaaset" required>
															</div>
															<div class="col-md-6">
																<label for="lokasiaset" class="form-label">Lokasi Aset</label>
																<input type="text" class="form-control" id="lokasiaset" value="<?php echo $a['lokasiaset']; ?>" name="lokasiaset" required>
															</div>
															<div class="col-md-6">
																<label for="jumlahaset" class="form-label">Jumlah Aset</label>
																<input type="number" class="form-control" id="jumlahaset" value="<?php echo $a['jumlahaset']; ?>" name="jumlahaset" required>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
																<button type="submit" class="btn btn-primary">Save changes</button>
															</div>
														</form>
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

		<!--end page wrapper -->
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		</div>