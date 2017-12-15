<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath']. '/core/init.php';

# Include db/broker.php here temporarily until new process is made for quickpay invoicing
include($_SESSION['ProjectPath'] . "/resource/library/quantum/module/broker/db/broker.php");

# Limbo
include($_SESSION['ProjectPath']."/includes/limbo.php");

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

# Include mPDF Class
include($_SESSION['ProjectPath'] . "/mpdf/mpdf.php");

# Create new mPDF Document
$mpdf = new mPDF();

# SPL-compatible autoloader for PHPMailer
require($_SESSION['ProjectPath'] . "/PHPMailer/PHPMailerAutoload.php");

# Create new PHPMailer instance
$mail = new PHPMailer;

# THIS CODE BELOW COULD BE SOMEWHERE ELSE
$broker_quickpay_email = $quickpay_broker_quick_pay_email_by_data_id[$_GET['broker_id']];
$driver_manager_email = $_QU_e_email[$_GET['driver_manager_id']];

# Save invoice_color in var
$limbo_client_invoice_color_by_data_id[$_GET['client_id']] ? $invoice_color = $limbo_client_invoice_color_by_data_id[$_GET['client_id']] : $invoice_color = 'b00b00';

# Get other charges
$load_other_charges = DB::getInstance()->query("SELECT * FROM loader_load_other_charges WHERE load_id = " . $_GET['load_id'] . " ORDER BY price DESC");
$load_other_charges_count = $load_other_charges->count();
$load_other_charges_counter = 1;
if ($load_other_charges_count) {
	foreach ($load_other_charges->results() as $load_other_charges_data) {
		$load_other_charges_data_id[$load_other_charges_counter] = $load_other_charges_data->data_id;
		$load_other_charges_item[$load_other_charges_counter] = $load_other_charges_data->item;
		$load_other_charges_price[$load_other_charges_counter] = $load_other_charges_data->price;

		$load_other_charges_counter++;
	}
}

# Get subtotal
$sum = 0;
for ($i = 1; $i <= $load_other_charges_count ; $i++) {
	$sum+= $load_other_charges_price[$i];
} 

$subtotal = $line_haul + $sum;

# Controller calls (KEEP THEM BELOW ABOVE VARIABLES)
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
								<li class="active">Quickpay Invoicing</li>
							</ol>
							<div class="clearfix">
								<h1 class="pull-left">Quickpay Invoicing</h1>
								<div class="pull-right top-page-ui">
									<a style="margin:0 5px;" href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i> Cancel</a>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12 col-md-12">
							<div class="main-box no-header clearfix">
								<div class="main-box-body clearfix">
									<form action="" method="post">
									  <div class="form-group input-group">
						          <span class="input-group-addon">To</span>
						          <input type="text" name="to" class="form-control" value="<?= $broker_quickpay_email ?>">
							      </div>
									  <div class="form-group input-group">
						          <span class="input-group-addon">Cc</span>
						          <input type="text" name="cc" class="form-control" value="<?= $driver_manager_email ?>, admin@logisticsfrog.com">
							      </div>
									  <div class="form-group input-group">
						          <span class="input-group-addon">Bcc</span>
						          <input type="text" name="bcc" class="form-control">
							      </div>
							      <div class="form-group input-group">
						          <span class="input-group-addon">Subject</span>
						          <input type="text" name="subject" class="form-control" value="<?= 'QUICKPAY - Load #'. $load_number .' - Invoice #' . ($quickpay_invoice_counter + 1) . ' - ' . $limbo_client_company_name_by_data_id[$_GET['client_id']] . '.' ?>">
							      </div>
							      <div class="form-group">
							      	<textarea id="body" name="body" rows="10" cols="80">
							      		<p>Good day,</p>
												<p>I'm attaching the following documents for quick pay:</p>
												<ul>
													<li>Signed rate confirmation</li>
													<li>Bol</li>
													<li>Invoice</li>
												</ul>
												<p>Should you need anything else from my end to process this payment, please do not hesitate to contact me.</p>
												<p>Best regards.</p>
							      	</textarea>
							      </div>



							      <?php 
echo '
		<h2 style="text-align: right;">Quickpay - Load #' . $load_number . '</h2>
		<div style="float: left; width: 33%; text-align: center; margin: 0 ! important; height: 85px; background: #' . $invoice_color . '; color: #fff;">
			<h2>' . $limbo_client_company_name_by_data_id[$_GET['client_id']] . '</h2>
			<p style="margin: 0;">MC# ' . $limbo_client_mc_number_by_data_id[$_GET['client_id']] . '</p>
		</div>
		<div style="float: left; width: 33%; text-align: center; margin: 0 ! important; height: 85px; background: #a6a6a6; color: #fff;">
			<h1>INVOICE</h1>
		</div>
		<div style="float: left; width: 33%; background: #a6a6a6; margin: 0 ! important; height: 85px; background: #a6a6a6; color: #fff;">
			<p style="text-align: right; margin: 10px 10px 3px 0;">908-731-6031</p>
			<p style="text-align: right; margin: 0 10px 3px 0;">admin@logisticsfrog.com</p>
			<p style="text-align: right; margin: 0 10px 3px 0;">F 908-750-5529</p>
		</div>
		<p style="text-align: right; margin-right: 10px; margin-top: 10px;"><b>Invoice Date:</b> ' . date('m/d/Y') . '</p>
		<p style="text-align: right; margin-right: 10px ; margin-bottom: 10px;"><b>Invoice number:</b> ' . ($quickpay_invoice_counter + 1) . '</p>
		<ul style="min-height: 110px; list-style-type: none; clear: both;">
			<li style="float: left; width: 66%;">
				<p><b>Bill to:</b></p>
				' . $quickpay_broker_address_line_1_by_data_id[1] . ',<br> ' . $quickpay_broker_address_line_2_by_data_id[1] . '<br>
				' . $quickpay_broker_city_by_data_id[1] . ', ' . $state_name[$quickpay_broker_state_id_by_data_id[1]] . '
			</li>
			<li style="float: left; width: 33%; background: #' . $invoice_color . '; color: #fff; text-align: center;">
				<p>Amount due:</p>
				<h2><i>$' . number_format($subtotal - (($subtotal / 100) * $invoice_service_fee_fee), 2) . '</i></h2>
			</li>
		</ul>
		<div style="margin-bottom: 10px; margin: 10px; border: 1px solid #888888; clear: both; min-height: 80px;">
			<h3 style="background: #' . $invoice_color . '; color: #fff; text-align: center; margin: 0;">Line haul</h3>
			<ul style="min-height: 110px; list-style-type: none; clear: both;">
				<li style="float: left; width: 50%; text-align: center;">';
					for ($i = 1; $i <= $loader_checkpoint_pick_up_count ; $i++) { 
						echo '<p style="margin: 3px 0;">' . $checkpoint_pick_up_city[$i] . ', ' . $state_abbr[$checkpoint_pick_up_state_id[$i]] . '</p>';
					}
				echo '</li>
				<li style="float: left; width: 50%; text-align: center;">'; 
					for ($i = 1; $i <= $loader_checkpoint_drop_off_count ; $i++) { 
						echo '<p style="margin: 3px 0;">' . $checkpoint_drop_off_city[$i] . ', ' . $state_abbr[$checkpoint_drop_off_state_id[$i]] . '</p>';
					}
				echo '</li>
			</ul>
		</div>
		<table class="invoice-details" style="width:100%; border-collapse: collapse;">
		  	<tr style="border: 1px solid #' . $invoice_color . '; background: #' . $invoice_color . '; color: #fff;">
			    <th style="color: #fff; padding: 3px 5px;">Item</th>
			    <th style="color: #fff; padding: 3px 5px;">Rate</th>
		  	</tr>
		  	<tr style="border: 1px solid #888888;">
			    <td style="padding: 3px 5px;">Line haul</td>
			    <td style="text-align: right; padding: 3px 5px;">$ ' . number_format($line_haul, 2) . '</td>
		  	</tr>';
		  	if ($load_other_charges_count) {
				for ($i = 1; $i <= $load_other_charges_count ; $i++) { ?>
				 	<tr style="border: 1px solid #888888;">
						<td style="padding: 3px 5px;">
							<?= $load_other_charges_item[$i] ?>
						</td>
						<td style="text-align: right; padding: 3px 5px;">
							$ <?= number_format($load_other_charges_price[$i], 2) ?>
						</td>
					</tr> <?php
				}
			}
		echo '</table>
		<table style="width:100%; margin-top: 20px;">
		  	<tr style="text-align: right;">
			    <td><b>Subtotal:</b></td>
			    <td style="text-align: right">$' . number_format($subtotal, 2) . '</td>
		  	</tr>
		  	<tr style="text-align: right;">
			    <td><b>Service charge (' . $invoice_service_fee_fee . '%):</b></td>
			    <td style="text-align: right">($' . number_format(($subtotal / 100) * $invoice_service_fee_fee, 2) . ')</td>
		  	</tr>
		  	<tr style="text-align: right;">
			    <td><h2>Total:</h2></td>
			    <td style="text-align: right"><h2><i>$' . number_format($subtotal - (($subtotal / 100) * $invoice_service_fee_fee), 2) . '</i></h2></td>
		  	</tr>
		</table>
		<p>If sending paper checks, please send them to our mailing address:</p>
		' . $client_billing_address_line_1[$_GET['client_id']] . '<br> ' . $client_billing_address_line_2[$_GET['client_id']] . '<br>
		' . $client_billing_address_city[$_GET['client_id']] . ', ' . $state_name[$client_billing_address_state_id[$_GET['client_id']]] . ' ' . $client_billing_address_zip_code[$_GET['client_id']] . '
		<img src="'. $_SESSION['HtmlDelimiter'] .'img/imager/logo.jpg" width="63" height="54" style="float: right;">
		<div style="margin: 80px 0 10px 0; background: #' . $invoice_color . '; color: #fff;">
			<p style="text-align: center; margin: 5px;">THANK YOU FOR YOUR BUSINESS</p>
		</div>
		<p style="text-align: right;">Don\'t forget to visit logisticsfrog.com to check our truck availability!</p>';

?>


									  <div class="form-group text-right">
						          <button type="submit" class="btn btn-primary">Send</button>
						          <a style="margin: 10px 0 0 10px;" href="<?= $_SERVER['HTTP_REFERER'] ?>">Go back</a>
							      </div>
									  <input type="hidden" name="_hp_send_quickpay_invoice" value="1">
									  <input type="hidden" name="token" value="<?= $csrfToken ?>">
									</form>
								</div>
							</div>
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
			} ?>

			// CKEditor
			CKEDITOR.replace('body');
		})();
	</script>
</body>
</html>