<?php

# This file name
# Keep this code after including db/client.php
$this_file_name = basename(__FILE__, '.php');

if (isset($_GET['invoice_id'])) {

	# Get invoice id data
	$invoice_get_id = DB::getInstance()->query("SELECT * FROM invoice WHERE id = " . $_GET['invoice_id']);

	if ($invoice_get_id->count()) {
		
		# Iterate through items
		foreach ($invoice_get_id->results() as $invoice_get_id_data) {
			
			$invoice_get_id_id = $invoice_get_id_data->id;
			$invoice_get_id_client_id = $invoice_get_id_data->client_id;
			$invoice_get_id_amount = $invoice_get_id_data->amount;
			$invoice_get_id_status = $invoice_get_id_data->status;
			$invoice_get_id_added = date('M. j, Y', strtotime($invoice_get_id_data->added));
			$invoice_get_id_added_prev_friday = date('M. j', strtotime('-7 day', strtotime($invoice_get_id_data->added)));
			$invoice_get_id_added_next_sunday = date('M. j, Y', strtotime('+2 day', strtotime($invoice_get_id_data->added)));
			$invoice_get_id_paid = $invoice_get_id_data->paid;
			$invoice_get_id_payer_name = $invoice_get_id_data->payer_name;
			$invoice_get_id_payer_last_name = $invoice_get_id_data->payer_last_name;
			$invoice_get_id_email = $invoice_get_id_data->email;
			$invoice_get_id_user_id = $invoice_get_id_data->user_id;
		}
	}

	# Get all loads from this client id from this pay period from invoice_week_load table
	$invoice_id_load = DB::getInstance()->query("
		SELECT 
			DISTINCT(invoice_week_load.load_id)
			, invoice_week_load.driver_id 
			, loader_load.first_checkpoint
			, loader_load.broker_id 
			, loader_load.line_haul 
			, broker.company_name 
		FROM invoice_week_load 
		LEFT JOIN loader_load ON invoice_week_load.load_id=loader_load.load_id
		LEFT JOIN broker ON loader_load.broker_id=broker.data_id
		WHERE client_id = $invoice_get_id_client_id 
		ORDER BY driver_id ASC, first_checkpoint ASC
	");

	$invoice_id_load_count = $invoice_id_load->count();

	if ($invoice_id_load_count) {
		
		$i = 1;
		$invoice_id_load_line_haul_total = 0;

		foreach ($invoice_id_load->results() as $invoice_id_load_data) {
			
			$invoice_id_load_load_id[$i] = $invoice_id_load_data->load_id;
			$invoice_id_load_driver_id[$i] = $invoice_id_load_data->driver_id;
			$invoice_id_load_first_checkpoint[$i] = date('m d Y', strtotime($invoice_id_load_data->first_checkpoint));
			$invoice_id_load_broker_company_name[$i] = $invoice_id_load_data->company_name;
			$invoice_id_load_line_haul[$i] = $invoice_id_load_data->line_haul;
			$invoice_id_load_line_haul_total += $invoice_id_load_data->line_haul;

			# Get first pickup data
			$first_checkpoint = DB::getInstance()->query("
				SELECT city, state_id 
				FROM loader_checkpoint 
				WHERE load_id = $invoice_id_load_load_id[$i] 
				ORDER BY date_time ASC 
				LIMIT 1
			");

			foreach ($first_checkpoint->results() as $first_checkpoint_data) {

				$first_checkpoint_city[$i] = ucwords(html_entity_decode($first_checkpoint_data->city));
				$first_checkpoint_state_id[$i] = $first_checkpoint_data->state_id;
			}

			# Get last pickup data
			$last_checkpoint = DB::getInstance()->query("
				SELECT city, state_id 
				FROM loader_checkpoint 
				WHERE load_id = $invoice_id_load_load_id[$i] 
				ORDER BY date_time DESC 
				LIMIT 1
			");

			foreach ($last_checkpoint->results() as $last_checkpoint_data) {

				$last_checkpoint_city[$i] = ucwords(html_entity_decode($last_checkpoint_data->city));
				$last_checkpoint_state_id[$i] = $last_checkpoint_data->state_id;
			}

			$i++;
		}
	}

	# Make sum of all of this invoice's item costs
	$invoice_id_item = DB::getInstance()->query("SELECT id, description, cost, default_charge FROM invoice_item WHERE invoice_id = " . $_GET['invoice_id']);
	$invoice_id_item_count = $invoice_id_item->count();
	$inv_item = 1;
	$invoice_id_item_total_amount = 0;

	foreach ($invoice_id_item->results() as $invoice_id_item_data) {
		
		$invoice_id_item_id[$inv_item] = $invoice_id_item_data->id;
		$invoice_id_item_description[$inv_item] = html_entity_decode($invoice_id_item_data->description);
		$invoice_id_item_cost[$inv_item] = number_format($invoice_id_item_data->cost, 2);
		$invoice_id_item_default_charge[$inv_item] = $invoice_id_item_data->default_charge;
		$invoice_id_item_total_amount += number_format($invoice_id_item_data->cost, 2);
		$inv_item++;
	}

	//Parametros Configuración
	$acquirerId = '99';
	$idCommerce = '8438';
	$purchaseOperationNumber = $_GET['invoice_id']; // '000000047';
	$purchaseAmount = $invoice_id_item_total_amount;
	$purchaseCurrencyCode = '840';

	//Clave SHA-2 de VPOS2
	$claveSecreta = 'XNGDatWVSdUcsuW?98254695';

	//VERSION PHP >= 5.3
	//echo openssl_digest('', 'sha512');
	//VERSION PHP < 5.3
	//echo hash('sha512', '$acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta');
	$purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta, 'sha512');
}

# Get all invoices
# invoice
$invoice = DB::getInstance()->query("
	SELECT invoice.id, invoice.client_id, invoice.status, invoice.added, invoice.paid, client.rate_type, client.rate 
	FROM invoice 
	LEFT JOIN client ON invoice.client_id=client.data_id
	ORDER BY added DESC");

$invoice_count = $invoice->count();
$i = 1;

if ($invoice_count) {
	
	# Iterate through items
	foreach ($invoice->results() as $invoice_data) {
		
		$invoice_id[$i] = $invoice_data->id;
		$invoice_client_id[$i] = $invoice_data->client_id;
		$invoice_status[$i] = $invoice_data->status;
		$invoice_added[$i] = $invoice_data->added;
		$invoice_paid[$i] = $invoice_data->paid;
		$invoice_rate_type[$i] = $invoice_data->rate_type;
		$invoice_rate[$i] = $invoice_data->rate;
		/*$invoice_payer_name[$i] = $invoice_data->payer_name;
		$invoice_payer_last_name[$i] = $invoice_data->payer_last_name;
		$invoice_email[$i] = $invoice_data->email;
		$invoice_user_id[$i] = $invoice_data->user_id;*/

		# Make sum of all of this invoice's item costs
		$invoice_item_cost = DB::getInstance()->query("SELECT cost FROM invoice_item WHERE invoice_id = " . $invoice_id[$i]);

		$total_cost[$i] = 0;

		foreach ($invoice_item_cost->results() as $invoice_item_cost_data) {
			
			$total_cost[$i] += $invoice_item_cost_data->cost;
		}

		$i++;
	}
}
