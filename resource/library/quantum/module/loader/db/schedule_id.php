<?php
session_start();
ob_start();
# Declare file directory
$file_directory = $_SESSION['ProjectPath'] . '/files/';

# Declare schedule directory
$schedule_directory = $_SESSION['ProjectPath'] . '/files/schedule/';

# Get all data related to this schedule id
$factoring_company_schedule = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE data_id = " . $_GET['schedule_id']);
$factoring_company_schedule_count = $factoring_company_schedule->count();

# Go on if found
if ($factoring_company_schedule_count) {
	
	# factoring_company_schedule data
	foreach ($factoring_company_schedule->results() as $factoring_company_schedule_data) {
		
		$schedule_client_assoc_id = $factoring_company_schedule_data->client_assoc_id;
		$schedule_counter = $factoring_company_schedule_data->counter;
		$schedule_payment_confirmation = $factoring_company_schedule_data->payment_confirmation;
	}

	# Get factoring_company_client_assoc data
	$factoring_company_client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE data_id = " . $schedule_client_assoc_id);
	
	foreach ($factoring_company_client_assoc->results() as $factoring_company_client_assoc_data) {
		
		$client_assoc_id = $factoring_company_client_assoc_data->data_id;
		$client_assoc_factoring_company_id = $factoring_company_client_assoc_data->factoring_company_id;
		$client_assoc_factoring_company_client_id = $factoring_company_client_assoc_data->client_id;
		$client_assoc_factoring_company_main = $factoring_company_client_assoc_data->main;
		$client_assoc_factoring_company_alt = $factoring_company_client_assoc_data->alt;
		$client_assoc_factoring_company_current_counter = $factoring_company_client_assoc_data->counter;
	}
}

# Factoring company service fee
$factoring_company_service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee WHERE factoring_company_id = " . $client_assoc_factoring_company_id);
$factoring_company_service_fee_count = $factoring_company_service_fee->count();
$i = 1;

if ($factoring_company_service_fee_count) {
	
	foreach ($factoring_company_service_fee->results() as $factoring_company_service_fee_data) {
		
		// counter
		$factoring_company_service_fee_factoring_company_id[$i] = $factoring_company_service_fee_data->factoring_company_id;
		$factoring_company_service_fee_data_id[$i] = $factoring_company_service_fee_data->data_id;
		$factoring_company_service_fee_fee[$i] = $factoring_company_service_fee_data->fee;
		$factoring_company_service_fee_method_id[$i] = $factoring_company_service_fee_data->method_id;
		$factoring_company_service_fee_number_of_days[$i] = $factoring_company_service_fee_data->number_of_days;

		// data_id
		$factoring_company_service_fee_factoring_company_id_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->factoring_company_id;
		$factoring_company_service_fee_fee_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->fee;
		$factoring_company_service_fee_method_id_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->method_id;
		$factoring_company_service_fee_number_of_days_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->number_of_days;

		$i++;		
	}
}

# Get load data for this schedule id
$factoring_company_schedule_load = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE schedule_id = " . $_GET['schedule_id']);
$factoring_company_schedule_load_count = $factoring_company_schedule_load->count();
$i = 1;

# Go on if count
if ($factoring_company_schedule_load_count) {
	
	foreach ($factoring_company_schedule_load->results() as $factoring_company_schedule_load_data) {
		
		# Make array with list of load ids
		$load_id[] = $factoring_company_schedule_load_data->load_id;
		$i++;
	}

	# Get other charges data for loads in this schedule
	$schedule_other_charges = DB::getInstance()->query("SELECT * FROM loader_load_other_charges WHERE load_id IN (" . implode(', ', $load_id) . ")");
	$schedule_other_charges_count = $schedule_other_charges->count();
	$schedule_other_charges_counter = 1;

	if ($schedule_other_charges_count) {
		
		foreach ($schedule_other_charges->results() as $schedule_other_charges_data) {
			
			$schedule_other_charges_price[$schedule_other_charges_counter] = $schedule_other_charges_data->price;
			$schedule_other_charges_counter++;

			# Sum other charge by load_id
			$sum_other_charge[$schedule_other_charges_data->load_id]+= $schedule_other_charges_data->price;
		}
	}

	# Get load data
	# This connection has special settings
	$load_list = DB::getInstance()->query("SELECT loader_load.entry_id, loader_load.broker_id, loader_load.load_id, loader_load.load_number, loader_load.line_haul, loader_load.miles, loader_load.reference, loader_load.billing_status, loader_load.user_id, loader_load.commodity, loader_load.first_checkpoint, loader_load.last_checkpoint, loader_load.load_lock, loader_load.load_status, loader_entry.driver_id, loader_entry.data_id FROM loader_load RIGHT JOIN loader_entry ON loader_load.entry_id=loader_entry.data_id WHERE loader_load.load_id IN (" . implode(', ', $load_id) . ") ORDER BY data_id ASC");
	$load_list_count = $load_list->count();
	$i = 1;

	if ($load_list_count) {
		
		foreach ($load_list->results() as $load_list_data) {
			
			# By counter
			# loader_load
			$load_list_entry_id[$i] = $load_list_data->entry_id;
			$load_list_broker_id[$i] = $load_list_data->broker_id;
			$load_list_load_number[$i] = $load_list_data->load_number;
			$load_list_load_id[$i] = $load_list_data->load_id;
			$load_list_line_haul[$i] = $load_list_data->line_haul;
			$load_list_miles[$i] = $load_list_data->miles;
			$load_list_reference[$i] = html_entity_decode($load_list_data->reference);
			$load_list_billing_status[$i] = $load_list_data->billing_status;
			$load_list_user_id[$i] = $load_list_data->user_id;
			$load_list_commodity[$i] = html_entity_decode($load_list_data->commodity);
			$load_list_first_checkpoint_date[$i] = date('m/d/y', strtotime($load_list_data->first_checkpoint));
			$load_list_first_checkpoint_time[$i] = date('G:i', strtotime($load_list_data->first_checkpoint));
			$load_list_last_checkpoint_date[$i] = date('m/d/y', strtotime($load_list_data->last_checkpoint));
			$load_list_last_checkpoint_time[$i] = date('G:i', strtotime($load_list_data->last_checkpoint));
			$load_list_load_lock[$i] = $load_list_data->load_lock;
			$load_list_load_status[$i] = $load_list_data->load_status;
			# loader_entry
			$load_list_driver_id[$i] = $load_list_data->driver_id;

			# Make driver id array
			$load_list_driver_id_array[] = $load_list_data->driver_id;

			# First checkpoint
			$load_first_checkpoint = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[$i] . " && data_type = 0 ORDER BY date_time ASC LIMIT 1");

			if ($load_first_checkpoint->count()) {
				
				foreach ($load_first_checkpoint->results() as $load_first_checkpoint_data) {
					
					$load_first_checkpoint_date[$i] = date('m/d/y', strtotime($load_first_checkpoint_data->date_time));
					$load_first_checkpoint_time[$i] = date('G:i', strtotime($load_first_checkpoint_data->date_time));
					$load_first_checkpoint_city[$i] = html_entity_decode($load_first_checkpoint_data->city);
					$load_first_checkpoint_state_id[$i] = $load_first_checkpoint_data->state_id;
					$load_first_checkpoint_zip_code[$i] = $load_first_checkpoint_data->zip_code;
				}
			}

			# Last checkpoint
			$load_last_checkpoint = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[$i] . " && data_type = 1 ORDER BY date_time DESC LIMIT 1");

			if ($load_last_checkpoint->count()) {
				
				foreach ($load_last_checkpoint->results() as $load_last_checkpoint_data) {
					
					$load_last_checkpoint_date[$i] = date('m/d/y', strtotime($load_last_checkpoint_data->date_time));
					$load_last_checkpoint_time[$i] = date('G:i', strtotime($load_last_checkpoint_data->date_time));
					$load_last_checkpoint_city[$i] = html_entity_decode($load_last_checkpoint_data->city);
					$load_last_checkpoint_state_id[$i] = $load_last_checkpoint_data->state_id;
					$load_last_checkpoint_zip_code[$i] = $load_last_checkpoint_data->zip_code;
				}
			}

			$i++;
		}
	}

	# Get schedule of accounts receivable file number of pages

	# Number of loads to display on each page
	$num_loads_per_page = 10;

	# Divide $factoring_company_schedule_load_count by $num_loads_per_page
	$page_math = $factoring_company_schedule_load_count / $num_loads_per_page;

	# Round up $page_math to get number of pages
	$soar_num_pages = ceil($page_math);

	# Make sum of line hauls
	$line_haul_sum = 0;
	for ($i = 1; $i <= $load_list_count ; $i++) {
		$line_haul_sum+= $load_list_line_haul[$i];
	}

	# Make sum of other charges
	$other_charges_sum = 0;
	for ($i = 1; $i <= $schedule_other_charges_count ; $i++) {
		$other_charges_sum+= $schedule_other_charges_price[$i];
	}

	# Add sum of other charges to sum of line hauls
	$schedule_amount = $line_haul_sum + $other_charges_sum;

	# Get emails for cc field, these are managers (owners or owner/operators)
	$schedule_load_manager_email = DB::getInstance()->query("SELECT * FROM client_user WHERE user_id IN (" . implode(', ', $load_list_driver_id_array) . ")");

	foreach ($schedule_load_manager_email->results() as $schedule_load_manager_email_data) {
		
		if ($schedule_load_manager_email_data->user_type == 2) {
			
			# Driver, get user_manager
			$driver_manager_id[] = $user_list_id_email[$schedule_load_manager_email_data->user_manager];
		} else {

			# Owner/operator
			$owner_id[] = $user_list_id_email[$schedule_load_manager_email_data->user_id];
		}
	}
}

# Get schedule notes
$schedule_note = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_note WHERE schedule_id = " . $_GET['schedule_id']);
$schedule_note_count = $schedule_note->count();
$i = 1;

foreach ($schedule_note->results() as $schedule_note_data) {

	$schedule_note_data_id[$i] = $schedule_note_data->data_id;
	$schedule_note_note[$i] = html_entity_decode($schedule_note_data->note);
	$schedule_note_type[$i] = $schedule_note_data->type;
	$schedule_note_added[$i] = date('m/d/Y', strtotime($schedule_note_data->added));
	$schedule_note_user_id[$i] = $schedule_note_data->user_id;

	# If $schedule_note_type[$i] == 2 save data_id for incorrect amount note
	$schedule_note_type[$i] == 2 ? $incorrect_amount_counter = $i : '';

	# If $schedule_note_type[$i] == 3 save data_id for close note
	$schedule_note_type[$i] == 3 ? $closing_note_counter = $i : '';

	$i++;
}

# Make array of schedule_ids for this $schedule_client_assoc_id
$schedule_id_array = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE client_assoc_id = " . $schedule_client_assoc_id);

foreach ($schedule_id_array->results() as $schedule_id_array_data) {
	
	# Array
	$schedule_id_per_client_assoc_array[] = $schedule_id_array_data->data_id;
}

# Get all schedule invoice numbers
# This block can replace $first_invoice and $last_invoice blocks after some analisis
$schedule_invoice = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE schedule_id = " . $_GET['schedule_id'] . " ORDER BY invoice_number ASC");
$i = 1;

foreach ($schedule_invoice->results() as $schedule_invoice_data) {
	$schedule_invoice_number[$i] = $schedule_invoice_data->invoice_number;
	$i++;
}

# Get first invoice_number counter for this schedule id
$first_invoice = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE schedule_id = " . $_GET['schedule_id'] . " ORDER BY invoice_number ASC LIMIT 1");

foreach ($first_invoice->results() as $first_invoice_data) {
	$first_invoice_number = $first_invoice_data->invoice_number;
}

# Get last invoice_number counter for this schedule id
$last_invoice = DB::getInstance()->query("SELECT invoice_number FROM factoring_company_schedule_load WHERE schedule_id IN (" . implode(', ', $schedule_id_per_client_assoc_array) . ") ORDER BY invoice_number DESC LIMIT 1");


foreach ($last_invoice->results() as $last_invoice_data) {
	$last_invoice_number = $last_invoice_data->invoice_number;
}

if ($_GET['fee_option']) {
	
	# Get Service fee data
	$service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee WHERE data_id = " . $_GET['fee_option']);

	foreach ($service_fee->results() as $service_fee_data) {
		
		$service_fee_method_id = $service_fee_data->method_id;
	}
}
