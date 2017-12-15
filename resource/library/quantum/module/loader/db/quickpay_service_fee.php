<?php

if (isset($_GET['broker_id'])) {
	
	$clause = "WHERE broker_id = " . $_GET['broker_id'] . " ORDER BY fee ASC";
} elseif (isset($_GET['client_id']) && isset($_POST['broker_id'])) {

	$clause = "WHERE broker_id = " . $_POST['broker_id'] . " ORDER BY fee ASC";
} else {

	$clause = " ORDER BY data_id ASC";
}

$loader_quickpay_service_fee = DB::getInstance()->query("SELECT * FROM loader_quickpay_service_fee " . $clause);
$loader_quickpay_service_fee_count = $loader_quickpay_service_fee->count();
$i = 1;

if ($loader_quickpay_service_fee_count) {
	foreach($loader_quickpay_service_fee->results() as $loader_quickpay_service_fee_data) {

		// By counter
		$quickpay_service_fee_data_id[$i] = $loader_quickpay_service_fee_data->data_id;
		$quickpay_service_fee[$i] = $loader_quickpay_service_fee_data->fee;
		$quickpay_service_method_id[$i] = $loader_quickpay_service_fee_data->method_id;
		$quickpay_service_number_of_days[$i] = $loader_quickpay_service_fee_data->number_of_days;
		$i++;

		// By data_id
		$quickpay_service_fee_data_id_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->data_id;
		$quickpay_service_fee_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->fee;
		$quickpay_service_method_id_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->method_id;
		$quickpay_service_number_of_days_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->number_of_days;
	}
}
