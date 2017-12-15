<?php

# loader_quickpay_method_of_payment
$quickpay_method_of_payment = DB::getInstance()->query("SELECT * FROM loader_quickpay_method_of_payment ORDER BY method ASC");
$quickpay_method_of_payment_count = $quickpay_method_of_payment->count();
$i = 1;

if ($quickpay_method_of_payment_count) {
	foreach($quickpay_method_of_payment->results() as $quickpay_method_of_payment_data) {
		
		/*$quickpay_method_of_payment_data_id[$i] = $quickpay_method_of_payment_data->data_id;
		$quickpay_method_of_payment_method[$i] = $quickpay_method_of_payment_data->method;*/
		$i++;

		# By data_id
		$quickpay_method_of_payment_data_id[$quickpay_method_of_payment_data->data_id] = $quickpay_method_of_payment_data->data_id;
		$quickpay_method_of_payment_method[$quickpay_method_of_payment_data->data_id] = $quickpay_method_of_payment_data->method;
	}
}
