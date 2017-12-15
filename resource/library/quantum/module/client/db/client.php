<?php
  session_start();
  ob_start();
  $_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# This file name
$this_file_name = basename(__FILE__, '.php');

# Company rate
# THIS FILE SHOULD BE SOMEWHERE ELSE, LEAVING HERE FOR NOW FOR CONVENIENCE
$company_rate = DB::getInstance()->query("SELECT * FROM company_rate ORDER BY rate ASC");
$company_rate_count = $company_rate->count();
$i = 1;

if ($company_rate_count) {
	foreach ($company_rate->results() as $company_rate_data) {

		$company_rate_data_id[$i] = $company_rate_data->data_id;
		$company_rate_title[$i] = html_entity_decode($company_rate_data->title);
		$company_rate_rate[$i] = $company_rate_data->rate;
		$company_rate_processing_fee[$i] = $company_rate_data->processing_fee;

		$i++;
	}
}

# Client status
isset($_POST['status']) && $_POST['status'] == 1 ? $status_value = 1 : $status_value = 0;
!isset($_POST['status']) ? $status_value = 1 : '';

# Clients
isset($_GET['client_id']) ? $clause = " WHERE data_id = " . $_GET['client_id'] : '';
isset($_GET['load_id']) ? $clause = " WHERE data_id = " . $load_client_id[1] : '';
isset($_GET['schedule_id']) ? $clause = " WHERE data_id = " . $client_assoc_factoring_company_client_id : '';
isset($_GET['draft_id']) ? $clause = " WHERE status = " . $status_value . " ORDER BY company_name ASC" : '';
!$_GET ? $clause = " WHERE status = " . $status_value . " ORDER BY company_name ASC" : '';

# Old quickpay connection (temporary)
isset($_GET['client_id']) && $_SESSION['$clean_php_self'] == '/0/quickpay-invoicing.php' ? $clause = " WHERE data_id = " . $_GET['client_id'] : '';

# client
$client = DB::getInstance()->query("SELECT * FROM client" . $clause);
$client_count = $client->count();
$i = 1;

if ($client_count) {
	
	# Iterate through items
	foreach ($client->results() as $client_data) {
		
		$client_data_id[$i] = $client_data->data_id;
		$client_company_name[$i] = $client_data->company_name;
		$client_mc_number[$i] = $client_data->mc_number;
		$client_us_dot_number[$i] = $client_data->us_dot_number;
		$client_ein_number[$i] = $client_data->ein_number;
		$client_chr_t[$i] = $client_data->chr_t;
		$client_main_contact[$i] = html_entity_decode($client_data->main_contact);
		$client_phone_number_01[$i] = $client_data->phone_number_01;
		$client_phone_number_02[$i] = $client_data->phone_number_02;
		$client_phone_number_03[$i] = $client_data->phone_number_03; // Per safer
		$client_invoice_color[$i] = html_entity_decode($client_data->invoice_color);
		$client_rate_id[$i] = $client_data->rate_id;
		$client_rate_type[$i] = $client_data->rate_type; // 1 Fixed fee 2 Percentage
		$client_rate[$i] = $client_data->rate;
		$client_scac_code[$i] = $client_data->scac_code;
		$client_status[$i] = $client_data->status;
		$client_formation_date[$i] = date('M d, Y', strtotime($client_data->formation_date));
		$client_formation_date_1[$i] = date('m/d/Y', strtotime($client_data->formation_date));
		$client_added[$i] = date('M d, Y', strtotime($client_data->added));
		$i++;

		$client_id_company_name[$client_data->data_id] = $client_data->company_name;
		$client_id_mc_number[$client_data->data_id] = $client_data->mc_number;
		$client_id_invoice_color[$client_data->data_id] = html_entity_decode($client_data->invoice_color);
	}
}

# Clients ALT
# USED FOR THE QUICK JUMP SELECT (for quick access to other clients when in a client)
$client_ALT = DB::getInstance()->query("SELECT * FROM " . $this_file_name . " ORDER BY company_name ASC");
$client_ALT_count = $client_ALT->count();
$i = 1;

if ($client_ALT_count) {
	
	# Iterate through items
	foreach ($client_ALT->results() as $client_ALT_data) {
		
		$client_ALT_id[$i] = $client_ALT_data->data_id;
		$client_ALT_company_name[$i] = html_entity_decode($client_ALT_data->company_name);
		$client_ALT_status[$i] = $client_data->status; // 0: inactive 1: act
		$i++;

		$client_ALT_id_company_name[$client_ALT_data->data_id] = html_entity_decode($client_ALT_data->company_name);
	}
}

if (isset($_GET['client_id'])) {

	# client address

	# Alter the clause if isset($_GET['address_id']) 
	isset($_GET['address_id']) ? $clause = "WHERE data_id = " . $_GET['address_id'] : $clause = "WHERE client_id = " . $_GET['client_id'];

	$client_address = DB::getInstance()->query("SELECT * FROM client_address " . $clause);
	$client_address_count = $client_address->count();
	$i = 1;

	if ($client_address_count) {
		foreach ($client_address->results() as $client_address_data) {
			
			$client_address_data_id[$i] = $client_address_data->data_id;
			$client_address_type[$i] = $client_address_data->address_type; // 1 physical 2 mailing
			$client_address_line_1[$i] = html_entity_decode($client_address_data->line_1);
			$client_address_line_2[$i] = html_entity_decode($client_address_data->line_2);
			$client_address_line_3[$i] = html_entity_decode($client_address_data->line_3);
			$client_address_city[$i] = html_entity_decode($client_address_data->city);
			$client_address_state_id[$i] = $client_address_data->state_id;
			$client_address_zip_code[$i] = html_entity_decode($client_address_data->zip_code);
			$client_address_mailing_use_physical[$i] = $client_address_data->mailing_use_physical; // 1 yes
			$i++;
		}
	}	

	# Connection settings
	$_GET['client_id'] && !$_GET['user_id'] ? $clause = " WHERE client_id = " . $_GET['client_id'] . " ORDER BY user_type ASC" : "";
	$_GET['client_id'] && $_GET['user_id'] ? $clause = " WHERE user_id = " . $_GET['user_id'] : "";
		
	# Lists all users associated with a client profile
	$client_user = DB::getInstance()->query("SELECT * FROM client_user" . $clause);
	$client_user_count = $client_user->count();
	$i = 1;

	if ($client_user_count) {
		foreach($client_user->results() as $client_user_data) {

			$client_user_user_id[$i] = $client_user_data->user_id;
			$client_user_client_id[$i] = $client_user_data->client_id;
			$client_user_data_id[$i] = $client_user_data->data_id;
			$client_user_user_type[$i] = $client_user_data->user_type; // 0 owner 1 owner/operator 2 driver
			$client_user_user_manager[$i] = $client_user_data->user_manager;

			# Owner has drivers?
			$is_user_manager = DB::getInstance()->query("SELECT * FROM client_user WHERE user_manager = " . $client_user_user_id[$i]);
			$is_user_manager_count[$i] = $is_user_manager->count();

			$i++;
		}
	}

	# Get number of owners
	$client_user_owner = DB::getInstance()->query("SELECT * FROM " . $this_file_name . "_user WHERE " . $this_file_name . "_id = " . $_GET['client_id'] . " && user_type = 0");
	$client_user_owner_count = $client_user_owner->count();

	# Get number of owner/operators
	$client_user_owner_operator = DB::getInstance()->query("SELECT * FROM " . $this_file_name . "_user WHERE " . $this_file_name . "_id = " . $_GET['client_id'] . " && user_type = 1");
	$client_user_owner_operator_count = $client_user_owner_operator->count();

	# Get number of drivers
	$client_user_driver = DB::getInstance()->query("SELECT * FROM " . $this_file_name . "_user WHERE " . $this_file_name . "_id = " . $_GET['client_id'] . " && user_type = 2");
	$client_user_driver_count = $client_user_driver->count();

	# Disable/Enable client activation
	# Go on if there is user count
	if ($client_user_count) {
		
		# We can activate if we have an owner and a driver count OR an owner/operator
		if (($client_user_owner_count && $client_user_driver_count) || $client_user_owner_operator_count) {
			$owner_driver_activate = 1;
		}

		# If deleting a client user
		if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_client_user' && $client_status[1] == 1) {

			if ($client_user_count == 1) {

				# User count == 1 and client profile is active means that this user is an owner/operator.
				# Deleting this user should set profile as inactive inmmediately.
				$deactivate_client = 1;

			} elseif ($client_user_count > 1) {
				
				# We have more than 1 user
				if ($client_user_owner_count == 1 && $client_user_driver_count == 1 && $client_user_owner_operator_count == 0) {
					
					# One owner, one driver, 0 owner/operators
					# This case should deactivate client profile
					$deactivate_client = 1;
				}
			}
		}

	}

	# client_broker_assoc
	if (isset($_GET['add_broker_assoc']) && !isset($_POST['_controller_' . $this_file_name])) {

		# Used to hide list of taken brokers on client_broker_assoc
		$clause = "WHERE " . $this_file_name . "_id = " . $_GET['client_id'];
	} elseif (isset($_GET['add_broker_assoc']) && isset($_POST['_controller_' . $this_file_name]) && $_POST['_controller_' . $this_file_name] == 'add_broker_assoc') {

		# Spot data replicas for add_broker_assoc controller (if count, block controller process)
		$clause = "WHERE " . $this_file_name . "_id = " . $_GET['client_id'] . " && broker_id = " . Input::get('broker_id');

	} elseif (isset($_GET['broker_assoc_id'])) {

		# Id specific data
		$clause = "WHERE data_id = " . $_GET['broker_assoc_id'];

	} else {

		$clause = "WHERE " . $this_file_name . "_id = " . $_GET['client_id'];
	}

	# client_broker_assoc
	$client_broker_assoc = DB::getInstance()->query("SELECT * FROM " . $this_file_name . "_broker_assoc " . $clause);
	$client_broker_assoc_count	 = $client_broker_assoc->count();
	$i = 1;
	
	if ($client_broker_assoc_count) {

		# List all entries and make them readonly
		foreach ($client_broker_assoc->results() as $client_broker_assoc_data) {
			
			$client_broker_assoc_data_id[$i] = $client_broker_assoc_data->data_id;
			$client_broker_assoc_broker_id[$i] = $client_broker_assoc_data->broker_id;
			$client_broker_assoc_quickpay_service_fee_id[$i] = $client_broker_assoc_data->quickpay_service_fee_id;
			$broker_assoc_id_array[] = $client_broker_assoc_data->broker_id;
			$i++;
		}
	}

	# client_manager
	// Lists all owners and owner/operators associated with a client profile
	$client_manager = DB::getInstance()->query("SELECT * FROM client_user WHERE client_id = " . $_GET['client_id'] . " && user_type IN (0, 1)");
	$client_manager_count = $client_manager->count();
	$i = 1;

	if ($client_manager_count) {
		foreach($client_manager->results() as $client_manager_data) {

			$client_manager_user_id[$i] = $client_manager_data->user_id;
			$client_manager_client_id[$i] = $client_manager_data->client_id;
			$client_manager_data_id[$i] = $client_manager_data->data_id;
			$client_manager_user_type[$i] = $client_manager_data->user_type;
			$client_manager_user_manager[$i] = $client_manager_data->user_manager;
			$i++;
		}
	}

	# client_insurance
	$client_insurance = DB::getInstance()->query("SELECT * FROM client_insurance WHERE client_id = " . $_GET['client_id']);
	$client_insurance_count = $client_insurance->count();

	if ($client_insurance_count) {
		foreach($client_insurance->results() as $client_insurance_data) {

			$client_insurance_client_id = $client_insurance_data->client_id;
			$client_insurance_id = $client_insurance_data->id;
			$client_insurance_insurance_company_id = $client_insurance_data->insurance_company_id;
			$client_insurance_producer = htmlspecialchars_decode($client_insurance_data->producer);
			$client_insurance_producer_phone_number = $client_insurance_data->producer_phone_number;
			$client_insurance_producer_fax_number = $client_insurance_data->producer_fax_number;
			$client_insurance_producer_email = str_replace(',', ', ', $client_insurance_data->producer_email);
			$client_insurance_website = htmlspecialchars_decode($client_insurance_data->website);
			$client_insurance_website_username = htmlspecialchars_decode($client_insurance_data->website_username);
			$client_insurance_website_password = htmlspecialchars_decode($client_insurance_data->website_password);
			$client_insurance_vin_number = str_replace(',', ', ', $client_insurance_data->vin_number);
			$client_insurance_user_id = $client_insurance_data->user_id;
			$client_insurance_added = $client_insurance_data->added;
		}

		# Client insurance type
		# The ORDER BY clause in this connection has things that depend on it, don't alter it.
		$client_insurance_type = DB::getInstance()->query("SELECT * FROM client_insurance_type WHERE client_insurance_id = " . $client_insurance_id . " ORDER BY type ASC");
		$client_insurance_type_count = $client_insurance_type->count();
		$i = 1;

		if ($client_insurance_type_count) {
			foreach($client_insurance_type->results() as $client_insurance_type_data) {

				$client_insurance_type_type[$i] = $client_insurance_type_data->type; // 1 auto 2 cargo 3 commercial
				$client_insurance_type_policy_number[$i] = htmlspecialchars_decode($client_insurance_type_data->policy_number);
				$client_insurance_type_amount[$i] = number_format($client_insurance_type_data->amount, 2);
				$client_insurance_type_issuing_date[$i] = date('m/d/Y', strtotime($client_insurance_type_data->issuing_date));
				$client_insurance_type_expiration_date[$i] = date('m/d/Y', strtotime($client_insurance_type_data->expiration_date));
				

				$client_insurance_type_type[$i] == 1 ? $type_label[$i] = 'Auto' : ($client_insurance_type_type[$i] == 2 ? $type_label[$i] = 'Cargo' : $type_label[$i] = 'Commercial');

				$i++;
			}
		}
	}
}

// This list excludes users that are already linked with a client profile (in user_e_profile_client_user)
$available_external_user = DB::getInstance()->query("SELECT * FROM user WHERE user_group = 4 && id NOT IN (SELECT user_id FROM client_user)");
$available_external_user_count = $available_external_user->count();
$i = 1;

if ($available_external_user_count) {
	foreach ($available_external_user->results() as $available_external_user_data) {
		
		$available_external_user_name[$available_external_user_data->id] = $available_external_user_data->name;
		$available_external_user_last_name[$available_external_user_data->id] = $available_external_user_data->last_name;
		$available_external_user_email[$available_external_user_data->id] = $available_external_user_data->email;

		# By counter
		$available_external_user_id_user_id[$i] = $available_external_user_data->id;
		$available_external_user_id_name[$i] = ucwords(strtolower($available_external_user_data->name));
		$available_external_user_id_last_name[$i] = ucwords(strtolower($available_external_user_data->last_name));
		$available_external_user_id_email[$i] = $available_external_user_data->email;
		$i++;
	}
}

# Factoring company client assoc
$factoring_company_client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE client_id = " . $_GET['client_id']);
$factoring_company_client_assoc_count = $factoring_company_client_assoc->count();

if ($factoring_company_client_assoc_count) {
	foreach ($factoring_company_client_assoc->results() as $factoring_company_client_assoc_data) {

		$factoring_company_client_assoc_factoring_company_id = $factoring_company_client_assoc_data->factoring_company_id;
		$factoring_company_client_assoc_main = $factoring_company_client_assoc_data->main;
		$factoring_company_client_assoc_alt = $factoring_company_client_assoc_data->alt;
		$factoring_company_client_assoc_counter = $factoring_company_client_assoc_data->counter;
		$factoring_company_client_assoc_invoice_counter = $factoring_company_client_assoc_data->invoice_counter;
	}
}

# Get schedules for a client id	
$schedule_all_client_id = DB::getInstance()->query("SELECT 	

factoring_company_schedule.data_id, 
factoring_company_schedule.client_assoc_id, 
factoring_company_schedule.counter AS factoring_company_schedule_counter, 
factoring_company_schedule.fee_option, 
factoring_company_schedule.payment_confirmation, 
factoring_company_schedule.payment_confirmation_added, 
factoring_company_schedule.added AS factoring_company_schedule_added, 
factoring_company_client_assoc.factoring_company_id, 
factoring_company_client_assoc.client_id, 
factoring_company_client_assoc.main, 
factoring_company_client_assoc.alt, 
factoring_company_client_assoc.counter AS factoring_company_client_assoc_counter,  
factoring_company_client_assoc.invoice_counter, 
factoring_company_client_assoc.added, 
factoring_company_client_assoc.user_id

FROM factoring_company_schedule 
LEFT JOIN factoring_company_client_assoc 
ON factoring_company_schedule.client_assoc_id=factoring_company_client_assoc.data_id 
WHERE client_id = " . $_GET['client_id'] . " ORDER BY factoring_company_schedule_counter DESC");

$schedule_all_client_id_count = $schedule_all_client_id->count();
$i = 1;

foreach ($schedule_all_client_id->results() as $schedule_all_client_id_data) {
	
	$schedule_all_client_id_data_id[$i] = $schedule_all_client_id_data->data_id;
	$schedule_all_client_id_client_assoc_id[$i] = $schedule_all_client_id_data->client_assoc_id;
	$schedule_all_client_id_counter[$i] = $schedule_all_client_id_data->factoring_company_schedule_counter;
	$schedule_all_client_id_fee_option[$i] = $schedule_all_client_id_data->fee_option;
	$schedule_all_client_id_payment_confirmation[$i] = $schedule_all_client_id_data->payment_confirmation;
	$schedule_all_client_id_payment_confirmation_added[$i] = $schedule_all_client_id_data->payment_confirmation_added;
	$schedule_all_client_id_created[$i] = date('m/d/y', strtotime($schedule_all_client_id_data->factoring_company_schedule_added));

	$schedule_all_client_id_factoring_company_id[$i] = $schedule_all_client_id_data->factoring_company_id;
	$schedule_all_client_id_main[$i] = $schedule_all_client_id_data->main;
	$schedule_all_client_id_alt[$i] = $schedule_all_client_id_data->alt;
	$schedule_all_client_id_current_counter[$i] = $schedule_all_client_id_data->factoring_company_client_assoc_counter;
	$schedule_all_client_id_invoice_counter[$i] = $schedule_all_client_id_data->invoice_counter;
	$schedule_all_client_id_added[$i] = $schedule_all_client_id_data->added;
	$schedule_all_client_id_user_id[$i] = $schedule_all_client_id_data->user_id;

	$schedule_all_client_id_load_id[] = $schedule_all_client_id_data->load_id;
	
	# Get load count for this schedule
	$schedule_all_client_id_load = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE schedule_id = " . $schedule_all_client_id_data_id[$i]);
	$schedule_all_client_id_load_count = $schedule_all_client_id_load->count();

	$i++;
}
