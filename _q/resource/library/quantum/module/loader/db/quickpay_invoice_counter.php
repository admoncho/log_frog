<?php

# Only pass if required values are set
if (isset($_POST['broker_id']) && isset($_GET['client_id'])) {
	
	#loader_quickpay_invoice_counter
	$loader_quickpay_invoice_counter = DB::getInstance()->query("SELECT * FROM loader_quickpay_invoice_counter WHERE broker_id = " . $_POST['broker_id'] . " && client_id = " . $_GET['client_id']);
	$loader_quickpay_invoice_counter_count = $loader_quickpay_invoice_counter->count();

	if ($loader_quickpay_invoice_counter_count) {
		foreach ($loader_quickpay_invoice_counter->results() as $loader_quickpay_invoice_counter_data) {
			$quickpay_invoice_counter = $loader_quickpay_invoice_counter_data->counter;
		}
	}
}
