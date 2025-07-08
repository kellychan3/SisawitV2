<!doctype html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!--favicon-->
	<link rel="icon" href="assets/images/favicon-32x32.png" type="image/png" />

	<!--plugins-->
	<link href="<?= base_url('assets/'); ?>/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="<?= base_url('assets/'); ?>/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="<?= base_url('assets/'); ?>/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="<?= base_url('assets/'); ?>/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="<?= base_url('assets/'); ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

	<!-- Bootstrap CSS -->
	<link href="<?= base_url('assets/'); ?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/app.css" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/icons.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	<title>Panel - Sisawit Dashboard</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
		<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="<?= base_url(); ?>assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text"><strong>SisawitV2</strong></h4>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<li>
					<ul>
						<li> <a href="<?= base_url('Dashboard'); ?>"><i class="fa-solid fa-square-poll-vertical"></i>Dashboard</a>
						</li>
					</ul>
				</li>
				<li class="menu-label mt-0">Data Perkebunan</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('Aset'); ?>"><i class="fa-solid fa-bars"></i>Data Aset Barang</a>
						</li>
						<li> <a href="<?= base_url('Panen'); ?>"><i class="fa-solid fa-bars"></i>Data Panen</a>
						</li>
						<li> <a href="<?= base_url('Penanaman'); ?>"><i class="fa-solid fa-bars"></i>Data Penanaman</a>
						</li>
						<li> <a href="<?= base_url('Pemupukan'); ?>"><i class="fa-solid fa-bars"></i>Data Pemupukan</a>
						</li>
						<li> <a href="<?= base_url('Kebun'); ?>"><i class="fa-solid fa-bars"></i>Data Kebun</a>
						</li>
					</ul>
				</li>
				<li class="menu-label mt-0">Monitoring</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('Log_panen'); ?>"><i class="fa-solid fa-list-ul"></i>Log Panen</a>
						</li>
						<li> <a href="<?= base_url('Prediksi'); ?>"><i class="fa-solid fa-chart-line"></i></i>Prediksi Hasil Panen</a>
						</li>
						<li> <a href="<?= base_url('Kesehatan'); ?>"><i class="bx bx-aperture"></i>Cek Kesehatan Pohon</a>
						</li>
						<li> <a href="<?= base_url('Log_kesehatan'); ?>"><i class="fa-solid fa-list-ul"></i>Log Kesehatan Pohon</a>
						</li>
					</ul>
				</li>
				<li class="menu-label mt-0">Data Pengguna</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('User'); ?>"><i class="fa-solid fa-bars"></i>Daftar Pengguna</a>
						</li>
						<li> <a href="<?= base_url('Log_pengguna'); ?>"><i class="fa-solid fa-list-ul"></i>Log Aktivitas Pengguna</a>
						</li>
					</ul>
				</li>
	
				<li>
					<ul>
						<li> <a href="<?= base_url('Profile'); ?>"><i class="fa-solid fa-user"></i>Profil</a>
						</li>
					</ul>
				</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('Authentication/logout'); ?>"><i class="fa-solid fa-arrow-right-from-bracket"></i>Keluar</a>
						</li>
					</ul>
				</li>
			</ul>
			<!--end navigation-->
		</div>
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu">
						<i class="bx bx-menu"></i>
					</div>
					<div class="top-menu ms-auto">
					</div>
				</nav>
			</div>
		</header>

		<script src="<?= base_url('assets/'); ?>js/jquery.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/app.js"></script>

</body>
