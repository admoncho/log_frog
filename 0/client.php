<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath']. '/core/init.php';

# Limbo
include($_SESSION['ProjectPath']."/includes/limbo.php");

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

# Declare schedule directory
$schedule_directory = '/home/' . $rootFolder . '/public_html/files/schedule/';

# schedule_all data
require($_SESSION['ProjectPath']."/includes/db_data/schedule_all.php");

# factoring_company data
$_GET['factoring_company_id'] ? require($_SESSION['ProjectPath']."/includes/db_data/factoring_company.php") : '';

# Controller calls
include($_SESSION['ProjectPath']."/includes/controller-calls.php");

// Save token
$csrfToken = Token::generate(); ?>
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
								<li><a href="<?= $_SESSION['href_location'] ?>0/"><?= $_QC_language[23] ?></a></li>
								<?php if ($_GET['new']) { ?>
									<li><a href="<?= $_SESSION['href_location'] ?>0/broker">Client</a></li>
									<li class="active"><a href="<?= $_SESSION['href_location'] ?>0/">Add</a></li> <?php
								} elseif ($_GET['id']) { ?>
									<li class="active"><a href="<?= $_SESSION['href_location'] ?>0/client">Client</a></li> <?php
								} ?>
							</ol>
							<div class="clearfix">
								
								<?php if (!$_GET) { ?>
									<h1 class="pull-left">Clients</h1> <?php
								} elseif ($_GET['new']) { ?>
									<h1 class="pull-left">Client</h1> <?php
								} elseif ($_GET['id']) { ?>
									<h1 class="pull-left"><?= $limbo_client_company_name_by_data_id[$_GET['id']] ?></h1> <?php
								} ?>

								<div class="pull-right top-page-ui">
									<?php 
									if (!$_GET['new']) {
										include($_SESSION['ProjectPath']."/includes/module-new-item-link.php");	
									} else { ?>
										<a style="margin:0 5px;" href="broker" class="btn btn-danger"><i class="fa fa-sign-out"></i> Cancel</a> <?php
									}
									include($_SESSION['ProjectPath']."/includes/module-refresh-link.php"); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?php 

							###############
							# Client List #
							###############

							if (!$_GET) { ?>

								<div class="main-box no-header">
									<div class="main-box-body clearfix">
										<?php if (!$limbo_user_e_profile_client_count) { ?>
											<div class="alert alert-warning" role="alert">
									      There are no clients yet, <a href="client?new=1">add the first one</a>.
									    </div> <?php
										} else { ?>
											<div class="filter-block pull-right">
												<div class="form-group pull-left">
													<input type="text" id="filter" class="form-control" placeholder="Search...">
													<i class="fa fa-search search-icon"></i>
												</div>
											</div>
											<table class="table footable toggle-circle-filled" data-page-size="10" data-filter="#filter" data-filter-text-only="true">
												<thead>
													<tr>
														<th>Company name</th>
														<th>MC#</th>
														<th>EIN</th>
														<th data-hide="all" class="text-right">US DOT</th>
														<th data-hide="all" class="text-right">Phone number 1</th>
														<th data-hide="all" class="text-right">Phone number 2</th>
														<th data-hide="all" class="text-right">Address line 1</th>
														<th data-hide="all" class="text-right">Address line 2</th>
														<th data-hide="all" class="text-right">City, State zipcode</th>
														<th data-hide="all" class="text-right">Mailing address line 1</th>
														<th data-hide="all" class="text-right">Mailing address line 2</th>
														<th data-hide="all" class="text-right">Mailing City, State zipcode</th>
														<th data-hide="all" class="text-right">Status</th>
														<th data-hide="all" class="text-right">Created</th>
													</tr>
												</thead>
												<tbody>
													<?php for ($i=1; $i <= $limbo_user_e_profile_client_count ; $i++) { ?>
														<tr>
															<td><a href="client?id=<?= $limbo_client_data_id[$i] ?>"><?= $limbo_client_company_name[$i] ?></a></td>
															<td><?= $limbo_client_mc_number[$i] ?></td>
															<td><?= $limbo_client_ein_number[$i] ?></td>
															<td><?= $limbo_client_us_dot_number[$i] ?></td>
															<td><?= $limbo_client_phone_number_01[$i] ?></td>
															<td><?= $limbo_client_phone_number_02[$i] ?></td>
															<td><?= $limbo_client_address_line_1[$i] ?></td>
															<td><?= $limbo_client_address_line_2[$i] ?></td>
															<td><?= $limbo_client_city[$i] . ', ' . $state_name[$limbo_client_state_id[$i]] . ' ' . $limbo_client_zip_code[$i] ?></td>
															<td><?= $limbo_client_billing_address_line_1[$i] ?></td>
															<td><?= $limbo_client_billing_address_line_2[$i] ?></td>
															<td><?= $limbo_client_billing_city[$i] . ', ' . $state_name[$limbo_client_billing_state_id[$i]] . ' ' . $limbo_client_billing_zip_code[$i] ?></td>
															<td><?= $limbo_client_status[$i] == 0 ? 'Inactive' : 'Active' ?></td>
															<td><?= date('M, Y', strtotime($limbo_client_added[$i])) ?></td>
														</tr> <?php
													} ?>
												</tbody>
											</table>
											<ul class="pagination pull-right hide-if-no-paging"></ul> <?php
										} ?>
									</div>
								</div> <?php								
							}

							##############
							# Add client #
							##############

							elseif ($_GET['new']) { ?>
								<div class="main-box">
									<header class="main-box-header clearfix">
										<h2>Add new client</h2>
									</header>
									<div class="main-box-body clearfix">
										<form action="" method="post" role="form">
											<div class="form-group col-sm-12 col-md-12" id="company_name_holder">
												<label class="control-label" for="company_name"><?= $user_e_language_client_profile[25] ?></label>
												<input name="company_name" type="text" class="form-control" id="company_name">
												<span class="help-block"><?= $user_e_language_client_profile[18] ?></span>
											</div>
											<div class="form-group col-sm-4 col-md-4" id="mc_number_holder">
												<label class="control-label" for="mc_number">MC Number</label>
												<input name="mc_number" type="text" class="form-control" id="mc_number">
												<span class="help-block"><?= $user_e_language_client_profile[19] ?></span>
											</div>
											<div class="form-group col-sm-4 col-md-4" id="us_dot_number_holder">
												<label class="control-label" for="us_dot_number">US DOT Number</label>
												<input name="us_dot_number" type="text" class="form-control" id="us_dot_number">
												<span class="help-block"><?= $user_e_language_client_profile[20] ?></span>
											</div>
											<div class="form-group col-sm-4 col-md-4" id="ein_number_holder">
												<label class="control-label" for="ein_number">EIN Number</label>
												<input name="ein_number" type="text" class="form-control" id="ein_number">
												<span class="help-block"><?= $user_e_language_client_profile[21] ?></span>
											</div>
											<div class="form-group">
												<button type="submit" class="btn btn-primary btn-block"><?= $user_e_language_client_profile[22] ?></button>
											</div>
											<input type="hidden" name="user_id" value="<?= $user_e_data->user_id ?>">
											<input type="hidden" name="_hp_add_loader_client" value="1">
											<input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div> <?php
							} 

							#############
							# Client ID #
							#############

							elseif ($_GET['id']) { ?>
								<div class="main-box">
									<header class="main-box-header clearfix">
										<small class="pull-right">Added by <?= $user_i_name[$broker_co_user_id_did[$_GET['id']]] . ' ' . $user_i_last_name[$broker_co_user_id_did[$_GET['id']]] ?> on <?= date('M Y', strtotime($broker_co_added_did[$_GET['id']])) ?>.</small>
									</header>
									<div class="main-box-body clearfix">
										<form action="" method="post">
											<div class="col-sm-12 col-md-7">
												<div class="form-group">
													<label class="control-label" for="company_name">Company Name</label>
													<input name="company_name" type="text" class="form-control" id="company_name" value="<?= $limbo_client_company_name_by_data_id[$_GET['id']] ?>">
												</div>
												<div class="form-group col-sm-12 col-md-4" style="padding-left:0;">
													<label class="control-label" for="mc_number">MC Number</label>
													<input name="mc_number" type="text" class="form-control text-center" id="mc_number" value="<?= $limbo_client_mc_number_by_data_id[$_GET['id']] ?>">
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label class="control-label" for="us_dot_number">US DOT Number</label>
													<input name="us_dot_number" type="text" class="form-control text-center" id="us_dot_number" value="<?= $limbo_client_us_dot_number_by_data_id[$_GET['id']] ?>">
												</div>
												<div class="form-group col-sm-12 col-md-4" style="padding-right:0;">
													<label class="control-label" for="ein_number">EIN Number</label>
													<input name="ein_number" type="text" class="form-control text-center" id="ein_number" value="<?= $limbo_client_ein_number_by_data_id[$_GET['id']] ?>">
												</div>
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-mobile-phone"></i></span>
														<input name="phone_number_01" type="text" class="form-control" id="phone_number_01" value="<?= $limbo_client_phone_number_01_by_data_id[$_GET['id']] ?>">
													</div>
												</div>
												<div class="form-group">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-mobile-phone"></i></span>
														<input name="phone_number_02" type="text" class="form-control" id="phone_number_02" value="<?= $limbo_client_phone_number_02_by_data_id[$_GET['id']] ?>">
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-sm-12 col-md-6">
															<label class="control-label">Rate</label>
															<select name="rate_id" class="form-control">
																<?= $limbo_client_rate_id_did[$_GET['id']] == 0 ? '<option></option>' : '' ?>
																<?php for ($i=1; $i <= $company_rate_count ; $i++) { ?>
																	<option value="<?= $company_rate_data_id[$i] ?>"<?= $limbo_client_rate_id_did[$_GET['id']] == $company_rate_data_id[$i] ? ' selected' : '' ?>><?= $company_rate_processing_fee[$i] != NULL ? '$ ' . (number_format($company_rate_rate[$i] + ($company_rate_rate[$i] / 100 * $company_rate_processing_fee[$i]), 2)) . ' - ' . $company_rate_title[$i] . ' + processing fee' : '$ ' . $company_rate_rate[$i] . ' - ' . $company_rate_title[$i] ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-6">
															<label class="control-label" for="ein_number">Invoice color</label>
															<input name="invoice_color" type="text" class="form-control text-center" value="<?= $limbo_client_invoice_color_by_data_id[$_GET['id']] ?>"<?= strlen($limbo_client_invoice_color_by_data_id[$_GET['id']]) == 6 ? 'style="background-color: #' . $limbo_client_invoice_color_by_data_id[$_GET['id']] . '; color: #fff"' : '' ?>>
														</div>
													</div>
												</div>
												<div class="form-group col-sm-12 col-md-12">
													<label><?= $user_e_language_client_profile[9] ?></label>
													<div class="radio">
														<input type="radio" name="status" id="status1" value="1"<?= $limbo_client_status_by_data_id[$_GET['id']] == 1 ? ' checked=""' : '' ; ?><?= !$owner_driver_activate ? 'disabled' : '' ?>>
														<label for="status1">
															<?= !$owner_driver_activate ? '<span style="text-decoration: line-through; color: #ccc">' . $user_e_language_client_profile[10] . '</span> [Owner and/or drivers missing]' : $user_e_language_client_profile[10] ?>
														</label>
													</div>
													<div class="radio">
														<input type="radio" name="status" id="status2" value="0"<?= $limbo_client_status_by_data_id[$_GET['id']] == 0 ? ' checked=""' : '' ; ?>>
														<label for="status2">
															<?= $user_e_language_client_profile[11] ?>
														</label>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-5">
												<div class="form-group">
													<label>Physical address</label>
													<input name="address_line_1" type="text" class="form-control" id="address_line_1" value="<?= $limbo_client_address_line_1_by_data_id[$_GET['id']] ?>" placeholder="<?= $user_e_language_client_profile[13] ?>">
												</div>
												<div class="form-group">
													<input name="address_line_2" type="text" class="form-control" id="address_line_2" value="<?= $limbo_client_address_line_2_by_data_id[$_GET['id']] ?>" placeholder="<?= $user_e_language_client_profile[14] ?>">
												</div>
												<div class="form-group col-sm-6 col-md-5" style="padding-left:0px;">
													<input name="city" type="text" class="form-control" id="city" value="<?= $limbo_client_city_by_data_id[$_GET['id']] ?>" placeholder="<?= $user_e_language_client_profile[23] ?>">
												</div>
												<div class="form-group col-sm-6 col-md-4">
													<select name="state_id" class="form-control" id="state">
														<?php $geo_state = DB::getInstance()->query("SELECT * FROM geo_state");
														// Show empty option only if state is still == 0
														echo $limbo_client_state_id_by_data_id[$_GET['id']] == 0 ? '<option></option>' : '' ;
														foreach ($geo_state->results() as $geo_state_data) { ?>
															<option value="<?= $geo_state_data->state_id ?>"<?= $limbo_client_state_id_by_data_id[$_GET['id']] == $geo_state_data->state_id ? 'selected="selected"' : '' ?>><?= $geo_state_data->abbr . ' - ' . $geo_state_data->name ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="form-group col-sm-6 col-md-3" style="padding-right:0px;">
													<input name="zip_code" type="text" class="form-control" id="zip_code" value="<?= $limbo_client_zip_code_by_data_id[$_GET['id']] ?>">
												</div>
												<div class="form-group">
													<div class="checkbox-nice checkbox-inline">
														<input type="checkbox" id="mailing_use_physical" name="mailing_use_physical"<?= $limbo_client_mailing_use_physical_by_data_id[$_GET['id']] == 1 ? ' checked' : '' ?>>
														<label for="mailing_use_physical">
															Use physical address as mailing address
														</label>
													</div>
												</div>
												<?php if ($limbo_client_mailing_use_physical_by_data_id[$_GET['id']] == 0) {
													# Displays only if physical address and mailing address are not the same ?>
													<div class="form-group">
														<label>Mailing address</label>
														<input name="billing_address_line_1" type="text" class="form-control" id="billing_address_line_1" value="<?= $limbo_client_billing_address_line_1_by_data_id[$_GET['id']] ?>" placeholder="<?= $user_e_language_client_profile[13] ?>">
													</div>
													<div class="form-group">
														<input name="billing_address_line_2" type="text" class="form-control" id="billing_address_line_2" value="<?= $limbo_client_billing_address_line_2_by_data_id[$_GET['id']] ?>" placeholder="<?= $user_e_language_client_profile[14] ?>">
													</div>
													<div class="form-group col-sm-6 col-md-5" style="padding-left:0px;">
														<input name="billing_city" type="text" class="form-control" id="billing_city" value="<?= $limbo_client_billing_city_by_data_id[$_GET['id']] ?>" placeholder="<?= $user_e_language_client_profile[23] ?>">
													</div>
													<div class="form-group col-sm-6 col-md-4">
														<select name="billing_state_id" class="form-control" id="billing_state">
															<?php $geo_state = DB::getInstance()->query("SELECT * FROM geo_state");
															// Show empty option only if state is still == 0
															echo $limbo_client_billing_state_id_by_data_id[$_GET['id']] == 0 ? '<option></option>' : '' ;
															foreach ($geo_state->results() as $geo_state_data) { ?>
																<option value="<?= $geo_state_data->state_id ?>"<?= $limbo_client_billing_state_id_by_data_id[$_GET['id']] == $geo_state_data->state_id ? 'selected="selected"' : '' ?>><?= $geo_state_data->abbr . ' - ' . $geo_state_data->name ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-6 col-md-3" style="padding-right:0px;">
														<input name="billing_zip_code" type="text" class="form-control" id="billing_zip_code" value="<?= $limbo_client_billing_zip_code_by_data_id[$_GET['id']] ?>">
													</div><?php
												} ?>
												<div class="col-sm-12 col-md-12">
													<p class="text-right"><span class="help-block"><?= $user_e_language_client_profile[15] ?> <?= date('F d, Y', strtotime($limbo_client_added_by_data_id[$_GET['id']])) ?></span></p>
												</div>
											</div>
											<div class="col-sm-6 col-md-6">
												<button type="submit" class="btn btn-primary"><?= $user_e_language_client_profile[16] ?></button>
											</div>
											<div class="col-sm-6 col-md-6 text-right">
												<?php if (!$_GET['delete_client']) { ?>
													<a href="?id=<?= $_GET['id'] ?>&delete_client=<?= $_GET['id'] ?>" class="btn btn-danger"><i class="fa fa-trash-o fa-2"></i></a> <?php
												} else { ?>
													<a href="?id=<?= $_GET['id'] ?>&_hp_delete_loader_client=<?= $_GET['id'] ?>" class="btn btn-danger"><i class="fa fa-trash-o fa-2"></i> Confirm delete</a> <?php
												} ?>
											</div>
											<input type="hidden" name="_hp_update_loader_client" value="1">
											<input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div>

								<!-- #################
								# Broker association #
								################## -->

								<div class="panel panel-primary" id="client-broker-assoc">
									<div class="panel-heading">
										<h4 class="panel-title"><?= $limbo_client_company_name_by_data_id[$_GET['id']] ?> - Broker association</h4>
									</div>
									<div class="panel-body">
										<?php if ($quickpay_broker_count) {
											# Show new relationship form
											# This is a get form, once we have the broker_id parameter we can 
											# populate the select form with each broker's options ?>
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<form action="<?= $_GET['broker_id'] ? '' : 'client#client-broker-assoc' ?>" method="<?= $_GET['broker_id'] ? 'post' : 'get' ?>">
														<?php # Grab id from URI to pass it onto the post form
														echo $_GET['broker_id'] ? '' : '<input type="hidden" name="id" value="' . $_GET['id'] . '">' ?>
														<?php # Grab client_id from $_GET
														echo $_GET['broker_id'] ? '<input type="hidden" name="client_id" value="' . $_GET['id'] . '">' : '' ?>
														<div class="col-sm-12 col-md-3 form-group">
															<select name="broker_id" id="broker_id" class="form-control"<?= $_GET['broker_id'] ? 'readonly="readonly"' : '' ?>>
																<option value="0">Choose a broker</option>
																<?php for ($i=1; $i <= $quickpay_broker_count ; $i++) { ?>
																	<option value="<?= $quickpay_broker_data_id[$i] ?>"<?= $quickpay_broker_data_id[$i] == $_GET['broker_id'] ? 'selected="selected"' : '' ?>><?= $quickpay_broker_company_name[$i] ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-3 form-group">
															<select name="quickpay_service_fee_id" class="form-control"<?= $_GET['broker_id'] ? '' : ' disabled' ?>>
																<option>Choose a payment option</option>
																<?php for ($i=1; $i <= $loader_quickpay_service_fee_count ; $i++) { ?>
																	<option value="<?= $quickpay_service_fee_data_id[$i] ?>"><?= $quickpay_service_fee[$i] . '% via ' . $quickpay_method_of_payment_method_ctr[$quickpay_service_method_id[$i]] . ' (' . ($quickpay_service_number_of_days[$i] == 0 ? 'Same day' : ($quickpay_service_number_of_days[$i] . ' day' . ($quickpay_service_number_of_days[$i] == 1 ? '' : 's')))  . ') ' ?></option> <?php
																} ?>
															</select>
														</div>

														<?php # Display if we have $_GET['broker_id'] && no $loader_quickpay_invoice_counter_count (first time creation)
														if ($_GET['broker_id'] && !$loader_quickpay_invoice_counter_count) { ?>
															<div class="col-sm-12 col-md-4 form-group">
																<input type="number" min="1" name="counter" placeholder="Quickpay invoice counter (optional)" class="form-control">
															</div> <?php
														} ?>

														<div class="col-sm-12 col-md-2 form-group">
															<button type="submit" id="assoc_btn" class="btn btn-primary" <?= $_GET['broker_id'] ? '' : 'disabled' ?>><?= $_GET['broker_id'] ? 'Create association' : 'Select broker' ?></button>
														</div>
														<?php # Show token and _hp_ parameter only when form goes via $_POST
														if ($_GET['broker_id']) { ?>
															<input type="hidden" name="_hp_update_loader_client_broker_assoc" value="1">
															<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php								
														} ?>
													</form>
												</div>
											</div> <?php

											# List associations here for updating
											# user_e_profile_client_broker_assoc
											$user_e_profile_client_broker_assoc = DB::getInstance()->query("SELECT * FROM user_e_profile_client_broker_assoc WHERE client_id = " . $_GET['id']);
											$user_e_profile_client_broker_assoc_count = $user_e_profile_client_broker_assoc->count();
											if ($user_e_profile_client_broker_assoc_count) {

												# List all entries and make them readonly
												foreach ($user_e_profile_client_broker_assoc->results() as $user_e_profile_client_broker_assoc_data) {
													# Get data from loader_quickpay_service_fee
													# _alt = $loader_quickpay_service_fee already exists in limbo
													$loader_quickpay_service_fee_alt = DB::getInstance()->query("SELECT * FROM loader_quickpay_service_fee WHERE data_id = " . $user_e_profile_client_broker_assoc_data->quickpay_service_fee_id);
													if ($loader_quickpay_service_fee_alt->count()) {
													 	foreach ($loader_quickpay_service_fee_alt->results() as $loader_quickpay_service_fee_alt_data) {
													 		$alt_fee = $loader_quickpay_service_fee_alt_data->fee;
													 		$alt_method_id = $loader_quickpay_service_fee_alt_data->method_id;
													 		$alt_number_of_days = $loader_quickpay_service_fee_alt_data->number_of_days;
													 	}
													} ?>

													<form action="" method="post">
														<div class="col-sm-12 col-md-4 form-group">
															<select class="form-control" readonly>
																<?php for ($i=1; $i <= $quickpay_broker_count ; $i++) { ?>
																	<option value="<?= $quickpay_broker_data_id[$i] ?>"<?= $quickpay_broker_data_id[$i] == $user_e_profile_client_broker_assoc_data->broker_id ? 'selected="selected"' : '' ?>><?= $quickpay_broker_company_name[$i] ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-4 form-group">
															<input type="text" class="form-control" value="<?= $alt_fee . '% via ' . $quickpay_method_of_payment_method_ctr[$alt_method_id] . ' (' . ($alt_number_of_days == 0 ? 'Same day' : ($alt_number_of_days . ' day' . ($alt_number_of_days == 1 ? '' : 's')))  . ') ' ?>" readonly>
														</div>
														<div class="col-sm-12 col-md-4 form-group">
															<a href="client?id=<?= $_GET['id'] ?>&_hp_delete_loader_client_broker_assoc=<?= $user_e_profile_client_broker_assoc_data->data_id ?>" class="btn btn-danger">Remove</a>
														</div>
													</form> <?php
												}
											}

										} else { ?>
											<div class="alert alert-warning">
												<i class="fa fa-warning fa-fw fa-lg"></i>
												<strong>Warning!</strong> There are no brokers with quickpay set.
											</div> <?php
										} ?>
									</div>
								</div>

								<!-- ############
								# Company users #
								############# -->

								<div class="panel panel-<?= $user_e_profile_client_user_count == 0 ? 'danger' : 'primary' ?>" id="company-users">
									<div class="panel-heading">
										<h4 class="panel-title">Company users</h4>
									</div>
									<div class="panel-body">
										<?php if ($user_e_profile_client_user_count == 0) { ?>
											<div class="alert alert-danger">
												<i class="fa fa-times-circle fa-fw fa-lg"></i>
												<strong>Users missing!</strong> This company has no users, in order to activate it, you need to set at least one owner and one driver or one owner/operator.
											</div>
											<div class="alert alert-info">
												<i class="fa fa-info-circle fa-fw fa-lg"></i>
												<strong>Important</strong> You cannot add drivers with out a manager, the first user has to be an owner or owner/operator.
											</div> <?php
										} ?>

										<!-- ##################
										# Company users - NEW #
										################### -->
										<?php if (!$_GET['delete_loader_client_user']) { 
											# Show only if not trying to delete an user ?>
											<div class="col-sm-12 col-md-12">
												<form action="<?= $_GET['user_id'] ? '' : '#company-users' ?>" method="<?= $_GET['user_id'] ? 'post' : 'get' ?>">
													<input type="hidden" name="id" value="<?= $_GET['id'] ?>">
													<div class="form-group col-sm-12 col-md-3">
														<select name="user_id" class="form-control"<?= $_GET['user_id'] ? 'readonly' : '' ?>>
															<option>Choose a user</option>
															<?php for ($i=1; $i <= $available_external_user_count ; $i++) { ?>
																<option value="<?= $available_external_user_user_id[$i] ?>"<?= $available_external_user_user_id[$i] == $_GET['user_id'] ? 'selected' : '' ?>><?= $available_external_user_name[$i] . ' ' . $available_external_user_last_name[$i] ?></option> <?php
															} ?>
														</select>
													</div>
													<?php if ($_GET['user_id']) {
														# Show only on second step ?>
														<div class="form-group col-sm-12 col-md-3">
															<select name="user_type" id="user_type" class="form-control">
																<option>User type</option>
																<option value="9">Owner</option><!-- "9" used instead of "0" to pass validation -->
																<option value="1">Owner/Operator</option>
																<?= $user_e_profile_client_user_count == 0 ? '' : '<option value="2">Driver</option>' ?>
															</select>
														</div>
														<?php if ($user_e_profile_client_user_count != 0) {
															# Show only when there is already an owner or owner/operator ?>
															<div class="form-group col-sm-12 col-md-3 hidden" id="user_manager_holder">
																<select name="user_manager" class="form-control">
																	<option>User manager</option>
																	<?php for ($i=1; $i <= $user_e_profile_client_manager_count ; $i++) { ?>
																		<option value="<?= $client_manager_user_id_ctr[$i] ?>"><?= $_QU_e_name[$client_manager_user_id_ctr[$i]] . ' ' . $_QU_e_last_name[$client_manager_user_id_ctr[$i]] ?></option> <?php
																	} ?>
																</select>
															</div> <?php
														} 
													} ?>
													<div class="form-group col-sm-12 col-md-3">
														<input type="submit" class="btn btn-primary" value="<?= $_GET['user_id'] ? 'Add' : 'Select user' ?>">
													</div>
													<?php if ($_GET['user_id']) { ?>
														<input type="hidden" name="_hp_add_loader_client_user" value="1">
														<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
													} ?>
												</form>
											</div> <?php
										} ?>

										<!-- ###################
										# Company users - LIST #
										#################### -->

										<?php for ($i=1; $i <= $user_e_profile_client_user_count ; $i++) { ?>
											<div class="col-sm-12 col-md-12">
												<form>
													<div class="form-group col-sm-12 col-md-3">
														<select name="user_id" class="form-control" disabled>
															<option><?= $_QU_e_name[$client_user_user_id_ctr[$i]] . ' ' . $_QU_e_last_name[$client_user_user_id_ctr[$i]] ?></option>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<select name="user_type" class="form-control" disabled>
															<option><?= $client_user_user_type_ctr[$i] == 0 ? 'Owner' : ($client_user_user_type_ctr[$i] == 1 ? 'Owner/Operator' : 'Driver') ?></option>
														</select>
													</div>
													<?php if ($client_user_user_type_ctr[$i] == 2) { ?>
														<div class="form-group col-sm-12 col-md-3">
															<input class="form-control" value="<?= 'Managed by ' . $_QU_e_name[$client_user_user_manager_ctr[$i]] . ' ' . $_QU_e_last_name[$client_user_user_manager_ctr[$i]] ?>" disabled>
														</div> <?php
													} ?>
													<div class="form-group col-sm-12 col-md-3">
														<?php if (!$_GET['delete_loader_client_user']) {
															# Show first delete button ?>
															<a href="client?id=<?= $_GET['id'] ?>&delete_loader_client_user=<?= $client_user_data_id_ctr[$i] ?>#company-users" class="btn btn-danger"><span class="fa fa-trash-o"></span></a> <?php
														} elseif ($_GET['delete_loader_client_user'] == $client_user_data_id_ctr[$i]) {
															# Show second delete button ?>
															<a href="client?id=<?= $_GET['id'] ?>&_hp_delete_loader_client_user=<?= $_GET['delete_loader_client_user'] ?><?= $deactivate_client ? '&deactivate=1' : '' ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span> Confirm delete</a>
															<a href="client?id=<?= $_GET['id'] ?>#company-users" class="btn btn-link">Cancel</a> <?php
														} ?>
													</div>
												</form>
											</div> <?php
										} ?>
									</div>
								</div>

								<!-- ################
								# Factoring company #
								##################### -->

								<div class="panel panel-<?= $factoring_company_client_assoc_count && $factoring_company_count ? 'primary' : 'danger' ?>" id="factoring-company">
									<div class="panel-heading">
										<h4 class="panel-title">Factoring company</h4>
									</div>
									<div class="panel-body">
									<div class="row">
										<div class="col-sm-12 col-md-12">
											
											<?php if ($factoring_company_client_assoc_count) { ?>
												<h4><?= $factoring_company_name_did[$factoring_company_client_assoc_factoring_company_id] ?></h4>
												<p><b>Main: </b><?= $factoring_company_service_fee_fee_did[$factoring_company_client_assoc_main] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$factoring_company_client_assoc_main]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_main] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_main] == 1 ? $factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_main] . ' day' : $factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_main] . ' days')) ?>]</p>
												<p><b>Alternate: </b><?= $factoring_company_service_fee_fee_did[$factoring_company_client_assoc_alt] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$factoring_company_client_assoc_alt]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_alt] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_alt] == 1 ? $factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_alt] . ' day' : $factoring_company_service_fee_number_of_days_did[$factoring_company_client_assoc_alt] . ' days')) ?>]</p>
												<p><b>Current <?= $factoring_company_batch_schedule_did[$factoring_company_client_assoc_factoring_company_id] == 1 ? 'batch' : 'schedule' ?> counter: </b> <?= $factoring_company_client_assoc_counter ?></p> <?php
											} ?>

											<form action="<?= $_GET['factoring_company_id'] && $_GET['main'] ? '' : '#factoring-company' ?>" method="<?= $_GET['factoring_company_id'] && $_GET['main'] ? 'post' : 'get' ?>"<?= $factoring_company_client_assoc_count ? ' style="display: none;"' : '' ; ?> enctype="multipart/form-data">
												
												<?= $_GET['factoring_company_id'] && $_GET['main'] ? '<input type="hidden" name="client_id" value="' . $_GET['id'] . '">' : '<input type="hidden" name="id" value="' . $_GET['id'] . '">' ; # display client_id on post form only. Display id on get form only  ?>
												
												<div class="form-group col-sm-12 col-md-3">
													<label class="sr-only">Factoring company</label>
													<select name="factoring_company_id" class="form-control"<?= $_GET['factoring_company_id'] ? ' readonly' : ' onchange="this.form.submit()"' ?>>
														<option value="">Choose a factoring company</option>
														<?php if ($factoring_company_count) {
															for ($i = 1; $i <= $factoring_company_count ; $i++) { ?>
																<option value="<?= $factoring_company_data_id[$i] ?>"<?= $factoring_company_data_id[$i] == $_GET['factoring_company_id'] ? ' selected' : '' ?>><?= $factoring_company_name[$i] ?></option> <?php
															}
														} ?>
													</select>
												</div>
												<?php if ($_GET['factoring_company_id']) {
													# Show factoring company fee options ?>
													<div class="form-group col-sm-12 col-md-2">
														<label class="sr-only">Main service fee option</label>
														<select name="main" class="form-control"<?= $_GET['main'] ? ' readonly' : ' onchange="this.form.submit()"' ?>>
															<option value="">Primary service fee option</option>
															<?php if ($factoring_company_service_fee_count) {
																for ($i = 1; $i <= $factoring_company_service_fee_count ; $i++) { ?>
																	<option value="<?= $factoring_company_service_fee_data_id[$i] ?>"<?= $factoring_company_service_fee_data_id[$i] == $_GET['main'] ? ' selected' : '' ?>><?= $factoring_company_service_fee_fee[$i] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id[$i]] . ' [' . ($factoring_company_service_fee_number_of_days[$i] > 0 ? $factoring_company_service_fee_number_of_days[$i] : '') . ($factoring_company_service_fee_number_of_days[$i] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days[$i] > 1 ? 'days' : 'day')) . ']' ?></option> <?php
																}
															} ?>
														</select>
													</div> <?php
												}

												if ($_GET['main']) {
													# Show factoring company fee options for alt method, hide main from $_GET value ?>
													
													<div class="form-group col-sm-12 col-md-2">
														<label class="sr-only">Alternate service fee option</label>
														<select name="alt" class="form-control">
															<option value="">Alternate service fee option</option>
															<?php if ($factoring_company_service_fee_count) {
																for ($i = 1; $i <= $factoring_company_service_fee_count ; $i++) {
																	if ($factoring_company_service_fee_data_id[$i] != $_GET['main']) { ?>
																		<option value="<?= $factoring_company_service_fee_data_id[$i] ?>"><?= $factoring_company_service_fee_fee[$i] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id[$i]] . ' [' . ($factoring_company_service_fee_number_of_days[$i] > 0 ? $factoring_company_service_fee_number_of_days[$i] : '') . ($factoring_company_service_fee_number_of_days[$i] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days[$i] > 1 ? 'days' : 'day')) . ']' ?></option> <?php
																	}
																}
															} ?>
														</select>
													</div>

													<div class="form-group col-sm-12 col-md-2">
														<label class="sr-only">Schedule counter</label>
														<input data-toggle="tooltip" data-placement="top" title="Enter the next schedule number to be used" type="number" name="counter" min="1" class="form-control" placeholder="Next schedule counter">
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="sr-only">Invoice counter</label>
														<input data-toggle="tooltip" data-placement="top" title="Enter the next invoice number to be used" type="number" name="invoice_counter" min="1" class="form-control" placeholder="Next invoice counter">
													</div>

													<?php
													if ($factoring_company_requires_soar[1]) { ?>
														
														<div class="form-group col-sm-12 col-md-12">
															<label>Choose the empty soar file background for <?= $factoring_company_name[1] ?> and <?= $limbo_client_company_name_by_data_id[$_GET['id']] ?></label>
															<input type="file" name="invoice_background" accept="image/jpg" class="btn btn-default">
														</div><?php
													}
													?>

													<div class="form-group col-sm-12 col-md-12 text-center">
														<button type="submit" class="btn btn-primary">Save</button>
													</div>
													<input type="hidden" name="_hp_add_factoring_company_client_assoc" value="1">
													<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
												}

												echo $_GET['factoring_company_id'] ? '<a class="btn btn-link" href="client?id=' . $_GET['id'] . '#factoring-company">Cancel</a>' : '' ?>
											</form>
										</div>
									</div>
									</div>
								</div>

								<h2 style="text-shadow: 1px 1px 1px #03a9f4;">Schedules</h2>

								<table class="table">
									<thead>
										<tr>
											<th><span>Schedule #</span></th>
											<th><span>Loads in schedule</span></th>
											<th class="text-center"><span>Status</span></th>
											<th class="text-center"><span>Created</span></th>
										</tr>
									</thead>
									<tbody>

										<?php
										# Iterate through all schedules for this client
										for ($i = 1; $i <= $schedule_all_client_id_count ; $i++) { ?>
											
											<tr>
												<td>
													<a class="btn btn-primary" href="factoring-company-schedule?schedule_id=<?= $schedule_all_client_id_data_id[$i] ?>"><?= $schedule_all_client_id_counter[$i] ?></a>
												</td>
												<td>
													<?= $schedule_all_client_id_load_count ?>
												</td>
												<td class="text-center">
													<span class="label label-<?= $schedule_all_client_id_counter[$i] == $schedule_all_client_id_current_counter[$i] ? 'warning' : 'success' ?>"><?= $schedule_all_client_id_counter[$i] == $schedule_all_client_id_current_counter[$i] ? 'Open' : 'Sent' ?></span>
												</td>
												<td class="text-center">
													<?= $schedule_all_client_id_created[$i] ?>
												</td>
											</tr> <?php
										}
										?>
									</tbody>
								</table> <?php 
							} ?>
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

			$("#maskedAccountsPayableNumber").mask("(999) 999-9999? x99999");
			$("#maskedPhoneNumber").mask("(999) 999-9999");
			$("#maskedFaxNumber").mask("(999) 999-9999");

			document.getElementById('status1').onchange = function(){
				if (document.getElementById('status1').checked) {
					$('#doNotUse').addClass("hidden");
				}
			}

			document.getElementById('status2').onchange = function(){
				if (document.getElementById('status2').checked) {
					$('#doNotUse').addClass("hidden");
				}
			}

			<?php if ($_GET['user_id']) { ?>
				document.getElementById('user_type').onchange = function(){
					if (document.getElementById('user_type').value == 2) {
						$('#user_manager_holder').removeClass("hidden");
					} else {
						$('#user_manager_holder').addClass("hidden");
					}
				} <?php
			} ?>

			document.getElementById('broker_id').onchange = function() {
				if (document.getElementById('broker_id').value == 0) {
					$('#assoc_btn').attr("disabled", true);
				} else {
					$('#assoc_btn').attr("disabled", false);
				}
			}
		})();
	</script>
</body>
</html>
