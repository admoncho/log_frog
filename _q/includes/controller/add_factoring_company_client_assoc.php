<?php

# Set counters in case they come empty
Input::get('counter') ? $counter = Input::get('counter') : $counter = 1;
Input::get('invoice_counter') ? $invoice_counter = Input::get('invoice_counter') : $invoice_counter = 1;

# Check for valid file types and size (less than 7,000,000 bytes)
if(($_FILES["invoice_background"]["type"] == "image/jpg" || $_FILES["invoice_background"]["type"] == "image/jpeg") && $_FILES["invoice_background"]["size"] < 7000000) {
  
  # If file handling errors
  if ($_FILES["invoice_background"]["error"] > 0){

    # Display error
    Session::flash('add_factoring_company_client_assoc_error', 'There was an error uploading the file');
  } else {

    # No file handling errors

    $validation = $validate->check($_POST, array(
		  'factoring_company_id' => array('required' => true),
		  'client_id' => array('required' => true),
		  'main' => array('required' => true),
			'alt' => array('required' => true)
		));

		if($validation->passed()) {

			# Add to database
			$insert = DB::getInstance()->query("INSERT INTO factoring_company_client_assoc (factoring_company_id, client_id, main, alt, counter, invoice_counter, user_id) VALUES (" . Input::get('factoring_company_id') . ", " . Input::get('client_id') . ", " . Input::get('main') . ", " . Input::get('alt') . ", " . $counter . ", " . $invoice_counter . ", " . $user->data()->user_id . ")");
			
			$lastInsertID = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS lastINSERTID FROM user_e_profile_client");
			foreach ($lastInsertID->results() as $id) {
				$last_id = $id->lastINSERTID;
			}

			if ($insert->count()) {
				
				# file_name
		    $file_name = $last_id . '.jpg';

		    # Upload image if data has been added to table
		    move_uploaded_file($_FILES["invoice_background"]["tmp_name"], $schedule_directory . '_temp/' . $user->data()->user_id);

		    # Raname and move out of _temp
		    rename($schedule_directory . '_temp/' . $user->data()->user_id, $schedule_directory . 'bg/' . $file_name);

		    Session::flash('add_factoring_company_client_assoc', 'Factoring company association added successfully');
		    Redirect::to('client?id=' . $_GET['id'] . '#factoring-company');
			}
		} else {
		    Session::flash('add_factoring_company_client_assoc_error', $_QC_language[16]);
		}
  }
} else {

  # Add without image, soar file not required by this factoring company
  # No file handling errors

    $validation = $validate->check($_POST, array(
		  'factoring_company_id' => array('required' => true),
		  'client_id' => array('required' => true),
		  'main' => array('required' => true),
			'alt' => array('required' => true)
		));

		if($validation->passed()) {

			# Add to database
			$insert = DB::getInstance()->query("INSERT INTO factoring_company_client_assoc (factoring_company_id, client_id, main, alt, counter, invoice_counter, user_id) VALUES (" . Input::get('factoring_company_id') . ", " . Input::get('client_id') . ", " . Input::get('main') . ", " . Input::get('alt') . ", " . $counter . ", " . $invoice_counter . ", " . $user->data()->user_id . ")");
			
			$lastInsertID = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS lastINSERTID FROM user_e_profile_client");
			foreach ($lastInsertID->results() as $id) {
				$last_id = $id->lastINSERTID;
			}

			if ($insert->count()) {

		    Session::flash('add_factoring_company_client_assoc', 'Factoring company association added successfully');
		    Redirect::to('client?id=' . $_GET['id'] . '#factoring-company');
			}
		} else {
		    Session::flash('add_factoring_company_client_assoc_error', $_QC_language[16]);
		}
}
