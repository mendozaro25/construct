<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Meta data -->
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>

	<!-- Title -->
	<title>SYSC &mdash; Sistema de Constructoría </title>

	<!--Favicon -->
	<link rel="icon" href="<?= base_url() ?>assets/images/brand/gerserv-favicon.ico" type="image/x-icon"/>

	<!--Bootstrap css -->
	<link href="<?= base_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

	<!-- Style css -->
	<link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/css/dark.css" rel="stylesheet" />
	<link href="<?= base_url() ?>assets/css/skin-modes.css" rel="stylesheet" />

	<!-- Animate css -->
	<link href="<?= base_url() ?>assets/css/animated.css" rel="stylesheet" />

	<!---Icons css-->
	<link href="<?= base_url() ?>assets/css/icons.css" rel="stylesheet" />

	<!-- Color Skin css -->
	<link id="theme" href="<?= base_url() ?>assets/colors/color1.css" rel="stylesheet" type="text/css"/>
</head>

<body class="h-100vh bg-primary">
		<div class="page">
			<a class="hiddenanchor" id="signup"></a>
			<a class="hiddenanchor" id="signin"></a>
			<div class="page-single" style="background-image: url('<?=base_url()?>/assets/images/background_auth.png');background-repeat: no-repeat;background-size: cover;background-position: center;">
				<div class="container">
					<div class="row">
						<div class="col mx-auto">
							<div class="row justify-content-center">
								<div class="col-md-5">
									<div class="card">
										<div class="card-body">
											<form autocomplete="off" method="post">
												<input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>" >
												<div class="text-center title-style mb-6">
													<h1 class="mb-2">Iniciar Sesión</h1>
												</div>
												<br>
												<?= isset($failed) && !empty($failed) ? "<p class='alert alert-danger' role='alert'>{$failed}</p>" : ""; ?> <?= $this->session->flashdata('success'); ?>
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-user"></i>
														</div>
													</div>
													<?= form_error('username', '<div class="err">', '</div>'); ?>
													<input type="text" class="form-control" placeholder="Usuario" required="" value="<?= set_value('username'); ?>" name="username" autofocus>
												</div>
												<div class="input-group mb-4">
													<div class="input-group-prepend">
														<div class="input-group-text">
															<i class="fe fe-lock"></i>
														</div>
													</div>
													<?= form_error('password', '<div class="err">', '</div>'); ?>
													<input type="password" class="form-control" placeholder="Clave" required="" value="<?= set_value('password'); ?>" name="password">
												</div>
												<div class="row">
													<div class="col-12">
														<button type="submit" class="btn  btn-primary btn-block px-4 submit">Ingresar</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Jquery js-->
		<script src="<?= base_url() ?>assets/js/jquery-3.5.1.min.js"></script>

		<!-- Bootstrap4 js-->
		<script src="<?= base_url() ?>assets/plugins/bootstrap/popper.min.js"></script>
		<script src="<?= base_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>

		<!--Othercharts js-->
		<script src="<?= base_url() ?>assets/plugins/othercharts/jquery.sparkline.min.js"></script>

		<!-- Circle-progress js-->
		<script src="<?= base_url() ?>assets/js/circle-progress.min.js"></script>

		<!-- Jquery-rating js-->
		<script src="<?= base_url() ?>assets/plugins/rating/jquery.rating-stars.js"></script>

		<!-- Custom js-->
		<script src="<?= base_url() ?>assets/js/custom.js"></script>
	</body>
</html>
