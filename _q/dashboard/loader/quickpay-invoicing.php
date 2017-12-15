<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_underscore = str_replace('-', '_', basename(__FILE__, '.php'));

$driver_manager_email = $user_list_id_email[$_POST['driver_manager_id']];

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");

?>

<div class="row">
	<div class="col-sm-12 col-md-12">

		<div class="panel panel-default">
			<div class="panel-body">

				<form action="" method="post">
				  <div class="form-group input-group">
	          <span class="input-group-addon">To</span>
	          <input type="text" name="to" class="form-control" value="<?= $_POST['broker_quickpay_email'] ?>">
		      </div>
				  <div class="form-group input-group">
	          <span class="input-group-addon">Cc</span>
	          <input type="text" name="cc" class="form-control" value="<?= $driver_manager_email ?>, admin@logisticsfrog.com">
		      </div>
				  <!-- <div class="form-group input-group">
	          <span class="input-group-addon">Bcc</span>
	          <input type="text" name="bcc" class="form-control">
		      </div> -->
		      <div class="form-group input-group">
	          <span class="input-group-addon">Subject</span>
	          <input type="text" name="subject" class="form-control" value="<?= 'QUICKPAY - Load #' . $_POST['load_number'] . ' - Invoice #' . ($quickpay_invoice_counter + 1) . ' - ' . $client_id_company_name[$_GET['client_id']] . '.' ?>">
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

				  <div class="form-group text-right">
	          <button type="submit" class="btn btn-primary">Send</button>
	          <a style="margin: 10px 0 0 10px;" href="<?= $_SERVER['HTTP_REFERER'] ?>">Go back</a>
		      </div>
				  <input type="hidden" name="_controller_quickpay_invoice" value="send">
				  <input type="hidden" name="token" value="<?= $csrfToken ?>">
				</form>
			</div>
		</div>

	</div>
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
