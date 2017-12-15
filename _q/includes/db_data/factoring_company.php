<?php

# Factoring companies
$_GET['factoring_company_id'] ? 
	$factoring_company = DB::getInstance()->query("SELECT * FROM factoring_company WHERE data_id = " . $_GET['factoring_company_id']) 
	: $factoring_company = DB::getInstance()->query("SELECT * FROM factoring_company ORDER BY name ASC") ;

$factoring_company_count = $factoring_company->count();
$i = 1;

if ($factoring_company_count) {
	foreach ($factoring_company->results() as $factoring_company_data) {

		// Counter
		$factoring_company_data_id[$i] = $factoring_company_data->data_id;
		$factoring_company_name[$i] = html_entity_decode($factoring_company_data->name);
		$factoring_company_uri[$i] = $factoring_company_data->uri;
		$factoring_company_invoicing_email[$i] = $factoring_company_data->invoicing_email;
		$factoring_company_phone_number_01[$i] = $factoring_company_data->phone_number_01;
		$factoring_company_fax[$i] = $factoring_company_data->fax;
		$factoring_company_batch_schedule[$i] = $factoring_company_data->batch_schedule;
		$factoring_company_requires_soar[$i] = $factoring_company_data->requires_soar;
		$factoring_company_status[$i] = $factoring_company_data->status;
		$factoring_company_added[$i] = date('M d, Y', strtotime($factoring_company_data->added));
		$factoring_company_user_id[$i] = $factoring_company_data->user_id;

		// data_id
		$factoring_company_name_did[$factoring_company_data->data_id] = html_entity_decode($factoring_company_data->name);
		$factoring_company_uri_did[$factoring_company_data->data_id] = $factoring_company_data->uri;
		$factoring_company_invoicing_email_did[$factoring_company_data->data_id] = $factoring_company_data->invoicing_email;
		$factoring_company_batch_schedule_did[$factoring_company_data->data_id] = $factoring_company_data->batch_schedule;

		$i++;
	}
}

# Factoring company contacts
$factoring_company_contact = DB::getInstance()->query("SELECT * FROM factoring_company_contact WHERE factoring_company_id = " . $_GET['factoring_company_id'] . " ORDER BY name ASC");
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
$factoring_company_address = DB::getInstance()->query("SELECT * FROM factoring_company_address WHERE factoring_company_id = " . $_GET['factoring_company_id']);
$factoring_company_address_count = $factoring_company_address->count();
$i = 1;

if ($factoring_company_address_count) {
	foreach ($factoring_company_address->results() as $factoring_company_address_data) {
		
		// by counter
		$factoring_company_address_data_id[$i] = $factoring_company_address_data->data_id;
		$factoring_company_address_type[$i] = $factoring_company_address_data->address_type;
		$factoring_company_address_line_1[$i] = html_entity_decode($factoring_company_address_data->line_1);
		$factoring_company_address_line_2[$i] = html_entity_decode($factoring_company_address_data->line_2);
		$factoring_company_address_line_3[$i] = html_entity_decode($factoring_company_address_data->line_3);
		$factoring_company_address_city[$i] = html_entity_decode($factoring_company_address_data->city);
		$factoring_company_address_state_id[$i] = $factoring_company_address_data->state_id;
		$factoring_company_address_zip_code[$i] = html_entity_decode($factoring_company_address_data->zip_code);

		$i++;		
	}
}

# Factoring company service fee
$_GET['factoring_company_id'] ? 
	$factoring_company_service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee WHERE factoring_company_id = " . $_GET['factoring_company_id']) 
	: $factoring_company_service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee") ;

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

# Factoring company client assoc
$factoring_company_client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE client_id = " . $_GET['id']);
$factoring_company_client_assoc_count = $factoring_company_client_assoc->count();

if ($factoring_company_client_assoc_count) {
	foreach ($factoring_company_client_assoc->results() as $factoring_company_client_assoc_data) {

		$factoring_company_client_assoc_factoring_company_id = $factoring_company_client_assoc_data->factoring_company_id;
		$factoring_company_client_assoc_main = $factoring_company_client_assoc_data->main;
		$factoring_company_client_assoc_alt = $factoring_company_client_assoc_data->alt;
		$factoring_company_client_assoc_counter = $factoring_company_client_assoc_data->counter;
	}
}