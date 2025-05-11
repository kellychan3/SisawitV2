<!--end header -->
<!--start page wrapper -->
<div class="page-wrapper">
	<div class="page-content">
		<div class="row">
			<div class="col-12 col-lg-3">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Jumlah Blok Sawit</p>
								<h5 class="my-4"><a href="<?= base_url('Sawit'); ?>" target="_blank"> <?= $count_kebun; ?></a></h5>
							</div>
							<div class="text-primary ms-auto font-30"><i class='lni lni-island'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-3">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Luas Kebun (HA)</p>
								<h5 class="my-4"><a href="<?= base_url('Sawit'); ?>" target="_blank"> <?= $luas_kebun; ?></a></h5>
							</div>
							<div class="text-primary ms-auto font-30"><i class='lni lni-investment'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-3">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Total Monitoring</p>
								<h5 class="my-4"><a href="<?= base_url('Produktivitas'); ?>" target="_blank"> <?= $count_total_monitoring; ?></a></h5>
								<!-- <?php foreach ($last_monitoring as $monitoring) : ?>
									<h5 class="my-4"><?= $monitoring->status ?></h5>
								<?php endforeach ?> -->
							</div>
							<div class="text-primary ms-auto font-30"><i class='bx bx-bar-chart-alt'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-3">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Monitoring Hari Ini</p>
								<h5 class="my-4"><a href="<?= base_url('Produktivitas'); ?>" target="_blank"><?= $count_today_monitoring; ?></a></h5>
								<!-- <?php foreach ($last_monitoring as $monitoring) : ?>
									<h5 class="my-4"><?= $monitoring->status ?></h5>
								<?php endforeach ?> -->
							</div>
							<div class="text-primary ms-auto font-30"><i class='bx bx-bar-chart-alt'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--end row-->
		<div class="row">
			<div class="col-12 col-lg-4">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Panen Hari Ini <?= '(Per ' . date('d/m/Y') . ')' ?></p>
								<?php if ($jumlah_panen) : ?>
									<h5 class="my-4"><a href="<?= base_url('Panen'); ?>" target="_blank"> <?= $jumlah_panen; ?> </a></h5>
								<?php else : ?>
									<h5 class="my-4"><a href="<?= base_url('Panen'); ?>" target="_blank">0 / Tidak ada panen</a></h5>
								<?php endif ?>
								<br>
							</div>
							<div class="text-primary ms-auto font-30"><i class='bx bx-add-to-queue'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-4">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body ">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Informasi Cuaca</p>
								<div class="mt-4 mb-1">
									<h6 class="my-4">Kelembapan : <?= $kelembapan; ?>% | Temperatur : <?= $temperatur; ?>°C</h6>
									Ramalan Cuaca Rokan Hulu (OpenWeatherMap)
								</div>
							</div>
							<div class="text-primary ms-auto font-30"><i class='bx bx-sun'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-4">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body ">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Total System Log</p>
								<div class="mt-4 mb-1">
									<h6 class="my-4"><a href="<?= base_url('SystemLog'); ?>"><?= $count_total_systemlog; ?></a></h6>
									<a href="<?= base_url('SystemLog'); ?>" target="_blank">Detail</a>
								</div>
							</div>
							<div class="text-primary ms-auto font-30"><i class='bx bx-key'></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-lg-12">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<div class="d-flex align-items-center">
							<div>
								<p class="mb-0 text-secondary font-14">Grafik Panen Tahunan</p>
								<div style="display: flex; justify-content: center;" class="chart-container1">
									<canvas id="panen_tahunan_chart"></canvas>
									<script>
										var panenPerTahun = <?php echo json_encode($panenPerTahun); ?>;

										// kirim masukkan data kedalam js
										var tahun_panen = [];
										var total_panen = [];
										for (var i in panenPerTahun) {
											tahun_panen.push(panenPerTahun[i].tahun_panen);
											total_panen.push(panenPerTahun[i].total_panen);
										}
									</script>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 col-lg-4">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<p class="text-secondary font-14">Grafik Luas Blok (KG)</p>
						<div class="chart-container1">
							<canvas id="luas_kebun_chart"></canvas>
							<script>
								var details = <?php echo json_encode($detail_luas_kebun); ?>;

								// kirim masukkan data kedalam js
								var nama_kebun = [];
								var luas_kebun = [];
								for (var i in details) {
									nama_kebun.push(details[i].nama);
									luas_kebun.push(details[i].luas);
								}
								console.log(nama_kebun);
							</script>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-4">
				<div class="card radius-10 overflow-hidden">
					<div class="card-body">
						<p id="monitoring_title" class="text-secondary font-14">Grafik Monitoring</p>
						<h6 id="monitoring_not_found" class="my-4">Klik Grafik Kebun</h6>
						<div id="monitoring_chart_container" class="chart-container1">
							<canvas id="monitoring_kebun_chart"></canvas>
							<script>
								var dataPerPohon = <?php echo json_encode($dataPerPohon); ?>;
								console.log(dataPerPohon);
								var dataKebun = {};
								for (let index = 0; index < dataPerPohon.length; index++) {
									const element = dataPerPohon[index];
									console.log(element);
									if (dataKebun.hasOwnProperty(element.kebun)) {
										dataKebun[element.kebun].buah_tandan += parseFloat(element.buah_tandan);
										dataKebun[element.kebun].buah_tandan_mentah += parseFloat(element.buah_tandan_mentah);
										dataKebun[element.kebun].buah_tandan_matang += parseFloat(element.buah_tandan_matang);
										dataKebun[element.kebun].buah_tandan_segera_matang += parseFloat(element.buah_tandan_segera_matang);
									} else {
										dataKebun[element.kebun] = {
											buah_tandan: parseFloat(element.buah_tandan),
											buah_tandan_mentah: parseFloat(element.buah_tandan_mentah),
											buah_tandan_matang: parseFloat(element.buah_tandan_matang),
											buah_tandan_segera_matang: parseFloat(element.buah_tandan_segera_matang),
										};
									}
								}
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end wrapper-->