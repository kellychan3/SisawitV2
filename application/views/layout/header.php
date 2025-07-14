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
	<link href="<?= base_url('assets/'); ?>plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<link href="<?= base_url('assets/'); ?>/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />

	<!-- Bootstrap CSS -->
	<link href="<?= base_url('assets/'); ?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/app.css" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/icons.css" rel="stylesheet">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

	<title>Panel - Sisawit Dashboard</title>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!-- Top Bar -->
		<div class="topbar">
			<nav class="navbar navbar-expand">
				<div class="mobile-toggle-menu d-lg-none">
					<i class='bx bx-menu'></i>
				</div>
				<!-- Your top bar content here -->
			</nav>
		</div>

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
					<a href="<?= base_url('Dashboard'); ?>">
						<i class="fa-solid fa-square-poll-vertical"></i>
						<span class="menu-text">Dashboard</span>
					</a>
				</li>
				
				<li class="menu-label mt-0">Data Perkebunan</li>
				<li>
					<a href="<?= base_url('Aset'); ?>">
						<i class="fa-solid fa-bars"></i>
						<span class="menu-text">Data Aset Barang</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Panen'); ?>">
						<i class="fa-solid fa-bars"></i>
						<span class="menu-text">Data Panen</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Penanaman'); ?>">
						<i class="fa-solid fa-bars"></i>
						<span class="menu-text">Data Penanaman</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Pemupukan'); ?>">
						<i class="fa-solid fa-bars"></i>
						<span class="menu-text">Data Pemupukan</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Kebun'); ?>">
						<i class="fa-solid fa-bars"></i>
						<span class="menu-text">Data Kebun</span>
					</a>
				</li>
				
				<li class="menu-label mt-0">Monitoring</li>
				<li>
					<a href="<?= base_url('Log_panen'); ?>">
						<i class="fa-solid fa-list-ul"></i>
						<span class="menu-text">Log Panen</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Prediksi'); ?>">
						<i class="fa-solid fa-chart-line"></i>
						<span class="menu-text">Prediksi Hasil Panen</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Kesehatan'); ?>">
						<i class="bx bx-aperture"></i>
						<span class="menu-text">Cek Kesehatan Pohon</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Log_kesehatan'); ?>">
						<i class="fa-solid fa-list-ul"></i>
						<span class="menu-text">Log Kesehatan Pohon</span>
					</a>
				</li>
				
				<li class="menu-label mt-0">Data Pengguna</li>
				<li>
					<a href="<?= base_url('User'); ?>">
						<i class="fa-solid fa-bars"></i>
						<span class="menu-text">Daftar Pengguna</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Log_pengguna'); ?>">
						<i class="fa-solid fa-list-ul"></i>
						<span class="menu-text">Log Aktivitas Pengguna</span>
					</a>
				</li>
				
				<li>
					<a href="<?= base_url('Profile'); ?>">
						<i class="fa-solid fa-user"></i>
						<span class="menu-text">Profil</span>
					</a>
				</li>
				<li>
					<a href="<?= base_url('Authentication/logout'); ?>">
						<i class="fa-solid fa-arrow-right-from-bracket"></i>
						<span class="menu-text">Keluar</span>
					</a>
				</li>
			</ul>
		</div>

		<!--page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
				<!-- Your page content here -->
			</div>
		</div>
	</div>

	<!-- At the end of your body tag, ensure this order -->
<script src="<?= base_url('assets/'); ?>js/jquery.min.js"></script>
<script src="<?= base_url('assets/'); ?>js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets/'); ?>plugins/metismenu/js/metisMenu.min.js"></script>
<script src="<?= base_url('assets/'); ?>plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
<script src="<?= base_url('assets/'); ?>js/app.js"></script>

	<!-- Overlay for mobile sidebar -->
<div class="sidebar-overlay"></div>

</body>
</html>