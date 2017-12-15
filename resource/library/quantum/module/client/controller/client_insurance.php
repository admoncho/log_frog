<?php

# Break dates apart
list($auto_issuing_month, $auto_issuing_day, $auto_issuing_year) = explode('/', Input::get('auto_issuing_date'));
list($auto_expiration_month, $auto_expiration_day, $auto_expiration_year) = explode('/', Input::get('auto_expiration_date'));
list($cargo_issuing_month, $cargo_issuing_day, $cargo_issuing_year) = explode('/', Input::get('cargo_issuing_date'));
list($cargo_expiration_month, $cargo_expiration_day, $cargo_expiration_year) = explode('/', Input::get('cargo_expiration_date'));
list($commercial_issuing_month, $commercial_issuing_day, $commercial_issuing_year) = explode('/', Input::get('commercial_issuing_date'));
list($commercial_expiration_month, $commercial_expiration_day, $commercial_expiration_year) = explode('/', Input::get('commercial_expiration_date'));

# Process producer emails
# Clear white space
$producer_email_input = preg_replace('/\s+/', '', Input::get('producer_email'));
// Make array
$producer_email_array = explode(",", $producer_email_input);
// Count array items
$producer_email_array_count = count($producer_email_array);
// Check for valid addresses
for ($i=0; $i < $producer_email_array_count; $i++) { 
    
  if (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $producer_email_array[$i])) {
    	
    # There are invalid addresses in the string
    $invalid_email_address = 1;
  }
}

# Process vin numbers only if data sent
# Clear white space
if (Input::get('vin_number')) {
	
	$vin_number_input = preg_replace('/\s+/', '', Input::get('vin_number'));
	// Make array
	$vin_number_array = explode(",", $vin_number_input);
	// Count array items
	$vin_number_array_count = count($vin_number_array);
	// Check for valid vin numbers
	for ($i=0; $i < $vin_number_array_count; $i++) { 
	    
	  if (strlen($vin_number_array[$i]) != 17) {
	    	
	    # There are invalid vin numbers in the string
	    $invalid_vin_number = 1;
	  }
	}
}

# Add/update item
if (Input::get('_controller_client_insurance') == 'add_insurance_info' || Input::get('_controller_client_insurance') == 'edit_insurance_info') {

  $validation = $validate->check($_POST, array(
    'insurance_company_id' => array('required' => true), 
    'producer' => array('required' => true),
    'producer_phone_number' => array('required' => true), 
    'producer_fax_number' => array('required' => true), 
    'producer_email' => array('required' => true), 
    'auto_insurance' => array('required' => true), 
    'auto_insurance_amount' => array('required' => true), 
    'auto_issuing_date' => array('required' => true), 
    'auto_expiration_date' => array('required' => true), 
    'cargo_insurance' => array('required' => true), 
    'cargo_insurance_amount' => array('required' => true), 
    'cargo_issuing_date' => array('required' => true), 
    'cargo_expiration_date' => array('required' => true)
  ));

  if ($validation->passed()) {

  	if ($invalid_email_address || $invalid_vin_number) {
  		
  		Session::flash('client_error', 'Make sure producer email addresses and vin numbers are valid.');
  	} else {

  		if (Input::get('_controller_client_insurance') == 'add_insurance_info') {
				
				# Add client insurance
		    $add = DB::getInstance()->query("INSERT INTO client_insurance (client_id, insurance_company_id, producer, producer_phone_number, producer_fax_number, producer_email, website, website_username, website_password, vin_number, user_id) VALUES (" . $_GET['client_id'] . ", " . Input::get('insurance_company_id') . ", '" . htmlentities(Input::get('producer'), ENT_QUOTES) . "', '" . htmlentities(Input::get('producer_phone_number'), ENT_QUOTES) . "', '" . htmlentities(Input::get('producer_fax_number'), ENT_QUOTES) . "', '" . $producer_email_input . "', '" . htmlentities(Input::get('website'), ENT_QUOTES) . "', '" . htmlentities(Input::get('website_username'), ENT_QUOTES) . "', '" . htmlentities(Input::get('website_password'), ENT_QUOTES) . "', '" . $vin_number_input . "', " . $user->data()->id . ")");

		    $add_last_id = $add->last();

		    if ($add->count()) {

		    	# Add client insurance type 1
		    	$add_type_1 = DB::getInstance()->query("INSERT INTO client_insurance_type (client_insurance_id, type, policy_number, amount, issuing_date, expiration_date, user_id) VALUES (" . $add_last_id . ", 1, '" . htmlentities(Input::get('auto_insurance'), ENT_QUOTES) . "', '" . Input::get('auto_insurance_amount') . "', '" . $auto_issuing_year . '-' . $auto_issuing_month . '-' . $auto_issuing_day . "', '" . $auto_expiration_year . '-' . $auto_expiration_month . '-' . $auto_expiration_day . "', " . $user->data()->id . ")");
		    	$add_type_1_count = $add_type_1->count();

		    	# Add client insurance type 2
		    	$add_type_2 = DB::getInstance()->query("INSERT INTO client_insurance_type (client_insurance_id, type, policy_number, amount, issuing_date, expiration_date, user_id) VALUES (" . $add_last_id . ", 2, '" . htmlentities(Input::get('cargo_insurance'), ENT_QUOTES) . "', '" . Input::get('cargo_insurance_amount') . "', '" . $cargo_issuing_year . '-' . $cargo_issuing_month . '-' . $cargo_issuing_day . "', '" . $cargo_expiration_year . '-' . $cargo_expiration_month . '-' . $cargo_expiration_day . "', " . $user->data()->id . ")");
		    	$add_type_2_count = $add_type_2->count();

		    	if (Input::get('commercial_insurance') && Input::get('commercial_insurance_amount')) {
		    		
		    		# Add client insurance type 3
			    	$add_type_3 = DB::getInstance()->query("INSERT INTO client_insurance_type (client_insurance_id, type, policy_number, amount, issuing_date, expiration_date, user_id) VALUES (" . $add_last_id . ", 3, '" . htmlentities(Input::get('commercial_insurance'), ENT_QUOTES) . "', '" . Input::get('commercial_insurance_amount') . "', '" . $commercial_issuing_year . '-' . $commercial_issuing_month . '-' . $commercial_issuing_day . "', '" . $commercial_expiration_year . '-' . $commercial_expiration_month . '-' . $commercial_expiration_day . "', " . $user->data()->id . ")");
		    	}

		    	if ($add_type_1_count && $add_type_2_count) {
		    		
		    		Session::flash('client', 'Client insurance added successfully');
			     	Redirect::to('client?client_id=' . $_GET['client_id']);
		    	}
		    } else {

		    	Session::flash('client_error', $core_language[27]);
		    }
	   	} elseif (Input::get('_controller_client_insurance') == 'edit_insurance_info') {

	   		# Update
	   		$update_main = DB::getInstance()->query("UPDATE client_insurance SET 
		      insurance_company_id = '" . Input::get('insurance_company_id') . "', 
		      producer = '" . htmlentities(Input::get('producer'), ENT_QUOTES) . "', 
		      producer_phone_number = '" . htmlentities(Input::get('producer_phone_number'), ENT_QUOTES) . "', 
		      producer_fax_number = '" . htmlentities(Input::get('producer_fax_number'), ENT_QUOTES) . "', 
		      producer_email = '" . $producer_email_input . "', 
		      website = '" . htmlentities(Input::get('website'), ENT_QUOTES) . "', 
		      website_username = '" . htmlentities(Input::get('website_username'), ENT_QUOTES) . "', 
		      website_password = '" . htmlentities(Input::get('website_password'), ENT_QUOTES) . "', 
		      vin_number = '" . $vin_number_input . "'

		    WHERE client_id = " . $_GET['client_id']);
	   		$update_main_count = $update_main->count();

	   		# Update client insurance type 1
	    	$update_type_1 = DB::getInstance()->query("UPDATE client_insurance_type SET 
	      policy_number = '" . htmlentities(Input::get('auto_insurance'), ENT_QUOTES) . "', 
	      amount = '" . Input::get('auto_insurance_amount') . "', 
	      issuing_date = '" . $auto_issuing_year . '-' . $auto_issuing_month . '-' . $auto_issuing_day . "', 
	      expiration_date = '" . $auto_expiration_year . '-' . $auto_expiration_month . '-' . $auto_expiration_day . "'


	    	WHERE client_insurance_id = " . $client_insurance_id . " && type = 1");
	    	$update_type_1_count = $update_type_1->count();

	    	# Update client insurance type 2
	    	$update_type_2 = DB::getInstance()->query("UPDATE client_insurance_type SET 
	      policy_number = '" . htmlentities(Input::get('cargo_insurance'), ENT_QUOTES) . "', 
	      amount = '" . Input::get('cargo_insurance_amount') . "', 
	      issuing_date = '" . $cargo_issuing_year . '-' . $cargo_issuing_month . '-' . $cargo_issuing_day . "', 
	      expiration_date = '" . $cargo_expiration_year . '-' . $cargo_expiration_month . '-' . $cargo_expiration_day . "'

	    	WHERE client_insurance_id = " . $client_insurance_id . " && type = 2");
	    	$update_type_2_count = $update_type_2->count();

	    	if (isset($client_insurance_type_policy_number[3])) {

	    		# Update client insurance type 3 if data exists	    		
	    		$update_type_3 = DB::getInstance()->query("UPDATE client_insurance_type SET 
		      policy_number = '" . htmlentities(Input::get('commercial_insurance'), ENT_QUOTES) . "', 
		      amount = '" . Input::get('commercial_insurance_amount') . "', 
		      issuing_date = '" . $commercial_issuing_year . '-' . $commercial_issuing_month . '-' . $commercial_issuing_day . "', 
		      expiration_date = '" . $commercial_expiration_year . '-' . $commercial_expiration_month . '-' . $commercial_expiration_day . "'

		    	WHERE client_insurance_id = " . $client_insurance_id . " && type = 3");
	    	} else {

	    		# Add client insurance type 3
			    $update_type_3 = DB::getInstance()->query("INSERT INTO client_insurance_type (client_insurance_id, type, policy_number, amount, issuing_date, expiration_date, user_id) VALUES (" . $client_insurance_id . ", 3, '" . htmlentities(Input::get('commercial_insurance'), ENT_QUOTES) . "', '" . Input::get('commercial_insurance_amount') . "', '" . $commercial_issuing_year . '-' . $commercial_issuing_month . '-' . $commercial_issuing_day . "', '" . $commercial_expiration_year . '-' . $commercial_expiration_month . '-' . $commercial_expiration_day . "', " . $user->data()->id . ")");
	    	}

	    	$update_type_3_count = $update_type_3->count();

		    if ($update_main_count || $update_type_1_count || $update_type_2_count || $update_type_3_count) {

		    	Session::flash('client', 'Client insurance updated successfully');
			    Redirect::to('client?client_id=' . $_GET['client_id']);
		    } else {

		    	Session::flash('client_error', $core_language[27]);
		    }
	   	}
  	}
	    
  } else {

    Session::flash('client_error', 'Make sure all required fields are filled properly');
  }
}
