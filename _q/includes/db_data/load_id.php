<?php

# Get all data related to this load id
$load_id = DB::getInstance()->query("SELECT * FROM loader_load WHERE load_id = " . $_GET['load_id']);

foreach ($load_id->results() as $load_id_data) {
	
	$load_id_entry_id = $load_id_data->entry_id;
	$load_id_broker_id = $load_id_data->broker_id;
	$load_id_load_number = $load_id_data->load_number;
	$load_id_line_haul = $load_id_data->line_haul;
	$load_id_avg_diesel_price = $load_id_data->avg_diesel_price;
	$load_id_weight = $load_id_data->weight;
	$load_id_miles = $load_id_data->miles;
	$load_id_deadhead = $load_id_data->deadhead;
	$load_id_reference = $load_id_data->reference;
	$load_id_commodity = $load_id_data->commodity;
	$load_id_equipment = $load_id_data->equipment;
	$load_id_notes = $load_id_data->notes;
	$load_id_broker_name_number = $load_id_data->broker_name_number;
	$load_id_broker_email = strtolower($load_id_data->broker_email);
	$load_id_billing_status = $load_id_data->billing_status;
	$load_id_billing_date = date('m/d/Y', strtotime($load_id_data->billing_date));
	$load_id_load_lock = $load_id_data->load_lock;
	$load_id_load_status = $load_id_data->load_status;
	$load_id_added = date('m/d/Y', strtotime($load_id_data->added));
	$load_id_added_by = $load_id_data->added_by;
	$load_id_user_id = $load_id_data->user_id;
}

# Get all data related to this entry_id
$entry_id = DB::getInstance()->query("SELECT * FROM loader_entry WHERE data_id = " . $load_id_entry_id);
foreach ($entry_id->results() as $entry_id_data) {

	$entry_id_driver_id = $entry_id_data->driver_id;
	$entry_id_added = date('m/d/Y', strtotime($entry_id_data->added));
}

# Get all data related to this client_user_id
$client_user_id = DB::getInstance()->query("SELECT * FROM client_user WHERE user_id = " . $entry_id_driver_id);

foreach ($client_user_id->results() as $client_user_id_data) {
	
	$client_user_id_client_id = $client_user_id_data->client_id;
	$client_user_id_data_id = $client_user_id_data->data_id;
	$client_user_id_user_type = $client_user_id_data->user_type;
	$client_user_id_user_manager= $client_user_id_data->usermanager;
}

# Get all data related to this user_id (driver)
$user_id = DB::getInstance()->query("SELECT * FROM user WHERE id = " . $entry_id_driver_id);

foreach ($user_id->results() as $user_id_data) {
	
	$user_id_email = strtolower($user_id_data->email);
	$user_id_name = $user_id_data->name;
	$user_id_last_name = $user_id_data->last_name;
	$user_id_second_last_name = $user_id_data->second_last_name;
	$user_id_phone_number_01 = $user_id_data->phone_number_01;
	$user_id_city = $user_id_data->city;
	$user_id_state_id = $user_id_data->state_id;
	$user_id_license_number = $user_id_data->license_number;
	$user_id_zip_code = $user_id_data->zip_code;
	$user_id_added = $user_id_data->added;
}

# Get all data related to this client_id
$client_id = DB::getInstance()->query("SELECT * FROM client WHERE data_id = " . $client_user_id_client_id);

foreach ($client_id->results() as $client_id_data) {
	
	$client_id_company_name = $client_id_data->company_name;
	$client_id_mc_number = $client_id_data->mc_number;
	$client_id_us_dot_number = $client_id_data->us_dot_number;
	$client_id_ein_number = $client_id_data->ein_number;
	$client_id_phone_number_01 = $client_id_data->phone_number_01;
	$client_id_phone_number_02 = $client_id_data->phone_number_02;
	$client_id_address_line_1 = $client_id_data->address_line_1;
	$client_id_address_line_2 = $client_id_data->address_line_2;
	$client_id_city = $client_id_data->city;
	$client_id_state_id = $client_id_data->state_id;
	$client_id_zip_code = $client_id_data->zip_code;
	$client_id_mailing_use_physical = $client_id_data->mailing_use_physical;
	$client_id_billing_address_line_1 = $client_id_data->billing_address_line_1;
	$client_id_billing_address_line_2 = $client_id_data->billing_address_line_2;
	$client_id_billing_city = $client_id_data->billing_city;
	$client_id_billing_state_id = $client_id_data->billing_state_id;
	$client_id_billing_zip_code = $client_id_data->billing_zip_code;
	$client_id_invoice_color = $client_id_data->invoice_color;
	$client_id_rate_id = $client_id_data->rate_id;
	$client_id_status = $client_id_data->status;
	$client_id_added = $client_id_data->added;
}

# Get all data related to this broker_id
$broker_id = DB::getInstance()->query("SELECT * FROM broker WHERE data_id = " . $load_id_broker_id);

foreach ($broker_id->results() as $broker_id_data) {
	
	$broker_id_data_id = $broker_id_data->data_id;
	$broker_id_company_name = $broker_id_data->company_name;
	$broker_id_phone_number_01 = $broker_id_data->phone_number_01;
	$broker_id_accounts_payable_number = $broker_id_data->accounts_payable_number;
	$broker_id_fax_number = $broker_id_data->fax_number;
	$broker_id_quickpay = $broker_id_data->quickpay;
	$broker_id_quickpay_email = strtolower($broker_id_data->quickpay_email);
	$broker_id_address_line_1 = $broker_id_data->address_line_1;
	$broker_id_address_line_2 = $broker_id_data->address_line_2;
	$broker_id_address_line_3 = $broker_id_data->address_line_3;
	$broker_id_city = $broker_id_data->city;
	$broker_id_state_id = $broker_id_data->state_id;
	$broker_id_zip_code = $broker_id_data->zip_code;
	$broker_id_status = $broker_id_data->status;
	$broker_id_do_not_use_reason = $broker_id_data->do_not_use_reason;
	$broker_id_added = $broker_id_data->added;
	$broker_id_user_id = $broker_id_data->user_id;
}

# Get all data related to this checkpoint_id
$checkpoint_id = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . " ORDER BY date_time ASC");
$checkpoint_id_count = $checkpoint_id->count();
$i = 1;

foreach ($checkpoint_id->results() as $checkpoint_id_data) {
	
	$checkpoint_id_load_id[$i] = $checkpoint_id_data->load_id;
	$checkpoint_id_checkpoint_id[$i] = $checkpoint_id_data->checkpoint_id;
	$checkpoint_id_date_time[$i] = date('m/d/Y h:i a', strtotime($checkpoint_id_data->date_time));
	$checkpoint_id_date_time_2[$i] = date('D m/d/Y', strtotime($checkpoint_id_data->date_time));
	$checkpoint_id_line_1[$i] = $checkpoint_id_data->line_1;
	$checkpoint_id_line_2[$i] = $checkpoint_id_data->line_2;
	$checkpoint_id_city[$i] = $checkpoint_id_data->city;
	$checkpoint_id_state_id[$i] = $checkpoint_id_data->state_id;
	$checkpoint_id_zip_code[$i] = $checkpoint_id_data->zip_code;
	$checkpoint_id_contact[$i] = $checkpoint_id_data->contact;
	$checkpoint_id_appointment[$i] = $checkpoint_id_data->appointment;
	$checkpoint_id_notes[$i] = $checkpoint_id_data->notes;
	$checkpoint_id_data_type[$i] = $checkpoint_id_data->data_type;
	$checkpoint_id_added[$i] = date('m/d/Y h:i a', strtotime($checkpoint_id_data->added));
	$checkpoint_id_user_id[$i] = $checkpoint_id_data->user_id;
	$checkpoint_id_status[$i] = $checkpoint_id_data->status;
	$i++;
}

# Check that at least 1 pick and 1 drop exist
# Set $checkpoint_count to 0
$checkpoint_count = 0;

# Iterate through results
for ($i = 1; $i <= $checkpoint_id_count ; $i++) { 
	
	# If found, add 1 to $checkpoint_count
	$checkpoint_id_data_type[$i] == 0 ? $checkpoint_count += 1 : '';
	$checkpoint_id_data_type[$i] == 1 ? $checkpoint_count += 1 : '';
}

# Get all schedule load data related to this load_id
$schedule_load = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE load_id = " . $_GET['load_id']);
$schedule_load_count = $schedule_load->count();

if ($schedule_load_count) {
	foreach ($schedule_load->results() as $schedule_load_data) {
		
		$schedule_load_schedule_id = $schedule_load_data->schedule_id;
		$schedule_load_invoice_number = $schedule_load_data->invoice_number;
	}	
}

# Get all schedule data related to this $schedule_load_schedule_id
$schedule = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE data_id = " . $schedule_load_schedule_id);
$schedule_count = $schedule->count();

foreach ($schedule->results() as $schedule_data) {
	
	$schedule_client_assoc_id = $schedule_data->client_assoc_id;
	$schedule_counter = $schedule_data->counter;
	$schedule_fee_option = $schedule_data->fee_option;
	$schedule_payment_confirmation = $schedule_data->payment_confirmation;
	$schedule_payment_confirmation_added = date('m/d/Y', strtotime($schedule_data->payment_confirmation_added));
	$schedule_added = date('m/d/Y', strtotime($schedule_data->added));
}

# Get all schedule loads related to this $schedule_load_schedule_id
$schedule_load_list = DB::getInstance()->query("SELECT factoring_company_schedule_load.load_id, factoring_company_schedule_load.invoice_number, loader_load.entry_id, loader_load.load_number FROM factoring_company_schedule_load LEFT JOIN loader_load ON factoring_company_schedule_load.load_id=loader_load.load_id WHERE schedule_id = " . $schedule_load_schedule_id);
$schedule_load_list_count = $schedule_load_list->count();
$i = 1;

if ($schedule_load_list_count) {
	foreach ($schedule_load_list->results() as $schedule_load_list_data) {
		
		$schedule_load_list_load_id[$i] = $schedule_load_list_data->load_id;
		$schedule_load_list_invoice_number[$i] = $schedule_load_list_data->invoice_number;
		$schedule_load_list_entry_id[$i] = $schedule_load_list_data->entry_id;
		$schedule_load_list_load_number[$i] = $schedule_load_list_data->load_number;
		$i++;
	}	
}
 
# If $schedule_client_assoc_id is set, get all data related to this factoring_company_client_assoc, else get all data related to this client_id
$schedule_client_assoc_id ? $value = "data_id = " . $schedule_client_assoc_id : $value = "client_id = " . $client_user_id_client_id;
$schedule_client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE " . $value);
$schedule_client_assoc_count = $schedule_client_assoc->count();

foreach ($schedule_client_assoc->results() as $schedule_client_assoc_data) {
	
	$client_assoc_data_id = $schedule_client_assoc_data->data_id;
	$client_assoc_factoring_company_id = $schedule_client_assoc_data->factoring_company_id;
	$client_assoc_main = $schedule_client_assoc_data->main;
	$client_assoc_alt = $schedule_client_assoc_data->alt;
	$client_assoc_counter = $schedule_client_assoc_data->counter;
	# 02/12/16 - $last_invoice_number is added here to fix the invoice number that doesn't get picked up on first time schedules 
	# (where the number should be taken from here)
	# To make all changes here, substract 1 to the number because the add load form will add 1.
	$last_invoice_number = $schedule_client_assoc_data->invoice_counter - 1;
	$client_assoc_added = date('m/d/Y', strtotime($schedule_client_assoc_data->added));
	$client_assoc_user_id = $schedule_client_assoc_data->user_id;
}

# Factoring company
$factoring_company = DB::getInstance()->query("SELECT * FROM factoring_company WHERE data_id = " . $client_assoc_factoring_company_id);
$factoring_company_count = $factoring_company->count();

if ($factoring_company_count) {
	foreach ($factoring_company->results() as $factoring_company_data) {

		$factoring_company_requires_soar = $factoring_company_data->requires_soar;
		$factoring_company_status = $factoring_company_data->status;
		$factoring_company_name = html_entity_decode($factoring_company_data->name);
	}
}

# Get all charges data related to this load_id
$other_charges = DB::getInstance()->query("SELECT * FROM loader_load_other_charges WHERE load_id = " . $_GET['load_id'] . " ORDER BY price DESC");
$other_charges_count = $other_charges->count();
$i = 1;
$other_charges_price_sum = 0;

foreach ($other_charges->results() as $other_charges_data) {
	
	$other_charges_data_id[$i] = $other_charges_data->data_id;
	$other_charges_item[$i] = html_entity_decode($other_charges_data->item);
	$other_charges_price[$i] = $other_charges_data->price;
	$other_charges_user_id[$i] = $other_charges_data->user_id;
	$other_charges_added[$i] = date('m/d/Y', strtotime($other_charges_data->added));

	$other_charges_price_sum += $other_charges_price[$i];
	$i++;
}

# Get all notes data related to this load_id
$load_notes = DB::getInstance()->query("SELECT * FROM loader_load_note WHERE load_id = " . $_GET['load_id'] . " ORDER BY added DESC");
$load_notes_count = $load_notes->count();
$i = 1;

foreach ($load_notes->results() as $load_notes_data) {
	
	$load_notes_note_id[$i] = $load_notes_data->data_id;
	$load_notes_note[$i] = html_entity_decode($load_notes_data->note);
	$load_notes_important[$i] = $load_notes_data->important;
	$load_notes_type[$i] = $load_notes_data->type;
	$load_notes_added[$i] = date('m/d/Y', strtotime($load_notes_data->added));
	$load_notes_user_id[$i] = $load_notes_data->user_id;
	$i++;
}

# File data (Rate confirmation, bol, raw bol, quickpay invoice, payment confirmation, soar, schedule invoice), (file directories)
$files = '/home/' . $rootFolder . '/public_html/files/';

file_exists($files . 'rate-confirmation-' . $load_id_entry_id . '-' . $_GET['load_id'] . '.pdf') ? $rate_confirmation = 1 : '';
file_exists($files . 'bol-' . $load_id_entry_id . '-' . $_GET['load_id'] . '.pdf') ? $bol = 1 : '';
file_exists($files . 'raw-bol' . $load_id_entry_id . '-' . $_GET['load_id'] . '.jpg') ? $raw_bol = 1 : '';
file_exists($files . 'quickpay-invoice/quickpay-invoice-' . $load_id_entry_id . '-' . $_GET['load_id'] . '.pdf') ? $quickpay_invoice = 1 : '';
file_exists($files . 'payment-confirmation-' . $load_id_entry_id . '-' . $_GET['load_id'] . '.pdf') ? $payment_confirmation = 1 : '';
($schedule_load_count && file_exists($files . 'schedule/soar-' . $schedule_load_schedule_id . '.pdf')) ? $soar_file = 1 : $soar_file = null;

### THIS CODE IS REPEATED ###
# Make array of schedule_ids for this $schedule_client_assoc_id
$schedule_id_array = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE client_assoc_id = " . $client_assoc_data_id);

if ($schedule_id_array->count()) {
	foreach ($schedule_id_array->results() as $schedule_id_array_data) {
		
		# Array
		$schedule_id_per_client_assoc_array[] = $schedule_id_array_data->data_id;
	}

	# Get last invoice_number counter for this schedule id
	$last_invoice = DB::getInstance()->query("SELECT invoice_number FROM factoring_company_schedule_load WHERE schedule_id IN (" . implode(', ', $schedule_id_per_client_assoc_array) . ") ORDER BY invoice_number DESC LIMIT 1");

	if ($_SERVER['REMOTE_ADDR'] == '186.26.115.213') {
		var_dump($last_invoice);
	}

	foreach ($last_invoice->results() as $last_invoice_data) {
		$last_invoice_number = $last_invoice_data->invoice_number;
	}	
}
### THIS CODE IS REPEATED END ###
