<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);

# Get all data related to all schedules from the same client_assoc_id
# The following block of code is used to get all schedules for the same client_assoc_id so run only if $_GET['schedule_id']
if ($_GET['schedule_id']) {
	
	$schedule_all_client_assoc_id = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE client_assoc_id = $schedule_client_assoc_id ORDER BY counter ASC");
	$schedule_all_client_assoc_id_count = $schedule_all_client_assoc_id->count();
	$i = 1;

	foreach ($schedule_all_client_assoc_id->results() as $schedule_all_client_assoc_id_data) {
		
		$schedule_all_client_assoc_id_counter[$i] = $schedule_all_client_assoc_id_data->counter;
		$i++;
	}

	$schedule_all_client_assoc_id_counter_first = $schedule_all_client_assoc_id_counter[1] . "\r\n";
	$schedule_all_client_assoc_id_counter_last = $schedule_all_client_assoc_id_counter[$schedule_all_client_assoc_id_count];
}

# Get schedules for a client id
# Only run on '/0/client.php' and when there is an ID
if ((strpos($_SESSION['$clean_php_self'] , '/0/client.php') === true) && $_GET['id']) {
	
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
	WHERE client_id = " . $_GET['id'] . " ORDER BY factoring_company_schedule_counter DESC");

	$schedule_all_client_id_count = $schedule_all_client_id->count();
	$i = 1;

	foreach ($schedule_all_client_id->results() as $schedule_all_client_id_data) {
		
		$schedule_all_client_id_data_id[$i] = $schedule_all_client_id_data->data_id;
		$schedule_all_client_id_client_assoc_id[$i] = $schedule_all_client_id_data->client_assoc_id;
		$schedule_all_client_id_counter[$i] = $schedule_all_client_id_data->factoring_company_schedule_counter;
		$schedule_all_client_id_fee_option[$i] = $schedule_all_client_id_data->fee_option;
		$schedule_all_client_id_payment_confirmation[$i] = $schedule_all_client_id_data->payment_confirmation;
		$schedule_all_client_id_payment_confirmation_added[$i] = $schedule_all_client_id_data->payment_confirmation_added;
		$schedule_all_client_id_created[$i] = date('m d, Y', strtotime($schedule_all_client_id_data->factoring_company_schedule_added));

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
}