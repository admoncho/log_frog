<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# Brokers
if (isset($_GET['broker_id'])) {
	
	$clause = " WHERE data_id = " . $_GET['broker_id'];
} elseif (isset($_GET['client_id']) && isset($_GET['add_broker_assoc'])) {

	include(LIBRARY_PATH . '/quantum/module/client/db/client.php');

	if (isset($broker_assoc_id_array)) {
		
		$clause = " WHERE data_id NOT IN (" . implode(', ', $broker_assoc_id_array) . ") && quickpay = 1 ORDER BY company_name ASC";
	} else {

		$clause = " WHERE quickpay = 1 ORDER BY company_name ASC";
	}
} elseif ($_SESSION['$clean_php_self'] == '/dashboard/loader/index.php') {

	# Loader main specific connection
	$clause = "";
} else {

	$clause = " ORDER BY company_name ASC";
}

# This file name
# Keep this code after including db/client.php
$this_file_name = basename(__FILE__, '.php');

# broker
$broker = DB::getInstance()->query("SELECT * FROM broker " . $clause); // New version
// $broker = DB::getInstance()->query("SELECT * FROM user_e_profile_broker " . $clause); // Old version
$broker_count = $broker->count();
$i = 1;

if ($broker_count) {
	
	# Iterate through items
	foreach ($broker->results() as $broker_data) {
		
		$broker_data_id[$i] = $broker_data->data_id;
		$broker_data_id_did[$broker_data->data_id] = $broker_data->data_id;
		$broker_company_name[$i] = html_entity_decode($broker_data->company_name);
		$broker_phone_number_01[$i] = $broker_data->phone_number_01;
		$broker_accounts_payable_number[$i] = $broker_data->accounts_payable_number;
		$broker_fax_number[$i] = $broker_data->fax_number;
		$broker_quickpay[$i] = $broker_data->quickpay;
		$broker_quickpay_email[$i] = $broker_data->quickpay_email;
		$broker_status[$i] = $broker_data->status; // 0 inactive 1 active 2 do not use
		$broker_do_not_use_reason[$i] = html_entity_decode($broker_data->do_not_use_reason);
		$broker_added[$i] = date('M d, Y', strtotime($broker_data->added));
		$broker_user_id[$i] = $broker_data->user_id;

		$broker_id_company_name[$broker_data->data_id] = html_entity_decode($broker_data->company_name);

		# Spot and block adding brokers that already exist
		if (Input::get('_controller_' . $this_file_name) == 'add_broker') {

			if (htmlentities(Input::get('company_name'), ENT_QUOTES) == $broker_company_name[$i]) {
				
				# Duplicate entry
				$broker_duplicate_entry = 1;
			}
		}

		$i++;
		
		$broker_id_company_name[$broker_data->data_id] = html_entity_decode($broker_data->company_name);
	}

	# Active radio button lock
	# Hide this conditional from loader's main page
	if ($_SESSION['$clean_php_self'] != '/dashboard/loader/index.php') {
		
		if (isset($_GET['broker_id'])) {

			# Locks the active radio button if quickpay is set for broker and there are
			# 0 entries on the "Broker Quickpay Service Fees ($loader_quickpay_service_fee_count)" section.
			if ($broker_quickpay[1] == 1 && !$loader_quickpay_service_fee_count) {
				
				$lock_quickpay_no_service_fee = 1;
			} else {

				$lock_quickpay_no_service_fee = null;
			}
		}
	}
}

# Brokers ALT
# USED FOR THE QUICK JUMP SELECT (for quick access to other Brokers when in a broker)
$broker_ALT = DB::getInstance()->query("SELECT * FROM " . $this_file_name . " ORDER BY company_name ASC");
$broker_ALT_count = $broker_ALT->count();
$i = 1;

if ($broker_ALT_count) {
	
	# Iterate through items
	foreach ($broker_ALT->results() as $broker_ALT_data) {
		
		$broker_ALT_id[$i] = $broker_ALT_data->data_id;
		$broker_ALT_company_name[$i] = html_entity_decode($broker_ALT_data->company_name);
		$broker_ALT_status[$i] = $broker_data->status; // 0: inactive 1: act
		$i++;
	}
}

if (isset($_GET['broker_id'])) {

	# Broker address

	# Alter the clause if isset($_GET['address_id']) 
	isset($_GET['address_id']) ? $clause = "WHERE data_id = " . $_GET['address_id'] : $clause = "WHERE broker_id = " . $_GET['broker_id'];

	$broker_address = DB::getInstance()->query("SELECT * FROM broker_address " . $clause);
	$broker_address_count = $broker_address->count();
	$i = 1;

	if ($broker_address_count) {
		foreach ($broker_address->results() as $broker_address_data) {
			
			$broker_address_data_id[$i] = $broker_address_data->data_id;
			$broker_address_type[$i] = $broker_address_data->address_type; // 1 physical 2 mailing
			$broker_address_line_1[$i] = html_entity_decode($broker_address_data->line_1);
			$broker_address_line_2[$i] = html_entity_decode($broker_address_data->line_2);
			$broker_address_line_3[$i] = html_entity_decode($broker_address_data->line_3);
			$broker_address_city[$i] = html_entity_decode($broker_address_data->city);
			$broker_address_state_id[$i] = $broker_address_data->state_id;
			$broker_address_zip_code[$i] = html_entity_decode($broker_address_data->zip_code);
			$i++;
		}
	}	
}

# Broker service fee
if (isset($_GET['broker_id'])) {
	
	# Alter the clause if isset($_GET['contact_data_id']) 
	isset($_GET['service_fee_id']) ? $clause = "WHERE data_id = " . $_GET['service_fee_id'] : $clause = "WHERE broker_id = " . $_GET['broker_id'] . " ORDER BY fee ASC";
	
	$broker_quickpay_service_fee = DB::getInstance()->query("SELECT * FROM broker_quickpay_service_fee " . $clause);
} else {

	$broker_quickpay_service_fee = DB::getInstance()->query("SELECT * FROM broker_quickpay_service_fee") ;
}

$broker_quickpay_service_fee_count = $broker_quickpay_service_fee->count();
$i = 1;

if ($broker_quickpay_service_fee_count) {
	foreach ($broker_quickpay_service_fee->results() as $broker_quickpay_service_fee_data) {

		$broker_quickpay_service_fee_broker_id[$i] = $broker_quickpay_service_fee_data->broker_id;
		$broker_quickpay_service_fee_data_id[$i] = $broker_quickpay_service_fee_data->data_id;
		$broker_quickpay_service_fee_fee[$i] = $broker_quickpay_service_fee_data->fee;
		$broker_quickpay_service_fee_method_id[$i] = $broker_quickpay_service_fee_data->method_id;
		$broker_quickpay_service_fee_number_of_days[$i] = $broker_quickpay_service_fee_data->number_of_days;

		$i++;		
	}
}
