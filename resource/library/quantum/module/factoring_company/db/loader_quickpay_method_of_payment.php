<?php

# loader_quickpay_method_of_payment
$loader_quickpay_method_of_payment = DB::getInstance()->query("SELECT * FROM loader_quickpay_method_of_payment ORDER BY method ASC");
$loader_quickpay_method_of_payment_count = $loader_quickpay_method_of_payment->count();
$loader_quickpay_method_of_payment_counter = 1;

if ($loader_quickpay_method_of_payment_count) {
	foreach($loader_quickpay_method_of_payment->results() as $loader_quickpay_method_of_payment_data) {

		# By counter (note the use of _ctr)
		$quickpay_method_of_payment_data_id_ctr[$loader_quickpay_method_of_payment_counter] = $loader_quickpay_method_of_payment_data->data_id;
		$quickpay_method_of_payment_method_ctr[$loader_quickpay_method_of_payment_counter] = $loader_quickpay_method_of_payment_data->method;
		$loader_quickpay_method_of_payment_counter++;

		# By data_id
		$quickpay_method_of_payment_data_id[$loader_quickpay_method_of_payment_data->data_id] = $loader_quickpay_method_of_payment_data->data_id;
		$quickpay_method_of_payment_method[$loader_quickpay_method_of_payment_data->data_id] = $loader_quickpay_method_of_payment_data->method;
	}
}
