<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath'] . '/core/init.php';

# Limbo
include($_SESSION['ProjectPath'] ."/includes/limbo.php");

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

# Declare file directory
$file_directory = "/home/" . $rootFolder . "/public_html/files/";

# Declare schedule directory
$schedule_directory = '/home/' . $rootFolder . '/public_html/files/schedule/';

# db/broker.php
include($_SESSION['ProjectPath']. "/resource/library/quantum/module/broker/db/broker.php");

# schedule_id data
$_GET['schedule_id'] ? require($_SESSION['ProjectPath'] . "/resource/library/quantum/module/loader/db/schedule_id.php") : '' ;

# client_id data
$_GET['schedule_id'] ? require($_SESSION['ProjectPath'] . "/resource/library/quantum/module/client/db/client.php") : '' ;

# schedule_all data
require($_SESSION['ProjectPath'] ."/includes/db_data/schedule_all.php");

# Controller calls
include($_SESSION['ProjectPath'] ."/includes/controller-calls.php");

# Include mPDF Class
include($_SESSION['ProjectPath'] . "/mpdf/mpdf.php");

# Create new mPDF Document
$mpdf = new mPDF();

# Factoring company
$factoring_company = DB::getInstance()->query("SELECT * FROM factoring_company WHERE data_id = " . $client_assoc_factoring_company_id);

$factoring_company_count = $factoring_company->count();

if ($factoring_company_count) {
	foreach ($factoring_company->results() as $factoring_company_data) {

		$factoring_company_requires_soar = $factoring_company_data->requires_soar;
	}
}

# Service fee option data
$_GET['fee_option'] ? require($_SESSION['ProjectPath'] ."/includes/db_data/service_fee.php") : '' ;

# schedule_config data
require($_SESSION['ProjectPath'] ."/includes/db_data/schedule_config.php");

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
	<link href='//fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/html5shiv.js"></script>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
	<![endif]-->
</head>
<body class="<?= $_IA_skin_class[$config_skin] ?>">
	<div id="theme-wrapper">
		<?php include($_SESSION['ProjectPath'] ."/includes/header.php") ?>
		<div id="page-wrapper" class="container<?= $config_nav == 1 ? ' nav-small' : '' ?>">
			<div class="row">
				<?php include($_SESSION['ProjectPath'] ."/includes/left-panel.php") ?>
				<div id="content-wrapper">
					<div class="row">
						<div class="col-lg-12">
					    <ol class="breadcrumb">
								<li><a href="<?= $_SESSION['href_location'] ?>0/"><?= $_QC_language[23] ?></a></li>
								<?php if ($_GET['add_load_to_schedule']) { ?>
									<li><a href="<?= $_SESSION['href_location'] ?>0/factoring-company-schedule">Factoring Company Schedule</a></li>
									<li class="active">Add</li> <?php
								} elseif ($_GET['schedule_id']) { ?>
									<li><a href="<?= $_SESSION['href_location'] ?>0/factoring-company">Factoring Company</a></li>
									<li><a href="<?= $_SESSION['href_location'] ?>0/factoring-company-schedule">Schedule</a></li>
									<li class="active">Schedule <?= $schedule_counter ?></li> <?php
								} ?>
							</ol>
							<div class="clearfix">
								
								<?php if (!$_GET) { ?>
									<h1 class="pull-left">Factoring Companies</h1> <?php
								} elseif ($_GET['add_load_to_schedule']) { ?>
									<h1 class="pull-left">Load to schedule</h1> <?php
								} elseif ($_GET['schedule_id']) { ?>
									<h1 class="pull-left">Schedule <?= $schedule_counter ?><small><?= $client_id_company_name[$client_assoc_factoring_company_client_id] . ' - ' . $factoring_company_name_did[$client_assoc_factoring_company_id] ?></small></h1> <?php
								} ?>

								<div class="pull-right top-page-ui">
									<?php 
									if (!$_GET['add_load_to_schedule']) {
										include($_SESSION['ProjectPath'] ."/includes/module-new-item-link.php");	
									} else { ?>
										<a style="margin:0 5px;" href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i> Cancel</a> <?php
									}
									include($_SESSION['ProjectPath'] ."/includes/module-refresh-link.php"); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?php  

							###############
							# Schedule ID #
							###############

							if ($_GET['schedule_id']) { ?>

								<div class="row">

									<?php
									# Show in confirmation page
									if ($_GET['fee_option']) { ?>
										
										<div class="col-sm-12 col-md-12">
											<div class="main-box no-header">
												<div class="main-box-body clearfix">
													<form action="" method="post">
													  <div class="form-group input-group">
											        <span class="input-group-addon">To</span>
											        <input type="text" name="to" class="form-control" value="<?= $factoring_company_invoicing_email_did[$client_assoc_factoring_company_id] ?>">
											      </div>
													  <div class="form-group input-group">
											        <span class="input-group-addon">Cc</span>
											        <input type="text" name="cc" class="form-control" value="admin@logisticsfrog.com<?= isset($driver_manager_id) ? ', ' . implode(', ', $driver_manager_id) : '' ?><?= isset($owner_id) ? ', ' . implode(', ', $owner_id) : '' ?>">
											      </div>
											      <div class="form-group input-group">
											        <span class="input-group-addon">Subject</span>
											        <input type="text" name="subject" class="form-control" value="<?= $schedule_config_email_subject ?>">
											      </div>
											      <div class="form-group">
										    			<textarea id="body" name="body" rows="10" cols="80">
										    				<?= $schedule_config_email_body ?>
										    			</textarea>
										    		</div>
													  <div class="form-group">
											        <button type="submit" class="btn btn-primary">Send</button>
											        <a style="margin: 10px 0;" class="pull-right" href="<?= $_SERVER['HTTP_REFERER'] ?>">Go back</a>
											      </div>
													  <input type="hidden" name="_hp_send_schedule" value="1" title="<?= $client_assoc_factoring_company_client_id ?>">
													  <input type="hidden" name="counter" value="<?= $schedule_counter ?>">
													  <input type="hidden" name="token" value="<?= $csrfToken ?>">
													</form>
												</div>
											</div>
										</div> <?php
									}
									?>

									<div class="col-sm-12 col-md-12">
										<div class="main-box">
											<div class="main-box-header clearfix text-center">
												<a href="client?id=<?= $client_assoc_factoring_company_client_id ?>"><?= $client_id_company_name[$client_assoc_factoring_company_client_id] ?></a> <span class="fa fa-arrows-h"></span> <a href="factoring-company?factoring_company_id=<?= $client_assoc_factoring_company_id ?>"><?= $factoring_company_name_did[$client_assoc_factoring_company_id] ?></a>
											</div>
											<div class="main-box-body clearfix">

												<?php
												# Create file if $_GET['create']
												if ($_GET['create']) {

													# Call create soar controller
													include($_SESSION['ProjectPath'] ."/includes/controller/add_soar_file.php");
												}

												if ($_GET['create_tafs']) {
													
													# Call add_tafs_invoice controller
													include($_SESSION['ProjectPath'] ."/includes/controller/add_tafs_invoice.php");
												}
												?>

												<div class="col-sm-12 col-md-6">
													<p><b>Schedule #<?= $schedule_counter ?></b></p>
													<p><b>Number of loads</b> <?= $factoring_company_schedule_load_count ?></p>
													<?= $factoring_company_requires_soar ? '<p><b>Number of soar pages</b> ' . $soar_num_pages . '</p>' : '' ?>
												</div>
												<div class="col-sm-12 col-md-6 text-right">
													
													<?php
													
													if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf') && $factoring_company_requires_soar) {

														# Hide if located on the last page before sending
														if (!$_GET['fee_option']) {

															# Display view soar button ?>
														 	<p><span class="green"><span class="fa fa-check"></span> Soar </span><a class="btn btn-link" href="factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>#invoice">View</a></p> <?php

														 	# For each load
															for ($i=1; $i <= $factoring_company_schedule_load_count ; $i++) { 

																# Set invoice count to 0
																$invoice_count == 0;

																# Declare file name
																$pre_invoice_file_name = strtolower(str_replace([' & ', ' '], ['-', '-'], $first_invoice_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));
																$invoice_file_name = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_file_name);

																# Check if invoices exist only on schedules where client assoc is not 4 (tafs).
																if ($client_assoc_id != 4) {
																	
																	if (file_exists($schedule_directory . $invoice_file_name)) {
																		# Display view invoice select (use select because if the list is long it will cause the parent box to grow too long vertically) ?>
																		<p>
																			<span class="green"><span class="fa fa-check"></span> Invoice <?= $first_invoice_number ?><?= $load_list_billing_status[$i] == 0 ? ' ready' : '' ?> </span><a class="btn btn-link" href="factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>#invoice-<?= $load_list_load_id[$i] ?>">View</a>
																		</p> <?php

																		# Add to $invoice_count
																		$invoice_count += 1;
																	} else { ?>
																		<p><a class="red" href="factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $first_invoice_number ?>&entry_id=<?= $load_list_entry_id[$i] ?>&load_id=<?= $load_list_load_id[$i] ?>&broker_id=<?= $load_list_broker_id[$i] ?>&invoice_name=<?= $invoice_file_name ?>">Create invoice <?= $first_invoice_number ?></a></p> <?php
																	}
																}

																# Increment the $first_invoice_number to display next invoice number
																$first_invoice_number++;
															}

															# if $client_assoc_factoring_company_current_counter != $schedule_counter show payment confirmation file upload
															if ($client_assoc_factoring_company_current_counter != $schedule_counter) {

																# Display view file link if file exists
																if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '-payment-confirmation.pdf')) { ?>

																	<p><span class="green"><span class="fa fa-check"></span> Payment confirmation</span> <a class="btn btn-link" href="factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>#payment-confirmation">View</a></p> <?php
																} else {

																	# Show payment confirmation upload link

																	echo !$_GET['upload_payment_confirmation'] ? '<p><span class="red">Payment confirmation</span> <a class="btn btn-link" href="factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&upload_payment_confirmation=1">Upload</a></p>' : '' ;
																	
																	# Show payment confirmation upload form
																	if ($_GET['upload_payment_confirmation']) { ?>
																		
																		<form action="" method="post" enctype="multipart/form-data">
																			<div class="form-group col-sm-12 col-md-12">
																				<label>Upload payment confirmation</label>
																				<p><input type="file" name="payment_confirmation_file" accept="application/pdf" class="btn btn-default pull-right"></p>
																			</div>
																			<div class="form-group col-sm-12 col-md-12">
																				<label>Correct amount paid?</label>
																				<p>
																					<select class="form-control pull-right" name="payment_confirmation" id="payment_confirmation" style="width: 297px;">
																						<option value=""></option>
																						<option value="3">Yes</option>
																						<option value="2">No</option>
																					</select>
																				</p>
																			</div>
																			<div class="form-group col-sm-12 col-md-12 hidden has-error" id="payment_confirmation_note_holder">
																				<label>Notes</label>
																				<p>
																					<textarea name="note" class="form-control pull-right red" style="width: 297px;"></textarea>
																				</p>
																			</div>
																			<div class="form-group col-sm-12 col-md-12">
																				<button type="submit" class="btn btn-primary">Upload payment confirmation</button>
																			</div>
																			<input type="hidden" name="_hp_upload_schedule_payment_confirmation" value="1">
			                    						<input type="hidden" name="token" value="<?= $csrfToken ?>">
																		</form> <?php
																	}
																}
															}
														}
													}	elseif (!$factoring_company_requires_soar) {
														
														# Hide if located on the last page before sending
														if (!$_GET['fee_option']) {

														 	# For each load
															for ($i = 1; $i <= $factoring_company_schedule_load_count ; $i++) { 

																# Set invoice count to 0
																$invoice_count == 0;

																# Declare file name
																$pre_invoice_file_name = strtolower(str_replace([' & ', ' '], ['-', '-'], $first_invoice_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));
																$invoice_file_name = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_file_name);

																# Check if invoices exist.
																if (file_exists($schedule_directory . $invoice_file_name)) {
																	# Display view invoice select (use select because if the list is long it will cause the parent box to grow too long vertically) ?>
																	<p>
																		<span class="green"><span class="fa fa-check"></span> Invoice <?= $first_invoice_number ?><?= $load_list_billing_status[$i] == 0 ? ' ready' : '' ?> </span><a class="btn btn-link" href="factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>#invoice-<?= $load_list_load_id[$i] ?>">View</a>
																	</p> <?php

																	# Add to $invoice_count
																	$invoice_count += 1;
																} else {

																	# Don't display if client_assoc_id = 4
																	if ($client_assoc_id != 4) { ?>
																		<p><a class="red" href="factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $first_invoice_number ?>&entry_id=<?= $load_list_entry_id[$i] ?>&load_id=<?= $load_list_load_id[$i] ?>&broker_id=<?= $load_list_broker_id[$i] ?>&invoice_name=<?= $invoice_file_name ?>">Create invoice <?= $first_invoice_number ?></a></p> <?php
																	}
																}

																# Increment the $first_invoice_number to display next invoice number
																$first_invoice_number++;
															}

															# if $client_assoc_factoring_company_current_counter != $schedule_counter show payment confirmation file upload
															if ($client_assoc_factoring_company_current_counter != $schedule_counter) {

																# Display view file link if file exists
																if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '-payment-confirmation.pdf')) { ?>

																	<p><span class="green"><span class="fa fa-check"></span> Payment confirmation</span> <a class="btn btn-link" href="factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>#payment-confirmation">View</a></p> <?php
																} else {

																	# Show payment confirmation upload link

																	echo !$_GET['upload_payment_confirmation'] ? '<p><span class="red">Payment confirmation</span> <a class="btn btn-link" href="factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&upload_payment_confirmation=1">Upload</a></p>' : '' ;
																	
																	# Show payment confirmation upload form
																	if ($_GET['upload_payment_confirmation']) { ?>
																		
																		<form action="" method="post" enctype="multipart/form-data">
																			<div class="form-group col-sm-12 col-md-12">
																				<label>Upload payment confirmation</label>
																				<p><input type="file" name="payment_confirmation_file" accept="application/pdf" class="btn btn-default pull-right"></p>
																			</div>
																			<div class="form-group col-sm-12 col-md-12">
																				<label>Correct amount paid?</label>
																				<p>
																					<select class="form-control pull-right" name="payment_confirmation" id="payment_confirmation" style="width: 297px;">
																						<option value=""></option>
																						<option value="3">Yes</option>
																						<option value="2">No</option>
																					</select>
																				</p>
																			</div>
																			<div class="form-group col-sm-12 col-md-12 hidden has-error" id="payment_confirmation_note_holder">
																				<label>Notes</label>
																				<p>
																					<textarea name="note" class="form-control pull-right red" style="width: 297px;"></textarea>
																				</p>
																			</div>
																			<div class="form-group col-sm-12 col-md-12">
																				<button type="submit" class="btn btn-primary">Upload payment confirmation</button>
																			</div>
																			<input type="hidden" name="_hp_upload_schedule_payment_confirmation" value="1">
																			<input type="hidden" name="token" value="<?= $csrfToken ?>">
																		</form> <?php
																	}
																}
															}
														}
													} else {
														
														# Show create soar link
														echo '<p><a class="btn btn-danger" href="factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&create=1&client_assoc_id=' . $schedule_client_assoc_id . '">Create Files</a></p>';
													}
													?>

												</div>
												<div class="col-sm-12 col-md-12">

												<?php
												# If the payment confirmation was uploaded with an incorrect amount
												if ($schedule_payment_confirmation == 2) {
													
													# Show incorrect amount warning and message. ?>

													<div class="alert alert-block alert-danger fade in">
														<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
														<h4>This schedule has payment confirmation but the amount is incorrect! <small class="pull-right">Added <?= $schedule_note_added[$incorrect_amount_counter] ?> by <?= $user_i_name[$schedule_note_user_id[$incorrect_amount_counter]] . ' ' . $user_i_last_name[$schedule_note_user_id[$incorrect_amount_counter]] ?></small></h4>
														<p>Note: <?= $schedule_note_note[$incorrect_amount_counter] ?></p>
														<p>

															<?= $_GET['close_schedule'] ? '' : '<a href="factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&close_schedule=1" class="btn btn-primary">Move to paid/closed schedules</a>' ; ?>

														</p>
													</div> <?php

													# If closing schedule
													if ($_GET['close_schedule']) { ?>
														
														<form method="post" action="">
															<div class="form-group has-error">
																<label class="red">Notes</label>
																<textarea name="note" class="form-control red" style="width: 297px;"></textarea>
															</div>
															<button type="submit" class="btn btn-danger">Close schedule</button>
															<input type="hidden" name="_hp_close_schedule" value="1">
		                    			<input type="hidden" name="token" value="<?= $csrfToken ?>">
														</form> <?php 
													}
												}

												# If closed schedule
												if ($schedule_payment_confirmation == 3) { ?>

													<div class="alert alert-block alert-success fade in">
														<h4>This schedule is closed <small class="pull-right"><?= $schedule_note_added[$closing_note_counter] ? 'Added ' . $schedule_note_added[$closing_note_counter] . ' by ' . $user_i_name[$schedule_note_user_id[$closing_note_counter]] . ' ' . $user_i_last_name[$schedule_note_user_id[$closing_note_counter]] : '' ?></small></h4>
														<?= $schedule_note_note[$closing_note_counter] ? '<p>Note:' . $schedule_note_note[$closing_note_counter] . '</p>' : '' ?>
													</div> <?php
												}
												?>

												</div>

												<?php 

												# Call create invoice controller if $_GET['create_invoice']
												if ($_GET['create_invoice']) {

													# Hide it
													echo '<div class="hidden">';
														# Controller call
														include_once($_SESSION['ProjectPath'] ."/includes/controller/create_schedule_invoice.php");
													echo '</div>';
												}

												# Display form only if soar file exists or if soar is not required 
												if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf') 
													|| !$factoring_company_requires_soar
													|| file_exists($schedule_directory . ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf')) {

													# Form method
													# If there is no $_GET['fee_option'] or there is $_GET['fee_option'] but set to 0
													((!isset($_GET['fee_option'])) || (isset($_GET['fee_option']) && $_GET['fee_option'] < 1)) ? $form_method = 'get' : '';
													# If $_GET['fee_option'] is greater than 0
													$_GET['fee_option'] > 0 ? $form_method = 'post' : $form_method = 'get' ;

													# Display form only if (1) we have loads on schedule, (2) if we have all merged invoices and (3) if this is the current schedule OR if TAFS invoice exists
													if (($factoring_company_schedule_load_count && ($invoice_count == $load_list_count) && ($client_assoc_factoring_company_current_counter == $schedule_counter)) || file_exists($schedule_directory . ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf')) { ?>
													 	
														<form action="" method="<?= $form_method ?>">
															<input type="hidden" name="schedule_id" value="<?= $_GET['schedule_id'] ?>">
															<div class="form-group">
																<?php if ($_GET['fee_option'] == $client_assoc_factoring_company_main) {
																	
																	# Display text only option chosen
																	echo '<p><b>Main service fee</b> <span class="fa fa-arrow-down"></span></p><p>' . $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_main] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_main]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 1 ? ' days' : ' day')) . ']</p>' ;

																} elseif ($_GET['fee_option'] == $client_assoc_factoring_company_alt) {

																	# Display text only option chosen
																	echo '<p><b>Alternate service fee</b> <span class="fa fa-arrow-down"></span></p><p>' . $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_alt] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_alt]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 1 ? ' days' : ' day')) . ']</p>' ;
																} else {

																	# We are not there yet, prompt user to choose fee option ?>
																	<select name="fee_option" class="form-control<?= isset($_GET['fee_option']) && $_GET['fee_option'] < 1 ? ' red' : '' ?>"<?= isset($_GET['fee_option']) && $_GET['fee_option'] < 1 ? ' style="border-color: red;"' : '' ?><?= $_GET['fee_option'] > 0 ? ' readonly' : '' ; ?>>
																		<option value="0">Choose a service fee</option>
																		<option value="<?= $client_assoc_factoring_company_main ?>">Main - <?= $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_main] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_main]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 1 ? ' days' : ' day')) . ']' ?></option>
																		<option value="<?= $client_assoc_factoring_company_alt ?>">Alt - <?= $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_alt] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_alt]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 1 ? ' days' : ' day')) . ']' ?></option>
																	</select> <?php
																} ?>
															</div>
															<div class="form-group">
																<?php 
																# If there is no $_GET['fee_option'] or there is $_GET['fee_option'] but set to 0
																if ((!isset($_GET['fee_option'])) || (isset($_GET['fee_option']) && $_GET['fee_option'] < 1)) { ?>
																	<input type="submit" class="btn btn-primary pull-right" value="Set service fee"> <?php
																} ?>
															</div>
														</form> <?php
													}
												}
												
												# No loads warning
												if (!$factoring_company_schedule_load_count) { ?>

													<div class="alert alert-warning">
														<i class="fa fa-warning fa-fw fa-lg"></i>
														<strong>Warning!</strong> There are no loads on this schedule.
													</div> <?php
												} ?>
											</div>
										</div>
									</div>
								</div>

								<div class="main-box">
									<header class="main-box-header clearfix">
										<h2>Loads on schedule</h2>
									</header>

									<div class="main-box-body clearfix">
										<?php if ($factoring_company_schedule_load_count) {
											
											# Display loads table ?>

											<div class="filter-block pull-right">
												<div class="form-group pull-left">
													<input type="text" id="filter" class="form-control" placeholder="Search...">
													<i class="fa fa-search search-icon"></i>
												</div>
											</div>
											<table class="table footable toggle-circle-filled" data-page-size="<?= $load_list_count ?>" data-filter="#filter" data-filter-text-only="true">
												<thead>
													<tr>
														<th></th>
														<th>Broker</th>
														<th>Load #</th>
														<th>Driver</th>
														<th>Line haul</th>
														<th data-hide="phone">Pick up</th>
														<th data-hide="phone">Delivery</th>
														<th data-hide="phone">Pickup</th>
														<th data-hide="phone">Delivery</th>
														<th data-hide="phone">User</th>
													</tr>
												</thead>
												<tbody>
													<?php for ($i=1; $i <= $load_list_count ; $i++) {

														# BOL File
														$loader_list_bol_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $load_list_load_id[$i] . ' && file_type = 1');
														$loader_list_bol_file_count = $loader_list_bol_file->count();

														# Check if this load belongs to a multiple load entry
														$loader_entry_multile_load = DB::getInstance()->query("SELECT entry_id, COUNT(entry_id) AS count_entry_id FROM loader_load WHERE entry_id = " . $load_list_entry_id[$i] . " GROUP BY entry_id");
														foreach ($loader_entry_multile_load->results() as $loader_entry_multile_load_data) {
														 	if ($loader_entry_multile_load_data->count_entry_id > 1) {
														 		# Multiple or single entry
														 		$entry_type = 'Multiple';
														 	} else {
														 		$entry_type = 'Single';
														 	}
														} ?>

														<tr<?= !$load_list_billing_status[$i] && $loader_list_bol_file_count ? ' style="background-color: #b00b00"' : ($load_list_billing_status[$i] == 1 ? ' class="purple-bg"' : ($load_list_billing_status[$i] == 2 ? ' class="yellow-bg"' : ($load_list_billing_status[$i] == 3 ? ' class="green-bg"' : ''))) ?>>
															<td><?= $load_list_billing_status[$i] == 0 ? '<a data-toggle="tooltip" data-placement="top" title="Removes this load from this schedule" href="factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&load_id=' . $load_list_load_id[$i] . '&_hp_delete_load_from_schedule=1" class="btn btn-danger"><span class="fa fa-trash-o"></span></a>' : '' ?></td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?> style="font-size: 0.875em; font-weight: normal;">
																<span data-toggle="tooltip" data-placement="top" title="<?= $broker_id_company_name[$load_list_broker_id[$i]] ?>"><?= substr($broker_id_company_name[$load_list_broker_id[$i]], 0, 25) ?></span>
															</td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>><?= $load_list_load_number[$i] ?></td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																<span data-toggle="tooltip" data-placement="top" title="<?= $_QU_e_phone_number_01[$load_list_driver_id[$i]] ?>"><?= $_QU_e_name[$client_driver_user_id[$load_list_driver_id[$i]]] . ' ' . $_QU_e_last_name[$client_driver_user_id[$load_list_driver_id[$i]]] ?></span>
															</td>
															<td class="text-right"<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>$ <?= number_format($load_list_line_haul[$i], 2) ?> / <span data-toggle="tooltip" data-toggle="top" title="$ <?= (number_format($load_list_line_haul[$i] / $load_list_miles[$i], 2)) ?> per mile"><?= number_format($load_list_miles[$i], 0) ?></span></td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																<?php # Get first pickup data if $load_list_load_id[$i] is set
																if (isset($load_list_load_id[$i])) {
																
																	$load_list_first_pick_up = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[$i] . " && data_type = 0 ORDER BY date_time LIMIT 1");
																	$load_list_first_pick_up_count = $load_list_first_pick_up->count();
																	if ($load_list_first_pick_up_count) {
																		
																		foreach ($load_list_first_pick_up->results() as $load_list_first_pick_up_data) {
																			$load_list_first_pick_up_checkpoint_id = $load_list_first_pick_up_data->checkpoint_id;
																			$load_list_first_pick_up_driver_id = $load_list_first_pick_up_data->driver_id;
																			$load_list_first_pick_up_date = date('m/d/y', strtotime($load_list_first_pick_up_data->date_time));
																			$load_list_first_pick_up_time = date('G:i', strtotime($load_list_first_pick_up_data->date_time));
																			$load_list_first_pick_up_city = ucfirst(strtolower(html_entity_decode($load_list_first_pick_up_data->city)));
																			$load_list_first_pick_up_state_id = $load_list_first_pick_up_data->state_id;
																			$load_list_first_pick_up_zip_code = $load_list_first_pick_up_data->zip_code;
																			$load_list_first_pick_up_status = $load_list_first_pick_up_data->status;

																			if ($load_list_first_pick_up_city || $load_list_first_pick_up_state_id) {
																				$pick_up_date = '<span data-toggle="tooltip" data-placement="top" title="' . $load_list_first_pick_up_zip_code . '">' . $load_list_first_pick_up_city . ', ' . $state_abbr[$load_list_first_pick_up_state_id] . ' </span>';
																			}

																			if ($load_list_first_pick_up_date) {
																				echo '<span class="pull-right" ' . ($load_list_first_pick_up_time ? 'data-toggle="tooltip" data-placement="top" title="' . $load_list_first_pick_up_time . '"' : '') . '>' . $load_list_first_pick_up_date . '</span>';
																			} 
																		}
																	}
																} ?>
															</td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																<?php # Get last pickup data if $load_list_load_id[$i] is set
																if (isset($load_list_load_id[$i])) {
																	
																	$load_list_last_drop_off = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[$i] . " && data_type = 1 ORDER BY date_time DESC LIMIT 1");
																	$load_list_last_drop_off_count = $load_list_last_drop_off->count();
																	if ($load_list_last_drop_off->count()) {
																		
																		foreach ($load_list_last_drop_off->results() as $load_list_last_drop_off_data) {
																			$load_list_last_drop_off_checkpoint_id = $load_list_last_drop_off_data->checkpoint_id;
																			$load_list_last_drop_off_driver_id = $load_list_last_drop_off_data->driver_id;
																			$load_list_last_drop_off_date = date('m/d/y', strtotime($load_list_last_drop_off_data->date_time));
																			$load_list_last_drop_off_time = date('G:i', strtotime($load_list_last_drop_off_data->date_time));
																			$load_list_last_drop_off_city = ucfirst(strtolower(html_entity_decode($load_list_last_drop_off_data->city)));
																			$load_list_last_drop_off_state_id = $load_list_last_drop_off_data->state_id;
																			$load_list_last_drop_off_zip_code = $load_list_last_drop_off_data->zip_code;
																			$load_list_last_drop_off_status = $load_list_last_drop_off_data->status;

																			if ($load_list_last_drop_off_city || $load_list_last_drop_off_state_id) {
																				$drop_date = '<span data-toggle="tooltip" data-placement="top" title="' . $load_list_last_drop_off_zip_code . '">' . $load_list_last_drop_off_city . ', ' . $state_abbr[$load_list_last_drop_off_state_id] . ' </span>';
																			}

																			if ($load_list_last_drop_off_date) {
																				echo '<span class="pull-right" ' . ($load_list_last_drop_off_time ? 'data-toggle="tooltip" data-placement="top" title="' . $load_list_last_drop_off_time . '"' : '') . '> ' . $load_list_last_drop_off_date . '</span>';
																			}
																		}
																	}
																} ?>
															</td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																<?= $pick_up_date ?>
															</td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																<?= $drop_date ?>
															</td>
															<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																<?= $user_i_name[$load_list_user_id[$i]] . ' ' . substr($user_i_last_name[$load_list_user_id[$i]], 0, 1) ?> 
																<span id="btn-<?= $i ?>" class="pull-right label label-<?= $entry_type == 'Multiple' ? 'primary' : 'default' ?> label-large"><a href="loader?id=<?= $load_list_entry_id[$i] ?>" style="color:#fff;"><span data-toggle="tooltip" data-placement="top" title="<?= $entry_type == 'Multiple' ? 'Multiple entry' : 'Single entry' ?>" class="fa fa-cube<?= $entry_type == 'Multiple' ? 's' : '' ?>"></span></a></span>
																<?php if ($load_list_entry_id[$i] > $load_list_load_id[$i]) { ?>
																 	<span class="pull-right label label-danger label-large" style="margin-right: 4px;"><a href="#" style="color:#fff;"><span data-toggle="tooltip" data-placement="top" title="Link unavailable, use entry link." class="fa fa-warning"></span></a></span> <?php 
																} else { ?>
																	<span class="pull-right label label-primary label-large" style="margin-right: 4px;"><a href="view-load?load_id=<?= $load_list_load_id[$i] ?>" style="color:#fff;"><span data-toggle="tooltip" data-placement="top" title="View load" class="fa fa-eye"></span></a></span> <?php
																} ?>
															</td>
														</tr><?php
													} ?>
												</tbody>
											</table> <?php
										} else {

											# Display warning ?>

											<div class="alert alert-warning">
												<i class="fa fa-warning fa-fw fa-lg"></i>
												<strong>Warning!</strong> There are no loads on this schedule.
											</div> <?php
										} ?>

									</div>
								</div>

								<?php

								# Display TAFS invoice
								if (file_exists($schedule_directory . ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf')) { ?>
									<embed<?= !$_GET['fee_option'] ? ' id="tafs-invoice "' : ' ' ?>src="http://<?= $domain ?>/files/schedule/<?= ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf' ?>?r=<?= date('Gis') ?>" width="100%" height="1015px"> <?php
								}

								# Display soar payment confirmation file
								if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '-payment-confirmation.pdf')) { ?>
									<embed<?= !$_GET['fee_option'] ? ' id="payment-confirmation "' : ' ' ?>src="http://<?= $domain ?>/files/schedule/soar-<?= $_GET['schedule_id'] ?>-payment-confirmation.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
								}
								
								# Display soar file
								if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf')) { ?>
									<embed<?= $_GET['fee_option'] ? '' : ' id="soar"' ?> src="http://<?= $domain ?>/files/schedule/soar-<?= $_GET['schedule_id'] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
								}

								# Get last invoice_number counter for this schedule id
								$first_invoice_2 = DB::getInstance()->query("SELECT invoice_number FROM factoring_company_schedule_load WHERE schedule_id = " . $_GET['schedule_id'] . " ORDER BY invoice_number ASC LIMIT 1");

								foreach ($first_invoice_2->results() as $first_invoice_2_data) {
									$first_invoice_2_number = $first_invoice_2_data->invoice_number;
								}

								for ($i=1; $i <= $load_list_count ; $i++) { 

									# Declare file name
									$pre_invoice_file_name = strtolower(str_replace([' & ', ' '], ['-', '-'], $first_invoice_2_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));
									$invoice_file_name = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_file_name);

									# Check if merged invoice exists for every load in schedule
									if (file_exists($schedule_directory . $invoice_file_name)) { ?>
										<embed <?= $_GET['fee_option'] ? '': 'id="invoice-' . $load_list_load_id[$i] . '"' ?> src="http://<?= $domain . '/files/schedule/' . $invoice_file_name ?>?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
									}

									$first_invoice_2_number++;
								}
							} ?>

						</div>
					</div>
					<?php include($_SESSION['ProjectPath'] ."/includes/footer.php"); ?>
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
			}

			# If next to last item
			$_GET['counter'] == ($factoring_company_schedule_load_count - 1) ? $last_item = 1 : '' ;

			# Being on $_GET['create'] means we have created the soar file, create invoices is next
			# Being on $_GET['create_no_soar'] means we have skipped creating the soar file, create invoices is next
			if ($_GET['create'] || $_GET['create_no_soar']) { ?>

				window.location = 'factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $schedule_invoice_number[1] ?>&entry_id=<?= $load_list_entry_id[1] ?>&load_id=<?= $load_list_load_id[1] ?>&broker_id=<?= $load_list_broker_id[1] ?>&invoice_name=<?= $invoice_name[1] ?>&counter=1<?= $factoring_company_schedule_load_count == 1 ? '&last_item=1' : '' ?>'; <?php
			}

			# Run after getting rid of $_GET['create']
			if ($_GET['create_invoice'] && !$_GET['create'] && !$_GET['last_item']) {

				# Run as long as there are loads in schedule_id
				if ($_GET['counter'] <= $factoring_company_schedule_load_count) { ?>

					window.location = 'factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $_GET['create_invoice'] + 1 ?>&entry_id=<?= $load_list_entry_id[$_GET['counter'] + 1] ?>&load_id=<?= $load_list_load_id[$_GET['counter'] + 1] ?>&broker_id=<?= $load_list_broker_id[$_GET['counter'] + 1] ?>&invoice_name=<?= $invoice_name[$_GET['counter'] + 1] ?>&counter=<?= $_GET['counter'] + 1 ?>&last_item=<?= $last_item ?>'; <?php
				}
			}

			# Back to main after making TAFS invoice
			if ($_GET['create_tafs']) { ?>

				window.location = 'factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>'; <?php
			}

			if ($_GET['last_item']) {

				# Go back to schedule ?>
				window.location = 'factoring-company-schedule?schedule_id=<?= $_GET['schedule_id'] ?>'; <?php
			}

			# CKEditor for schedule mail body
			if ($_GET['fee_option']) { ?>
				
				// CKEditor
				CKEDITOR.replace('body'); <?php
			} ?>

			$('.footable').footable();


			document.getElementById('payment_confirmation').onchange = function(){
				if (document.getElementById('payment_confirmation').selectedIndex == 2) {
					$('#payment_confirmation_note_holder').removeClass("hidden");
				} else {
					$('#payment_confirmation_note_holder').addClass("hidden");
				}
			}

		})();
	</script>
</body>
</html>