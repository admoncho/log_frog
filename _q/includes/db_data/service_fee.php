<?php

# Get Service fee data
$service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee WHERE data_id = " . $_GET['fee_option']);

foreach ($service_fee->results() as $service_fee_data) {
	
	$service_fee_method_id = $service_fee_data->method_id;
}
