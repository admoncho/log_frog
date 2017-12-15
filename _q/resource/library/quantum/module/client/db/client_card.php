<?php

# client_card
$client_card = DB::getInstance()->query("SELECT * FROM client WHERE data_id = " . Input::get('client_card'));

foreach ($client_card->results() as $client_card_data) {
	
	$client_card_company_name = $client_card_data->company_name;
	$client_card_mc_number = $client_card_data->mc_number;
	$client_card_us_dot_number = $client_card_data->us_dot_number;
	$client_card_ein_number = $client_card_data->ein_number;
	$client_card_chr_t = $client_card_data->chr_t;
	$client_card_main_contact = html_entity_decode($client_card_data->main_contact);
	$client_card_phone_number_01 = $client_card_data->phone_number_01;
	$client_card_phone_number_02 = $client_card_data->phone_number_02;
	$client_card_phone_number_03 = $client_card_data->phone_number_03; // Per safer
	$client_card_invoice_color = html_entity_decode($client_card_data->invoice_color);
	$client_card_rate_id = $client_card_data->rate_id;
	$client_card_scac_code = $client_card_data->scac_code;
	$client_card_status = $client_card_data->status;
	$client_card_formation_date = date('M d, Y', strtotime($client_card_data->formation_date));
	$client_card_added = date('M d, Y', strtotime($client_card_data->added));
}

# client address
$client_card_address = DB::getInstance()->query("SELECT * FROM client_address WHERE client_id = " . $_POST['client_card']);
$client_card_address_count = $client_card_address->count();
$i = 1;

if ($client_card_address_count) {
	foreach ($client_card_address->results() as $client_card_address_data) {
		
		$client_card_address_data_id[$i] = $client_card_address_data->data_id;
		$client_card_address_type[$i] = $client_card_address_data->address_type; // 1 physical 2 mailing
		$client_card_address_line_1[$i] = html_entity_decode($client_card_address_data->line_1);
		$client_card_address_line_2[$i] = html_entity_decode($client_card_address_data->line_2);
		$client_card_address_line_3[$i] = html_entity_decode($client_card_address_data->line_3);
		$client_card_address_city[$i] = html_entity_decode($client_card_address_data->city);
		$client_card_address_state_id[$i] = $client_card_address_data->state_id;
		$client_card_address_zip_code[$i] = html_entity_decode($client_card_address_data->zip_code);
		$client_card_address_mailing_use_physical[$i] = $client_card_address_data->mailing_use_physical; // 1 yes
		$i++;
	}
}

# Client user
# Lists all users associated with a client profile
$cc_client_user = DB::getInstance()->query("SELECT * FROM client_user WHERE client_id = " . $_POST['client_card'] . " ORDER BY user_type ASC");
$cc_client_user_count = $cc_client_user->count();
$i = 1;

if ($cc_client_user_count) {
	foreach($cc_client_user->results() as $cc_client_user_data) {

		$cc_client_user_user_id[$i] = $cc_client_user_data->user_id;
		$cc_client_user_client_id[$i] = $cc_client_user_data->client_id;
		$cc_client_user_data_id[$i] = $cc_client_user_data->data_id;
		$cc_client_user_user_type[$i] = $cc_client_user_data->user_type; // 0 owner 1 owner/operator 2 driver
		$cc_client_user_user_manager[$i] = $cc_client_user_data->user_manager;

		# Owner has drivers?
		$is_user_manager = DB::getInstance()->query("SELECT * FROM client_user WHERE user_manager = " . $cc_client_user_user_id[$i]);
		$is_user_manager_count[$i] = $is_user_manager->count();

		$i++;
	}
}

# Get number of owners
$cc_client_user_owner = DB::getInstance()->query("SELECT * FROM client_user WHERE client_id = " . $_GET['client_id'] . " && user_type = 0");
$cc_client_user_owner_count = $cc_client_user_owner->count();

# Get number of owner/operators
$cc_client_user_owner_operator = DB::getInstance()->query("SELECT * FROM client_user WHERE client_id = " . $_GET['client_id'] . " && user_type = 1");
$cc_client_user_owner_operator_count = $cc_client_user_owner_operator->count();

# Get number of drivers
$cc_client_user_driver = DB::getInstance()->query("SELECT * FROM client_user WHERE client_id = " . $_GET['client_id'] . " && user_type = 2");
$cc_client_user_driver_count = $cc_client_user_driver->count();

/*

# client_broker_assoc
$client_broker_assoc = DB::getInstance()->query("SELECT * FROM client_broker_assoc WHERE client_id = " . $_GET['client_id']);
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
*/

# client_insurance
$client_insurance = DB::getInstance()->query("SELECT * FROM client_insurance WHERE client_id = " . $_POST['client_card']);
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

# Factoring company client assoc
$cc_factoring_company_client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE client_id = " . $_POST['client_card']);
$cc_factoring_company_client_assoc_count = $cc_factoring_company_client_assoc->count();

if ($cc_factoring_company_client_assoc_count) {
	foreach ($cc_factoring_company_client_assoc->results() as $cc_factoring_company_client_assoc_data) {

		$cc_factoring_company_client_assoc_factoring_company_id = $cc_factoring_company_client_assoc_data->factoring_company_id;
		$cc_factoring_company_client_assoc_main = $cc_factoring_company_client_assoc_data->main;
		$cc_factoring_company_client_assoc_alt = $cc_factoring_company_client_assoc_data->alt;
		$cc_factoring_company_client_assoc_counter = $cc_factoring_company_client_assoc_data->counter;
		$cc_factoring_company_client_assoc_invoice_counter = $cc_factoring_company_client_assoc_data->invoice_counter;
	}
}

/*

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
*/

# tractor
include 'client_user_tractor.php';
# trailer
include 'client_tractor_trailer.php';
