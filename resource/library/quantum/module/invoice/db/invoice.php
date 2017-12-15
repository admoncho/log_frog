<?php
session_start();
ob_start();

# Keep this code after including db/client.php
# $this_file_name = basename(__FILE__, '.php');

# Get client id for external users
if ($user->data()->user_group == 4) {

	$client_user = DB::getInstance()->query("
		SELECT client_id, user_type 
		FROM client_user 
		WHERE user_id = " . $user->data()->id);


	foreach ($client_user->results() as $client_user_data) {
		
		$client_user_data_client_id = $client_user_data->client_id;
		$client_user_data_user_type = $client_user_data->user_type;
	}

	# Redirect drivers to dashboard, only owners/owner/operators see invoices
	if ($client_user_data_user_type == 2) {
		
		Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/');
	}
}

# Invoice id page
if (isset($_GET['invoice_id'])) {

	# Get invoice id data
	$invoice_get_id = DB::getInstance()->query("SELECT * FROM invoice WHERE id = " . $_GET['invoice_id']);

	if ($invoice_get_id->count()) {
		
		# Iterate through items
		foreach ($invoice_get_id->results() as $invoice_get_id_data) {
			
			$invoice_get_id_client_id = $invoice_get_id_data->client_id;
			$invoice_get_id_added = date('M. j, Y', strtotime($invoice_get_id_data->added));
			$invoice_get_id_added_prev_friday = date('M. j', strtotime('-7 day', strtotime($invoice_get_id_data->added)));
			$invoice_get_id_added_next_sunday = date('M. j, Y', strtotime('+2 day', strtotime($invoice_get_id_data->added)));
			$invoice_get_id_week_number = $invoice_get_id_data->week_number;
			$invoice_get_id_paid = date('M j, Y', strtotime($invoice_get_id_data->paid));
			$invoice_get_id_rejected = date('M j, Y', strtotime($invoice_get_id_data->rejected));
			$invoice_get_id_errorCode = $invoice_get_id_data->errorCode;
			$invoice_get_id_errorMessage = $invoice_get_id_data->errorMessage;
			$invoice_get_id_authorizationCode = $invoice_get_id_data->authorizationCode;
			$invoice_get_id_purchase_amount = $invoice_get_id_data->purchase_amount;
		}

		# Invoice shipping fields data
		# Get manager's user id
		$invoice_manager = DB::getInstance()->query("
			SELECT * FROM client_user 
			WHERE client_id = " . $invoice_get_id_client_id . " 
			&& user_type IN (0, 1) 
			LIMIT 1");

		foreach ($invoice_manager->results() as $invoice_manager_data) {

			$invoice_manager_id = $invoice_manager_data->user_id;
		}

		# Get invoice's shipping data
		$invoice_location = DB::getInstance()->query("
			SELECT * FROM client_address 
			WHERE client_id = " . $invoice_get_id_client_id . " 
			&& address_type = 1 
			LIMIT 1");

		foreach ($invoice_location->results() as $invoice_location_data) {

			$invoice_location_line_1 = html_entity_decode($invoice_location_data->line_1);
			$invoice_location_city = html_entity_decode($invoice_location_data->city);
			$invoice_location_state_id = $invoice_location_data->state_id;
			$invoice_location_zip_code = $invoice_location_data->zip_code;
		}

		# Get invoice's description (rate_type)
		$invoice_description = DB::getInstance()->query("
			SELECT * FROM client 
			WHERE data_id = " . $invoice_get_id_client_id);

		foreach ($invoice_description->results() as $invoice_description_data) {

			$invoice_description_rate_type = $invoice_description_data->rate_type;
			$invoice_description_rate = $invoice_description_data->rate;
		}

		# Set input descriptionProducts' value
		if ($invoice_description_rate_type == 1) {
			
			if ($invoice_description_rate == 150) {
				
				$description_product = 'Full service, Logistics Frog';
			} else {

				$description_product = 'Back office, Logistics Frog';
			}
		} elseif ($invoice_description_rate_type == 2) {
			
			$description_product = 'Full service, Logistics Frog';
		}
	}

	# Redirect users trying to get another client's invoice data
	# If $client_user_data_client_id is set, it should match $invoice_get_id_client_id
	if (isset($client_user_data_client_id)) {
		
		if ($client_user_data_client_id != $invoice_get_id_client_id) {
			
			Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/invoice/');
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
		WHERE client_id = $invoice_get_id_client_id && week_number = $invoice_get_id_week_number
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
		$invoice_id_item_total_amount += $invoice_id_item_data->cost;
		$inv_item++;
	}

	# Check if purchase amount has decimals, if it comes with a dot (.) then yes
	if (strpos($invoice_id_item_total_amount, '.') !== false) {
	  
	  # Use two decimals and remove the dot.
	  $purchaseAmount = str_replace('.', '', number_format($invoice_id_item_total_amount, 2));
	} else {

		$purchaseAmount = $invoice_id_item_total_amount . '00';
	}
}

# If external user, get client id to filter by that parameter on the query
if ($user->data()->user_group == 4) {

	# Get all invoices
	# Filtering errorCode = 0 only shows unprocessed and paid invoices for external users
	$invoice = DB::getInstance()->query("
		SELECT 
			invoice.id
			, invoice.client_id
			, invoice.added
			, invoice.paid
			, invoice.rejected
			, invoice.errorCode
			, invoice.errorMessage
			, invoice.authorizationCode
			, invoice.purchaseAmount
			, client.rate_type
			, client.rate 
		FROM invoice 
		LEFT JOIN client ON invoice.client_id=client.data_id
		WHERE client_id = $client_user_data_client_id && errorCode = 0
		ORDER BY added DESC");

	$invoice_count = $invoice->count();
	$i = 1;

	if ($invoice_count) {
		
		# Iterate through items
		foreach ($invoice->results() as $invoice_data) {
			
			$invoice_id[$i] = $invoice_data->id;
			$invoice_client_id[$i] = $invoice_data->client_id;
			$invoice_added[$i] = $invoice_data->added;
			$invoice_paid[$i] = $invoice_data->paid;
			$invoice_rejected[$i] = $invoice_data->rejected;
			$invoice_errorCode[$i] = $invoice_data->errorCode;
			$invoice_errorMessage[$i] = $invoice_data->errorMessage;
			$invoice_authorizationCode[$i] = $invoice_data->authorizationCode;
			$invoice_purchaseAmount[$i] = $invoice_data->purchaseAmount;
			$invoice_rate_type[$i] = $invoice_data->rate_type;
			$invoice_rate[$i] = $invoice_data->rate;

			# Make sum of all of this invoice's item costs
			$invoice_item_cost = DB::getInstance()->query("SELECT cost FROM invoice_item WHERE invoice_id = " . $invoice_id[$i]);

			$total_cost[$i] = 0;

			foreach ($invoice_item_cost->results() as $invoice_item_cost_data) {
				
				$total_cost[$i] += $invoice_item_cost_data->cost;
			}

			$i++;
		}
	}
} else {

	# Get all invoices
	# invoice
	$invoice = DB::getInstance()->query("
		SELECT 
			invoice.id
			, invoice.client_id
			, invoice.added
			, invoice.paid
			, invoice.rejected
			, invoice.errorCode
			, invoice.errorMessage
			, invoice.authorizationCode
			, invoice.purchaseAmount
			, client.rate_type
			, client.rate 
		FROM invoice 
		LEFT JOIN client ON invoice.client_id=client.data_id 
		WHERE errorMessage != 'Custom - deleted' 
		ORDER BY added DESC");

	$invoice_count = $invoice->count();
	$i = 1;

	if ($invoice_count) {
		
		# Iterate through items
		foreach ($invoice->results() as $invoice_data) {
			
			$invoice_id[$i] = $invoice_data->id;
			$invoice_client_id[$i] = $invoice_data->client_id;
			$invoice_added[$i] = $invoice_data->added;
			$invoice_paid[$i] = $invoice_data->paid;
			$invoice_rejected[$i] = $invoice_data->rejected;
			$invoice_errorCode[$i] = $invoice_data->errorCode;
			$invoice_errorMessage[$i] = $invoice_data->errorMessage;
			$invoice_authorizationCode[$i] = $invoice_data->authorizationCode;
			$invoice_purchaseAmount[$i] = $invoice_data->purchaseAmount;
			$invoice_rate_type[$i] = $invoice_data->rate_type;
			$invoice_rate[$i] = $invoice_data->rate;

			# Make sum of all of this invoice's item costs
			$invoice_item_cost = DB::getInstance()->query("SELECT cost FROM invoice_item WHERE invoice_id = " . $invoice_id[$i]);

			$total_cost[$i] = 0;

			foreach ($invoice_item_cost->results() as $invoice_item_cost_data) {
				
				$total_cost[$i] += $invoice_item_cost_data->cost;
			}

			$i++;
		}
	}

	# Get all invoices that were paid today
	$today_invoice = DB::getInstance()->query("
		SELECT * 
		FROM invoice 
		WHERE paid LIKE '" . date('Y-m-d') . "%'");

	$today_invoice_count = $today_invoice->count();
	$i = 1;

	if ($today_invoice_count) {
		
		$today_invoice_purchaseAmount = 0;

		# Iterate through items
		foreach ($today_invoice->results() as $today_invoice_data) {
			
			$today_invoice_purchaseAmount += $today_invoice_data->purchaseAmount;
			$i++;
		}
	}

	# Get all invoices that were paid this month
	$month_invoice = DB::getInstance()->query("
		SELECT * 
		FROM invoice 
		WHERE paid LIKE '" . date('Y-m') . "%'");

	$month_invoice_count = $month_invoice->count();
	$i = 1;

	if ($month_invoice_count) {
		
		$month_invoice_purchaseAmount = 0;

		# Iterate through items
		foreach ($month_invoice->results() as $month_invoice_data) {
			
			$month_invoice_purchaseAmount += $month_invoice_data->purchaseAmount;
			$i++;
		}
	}

	# Get all invoices that were paid this year
	$year_invoice = DB::getInstance()->query("
		SELECT * 
		FROM invoice 
		WHERE paid LIKE '" . date('Y') . "%'");

	$year_invoice_count = $year_invoice->count();
	$i = 1;

	if ($year_invoice_count) {
		
		$year_invoice_purchaseAmount = 0;

		# Iterate through items
		foreach ($year_invoice->results() as $year_invoice_data) {
			
			$year_invoice_purchaseAmount += $year_invoice_data->purchaseAmount;
			$i++;
		}
	}

	# Get all invoices that were paid on posted set date range
	if (isset($_POST['date_range'])) {
		
		$start_day = substr($_POST['date_range'], 3, 2);
		$start_month = substr($_POST['date_range'], 0, 2);
		$start_year = substr($_POST['date_range'], 6, 4);
		$end_day = substr($_POST['date_range'], 16, 2);
		$end_month = substr($_POST['date_range'], 13, 2);
		$end_year = substr($_POST['date_range'], 19, 4);

		$date_range_invoice = DB::getInstance()->query("
			SELECT * 
			FROM invoice 
			WHERE paid >= '$start_year-$start_month-$start_day 00:00:00' && paid <= '$end_year-$end_month-$end_day 23:59:59'");

		$date_range_invoice_count = $date_range_invoice->count();
		$i = 1;

		if ($date_range_invoice_count) {
			
			$date_range_invoice_purchaseAmount = 0;

			# Iterate through items
			foreach ($date_range_invoice->results() as $date_range_invoice_data) {
				
				$date_range_invoice_purchaseAmount += $date_range_invoice_data->purchaseAmount;
				$i++;
			}
		}
	}
}
