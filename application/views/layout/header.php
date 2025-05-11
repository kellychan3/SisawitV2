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
	<!-- loader-->
	<link href="<?= base_url('assets/'); ?>/css/pace.min.css" rel="stylesheet" />
	<script src="<?= base_url('assets/'); ?>/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="<?= base_url('assets/'); ?>/css/bootstrap.min.css" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/bootstrap-extended.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/app.css" rel="stylesheet">
	<link href="<?= base_url('assets/'); ?>/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="<?= base_url('assets/'); ?>css/dark-theme.css" />
	<link rel="stylesheet" href="<?= base_url('assets/'); ?>css/semi-dark.css" />
	<link rel="stylesheet" href="<?= base_url('assets/'); ?>css/header-colors.css" />
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
					<h4 class="logo-text">Sisawit</h4>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<!-- <li>
					<a href="<?= base_url('Dashboard'); ?>">
						<div class="parent-icon"><i class='bx bx-cookie'></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</a>
				</li> -->
				<li>
					<ul>
						<li> <a href="<?= base_url('Dashboard'); ?>"><i class="bx bx-world"></i>Dashboard</a>
						</li>
					</ul>
				</li>
				<li class="menu-label mt-0">Data</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('Asset'); ?>"><i class="bx bx-trim"></i>Aset Barang</a>
						</li>
						<li> <a href="<?= base_url('Panen'); ?>"><i class="bx bx-cookie"></i>Panen</a>
						</li>
					</ul>
				</li>
				<li class="menu-label mt-0">Monitoring</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('Produktivitas'); ?>"><i class="bx bx-sun"></i>Produktivitas</a>
						</li>
						<li> <a href="<?= base_url('Forecast'); ?>"><i class="bx bx-aperture"></i>Forecast</a>
						</li>
					</ul>
				</li>
				<li class="menu-label mt-0">Master Data</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('Sawit'); ?>"><i class="bx bx-coin-stack"></i>Blok Sawit</a>
						</li>
						<li> <a href="<?= base_url('SystemLog'); ?>"><i class="bx bx-world"></i>System Log</a>
						</li>
						<li> <a href="<?= base_url('User'); ?>"><i class="bx bx-user"></i>User List</a>
						</li>
					</ul>
				</li>
				<li class="menu-label mt-0">User</li>
				<li>
					<ul>
						<li> <a href="<?= base_url('Profile'); ?>"><i class="bx bx-happy"></i>Profile</a>
						</li>
						<li> <a href="<?= base_url('Authentication/logout'); ?>"><i class="bx bx-log-out"></i>Logout</a>
						</li>
					</ul>
				</li>
			</ul>
			<!--end navigation-->
		</div>
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					<div class="top-menu ms-auto">
					</div>
				</nav>
			</div>
		</header>
	