<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath']. '/core/init.php';

# Limbo
include($_SESSION['ProjectPath']."/includes/limbo.php");

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

# Controller calls
include($_SESSION['ProjectPath']."/includes/controller-calls.php");

# Token
$csrfToken = Token::generate();

?>

<!DOCTYPE html>
<html>
<head>	
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title></title>
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/nanoscroller.css" />
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/compiled/theme_styles.css" />
	<!-- this page specific styles -->
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-default.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-growl.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-bar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-attached.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-other.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-theme.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/select2.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/compiled/wizard.css">
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/footable.core.css" />
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/bootstrap-timepicker.css" />
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/html5shiv.js"></script>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
	<![endif]-->
</head>
<body class="<?= $_IA_skin_class[$config_skin] ?>">
	<div id="theme-wrapper">
		<?php include($_SESSION['ProjectPath']."/includes/header.php") ?>
		<div id="page-wrapper" class="container<?= $config_nav == 1 ? ' nav-small' : '' ?>">
			<div class="row">
				<?php include($_SESSION['ProjectPath']."/includes/left-panel.php") ?>
				<div id="content-wrapper">
					<div class="row">
						<div class="col-lg-12">
					    <ol class="breadcrumb">
								<li><a href="/0/"><?= $_QC_language[23] ?></a></li>
								<?php if ($_GET['id']) { ?>
									<li class="active"><a href="tractors-trailers-equipment">Tractors, trailers &amp; equipment</a></li> <?php
								} ?>
							</ol>
							<div class="clearfix">
								
								<?php if (!$_GET) { ?>
									<h1 class="pull-left">Tractors, trailers &amp; equipment</h1> <?php
								} elseif ($_GET['id']) { ?>
									<h1 class="pull-left">User: <?= $_QU_e_name[$client_driver_user_id[$_GET['id']]] . ' ' . $_QU_e_last_name[$client_driver_user_id[$_GET['id']]] ?></h1> <?php
								} ?>

								<div class="pull-right top-page-ui">
									<?php include($_SESSION['ProjectPath']."/includes/module-refresh-link.php"); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?php 

							###########################################
							# Tractors, trailers &amp; equipment List #
							###########################################

							if (!$_GET) { ?>

								<div class="main-box no-header">
									<div class="main-box-body clearfix">
										<div class="filter-block pull-right">
											<div class="form-group pull-left">
												<input type="text" id="filter" class="form-control" placeholder="Search...">
												<i class="fa fa-search search-icon"></i>
											</div>
										</div>
										<?php 
											# REMOVE IP ADDRESS CHECK CONDITIONAL WHEN DONE
											if ($_SERVER['REMOTE_ADDR'] == '190.242.136.34') {
												echo date('D M d, Y G:i a');
											}
										?>
										<table class="table footable toggle-circle-filled" data-page-size="10" data-filter="#filter" data-filter-text-only="true">
											<thead>
												<tr>
													<th>User</th>
													<th>Company</th>
												</tr>
											</thead>
											<tbody>
												<?php for ($i=1; $i <= $user_e_profile_client_driver_count ; $i++) { ?>
													<tr>
														<td><a href="tractors-trailers-equipment?id=<?= $client_driver_user_id_ctr[$i] ?>"><?= $_QU_e_name[$client_driver_user_id_ctr[$i]] . ' ' . $_QU_e_last_name[$client_driver_user_id_ctr[$i]] ?></a></td>
														<td><?= $limbo_client_company_name_by_data_id[$client_driver_client_id_ctr[$i]] ?></td>
													</tr> <?php
												} ?>
											</tbody>
										</table>
										<ul class="pagination pull-right hide-if-no-paging"></ul>
									</div>
								</div> <?php								
							} 

							#################################################
							# Tractors, trailers &amp; equipment by user id #
							#################################################

							elseif ($_GET['id']) { ?>
								<div class="col-sm-12 col-md-12">
									<div id="tractor_info" class="panel panel-<?= $loader_tractor_count ? 'primary' : 'danger' ?>">
										<div class="panel-heading">Tractor info <?= !$loader_tractor_count ? ' - This driver has no tractor assigned!' : '' ?><span class="pull-right label label-danger"><small>* Required fields</small></span></div>
										<div class="panel-body">
											<form action="" method="post">
												<div class="col-sm-12 col-md-3 form-group">
													<label><span class="red">*</span> Tractor Number</label>
													<input name="number" class="form-control" type="text" value="<?= $tractor_number ?>">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label><span class="red">*</span> Tractor Color</label>
													<input name="color" class="form-control" type="text" value="<?= $tractor_color ?>">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label><span class="red">*</span> Headrack</label>
													<select name="headrack" class="form-control">
														<option></option>
														<option value="on"<?= $tractor_headrack == 1 ? 'selected="selected"' : '' ?>>Yes</option>
														<option value="off"<?= $tractor_headrack == 1 ? '' : 'selected="selected"' ?>>No</option>
													</select>
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label><span class="red">*</span> Year</label>
													<input name="year" class="form-control" type="number" value="<?= $tractor_year ?>" placeholder="i.e: <?= date('Y') ?>">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label><span class="red">*</span> Make</label>
													<input name="make" class="form-control" type="text" value="<?= $tractor_make ?>">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label><span class="red">*</span> Model</label>
													<input name="model" class="form-control" type="text" value="<?= $tractor_model ?>">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label><span class="red">*</span> Trailer type</label>
													<select name="trailer_type" class="form-control">
														<option></option>
														<?php for ($i = 1; $i <= $loader_trailer_types_count ; $i++) { ?>
															<option value="<?= $trailer_types_data_id[$i] ?>"<?= $tractor_trailer_type == $trailer_types_data_id[$i] ? 'selected="selected"' : '' ?>><?= $trailer_types_name[$i] ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label>Vin Number</label>
													<input name="vin" class="form-control" type="text" value="<?= $tractor_vin ?>">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<label>License plate</label>
													<input name="license_plate" class="form-control" type="text" value="<?= $tractor_license_plate ?>">
												</div>
												<div class="col-sm-12 col-md-12 form-group text-right">
													<button class="btn btn-primary" type="submit"><?= $user_e_language_driver_profile[6] ?></button>
													<a href="user-e?id=<?= $_GET['id'] ?>&_hp_delete_loader_tractor=<?= $tractor_data_id ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a>
												</div>
												<input type="hidden" name="data_id" value="<?= $tractor_data_id ?>">
												<input type="hidden" name="owner_user_id" value="<?= $_GET['id'] ?>">
												<input type="hidden" name="driver_profile_id_alt" value="<?= $driver_profile_id ?>">
												<!-- Send current trailer_type to check if this value is being changed, if it changes and there is a trailer assigned to this tractor, this trailer goes to status 0 (inactive) -->
												<input type="hidden" name="current_tractor_trailer_type" value="<?= $tractor_trailer_type ?>">
												<!-- Save $loader_trailer_count in hidden field -->
												<input type="hidden" name="loader_trailer_count" value="<?= $loader_trailer_count ?>">
												<!-- Save $tractor_id in hidden field -->
												<input type="hidden" name="tractor_id" value="<?= $tractor_data_id ?>">
												<input type="hidden" name="_hp_update_loader_tractor" value="1">
												<input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
										</div>
									</div>
								</div>
								<?php # Display trailer info if $loader_tractor_count
								# $loader_tractor_count is declared in user-e.txt
								if ($loader_tractor_count) { ?>
									<div class="col-sm-12 col-md-12">
										<div id="trailer_info" class="panel panel-<?= $loader_trailer_count ? 'primary' : 'danger' ?>">
											<div class="panel-heading">Trailer info <?= !$loader_trailer_count ? ' - This tractor has no trailer information!' : '' ?><span class="pull-right label label-danger"><small>* Required fields</small></span></div>
											<div class="panel-body">
												<form action="" method="post">
													<div class="col-sm-12 col-md-3 form-group">
														<label><span class="red">*</span> Lenght</label>
														<?php if ($tractor_trailer_type != 5) {
															# If !5, display select element ?>
															<select name="length" class="form-control">
																<option></option>
																<option value="48"<?= $trailer_length == 48 ? 'selected="selected"' : '' ?>>48</option>
																<option value="53"<?= $trailer_length == 53 ? 'selected="selected"' : '' ?>>53</option>
															</select> <?php
														} else {
															# Else display open number input ?>
															<input name="length" type="number" class="form-control" value="<?= $trailer_length ?>"> <?php
														} ?>
													</div>
													<?php # Display height only for trailer type V && RF
													if ($tractor_trailer_type == 3 || $tractor_trailer_type == 4) { ?>
													 	<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Height</label>
															<input name="height" class="form-control" type="text" value="<?= $trailer_height ?>">
														</div> <?php
													} ?>
													<div class="col-sm-12 col-md-3 form-group">
														<label><span class="red">*</span> Width</label>
														<input name="width" class="form-control" type="text" value="<?= $trailer_width ?>">
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<label><span class="red">*</span> Trailer number</label>
														<input name="trailer_number" class="form-control" type="text" value="<?= $trailer_number ?>">
													</div>
													<?php # Display deck_material && headrack only for trailer type FB && SD
													if ($tractor_trailer_type == 1 || $tractor_trailer_type == 2) { ?>
														<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Deck material</label>
															<select name="deck_material" class="form-control">
																<option></option>
																<?php for ($i = 1; $i <= $loader_trailer_deck_material_count ; $i++) { ?>
																	<option value="<?= $trailer_deck_material_data_id[$i] ?>"<?= $trailer_deck_material == $trailer_deck_material_data_id[$i] ? 'selected' : '' ?>><?= $trailer_deck_material_name[$i] ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Headrack</label>
															<select name="headrack" class="form-control">
																<option></option>
																<option value="on"<?= $trailer_headrack == 1 ? 'selected="selected"' : '' ?>>Yes</option>
																<option value="off"<?= $trailer_headrack == 1 ? '' : 'selected="selected"' ?>>No</option>
															</select>
														</div> <?php 
													} ?>
													<div class="col-sm-12 col-md-3 form-group">
														<label>Air ride</label>
														<select name="air_ride" class="form-control">
															<option></option>
															<option value="on"<?= $trailer_air_ride == 1 ? 'selected="selected"' : '' ?>>Yes</option>
															<option value="off"<?= $trailer_air_ride == 1 ? '' : 'selected="selected"' ?>>No</option>
														</select>
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<label>Gross weight</label>
														<input name="gross_weight" class="form-control" type="text" value="<?= $trailer_gross_weight ?>">
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<label>Vin Number</label>
														<input name="vin" class="form-control" type="text" value="<?= $trailer_vin ?>">
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<label>Trailer license plate</label>
														<input name="license_plate" class="form-control" type="text" value="<?= $trailer_license_plate ?>">
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<label><span class="red">*</span> Year</label>
														<input name="trailer_year" class="form-control" type="number" value="<?= $trailer_year ?>" placeholder="i.e: <?= date('Y') ?>">
													</div>
													<?php # Display door_type && roof_type only for trailer type V && RF
													if ($tractor_trailer_type == 3 || $tractor_trailer_type == 4) { ?>
														<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Door type</label>
															<select name="door_type" class="form-control">
																<option></option>
																<?php for ($i = 1; $i <= $loader_trailer_door_type_count ; $i++) { ?>
																	<option value="<?= $trailer_door_type_data_id[$i] ?>"<?= $trailer_door_type_data_id[$i] == $trailer_door_type ? 'selected="selected"' : '' ?>><?= $trailer_door_type_name[$i] ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Roof type</label>
															<select name="roof_type" class="form-control">
																<option></option>
																<?php for ($i = 1; $i <= $loader_trailer_roof_type_count ; $i++) { ?>
																	<option value="<?= $trailer_roof_type_data_id[$i] ?>"<?= $trailer_roof_type_data_id[$i] == $trailer_roof_type ? 'selected="selected"' : '' ?>><?= $trailer_roof_type_name[$i] ?></option> <?php
																} ?>
															</select>
														</div> <?php 
													}

													# Display bottom_deck && upper_deck only for trailer type SD
													if ($tractor_trailer_type == 2) { ?>
														<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Bottom deck</label>
															<input name="bottom_deck" class="form-control" type="number" min="1" value="<?= $trailer_bottom_deck ?>">
														</div>
														<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Upper deck</label>
															<input name="upper_deck" class="form-control" type="number" min="1" value="<?= $trailer_upper_deck ?>">
														</div> <?php
													}

													# Display goose_neck only for trailer type HS
													if ($tractor_trailer_type == 5) { ?>
														<div class="col-sm-12 col-md-3 form-group">
															<label><span class="red">*</span> Goose neck</label>
															<select name="goose_neck" class="form-control">
																<option></option>
																<option value="1"<?= $loader_trailer_count && $trailer_goose_neck == 1 ? 'selected' : '' ?>>Yes</option>
																<option value="0"<?= $loader_trailer_count && $trailer_goose_neck == 0 ? 'selected' : '' ?>>No</option>
															</select>
														</div> <?php 
													} ?>
													<div class="col-sm-12 col-md-12 form-group text-right">
														<button class="btn btn-primary" type="submit"><?= $user_e_language_driver_profile[6] ?></button>
														<?php # Display delete button if trailer data is available
														if ($loader_trailer_count) { ?>
														 	<a href="user-e?id=<?= $_GET['id'] ?>&_hp_delete_loader_trailer=<?= $trailer_data_id ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a> <?php
														} ?>
													</div>
													<input type="hidden" name="tractor_id" value="<?= $tractor_data_id ?>">
													<input type="hidden" name="tractor_trailer_type" value="<?= $tractor_trailer_type ?>">
													<input type="hidden" name="_hp_update_loader_trailer" value="1">
													<?php # Display data_id if trailer data is available
													if ($loader_trailer_count) { ?>
													 	<input type="hidden" name="trailer_data_id" value="<?= $trailer_data_id ?>"> <?php
													} ?>
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form>
											</div>
										</div>
									</div> <?php
								} 
							} ?>
							<div class="col-sm-12 col-md-6">
								<div id="driver_equipment" class="panel panel-primary">
									<div class="panel-heading">Driver Equipment</div>
									<div class="panel-body">
										<?php if ($loader_driver_equipment_assoc_count) {
											# Display equipment associations
											for ($i = 1; $i <= $loader_driver_equipment_assoc_count ; $i++) { ?>
												<div class="col-sm-12 col-md-12">
													<form action="" method="post">
														<div class="col-sm-12 col-md-4 form-group">
															<select name="equipment_id" class="form-control" readonly>
																<option value="">Type of equipment</option>
																<?php for ($x = 1; $x <= $loader_driver_equipment_count ; $x++) { ?>
																	<option value="<?= $driver_equipment_data_id[$x] ?>"<?= $driver_equipment_data_id[$x] == $driver_equipment_assoc_equipment_id[$i] ? ' selected="selected"' : '' ?>><?= $driver_equipment_name[$x] ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-4 form-group">
															<input name="quantity" type="number" min="1" class="form-control" placeholder="Quantity" value="<?= $driver_equipment_assoc_quantity[$i] ?>">
														</div>
														<div class="col-sm-12 col-md-4 form-group">
															<button type="submit" class="btn btn-primary">Update</button>
															<a class="btn btn-danger" href="tractors-trailers-equipment?id=<?= $_GET['id'] ?>&_hp_delete_loader_driver_equipment_assoc=<?= $driver_equipment_assoc_data_id[$i] ?>"><span class="fa fa-trash-o"></span></a>
														</div>
														<input type="hidden" name="data_id" value="<?= $driver_equipment_assoc_data_id[$i] ?>">
														<input type="hidden" name="_hp_update_loader_driver_equipment_assoc" value="1">
														<input type="hidden" name="token" value="<?= $csrfToken ?>">
													</form>
												</div> <?php
											}
										} else {
											# Display warning ?>
											<div class="alert alert-warning">
												<i class="fa fa-warning fa-fw fa-lg"></i>
												This driver doesn't have any equipment assigned.
											</div> <?php
										} ?>
										<div class="col-sm-12 col-sm-12">
											<?= $loader_driver_equipment_assoc_count ? '<hr />' : '' ?>
											<form action="" method="post">
												<div class="col-sm-12 col-md-4 form-group">
													<select name="equipment_id" class="form-control">
														<option value="">Type of equipment</option>
														<?php for ($i = 1; $i <= $loader_driver_equipment_count ; $i++) { ?>
															<option value="<?= $driver_equipment_data_id[$i] ?>"><?= $driver_equipment_name[$i] ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="col-sm-12 col-md-4 form-group">
													<input name="quantity" type="number" min="1" class="form-control" placeholder="Quantity">
												</div>
												<div class="col-sm-12 col-md-4 form-group">
													<button type="submit" class="btn btn-primary">Add equipment</button>
												</div>
												<input type="hidden" name="_hp_add_loader_driver_equipment_assoc" value="1">
												<input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
										</div>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-6">
								<div id="driver_features" class="panel panel-primary">
									<div class="panel-heading">Driver Features</div>
									<div class="panel-body">
										<?php if ($loader_driver_features_assoc_count) {
											# Display features associations
											for ($i = 1; $i <= $loader_driver_features_assoc_count ; $i++) { ?>
												<div class="form-group col-sm-12 col-md-10">
													<select class="form-control" readonly>
														<?php for ($x = 1; $x <= $loader_driver_features_count ; $x++) { ?>
															<option value="<?= $driver_features_data_id[$x] ?>"<?= $driver_features_data_id[$x] == $driver_feature_feature_id[$i] ? ' selected="selected"' : '' ?>><?= $driver_features_name[$x] ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="form-group col-sm-12 col-md-2">
													<a href="tractors-trailers-equipment?id=<?= $_GET['id'] ?>&_hp_delete_loader_driver_features_assoc=<?= $driver_feature_data_id[$i] ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a>
												</div> <?php
											}
										} else {
											# Display warning ?>
											<div class="alert alert-warning">
												<i class="fa fa-warning fa-fw fa-lg"></i>
												This driver doesn't have any features assigned.
											</div> <?php
										} ?>
										<form action="" method="post">
											<div class="form-group col-sm-12 col-md-8">
												<select name="feature_id" class="form-control">
													<option value=""></option>
													<?php for ($i = 1; $i <= $loader_driver_features_count ; $i++) { ?>
														<option value="<?= $driver_features_data_id[$i] ?>"><?= $driver_features_name[$i] ?></option> <?php
													} ?>
												</select>
											</div>
											<div class="form-group col-sm-12 col-md-4">
												<button type="submit" class="btn btn-primary">Add feature</button>
											</div>
											<input type="hidden" name="_hp_add_loader_driver_features_assoc" value="1">
											<input type="hidden" name="token" value="<?= $csrfToken ?>">	
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php include($_SESSION['ProjectPath']."/includes/footer.php"); ?>
				</div>
			</div>
		</div>
	</div>
	<!-- global scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.nanoscroller.min.js"></script>
	<!-- this page specific scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/ckeditor/ckeditor.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/modernizr.custom.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/snap.svg-min.js"></script> <!-- For Corner Expand and Loading circle effect only -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/classie.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/notificationFx.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/select2.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/wizard.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/bootstrap-datepicker.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/bootstrap-timepicker.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.maskedinput.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.sort.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.paginate.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.filter.js"></script>
	<!-- theme scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>/js/scripts.js"></script>
	<script type="text/javascript">
		(function() {
			<?php // Notices
			$_QC_module_controller = DB::getInstance()->query("SELECT * FROM _QC_module_controller");
	    foreach ($_QC_module_controller->results() as $_QC_module_controller_data) {
				if (Session::exists($_QC_module_controller_data->controller)) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash($_QC_module_controller_data->controller) ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
				elseif (Session::exists($_QC_module_controller_data->controller.'_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash($_QC_module_controller_data->controller.'_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			}  ?>

			$('.footable').footable();
		})();
	</script>
</body>
</html>
