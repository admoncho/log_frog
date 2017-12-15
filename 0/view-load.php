<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath']. '/core/init.php';

# Limbo
include($_SESSION['ProjectPath']."/includes/limbo.php");

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

# load_id data
$_GET['load_id'] ? require($_SESSION['ProjectPath']."/includes/db_data/load_id.php") : '' ;

# URI: /0/loader?id=n&load_id=n&file_id=n
# URI: /0/loader?id=n&load_id=n&delete_file_id=n
if ($_GET['file_id'] || $_GET['delete_file_id']) {
	$_GET['file_id'] ? $_GET_file_id = $_GET['file_id'] : $_GET_file_id = $_GET['delete_file_id'] ;
	$loader_file_id = DB::getInstance()->query("SELECT * FROM loader_file WHERE file_id = " . $_GET_file_id);
	$loader_file_id_count = $loader_file_id->count();
	if ($loader_file_id_count) {
		foreach ($loader_file_id->results() as $loader_file_id_data) {
			$loader_file_id_name = $loader_file_id_data->file_name;
			$loader_file_id_extensionless_name = preg_replace('/\.(.*)/', '', $loader_file_id_data->file_name);
			$loader_file_id_extension = preg_replace('/^(.*[.])/', '', $loader_file_id_data->file_name);
			$loader_file_id_type = $loader_file_id_data->file_type;
			$loader_file_id_added = date('m d Y', strtotime($loader_file_id_data->added));
			$loader_file_id_user_id = $loader_file_id_data->user_id;
		}
	}
}

# BOL File
$loader_bol_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 1');
$loader_bol_file_count = $loader_bol_file->count();

if ($loader_bol_file_count) {
	foreach ($loader_bol_file->results() as $loader_bol_file_data) {
		$bol_file_id = $loader_bol_file_data->file_id;
		$bol_file_name = $loader_bol_file_data->file_name;
	}
}

# Rate confirmation File
$loader_rate_confirmation_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 2');
$loader_rate_confirmation_file_count = $loader_rate_confirmation_file->count();
if ($loader_rate_confirmation_file_count) {
	foreach ($loader_rate_confirmation_file->results() as $loader_rate_confirmation_file_data) {
		$rate_confirmation_file_id = $loader_rate_confirmation_file_data->file_id;
		$rate_confirmation_file_name = $loader_rate_confirmation_file_data->file_name;
	}
}
	
# Payment confirmation File
$loader_payment_confirmation_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 3');
$loader_payment_confirmation_file_count = $loader_payment_confirmation_file->count();
if ($loader_payment_confirmation_file_count) {
	foreach ($loader_payment_confirmation_file->results() as $loader_payment_confirmation_file_data) {
		$payment_confirmation_file_id = $loader_payment_confirmation_file_data->file_id;
		$payment_confirmation_file_name = $loader_payment_confirmation_file_data->file_name;
	}
}

# RAW BOL File
$loader_raw_bol_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 4');
$loader_raw_bol_file_count = $loader_raw_bol_file->count();
if ($loader_raw_bol_file_count) {
	foreach ($loader_raw_bol_file->results() as $loader_raw_bol_file_data) {
		$raw_bol_file_id = $loader_raw_bol_file_data->file_id;
		$raw_bol_file_name = $loader_raw_bol_file_data->file_name;
	}
}

# File directory
$file_directory = "/home/" . $rootFolder . "/public_html/files/";

# Quickpay file directory
$quickpay_invoice_dir = $file_directory . "quickpay-invoices/";

# Get rid of the dots that scandir() picks up in Linux environments
$quickpay_invoice_files = array_diff(scandir($quickpay_invoice_dir), array('..', '.'));

foreach ($quickpay_invoice_files as $file) {
	if (strpos($file, 'invoice-' . $load_id_entry_id . '-' . $_GET['load_id']) !== false) {
		$quickpay_invoice = $file;
	}
}

# Get client_id from this load driver_id
// $loader_entry_client_id = DB::getInstance()->query("SELECT * FROM user_e_profile_client_user WHERE user_id = " . $entry_id_driver_id); // OLD
$loader_entry_client_id = DB::getInstance()->query("SELECT * FROM client_user WHERE user_id = " . $entry_id_driver_id); // NEW

foreach ($loader_entry_client_id->results() as $loader_entry_client_id_data) {
	
	$entry_client_id = $loader_entry_client_id_data->client_id;

	# Which person should receive the quickpay email if sent?
	if ($loader_entry_client_id_data->user_type == 2) {
		
		// If user type == 2 (driver), send email to user_manager
		$driver_manager_id = $loader_entry_client_id_data->user_manager;
	} elseif ($loader_entry_client_id_data->user_type == 1) {
		
		// If user type == 1 (owner/operator), send email to user_id
		$driver_manager_id = $loader_entry_client_id_data->user_id;
	}
}

# Check if there is a client/broker association
$client_broker_assoc = DB::getInstance()->query("SELECT * FROM client_broker_assoc WHERE client_id = $entry_client_id && broker_id = $load_id_broker_id");
$client_broker_assoc_count = $client_broker_assoc->count();

# Controller calls
include($_SESSION['ProjectPath']."/includes/controller-calls.php");

$load_id_billing_status == 0 ? '' 
	: ($load_id_billing_status == 1 ? $box_format = ' style="-webkit-box-shadow: 4px 4px 5px 0px rgba(156,39,176,1); -moz-box-shadow: 4px 4px 5px 0px rgba(156,39,176,1); box-shadow: 4px 4px 5px 0px rgba(156,39,176,1);"'
		: ($load_id_billing_status == 2 ? $box_format = ' style="-webkit-box-shadow: 4px 4px 5px 0px rgba(255,193,7,1); -moz-box-shadow: 4px 4px 5px 0px rgba(255,193,7,1); box-shadow: 4px 4px 5px 0px rgba(255,193,7,1);"' 
			: $box_format = 'style="-webkit-box-shadow: 4px 4px 5px 0px rgba(139,195,74,1); -moz-box-shadow: 4px 4px 5px 0px rgba(139,195,74,1); box-shadow: 4px 4px 5px 0px rgba(139,195,74,1);"'));

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

	<style type="text/css">
    #file-prompt {
      display:none;
    }
  </style>

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
								<li><a href="<?= $_SESSION['href_location'] ?>dashboard/"><?= $_QC_language[23] ?></a></li>
								<li><a href="<?= $_SESSION['href_location'] ?>dashboard/loader/">Loader</a></li>
								<li class="active">Load #<?= $load_id_load_number ?></li>
							</ol>
							<div class="clearfix">
								<h1 class="pull-left">Load #<?= $load_id_load_number ?></h1>
								<div class="pull-right top-page-ui">

									<?php

									# If file upload request
									if ($_GET['upload']) { ?>
										
										<div class="pull-left" style="margin: 0 10px;">
											<form action="" method="post" enctype="multipart/form-data">
												<div style="padding-top: 13px;">
													<div id="file" class="pull-left red" style="margin: 6px 10px 0 10px; cursor: pointer;">Upload <?= $_GET['upload'] == 1 ? 'BOL' : ($_GET['upload'] == 2 ? 'Rate confirmation' : ($_GET['upload'] == 3 ? 'Payment confirmation' : 'Raw BOL')) ?></div>
													<input id="file-prompt" type="file" name="file" accept="application/pdf"/>
													<button class="btn btn-link" type="submit"><span style="font-size: 18px;" class="fa fa-cloud-upload"></span></button>
												</div>
												<input type="hidden" name="entry_id" value="<?= $load_id_entry_id ?>">
												<input type="hidden" name="file_type" value="<?= $_GET['upload'] ?>">
												<input type="hidden" name="_hp_add_loader_file" value="1">
								        <input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
										</div> <?php
									}

									# Schedule info is only available if there is a bol and ratecon for this load
									if ($bol && $rate_confirmation) {

										# Dislpay schedule info only if it exists and if this load is in it
										if ($schedule_count && $schedule_load_count) { ?>
										 	
										 	<a 
											class="btn btn-primary hidden"
											href="#" 
											data-toggle="popover" 
											data-html="true" 
											data-placement="bottom" 
											title="Schedule <b><?= $schedule_counter . ' </b><span class=\'pull-right\'><a href=\'factoring-company-schedule?schedule_id=' . $schedule_load_schedule_id . '\'>[view]</a></span>' ?>"
											data-content="
												<div>

													<?php

													# Loads title
													echo '<h5>' . $schedule_load_list_count . ' load' . ($schedule_load_list_count > 1 ? 's' : '') . '</h5>';

													# Iterate through loads in schedule
													for ($i = 1; $i <= $schedule_load_list_count ; $i++) { 
														
														echo '<a href=\'view-load?load_id=' . $schedule_load_list_load_id[$i] . '\'>Load #' . $schedule_load_list_load_number[$i] . ($schedule_load_list_load_id[$i] == $_GET[load_id] ? ' (this load)' : '') . '</a><br>';
													}

													echo $soar_file ? '<hr>
													<a href=\'view-load?load_id=' . $_GET['load_id'] . '&schedule=' . $schedule_load_schedule_id . '#schedule\' class=\'green\'><span class=\'fa fa-check\'></span> Soar file</a>' : '';

													# factoring company
													echo '<hr><a href=\'factoring-company?factoring_company_id=' . $client_assoc_factoring_company_id . '\'>' . $factoring_company_name_did[$client_assoc_factoring_company_id] . '</a> <small>[<a href=\'' . $factoring_company_uri_did[$client_assoc_factoring_company_id] . '\' target=\'_blank\'>url <span class=\'fa fa-external-link\'></span></a>]</small>';
													?>

												</div>">
												<span class="fa fa-calendar"></span>
											</a> <?php
										} else {

											# Display add load to schedule link ?>

											<a 
											class="btn btn-default hidden"
											href="#" 
											data-toggle="popover" 
											data-html="true" 
											data-placement="bottom" 
											title="Load not in schedule"
											data-content='
												<div>

													<?php
													if ($client_broker_assoc_count) {
														
														# Show warning if there is a client/broker association ?>
														
														<p class="text-jutified">
															<span class="red"> <i class="fa fa-warning"></i> </span>
															There is a quickpay association with this broker, are you sure you want to send this bill to <b><?= $factoring_company_name ?></b>?
														</p> <?php
													}
													?>
													<form action="factoring-company-schedule" method="post">
														<div class="row">
															<div class="form-group">
																<br>
																<div class="checkbox-nice checkbox-inline<?= $client_assoc_data_id == 4 || $factoring_company_status != 1 ? ' hidden' : '' ?>">
																	<input type="checkbox" id="checkbox-inl-1" name="create_files"<?= $client_assoc_data_id == 4 ? ' checked' : '' ?>>
																	<label for="checkbox-inl-1">
																		Create files?
																	</label>
																</div>
															</div>
															<div class="form-group">
																<div class="col-sm-12 col-md-12">
																	<input type="submit" class="btn btn-primary<?= $factoring_company_status != 1 ? ' hidden' : '' ?>" value="Add load to schedule">
																</div>
															</div>
														</div>
														<input type="hidden" name="client_assoc_id" value="<?= $client_assoc_data_id ?>">
														<input type="hidden" name="counter" value="<?= $client_assoc_counter ?>">
														<input type="hidden" name="invoice_counter" value="<?= ($last_invoice_number + 1) ?>">
														<input type="hidden" name="load_id" value="<?= $_GET['load_id'] ?>">
														<input type="hidden" name="requires_soar" value="<?= $factoring_company_requires_soar ? $factoring_company_requires_soar : "0" ?>">
														<input type="hidden" name="<?= $_SERVER['REMOTE_ADDR'] == '201.191.199.66' ? '_controller_schedule' : '_hp_add_load_to_schedule' ?>" value="1">
				                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
													</form>
												</div>
												<?= $factoring_company_status != 1 ? ' <p class="red"><a href="factoring-company?factoring_company_id=' . $client_assoc_factoring_company_id . '">' . $factoring_company_name_did[$client_assoc_factoring_company_id] . '</a> is inactive!</p>' : '' ?>'>
												<span class="fa fa-calendar"></span>
											</a> <?php
										} 
									} 

									# If either file is missing
									if (!$bol || !$rate_confirmation) { ?>

										<a 
										class="btn btn-danger hidden"
										href="#" 
										data-toggle="popover" 
										data-html="true" 
										data-placement="bottom" 
										title="Load not ready for schedule"
										data-content='
											<div>
												<?php 
												if (!$loader_rate_confirmation_file_count) { ?>
												 	
												 	<p>
												 		<span class="red">Rate confirmation missing!</span> - <a href="view-load?load_id=<?= $_GET['load_id'] ?>&upload=2">Upload</a>
												 	</p> <?php
												}

												if (!$loader_bol_file_count) { ?>

													<p>
												 		<span class="red">BOL missing!</span> - <a href="view-load?load_id=<?= $_GET['load_id'] ?>&upload=1">Upload</a>
												 	</p> <?php
												} ?>

											</div>' ?>

											<span class="fa fa-calendar"></span>
										</a>
										
										<?php 
									} ?>

									<div class="btn-group">
										<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>&file_handle=1" class="btn btn-info" style="display: none;">Files</a>
										<!-- <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" role="menu">

											<?= $loader_rate_confirmation_file_count ? '<li><a class="green" href="view-load?load_id=' . $_GET['load_id'] . '&rate_confirmation=' . $load_id_entry_id . '-' . $_GET['load_id'] . '#rate_confirmation">Rate confirmation</a></li>' : '<li><a class="red" href="view-load?load_id=' . $_GET['load_id'] . '&upload=2">Rate confirmation</a></li>' ?>
											<?= $loader_bol_file_count ? '<li><a class="green" href="view-load?load_id=' . $_GET['load_id'] . '&bol=' . $load_id_entry_id . '-' . $_GET['load_id'] . '#bol">BOL</a></li>' : '<li><a class="red" href="view-load?load_id=' . $_GET['load_id'] . '&upload=1">BOL</a></li>' ?>
											<?= $loader_raw_bol_file_count ? '<li><a class="green" href="view-load?load_id=' . $_GET['load_id'] . '&raw_bol=' . $load_id_entry_id . '-' . $_GET['load_id'] . '#raw_bol">Raw BOL</a></li>' : '<li><a class="red" href="view-load?load_id=' . $_GET['load_id'] . '&upload=4">Raw BOL</a></li>' ?>
											<?= $loader_payment_confirmation_file_count ? '<li><a class="green" href="view-load?load_id=' . $_GET['load_id'] . '&payment_confirmation=' . $load_id_entry_id . '-' . $_GET['load_id'] . '#payment_confirmation">Payment confirmation</a></li>' : '<li><a class="red" href="view-load?load_id=' . $_GET['load_id'] . '&upload=3">Payment confirmation</a></li>' ?>
											<?= $quickpay_invoice ? '<li><a class="green" href="view-load?load_id=' . $_GET['load_id'] . '&quickpay_invoice=' . $quickpay_invoice . '#quickpay_invoice">Quickpay invoice</a></li>' : '<li><a class="red" href="#">Quickpay invoice</a></li>'  ?>
											
										</ul> -->
									</div>

									<span style="display: none;" data-toggle="tooltip" data-placement="top" title="<?= ($checkpoint_count > 1) ? 'Send load info' : 'Checkpoints missing, cannot send info' ?>" class="btn btn-<?= ($checkpoint_count > 1) ? 'primary' : 'warning' ?> label-large" style="margin-left: 5px;">
										<a style="display: none;" <?= ($checkpoint_count > 1) ? 'href="loader?id=' . $load_id_entry_id . '&load_id=' . $_GET['load_id'] . '&send_load_info=' . $_GET['load_id'] . '"' : '' ?> style="color:#fff;">
											<span class="fa fa-envelope"></span>
										</a>
									</span>

									<?php 
									
									# Edit load
									echo '<a href="loader?id=' . $load_id_entry_id . '&load_id=' . $_GET['load_id'] . '" class="btn btn-danger"><span class="fa fa-edit"></span></a>'; ?>

								</div>
							</div>
						</div>

						<?php

						# Add loader file notifications
						if (Session::exists('add_loader_file')) { ?>
							
							<div class="col-lg-12">
								<div class="alert alert-success fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<i class="fa fa-check fa-fw fa-lg"></i>
									<?= Session::flash('add_loader_file') ?>
								</div>
							</div> <?php
						} elseif (Session::exists('add_loader_file_error')) { ?>
							
							<div class="col-lg-12">
								<div class="alert alert-danger fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<i class="fa fa-times-circle fa-fw fa-lg"></i>
									<?= Session::flash('add_loader_file_error') ?>
								</div>
							</div> <?php
						}

						# Add load to schedule notifications
						if (Session::exists('add_load_to_schedule')) { ?>
							
							<div class="col-lg-12">
								<div class="alert alert-success fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<i class="fa fa-check fa-fw fa-lg"></i>
									<?= Session::flash('add_load_to_schedule') ?>
								</div>
							</div> <?php
						} elseif (Session::exists('add_load_to_schedule_error')) { ?>
							
							<div class="col-lg-12">
								<div class="alert alert-danger fade in">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<i class="fa fa-times-circle fa-fw fa-lg"></i>
									<?= Session::flash('add_load_to_schedule_error') ?>
								</div>
							</div> <?php
						} ?>

					</div>

					<div class="row">
						
						<div class="alert alert-danger">
							<i class="fa fa-warning fa-fw fa-lg"></i>
							Everything is on the <a href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>">new load page</a>
						</div>

						<div class="col-sm-12 col-md-12" style="display: none;">
							<div class="main-box"<?= $box_format ?>>
								<header class="main-box-header clearfix">
									<h2 class="text-center" style="font-size: 1.5em;">
										<?= $_QU_e_name[$entry_id_driver_id] . ' ' . $_QU_e_last_name[$entry_id_driver_id] . ' - ' . $client_id_company_name ?>
									</h2>
								</header>
								<div class="main-box-body clearfix">
									<div class="row">
										<div class="col-sm-12 col-md-6">

											<h1><?= $checkpoint_id_city[1] . ', ' . $state_abbr[$checkpoint_id_state_id[1]] ?></h1>
											<h2><?= $checkpoint_id_date_time_2[1] ?></h2>
										</div>
										<div class="col-sm-12 col-md-6 text-right">

											<h1><?= $checkpoint_id_city[$checkpoint_id_count] . ', ' . $state_abbr[$checkpoint_id_state_id[$checkpoint_id_count]] ?></h1>
											<h2><?= $checkpoint_id_date_time_2[$checkpoint_id_count] ?></h2>
										</div>
										<div class="col-sm-12 col-md-4">

											<?php
											echo '<b>Deadhead: </b>' . $load_id_deadhead;
											echo $load_id_reference ? '<br><b>Reference: </b>' . $load_id_reference . '<br>' : '';
											?>

										</div>
										<div class="col-sm-12 col-md-4 text-center">

											<?= '<p>$' . number_format($load_id_line_haul, 2) . ' - ' . $load_id_miles . 'm - $' . (number_format($load_id_line_haul / $load_id_miles, 2)) . ' per mile</p>' ;?>
											<?= '<p><a href="broker?id=' . $load_id_broker_id . '">' . $broker_id_company_name . '</a></p>' ?>

										</div>
										<div class="col-sm-12 col-md-4 text-right">

											<?= number_format($load_id_weight, 2) . 'lb'  ?>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-12 col-md-12" style="display: none;">
							<div class="main-box">
								<header class="main-box-header clearfix">
									<h2>Load data</h2>

									<?php echo $load_id_load_lock ? '<small class="red">Some items of this load are no longer editable.</small>' : ''; ?>

								</header>
								<div class="main-box-body clearfix">
									<div class="row">
										<div class="col-sm-12 col-md-6">
											
											<?php
											echo '<b>Broker name &amp; number: </b>' . $load_id_broker_name_number . '<br>';
											echo '<b>Broker email: </b><a href="mailto:' . $load_id_broker_email . '">' . $load_id_broker_email . '</a><br>';
											echo $load_id_commodity ? '<b>Commodity: </b>' . $load_id_commodity . '<br>' : '';
											?>

										</div>
										<div class="col-sm-12 col-md-6">
											
											<?php 
											echo '<b>Avg diesel price: </b>$' . $load_id_avg_diesel_price . '<br>';
											echo '<b>Load number: </b>' . $load_id_load_number . '<br>';
											echo $load_id_billing_date != '11/30/-0001' ? '<b>Billing date: </b>' . $load_id_billing_date . '<br>' : '';
											echo '<b>Agent: </b>' . $_QU_i_name[$load_id_user_id] . ' ' . $_QU_i_last_name[$load_id_user_id] . '<br>';
											echo $load_id_notes ? '<b>Notes: </b>' . $load_id_notes . '<br>' : '';
											echo $load_id_load_status == 1 ? '<b>Status: </b><span class="red">Deleted</span><br>' : '';
											?>

										</div>
									</div>
								</div>
							</div>

							<?php
							if ($checkpoint_id_count) { ?>
								
								<div class="main-box">
									<header class="main-box-header clearfix">
										<h2>Checkpoints</h2>
									</header>
									<div class="main-box-body clearfix">

										<?php
										for ($i = 1; $i <= $checkpoint_id_count ; $i++) {

											# Throw .clearfix only on odd numbers and hide on number 1 and hide on last item
									 		echo $i % 2 != 0 && $i != 1 && $i != $checkpoint_id_count ? '<div class="clearfix"></div>' : '';
											echo '<div class="col-sm-12 col-md-6" style="height: inherit ! important">';
												echo $i > 2 ? '<br>' : '';
												echo '<h2 style="padding-left: 10px;"' . ($checkpoint_id_data_type[$i] == 0 ? ' class="bg-info"' : ' class="bg-primary"') . '>' . $checkpoint_id_date_time[$i] . ' <small' . ($checkpoint_id_data_type[$i] == 1 ? ' style="color: #fff;"' : '') . '>[' . ($checkpoint_id_data_type[$i] == 0 ? 'Pick' : 'Drop') . ']</small></h2>';
												echo '<b>' . $checkpoint_id_line_1[$i] . '</b><br>';
												echo $checkpoint_id_line_2[$i] ? $checkpoint_id_line_2[$i] . '<br>' : '';
												echo $checkpoint_id_city[$i] . ', ' . $state_abbr[$checkpoint_id_state_id[$i]] . ' ' . $checkpoint_id_zip_code[$i];
												echo '<br><br>';
												echo $checkpoint_id_contact[$i] ? '<b>Contact: </b>' . $checkpoint_id_contact[$i] . '<br>' : '';
												echo $checkpoint_id_appointment[$i] ? '<b>Appointment: </b>' . $checkpoint_id_appointment[$i] . '<br>' : '';
												echo $checkpoint_id_notes[$i] ? '<b>Notes: </b>' . $checkpoint_id_notes[$i] . '<br>' : '';
												echo '<b>Status: </b>' . ($checkpoint_id_status[$i] == 0 ? '<span class="red">Incomplete</span> - <a href="loader-status-change-notification?checkpoint_id=' . $checkpoint_id_checkpoint_id[$i] . '&id=' . $load_id_entry_id . '&load_id=' . $_GET['load_id'] . '&checkpoint_data_type=' . $checkpoint_id_data_type[$i] . '&checkpoint_city=' . $checkpoint_id_city[$i] . '&checkpoint_state_id=' . $checkpoint_id_state_id[$i] . '&broker_email=' . $load_id_broker_email . '&client_id=' . $client_user_id_client_id . '">Send status change notification</a>' : '<span class="text-success">Complete</span>') . '<br>';
												echo '<small class="text-muted">Added ' . $checkpoint_id_added[$i] . ' by ' . $_QU_i_name[$checkpoint_id_user_id[$i]] . ' ' . $_QU_i_last_name[$checkpoint_id_user_id[$i]] . '</small>';
											echo '</div>';
										}
										?>

									</div>
								</div> <?php
							}

							# Split into 2 colums if $other_charges_count
							echo $other_charges_count ? '<div class="col-sm-12 col-md-3">' : '';

								# Check for other charges
								if ($other_charges_count) { ?>

									<div class="main-box" id="staff_notes">
										<header class="main-box-header clearfix">
											<h2>Other charges</h2>
										</header>
										<div class="main-box-body clearfix">
											<table class="table table-bordered">
												<thead>
													<tr>
														<th><span>Detail</span></th>
														<th><span>Amount</span></th>
													</tr>
												</thead>
												<tbody>

													<?php
													for ($i = 1; $i <= $other_charges_count ; $i++) { ?>
														
														<tr>
															<td>
																<?= $other_charges_item[$i] ?>
															</td>
															<td class="text-right">
																<?= '$' . number_format($other_charges_price[$i], 2) ?>
															</td>
														</tr> <?php
													}
													?>

													<tr>
														<td>
															<b>Total</b>
														</td>
														<td class="text-right">
															<b><?= '$' . number_format($other_charges_price_sum, 2) ?></b>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div> <?php
								}

							# Split into 2 colums if $other_charges_count closing div
							echo $other_charges_count ? '</div>' : '';

							# Split into 2 colums if $other_charges_count
							echo $other_charges_count ? '<div class="col-sm-12 col-md-9">' : ''; ?>

								<div class="main-box" id="staff_notes">
									<header class="main-box-header clearfix">
										<h2>Staff notes</h2>
									</header>
									<div class="main-box-body clearfix">

										<div class="conversation-wrapper">
											<div class="conversation-content">
												<div class="conversation-inner">
													
													<?php
													for ($i= 1; $i <= $load_note_count ; $i++) { 
														# Display notes ?>
														<div class="conversation-item item-<?= $user->data()->user_id == $load_notes_note_id[$i] ? 'right' : 'left' ?> clearfix">
															<div class="conversation-user">
																<span class="fa fa-user"></span>
															</div>
															<div class="conversation-body"<?= $load_notes_important[$i] == 1 && $load_notes_type[$i] == 0 ? ' style="background-color: #e84e40 !important; color: #fff !important; "' : ($load_notes_important[$i] == 0 && $load_notes_type[$i] == 1 ? ' style="background-color: #8bc34a !important; color: #fff !important; "' : '') ?>>
																<div class="name">
																	<?= $_QU_i_name[$load_notes_user_id[$i]] . ' ' . $_QU_i_last_name[$load_notes_user_id[$i]] ?> <?= $load_notes_type[$i] == 1 ? '<small><i>[automated]</i></small>' : '' ?>
																</div>
																<div class="time hidden-xs"<?= $load_notes_important[$i] == 1 || $load_notes_type[$i] == 1 ? ' style="color: #fff !important;"' : '' ?>>
																	<?= $load_note_added[$i] ?>
																</div>
																<div class="text">
																	<?= $load_notes_note[$i] ?>
																</div>
															</div>
														</div> <?php
													}
													?>
													
												</div>
											</div>
											<div class="conversation-new-message">
													<form action="" method="post">
														<div class="form-group">
															<textarea name="note" class="form-control" rows="2" placeholder="Enter your message..."></textarea>
														</div>
														<div class="form-group">
															<div class="checkbox-nice checkbox-inline">
																<input type="checkbox" id="important_note" name="important_note">
																<label for="important_note">
																	Mark as important
																</label>
															</div>
														</div>
														<div class="clearfix">
															<button type="submit" class="btn btn-primary">Add note</button>
														</div>
														<input type="hidden" name="_hp_add_loader_load_note" value="1">
                    				<input type="hidden" name="token" value="<?= $csrfToken ?>">
													</form>
												</div>
										</div>

									</div>
								</div> <?php 
								

							# Split into 2 colums if $schedule_load_count || $other_charges_count closing div
							echo $other_charges_count ? '</div>' : '';
							?>

						</div>

						<div class="col-sm-12 col-md-12" style="display: none;">
							
							<?php
							# rate_confirmation
							echo $_GET['rate_confirmation'] ? '<div class="text-right"><a class="btn btn-link red" href="view-load?load_id=' . $_GET['load_id'] . '">Close file <span class="fa fa-close"></span></a></div><embed id="rate_confirmation" src="http://' . $domain . '/files/rate-confirmation-' . $_GET['rate_confirmation'] . '.pdf?r=' . date('Gis') . '" width="100%" height="1001px">' : '';
							# bol
							echo $_GET['bol'] ? '<div class="text-right"><a class="btn btn-link red" href="view-load?load_id=' . $_GET['load_id'] . '">Close file <span class="fa fa-close"></span></a></div><embed id="bol" src="http://' . $domain . '/files/bol-' . $_GET['bol'] . '.pdf?r=' . date('Gis') . '" width="100%" height="1001px">' : '';
							# raw_bol
							echo $_GET['raw_bol'] ? '<div class="text-right"><a class="btn btn-link red" href="view-load?load_id=' . $_GET['load_id'] . '">Close file <span class="fa fa-close"></span></a></div><embed id="raw_bol" src="http://' . $domain . '/files/raw-bol-' . $_GET['raw_bol'] . '.pdf?r=' . date('Gis') . '" width="100%" height="1001px">' : '';
							# invoice
							echo $_GET['invoice'] ? '<div class="text-right"><a class="btn btn-link red" href="view-load?load_id=' . $_GET['load_id'] . '">Close file <span class="fa fa-close"></span></a></div><embed id="invoice" src="http://' . $domain . '/files/invoice-' . $_GET['invoice'] . '.pdf?r=' . date('Gis') . '" width="100%" height="1001px">' : '';
							# payment_confirmation
							echo $_GET['payment_confirmation'] ? '<div class="text-right"><a class="btn btn-link red" href="view-load?load_id=' . $_GET['load_id'] . '">Close file <span class="fa fa-close"></span></a></div><embed id="payment_confirmation" src="http://' . $domain . '/files/payment-confirmation-' . $_GET['payment_confirmation'] . '.pdf?r=' . date('Gis') . '" width="100%" height="1001px">' : '';
							# quickpay_invoice
							echo $_GET['quickpay_invoice'] ? '<div class="text-right"><a class="btn btn-link red" href="view-load?load_id=' . $_GET['load_id'] . '">Close file <span class="fa fa-close"></span></a></div><embed id="quickpay_invoice" src="http://' . $domain . '/files/quickpay-invoices/' . $quickpay_invoice . '?r=' . date('Gis') . '" width="100%" height="1001px">' : '';
							# schedule
							echo $_GET['schedule'] ? '<div class="text-right"><a class="btn btn-link red" href="view-load?load_id=' . $_GET['load_id'] . '">Close file <span class="fa fa-close"></span></a></div><embed id="schedule" src="http://' . $domain . '/files/schedule/soar-' . $_GET['schedule'] . '.pdf?r=' . date('Gis') . '" width="100%" height="1001px">' : '';
							?>

						</div>

						<div class="col-sm-12 col-md-12" style="display: none;">

							<form action="/dashboard/loader/quickpay-invoicing" method="post">
								
								<button type="submit" class="btn btn-link">Send quickpay invoice</button>
								<input type="hidden" name="broker_quickpay_email" value="<?= $load_id_broker_email ?>">
								<input type="hidden" name="driver_manager_id" value="<?= $driver_manager_id ?>">
								<input type="hidden" name="load_number" value="<?= $load_id_load_number ?>">
							</form>
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

			var wrapper = $('<div/>').css({'display': 'none','overflow':'hidden'});
	    var fileInput = $(':file').wrap(wrapper);

	    fileInput.change(function(){
	      $this = $(this);
	      $('#file').text($this.val());
	    })

	    $('#file').click(function(){
	      fileInput.click();
	    }).show();

			$('.footable').footable();

			$("[data-toggle=popover]").popover();
		})();
	</script>
</body>
</html>
