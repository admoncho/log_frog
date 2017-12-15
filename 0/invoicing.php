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

# Get invoices
if (!$_GET['new']) {
	# Hide from new invoice form
	if (!$_GET) {
		# Lists all invoices
		$invoice = DB::getInstance()->query("SELECT * FROM invoice ORDER BY status ASC, added DESC");
	} elseif ($_GET['invoice_id']) {
		# Get invoice_id
		$invoice = DB::getInstance()->query("SELECT * FROM invoice WHERE data_id = " . $_GET['invoice_id']);
	}

	$invoice_count = $invoice->count();
	$invoice_counter = 1;

	if ($invoice_count) {
		foreach ($invoice->results() as $invoice_data) {
			$invoice_manager[$invoice_counter] = $invoice_data->manager;
			$invoice_data_id[$invoice_counter] = $invoice_data->data_id;
			$invoice_quantity[$invoice_counter] = $invoice_data->quantity;
			$invoice_description[$invoice_counter] = html_entity_decode($invoice_data->description);
			$invoice_amount[$invoice_counter] = $invoice_data->amount;
			$invoice_status[$invoice_counter] = $invoice_data->status;
			$invoice_added[$invoice_counter] = date('M d, Y', strtotime($invoice_data->added));
			$invoice_paid[$invoice_counter] = date('M d, Y', strtotime($invoice_data->paid));
			$invoice_counter++;
		}
	}
}

# Get list of owners/(owner/operators) that belong to active companies
$manager = DB::getInstance()->query("SELECT user_e_profile_client_user.user_id, 
																			user_e_profile_client_user.client_id
																			FROM user_e_profile_client_user 
																			INNER JOIN user_e_profile_client 
																			ON user_e_profile_client_user.client_id=user_e_profile_client.data_id 
																			WHERE user_type != 2 && status = 1");
$manager_count = $manager->count();
$manager_counter = 1;

if ($manager_count) {
	foreach ($manager->results() as $manager_count_data) {
		$manager_user_id[$manager_counter] = $manager_count_data->user_id;
		$manager_client_id[$manager_counter] = $manager_count_data->client_id;
		$manager_counter++;
	}
}

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
									<li><a href="<?= $_SESSION['href_location'] ?>0/invoicing">Invoicing</a></li>
									<li class="active">Add</li> <?php
								} elseif ($_GET['invoice_id']) { ?>
									<li class="active"><a href="<?= $_SESSION['href_location'] ?>0/invoicing">Invoicing</a></li>
									<li class="active">Invoice</li> <?php
								} ?>
							</ol>
							<div class="clearfix">
								
								<?php if (!$_GET) { ?>
									<h1 class="pull-left">Invoicing</h1> <?php
								} elseif ($_GET['new']) { ?>
									<h1 class="pull-left">Invoice</h1> <?php
								} elseif ($_GET['invoice_id']) { ?>
									<h1 class="pull-left"># <?= $_GET['invoice_id'] ?></h1> <?php
								} ?>

								<div class="pull-right top-page-ui">
									<?php 
									if (!$_GET['new']) {
										include($_SESSION['ProjectPath']."/includes/module-new-item-link.php");	
									} else { ?>
										<a style="margin:0 5px;" href="invoicing" class="btn btn-danger"><i class="fa fa-sign-out"></i> Cancel</a> <?php
									}
									include($_SESSION['ProjectPath']."/includes/module-refresh-link.php"); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12">
							<?php 

							################
							# Invoice List #
							################

							if (!$_GET) { ?>

								<div class="main-box clearfix">
									<header class="main-box-header clearfix">
										<?php if ($invoice_count) { ?>
											<div class="filter-block pull-right">
												<div class="form-group pull-left">
													<input type="text" id="filter" class="form-control" placeholder="Search...">
													<i class="fa fa-search search-icon"></i>
												</div>
											</div> <?php 
										} else { ?>
											<div class="alert alert-warning">
												<i class="fa fa-warning fa-fw fa-lg"></i>
												There are no invoices to display.
											</div> <?php
										} ?>
									</header>
									<div class="main-box-body clearfix">
										<?php if ($invoice_count) { ?>
											<table class="table footable toggle-circle-filled" data-page-size="10" data-filter="#filter" data-filter-text-only="true">
												<thead>
													<tr>
														<th>Invoice ID</th>
														<th>Added</th>
														<th>Paid</th>
														<th>Client</th>
														<th>Status</th>
														<th class="text-right">Amount</th>
													</tr>
												</thead>
												<tbody>
													<?php for ($i=1; $i <= $invoice_count ; $i++) { ?>
														<tr>
															<td><a class="btn btn-primary btn-xs" href="invoicing?invoice_id=<?= $invoice_data_id[$i] ?>"><?= $invoice_data_id[$i] ?></a></td>
															<td><?= $invoice_added[$i] ?></td>
															<td><?= $invoice_paid[$i] != 'Nov 30, -0001' ? $invoice_paid[$i] : '' ?></td>
															<td><?= $_QU_e_name[$invoice_manager[$i]] . ' ' . $_QU_e_last_name[$invoice_manager[$i]] ?></td>
															<td><span class="label label-<?= $invoice_status[$i] == 1 ? 'success' : 'warning' ?>"><?= $invoice_status[$i] == 1 ? 'Paid' : 'Pending' ?></span></td>
															<td class="text-right">&dollar; <?= number_format($invoice_amount[$i] * $invoice_quantity[$i], 2) ?></td>
														</tr> <?php
													} ?>
												</tbody>
											</table>
											<ul class="pagination pull-right hide-if-no-paging"></ul> <?php 
										} ?>
									</div>
								</div> <?php								
							}

							###############
							# Add invoice #
							###############

							# Adding invoices from this form sets an user_id on the newly created invoice, this parameter is 
							# then used to know that it was created by a user instead of a cron job

							elseif ($_GET['new']) { ?>
								<div class="main-box no-header">
									<div class="main-box-body clearfix">
										<form action="" method="post">
											<div class="row">
												<div class="form-group form-group-select2 col-sm-12 col-md-3">
													<select name="manager" style="width:100%;" id="manager_selector">
														<option value="">Manager</option>
														<?php for ($i=1; $i <= $manager_count ; $i++) { ?>
															<option value="<?= $manager_user_id[$i] ?>"><?= $_QU_e_name[$manager_user_id[$i]] . ' ' . $_QU_e_last_name[$manager_user_id[$i]] ?></option> <?php
														} ?>
													</select>
												</div>
												<div class="form-group col-sm-12 col-md-5">
													<input type="text" name="description" class="form-control" placeholder="Description">
												</div>
												<div class="form-group col-sm-12 col-md-2">
													<input type="number" name="amount" class="form-control" placeholder="Amount" min="1">
												</div>
												<div class="form-group col-sm-12 col-md-2">
													<button type="submit" class="btn btn-success">Create</button>
												</div>
											</div>
											<input type="hidden" name="_hp_add_invoice" value="1">
											<input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div> <?php
							} 

							##############
							# Invoice ID #
							##############

							elseif ($_GET['invoice_id']) { ?>
								<div class="main-box clearfix">
									<header class="main-box-header clearfix">
										<h2 class="pull-left">Invoice no. <?= $_GET['invoice_id'] ?></h2>
										<?php if ($invoice_status[1] == 0) {
											# Show mark as paid option if status == 0 (unpaid)
											if (!$_GET['mark_paid']) {
												# Show first mark as paid button ?>
												<a class="btn btn-primary pull-right" href="invoicing?invoice_id=<?= $_GET['invoice_id'] ?>&mark_paid=1">Mark as paid</a> <?php
											} else {
												# Show confirmation button ?>
												<a class="btn btn-link pull-right red" href="invoicing?invoice_id=<?= $_GET['invoice_id'] ?>">Cancel</a>
												<a class="btn btn-primary pull-right" href="invoicing?invoice_id=<?= $_GET['invoice_id'] ?>&_hp_update_invoice=1">Confirm payment of invoice #<?= $_GET['invoice_id'] ?></a> <?php
											}
										} ?>
									</header>
									
									<div class="main-box-body clearfix">
										<div id="invoice-companies" class="row">
											<div class="col-sm-4 invoice-box">
												<div class="invoice-icon hidden-sm">
													<i class="fa fa-home"></i> From
												</div>
												<div class="invoice-company">
													<h4>logisticsfrog.com</h4>
												</div>
											</div>
											<div class="col-sm-4 invoice-box">
												<div class="invoice-icon hidden-sm">
													<i class="fa fa-truck"></i> To
												</div>
												<div class="invoice-company">
													<h4><?= $_QU_e_name[$invoice_manager[1]] . ' ' . $_QU_e_last_name[$invoice_manager[1]] ?></h4>
												</div>
											</div>
											<div class="col-sm-4 invoice-box invoice-box-dates">
												<div class="invoice-dates">
													<div class="invoice-number clearfix">
														<strong>Invoice no.</strong>
														<span class="pull-right"><?= $_GET['invoice_id'] ?></span>
													</div>
													<div class="invoice-date clearfix">
														<strong>Invoice date:</strong>
														<span class="pull-right"><?= $invoice_added[1] ?></span>
													</div>
													<div class="invoice-date invoice-due-date clearfix">
														<strong>Paid date:</strong>
														<span class="pull-right"><?= $invoice_paid[1] != 'Nov 30, -0001' ? $invoice_paid[1] : '' ?></span>
													</div>
												</div>
											</div>
										</div>
										
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th><span>Description</span></th>
														<th class="text-center"><span>Quantity</span></th>
														<th class="text-center"><span>Unit price</span></th>
														<th class="text-center"><span>Total</span></th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td>
															<?= $invoice_description[1] ?>
														</td>
														<td class="text-center">
															<?= $invoice_quantity[1] ?>
														</td>
														<td class="text-center">
															&dollar; <?= $invoice_amount[1] ?>
														</td>
														<td class="text-center">
															&dollar; <?= number_format(($invoice_amount[1] * $invoice_quantity[1]), 2) ?>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										
										<div class="invoice-box-total clearfix">
											<div class="row grand-total">
												<div class="col-sm-9 col-md-10 col-xs-6 text-right invoice-box-total-label">
													Total
												</div>
												<div class="col-sm-3 col-md-2 col-xs-6 text-right invoice-box-total-value">
													&dollar; <?= number_format(($invoice_amount[1] * $invoice_quantity[1]), 2) ?>
												</div>
											</div>
										</div>										
									</div>
								</div>
								 <?php 
							} ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- global scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.nanoscroller.min.js"></script>
	<!-- this page specific scripts -->_
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

			if ($_GET['new']) { ?>
				$('#manager_selector').select2(); <?php
			}  ?>

			$('.footable').footable();
		})();
	</script>
</body>
</html>