<?php

# Add checkpoint
if (Input::get('_controller_checkpoint') == 'add') {

	$validation = $validate->check($_POST, array(
		'date' => array('required' => true),
		'time' => array('required' => true),
		'line_1' => array('required' => true),
		'city' => array('required' => true),
		'state_id' => array('required' => true),
		'zip_code' => array('required' => true)
	));	

	if($validation->passed()) {

		# data_type value
		Input::get('data_type') == 9 ? $data_type_value = 0 : $data_type_value = 1;

		$add = DB::getInstance()->query("
			INSERT INTO loader_checkpoint (load_id, date_time, line_1, line_2, city, state_id, zip_code, contact, appointment, notes, data_type, user_id) 
			VALUES (" . $_GET['load_id'] . ", '" . $checkpoint_date_time . "' ,'" . htmlentities(Input::get('line_1'), ENT_QUOTES) . "', '" . htmlentities(Input::get('line_2'), ENT_QUOTES) . "', '" . htmlentities(Input::get('city'), ENT_QUOTES) . "', " . Input::get('state_id') . ", '" . htmlentities(Input::get('zip_code'), ENT_QUOTES) . "', '" . htmlentities(Input::get('contact'), ENT_QUOTES) . "', '" . htmlentities(Input::get('appointment'), ENT_QUOTES) . "', '" . htmlentities(Input::get('notes'), ENT_QUOTES) . "', " . $data_type_value . ", " . $user->data()->id . ")");

		if ($add->count()) {

			# Sync first checkpoint datetime
			if ($load_first_checkpoint_date_time[1] == '-0001/11/30 0:00:00' && $data_type_value == 0) {

				$update = DB::getInstance()->query("
					UPDATE loader_load 
					SET first_checkpoint = '" . date('Y-m-d G:i:s', strtotime($checkpoint_date_time)) . "'
			  	WHERE load_id = " . $_GET['load_id']);
			} elseif ($load_first_checkpoint_date_time[1] > $checkpoint_date_time && $data_type_value == 0) {

				# A checkpoint is being added on a datetime prior to current saved date, update
				$update = DB::getInstance()->query("
					UPDATE loader_load 
					SET first_checkpoint = '" . date('Y-m-d G:i:s', strtotime($checkpoint_date_time)) . "'
			  	WHERE load_id = " . $_GET['load_id']);
			}

			# Sync last checkpoint datetime
			if ($load_last_checkpoint_date_time[1] == '-0001/11/30 0:00:00' && $data_type_value == 1) {

				$update = DB::getInstance()->query("
					UPDATE loader_load 
					SET last_checkpoint = '" . date('Y-m-d G:i:s', strtotime($checkpoint_date_time)) . "'
			  	WHERE load_id = " . $_GET['load_id']);
			} elseif ($load_last_checkpoint_date_time[1] < $checkpoint_date_time && $data_type_value == 1) {

				# A checkpoint is being added on a datetime after the current saved date, update
				$update = DB::getInstance()->query("
					UPDATE loader_load 
					SET last_checkpoint = '" . date('Y-m-d G:i:s', strtotime($checkpoint_date_time)) . "'
			  	WHERE load_id = " . $_GET['load_id']);
			}
			
			Session::flash('loader', 'Checkpoint added successfully');
			Redirect::to('load?load_id=' . $_GET['load_id']);
		} else {

			Session::flash('loader_error', $core_language[27]);
		}
	} else {

		Session::flash('loader_error', $core_language[27]);
	}
}

# Update checkpoint
if (Input::get('_controller_checkpoint') == 'update') {

	$validation = $validate->check($_POST, array(
		'date' => array('required' => true),
		'time' => array('required' => true),
		'line_1' => array('required' => true),
		'city' => array('required' => true),
		'state_id' => array('required' => true),
		'zip_code' => array('required' => true)
	));	

	if($validation->passed()) {
		
		$update = DB::getInstance()->query("UPDATE loader_checkpoint SET 
      date_time = '" . $checkpoint_date_time . "', 
      line_1 = '" . htmlentities(Input::get('line_1'), ENT_QUOTES) . "', 
      line_2 = '" . htmlentities(Input::get('line_2'), ENT_QUOTES) . "', 
      city = '" . htmlentities(Input::get('city'), ENT_QUOTES) . "', 
      state_id = '" . Input::get('state_id') . "', 
      zip_code = '" . htmlentities(Input::get('zip_code'), ENT_QUOTES) . "', 
      contact = '" . htmlentities(Input::get('contact'), ENT_QUOTES) . "', 
      appointment = '" . htmlentities(Input::get('appointment'), ENT_QUOTES) . "', 
      notes = '" . htmlentities(Input::get('notes'), ENT_QUOTES) . "', 
      data_type = '" . Input::get('data_type') . "'

    WHERE checkpoint_id = " . $_GET['checkpoint_id']);

    if ($update->count()) {

    	
			if (Input::get('data_type') == 0 && $load_first_checkpoint_date_time[1] > $checkpoint_date_time) {

				# Sync first checkpoint datetime
				# A checkpoint is being updated with a datetime prior to current saved date, update
				$update = DB::getInstance()->query("
					UPDATE loader_load 
					SET first_checkpoint = '" . date('Y-m-d G:i:s', strtotime($checkpoint_date_time)) . "'
			  	WHERE load_id = " . $_GET['load_id']);
			} elseif (Input::get('data_type') == 1 && $load_last_checkpoint_date_time[1] < $checkpoint_date_time) {

				# Sync last checkpoint datetime
				# A checkpoint is being updated with a datetime past current saved date, update
				$update = DB::getInstance()->query("
					UPDATE loader_load 
					SET last_checkpoint = '" . date('Y-m-d G:i:s', strtotime($checkpoint_date_time)) . "'
			  	WHERE load_id = " . $_GET['load_id']);
			}
    	
    	Session::flash('loader', 'Checkpoint updated successfully');
    	Redirect::to('load?load_id=' . $_GET['load_id']);
    } else {

    	Session::flash('loader_error', $core_language[27]);
    }
	}
}

# Delete checkpoint
if (Input::get('_controller_checkpoint') == 'delete') {

	$delete = DB::getInstance()->query("DELETE FROM loader_checkpoint WHERE checkpoint_id = " . $_POST['delete_checkpoint']);

	if ($delete->count()) {
		
		Session::flash('loader', 'Item deleted successfully');
		Redirect::to('load?load_id=' . $_GET['load_id']);
	} else {

		Session::flash('loader_error', $core_language[27]);
	}
}

# Status update
if (Input::get('_controller_checkpoint') == 'status') {

	if (Input::get('status') == 1) {
		
		# Updating status only
		$update = DB::getInstance()->query("UPDATE loader_checkpoint SET status = 1 WHERE checkpoint_id = " . Input::get('checkpoint_id'));
	
		if ($update->count()) {
		 	
		 	Session::flash('loader', 'Checkpoint marked as complete.');
		 	Redirect::to('load?load_id=' . $_GET['load_id']);
		} else {

			Session::flash('loader_error', $core_language[27]) ;
		}
	} elseif (Input::get('status') == 2) {
		
		# Updating status and sending notification
		# Form comes here first, just to redirect back with checkpoint_id in the url as value for parameter checkpoint_status_update
		if (!Input::get('to')) {
			
			Redirect::to('load?load_id=' . $_GET['load_id'] . '&checkpoint_status_update=' . Input::get('checkpoint_id'));
		} else {

			# Send email
			$to = Input::get('to');
			$to_items = explode(",", str_replace(' ', '', $to));
			$to_items_count = count($to_items);
			
			# Check for invalid emails on the $to_items array
			for ($i = 0; $i < $to_items_count ; $i++) {
				
				!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $to_items[$i], $matches) ? $items_error = 1 : '';
			}

			# cc is not required, an empty string throws an error, so validation runs only if input value is set
			if (Input::get('cc')) {
				
				$cc = Input::get('cc');
				$cc_items = explode(",", str_replace(' ', '', $cc));
				$cc_items_count = count($cc_items);
				
				# Check for invalid emails on the $cc_items array
				for ($i = 0; $i < $cc_items_count ; $i++) {
					!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $cc_items[$i], $matches) ? $items_error = 1 : '';
				}
			}

			# Set from email address
			$from = 'no-reply@logisticsfrog.com';

			# Go on if there are no invalid emails
			if (!$items_error) {
				
				# Send mail
	    	$email_to = $to;

				$subject = Input::get('subject');

				$headers = "From: " . strip_tags($from) . "\r\n";
				$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
				$headers .= "CC: " . $cc . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";

				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$message = '<html><body>';
				$message .= Input::get('body');
				$message .= '</body></html>';


				mail($email_to, $subject, $message, $headers);

				# Add note
				$insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, type, user_id) VALUES (" . $_GET['load_id'] . ", '" . $subject . '<p>' . Input::get('body') . '</p>' . "', 0, 1, " . $user->data()->id . ")");
				
				# Change checkpoint status
				$update = DB::getInstance()->query("UPDATE loader_checkpoint SET status = 2 WHERE checkpoint_id = " . $_GET['checkpoint_status_update']);
				
				Session::flash('loader', 'Email sent successfully, checkpoint marked as complete.');
				Redirect::to('load?load_id=' . $_GET['load_id']);
			} else {

				Session::flash('loader_error', $_QC_language[16]);
			}
		}
	}
}
