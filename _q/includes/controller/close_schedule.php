<?php

$validation = $validate->check($_POST, array(
  'note' => array('required' => true)
));

if($validation->passed()) {

	# Update payment_confirmation and note
	$update_payment_confirmation = DB::getInstance()->query("UPDATE factoring_company_schedule SET payment_confirmation = 3 WHERE data_id = " . $_GET['schedule_id']);
	$update_payment_confirmation_count = $update_payment_confirmation->count();

	if ($update_payment_confirmation_count) {
		
		# Add note
	  $insert = DB::getInstance()->query("INSERT INTO factoring_company_schedule_note (schedule_id, note, important, type, user_id) VALUES (" . $_GET['schedule_id'] . ", '" . htmlentities(Input::get('note'), ENT_QUOTES) . "', 0, 3, " . $user->data()->user_id . ")");

		# Update billing status for all loads in schedule
		for ($i=1; $i <= $load_list_count ; $i++) {

		  # Updates billing_status
		  $update_billing_status = DB::getInstance()->query("UPDATE loader_load SET billing_status = 3 WHERE load_id IN (" . implode(', ', $load_id) . ")");
		  
		  # Add note for each load
		  $insert = DB::getInstance()->query("INSERT INTO factoring_company_schedule_note (load_id, note, important, type, user_id) VALUES (" . $load_list_load_id[$i] . ", 'Schedule closed from paid open', 0, 3, " . $user->data()->user_id . ")");
		  
		  # Make count of loads updated
		  $update_billing_status_count += 1;
		}

		# If we update billing status for all loads in schedule
		if ($load_list_count == $update_billing_status_count && $update_payment_confirmation_count) {
		  Session::flash('close_schedule', 'Schedule closed successfully.');
		  Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id']);
		} else {
			Session::flash('close_schedule_error', 'There was an error.');
		}
	}
}
