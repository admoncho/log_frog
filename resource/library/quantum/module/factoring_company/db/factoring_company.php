<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# This file name
$this_file_name = basename(__FILE__, '.php');

# Factoring companies
isset($_GET['factoring_company_id']) ? $clause = " WHERE data_id = " . $_GET['factoring_company_id'] : '';
isset($client_assoc_factoring_company_id) ? $clause = " WHERE data_id = " . $client_assoc_factoring_company_id : '';
isset($_POST['client_card']) ? $clause = " WHERE data_id = " . $cc_factoring_company_client_assoc_factoring_company_id : '';
!$_GET && !isset($_POST['client_card']) ? $clause = " ORDER BY name ASC" : '';
$_SESSION['$clean_php_self'] == '/dashboard/client/client.php' ? $clause = " ORDER BY name ASC" : '';

# factoring_company
$factoring_company = DB::getInstance()->query("SELECT * FROM " . $this_file_name . $clause);
$factoring_company_count = $factoring_company->count();
$i = 1;

if ($factoring_company_count) {
	
	# Iterate through items
	foreach ($factoring_company->results() as $factoring_company_data) {
		
		$factoring_company_id[$i] = $factoring_company_data->data_id;
		$factoring_company_name[$i] = html_entity_decode($factoring_company_data->name);
		# $factoring_company_name_alt[$i] exists because $factoring_company_name[$i]
		# is conflingting somewhere and the load page will only display the first character
		$factoring_company_name_alt[$i] = html_entity_decode($factoring_company_data->name);
		$factoring_company_uri[$i] = html_entity_decode($factoring_company_data->uri);
		$factoring_company_invoicing_email[$i] = html_entity_decode($factoring_company_data->invoicing_email);
		$factoring_company_phone_number_01[$i] = html_entity_decode($factoring_company_data->phone_number_01);
		$factoring_company_fax[$i] = html_entity_decode($factoring_company_data->fax);
		$factoring_company_batch_schedule[$i] = $factoring_company_data->batch_schedule; // 1: batch 2: schedule
		$factoring_company_requires_soar[$i] = $factoring_company_data->requires_soar; // 0: no 1: yes
		$factoring_company_open_hour[$i] = $factoring_company_data->open_hour;
		$factoring_company_close_hour[$i] = $factoring_company_data->close_hour;
		$factoring_company_time_zone[$i] = $factoring_company_data->time_zone;
		$factoring_company_status[$i] = $factoring_company_data->status; // 0: inactive 1: act
		$factoring_company_added[$i] = date('m/d/Y', strtotime($factoring_company_data->added));
		$factoring_company_user_id[$i] = $factoring_company_data->user_id;
		$i++;

		$factoring_company_id_name[$factoring_company_data->data_id] = html_entity_decode($factoring_company_data->name);
		$factoring_company_id_uri[$factoring_company_data->data_id] = html_entity_decode($factoring_company_data->uri);
		$factoring_company_id_invoicing_email[$factoring_company_data->data_id] = html_entity_decode($factoring_company_data->invoicing_email);
		$factoring_company_id_requires_soar[$factoring_company_data->data_id] = $factoring_company_data->requires_soar; // 0: no 1: yes
		$factoring_company_id_batch_schedule[$factoring_company_data->data_id] = $factoring_company_data->batch_schedule; // 1: batch 2: schedule
	}
}
		
# Factoring companies ALT
# USED FOR THE QUICK JUMP SELECT (for quick access to other factoring companies when in a factoring company)
$factoring_company_ALT = DB::getInstance()->query("SELECT * FROM " . $this_file_name . " ORDER BY name ASC");
$factoring_company_ALT_count = $factoring_company_ALT->count();
$i = 1;

if ($factoring_company_ALT_count) {
	
	# Iterate through items
	foreach ($factoring_company_ALT->results() as $factoring_company_ALT_data) {
		
		$factoring_company_ALT_id[$i] = $factoring_company_ALT_data->data_id;
		$factoring_company_ALT_name[$i] = html_entity_decode($factoring_company_ALT_data->name);
		$factoring_company_ALT_status[$i] = $factoring_company_data->status; // 0: inactive 1: act
		$i++;
	}
}

if (isset($_GET['factoring_company_id']) || isset($cc_factoring_company_client_assoc_factoring_company_id)) {

	# Alter the clause if isset($_GET['contact_data_id']) 
	if (isset($_GET['contact_data_id'])) {
		
		$clause = "WHERE data_id = " . $_GET['contact_data_id'];
	} elseif (isset($cc_factoring_company_client_assoc_factoring_company_id)) {
		
		$clause = "WHERE factoring_company_id = " . $cc_factoring_company_client_assoc_factoring_company_id . " ORDER BY name ASC";
	} else {

		$clause = "WHERE factoring_company_id = " . $_GET['factoring_company_id'] . " ORDER BY name ASC";
	}
	
	# factoring_company_contact
	$factoring_company_contact = DB::getInstance()->query("SELECT * FROM factoring_company_contact " . $clause);
	$factoring_company_contact_count = $factoring_company_contact->count();
	$i = 1;

	if ($factoring_company_contact_count) {
		foreach ($factoring_company_contact->results() as $factoring_company_contact_data) {

			$factoring_company_contact_data_id[$i] = $factoring_company_contact_data->data_id;
			$factoring_company_contact_name[$i] = html_entity_decode($factoring_company_contact_data->name);
			$factoring_company_contact_last_name[$i] = html_entity_decode($factoring_company_contact_data->last_name);
			$factoring_company_contact_title[$i] = html_entity_decode($factoring_company_contact_data->title);
			$factoring_company_contact_email[$i] = html_entity_decode($factoring_company_contact_data->email);
			$factoring_company_contact_phone_number_01[$i] = html_entity_decode($factoring_company_contact_data->phone_number_01);
			$i++;		
		}
	}

	# Factoring company address

	# Alter the clause if isset($_GET['address_id']) 
	if (isset($_GET['address_id'])) {
		
		$clause = "WHERE data_id = " . $_GET['address_id'];
	} elseif (isset($cc_factoring_company_client_assoc_factoring_company_id)) {
		
		$clause = "WHERE data_id = " . $cc_factoring_company_client_assoc_factoring_company_id;
	} else {

		$clause = "WHERE factoring_company_id = " . $_GET['factoring_company_id'];
	}

	$factoring_company_address = DB::getInstance()->query("SELECT * FROM factoring_company_address " . $clause);
	$factoring_company_address_count = $factoring_company_address->count();
	$i = 1;

	if ($factoring_company_address_count) {
		foreach ($factoring_company_address->results() as $factoring_company_address_data) {
			
			// by counter
			$factoring_company_address_data_id[$i] = $factoring_company_address_data->data_id;
			$factoring_company_address_type[$i] = $factoring_company_address_data->address_type; // 1 physical 2 mailing
			$factoring_company_address_line_1[$i] = html_entity_decode($factoring_company_address_data->line_1);
			$factoring_company_address_line_2[$i] = html_entity_decode($factoring_company_address_data->line_2);
			$factoring_company_address_line_3[$i] = html_entity_decode($factoring_company_address_data->line_3);
			$factoring_company_address_city[$i] = html_entity_decode($factoring_company_address_data->city);
			$factoring_company_address_state_id[$i] = $factoring_company_address_data->state_id;
			$factoring_company_address_zip_code[$i] = html_entity_decode($factoring_company_address_data->zip_code);
			$i++;
		}
	}
}

# Factoring company service fee
if (isset($_GET['factoring_company_id'])) {
	
	# Alter the clause if isset($_GET['contact_data_id']) 
	isset($_GET['service_fee_id']) ? $clause = "WHERE data_id = " . $_GET['service_fee_id'] : $clause = "WHERE factoring_company_id = " . $_GET['factoring_company_id'] . " ORDER BY fee ASC";
	
	$factoring_company_service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee " . $clause);
} else {

	$factoring_company_service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee") ;
}

$factoring_company_service_fee_count = $factoring_company_service_fee->count();
$i = 1;

if ($factoring_company_service_fee_count) {
	foreach ($factoring_company_service_fee->results() as $factoring_company_service_fee_data) {

		$factoring_company_service_fee_factoring_company_id[$i] = $factoring_company_service_fee_data->factoring_company_id;
		$factoring_company_service_fee_data_id[$i] = $factoring_company_service_fee_data->data_id;
		$factoring_company_service_fee_fee[$i] = $factoring_company_service_fee_data->fee;
		$factoring_company_service_fee_method_id[$i] = $factoring_company_service_fee_data->method_id;
		$factoring_company_service_fee_number_of_days[$i] = $factoring_company_service_fee_data->number_of_days;

		$i++;

		$factoring_company_service_fee_id_factoring_company_id[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->factoring_company_id;
		$factoring_company_service_fee_id_fee[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->fee;
		$factoring_company_service_fee_id_method_id[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->method_id;
		$factoring_company_service_fee_id_number_of_days[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->number_of_days;
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
	}
}