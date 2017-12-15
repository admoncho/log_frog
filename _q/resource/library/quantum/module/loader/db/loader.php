<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# This file name
$this_file_name = basename(__FILE__, '.php');

# Load status
isset($_POST['load_status']) && $_POST['load_status'] == 9 ? $status_value = 0 : $status_value = 0;
isset($_POST['load_status']) && $_POST['load_status'] == 1 ? $status_value = 1 : $status_value = 0;

# Set SELECT
$select = 'SELECT loader_load.entry_id, loader_load.draft_id, loader_load.broker_id, loader_load.load_id, 	loader_load.load_number, loader_load.line_haul, loader_load.avg_diesel_price, loader_load.weight, loader_load.miles, loader_load.deadhead, loader_load.reference, loader_load.commodity, loader_load.equipment, loader_load.notes, loader_load.broker_name_number, loader_load.broker_email, loader_load.billing_status, loader_load.billing_date, loader_load.first_checkpoint, loader_load.last_checkpoint, loader_load.load_lock, loader_load.load_status, loader_load.added, loader_load.added_by, loader_load.user_id, loader_entry.data_id, loader_entry.driver_id, client_user.client_id

	FROM loader_load 

	LEFT JOIN loader_entry ON loader_load.entry_id = loader_entry.data_id 
	LEFT JOIN client_user ON loader_entry.driver_id = client_user.user_id';

if ($user->data()->user_group == 4) {

	# External user connection settings

	if (!$_GET['load_id']) {
		
		# Main loader page data
		# client_user data
		$client_user = DB::getInstance()->query("SELECT * FROM client_user WHERE user_id = " . $user->data()->id);

		foreach($client_user->results() as $client_user_data) {

			$client_user_user_type = $client_user_data->user_type; // 0 owner 1 owner/operator 2 driver
		}

		# CLIENT (this logged user) has drivers?
		$user_has_drivers = DB::getInstance()->query("SELECT * FROM client_user WHERE user_manager = " . $user->data()->id);
		$user_has_drivers_count = $user_has_drivers->count();

		if ($user_has_drivers_count) {
			
			# Create array of all drivers managed by this user_id
	    $user_has_drivers_user_id = array();
	    
	    foreach ($user_has_drivers->results() as $user_has_drivers_data) {
	      
	      $user_has_drivers_user_id[] = $user_has_drivers_data->user_id;
	    }

	    # Add comma separated array into string
	    $user_has_drivers_user_id_string = implode("," , $user_has_drivers_user_id);
		}

		# This list has to target 4 different types of user [DRIVER, OWNER/OPERATOR (no other drivers), OWNER/OPERATOR (with other drivers), CLIENT]
		if ($client_user_user_type ==  0) {

			# CLIENT
			$where = ' WHERE driver_id IN (' . $user_has_drivers_user_id_string . ') ORDER BY last_checkpoint DESC';
		} elseif ($client_user_user_type == 1) {
			
			# OWNER/OPERATOR

			if ($user_has_drivers_count) {

				$where = ' WHERE driver_id IN (' . $user_has_drivers_user_id_string . ', ' . $user->data()->id . ') ORDER BY last_checkpoint DESC';	
			} else {

				$where = ' WHERE driver_id = ' . $user->data()->id;
			}
		} elseif ($client_user_user_type == 2) {
			
			# DRIVER
			$where = ' WHERE driver_id = ' . $user->data()->id . ' ORDER BY last_checkpoint DESC';
		}
	} else {

		# Load page
		$where = ' WHERE load_id = ' . $_GET['load_id'];
	}
} else {

	# Internal user connection settings
	# Set WHERE
	if (isset($_POST['broker_id']) && isset($_POST['driver_id'])) {
		
		$where = ' WHERE broker_id = ' . $_POST['broker_id'] . ' && driver_id = ' . $_POST['driver_id'] . ' && load_status = ' . $status_value . ' ORDER BY last_checkpoint DESC';
	} elseif (isset($_POST['broker_id']) && !isset($_POST['driver_id'])) {

		$where = ' WHERE broker_id = ' . $_POST['broker_id'] . ' && load_status = ' . $status_value . ' ORDER BY last_checkpoint DESC';
	} elseif (!isset($_POST['broker_id']) && isset($_POST['driver_id'])) {

		$where = ' WHERE driver_id = ' . $_POST['driver_id'] . ' && load_status = ' . $status_value . ' ORDER BY last_checkpoint DESC';
	} elseif (isset($_POST['load_number'])) {

		$where = ' WHERE load_number = "' . $_POST['load_number'] . '"';
	} elseif (isset($_GET['load_id'])) {

		$where = ' WHERE load_id = ' . $_GET['load_id'];
	} else {
		
		$where = ' WHERE load_status = ' . $status_value . ' ORDER BY last_checkpoint DESC';
	}
}

# Reset $limit if not set
isset($_POST['limit']) && !isset($_GET['load_id']) ? $limit = ' LIMIT ' . $_POST['limit'] : $limit = ' LIMIT 250';
isset($_GET['load_id']) ? $limit = '' : '';

# Loads
$load = DB::getInstance()->query($select . $where . $limit);
$load_count = $load->count();
$i = 1;

if ($load_count) {
	
	foreach ($load->results() as $load_data) {
		
		$load_entry_id[$i] = $load_data->entry_id;
		$load_draft_id[$i] = $load_data->draft_id;
		$load_broker_id[$i] = $load_data->broker_id;
		$load_load_id[$i] = $load_data->load_id;
		$load_load_number[$i] = $load_data->load_number;
		$load_line_haul[$i] = $load_data->line_haul; // This is used for mathematical ops, leave clean
		$load_line_haul_format_1[$i] = number_format($load_data->line_haul, 2);
		$load_avg_diesel_price[$i] = $load_data->avg_diesel_price;
		$load_weight[$i] = $load_data->weight;
		$load_miles[$i] = substr($load_data->miles, -2) == '.0' ? number_format($load_data->miles, 0) : $load_data->miles; // Cleans '.0'
		$load_deadhead[$i] = $load_data->deadhead;
		$load_reference[$i] = $load_data->reference;
		$load_commodity[$i] = $load_data->commodity;
		$load_equipment[$i] = $load_data->equipment;
		$load_notes[$i] = $load_data->notes;
		$load_broker_name_number[$i] = $load_data->broker_name_number;
		$load_broker_email[$i] = strtolower($load_data->broker_email);
		$load_billing_status[$i] = $load_data->billing_status;
		$load_billing_date[$i] = date('m/d/Y', strtotime($load_data->billing_date));
		$load_first_checkpoint_date_time[$i] = date('Y/m/d G:i:s', strtotime($load_data->first_checkpoint));
		$load_last_checkpoint_date_time[$i] = date('Y/m/d G:i:s', strtotime($load_data->last_checkpoint));
		$load_load_lock[$i] = $load_data->load_lock;
		$load_load_status[$i] = $load_data->load_status;
		$load_added[$i] = date('m/d/Y', strtotime($load_data->added));
		$load_added_time[$i] = date('G:i', strtotime($load_data->added));
		$load_added_by[$i] = $load_data->added_by;
		$load_user_id[$i] = $load_data->user_id;

		$entry_driver_id[$i] = $load_data->driver_id;

		$load_client_id[$i] = $load_data->client_id;

		# First and last checkpoint for main page only
		if ($_SESSION['$clean_php_self'] == '/dashboard/loader/index.php') {
			
			# First checkpoint
			$load_first_checkpoint = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_load_id[$i] . " && data_type = 0 ORDER BY date_time ASC LIMIT 1");

			if ($load_first_checkpoint->count()) {
				
				foreach ($load_first_checkpoint->results() as $load_first_checkpoint_data) {
					
					$load_first_checkpoint_date[$i] = date('m/d/y', strtotime($load_first_checkpoint_data->date_time));
					$load_first_checkpoint_time[$i] = date('G:i', strtotime($load_first_checkpoint_data->date_time));
					$load_first_checkpoint_city[$i] = html_entity_decode($load_first_checkpoint_data->city);
					$load_first_checkpoint_state_id[$i] = $load_first_checkpoint_data->state_id;
					$load_first_checkpoint_zip_code[$i] = $load_first_checkpoint_data->zip_code;
				}

				if ($load_first_checkpoint_date_time[$i] == '-0001/11/30 0:00:00') {

					$update = DB::getInstance()->query("
						UPDATE loader_load 
						SET first_checkpoint = '" . date('Y-m-d G:i:s', strtotime($load_first_checkpoint_data->date_time)) . "'
				  	WHERE load_id = " . $load_load_id[$i]);
				}
			}

			# Last checkpoint
			$load_last_checkpoint = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_load_id[$i] . " && data_type = 1 ORDER BY date_time DESC LIMIT 1");

			if ($load_last_checkpoint->count()) {
				
				foreach ($load_last_checkpoint->results() as $load_last_checkpoint_data) {
					
					$load_last_checkpoint_date[$i] = date('m/d/y', strtotime($load_last_checkpoint_data->date_time));
					$load_last_checkpoint_date_1[$i] = date('Y/m/d', strtotime($load_last_checkpoint_data->date_time)); // Used for fixing datatables date sorting issue
					$load_last_checkpoint_time[$i] = date('G:i', strtotime($load_last_checkpoint_data->date_time));
					$load_last_checkpoint_city[$i] = html_entity_decode($load_last_checkpoint_data->city);
					$load_last_checkpoint_state_id[$i] = $load_last_checkpoint_data->state_id;
					$load_last_checkpoint_zip_code[$i] = $load_last_checkpoint_data->zip_code;
				}

				# Only run if last_checkpoint is empty
				if ($load_last_checkpoint_date_time[$i] == '-0001/11/30 0:00:00') {

					$update = DB::getInstance()->query("
						UPDATE loader_load 
						SET last_checkpoint = '" . date('Y-m-d G:i:s', strtotime($load_last_checkpoint_data->date_time)) . "'
				  	WHERE load_id = " . $load_load_id[$i]);
				}
			}

			# On adding a new load, spot duplicate load numbers here
			if (Input::get('_controller_' . $this_file_name) == 'add_load') {

				$load_load_number[$i] == htmlentities(Input::get('load_number'), ENT_QUOTES) ? $load_number_exists = 1 : '';
			}
		}

		# Has files?
		$path = str_replace('_q', 'files/', $_SESSION['ProjectPath']);
		
		file_exists($path . 'bol-' . $load_entry_id[$i] . '-' . $load_load_id[$i] . '.pdf') ? $bol_exists[$i] = 1 : '';
		file_exists($path . 'rate-confirmation-' . $load_entry_id[$i] . '-' . $load_load_id[$i] . '.pdf') ? $rate_confirmation_exists[$i] = 1 : '';
		file_exists($path . 'raw-bol-' . $load_entry_id[$i] . '-' . $load_load_id[$i] . '.pdf') ? $raw_bol_exists[$i] = 1 : '';
		file_exists($path . 'payment-confirmation-' . $load_entry_id[$i] . '-' . $load_load_id[$i] . '.pdf') ? $payment_confirmation_exists[$i] = 1 : '';
		file_exists($path . 'quickpay-invoices/invoice-' . $load_entry_id[$i] . '-' . $load_load_id[$i] . '.pdf') ? $quickpay_invoice_exists[$i] = 1 : '';

		$i++;
	}
}

# Load id specific
# /dashboard/loader/load?load_id=n
if (isset($_GET['load_id'])) {
	
	
	if ($_GET['checkpoint_id']) {
		
		# Get all data related to this checkpoint_id	
		$checkpoint_id = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . " && checkpoint_id = " . $_GET['checkpoint_id']);	
	} elseif ($_GET['checkpoint_status_update']) {
		
		# Get all data related to this checkpoint_id	
		$checkpoint_id = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . " && checkpoint_id = " . $_GET['checkpoint_status_update']);	
	} else {
		
		# Get all data related to this load_id	
		$checkpoint_id = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . " ORDER BY date_time ASC");
	}
	
	$checkpoint_id_count = $checkpoint_id->count();
	$i = 1;

	foreach ($checkpoint_id->results() as $checkpoint_id_data) {
		
		$checkpoint_id_load_id[$i] = $checkpoint_id_data->load_id;
		$checkpoint_id_checkpoint_id[$i] = $checkpoint_id_data->checkpoint_id;
		$checkpoint_id_date_time[$i] = date('m/d/Y G:i', strtotime($checkpoint_id_data->date_time));
		$checkpoint_id_date_time_2[$i] = date('D m/d/Y', strtotime($checkpoint_id_data->date_time));
		$checkpoint_id_date_time_3[$i] = date('Y-m-d G:i:s', strtotime($checkpoint_id_data->date_time));
		$checkpoint_id_date_time_4[$i] = date('M j, Y \a\t G:i', strtotime($checkpoint_id_data->date_time));
		$checkpoint_id_date_1[$i] = date('m-d-Y', strtotime($checkpoint_id_data->date_time));
		$checkpoint_id_time_1[$i] = date('G:i', strtotime($checkpoint_id_data->date_time));
		$checkpoint_id_line_1[$i] = $checkpoint_id_data->line_1;
		$checkpoint_id_line_2[$i] = $checkpoint_id_data->line_2;
		$checkpoint_id_city[$i] = $checkpoint_id_data->city;
		$checkpoint_id_state_id[$i] = $checkpoint_id_data->state_id;
		$checkpoint_id_zip_code[$i] = $checkpoint_id_data->zip_code;
		$checkpoint_id_contact[$i] = $checkpoint_id_data->contact;
		$checkpoint_id_appointment[$i] = $checkpoint_id_data->appointment;
		$checkpoint_id_notes[$i] = $checkpoint_id_data->notes;
		$checkpoint_id_data_type[$i] = $checkpoint_id_data->data_type; // 0 pick up 1 dropoff
		$checkpoint_id_added[$i] = date('m/d/Y G:i', strtotime($checkpoint_id_data->added));
		$checkpoint_id_user_id[$i] = $checkpoint_id_data->user_id;
		$checkpoint_id_status[$i] = $checkpoint_id_data->status; // 0 incomplete 1 complete 2 complete, notification sent

		# Check there are at least 2 checkpoints
		if ($checkpoint_id_count > 1) {
			
			# Make sure one is a pick up and one is a dropoff
			$checkpoint_id_data_type[$i] == 0 ? $checkpoint_data_type_0_exists = 1 : '';
			$checkpoint_id_data_type[$i] == 1 ? $checkpoint_data_type_1_exists = 1 : '';
		}

		# Count checkpoint associations for data_type = 0 (pickups)
		if ($checkpoint_id_data_type[$i] == 0) {
			$loader_checkpoint_assoc = DB::getInstance()->query("SELECT * FROM loader_checkpoint_assoc WHERE checkpoint = " . $checkpoint_id_checkpoint_id[$i]);
			$loader_checkpoint_assoc_count[$i] = $loader_checkpoint_assoc->count();
		}

		$i++;
	}

	# Logic when creating/updating checkpoints
	if (Input::get('_controller_checkpoint') == 'add' || Input::get('_controller_checkpoint') == 'update') {

		# Is address ready?
		$_POST['line_1'] && $_POST['city'] && $_POST['state_id'] && $_POST['zip_code'] ? $address_ready = 1 : '';
		# Is date/time ready?
		$_POST['date'] && $_POST['time'] ? $datetime_ready = 1 : '';

		# Handle date
		$date_unit 						= explode("-", $_POST['date']);
		$time_unit 						= explode(":", $_POST['time']);
		$checkpoint_year 			= $date_unit[2];
		$checkpoint_month 		= $date_unit[0];
		$checkpoint_day 			= $date_unit[1];
		$checkpoint_hour 			= $time_unit[0];
		$checkpoint_minute		= $time_unit[1];
		$checkpoint_date_time = $date_unit[2] . '-' . $date_unit[0] . '-' . $date_unit[1] . ' ' . $time_unit[0] . ':' . $time_unit[1] . ':00';
	}

	# Processing files
	if ($_GET['rate_confirmation'] || $_GET['bol'] || $_GET['raw_bol'] || $_GET['payment_confirmation'] || $_GET['quickpay_invoice']) {
		
		$processing_file = 1;

		$_GET['rate_confirmation'] ? $file_get_value = 'rate_confirmation' : '';
		$_GET['bol'] ? $file_get_value = 'bol' : '';
		$_GET['raw_bol'] ? $file_get_value = 'raw_bol' : '';
		$_GET['payment_confirmation'] ? $file_get_value = 'payment_confirmation' : '';
		$_GET['quickpay_invoice'] ? $file_get_value = 'quickpay_invoice' : '';
	}

	# List available other charges
	$other_charges_list = DB::getInstance()->query("SELECT * FROM loader_other_charges ORDER BY name ASC");
	$other_charges_list_count = $other_charges_list->count();
	$i = 1;
	
	if ($other_charges_list_count) {

		foreach ($other_charges_list->results() as $other_charges_list_data) {
			
			$charge_data_id[$i] = $other_charges_list_data->data_id;
			$charge_name[$i] = $other_charges_list_data->name;
			$i++;

			$charge_id_name[$other_charges_list_data->data_id] = $other_charges_list_data->name;
		}
	}

	# Get other charges for this load
	$other_charges = DB::getInstance()->query("SELECT * FROM loader_load_other_charges WHERE load_id = " . $_GET['load_id'] . " ORDER BY price DESC");
	$other_charges_count = $other_charges->count();
	$i = 1;

	if ($other_charges_count) {

		foreach ($other_charges->results() as $other_charges_data) {
			$other_charges_data_id[$i] = $other_charges_data->data_id;
			$other_charges_id[$i] = $other_charges_data->other_charge_id;
			$other_charges_price[$i] = number_format($other_charges_data->price, 2);
			$other_charges_price_total += $other_charges_data->price;
			$i++;
		}
	}

	# Get all notes data related to this load_id
	$load_note = DB::getInstance()->query("SELECT * FROM loader_load_note WHERE load_id = " . $_GET['load_id'] . " ORDER BY added DESC");
	$load_note_count = $load_note->count();
	$i = 1;

	foreach ($load_note->results() as $load_note_data) {
		
		$load_note_note_id[$i] = $load_note_data->data_id;
		$load_note_note[$i] = html_entity_decode($load_note_data->note);
		$load_note_important[$i] = $load_note_data->important;
		$load_note_type[$i] = $load_note_data->type;
		$load_note_added[$i] = date('m/d/Y', strtotime($load_note_data->added));
		$load_note_added_time[$i] = date('G:i', strtotime($load_note_data->added));
		$load_note_user_id[$i] = $load_note_data->user_id;

		$i++;
	}

	# Schedule data
	# If available, get schedule data related to this load_id
	$schedule_load = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE load_id = " . $_GET['load_id']);
	$schedule_load_count = $schedule_load->count();

	if ($schedule_load_count) {
		
		foreach ($schedule_load->results() as $schedule_load_data) {
			
			$schedule_load_schedule_id = $schedule_load_data->schedule_id;
			$schedule_load_invoice_number = $schedule_load_data->invoice_number;
		}	
	}

	# Get all schedule data related to this $schedule_load_schedule_id
	$schedule = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE data_id = " . $schedule_load_schedule_id);
	
	$schedule_count = $schedule->count();

	foreach ($schedule->results() as $schedule_data) {
		
		$schedule_client_assoc_id = $schedule_data->client_assoc_id;
		$schedule_counter = $schedule_data->counter;
		$schedule_fee_option = $schedule_data->fee_option;
		$schedule_payment_confirmation = $schedule_data->payment_confirmation;
		$schedule_payment_confirmation_added = date('m/d/Y', strtotime($schedule_data->payment_confirmation_added));
		$schedule_added = date('m/d/Y', strtotime($schedule_data->added));
	}

	# Get all schedule loads related to this $schedule_load_schedule_id
	$schedule_load_list = DB::getInstance()->query("SELECT factoring_company_schedule_load.load_id, factoring_company_schedule_load.invoice_number, loader_load.entry_id, loader_load.load_number FROM factoring_company_schedule_load LEFT JOIN loader_load ON factoring_company_schedule_load.load_id=loader_load.load_id WHERE schedule_id = " . $schedule_load_schedule_id);
	$schedule_load_list_count = $schedule_load_list->count();
	$i = 1;

	if ($schedule_load_list_count) {
		foreach ($schedule_load_list->results() as $schedule_load_list_data) {
			
			$schedule_load_list_load_id[$i] = $schedule_load_list_data->load_id;
			$schedule_load_list_invoice_number[$i] = $schedule_load_list_data->invoice_number;
			$schedule_load_list_entry_id[$i] = $schedule_load_list_data->entry_id;
			$schedule_load_list_load_number[$i] = $schedule_load_list_data->load_number;
			$i++;
		}	
	}

	# If $schedule_client_assoc_id is set, get all data related to this factoring_company_client_assoc, else get all data related to this client_id
	$schedule_client_assoc_id ? $value = "data_id = " . $schedule_client_assoc_id : $value = "client_id = " . $load_client_id[1];
	$schedule_client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE " . $value);

	$schedule_client_assoc_count = $schedule_client_assoc->count();

	foreach ($schedule_client_assoc->results() as $schedule_client_assoc_data) {
		
		$client_assoc_data_id = $schedule_client_assoc_data->data_id;
		$client_assoc_factoring_company_id = $schedule_client_assoc_data->factoring_company_id;
		$client_assoc_main = $schedule_client_assoc_data->main;
		$client_assoc_alt = $schedule_client_assoc_data->alt;
		$client_assoc_counter = $schedule_client_assoc_data->counter;
		# 02/12/16 - $last_invoice_number is added here to fix the invoice number that doesn't get picked up on first time schedules 
		# (where the number should be taken from here)
		# To make all changes here, substract 1 to the number because the add load form will add 1.
		# This value may be overwritten on the next block of code
		$last_invoice_number = $schedule_client_assoc_data->invoice_counter - 1;
		$client_assoc_added = date('m/d/Y', strtotime($schedule_client_assoc_data->added));
		$client_assoc_user_id = $schedule_client_assoc_data->user_id;
	}

	# Make array of schedule_ids for this $schedule_client_assoc_id
	$schedule_id_array = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE client_assoc_id = " . $client_assoc_data_id);

	if ($schedule_id_array->count()) {

		foreach ($schedule_id_array->results() as $schedule_id_array_data) {
			
			# Array
			$schedule_id_per_client_assoc_array[] = $schedule_id_array_data->data_id;
		}

		# Get last invoice_number counter for this schedule id
		$last_invoice = DB::getInstance()->query("SELECT invoice_number FROM factoring_company_schedule_load WHERE schedule_id IN (" . implode(', ', $schedule_id_per_client_assoc_array) . ") ORDER BY invoice_number DESC LIMIT 1");

		foreach ($last_invoice->results() as $last_invoice_data) {
			
			$last_invoice_number = $last_invoice_data->invoice_number;
		}	
	}

	# Factoring company
	$factoring_company = DB::getInstance()->query("SELECT * FROM factoring_company WHERE data_id = " . $client_assoc_factoring_company_id);
	$factoring_company_count = $factoring_company->count();

	if ($factoring_company_count) {
		foreach ($factoring_company->results() as $factoring_company_data) {

			// $factoring_company_requires_soar = $factoring_company_data->requires_soar;
			$factoring_company_status_1 = $factoring_company_data->status;
			$factoring_company_name = html_entity_decode($factoring_company_data->name);
		}
	}

	# Check for soar file
	file_exists($path . 'schedule/soar-' . $schedule_load_schedule_id . '.pdf') ? $soar_file = 1 : $soar_file = null;

	### Sync checkpoints for first time visited loads that come from drafts ###
	# Filter loads that originated from a draft
	if ($load_draft_id[$i] > 0) {
		
		# Sync if no checkpoint count
		# Checkpoints are required to add drafts, all drafts should have checkpoints
		if (!$checkpoint_id_count) {
			
			# Checkpoints from draft
			$checkpoint_from_draft = DB::getInstance()->query("SELECT * FROM loader_load_draft_checkpoint WHERE load_draft_id = " . $load_draft_id[$i]);
			$checkpoint_from_draft_count = $checkpoint_from_draft->count();

			# Loop through results
			foreach ($checkpoint_from_draft->results() as $checkpoint_from_draft_data) {
				
				# Sync checkpoint on loader_checkpoint table
				$sync_checkpoint = DB::getInstance()->query("
					INSERT INTO loader_checkpoint (
						load_id
						, date_time
						, city
						, state_id
						, data_type
						, added
						, user_id) 

					VALUES (
						" . $_GET['load_id'] . "
						, '" . $checkpoint_from_draft_data->date_time . "'
						, '" . $checkpoint_from_draft_data->city . "'
						, " . $checkpoint_from_draft_data->state_id . "
						, " . $checkpoint_from_draft_data->data_type . "
						, '" . $checkpoint_from_draft_data->added . "'
						, " . $checkpoint_from_draft_data->user_id . "
					)"
				);

				if ($sync_checkpoint->count()) {

					$added_checkpoint+= 1;

					if ($added_checkpoint == $checkpoint_from_draft_count) {
						
						$checkpoints_synced = 1;
						Session::flash('loader', 'Checkpoints synced');
					}
				}
			}
		}
	}
}

# Loads (alt)
$load_ALT = DB::getInstance()->query("
	
	SELECT 

	loader_load.entry_id, loader_load.broker_id, loader_load.load_id, 
	loader_load.load_number, loader_load.line_haul, loader_load.avg_diesel_price, 
	loader_load.weight, loader_load.miles, loader_load.deadhead, 
	loader_load.reference, loader_load.commodity, loader_load.equipment, 
	loader_load.notes, loader_load.broker_name_number, loader_load.broker_email, 
	loader_load.billing_status, loader_load.billing_date, loader_load.load_lock, 
	loader_load.load_status, loader_load.added, loader_load.added_by, 
	loader_load.user_id, loader_entry.data_id, loader_entry.driver_id

	FROM loader_load 

	LEFT JOIN loader_entry ON loader_load.entry_id = loader_entry.data_id

	ORDER BY load_number ASC
");

$load_ALT_count = $load_ALT->count();
$i = 1;

if ($load_ALT_count) {
	
	foreach ($load_ALT->results() as $load_ALT_data) {

		$load_ALT_load_id[$i] = $load_ALT_data->load_id;
		$load_ALT_load_number[$i] = $load_ALT_data->load_number;

		$load_ALT_entry_driver_id[$i] = $load_ALT_data->driver_id;
		$i++;
	}
}

# Driver
// $driver_list = DB::getInstance()->query("SELECT client_user.user_id, client_user.client_id, client_user.user_type, user.id, user.name, user.last_name FROM client_user INNER JOIN user ON client_user.user_id=user.id WHERE user_type != 0 ORDER BY user.name ASC");

$driver_list = DB::getInstance()->query("SELECT client_user.user_id, client_user.client_id, client_user.user_type, user.id, user.name, user.last_name, client.status FROM client_user INNER JOIN user ON client_user.user_id=user.id INNER JOIN client ON client_user.client_id=client.data_id WHERE user_type != 0 ORDER BY user.name ASC");

$driver_list_count = $driver_list->count();
$i = 1;

if ($driver_list_count) {
	
	foreach ($driver_list->results() as $driver_list_data) {
		
		$driver_list_user_id[$i] = $driver_list_data->user_id;
		$driver_list_client_id[$i] = $driver_list_data->client_id;
		$driver_list_name[$i] = html_entity_decode($driver_list_data->name);
		$driver_list_last_name[$i] = html_entity_decode($driver_list_data->last_name);
		$driver_list_client_status[$i] = $driver_list_data->status;
		$i++;

		$driver_list_id_client_id[$driver_list_data->user_id] = $driver_list_data->client_id;
	}
}

if ($_GET['edit_main']) {
	
	# client_driver
	// Lists all drivers associated with an active client profile
	$client_driver = DB::getInstance()->query("SELECT client_user.user_id, client_user.client_id, client_user.user_type , client.company_name, client.status FROM client_user INNER JOIN client ON client_user.client_id=client.data_id WHERE user_type IN (1, 2) && status = 1");
	
	$client_driver_count = $client_driver->count();
	$i = 1;

	if ($client_driver_count) {
		foreach($client_driver->results() as $client_driver_data) {

			$client_driver_user_id[$i] = $client_driver_data->user_id;
			$client_driver_client_id[$i] = $client_driver_data->client_id;
			$client_driver_data_id[$i] = $client_driver_data->data_id;
			$client_driver_user_type[$i] = $client_driver_data->user_type;
			$client_driver_user_manager[$i] = $client_driver_data->user_manager;
			$i++;
		}
	}
}

# Draft rate confirmation path
$draft_rate_con_path = '/home/logistic/public_html/files/draft-rate-confirmation/';

if ($_POST['show_all_drafts']) {
	
	# Draft loads
	$draft_list = DB::getInstance()->query("
		SELECT *
		FROM loader_load_draft
		WHERE added LIKE '" . date('Y-m-d') . "%'
		ORDER BY added DESC");

	$draft_list_count = $draft_list->count();
	$i = 1;

	if ($draft_list_count) {

		foreach ($draft_list->results() as $draft_list_data) {

			$draft_item_id[$i] = $draft_list_data->id;
			$draft_item_initial_rate[$i] = number_format($draft_list_data->initial_rate, 2);
			$draft_item_driver_request_rate[$i] = number_format($draft_list_data->driver_request_rate, 2);
			$draft_item_final_rate[$i] = number_format($draft_list_data->final_rate, 2);
			$draft_item_deadhead[$i] = number_format($draft_list_data->deadhead, 2);
			$draft_item_broker_name_number[$i] = html_entity_decode($draft_list_data->broker_name_number);
			$draft_item_broker_email[$i] = html_entity_decode($draft_list_data->broker_email);
			$draft_item_note[$i] = html_entity_decode($draft_list_data->note);
			$draft_item_loaded[$i] = $draft_list_data->loaded;
			$draft_item_user_id[$i] = $draft_list_data->user_id;
			$draft_item_added[$i] = $draft_list_data->added;

		$i++;
		}
	}
} elseif ($_GET['draft_rate_con']) {

	# Draft loads
	$draft_list = DB::getInstance()->query("
		SELECT 
			loader_load_draft_lead_status.lead_id
			, loader_load_draft_lead_status.status
			, loader_load_draft_lead_status.user_id
			, loader_load_draft_lead_status.added
			, loader_load_draft_lead.draft_id
			, loader_load_draft_lead.driver_id
		FROM loader_load_draft_lead_status 
		LEFT JOIN loader_load_draft_lead ON loader_load_draft_lead_status.lead_id=loader_load_draft_lead.id
		WHERE draft_id = " . $_GET['draft_rate_con']);

	$draft_list_count = $draft_list->count();
	$i = 1;

	if ($draft_list_count) {

		foreach ($draft_list->results() as $draft_list_data) {

			$draft_list_draft_id[$i] = $draft_list_data->draft_id;
			$draft_list_driver_id[$i] = $draft_list_data->driver_id;
		
			# Get draft data
			$draft_item = DB::getInstance()->query("
				SELECT * FROM loader_load_draft 
				WHERE id = " . $draft_list_draft_id[$i] . " && added LIKE '" . date('Y-m-d') . "%'");

			foreach ($draft_item->results() as $draft_item_data) {

				$draft_item_id[$i] = $draft_item_data->id;
				$draft_item_final_rate[$i] = number_format($draft_item_data->initial_rate, 2);
				$draft_item_final_rate_1[$i] = $draft_item_data->initial_rate;
				$draft_item_deadhead[$i] = number_format($draft_item_data->deadhead, 2);
				$draft_item_broker_name_number[$i] = html_entity_decode($draft_item_data->broker_name_number);
				$draft_item_broker_email[$i] = html_entity_decode($draft_item_data->broker_email);
				$draft_item_note[$i] = html_entity_decode($draft_item_data->note);
				$draft_item_loaded[$i] = $draft_item_data->loaded;
				$draft_item_loaded_count+= $draft_item_data->loaded;
				$draft_item_user_id[$i] = $draft_item_data->user_id;
				$draft_item_added[$i] = $draft_item_data->added;

				# Check if draft has rate confirmation
				if (file_exists($draft_rate_con_path . $draft_item_id[$i] . '.pdf')) {
		  		
		  		$draft_has_rate_con[$i] = 1;
		  	}
			}

		$i++;
		}
	}
} else {

	# Draft loads
	$draft_list = DB::getInstance()->query("
		SELECT 
			loader_load_draft_lead_status.lead_id
			, loader_load_draft_lead_status.status
			, loader_load_draft_lead_status.user_id
			, loader_load_draft_lead_status.added
			, loader_load_draft_lead.draft_id
			, loader_load_draft_lead.driver_id
		FROM loader_load_draft_lead_status 
		LEFT JOIN loader_load_draft_lead ON loader_load_draft_lead_status.lead_id=loader_load_draft_lead.id
		WHERE status = 4
		ORDER BY added DESC");

	$draft_list_count = $draft_list->count();
	$i = 1;

	if ($draft_list_count) {

		foreach ($draft_list->results() as $draft_list_data) {

			$draft_list_draft_id[$i] = $draft_list_data->draft_id;
			$draft_list_driver_id[$i] = $draft_list_data->driver_id;
		
			# Get draft data
			/*$draft_item = DB::getInstance()->query("
				SELECT * FROM loader_load_draft 
				WHERE id = " . $draft_list_draft_id[$i] . " && added LIKE '" . date('Y-m-d') . "%'");*/

			$draft_item = DB::getInstance()->query("
				SELECT * FROM loader_load_draft 
				WHERE id = " . $draft_list_draft_id[$i]);

			foreach ($draft_item->results() as $draft_item_data) {

				$draft_item_id[$i] = $draft_item_data->id;
				$draft_item_final_rate[$i] = number_format($draft_item_data->initial_rate, 2);
				$draft_item_final_rate_1[$i] = $draft_item_data->initial_rate;
				$draft_item_deadhead[$i] = number_format($draft_item_data->deadhead, 2);
				$draft_item_broker_name_number[$i] = html_entity_decode($draft_item_data->broker_name_number);
				$draft_item_broker_email[$i] = html_entity_decode($draft_item_data->broker_email);
				$draft_item_note[$i] = html_entity_decode($draft_item_data->note);
				$draft_item_loaded[$i] = $draft_item_data->loaded;
				$draft_item_loaded_count+= $draft_item_data->loaded;
				$draft_item_user_id[$i] = $draft_item_data->user_id;
				$draft_item_added[$i] = $draft_item_data->added;

				# Check if draft has rate confirmation
				if (file_exists($draft_rate_con_path . $draft_item_id[$i] . '.pdf')) {
		  		
		  		$draft_has_rate_con[$i] = 1;
		  	}
			}

		$i++;
		}
	}
}
