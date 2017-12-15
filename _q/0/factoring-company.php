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
include($_SESSION['ProjectPath']."/includes/db_data/factoring_company.php");

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
									<li><a href="<?= $_SESSION['href_location'] ?>0/factoring-company">Factoring Company</a></li>
									<li class="active"><a href="<?= $_SESSION['href_location'] ?>0/">Add</a></li> <?php
								} elseif ($_GET['factoring_company_id']) { ?>
									<li class="active"><a href="<?= $_SESSION['href_location'] ?>0/factoring-company">Factoring Company</a></li> <?php
								} ?>
							</ol>
							<div class="clearfix">
								
								<?php if (!$_GET) { ?>
									<h1 class="pull-left">Factoring Companies</h1> <?php
								} elseif ($_GET['new']) { ?>
									<h1 class="pull-left">Factoring Company</h1> <?php
								} elseif ($_GET['factoring_company_id']) { ?>
									<h1 class="pull-left"><?= $factoring_company_name[1] ?></h1> <?php
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

							##########################
							# Factoring company List #
							##########################

							if (!$_GET) { ?>

								<div class="main-box no-header">
									<div class="main-box-body clearfix">
										<?php if (!$factoring_company_count) { ?>
											<div class="alert alert-warning" role="alert">
									      There are no factoring companies yet, <a href="factoring-company?new=1">add the first one</a>.
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
														<th>Website</th>
														<th>Invoicing email</th>
														<th>Phone number</th>
													</tr>
												</thead>
												<tbody>
													<?php for ($i=1; $i <= $factoring_company_count ; $i++) { ?>
														<tr>
															<td><a href="factoring-company?factoring_company_id=<?= $factoring_company_data_id[$i] ?>"<?= $factoring_company_status[$i] == 0 ? ' class="red"' : '' ?>><?= $factoring_company_name[$i] ?></a></td>
															<td><?= $factoring_company_uri[$i] ?></td>
															<td><?= $factoring_company_invoicing_email[$i] ?></td>
															<td><?= $factoring_company_phone_number_01[$i] ?></td>
														</tr> <?php
													} ?>
												</tbody>
											</table>
											<ul class="pagination pull-right hide-if-no-paging"></ul> <?php
										} ?>
									</div>
								</div> <?php								
							}

							#########################
							# Add factoring company #
							#########################

							elseif ($_GET['new']) { ?>
								<div class="main-box">
									<header class="main-box-header clearfix">
										<h2>Add new factoring company</h2>
									</header>
									<div class="main-box-body clearfix">
										<form action="" method="post">
											<div class="row">
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Name</label>
													<input name="name" class="form-control" type="text">
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Website</label>
													<input name="uri" class="form-control" type="text">
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Invoicing email</label>
													<input name="invoicing_email" class="form-control" type="text">
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Phone Number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-phone"></i></span>
														<input name="phone_number_01" class="form-control" type="text" id="maskedPhoneNumber">
													</div>
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label>Fax Number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-fax"></i></span>
														<input name="fax" class="form-control" type="text" id="maskedFaxNumber">
													</div>
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Batch/Schedule</label>
													<select name="batch_schedule" class="form-control">
														<option></option>
														<option value="1">Batch</option>
														<option value="2">Schedule</option>
													</select>
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Requires SOAR</label>
													<select name="requires_soar" class="form-control">
														<option value="1">Yes</option>
														<option value="2">No</option>
													</select>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<input type="submit" class="btn btn-primary" value="Add new factoring company">
												</div>
											</div>
											<input type="hidden" name="_hp_add_loader_factoring_company" value="1">
	                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div> <?php
							} 

							########################
							# Factoring company ID #
							########################

							elseif ($_GET['factoring_company_id']) { 
								####################
								# Main information #
								#################### ?>

								<div class="main-box">
									<header class="main-box-header clearfix">
										<small class="pull-right">Added by <?= $user_i_name[$factoring_company_user_id[1]] . ' ' . $user_i_last_name[$factoring_company_user_id[1]] ?> on <?= date('M Y', strtotime($factoring_company_added[1])) ?>.</small>
									</header>

									<div class="main-box-body clearfix">
										<form action="" method="post">
											<div class="row">
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Name</label>
													<input name="name" class="form-control" type="text" value="<?= $factoring_company_name[1] ?>">
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Website</label>
													<input name="uri" class="form-control" type="text" value="<?= $factoring_company_uri[1] ?>">
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Invoicing email</label>
													<input name="invoicing_email" class="form-control" type="text" value="<?= $factoring_company_invoicing_email[1] ?>">
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Phone Number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-phone"></i></span>
														<input name="phone_number_01" class="form-control" type="text" id="maskedPhoneNumber" value="<?= $factoring_company_phone_number_01[1] ?>">
													</div>
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label>Fax Number</label>
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-fax"></i></span>
														<input name="fax" class="form-control" type="text" id="maskedFaxNumber" value="<?= $factoring_company_fax[1] ?>">
													</div>
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Batch/Schedule</label>
													<select name="batch_schedule" class="form-control">
														<option></option>
														<option value="1"<?= $factoring_company_batch_schedule[1] == 1 ? ' selected' : '' ?>>Batch</option>
														<option value="2"<?= $factoring_company_batch_schedule[1] == 2 ? ' selected' : '' ?>>Schedule</option>
													</select>
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label><span class="red">* </span>Requires SOAR</label>
													<select name="requires_soar" class="form-control">
														<option value="1"<?= $factoring_company_requires_soar[1] == 1 ? ' selected' : '' ?>>Yes</option>
														<option value="2"<?= $factoring_company_requires_soar[1] == 0 ? ' selected' : '' ?>>No</option>
													</select>
												</div>
												<div class="form-group col-sm-12 col-md-4">
													<label>Status</label>
													<div class="radio">
														<input type="radio" name="status" id="status1" value="1"<?= $factoring_company_status[1] == 1 ? 'checked' : '' ?><?= $factoring_company_contact_count && $factoring_company_address_count && $factoring_company_service_fee_count ? '' : ' disabled' ?>>
														<label for="status1"<?= $factoring_company_contact_count && $factoring_company_address_count && $factoring_company_service_fee_count ? '' : ' data-toggle="tooltip" data-placement="top" title="' . $factoring_company_name[1] . ' is missing contact, address or service fee information, it cannot be activated."' ?>>
															Active
														</label>
													</div>
													<div class="radio">
														<input type="radio" name="status" id="status2" value="0"<?= $factoring_company_status[1] == 0 ? ' checked=""' : '' ?>>
														<label for="status2">
															Inactive
														</label>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<input type="submit" class="btn btn-primary" value="Update">
												</div>
											</div>
											<input type="hidden" name="_hp_update_loader_factoring_company" value="1">
	                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div>

								<?php 
								################
								# Contact data #
								################ ?>

								<div class="main-box" id="factoring-company-contact">
									<header class="main-box-header clearfix">
										<h2>Contact list</h2>
									</header>
									<div class="main-box-body clearfix">
										<div class="row">
											<div class="col-sm-12 col-md-12">
												<?php if (!$factoring_company_contact_count) { ?>
													<div class="alert alert-warning" role="alert">
											      This company doesn't have any contacts yet.
											    </div> <?php
												} ?>
												<form action="" method="post" class="form-inline">
													<div class="form-group">
														<label class="sr-only">Name</label>
														<input type="text" name="name" class="form-control" placeholder="Name" value="<?= $_GET['name'] ?>">
													</div>
													<div class="form-group">
														<label class="sr-only">Last name</label>
														<input type="text" name="last_name" class="form-control" placeholder="Last name" value="<?= $_GET['last_name'] ?>">
													</div>
													<div class="form-group">
														<label class="sr-only">Title</label>
														<input type="text" name="title" class="form-control" placeholder="Title" value="<?= $_GET['title'] ?>">
													</div>
													<div class="form-group">
														<label class="sr-only">Email</label>
														<input type="text" name="email" class="form-control" placeholder="Email" value="<?= $_GET['email'] ?>">
													</div>
													<div class="form-group">
														<label class="sr-only">Phone number</label>
														<input type="text" name="phone_number_01" class="form-control" placeholder="Phone number" id="maskedContactPhoneNumber" value="<?= $_GET['phone_number_01'] ?>">
													</div>
													<div class="form-group">
														<button type="submit" class="btn btn-warning">Add new contact</button>
													</div>
													<input type="hidden" name="_hp_add_loader_factoring_company_contact" value="1">
			                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form>
											</div>
										</div>
										<?php if ($factoring_company_contact_count) {
											# Display contacts
											for ($i=1; $i <= $factoring_company_contact_count ; $i++) { ?>
												<div class="row">
													<div class="col-sm-12 col-md-12" style="margin-top: 5px;">
														<form action="" method="post" class="form-inline">
															<div class="form-group">
																<label class="sr-only">Name</label>
																<input type="text" name="name" class="form-control" placeholder="Name" value="<?= $factoring_company_contact_name[$i] ?>">
															</div>
															<div class="form-group">
																<label class="sr-only">Last name</label>
																<input type="text" name="last_name" class="form-control" placeholder="Last name" value="<?= $factoring_company_contact_last_name[$i] ?>">
															</div>
															<div class="form-group">
																<label class="sr-only">Title</label>
																<input type="text" name="title" class="form-control" placeholder="Title" value="<?= $factoring_company_contact_title[$i] ?>">
															</div>
															<div class="form-group">
																<label class="sr-only">Email</label>
																<input type="text" name="email" class="form-control" placeholder="Email" value="<?= $factoring_company_contact_email[$i] ?>">
															</div>
															<div class="form-group">
																<label class="sr-only">Phone number</label>
																<input type="text" name="phone_number_01" class="form-control" placeholder="Phone number" id="maskedContactPhoneNumber" value="<?= $factoring_company_contact_phone_number_01[$i] ?>">
															</div>
															<div class="form-group">
																<?php if (!$_GET['contact_id']) {
																	# Show update & delete button if !$_GET['contact_id'] ?>
																	<button type="submit" class="btn btn-primary"><span class="fa fa-save"></span></button>
																 	<a class="btn btn-danger" href="factoring-company?factoring_company_id=<?= $_GET['factoring_company_id'] ?>&contact_id=<?= $factoring_company_contact_data_id[$i] ?>#factoring-company-contact"<?= $factoring_company_contact_count === 1 ? ' data-toggle="tooltip" data-placement="bottom" title="This is the only item on this list, deleting it will set this factoring company as inactive."' : '' ?>><span class="fa fa-trash-o"></span></a> <?php
																} elseif ($factoring_company_contact_data_id[$i] == $_GET['contact_id']) {
																	# Else, if we have a $_GET['contact_id'], match it with $factoring_company_contact_data_id[$i] and display confirm delete button ?>
																	<a style="margin-top: 5px;" class="btn btn-danger" href="factoring-company?factoring_company_id=<?= $_GET['factoring_company_id'] ?>&_hp_delete_loader_factoring_company_contact=<?= $factoring_company_contact_data_id[$i] ?>"><span class="fa fa-trash-o"></span> Confirm</a>
																	<a class="btn btn-link" href="factoring-company?factoring_company_id=<?= $_GET['factoring_company_id'] ?>#factoring-company-contact">Cancel</a> <?php
																} ?>
															</div>
															<input type="hidden" name="contact_data_id" value="<?= $factoring_company_contact_data_id[$i] ?>">
															<input type="hidden" name="_hp_update_loader_factoring_company_contact" value="1">
					                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
														</form>
													</div>
												</div><?php
											} ?> <?php
										} ?>
									</div>
								</div>

								<?php 
								#######################
								# Address information #
								####################### ?>

								<div class="main-box" id="factoring-company-address">
									<header class="main-box-header clearfix">
										<h2>Address list</h2>
									</header>
									<div class="main-box-body clearfix">
										<div class="row">
											<?php if (!$factoring_company_address_count) { ?>
												<div class="alert alert-warning" role="alert">
										      There are no addresses yet.
										    </div> <?php
											} else {
												# Display address list ?>
												<div class="col-sm-12 col-md-8">
												<?php for ($i=1; $i <= $factoring_company_address_count ; $i++) { ?>
													<div class="col-sm-12 col-md-4"<?= (($_GET['address_data_id'] && $_GET['address_data_id'] != $factoring_company_address_data_id[$i]) || ($_GET['delete_address'] && $_GET['delete_address'] != $factoring_company_address_data_id[$i])) ? ' style="display: none;"' : '' ?>>
														<div class="main-box infographic-box colored <?= $factoring_company_address_type[$i] == 1 ?  'purple' : 'green' ?>-bg" style="min-height: 180px;">
															<p><?= $factoring_company_address_type[$i] == 1 ?  'Physical' : 'Mailing' ?></p>
															<p style="line-height: 7px;"><b><?= $factoring_company_address_line_1[$i] ?></b></p>
															<p style="line-height: 7px;"><?= $factoring_company_address_line_2[$i] ?></p>
															<?= $factoring_company_address_line_3[$i] ? '<p style="line-height: 7px;">' . $factoring_company_address_line_3[$i] . '</p>' : '' ?>
															<p style="line-height: 7px;"><?= $factoring_company_address_city[$i] . ', ' . $state_abbr[$factoring_company_address_state_id[$i]] . ' ' . $factoring_company_address_zip_code[$i] ?></p>
															<p>
																<a class="btn btn-primary btn-xs<?= $_GET['delete_address'] ? ' hidden' : '' ?>" href="factoring-company?factoring_company_id=1&address_data_id=<?= $factoring_company_address_data_id[$i] ?>&address_type=<?= $factoring_company_address_type[$i] ?>&line_1=<?= $factoring_company_address_line_1[$i] ?>&line_2=<?= $factoring_company_address_line_2[$i] ?>&line_3=<?= $factoring_company_address_line_3[$i] ?>&city=<?= $factoring_company_address_city[$i] ?>&state_id=<?= $factoring_company_address_state_id[$i] ?>&zip_code=<?= $factoring_company_address_zip_code[$i] ?>#factoring-company-address"><span class="fa fa-edit"></span> Edit</a>
																<a class="btn btn-danger btn-xs" href="factoring-company?factoring_company_id=<?= $_GET['factoring_company_id'] ?>&<?= $_GET['delete_address'] ? '_hp_delete_loader_factoring_company_address' : 'delete_address' ?>=<?= $factoring_company_address_data_id[$i] ?>#factoring-company-address"<?= $factoring_company_address_count === 1 ? ' data-toggle="tooltip" data-placement="bottom" title="This is the only item on this list, deleting it will set this factoring company as inactive."' : '' ?>><span class="fa fa-trash-o"></span> <?= $_GET['delete_address'] ? ' Confirm delete' : 'Delete' ?></a>
																<a class="btn btn-link<?= $_GET['delete_address'] ? '' : ' hidden' ?>" href="factoring-company?factoring_company_id=<?= $_GET['factoring_company_id'] ?>#factoring-company-address">Cancel</a>
															</p>
														</div>
													</div> <?php
												} ?>
												</div><?php
											} ?>
											<div class="col-sm-12 col-md-4">
												<?php if ($_GET['address_data_id']) {
													# Display form for address being edited ?>
													<div class="panel panel-primary">
													  <div class="panel-heading">
													    Update address
													  </div>
													  <form action="" method="post">
													  	<div class="panel-body">
														    <div class="form-group">
														    	<label class="sr-only">Address type</label>
																	<select name="address_type" class="form-control">
																		<option value="">* Address type</option>
																		<option value="1"<?= $_GET['address_type'] == 1 ? 'selected' : '' ?>>Physical</option>
																		<option value="2"<?= $_GET['address_type'] == 2 ? 'selected' : '' ?>>Mailing</option>
																	</select>
																</div>
																<div class="form-group">
																	<label class="sr-only">Line 1</label>
																	<input type="text" name="line_1" class="form-control" placeholder="* Line 1" value="<?= $factoring_company_name[1] ?>">
																</div>
																<div class="form-group">
																	<label class="sr-only">Line 2</label>
																	<input type="text" name="line_2" class="form-control" placeholder="* Line 2" value="<?= $_GET['line_2'] ?>">
																</div>
																<div class="form-group">
																	<label class="sr-only">Line 3</label>
																	<input type="text" name="line_3" class="form-control" placeholder="Line 3" value="<?= $_GET['line_3'] ?>">
																</div>
																<div class="row">
																	<div class="form-group col-sm-12 col-md-4">
																		<label class="sr-only">City</label>
																		<input type="text" name="city" class="form-control" placeholder="* City" value="<?= $_GET['city'] ?>">
																	</div>
																	<div class="form-group col-sm-12 col-md-4">
																		<label class="sr-only">State</label>
																		<select name="state_id" style="width:100%" id="state_selector" class="form-control">
																			<option>* State</option>
																			<?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
																				<option value="<?= $i ?>"<?= $i == $_GET['state_id'] ? 'selected' : '' ?>><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
																			} ?>
																		</select>
																	</div>
																	<div class="form-group col-sm-12 col-md-4">
																		<label class="sr-only">Zip code</label>
																		<input type="text" name="zip_code" class="form-control" placeholder="* Zip code" value="<?= $_GET['zip_code'] ?>">
																		<small class="pull-right" style="color: #888; margin-top: 10px;">* required</small>
																	</div>
																</div>
														  </div>
														  <div class="panel-footer">
														    <button class="btn btn-primary">Update address</button>
														    <a class="pull-right btn btn-link red" href="factoring-company?factoring_company_id=<?= $_GET['factoring_company_id'] ?>#factoring-company-address">Cancel</a>
														  </div>
															<input type="hidden" name="address_data_id" value="1">
															<input type="hidden" name="_hp_update_loader_factoring_company_address" value="1">
							                <input type="hidden" name="token" value="<?= $csrfToken ?>">
														</form>
													</div> <?php
												} else {
													# Display new address form
													# Hide on delete confirmation ?>
													<div class="panel panel-info<?= $_GET['delete_address'] ? ' hidden' : '' ?>">
													  <div class="panel-heading">
													    Add new address
													  </div>
													  <form action="" method="post">
													  	<div class="panel-body">
														    <div class="form-group">
														    	<label class="sr-only">Address type</label>
																	<select name="address_type" class="form-control">
																		<option value="">* Address type</option>
																		<option value="1">Physical</option>
																		<option value="2">Mailing</option>
																	</select>
																</div>
																<div class="form-group">
																	<label class="sr-only">Line 1</label>
																	<input type="text" name="line_1" class="form-control" placeholder="* Line 1" value="<?= $factoring_company_name[1] ?>">
																</div>
																<div class="form-group">
																	<label class="sr-only">Line 2</label>
																	<input type="text" name="line_2" class="form-control" placeholder="* Line 2">
																</div>
																<div class="form-group">
																	<label class="sr-only">Line 3</label>
																	<input type="text" name="line_3" class="form-control" placeholder="Line 3">
																</div>
																<div class="row">
																	<div class="form-group col-sm-12 col-md-4">
																		<label class="sr-only">City</label>
																		<input type="text" name="city" class="form-control" placeholder="* City">
																	</div>
																	<div class="form-group col-sm-12 col-md-4">
																		<label class="sr-only">State</label>
																		<select name="state_id" style="width:100%" id="state_selector" class="form-control">
																			<option>* State</option>
																			<?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
																				<option value="<?= $i ?>"><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
																			} ?>
																		</select>
																	</div>
																	<div class="form-group col-sm-12 col-md-4">
																		<label class="sr-only">Zip code</label>
																		<input type="text" name="zip_code" class="form-control" placeholder="* Zip code">
																		<small class="pull-right" style="color: #888; margin-top: 10px;">* required</small>
																	</div>
																</div>
														  </div>
														  <div class="panel-footer">
														    <button class="btn btn-warning">Add</button>
														  </div>
															<input type="hidden" name="_hp_add_loader_factoring_company_address" value="1">
							                <input type="hidden" name="token" value="<?= $csrfToken ?>">
														</form>
													</div> <?php
												} ?>
											</div>
										</div>
									</div>
								</div>

								<?php 
								################
								# Service fees #
								################ ?>

								<div class="main-box" id="factoring-company-service-fee">
									<header class="main-box-header clearfix">
										<h2>Service fees</h2>
									</header>
									<div class="main-box-body clearfix">
										<?php if (!$factoring_company_service_fee_count) { ?>
											<div class="row">
												<div class="col-sm-12 col-md-12">
													<div class="alert alert-warning" role="alert">
											      There are no service fees yet.
											    </div>
												</div>
											</div> <?php
										} ?>
										<!-- NEW ITEM FORM -->
										<div class="row">
											<form action="" method="post">
												<div class="col-sm-12 col-md-3 form-group">
													<input type="number" min="0" step="0.01" name="fee" class="form-control" placeholder="Service fee">
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
												<input type="hidden" name="_hp_add_loader_factoring_company_service_fee" value="1">
		                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
										</div>

										<?php
										if ($factoring_company_service_fee_count) {
											for ($i=1; $i <= $factoring_company_service_fee_count ; $i++) { ?>
												<!-- UPDATE ITEM FORM -->
												<div class="row">
													<form action="" method="post">
														<div class="col-sm-12 col-md-3 form-group">
															<input type="number" min="0.01" step="0.01" name="fee" class="form-control" value="<?= $factoring_company_service_fee_fee[$i] ?>">
														</div>
														<div class="col-sm-12 col-md-3 form-group">
															<select name="method_id" class="form-control">
																<option>Choose a method of payment</option>
																<?php for ($v=1; $v <= $loader_quickpay_method_of_payment_count ; $v++) { ?>
																	<option value="<?= $quickpay_method_of_payment_data_id[$v] ?>"<?= $factoring_company_service_fee_method_id[$i] == $quickpay_method_of_payment_data_id[$v] ? ' selected="selected"' : '' ?>><?= $quickpay_method_of_payment_method[$v] ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-3 form-group">
															<input type="number" min="0" name="number_of_days" class="form-control" placeholder="Number of days. 0 = same day" value="<?= $factoring_company_service_fee_number_of_days[$i] ?>">
														</div>
														<div class="col-sm-12 col-md-3 form-group">
															<input type="submit" class="btn btn-primary" value="Update">
															<a href="factoring-company?factoring_company_id=<?= $_GET['factoring_company_id'] ?>&_hp_delete_loader_factoring_company_service_fee=<?= $factoring_company_service_fee_data_id[$i] ?>" class="btn btn-danger"<?= $factoring_company_service_fee_count === 1 ? ' data-toggle="tooltip" data-placement="bottom" title="This is the only item on this list, deleting it will set this factoring company as inactive."' : '' ?>><span class="fa fa-trash-o"></span></a>
														</div>
														<input type="hidden" name="data_id" value="<?= $factoring_company_service_fee_data_id[$i] ?>">
														<input type="hidden" name="_hp_update_loader_factoring_company_service_fee" value="1">
				                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
													</form>
												</div> <?php
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

			$("#maskedPhoneNumber").mask("(999) 999-9999? x99999");
			$("#maskedFaxNumber").mask("(999) 999-9999");
			$("#maskedContactPhoneNumber").mask("(999) 999-9999? x99999");
		})();
	</script>
</body>
</html>
