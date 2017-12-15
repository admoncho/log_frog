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
								<li><a href="<?= $_SESSION['href_location'] ?>0/"><?= $_QC_language[23] ?></a></li>
								<?php if ($_GET['new']) { ?>
									<li><a href="<?= $_SESSION['href_location'] ?>0/broker">Broker</a></li>
									<li class="active"><a href="<?= $_SESSION['href_location'] ?>0/">Add</a></li> <?php
								} elseif ($_GET['id']) { ?>
									<li class="active"><a href="<?= $_SESSION['href_location'] ?>0/broker">Broker</a></li> <?php
								} ?>
							</ol>
							<div class="clearfix">
								
								<?php if (!$_GET) { ?>
									<h1 class="pull-left">Brokers</h1> <?php
								} elseif ($_GET['new']) { ?>
									<h1 class="pull-left">Broker</h1> <?php
								} elseif ($_GET['id']) { ?>
									<h1 class="pull-left"><?= $broker_co_name_did[$_GET['id']] ?></h1> <?php
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
							# Broker List #
							###############

							if (!$_GET) { ?>

								<div class="main-box no-header">
									<?php if ($broker_co_count) { ?>
									<?php } ?>
									<div class="main-box-body clearfix">
										<?php if (!$broker_co_count) { ?>
											<div class="alert alert-warning" role="alert">
									      There are no brokers yet, <a href="broker?new=1">add the first one</a>.
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
														<th>Phone number</th>
														<th></th>
														<th data-hide="all" class="text-right">Fax #</th>
														<th data-hide="all" class="text-right">Quickpay</th>
														<!-- <th data-hide="all" class="text-right">Quickpay email</th> -->
														<th data-hide="all" class="text-right">Accounts payable #</th>
														<th data-hide="all" class="text-right">Address line 1</th>
														<th data-hide="all" class="text-right">Address line 2</th>
														<th data-hide="all" class="text-right">Address line 3</th>
														<th data-hide="all" class="text-right">City, State zipcode</th>
														<th data-hide="all" class="text-right">Status</th>
														<th data-hide="all" class="text-right">Created</th>
													</tr>
												</thead>
												<tbody>
													<?php for ($i=1; $i <=$broker_co_count ; $i++) { ?>
														<tr>
															<td><a href="broker?id=<?= $broker_co_data_id[$i] ?>"<?= $broker_co_status[$i] == 0 ? ' class="red"' : '' ?>><?= $broker_co_name[$i] ?></a></td>
															<td><?= $broker_co_phone_number_01[$i] ?></td>
															<td><a href="broker?id=<?= $broker_co_data_id[$i] ?>" class="btn btn-primary btn-sm"><span class="fa fa-edit"></span></a></td>
															<td><?= $broker_co_fax_number[$i] ?></td>
															<td><?= $broker_co_quickpay[$i] == 1 ? 'Yes' : 'No' ?></td>
															<!-- <td><?= $broker_co_quickpay_email[$i] // if this is present, the searchbox becomes unusable ?></td> -->
															<td><?= $broker_co_accounts_payable_number[$i] ?></td>
															<td><?= $broker_co_address_line_1[$i] ?></td>
															<td><?= $broker_co_address_line_2[$i] ?></td>
															<td><?= $broker_co_address_line_3[$i] ?></td>
															<td><?= $broker_co_city[$i] . ', ' . $state_name[$broker_co_state_id[$i]] . ' ' . $broker_co_zip_code[$i] ?></td>
															<td><?= $broker_co_status[$i] == 0 ? 'Inactive' : ($broker_co_status[$i] == 1 ? 'Active' : 'DO NOT USE') ?></td>
															<td><?= date('M, Y', strtotime($broker_co_added[$i])) ?> by <?= $user_i_name[$broker_co_user_id[$i]] . ' ' . $user_i_last_name[$broker_co_user_id[$i]] ?></td>
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
							# Add broker #
							##############

							elseif ($_GET['new']) { ?>
								<!-- <div class="main-box">
									<header class="main-box-header clearfix">
										<h2>Add new broker company</h2>
									</header>
									<div class="main-box-body clearfix">
										<form action="" method="post">
											<div class="col-sm-12 col-md-8">
												<div class="form-group">
													<label><span class="red">* </span>Company name</label>
													<input name="company_name" class="form-control" type="text">
												</div>
												<div class="form-group">
													<label><span class="red">* </span>Phone Number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-phone"></i></span>
														<input name="phone_number_01" class="form-control" type="text" id="maskedPhoneNumber">
													</div>
												</div>
												<div class="form-group">
													<label><span class="red">* </span>Quickpay</label>
													<div class="radio">
														<input type="radio" name="quickpay" id="quickpay1" value="1">
														<label for="quickpay1">
															Yes
														</label>
													</div>
													<div class="radio">
														<input type="radio" name="quickpay" id="quickpay2" value="2" checked="">
														<label for="quickpay2">
															No
														</label>
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-4">
												<div class="form-group" id="address_line_1_holder">
													<label><?= $user_e_language_broker_profile[22] ?></label>
													<input name="address_line_1" type="text" class="form-control" id="address_line_1" placeholder="* <?= $user_e_language_broker_profile[23] ?>">
												</div>
												<div class="form-group" id="address_line_2_holder">
													<input name="address_line_2" type="text" class="form-control" id="address_line_2" placeholder="* <?= $user_e_language_broker_profile[24] ?>">
												</div>
												<div class="form-group">
													<input name="address_line_3" type="text" class="form-control" id="address_line_3" placeholder="Line 3">
												</div>
												<div class="form-group col-sm-6 col-md-5" id="city_holder" style="padding-left:0px;">
													<input name="city" type="text" class="form-control" id="city" value="<?= $broker_profile_data->city ?>" placeholder="* <?= $user_e_language_broker_profile[25] ?>">
												</div>
												<div class="form-group col-sm-6 col-md-4">
													<select name="state_id" class="form-control" id="state">
														<?php $geo_state = DB::getInstance()->query("SELECT * FROM geo_state");
														// Show empty option only if state is still == 0
														echo $broker_profile_data->state_id == 0 ? '<option>* state</option>' : '' ;
														foreach ($geo_state->results() as $geo_state_data) { ?>
															<option value="<?= $geo_state_data->state_id ?>"<?= $broker_profile_data->state_id == $geo_state_data->state_id ? 'selected="selected"' : '' ?>><?= $geo_state_data->abbr . ' - ' . $geo_state_data->name ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="form-group col-sm-6 col-md-3" id="zip_code_holder" style="padding-right:0px;">
													<input name="zip_code" type="text" class="form-control" id="zip_code" placeholder="* Zip code">
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<input type="submit" class="btn btn-primary" value="Add new broker">
											</div>
											<input type="hidden" name="_hp_add_loader_broker" value="1">
	                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div> --> <?php
							} 

							#############
							# Broker ID #
							#############

							elseif ($_GET['id']) { ?>
								<div class="main-box">
									<header class="main-box-header clearfix">
										<small class="pull-right">Added by <?= $user_i_name[$broker_co_user_id_did[$_GET['id']]] . ' ' . $user_i_last_name[$broker_co_user_id_did[$_GET['id']]] ?> on <?= date('M Y', strtotime($broker_co_added_did[$_GET['id']])) ?>.</small>
									</header>
									<div class="main-box-body clearfix">
										<form action="" method="post">
											<div class="col-sm-12 col-md-8">
												<h3>Main</h3>
												<div class="form-group col-sm-12 col-sm-12">
													<label>Company name</label>
													<input name="company_name" class="form-control" type="text" value="<?= $broker_co_name_did[$_GET['id']] ?>">
												</div>
												<div class="form-group col-sm-12 col-sm-6">
													<label>Phone Number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-phone"></i></span>
														<input name="phone_number_01" class="form-control" type="text" value="<?= $broker_co_phone_number_01_did[$_GET['id']] ?>" id="maskedPhoneNumber">
													</div>
												</div>
												<div class="form-group col-sm-12 col-sm-6">
													<label>Fax number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-phone"></i></span>
														<input name="fax_number" class="form-control" type="text" value="<?= $broker_co_fax_number_did[$_GET['id']] ?>"  id="maskedFaxNumber">
													</div>
												</div>
												<div class="form-group col-sm-12 col-md-12">
													<label>Quickpay</label>
													<div class="radio">
														<input type="radio" name="quickpay" id="quickpay1" value="1"<?= $broker_co_quickpay_did[$_GET['id']] == 1 ? ' checked=""' : '' ?>>
														<label for="quickpay1">
															Yes
														</label>
													</div>
													<div class="radio">
														<input type="radio" name="quickpay" id="quickpay2" value="2"<?= $broker_co_quickpay_did[$_GET['id']] == 0 ? ' checked=""' : '' ?>>
														<label for="quickpay2">
															No
														</label>
													</div>
												</div>
												<div class="form-group col-sm-12 col-md-4<?= $broker_co_quickpay_did[$_GET['id']] == 0 ? ' hidden' : '' ?>" id="quickpay_email_holder">
													<label>Quickpay email</label>
													<input name="quickpay_email" class="form-control" type="email" value="<?= $broker_co_quickpay_email_did[$_GET['id']] ?>">
												</div>
												<div class="form-group col-sm-12 col-md-4<?= $broker_co_quickpay_did[$_GET['id']] == 0 ? ' hidden' : '' ?>" id="accounts_payable_number_holder">
													<label>Accounts payable number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-phone"></i></span>
														<input name="accounts_payable_number" class="form-control" type="text" value="<?= $broker_co_accounts_payable_number_did[$_GET['id']] ?>" id="maskedAccountsPayableNumber">
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-4">
												<h3>Address</h3>
												<div class="form-group" id="address_line_1_holder">
													<input name="address_line_1" type="text" class="form-control" id="address_line_1" value="<?= $broker_co_address_line_1_did[$_GET['id']] ?>" placeholder="<?= $user_e_language_broker_profile[23] ?>">
												</div>
												<div class="form-group" id="address_line_2_holder">
													<input name="address_line_2" type="text" class="form-control" id="address_line_2" value="<?= $broker_co_address_line_2_did[$_GET['id']] ?>" placeholder="<?= $user_e_language_broker_profile[24] ?>">
												</div>
												<div class="form-group">
													<input name="address_line_3" type="text" class="form-control" id="address_line_3" value="<?= $broker_co_address_line_3_did[$_GET['id']] ?>" placeholder="Line 3">
												</div>
												<div class="form-group col-sm-6 col-md-5" id="city_holder" style="padding-left:0px;">
													<input name="city" type="text" class="form-control" id="city" value="<?= $broker_co_city_did[$_GET['id']] ?>" placeholder="<?= $user_e_language_broker_profile[25] ?>">
												</div>
												<div class="form-group col-sm-6 col-md-4">
													<select name="state_id" class="form-control" id="state">
														<?php $geo_state = DB::getInstance()->query("SELECT * FROM geo_state");
														// Show empty option only if state is still == 0
														echo $broker_profile_data->state_id == 0 ? '<option></option>' : '' ;
														foreach ($geo_state->results() as $geo_state_data) { ?>
															<option value="<?= $geo_state_data->state_id ?>"<?= $broker_co_state_id_did[$_GET['id']] == $geo_state_data->state_id ? 'selected="selected"' : '' ?>><?= $geo_state_data->abbr . ' - ' . $geo_state_data->name ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="form-group col-sm-6 col-md-3" id="zip_code_holder" style="padding-right:0px;">
													<input name="zip_code" type="text" class="form-control" id="zip_code" value="<?= $broker_co_zip_code_did[$_GET['id']] ?>">
												</div>
											</div>
											<div class="col-sm-12 col-md-4">
												<h3>Status</h3>
												<div class="form-group">
													<div class="radio">
														<input type="radio" name="status" id="status1" value="1"<?= $broker_co_status_did[$_GET['id']] == 1 ? ' checked=""' : '' ?><?= $broker_co_quickpay_did[$_GET['id']] == 1 && (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $broker_co_quickpay_email_did[$_GET['id']]) || $broker_co_accounts_payable_number_did[$_GET['id']] == '' || $lock_quickpay_no_service_fee) ? ' disabled="disabled"' : '' ?>>
														<label for="status1"<?= $broker_co_quickpay_did[$_GET['id']] == 1 && (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $broker_co_quickpay_email_did[$_GET['id']]) || $broker_co_accounts_payable_number_did[$_GET['id']] == '') ? ' data-toggle="tooltip" data-placement="top" title="For quickpay enabled brokers, the fields quickpay email and accounts payable number are required to activate status."' : '' ?>>
															Active
														</label>
													</div>
													<div class="radio">
														<input type="radio" name="status" id="status2" value="2"<?= $broker_co_status_did[$_GET['id']] == 0 ? ' checked=""' : '' ?>>
														<label for="status2">
															Inactive
														</label>
													</div>
													<div class="radio">
														<input type="radio" name="status" id="status3" value="3"<?= $broker_co_status_did[$_GET['id']] == 2 ? ' checked=""' : '' ?>>
														<label for="status3" class="red">
															<b>DO NOT USE</b>
														</label>
													</div>
													<div class="<?= $broker_co_do_not_use_reason_did[$_GET['id']] && $broker_co_status_did[$_GET['id']] == 2  ? '' : 'hidden' ?>" id="doNotUse">
														<label>Please provide a reason</label>
														<input name="do_not_use_reason" id="do_not_use_reason" type="text" class="form-control" value="<?= $broker_co_do_not_use_reason_did[$_GET['id']] ?>">
													</div>
												</div>
											</div>
											<div class="col-sm-12 col-md-12">
												<input type="submit" class="btn btn-primary" value="Update" id="update">
												<?php if ($_GET['delete_loader_broker']) {
													# Display second delete button ?>
													<a href="broker?id=<?= $_GET['id'] ?>&_hp_delete_loader_broker=1" class="btn btn-danger"><span class="fa fa-trash-o"></span> Really, delete?</a> <?php
												} else {
													# Display first delete button ?>
													<a href="broker?id=<?= $_GET['id'] ?>&delete_loader_broker=1#update" class="btn btn-danger"><span class="fa fa-trash-o"></span></a><?php
												} ?>
											</div>
											<input type="hidden" name="_hp_update_loader_broker" value="1">
											<input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div>

								<!-- ###########################
								# Broker Quickpay Service Fees #
								############################ -->

								<div class="main-box<?= $broker_co_quickpay_did[$_GET['id']] == 0 ? ' hidden' : '' ?>" id="service-fee">
									<header class="main-box-header clearfix">
										<h3>Broker Quickpay Service Fees</h3>
									</header>
									<div class="main-box-body clearfix">
										<!-- NEW ITEM FORM -->
										<form action="" method="post">
												<div class="col-sm-12 col-md-3 form-group">
													<input type="number" min="0" step="0.01" name="fee" class="form-control" placeholder="Service fee %">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<select name="method_id" class="form-control">
														<option>Method of payment</option>
														<?php for ($i=1; $i <= $loader_quickpay_method_of_payment_count ; $i++) { ?>
															<option value="<?= $quickpay_method_of_payment_data_id[$i] ?>"><?= $quickpay_method_of_payment_method[$i] ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<input type="number" min="0" name="number_of_days" class="form-control" placeholder="Number of days. 0 = same day">
												</div>
												<div class="col-sm-12 col-md-3 form-group">
													<input type="submit" class="btn btn-primary" value="Add service fee">
												</div>
												<input type="hidden" name="new" value="1">
												<input type="hidden" name="_hp_update_loader_quickpay_service_fee" value="1">
		                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>

										<?php if ($loader_quickpay_service_fee_count) {
											for ($i=1; $i <= $loader_quickpay_service_fee_count ; $i++) { ?>
												<!-- UPDATE ITEM FORM -->
											 	<form action="" method="post">
													<div class="col-sm-12 col-md-3 form-group">
														<input type="number" min="0" step="0.01" name="fee" class="form-control" value="<?= $quickpay_service_fee[$i] ?>">
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<select name="method_id" class="form-control">
															<option>Choose a method of payment</option>
															<?php for ($v=1; $v <= $loader_quickpay_method_of_payment_count ; $v++) { ?>
																<option value="<?= $quickpay_method_of_payment_data_id[$v] ?>"<?= $quickpay_service_method_id[$i] == $quickpay_method_of_payment_data_id[$v] ? ' selected="selected"' : '' ?>><?= $quickpay_method_of_payment_method[$v] ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<input type="number" min="0" name="number_of_days" class="form-control" placeholder="Number of days. 0 = same day" value="<?= $quickpay_service_number_of_days[$i] ?>">
													</div>
													<div class="col-sm-12 col-md-3 form-group">
														<input type="submit" class="btn btn-primary" value="Update">
														<a href="broker?id=<?= $_GET['id'] ?>&_hp_delete_loader_quickpay_service_fee=<?= $quickpay_service_fee_data_id[$i] ?>" class="btn btn-danger"<?= $loader_quickpay_service_fee_count === 1 ? ' data-toggle="tooltip" data-placement="bottom" title="This is the only item on this list, deleting it will remove quickpay from this broker."' : '' ?>><span class="fa fa-trash-o"></span></a>
													</div>
													<input type="hidden" name="data_id" value="<?= $quickpay_service_fee_data_id[$i] ?>">
													<input type="hidden" name="_hp_update_loader_quickpay_service_fee" value="1">
			                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form> <?php
											}
										} ?>
									</div>
								</div> <?php 
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

			document.getElementById('quickpay1').onchange = function(){
				if (document.getElementById('quickpay1').checked) {
					$('#service-fee').removeClass("hidden");
					$('#quickpay_email_holder').removeClass("hidden");
					$('#accounts_payable_number_holder').removeClass("hidden");
				}
			}

			document.getElementById('quickpay2').onchange = function(){
				if (document.getElementById('quickpay2').checked) {
					$('#service-fee').addClass("hidden");
					$('#quickpay_email_holder').addClass("hidden");
					$('#accounts_payable_number_holder').addClass("hidden");
				}
			}

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

			document.getElementById('status3').onchange = function(){
				if (document.getElementById('status3').checked) {
					$('#doNotUse').removeClass("hidden");

					// Disable update button if status "DO NOT USE" is selected and reason is empty
					if (document.getElementById('do_not_use_reason').value == '') {
						document.getElementById("update").disabled = true;
					};
				}
			}

			document.getElementById('do_not_use_reason').onkeydown = function(){
				if (document.getElementById('do_not_use_reason').value != '') {
					document.getElementById("update").disabled = false;
				}
			}
		})();
	</script>
</body>
</html>
