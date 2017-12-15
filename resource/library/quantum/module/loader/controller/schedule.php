<?php
session_start();
ob_start();

# Add load to schedule
if (Input::get('_controller_schedule') == 'add_load') {
	
	$validation = $validate->check($_POST, array(
	  'client_assoc_id' => array('required' => true),
	  'factoring_company_id' => array('required' => true),
	  'counter' => array('required' => true),
	  'invoice_counter' => array('required' => true),
	  'load_id' => array('required' => true)
	));

	if($validation->passed()) {

		# Check if there is a schedule created on factoring_company_schedule with this client_assoc_id and counter, if not found create it.
		$schedule_check = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE client_assoc_id = " . Input::get('client_assoc_id') . " && counter = " . Input::get('counter'));

		if (!$schedule_check->count()) {
			
			# No count, create.
			$add_schedule = DB::getInstance()->query("INSERT INTO factoring_company_schedule (client_assoc_id, counter) VALUES (" . Input::get('client_assoc_id') . ", " . Input::get('counter') . ")");

			if ($add_schedule->count()) {

				$factoring_company_schedule_data_id = $add_schedule->last();
			}
		} else {

			# Count, get data_id
			foreach ($schedule_check->results() as $schedule_check_data) {
				
				$factoring_company_schedule_data_id = $schedule_check_data->data_id;
			}
		}

		# If $factoring_company_schedule_data_id is set
		if (isset($factoring_company_schedule_data_id)) {
			
			# Add to load to table factoring_company_schedule_load
			$add = DB::getInstance()->query("INSERT INTO factoring_company_schedule_load (schedule_id, load_id, invoice_number) VALUES (" . $factoring_company_schedule_data_id . ", " . Input::get('load_id') . ", " . Input::get('invoice_counter') . ")");
			
			if ($add->count()) {
			 	
			 	# If create files when soar is required
			 	if (Input::get('create_files') && Input::get('requires_soar')) {
			 		
			 		Redirect::to('schedule?schedule_id=' . $factoring_company_schedule_data_id . '&create=1&client_assoc_id=' . Input::get('client_assoc_id'));
			 	} 
			 	
			 	# If create files when soar is not required
			 	if (Input::get('create_files') && !Input::get('requires_soar')) {

			 		# Declare get parameter if TAFS (factoring_company_id 3)
			 		Input::get('factoring_company_id') == 3 ? $get_param = 'create_tafs' : $get_param = 'create_no_soar' ;
			 		Redirect::to('schedule?schedule_id=' . $factoring_company_schedule_data_id . '&' . $get_param . '=1&client_assoc_id=' . Input::get('client_assoc_id') . '&load_client_id=' . Input::get('client_id'));
			 	} else {

			 		Session::flash('loader', 'Load added to schedule #' . Input::get('counter') . ' successfully');
			 		Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/loader/load?load_id=' . Input::get('load_id'));
			 	}
			} else {

				Session::flash('loader_error', $core_language[27]);
			}
		}
	} else {
	    Session::flash('loader_error', $core_language[27]);
	}
}

# Delete load from schedule
if (Input::get('_controller_schedule') == 'delete_load') {
	
	$validation = $validate->check($_POST, array(
	  'load_id' => array('required' => true)
	));

	if($validation->passed()) {

		# Delete load from factoring_company_schedule_load table
		$delete = DB::getInstance()->query("DELETE FROM factoring_company_schedule_load WHERE load_id = " . Input::get('load_id'));
		
		if ($delete->count()) {
		 	
		 	Session::flash('loader', 'Load removed from schedule successfully');
	 		Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/loader/schedule?schedule_id=' . $_GET['schedule_id']);
		} else {

			Session::flash('loader_error', $core_language[27]);
		}
	} else {
	    Session::flash('loader_error', $core_language[27]);
	}
}

# Delete soar file
if (Input::get('_controller_schedule') == 'kill_soar') {

	# Kill
  if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf')) {
    
    unlink($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf');
  }

  Session::flash('loader', 'File deleted successfully');
  Redirect::to('schedule?schedule_id=' . $_GET['schedule_id']);
}

# Close schedule
if (Input::get('_controller_schedule') == 'close_schedule') {

	$validation = $validate->check($_POST, array(
	  'note' => array('required' => true)
	));

	if($validation->passed()) {

		# Update payment_confirmation and note
		$update_payment_confirmation = DB::getInstance()->query("UPDATE factoring_company_schedule SET payment_confirmation = 3 WHERE data_id = " . $_GET['schedule_id']);
		$update_payment_confirmation_count = $update_payment_confirmation->count();

		if ($update_payment_confirmation_count) {
			
			# Add note
		  $insert = DB::getInstance()->query("INSERT INTO factoring_company_schedule_note (schedule_id, note, important, type, user_id) VALUES (" . $_GET['schedule_id'] . ", '" . htmlentities(Input::get('note'), ENT_QUOTES) . "', 0, 3, " . $user->data()->id . ")");

			# Update billing status for all loads in schedule
			for ($i=1; $i <= $load_list_count ; $i++) {

			  # Updates billing_status
			  $update_billing_status = DB::getInstance()->query("UPDATE loader_load SET billing_status = 3 WHERE load_id IN (" . implode(', ', $load_id) . ")");
			  
			  # Add note for each load
			  $insert = DB::getInstance()->query("INSERT INTO factoring_company_schedule_note (load_id, note, important, type, user_id) VALUES (" . $load_list_load_id[$i] . ", 'Schedule closed from paid open', 0, 3, " . $user->data()->id . ")");
			  
			  # Make count of loads updated
			  $update_billing_status_count += 1;
			}

			# If we update billing status for all loads in schedule
			if ($load_list_count == $update_billing_status_count && $update_payment_confirmation_count) {

			  Session::flash('loader', 'Schedule closed successfully.');
			  Redirect::to('schedule?schedule_id=' . $_GET['schedule_id']);
			} else {
				
				Session::flash('loader_error', 'There was an error.');
			}
		}
	}
}
