<?php

$validation = $validate->check($_POST, array(
  'client_assoc_id' => array('required' => true),
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

			$last_insert_id = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS last_insert_id FROM factoring_company_schedule");
			foreach ($last_insert_id->results() as $id) {
				$factoring_company_schedule_data_id = $id->last_insert_id;
			}
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
		 		
		 		Redirect::to('factoring-company-schedule?schedule_id=' . $factoring_company_schedule_data_id . '&create=1&client_assoc_id=' . Input::get('client_assoc_id'));
		 	} 
		 	
		 	# If create files when soar is not required
		 	if (Input::get('create_files') && !Input::get('requires_soar')) {

		 		# Declare get parameter if TAFS (client assoc id 4)
		 		Input::get('client_assoc_id') == 4 ? $get_param = 'create_tafs' : $get_param = 'create_no_soar' ;
		 		Redirect::to('factoring-company-schedule?schedule_id=' . $factoring_company_schedule_data_id . '&' . $get_param . '=1&client_assoc_id=' . Input::get('client_assoc_id'));
		 	} else {

		 		Session::flash('add_load_to_schedule', 'Load added to schedule #' . Input::get('counter') . ' successfully');
		 		Redirect::to('view-load?load_id=' . Input::get('load_id'));
		 	}
		} else {

			Session::flash('add_load_to_schedule_error', $_QC_language[16]);
		}
	}
} else {
    Session::flash('add_load_to_schedule_error', $_QC_language[16]);
}
