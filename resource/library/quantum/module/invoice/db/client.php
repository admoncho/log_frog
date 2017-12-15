<?php 

# client
$invoice_client = DB::getInstance()->query("SELECT * FROM client ORDER BY company_name ASC");
$invoice_client_count = $invoice_client->count();
$i = 1;

if ($invoice_client_count) {
	
	# Iterate through items
	foreach ($invoice_client->results() as $invoice_client_data) {
		
		$invoice_client_data_id[$i] = $invoice_client_data->data_id;
		$invoice_client_company_name[$i] = $invoice_client_data->company_name;
		$invoice_client_rate_type[$i] = $invoice_client_data->rate_type; // 1 Fixed fee 2 Percentage
		$invoice_client_rate[$i] = $invoice_client_data->rate;
		$i++;

		$invoice_client_id_company_name[$invoice_client_data->data_id] = $invoice_client_data->company_name;
	}
}

# New invoice client drop down menu
$new_invoice_client = DB::getInstance()->query("SELECT data_id, company_name FROM client WHERE status = 1 && rate != 0 ORDER BY company_name ASC");
$new_invoice_client_count = $new_invoice_client->count();
$i = 1;

if ($new_invoice_client_count) {
	
	# Iterate through items
	foreach ($new_invoice_client->results() as $new_invoice_client_data) {
		
		$new_invoice_client_data_id[$i] = $new_invoice_client_data->data_id;
		$new_invoice_client_company_name[$i] = $new_invoice_client_data->company_name;
		$i++;

		$new_invoice_client_id_company_name[$new_invoice_client_data->data_id] = $new_invoice_client_data->company_name;
	}
}
