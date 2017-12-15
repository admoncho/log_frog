<?php
session_start();
ob_start();
# SPL-compatible autoloader for PHPMailer;
require($_SESSION['ProjectPath'] . "/PHPMailer/PHPMailerAutoload.php");

# Create new PHPMailer instance
$mail = new PHPMailer;

# Mail recipients
$mail->From = 'admin@logisticsfrog.com';
$mail->FromName = 'logisticsfrog.com';
$mail->addReplyTo('admin@logisticsfrog.com', '');

# Separate to items and loop through them to create $mail->AddAddress($to_items[$i]); for each one
$to = Input::get('to');
$to_items = explode(",", str_replace(' ', '', $to));
$to_items_count = count($to_items);
# Check for invalid emails on the $to_items array
for ($i = 0; $i < $to_items_count ; $i++) {

	# Check if email address is valid
	$valid = phpMailer::ValidateAddress($to_items[$i]);
	# Not valid? Throw error and redirect back
	$valid == false ? Session::flash('send_schedule_error', 'All email addresses must be valid.') . Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&fee_option=' . $_GET['fee_option']) : '';
	# Else, go on
	$mail->AddAddress($to_items[$i]);
}

# cc is not required, an empty string throws an error, so validation runs only if input value is set
if (Input::get('cc')) {
	# Separate cc items and loop through them to create $mail->addCC($cc_items[$i]); for each one
	$cc = Input::get('cc');
	$cc_items = explode(",", str_replace(' ', '', $cc));
	$cc_items_count = count($cc_items);
	# Check for invalid emails on the $cc_items array
	for ($i = 0; $i < $cc_items_count ; $i++) {

		# Check if email address is valid
		$valid = phpMailer::ValidateAddress($cc_items[$i]);
		# Not valid? Throw error and redirect back
		$valid == false ? Session::flash('send_schedule_error', 'All email addresses must be valid.') . Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&fee_option=' . $_GET['fee_option']) : '';
		# Else, go on
		$mail->addCC($cc_items[$i]);
	}
}

# Set attachments path

# soar
$soar = $schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf';

# TAFS invoice
$tafs_invoice = $schedule_directory . $first_invoice_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf';

# Add attachment
$mail->addAttachment($soar);
$mail->addAttachment($tafs_invoice);

if (file_exists($tafs_invoice)) {
	
	# Add bol and ratecon
	$rate_con = $file_directory . 'rate-confirmation-' . $load_list_entry_id[1] . '-' . $load_list_load_id[1] . '.pdf';
	$bol = $file_directory . 'bol-' . $load_list_entry_id[1] . '-' . $load_list_load_id[1] . '.pdf';
	
	$mail->addAttachment($rate_con);
	$mail->addAttachment($bol);
}

# Set attachment path for every invoice
for ($i=1; $i <= $load_list_count ; $i++) { 

	# Declare file name
	$pre_invoice_file_name = strtolower(str_replace([' & ', ' '], ['-', '-'], $first_invoice_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));
	$invoice_file_name = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_file_name);

	# Invoice
	${'invoice-' . $i} = $schedule_directory . $invoice_file_name;

	# Add attachment
	$mail->addAttachment(${'invoice-' . $i});

	$first_invoice_number++;
}

# Set email format to HTML
$mail->isHTML(true);

$mail->Subject = Input::get('subject');
$mail->Body    = Input::get('body');
$mail->AltBody = '';

if(!$mail->send()) {
  // echo 'Message could not be sent.';
  // echo 'Mailer Error: ' . $mail->ErrorInfo;
	Session::flash('send_schedule_error', 'There was an error sending the schedule, please try again.') ;
	Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id'] . '&fee_option=' . $_GET['fee_option']);
} else {
	
	# Update billing status for all loads in schedule
	for ($i=1; $i <= $load_list_count ; $i++) {

		$update = DB::getInstance()->query("UPDATE loader_load SET billing_status = 1, billing_date = '" . date('Y/m/d') . "' WHERE load_id IN (" . implode(', ', $load_id) . ")");

		# Add note for each load
		$insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, type, user_id) VALUES (" . $load_list_load_id[$i] . ", 'Invoice sent', 0, 1, " . $user->data()->user_id . ")");
		
		# Make count of loads updated
		$update_count += 1;
	}

	# If we update billing status for all loads in schedule
	if ($load_list_count == $update_count) {
		
		# Update factoring_company_client_assoc counter
		$update_counter = DB::getInstance()->query("UPDATE factoring_company_client_assoc SET counter = " . (Input::get('counter') + 1) . " WHERE client_id = " . $client_assoc_factoring_company_client_id);
		$update_counter->count() ? Session::flash('send_schedule', 'Schedule sent successfully!') . Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id']) : Session::flash('send_schedule_error', 'There was an error updating data.') ;
	}

	Session::flash('send_schedule', 'Schedule sent successfully!') . Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id']);
}