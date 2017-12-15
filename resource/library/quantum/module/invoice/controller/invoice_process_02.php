<?php
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

/*

THIS IS A CRON JOB

Create one invoice per client_id found on the invoice_week_load table.


*/

$week_loads_02 = DB::getInstance()->query("
	SELECT DISTINCT(invoice_week_load.client_id), client.rate_type, client.rate 
	FROM invoice_week_load 
	LEFT JOIN client ON invoice_week_load.client_id=client.data_id
	WHERE created 
	LIKE '" . date('Y-m-d') . "%'");

foreach ($week_loads_02->results() as $week_loads_02_data) {

	# Count drivers per client
	$week_loads_02_driver_count = DB::getInstance()->query("
		SELECT DISTINCT(invoice_week_load.driver_id) 
		FROM invoice_week_load 
		WHERE client_id = " . $week_loads_02_data->client_id . " && created LIKE '" . date('Y-m-d') . "%'
	");

  $driver_count = $week_loads_02_driver_count->count();

	# Add invoices
	$insert = DB::getInstance()->query("
		INSERT INTO invoice (client_id, week_number) 
		VALUES (" . $week_loads_02_data->client_id . ", " . date('W') . ")
	");

	# If invoice added, add default description and cost to invoice_item table
	if ($insert) {

		# Check rate type and amount
		if ($week_loads_02_data->rate_type == 1 && $week_loads_02_data->rate == 150) {
			
			$description = 'Full Service';
			$rate = $week_loads_02_data->rate * $driver_count;
		} elseif ($week_loads_02_data->rate_type == 1 && $week_loads_02_data->rate == 100) {
			
			$description = 'Back office Services';
			$rate = $week_loads_02_data->rate * $driver_count;
		} elseif ($week_loads_02_data->rate_type == 2) {

			# Rate type == 2 is by comission, this means we have to get the sum of 
			# all line hauls and get the rate based on percentage.
			# 1 - Make load_id array
			$week_load_list = DB::getInstance()->query("
				SELECT invoice_week_load.load_id, loader_load.line_haul 
				FROM invoice_week_load 
				LEFT JOIN loader_load ON invoice_week_load.load_id=loader_load.load_id
				WHERE client_id = " . $week_loads_02_data->client_id . " 
				&& created LIKE '" . date('Y-m-d') . "%'
			");

			$line_haul_sum = 0;

			foreach ($week_load_list->results() as $week_load_list_data) {
				
				$line_haul_sum += $week_load_list_data->line_haul;
			}

			$description = $week_loads_02_data->rate . '% Comission';
			$rate = ($line_haul_sum / 100) * $week_loads_02_data->rate;
		}

		# Add invoice items
		$insert_item = DB::getInstance()->query("
			INSERT INTO invoice_item (invoice_id, description, cost, default_charge) 
			VALUES (" . $add_last_id = $insert->last() . ", '" . $description . "', " . $rate . ", 1)
		");
	}
}
