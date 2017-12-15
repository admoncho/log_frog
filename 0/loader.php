<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath']. '/core/init.php';

# Limbo
include($_SESSION['ProjectPath']."/includes/limbo.php");

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

$file_directory = "/home/" . $rootFolder . "/public_html/files/"; 	// File directory

if (!$_GET) {

	# loader_config
	$loader_config = DB::getInstance()->query("SELECT * FROM loader_config WHERE config_id = 1");
	$loader_config_count = $loader_config->count();
	foreach ($loader_config->results() as $loader_config_data) {
		$linked_checkpoint_max_time_span = $loader_config_data->linked_checkpoint_max_time_span;
		$loader_status_change_notification_template = $loader_config_data->loader_status_change_notification_template;
		$loader_status_change_notification_subject = $loader_config_data->loader_status_change_notification_subject;
	}

	# Schedule config
	$schedule_config = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_config");
	$schedule_config_count = $schedule_config->count();
	
	foreach ($schedule_config->results() as $schedule_config_data) {
		$schedule_email_subject = html_entity_decode($schedule_config_data->email_subject);
		$schedule_email_body = html_entity_decode($schedule_config_data->email_body);
	}

} elseif($_GET['id']) {
	$loader_entry = DB::getInstance()->query("SELECT * FROM loader_entry WHERE data_id = " . $_GET['id']);
	foreach ($loader_entry->results() as $loader_entry_data) {
		$entry_driver_id = $loader_entry_data->driver_id;
	}

	# Get client_id from this load driver_id
	$loader_entry_client_id = DB::getInstance()->query("SELECT * FROM client_user WHERE user_id = " . $entry_driver_id);
	foreach ($loader_entry_client_id->results() as $loader_entry_client_id_data) {
		$entry_client_id = $loader_entry_client_id_data->client_id;

		# Which person should receive the quickpay email if sent?
		if ($loader_entry_client_id_data->user_type == 2) {
			// If user type == 2 (driver), send email to user_manager
			$driver_manager_id = $loader_entry_client_id_data->user_manager;
		} elseif ($loader_entry_client_id_data->user_type == 1) {
			// If user type == 1 (owner/operator), send email to user_id
			$driver_manager_id = $loader_entry_client_id_data->user_id;
		}
	}

	# Loads
	if (!$_GET['load_id'] && !$_GET['delete_load_id']) {
		// Gets list of all available loads for this entry_id
		$loader_load = DB::getInstance()->query("SELECT * FROM loader_load WHERE entry_id = " . $_GET['id']);
	} elseif ($_GET['load_id'] || $_GET['send_load_info']) {
		// Gets data for one load only (load_id)

		// Set value based on parameter
		$_GET['load_id'] ? $get_load_id = $_GET['load_id'] : ($_GET['send_load_info'] ? $get_load_id = $_GET['send_load_info'] : '');

		$loader_load = DB::getInstance()->query("SELECT * FROM loader_load WHERE load_id = " . $get_load_id);
	} elseif ($_GET['delete_load_id']) {
		// Gets data for one load only (load_id)
		$loader_load = DB::getInstance()->query("SELECT * FROM loader_load WHERE load_id = " . $_GET['delete_load_id']);
	} elseif (!$_GET) {

		### loading/offloading hour met alert ###
		
		// Gets data for all loads
		$loader_load = DB::getInstance()->query("SELECT * FROM loader_load");
	}

	$loader_load_count = $loader_load->count();
	$loader_load_counter = 1;

	if ($loader_load_count) {
		foreach ($loader_load->results() as $loader_load_data) {
			$load_data_broker_data_id[$loader_load_counter] = $loader_load_data->broker_id;
			$load_data_load_id[$loader_load_counter] =  $loader_load_data->load_id;
			$load_data_billing_status[$loader_load_counter] = $loader_load_data->billing_status;
			$load_data_line_haul[$loader_load_counter] = number_format($loader_load_data->line_haul, 2);
			$load_data_weight[$loader_load_counter] = $loader_load_data->weight;
			$load_data_miles[$loader_load_counter] = $loader_load_data->miles;
			$load_data_deadhead[$loader_load_counter] = number_format($loader_load_data->deadhead, 1);
			$load_data_broker_name_number[$loader_load_counter] = html_entity_decode($loader_load_data->broker_name_number);
			$load_data_broker_email[$loader_load_counter] = html_entity_decode($loader_load_data->broker_email);
			$load_data_commodity[$loader_load_counter] = html_entity_decode($loader_load_data->commodity);
			$load_data_notes[$loader_load_counter] = html_entity_decode($loader_load_data->notes);
			$load_data_avg_diesel_price[$loader_load_counter] = number_format($loader_load_data->avg_diesel_price, 2);
			$load_data_reference[$loader_load_counter] = html_entity_decode($loader_load_data->reference);
			$load_data_load_number[$loader_load_counter] = html_entity_decode($loader_load_data->load_number);
			$load_data_added[$loader_load_counter] = date('M d, Y', strtotime($loader_load_data->added));
			$load_data_added_time[$loader_load_counter] = date('h:i a', strtotime($loader_load_data->added));
			$load_data_billing_date[$loader_load_counter] = date('M d, Y', strtotime($loader_load_data->billing_date));
			$load_data_load_lock[$loader_load_counter] = $loader_load_data->load_lock;
			$load_data_load_status[$loader_load_counter] = $loader_load_data->load_status;
			$load_data_user_id[$loader_load_counter] = $loader_load_data->user_id;
			$load_data_added_by[$loader_load_counter] = $loader_load_data->added_by;
			$loader_load_counter++;

			# User data
			$load_user = DB::getInstance()->query("SELECT * FROM _QU_i WHERE user_id = " . $load_data_user_id[$i]);
			foreach ($load_user->results() as $load_user_data) {
				$load_added_by_name = html_entity_decode($load_user_data->name);
				$load_added_by_last_name = html_entity_decode($load_user_data->last_name);
			}

			# Checkpoints
			$loader_checkpoint = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . ' ORDER BY date_time ASC');
			$loader_checkpoint_count = $loader_checkpoint->count();
			if ($loader_checkpoint_count) {
				$checkpointCounter = 1;
				foreach ($loader_checkpoint->results() as $loader_checkpoint_data) {
					$checkpoint_id[$checkpointCounter] = $loader_checkpoint_data->checkpoint_id;
					$checkpoint_date_time[$checkpointCounter] = date('Y-m-d G:i:s', strtotime($loader_checkpoint_data->date_time));
					$checkpoint_date[$checkpointCounter] = date('m-d-Y', strtotime($loader_checkpoint_data->date_time));
					$checkpoint_time[$checkpointCounter] = date('G:i', strtotime($loader_checkpoint_data->date_time));
					$checkpoint_line_1[$checkpointCounter] = html_entity_decode($loader_checkpoint_data->line_1);
					$checkpoint_line_2[$checkpointCounter] = html_entity_decode($loader_checkpoint_data->line_2);
					$checkpoint_city[$checkpointCounter] = html_entity_decode($loader_checkpoint_data->city);
					$checkpoint_state_id[$checkpointCounter] = $loader_checkpoint_data->state_id;
					$checkpoint_zip_code[$checkpointCounter] = html_entity_decode($loader_checkpoint_data->zip_code);
					$checkpoint_contact[$checkpointCounter] = html_entity_decode($loader_checkpoint_data->contact);
					$checkpoint_appointment[$checkpointCounter] = html_entity_decode($loader_checkpoint_data->appointment);
					$checkpoint_notes[$checkpointCounter] = html_entity_decode($loader_checkpoint_data->notes);
					$checkpoint_data_type[$checkpointCounter] = $loader_checkpoint_data->data_type;
					$checkpoint_added[$checkpointCounter] = date('M d, Y', strtotime($loader_checkpoint_data->added));
					$checkpoint_added_time[$checkpointCounter] = date('h:i:s a', strtotime($loader_checkpoint_data->added));
					$checkpoint_user_id[$checkpointCounter] = $loader_checkpoint_data->user_id;
					$checkpoint_status[$checkpointCounter] = $loader_checkpoint_data->status;

					# Count checkpoint associations for data_type = 0 (pickups)
					if ($checkpoint_data_type[$checkpointCounter] == 0) {
						$loader_checkpoint_assoc = DB::getInstance()->query("SELECT * FROM loader_checkpoint_assoc WHERE checkpoint = " . $checkpoint_id[$checkpointCounter]);
						$loader_checkpoint_assoc_count[$checkpointCounter] = $loader_checkpoint_assoc->count();
					}

					$checkpointCounter++;
				}				
			}

			# Files
			$loader_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id']);
			$loader_file_count = $loader_file->count();
			if ($loader_file_count) {
				$fileCounter = 1;
				foreach ($loader_file->results() as $loader_file_data) {
					$file_id[$fileCounter] = $loader_file_data->file_id;
					$file_name[$fileCounter] = $loader_file_data->file_name;
					$file_type[$fileCounter] = $loader_file_data->file_type;
					$file_added_date[$fileCounter] = date('m d Y', strtotime($loader_file_data->added));
					$file_added_time[$fileCounter] = date('G:i a', strtotime($loader_file_data->added));
					$file_user_id[$fileCounter] = $loader_file_data->user_id;
					$file_extension[$fileCounter] = preg_replace('/^(.*[.])/', '', $file_name[$fileCounter]);
					$fileCounter++;
				}
			}

			# URI: /0/loader?id=n&load_id=n&file_id=n
			# URI: /0/loader?id=n&load_id=n&delete_file_id=n
			if ($_GET['file_id'] || $_GET['delete_file_id']) {
				$_GET['file_id'] ? $_GET_file_id = $_GET['file_id'] : $_GET_file_id = $_GET['delete_file_id'] ;
				$loader_file_id = DB::getInstance()->query("SELECT * FROM loader_file WHERE file_id = " . $_GET_file_id);
				$loader_file_id_count = $loader_file_id->count();
				if ($loader_file_id_count) {
					foreach ($loader_file_id->results() as $loader_file_id_data) {
						$loader_file_id_name = $loader_file_id_data->file_name;
						$loader_file_id_extensionless_name = preg_replace('/\.(.*)/', '', $loader_file_id_data->file_name);
						$loader_file_id_extension = preg_replace('/^(.*[.])/', '', $loader_file_id_data->file_name);
						$loader_file_id_type = $loader_file_id_data->file_type;
						$loader_file_id_added = date('m d Y', strtotime($loader_file_id_data->added));
						$loader_file_id_user_id = $loader_file_id_data->user_id;
					}
				}
			}

			# BOL File
			$loader_bol_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 1');
			$loader_bol_file_count = $loader_bol_file->count();
			if ($loader_bol_file_count) {
				foreach ($loader_bol_file->results() as $loader_bol_file_data) {
					$bol_file_id = $loader_bol_file_data->file_id;
					$bol_file_name = $loader_bol_file_data->file_name;
				}
			}

			# Rate confirmation File
			$loader_rate_confirmation_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 2');
			$loader_rate_confirmation_file_count = $loader_rate_confirmation_file->count();
			if ($loader_rate_confirmation_file_count) {
				foreach ($loader_rate_confirmation_file->results() as $loader_rate_confirmation_file_data) {
					$rate_confirmation_file_id = $loader_rate_confirmation_file_data->file_id;
					$rate_confirmation_file_name = $loader_rate_confirmation_file_data->file_name;
				}
			}

			# Payment confirmation File
			$loader_payment_confirmation_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 3');
			$loader_payment_confirmation_file_count = $loader_payment_confirmation_file->count();
			if ($loader_payment_confirmation_file_count) {
				foreach ($loader_payment_confirmation_file->results() as $loader_payment_confirmation_file_data) {
					$payment_confirmation_file_id = $loader_payment_confirmation_file_data->file_id;
					$payment_confirmation_file_name = $loader_payment_confirmation_file_data->file_name;
				}
			}

			# RAW BOL File
			$loader_raw_bol_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $_GET['load_id'] . ' && file_type = 4');
			$loader_raw_bol_file_count = $loader_raw_bol_file->count();
			if ($loader_raw_bol_file_count) {
				foreach ($loader_raw_bol_file->results() as $loader_raw_bol_file_data) {
					$raw_bol_file_id = $loader_raw_bol_file_data->file_id;
					$raw_bol_file_name = $loader_raw_bol_file_data->file_name;
				}
			}
		}

		# TEMP: GET DRIVER EMAIL ADDRESS
		$driver_email = DB::getInstance()->query("SELECT * FROM user WHERE id = " . $entry_driver_id);

		foreach ($driver_email->results() as $driver_email_data) {
			
			$temp_driver_email = $driver_email_data->email;
		}
	}

	### Factoring Companies ###

	# View schedule button if load already belongs to a schedule
	$in_schedule = DB::getInstance()->query("SELECT factoring_company_schedule_load.schedule_id, factoring_company_schedule.counter FROM factoring_company_schedule_load INNER JOIN factoring_company_schedule ON factoring_company_schedule_load.schedule_id=factoring_company_schedule.data_id WHERE load_id = " . $_GET['load_id']);
	$in_schedule_count = $in_schedule->count();

	# Get schedule_id if count
	if ($in_schedule_count) {
		
		foreach ($in_schedule->results() as $in_schedule_data) {
			
			$schedule_id = $in_schedule_data->schedule_id;
			$schedule_counter = $in_schedule_data->counter;
		}
	}

	# Gather data necessary to create a schedule
	# Get factoring company - client association from factoring_company_client_assoc by $entry_client_id
	$client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE client_id = " . $entry_client_id);
	$client_assoc_count = $client_assoc->count();

	# Go on if item found
	if ($client_assoc_count) {
		
		# Loop trough data
		foreach ($client_assoc->results() as $client_assoc_data) {
			
			$client_assoc_factoring_company_id = $client_assoc_data->factoring_company_id;
			$client_assoc_data_id = $client_assoc_data->data_id;
			$client_assoc_main = $client_assoc_data->main;
			$client_assoc_alt = $client_assoc_data->alt;
			$client_assoc_counter = $client_assoc_data->counter;
			$client_assoc_invoice_counter = $client_assoc_data->invoice_counter; // The number saved when creating the factoring co / client assoc, next # to be used
		}

		# Check if there is a schedule with same counter number under this client_assoc_id
		$factoring_company_schedule = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE client_assoc_id = " . $client_assoc_data_id . " && counter = " . $client_assoc_counter);
		$factoring_company_schedule_count = $factoring_company_schedule->count();

		if ($factoring_company_schedule_count) {
			
			foreach ($factoring_company_schedule->results() as $factoring_company_schedule_data) {
				
				$schedule_invoice_counter = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE schedule_id = " . $factoring_company_schedule_data->data_id . " ORDER BY invoice_number DESC LIMIT 1");

				# If count, add 1 to last invoice number
				if ($schedule_invoice_counter->count()) {

					foreach ($schedule_invoice_counter->results() as $schedule_invoice_counter_data) {
						
						# Rewrite variable adding 1 to last invoice found
						$client_assoc_invoice_counter = $schedule_invoice_counter_data->invoice_number + 1;
					}
				} else {

					# Get last schedule data to get next invoice counter
					# This is necessary because if there are no loads in this schedule (as in this is the first load to be added to this schedule)
					# We need to know what was the last invoice counter used
					$previous_factoring_company_schedule = DB::getInstance()->query("SELECT * FROM factoring_company_schedule WHERE client_assoc_id = " . $client_assoc_data_id . " && counter = " . ($client_assoc_counter - 1));
					
					foreach ($previous_factoring_company_schedule->results() as $previous_factoring_company_schedule_data) {
						
						# Get last number used
						$previous_schedule_invoice_counter = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_load WHERE schedule_id = " . $previous_factoring_company_schedule_data->data_id . " ORDER BY invoice_number DESC LIMIT 1");

						foreach ($previous_schedule_invoice_counter->results() as $previous_schedule_invoice_counter_data) {
							
							# Rewrite variable adding 1 to last invoice found
							$client_assoc_invoice_counter = $previous_schedule_invoice_counter_data->invoice_number + 1;
						}
					}
				}
			}
		}
	}
}

### THIS CODE IS REPEATED ###
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
### THIS CODE IS REPEATED END ###

# Check if there is a client/broker relationship, if not, invoice cannot be sent
$client_broker_relation = DB::getInstance()->query("SELECT * FROM client_broker_assoc WHERE broker_id = " . $load_data_broker_data_id[1] . " && client_id = " . $entry_client_id);

$client_broker_relation_count = $client_broker_relation->count();

# Check if there is a client/broker counter, if not, invoice cannot be sent
$client_broker_invoice_counter = DB::getInstance()->query("SELECT * FROM loader_quickpay_invoice_counter WHERE broker_id = " . $load_data_broker_data_id[1] . " && client_id = " . $entry_client_id);
$client_broker_invoice_counter_count = $client_broker_invoice_counter->count();

# Get list of active broker companies
$user_e_profile_broker = DB::getInstance()->query("SELECT * FROM broker WHERE status = 1 ORDER BY company_name ASC");
$user_e_profile_broker_count = $user_e_profile_broker->count();
if ($user_e_profile_broker_count) {
	$i = 1;
	foreach ($user_e_profile_broker->results() as $user_e_profile_broker_data) {
		$broker_data_id[$i] = $user_e_profile_broker_data->data_id;
		$broker_company_name[$i] = $user_e_profile_broker_data->company_name;
		$i++;
	}
}

# Get list of active broker companies by ID
$user_e_profile_broker_by_id = DB::getInstance()->query("SELECT * FROM broker WHERE status = 1 ORDER BY data_id ASC");
$user_e_profile_broker_by_id_count = $user_e_profile_broker_by_id->count();
if ($user_e_profile_broker_by_id_count) {
	foreach ($user_e_profile_broker_by_id->results() as $user_e_profile_broker_by_id_data) {
		$broker_data_id_by_id[$user_e_profile_broker_by_id_data->data_id] = $user_e_profile_broker_by_id_data->data_id;
		$broker_company_name_by_id[$user_e_profile_broker_by_id_data->data_id] = $user_e_profile_broker_by_id_data->company_name;
	}
}

# Get list of other charges
$loader_other_charges = DB::getInstance()->query("SELECT * FROM loader_other_charges ORDER BY name ASC");
$loader_other_charges_count = $loader_other_charges->count();
if ($loader_other_charges_count) {
	$i = 1;
	foreach ($loader_other_charges->results() as $loader_other_charges_data) {
		$charge_data_id[$i] = $loader_other_charges_data->data_id;
		$charge_name[$i] = $loader_other_charges_data->name;
		$i++;
	}
}

include($_SESSION['ProjectPath']."/includes/controller-calls.php");

# Get other charges [REQUIRED POST CONTROLLER CALLS]
$load_other_charges = DB::getInstance()->query("SELECT * FROM loader_load_other_charges WHERE load_id = " . $load_data_load_id[1] . " ORDER BY price DESC");
$load_other_charges_count = $load_other_charges->count();
$load_other_charges_counter = 1;
if ($load_other_charges_count) {
	foreach ($load_other_charges->results() as $load_other_charges_data) {
		$load_other_charges_data_id[$load_other_charges_counter] = $load_other_charges_data->data_id;
		$load_other_charges_item[$load_other_charges_counter] = $load_other_charges_data->item;
		$load_other_charges_price[$load_other_charges_counter] = $load_other_charges_data->price;

		$load_other_charges_counter++;
	}
}

// Push tabs
if (!$_GET) {

	Session::exists('add_loader_other_charge') 
		|| Session::exists('add_loader_other_charge_error')
			|| Session::exists('update_loader_other_charge')
				|| Session::exists('update_loader_other_charge_error')
					|| Session::exists('delete_loader_other_charge')
						|| Session::exists('delete_loader_other_charge_error')
							|| Session::exists('add_loader_file_label') 
								|| Session::exists('add_loader_file_label_error')
									|| Session::exists('update_loader_file_label')
										|| Session::exists('update_loader_file_label_error')
											|| Session::exists('delete_loader_file_label')
												|| Session::exists('delete_loader_file_label_error')
													|| Session::exists('update_loader_config')
														|| Session::exists('add_loader_trailer_type')
															|| Session::exists('update_loader_trailer_type')
																|| Session::exists('delete_loader_trailer_type')
																	|| Session::exists('add_loader_trailer_deck_material')
																		|| Session::exists('update_loader_trailer_deck_material')
																			|| Session::exists('delete_loader_trailer_deck_material')
																				|| Session::exists('add_loader_trailer_door_type')
																					|| Session::exists('update_loader_trailer_door_type')
																						|| Session::exists('delete_loader_trailer_door_type')
																							|| Session::exists('add_loader_trailer_roof_type')
																								|| Session::exists('update_loader_trailer_roof_type')
																									|| Session::exists('delete_loader_trailer_roof_type')
																										|| Session::exists('add_loader_driver_equipment')
																											|| Session::exists('update_loader_driver_equipment')
																												|| Session::exists('delete_loader_driver_equipment')
																													|| Session::exists('add_loader_driver_features')
																														|| Session::exists('update_loader_driver_features')
																															|| Session::exists('delete_loader_driver_features')
																																|| Session::exists('add_loader_payment_method')
																																	|| Session::exists('update_loader_payment_method')
																																		|| Session::exists('delete_loader_payment_method')
																																			|| Session::exists('update_schedule_template')
																																				|| Session::exists('update_schedule_template_error') ? $pushTab = 'tab_config' : '' ;

}

# Broker
include($_SESSION['ProjectPath'] . "/resource/library/quantum/module/broker/db/broker.php");

// Save token
$csrfToken = Token::generate(); ?>
<!DOCTYPE html>
<html>
<head>	
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title></title>
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/nanoscroller.css" />
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/compiled/theme_styles.css" />
	<!-- this page specific styles -->
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-default.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-growl.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-bar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-attached.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-other.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-theme.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/select2.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/compiled/wizard.css">
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/footable.core.css" />
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/bootstrap-timepicker.css" />
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/html5shiv.js"></script>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
	<![endif]-->
</head>
<body class="<?= $_IA_skin_class[$config_skin] ?>">
	<div id="theme-wrapper">
		<?php include($_SESSION['ProjectPath']."/includes/header.php") ?>
		<div id="page-wrapper" class="container<?= $config_nav == 1 ? ' nav-small' : '' ?>">
			<div class="row">
				<?php include($_SESSION['ProjectPath']."/includes/left-panel.php") ?>
				<div id="content-wrapper">
					<?php # URI: /0/loader
					if(!$_GET || $_GET['deleted_loads'] || $_GET['all_loads']):	 ?>
						<div class="row">
							<div class="col-lg-12">
	              <?php include($_SESSION['ProjectPath']."/includes/breadcrumbs-loader.php"); ?>
								<div class="clearfix">
									<h1 class="pull-left"><?= $_QC_language[$_QC_language_module_name_data_id] ?></h1>
									<div class="pull-right top-page-ui">
										<?php echo $_GET['deleted_loads'] || $_GET['all_loads'] ? '<a href="loader" class="btn btn-danger">Back to active loads</a>' : '' ;
										!$_GET ? include($_SESSION['ProjectPath']."/includes/module-new-item-link.php") : '';
										echo !$_GET ? '<a href="loader?deleted_loads=1" class="btn btn-danger">Deleted loads</a>' : '';
										echo !$_GET ? '<a style="margin-left: 5px;" href="loader?all_loads=1" class="btn btn-primary">All loads</a>' : '';
										include($_SESSION['ProjectPath']."/includes/module-refresh-link.php"); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="main-box no-header clearfix">
									<div class="main-box-body clearfix">
										<div class="tabs-wrapper profile-tabs">
											<ul class="nav nav-tabs">
												<li<?= !$pushTab ? ' class="active"' : '' ?>><a href="#tab_loader" data-toggle="tab"><?= $_GET['deleted_loads'] ? 'Deleted ' : '' ?>Load entries</a></li>
												<?php if (!$_GET) {
													# Hide if GET params ?>
													<li<?= $pushTab == 'tab_config' ? ' class="active"' : '' ?>><a href="#tab_config" data-toggle="tab"><?= $_QC_language[141] ?></a></li> <?php
												} ?>
											</ul>
											<div class="tab-content">
												<div class="tab-pane fade<?= !$pushTab ? ' in active' : '' ?>" id="tab_loader">
													<?php if ($load_list_count) { ?>
														<div class="row">
															<div class="col-sm-12 col-md-6">
																<?php # Display warning for $flag_payment_missing_count
																if ($flag_payment_missing_count) { ?>
																 	<div class="alert alert-block alert-danger fade in">
																		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
																		<h4>Aging invoices</h4>
																		<p>The system detects <?= $flag_payment_missing_count ?> load<?= $flag_payment_missing_count > 1 ? 's' : '' ?> that <?= $flag_payment_missing_count > 1 ? 'were' : 'was' ?> created 10+ days ago and have not been billed yet, click on the drop down menu to see them.</p>
																		<p>
																			<form action="" method="get">
																				<select class="form-control" id="flag_payment_missing">
																					<option></option>
																					<?php for ($i=1; $i <= $flag_payment_missing_count ; $i++) { ?>
																						<option value="<?= $flag_payment_missing_load_number[$i] ?>">Load #<?= $flag_payment_missing_load_number[$i] ?></option> <?php
																					} ?>
																				</select>
																			</form>
																		</p>
																	</div> <?php
																} ?>
															</div>
															<div class="col-sm-12 col-md-6">
																<?php 
																### loading/offloading hour met alert ###
																 	# loader side #

																 	# Display alert block ?>
																	<div class="alert alert-block alert-danger fade in">
																		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
																		<h4>Alerts</h4>
																		<p>Status change notification alerts.</p>
																		<p>
																			<form action="" method="get">
																				<select class="form-control" id="status_change_notification_alert">
																					<option></option> <?php
																					for ($i=1; $i <= $load_list_count ; $i++) {

																						# If condition to filter according to documentation (The load is active & The load is not locked) - load level
																						if ($load_list_load_status[$i] == 0 && $load_list_load_lock[$i] == 0) {

																							# Get data for this load
																							$load_alert = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[$i] . " && status = 0 && date_time < CURDATE() LIMIT 1");
																							
																							# Go on if we have data to display
																							if ($load_alert->count()) { 
																								# Loop through data
																								foreach ($load_alert->results() as $load_alert_data) { ?>
																									<option value="<?= $load_list_load_number[$i] ?>">Load #<?= $load_list_load_number[$i] ?></option><?php
																									
																									# Show notifications for today only
																									# This is not working
																									/*if (date('Y-m-d', strtotime($load_alert_data->date_time)) == date('Y-m-d')) { 
																									}*/
																								}
																							}
																						}
																					} ?>
																				</select>
																			</form>
																		</p>
																	</div> <?php
																?>
															</div>
														</div>

														<div class="filter-block pull-right">
															<div class="form-group pull-left">
																<input type="text" id="filter" class="form-control" placeholder="Search...">
																<i class="fa fa-search search-icon"></i>
															</div>
														</div>
														<?//=  $load_list_count
														# Hardcoding the data-page-size is not working (data-page-size="250"), limiting to 250 from limbo connection ?>
														<table class="table footable toggle-circle-filled" data-page-size="<?= $_GET['all_loads'] ? $load_list_count : 150 ?>" data-filter="#filter" data-filter-text-only="true">
															<thead>
																<tr>
																	<th>Broker</th>
																	<th>Load #</th>
																	<th>Driver</th>
																	<th>Line haul</th>
																	<th>Pick up</th>
																	<th>Delivery</th>
																	<th>Pickup</th>
																	<th>Delivery</th>
																	<th data-hide="phone">User</th>
																</tr>
															</thead>
															<tbody>
																<?php for ($i=1; $i <= $load_list_count ; $i++) {

																	# BOL File
																	$loader_list_bol_file = DB::getInstance()->query("SELECT * FROM loader_file WHERE load_id = " . $load_list_load_id[$i] . ' && file_type = 1');
																	$loader_list_bol_file_count = $loader_list_bol_file->count();

																	# Check if this load belongs to a multiple load entry
																	$loader_entry_multile_load = DB::getInstance()->query("SELECT entry_id, COUNT(entry_id) AS count_entry_id FROM loader_load WHERE entry_id = " . $load_list_entry_id[$i] . " GROUP BY entry_id");
																	foreach ($loader_entry_multile_load->results() as $loader_entry_multile_load_data) {
																	 	if ($loader_entry_multile_load_data->count_entry_id > 1) {
																	 		# Multiple or single entry
																	 		$entry_type = 'Multiple';
																	 	} else {
																	 		$entry_type = 'Single';
																	 	}
																	} ?>
																	<tr<?= !$load_list_billing_status[$i] && $loader_list_bol_file_count ? ' style="background-color: #b00b00"' : ($load_list_billing_status[$i] == 1 ? ' class="purple-bg"' : ($load_list_billing_status[$i] == 2 ? ' class="yellow-bg"' : ($load_list_billing_status[$i] == 3 ? ' class="green-bg"' : ''))) ?>>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?> style="font-size: 0.875em; font-weight: normal;">
																			<span data-toggle="tooltip" data-placement="top" title="<?= $broker_id_company_name[$load_list_broker_id[$i]] ?>"><?= substr($broker_id_company_name[$load_list_broker_id[$i]], 0, 25) ?></span>
																		</td>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>><?= $load_list_load_number[$i] ?></td>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																			<span data-toggle="tooltip" data-placement="top" title="<?= $_QU_e_phone_number_01[$load_list_driver_id[$i]] ?>"><?= $_QU_e_name[$client_driver_user_id[$load_list_driver_id[$i]]] . ' ' . $_QU_e_last_name[$client_driver_user_id[$load_list_driver_id[$i]]] ?></span>
																		</td>
																		<td class="text-right"<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>$ <?= number_format($load_list_line_haul[$i], 2) ?> / <span data-toggle="tooltip" data-toggle="top" title="$ <?= (number_format($load_list_line_haul[$i] / $load_list_miles[$i], 2)) ?> per mile"><?= number_format($load_list_miles[$i], 0) ?></span></td>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																			<?php # Get first pickup data if $load_list_load_id[$i] is set
																			if (isset($load_list_load_id[$i])) {
																			
																				$load_list_first_pick_up = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[$i] . " && data_type = 0 ORDER BY date_time LIMIT 1");
																				$load_list_first_pick_up_count = $load_list_first_pick_up->count();
																				if ($load_list_first_pick_up_count) {
																					
																					foreach ($load_list_first_pick_up->results() as $load_list_first_pick_up_data) {
																						$load_list_first_pick_up_checkpoint_id = $load_list_first_pick_up_data->checkpoint_id;
																						$load_list_first_pick_up_driver_id = $load_list_first_pick_up_data->driver_id;
																						$load_list_first_pick_up_date = date('m/d/y', strtotime($load_list_first_pick_up_data->date_time));
																						$load_list_first_pick_up_time = date('G:i', strtotime($load_list_first_pick_up_data->date_time));
																						$load_list_first_pick_up_city = ucfirst(strtolower(html_entity_decode($load_list_first_pick_up_data->city)));
																						$load_list_first_pick_up_state_id = $load_list_first_pick_up_data->state_id;
																						$load_list_first_pick_up_zip_code = $load_list_first_pick_up_data->zip_code;
																						$load_list_first_pick_up_status = $load_list_first_pick_up_data->status;

																						if ($load_list_first_pick_up_city || $load_list_first_pick_up_state_id) {
																							$pick_up_date = '<span data-toggle="tooltip" data-placement="top" title="' . $load_list_first_pick_up_zip_code . '">' . $load_list_first_pick_up_city . ', ' . $state_abbr[$load_list_first_pick_up_state_id] . ' </span>';
																						}

																						if ($load_list_first_pick_up_date) {
																							echo '<span class="pull-right" ' . ($load_list_first_pick_up_time ? 'data-toggle="tooltip" data-placement="top" title="' . $load_list_first_pick_up_time . '"' : '') . '>' . $load_list_first_pick_up_date . '</span>';
																						} 
																					}
																				}
																			} ?>
																		</td>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																			<?php # Get last pickup data if $load_list_load_id[$i] is set
																			if (isset($load_list_load_id[$i])) {
																				
																				$load_list_last_drop_off = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[$i] . " && data_type = 1 ORDER BY date_time DESC LIMIT 1");
																				$load_list_last_drop_off_count = $load_list_last_drop_off->count();
																				if ($load_list_last_drop_off->count()) {
																					
																					foreach ($load_list_last_drop_off->results() as $load_list_last_drop_off_data) {
																						$load_list_last_drop_off_checkpoint_id = $load_list_last_drop_off_data->checkpoint_id;
																						$load_list_last_drop_off_driver_id = $load_list_last_drop_off_data->driver_id;
																						$load_list_last_drop_off_date = date('m/d/y', strtotime($load_list_last_drop_off_data->date_time));
																						$load_list_last_drop_off_time = date('G:i', strtotime($load_list_last_drop_off_data->date_time));
																						$load_list_last_drop_off_city = ucfirst(strtolower(html_entity_decode($load_list_last_drop_off_data->city)));
																						$load_list_last_drop_off_state_id = $load_list_last_drop_off_data->state_id;
																						$load_list_last_drop_off_zip_code = $load_list_last_drop_off_data->zip_code;
																						$load_list_last_drop_off_status = $load_list_last_drop_off_data->status;

																						if ($load_list_last_drop_off_city || $load_list_last_drop_off_state_id) {
																							$drop_date = '<span data-toggle="tooltip" data-placement="top" title="' . $load_list_last_drop_off_zip_code . '">' . $load_list_last_drop_off_city . ', ' . $state_abbr[$load_list_last_drop_off_state_id] . ' </span>';
																						}

																						if ($load_list_last_drop_off_date) {
																							echo '<span class="pull-right" ' . ($load_list_last_drop_off_time ? 'data-toggle="tooltip" data-placement="top" title="' . $load_list_last_drop_off_time . '"' : '') . '> ' . $load_list_last_drop_off_date . '</span>';
																						}
																					}
																				}
																			} ?>
																		</td>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																			<?= $pick_up_date ?>
																		</td>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																			<?= $drop_date ?>
																		</td>
																		<td<?= ($load_list_billing_status[$i] != 0 || !$load_list_billing_status[$i] && $loader_list_bol_file_count) ? ' style="color:#fff;"' : '' ?>>
																			<?= $user_i_name[$load_list_user_id[$i]] . ' ' . substr($user_i_last_name[$load_list_user_id[$i]], 0, 1) ?> 
																			<span id="btn-<?= $i ?>" class="pull-right label label-<?= $entry_type == 'Multiple' ? 'primary' : 'default' ?> label-large"><a href="loader?id=<?= $load_list_entry_id[$i] ?>" style="color:#fff;"><span data-toggle="tooltip" data-placement="top" title="<?= $entry_type == 'Multiple' ? 'Multiple entry' : 'Single entry' ?>" class="fa fa-cube<?= $entry_type == 'Multiple' ? 's' : '' ?>"></span></a></span>
																			<?php if ($load_list_entry_id[$i] > $load_list_load_id[$i]) { ?>
																			 	<span class="pull-right label label-danger label-large" style="margin-right: 4px;"><a href="#" style="color:#fff;"><span data-toggle="tooltip" data-placement="top" title="Link unavailable, use entry link." class="fa fa-warning"></span></a></span> <?php 
																			} else { ?>
																				<span class="pull-right label label-primary label-large" style="margin-right: 4px;"><a href="view-load?load_id=<?= $load_list_load_id[$i] ?>" style="color:#fff;"><span data-toggle="tooltip" data-placement="top" title="View load" class="fa fa-eye"></span></a></span> <?php
																			} ?>
																		</td>
																	</tr><?php
																} ?>
															</tbody>
														</table><?php
													} else { ?>
														<div class="alert alert-info">
                                <i class="fa fa-info-circle fa-fw fa-lg"></i>
                                There are no entries here yet!
                            </div><?php
													} ?>
												</div>
												<div class="tab-pane<?= $pushTab == 'tab_config' ? ' in active' : '' ?>" id="tab_config">
                          <div class="row">
													  <div class="col-sm-12 col-md-3">
													    <table id="dealType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>Other Charges</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_other_charge" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_other_charges = DB::getInstance()->query("SELECT * FROM loader_other_charges");
													        foreach($loader_other_charges->results() as $loader_other_charges_data) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_other_charges_data->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_other_charge=<?= $loader_other_charges_data->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_other_charge" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_other_charges_data->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table>
													  </div>
													  <div class="col-sm-12 col-md-3">
													    <table id="trailerType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>Trailer Types</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_trailer_type" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_trailer_types = DB::getInstance()->query("SELECT * FROM loader_trailer_types");
													        foreach($loader_trailer_types->results() as $loader_trailer_types_data) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_trailer_types_data->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_trailer_type=<?= $loader_trailer_types_data->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_trailer_type" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_trailer_types_data->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table> 
													  </div>
													  <div class="col-sm-12 col-md-3">
													    <table id="trailerType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>Trailer deck materials</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_trailer_deck_material" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_trailer_deck_material = DB::getInstance()->query("SELECT * FROM loader_trailer_deck_material");
													        foreach($loader_trailer_deck_material->results() as $loader_trailer_deck_material_data) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_trailer_deck_material_data->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_trailer_deck_material=<?= $loader_trailer_deck_material_data->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_trailer_deck_material" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_trailer_deck_material_data->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table> 
													  </div>
													  <div class="col-sm-12 col-md-3">
													    <table id="trailerType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>Payment methods</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="method" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_payment_method" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_payment_method = DB::getInstance()->query("SELECT * FROM loader_quickpay_method_of_payment");
													        foreach($loader_payment_method->results() as $loader_payment_method) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="method" class="form-control" type="text" value="<?= $loader_payment_method->method ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_payment_method=<?= $loader_payment_method->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_payment_method" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_payment_method->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table> 
													  </div>
													</div>
													<div class="row">
													  <div class="col-sm-12 col-md-3">
													    <table id="dealType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													            <th>Driver Equipment</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_driver_equipment" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_driver_equipment = DB::getInstance()->query("SELECT * FROM loader_driver_equipment");
													        foreach($loader_driver_equipment->results() as $loader_driver_equipment_data) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_driver_equipment_data->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_driver_equipment=<?= $loader_driver_equipment_data->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_driver_equipment" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_driver_equipment_data->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table>
													  </div>
													  <div class="col-sm-12 col-md-3">
													    <table id="dealType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													            <th>Driver Features</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_driver_features" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_driver_features = DB::getInstance()->query("SELECT * FROM loader_driver_features");
													        foreach($loader_driver_features->results() as $loader_driver_features_data) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_driver_features_data->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_driver_features=<?= $loader_driver_features_data->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_driver_features" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_driver_features_data->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table>
													  </div>
													  <div class="col-sm-12 col-md-3">
													    <table id="trailerType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>Trailer door types</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_trailer_door_type" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_trailer_door_type = DB::getInstance()->query("SELECT * FROM loader_trailer_door_type");
													        foreach($loader_trailer_door_type->results() as $loader_trailer_door_type) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_trailer_door_type->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_trailer_door_type=<?= $loader_trailer_door_type->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_trailer_door_type" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_trailer_door_type->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table>
													  </div>
													  <div class="col-sm-12 col-md-3">
													    <table id="trailerType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>Trailer roof types</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item">
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_trailer_roof_type" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_trailer_roof_type = DB::getInstance()->query("SELECT * FROM loader_trailer_roof_type");
													        foreach($loader_trailer_roof_type->results() as $loader_trailer_roof_type) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_trailer_roof_type->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_trailer_roof_type=<?= $loader_trailer_roof_type->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_trailer_roof_type" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_trailer_roof_type->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table> 
													  </div>
													</div>
													<div class="row">
														<div class="col-sm-12 col-md-3">
													    <table id="dealType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>File labels</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group">
													                <span class="input-group-btn">
													                  <button class="btn btn-warning" type="submit" disabled><span class="fa fa-save"></span></button>
													                </span>
													                <input name="name" class="form-control" type="text" placeholder="New item" disabled>
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_add_loader_file_label" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        <?php $loader_file_label = DB::getInstance()->query("SELECT * FROM loader_file_label");
													        foreach($loader_file_label->results() as $loader_file_label_data) { ?>
													          <form action="" method="post">
													            <tr>
													              <td>
													                <div class="input-group">
													                  <span class="input-group-btn">
													                    <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                  </span>
													                  <input name="name" class="form-control" type="text" value="<?= $loader_file_label_data->name ?>">
													                </div>
													              </td>
													              <td>
													                <a href="?_hp_delete_loader_file_label=<?= $loader_file_label_data->data_id ?>" style="color:#e84e40;font-size:16px;"><span class="fa fa-trash-o"></span></a>
													              </td>
													            </tr>
													            <input type="hidden" name="_hp_update_loader_file_label" value="1">
													            <input type="hidden" name="data_id" value="<?= $loader_file_label_data->data_id ?>">
													            <input type="hidden" name="token" value="<?= $csrfToken ?>">
													          </form><?php
													        } ?>
													      </tbody>
													    </table>
													  </div>
													  <div class="col-sm-12 col-md-3">
													    <table id="dealType" class="table table-bordered table-striped">
													      <thead>
													        <tr>
													          <th>Linked checkpoint max time span (hours)</th>
													        </tr>
													      </thead>
													      <tbody>
													        <form action="" method="post">
													          <tr>
													            <td>
													              <div class="input-group input-append bootstrap-timepicker">
													                <span class="input-group-btn">
													                  <button class="btn btn-primary" type="submit"><span class="fa fa-save"></span></button>
													                </span>
													                <input name="linked_checkpoint_max_time_span" type="number" step="1" min="1" class="form-control" id="timepicker<?= $i ?>" value="<?= $linked_checkpoint_max_time_span ?>">
													                <span class="add-on input-group-addon"><i class="fa fa-clock-o"></i></span>
													              </div>
													            </td>
													          </tr>
													          <input type="hidden" name="_hp_update_loader_config" value="1">
													          <input type="hidden" name="loader_status_change_notification_subject" value="<?= $loader_status_change_notification_subject ?>">
													          <input type="hidden" name="loader_status_change_notification_template" value='<?= $loader_status_change_notification_template ?>'>
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													      </tbody>
													    </table>
													  </div>
													</div>
													<div class="row">
													  <div class="col-sm-12 col-md-12">
													    <div class="panel panel-primary">
													      <div class="panel-heading">Status change notification template</div>
													      <div class="panel-body">
													        <form action="" method="post">
													          <div class="form-group col-sm-12 col-md-12">
													            <label>Subject</label>
													            <input name="loader_status_change_notification_subject" class="form-control" value="<?= $loader_status_change_notification_subject ?>">
													          </div>
													          <div class="form-group col-sm-12 col-md-12">
													            <label>Body</label>
													            <textarea id="loader_status_change_notification_template" name="loader_status_change_notification_template" rows="10" cols="80"><?= $loader_status_change_notification_template ?></textarea>
													          </div>
													          <div class="col-sm-12 col-md-12 text-right">
													            <button type="submit" class="btn btn-primary">Save</button>
													          </div>
													          <input type="hidden" name="_hp_update_loader_config" value="1">
													          <input type="hidden" name="linked_checkpoint_max_time_span" value="<?= $linked_checkpoint_max_time_span ?>">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													        </div>
													      <!--<div class="panel-footer">Panel footer</div>-->
													    </div>
													  </div>

													  <div class="col-sm-12 col-md-12" id="schedule_template">
													    <div class="panel panel-primary">
													      <div class="panel-heading">Schedule email template</div>
													      <div class="panel-body">
													        <form action="" method="post">
													          <div class="form-group col-sm-12 col-md-12">
													            <label>Subject</label>
													            <input name="schedule_email_subject" class="form-control" value="<?= $schedule_email_subject ?>">
													          </div>
													          <div class="form-group col-sm-12 col-md-12">
													            <label>Body</label>
													            <textarea id="schedule_email_body" name="schedule_email_body" rows="10" cols="80"><?= $schedule_email_body ?></textarea>
													          </div>
													          <div class="col-sm-12 col-md-12 text-right">
													            <button type="submit" class="btn btn-primary">Save</button>
													          </div>
													          <input type="hidden" name="_hp_update_schedule_template" value="1">
													          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													        </form>
													      </div>
													    </div>
													  </div>
													</div>
	                      </div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> 
					<?php # URI: /0/loader?id=n&send_load_info=n
					elseif($_GET['send_load_info']): ?>
						<div class="row">
							<div class="col-lg-12">
	              
								<ol class="breadcrumb">
									<li><a href="<?= $_SESSION['href_location'] ?>dashboard/"><?= $_QC_language[23] ?></a></li><?php // Show dashboard ?>
									<li><a href="<?= $_SESSION['href_location'] ?>dashboard/loader/">Loader</a></li>
									<li><a href="loader?id=<?= $_GET['id'] ?>">Entry #<?= $_GET['id'] ?></a></li>
									<li><a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['send_load_info'] ?>">Load #<?= $_GET['send_load_info'] ?></a></li>
								</ol>

								<div class="clearfix">
									<h1 class="pull-left">Send load info</h1>
									<div class="pull-right top-page-ui">
										<a style="margin:0 5px;" href="<?= $_SERVER['HTTP_REFERER'] ?>" class="btn btn-danger"><i class="fa fa-sign-out"></i> Back</a>
										<?php include($_SESSION['ProjectPath']."/includes/module-refresh-link.php"); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 col-md-4">
								<div class="main-box no-header clearfix">
									<div class="main-box-body clearfix">
										<form action="" method="post">
										  <div class="form-group input-group">
							          <span class="input-group-addon">To</span>
							          <input type="text" name="to" class="form-control" value="<?= $temp_driver_email ?>">
								      </div>
										  <div class="form-group input-group">
							          <span class="input-group-addon">Cc</span>
							          <input type="text" name="cc" class="form-control" value="<?= $user->data()->email ?>">
								      </div>
										  <div class="form-group input-group">
							          <span class="input-group-addon">Bcc</span>
							          <input type="text" name="bcc" class="form-control">
								      </div>
										  <div class="form-group">
							          <button type="submit" class="btn btn-block btn-primary">Send</button>
							          <a style="margin: 10px 0;" class="pull-right" href="<?= $_SERVER['HTTP_REFERER'] ?>">Go back</a>
								      </div>
										  <textarea name="pick_up_info" class="hide">
									      <?php 
							          // $pickUps = DB::getInstance()->query("SELECT * FROM loader_pick_up_drop_off WHERE data_type = 1 && load_id = $load_id ORDER BY data_order ASC");
							          for ($i = 1; $i <= $loader_checkpoint_pick_up_count; $i++) {
						              if ($loader_checkpoint_pick_up_count > 1 && $i > 1) {
						                  // Display line separator only if we have more than 1 address and if we are on the second address+ ?>
						                  <tr><!-- line -->
						                      <td width="100%" height="1" bgcolor="#d9d9d9"></td>
						                  </tr><?php
						              } ?>
						              <tr><!-- data -->
						                  <td width="100%" style=" font-size: 14px; line-height: 12px; font-family:helvetica, Arial, sans-serif; text-align: left; color:#000; padding: 0 15px;">
						                      <?= $loader_checkpoint_pick_up_count > 1 ? '<h3>Address '.$i.'</h3>' : '' ?>
						                      <p><?= $checkpoint_pick_up_line_1[$i] ?><br>
						                      <?= $checkpoint_pick_up_line_2[$i] ?><br>
						                      <?= $checkpoint_pick_up_city[$i] ?>, <?= $state_abbr[$checkpoint_pick_up_state_id[$i]] ?> <?= $checkpoint_pick_up_zip_code[$i] ?></p>
						                      <p>Date: <?= $checkpoint_pick_up_date[$i] ?></p>
						                      <p><?= $checkpoint_pick_up_contact[$i] ? 'Contact: '. $checkpoint_pick_up_contact[$i] .'<br>' : '' ?>
						                      <?= $checkpoint_pick_up_appointment[$i] ? 'Appointment: '. $checkpoint_pick_up_appointment[$i] .'<br>' : '' ?>
						                      <?= $checkpoint_pick_up_notes[$i] ? 'Notes: ' . $checkpoint_pick_up_notes[$i] : '' ?></p>
						                  </td>
						              </tr><?php
							          } ?>
										  </textarea>
										  <textarea name="drop_off_info" class="hide">
									      <?php //foreach ($loader_checkpoint_drop_off_count->results() as $dropOff) {
									        for ($i = 1; $i <= $loader_checkpoint_drop_off_count ; $i++) { 
									          if ($loader_checkpoint_drop_off_count > 1 && $i > 1) {
									              // Display line separator only if we have more than 1 address and if we are on the second address+ ?>
									              <tr><!-- line -->
									                  <td width="100%" height="1" bgcolor="#d9d9d9"></td>
									              </tr><?php
									          } ?>
									          <tr><!-- data -->
								              <td width="100%" style=" font-size: 14px; line-height: 12px; font-family:helvetica, Arial, sans-serif; text-align: left; color:#000; padding: 0 15px;">
								                  <?= $loader_checkpoint_drop_off_count > 1 ? '<h3>Address '.$i.'</h3>' : '' ?>
								                  <p><?= $checkpoint_drop_off_line_1[$i] ?><br>
								                  <?= $checkpoint_drop_off_line_2[$i] ?><br>
								                  <?= $checkpoint_drop_off_city[$i] ?>, <?= $state_abbr[$checkpoint_drop_off_state_id[$i]] ?> <?= $checkpoint_drop_off_zip_code[$i] ?></p>
								                  <p>Date: <?= $checkpoint_drop_off_date[$i] ?></p>
								                  <p><?= $checkpoint_drop_off_contact[$i] ? 'Contact: '. $checkpoint_drop_off_contact[$i] .'<br>' : '' ?>
								                  <?= $checkpoint_drop_off_appointment[$i] ? 'Appointment: '. $checkpoint_drop_off_appointment[$i] .'<br>' : '' ?>
								                  <?= $checkpoint_drop_off_notes[$i] ? 'Notes: ' . $checkpoint_drop_off_notes[$i] : '' ?></p>
								              </td>
									          </tr><?php
									      } ?>
										  </textarea>
										  <input type="hidden" name="line_haul" value="<?= $load_data_line_haul[1] ?>">
										  <input type="hidden" name="miles" value="<?= $load_data_miles[1] ?>">
										  <input type="hidden" name="weight" value="<?= $load_data_weight[1] ?>">
										  <input type="hidden" name="deadhead" value="<?= $load_data_deadhead[1] ?>">
										  <input type="hidden" name="reference" value="<?= $load_data_reference[1] ?>">
										  <input type="hidden" name="driver_id" value="<?= $entry_driver_id ?>">
										  <input type="hidden" name="broker_id" value="<?= $load_data_broker_data_id[1] ?>">
										  <input type="hidden" name="commodity" value="<?= $load_data_commodity[1] ?>">
										  <input type="hidden" name="notes" value="<?= $load_data_notes[1] ?>">
										  <input type="hidden" name="_hp_send_loader_load" value="1">
										  <input type="hidden" name="token" value="<?= $csrfToken ?>">
										</form>
									</div>
								</div>
							</div>
							<div class="col-sm-12 col-md-8">
								<div class="main-box no-header clearfix">
									<div class="main-box-body clearfix">
											<h1 class="text-center"><?= $domain ?> <i class="fa fa-road"></i></h1>
											<div class="col-sm-12 col-md-6">
												<p><b><?= $_QU_e_name[$entry_driver_id] . ' ' . $_QU_e_last_name[$entry_driver_id] ?></b></p>
												<p><b><?= $limbo_client_company_name_by_data_id[$entry_client_id] ?></b></p>
												<p><b><?= $broker_id_company_name[$load_data_broker_data_id[1]] ?></b></p>
												<?= $load_data_commodity[1] ? '<p><b>Commodity: </b>' . $load_data_commodity[1] . '</p>' : '' ?>
												<?= $load_data_notes[1] ? '<p><b>Notes: </b>' . $load_data_notes[1] . '</p>' : '' ?>
											</div>
											<div class="col-sm-12 col-md-6">
												<p><b>Rate:</b> <?= '$' . $load_data_line_haul[1] ?></p>
												<p><b>Miles:</b> <?= $load_data_miles[1] ?></p>
												<p><b>Per loaded mile:</b> $<?= number_format(str_replace(',', '', $load_data_line_haul[1]) / $load_data_miles[1], 2) ?></p>
												<p><b>Weight:</b> <?= $load_data_weight[1] ?></p>
												<p><b>Deadhead:</b> <?= $load_data_deadhead[1] ?> miles</p>
												<p><b>Reference:</b> <?= $load_data_reference[1] ?></p>
											</div>
									</div>
								</div>
								<div class="main-box no-header clearfix">
									<div class="main-box-body clearfix">
										<h2 class="text-center">Pick up information</h2>
										<?php for ($i = 1; $i <= $loader_checkpoint_pick_up_count ; $i++) { 
											# Display pickups ?>
											<?= $loader_checkpoint_pick_up_count > 1 ? '<h3>Address ' . $i . '</h3>' : ''; ?>
											<p><?= $checkpoint_pick_up_line_1[$i] ?></p>
                      <?= $checkpoint_pick_up_line_2[$i] ? '<p>' . $checkpoint_pick_up_line_2[$i] . '</p>' : '' ?>
                      <p><?= $checkpoint_pick_up_city[$i] ?>, <?= $state_abbr[$checkpoint_pick_up_state_id[$i]] ?> <?= $checkpoint_pick_up_zip_code[$i] ?></p>
                      <p>Date: <?= $checkpoint_pick_up_date[$i] ?></p>
                      <?= $checkpoint_pick_up_contact[$i] ? '<p>Contact: '. $checkpoint_pick_up_contact[$i] .'</p>' : '' ?>
                      <?= $checkpoint_pick_up_appointment[$i] ? '<p>Appointment: '. $checkpoint_pick_up_appointment[$i] .'</p>' : '' ?>
                      <?= $checkpoint_pick_up_notes[$i] ? '<p>Notes: '. $checkpoint_pick_up_notes[$i] .'</p>' : '' ?> <?php
										} ?>
									</div>
								</div>
								<div class="main-box no-header clearfix">
									<div class="main-box-body clearfix">
										<h2 class="text-center">Destination information</h2>
										<?php for ($i = 1; $i <= $loader_checkpoint_drop_off_count ; $i++) { 
											# Display drops ?>
											<?= $loader_checkpoint_drop_off_count > 1 ? '<h3>Address ' . $i . '</h3>' : ''; ?>
											<p><?= $checkpoint_drop_off_line_1[$i] ?></p>
                      <?= $checkpoint_drop_off_line_2[$i] ? '<p>' . $checkpoint_drop_off_line_2[$i] . '</p>' : '' ?>
                      <p><?= $checkpoint_drop_off_city[$i] ?>, <?= $state_abbr[$checkpoint_drop_off_state_id[$i]] ?> <?= $checkpoint_drop_off_zip_code[$i] ?></p>
                      <p>Date: <?= $checkpoint_drop_off_date[$i] ?></p>
                      <?= $checkpoint_drop_off_contact[$i] ? '<p>Contact: '. $checkpoint_drop_off_contact[$i] .'</p>' : '' ?>
                      <?= $checkpoint_drop_off_appointment[$i] ? '<p>Appointment: '. $checkpoint_drop_off_appointment[$i] .'</p>' : '' ?>
                      <?= $checkpoint_drop_off_notes[$i] ? '<p>Notes: '. $checkpoint_drop_off_notes[$i] .'</p>' : '' ?> <?php
										} ?>
									</div>
								</div>
							</div>
						</div>
					<?php # URI: /0/loader?new=1
					elseif($_GET['new']): ?>
						<div class="row">
							<div class="col-lg-12">
	              <?php include($_SESSION['ProjectPath']."/includes/breadcrumbs-loader.php"); ?>
								<div class="clearfix">
									<h1 class="pull-left">Entry</h1>
									<div class="pull-right top-page-ui">
										<?php include($_SESSION['ProjectPath']."/includes/module-cancel-new-item-link.php");
										include($_SESSION['ProjectPath']."/includes/module-info-link.php");
										include($_SESSION['ProjectPath']."/includes/module-refresh-link.php"); ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12">
								<div class="main-box no-header clearfix">
									<div class="main-box-body clearfix">
										<form action="" method="<?= $_GET['driver_id'] ? 'post' : 'get' ?>" enctype="multipart/form-data">
											<?php # This form has been changed to post to GET first so we can load the list of equipment available
											# for this driver, this first GET form passes the driver_id to the URL ?>
											<div class="row">
												<div class="col-sm-12 col-md-3">
													<div class="form-group form-group-select2">
														<label class="control-label"><span class="red">* </span>Driver</label>
														<?php if ($_GET['driver_id']) {
															# Display text field with readonly driver name and last name ?>
															<input type="text" class="form-control" value="<?= $_QU_e_name[$_GET['driver_id']] . ' ' . $_QU_e_last_name[$_GET['driver_id']] ?>" readonly> <?php
														} else {
															# Display driver_id select if we are still on GET form ?>
															<select name="driver_id" style="width:100%" id="new_driver_selector" onchange="this.form.submit()">
																<option></option>
																<?php for ($i = 1; $i <= $user_e_profile_client_driver_count ; $i++) { ?>
																	<option value="<?= $client_driver_user_id_ctr[$i] ?>"<?= $client_driver_user_id_ctr[$i] == $_GET['driver_id'] ? ' selected' : '' ?>><?= $_QU_e_name[$client_driver_user_id_ctr[$i]] . ' ' . $_QU_e_last_name[$client_driver_user_id_ctr[$i]] ?> [<?= $limbo_client_company_name_by_data_id[$client_driver_client_id_ctr[$i]] ?>]</option> <?php
																} ?>
															</select> <?php
														} ?>
													</div>
												</div>
												<?php if ($_GET['driver_id']) { 
													# Display on POST form only ?>
													<div class="form-group form-group-select2 col-sm-12 col-md-3">
														<label class="control-label"><span class="red">* </span>Broker company</label>
														<select name="broker_id" style="width:100%" id="broker_selector">
															<option></option>
															<?php for ($i = 1; $i <= $broker_count ; $i++) { ?>
																<option value="<?= $broker_data_id[$i] ?>"<?= $broker_data_id[$i] == Input::get('broker_id') ? ' selected="selected"' : '' ; ?>><?= $broker_company_name[$i] ?></option> <?php
															} ?>
														</select>
													</div>

													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label"><span class="red">* </span>Broker's name &amp; number</label>
														<input name="broker_name_number" type="text" class="form-control" value="<?= Input::get('broker_name_number') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label"><span class="red">* </span>Broker's email</label>
														<input name="broker_email" type="text" class="form-control" value="<?= Input::get('broker_email') ?>">
													</div> <?php
												} ?>
											</div>
											<?php if ($_GET['driver_id']) { 
												# Display on POST form only ?>
												<div class="row">
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label"><span class="red">* </span>Line haul</label>
														<input name="line_haul" type="number" min="0" class="form-control" value="<?= Input::get('line_haul') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right"><span class="red">* </span>Weight</label>
														<input name="weight" type="number" class="form-control" value="<?= Input::get('weight') ?>" min="1">
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right"><span class="red">* </span>Miles</label>
														<input name="miles" type="number" class="form-control" value="<?= Input::get('miles') ?>" min="1" step="0.1">
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right">Deadhead</label>
														<input name="deadhead" type="number" class="form-control" value="<?= Input::get('deadhead') ?>" min="0" step="0.1">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right"><span class="red">* </span>Avg. diesel price</label>
														<input name="avg_diesel_price" type="number" class="form-control" value="<?= Input::get('avg_diesel_price') ?>" min="1" step="0.01">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right"><span class="red">* </span>Load #</label>
														<input name="load_number" type="text" class="form-control" value="<?= Input::get('load_number') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Reference</label>
														<input name="reference" type="text" class="form-control" value="<?= Input::get('reference') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Notes</label>
														<input name="notes" type="text" class="form-control" value="<?= Input::get('notes') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Commodity</label>
														<input name="commodity" type="text" class="form-control" value="<?= Input::get('commodity') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right"><span class="red">* </span>Dispatcher</label>
														<select class="form-control" name="user_id">
															<option></option>
															<?php for ($i=1; $i <= $dispatcher_count ; $i++) { 
																# Show dispatcher's list ?>
																<option value="<?= $dispatcher_user_id_ctr[$i] ?>"<?= $dispatcher_user_id_ctr[$i] == $user->data()->user_id ? ' selected' : '' ?>><?= $dispatcher_name_ctr[$i] . ' ' . $dispatcher_last_name_ctr[$i] ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Equipment</label>
														<select class="form-control" name="equipment[]" multiple>
															<?php for ($i=1; $i <= $loader_driver_equipment_assoc_count ; $i++) { 
																# Show driver equipment list ?>
																<option value="<?= $driver_equipment_assoc_equipment_id[$i] ?>"><?= $driver_equipment_name_did[$driver_equipment_assoc_equipment_id[$i]] . ' [' . $driver_equipment_assoc_quantity[$i] . ' unit' . ($driver_equipment_assoc_quantity[$i] > 1 ? 's' : '') . ']' ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right"><span class="red">* </span>Rate confirmation</label>
														<div class="input-group form-group">
	                            <input style="display:inline; margin-right: 5px;" type="file" name="file" accept="image/gif, image/jpeg, image/png, application/pdf" class="btn btn-default">
	                          </div>
	                        </div>
													<div class="form-group col-sm-12 col-md-12 text-right">
														<small class="red pull-left">* Required fields</small>
														<button type="submit" class="btn btn-primary">Add</button>
													</div>
												</div>
												<input type="hidden" name="added_by" value="<?= $user->data()->user_id ?>">
												<input type="hidden" name="_hp_add_loader_entry" value="1">
												<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
											} else {
												# Pass the new value to maintain the required "new" parameter ?>
												<input type="hidden" name="new" value="1"> <?php
											} ?>
										</form>
									</div>
								</div>
							</div>
						</div>
					<?php # URI: /0/loader?id=n
					elseif($_GET['id']): ?>
						<div class="row">
							<div class="col-lg-12">
	              <?php include($_SESSION['ProjectPath']."/includes/breadcrumbs-loader.php"); ?>
								<div class="clearfix">
									<h1 class="pull-left">
										<?php echo !$_GET['new_load'] && !$_GET['load_id'] ? '# ' . $_GET['id'] : '' ;
										echo $_GET['new_load'] ? 'Load' : '';
										echo $_GET['load_id'] && !$_GET['new_checkpoint'] && !$_GET['new_file'] ? '#' . $_GET['load_id'] : '';
										echo $_GET['load_id'] && $_GET['new_checkpoint'] && !$_GET['new_file'] ? 'Checkpoint' : '';
										echo $_GET['load_id'] && !$_GET['new_checkpoint'] && $_GET['new_file'] ? 'File' : ''; ?>
									</h1>
									<div class="pull-right top-page-ui">
									<a class="btn btn-primary" href="view-load?load_id=<?= $_GET['load_id'] ?>">Read only view</a>

										<?php include($_SESSION['ProjectPath']."/includes/module-info-link.php");
										!$_GET['new_load'] && !$_GET['load_id'] ? include($_SESSION['ProjectPath']."/includes/module-id-exit-link.php") : '' ;
										$_GET['new_load'] ? include($_SESSION['ProjectPath']."/includes/module-cancel-new-load.php") : '' ;
										
										if ($_GET['load_id'] && !$_GET['new_checkpoint'] && !$_GET['new_file']) { ?>
										 	<span data-toggle="tooltip" data-placement="top" title="<?= $loader_checkpoint_first_pick_up_count && $loader_checkpoint_last_drop_off_count ? 'Send load info' : 'Checkpoints missing, cannot send info' ?>" class="btn btn-<?= $loader_checkpoint_first_pick_up_count && $loader_checkpoint_last_drop_off_count ? 'primary' : 'warning' ?> label-large" style="margin-left: 5px;"><a <?= $loader_checkpoint_first_pick_up_count && $loader_checkpoint_last_drop_off_count ? '' .  $_SESSION['href_location'] . 'dashboard/loader/load?load_id=' . $_GET['load_id'] . '&send_load_info=1"' : '' ?> style="color:#fff;"><span class="fa fa-envelope"></span></a></span> <?php
										}

										$_GET['load_id'] && $_GET['new_checkpoint'] && !$_GET['new_file'] ? include($_SESSION['ProjectPath']."/includes/module-new-checkpoint-exit-link.php") : '' ;
										$_GET['load_id'] && !$_GET['new_checkpoint'] && $_GET['new_file'] ? include($_SESSION['ProjectPath']."/includes/module-new-checkpoint-exit-link.php") : '' ;
										include($_SESSION['ProjectPath']."/includes/module-refresh-link.php"); ?>
									</div>
								</div>
							</div>
						</div>
						<?php 
						### loading/offloading hour met alert ###
						# loader?id=n&load_id=n side #

						# Run only on unlocked active loads
						if ($load_data_load_lock[1] == 0 && $load_data_load_status[1] == 0) {
							
							# Run only if we have a checkpoint count
							if ($loader_checkpoint_count) {
								
								# Loop through all checkpoints
								for ($i = 1; $i <= $loader_checkpoint_count ; $i++) {

									# Incomplete checkpoints only
									if ($checkpoint_status[$i] == 0) {
										
										# Compare checkpoint date/time with current time
										if (new DateTime() > new DateTime(" . $checkpoint_date_time[$i] . ")) {

											# Display warning ?>
											<div class="alert alert-danger" role="alert">
											  Checkpoint <?= $i ?> :: <?= $checkpoint_data_type[$i] == 0 ? 'Picks up' : 'Drops off' ?> in <?= $checkpoint_city[$i] ?>, <?= $state_abbr[$checkpoint_state_id[$i]] ?> 
											  should be complete, double check and <a href="loader-status-change-notification?checkpoint_id=<?= $checkpoint_id[$i] ?>&id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&checkpoint_data_type=<?= $checkpoint_data_type[$i] ?>&checkpoint_city=<?= $checkpoint_city[$i] ?>&checkpoint_state_id=<?= $checkpoint_state_id[$i] ?>&broker_email=<?= $load_data_broker_email[1] ?>&client_id=<?= $entry_client_id ?>" class="alert-link">send status change notification</a>.
											</div> <?php
										}
									}
								}
							}
						}
						?>
						
						<div class="row">
							<?php if (!$_GET['new_load'] && !$_GET['new_checkpoint'] && !$_GET['new_file']) { ?>
								<div class="col-md-3 col-sm-6 col-xs-12">
									<div class="main-box small-graph-box emerald-bg" style="display: none;">
										<form action="" method="post">
											<h2>Driver</h2>
											<div class="form-group form-group-select2">
												<select name="driver_id" style="width:100%" id="driver_selector">
													<option></option>
													<?php for ($i = 1; $i <= $user_e_profile_client_driver_count ; $i++) { ?>
														<option value="<?= $client_driver_user_id_ctr[$i] ?>"<?= $entry_driver_id == $client_driver_user_id_ctr[$i] ? ' selected="selected"' : '' ; ?>><?= $_QU_e_name[$client_driver_user_id_ctr[$i]] . ' ' . $_QU_e_last_name[$client_driver_user_id_ctr[$i]] ?> [<?= $limbo_client_company_name_by_data_id[$client_driver_client_id_ctr[$i]] ?>]</option> <?php
													} ?>
												</select>
												<?= $load_data_load_lock[1] == 0 && $load_data_load_status[1] == 0 ? '<a ' .  $_SESSION['href_location'] . 'dashboard/loader/load?load_id=' . $_GET['load_id'] . '&update_driver_prompt=1" class="btn btn-link pull-right" style="color:#fff;">Update</a>' : '' ?>
											</div>
											<input type="hidden" name="_hp_update_loader_entry_driver" value="1">
											<input type="hidden" name="token" value="<?= $csrfToken ?>">								
										</form>
									</div>
								</div> <?php
							}

							if (!$_GET['new_checkpoint'] && !$_GET['new_file'] && !$_GET['load_id']) { ?>
								<div class="col-md-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box">
										<i class="fa fa-cubes <?= $loader_load_count == 0 ? 'red' : 'green' ?>-bg"></i>
										<span class="headline">Loads</span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												<?= $loader_load_count ?>
											</span>
										</span>
										<div class="form-group">
											<?= !$_GET['new_load'] ? '<a href="loader?id=' . $_GET['id'] . '&new_load=1" class="btn btn-link pull-right">Add</a>' : '<a href="loader?id=' . $_GET['id'] . '" class="btn btn-link pull-right red">Cancel new load</a>' ?>
										</div>
									</div>
								</div><?php 
							}

							if ($_GET['load_id'] && !$_GET['new_file']) {
								# Display checkpoints ?>
								<div class="col-md-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box" style="display: none;">
										<i class="fa fa-map-marker <?= $loader_checkpoint_count == 0 ? 'red' : 'green' ?>-bg"></i>
										<span class="headline"><?= $loader_checkpoint_count ? '<a href="#accordion">Checkpoints</a>' : 'Checkpoints' ?></span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												<?= $loader_checkpoint_count ?>
											</span>
										</span>
										<?php if ($load_data_load_lock[1] == 0 && $load_data_load_status[1] == 0) {
											# Show link only of load is not locked nor deleted?>
											<div class="form-group">
												<?= !$_GET['new_checkopint'] ? '<a ' .  $_SESSION['href_location'] . 'dashboard/loader/load?load_id=' . $_GET['load_id'] . '&update_checkpoint=1" class="btn btn-link pull-right">Add</a>' : '<a href="loader?id=' . $_GET['id'] . '&load_id=' . $_GET['load_id'] . '" class="btn btn-link pull-right red">Cancel new checkpoint</a>' ?>
											</div> <?php
										} else {
											# Needed to maintain looks ?>
											<a style="color:#fff;" href="#">-</a> <?php
										} ?>
									</div>
								</div> <?php
							}

							if ($_GET['load_id'] && !$_GET['new_load'] && !$_GET['new_checkpoint']) { ?>
								<div class="col-md-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box" style="display: none;">
										<i class="fa fa-file<?= $loader_file_count > 1 ? 's' : '' ?>-o <?= $loader_file_count == 0 ? 'red' : 'green' ?>-bg"></i>
										<span class="headline"><?= $loader_file_count ? '<a href="#accordion">Files</a>' : 'Files' ?></span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												<?= $loader_file_count ?>
											</span>
										</span>
										<div class="form-group">
											<?/*= !$_GET['new_file'] ? '<a href="loader?id=' . $_GET['id'] . '&load_id=' . $_GET['load_id'] . '&new_file=1" class="btn btn-link pull-right">Add</a>' : '<a href="loader?id=' . $_GET['id'] . '&load_id=' . $_GET['load_id'] . '" class="btn btn-link pull-right red">Cancel file upload</a>'*/ ?>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box">
										<i class="fa fa-bars <?= $load_data_billing_status[1] == 0 ? 'red' : ($load_data_billing_status[1] == 1 ? 'yellow' : 'green') ?>-bg"></i>
										<span class="headline">Billing Status</span>
										<span class="value">
											<span class="timer" data-from="120" data-to="2562" data-speed="1000" data-refresh-interval="50">
												<?php if ($load_data_billing_status[1] == 0) {
													echo "<small>Not billed</small>";
												} elseif ($load_data_billing_status[1] == 1) {
													echo "<span data-toggle='tooltip' data-placement='top' title='" . $load_data_billing_date[1] . "'>Billed</span>";
												} elseif ($load_data_billing_status[1] == 2) {
													echo "<small class='yellow'>Paid/open</small>";
												} elseif ($load_data_billing_status[1] == 3) {
													echo "<small>Paid</small>";
												} ?>
											</span>
										</span>
										<div class="form-group">
											<?php if (!$loader_rate_confirmation_file_count && !$loader_bol_file_count) { ?>
												<a href="#" class="btn btn-link pull-right">Rate confirmation &amp; BOL missing</a> <?php
											} elseif (!$loader_rate_confirmation_file_count && $loader_bol_file_count) { ?>
												<a href="#" class="btn btn-link pull-right">Rate confirmation missing</a> <?php												
											} elseif ($loader_rate_confirmation_file_count && !$loader_bol_file_count) { ?>
												<a href="#" class="btn btn-link pull-right">BOL missing</a> <?php
											} else {

												# display link if billing_status == 0 (not billed)
												if ($load_data_billing_status[1] == 0) {
													# This is the link that sends quickpay invoices
													// Make link clickable only if we have $client_broker_relation_count AND $client_broker_invoice_counter_count
													if ($client_broker_relation_count && $client_broker_invoice_counter_count) { ?>
													 	<a href="quickpay-invoicing?entry_id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&client_id=<?= $entry_client_id ?>&driver_manager_id=<?= $driver_manager_id ?>&broker_email=<?= $load_data_broker_email[1] ?>&broker_id=<?= $load_data_broker_data_id[1] ?>" class="btn btn-link pull-right">Send invoice</a> <?php
													} else { ?>
														<a data-toggle="tooltip" data-placement="left" title="This client doesn't have a broker set with quickpay" href="#" class="btn btn-link pull-right" style="text-decoration: line-through;">Send invoice</a> <?php
													}
												} # else, display button to set billing_status as paid
												elseif ($load_data_billing_status[1] == 1) {
													if ($_GET['mark_as_paid']) { ?>
													 	<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&_hp_update_loader_load=2" class="btn btn-link pull-right">Paid/open</a>
													 	<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&_hp_update_loader_load=3" class="btn btn-link pull-right">Paid/closed</a> <?php
													} else {?>
														<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&mark_as_paid=1" class="btn btn-link pull-right">Mark as paid</a> <?php
													}
												} # else, display button to set billing_status as paid/closed
												elseif ($load_data_billing_status[1] == 2) { ?>
													<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&_hp_update_loader_load=3" class="btn btn-link pull-right">Set as Paid/closed</a> <?php
												} # Allow users to go back to paid/open
												elseif ($load_data_billing_status[1] == 3) { ?>
													<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&_hp_update_loader_load=2" class="btn btn-link pull-right">set as Paid/open</a> <?php
												}
											} ?>
										</div>
									</div>
								</div> <?php
							} ?>
						</div>
						<?php # URI: /0/loader?id=n&new_load=1
						if ($_GET['new_load']) { ?>
							<div class="row">
								<div class="col-lg-12">
									<div class="panel panel-primary">
									  <div class="panel-heading">
									    <h2 class="panel-title">Load entry #<?= $_GET['id'] ?></h2>
									    <p>Add new load</p>
									  </div>
									  <div class="panel-body">
									    <form action="" method="post">
												<div class="row">
													<div class="form-group form-group-select2 col-sm-12 col-md-4">
														<label class="control-label"><span class="red">* </span>Broker company</label>
														<select name="broker_id" style="width:100%" id="broker_selector">
															<option></option>
															<?php for ($i = 1; $i <= $broker_count ; $i++) { ?>
																<option value="<?= $broker_data_id[$i] ?>"<?= $broker_data_id[$i] == Input::get('broker_id') ? ' selected="selected"' : '' ; ?>><?= $broker_company_name[$i] ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-4">
														<label class="control-label"><span class="red">* </span>Broker's name &amp; number</label>
														<input name="broker_name_number" type="text" class="form-control" value="<?= Input::get('broker_name_number') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-4">
														<label class="control-label"><span class="red">* </span>Broker's email</label>
														<input name="broker_email" type="text" class="form-control" value="<?= strtolower(Input::get('broker_email')) ?>">
													</div>
												</div>
												<div class="row">
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label"><span class="red">* </span>Line haul</label>
														<input name="line_haul" type="number" min="0" class="form-control" value="<?= Input::get('line_haul') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right"><span class="red">* </span>Weight</label>
														<input name="weight" type="number" class="form-control" value="<?= Input::get('weight') ?>" min="1">
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right"><span class="red">* </span>Miles</label>
														<input name="miles" type="number" class="form-control" value="<?= Input::get('miles') ?>" min="1" step="0.1">
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right">Deadhead</label>
														<input name="deadhead" type="number" class="form-control" value="<?= Input::get('deadhead') ?>" min="0" step="0.1">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right"><span class="red">* </span>Avg. diesel price</label>
														<input name="avg_diesel_price" type="number" class="form-control" value="<?= Input::get('avg_diesel_price') ?>" min="1" step="0.01">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right"><span class="red">* </span>Load #</label>
														<input name="load_number" type="text" class="form-control" value="<?= Input::get('load_number') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Reference</label>
														<input name="reference" type="text" class="form-control" value="<?= Input::get('reference') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Notes</label>
														<input name="notes" type="text" class="form-control" value="<?= Input::get('notes') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Commodity</label>
														<input name="commodity" type="text" class="form-control" value="<?= Input::get('commodity') ?>">
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right"><span class="red">* </span>Dispatcher</label>
														<select class="form-control" name="user_id">
															<option></option>
															<?php for ($i=1; $i <= $dispatcher_count ; $i++) { 
																# Show dispatcher's list ?>
																<option value="<?= $dispatcher_user_id_ctr[$i] ?>"<?= $dispatcher_user_id_ctr[$i] == $user->data()->user_id ? ' selected' : '' ?>><?= $dispatcher_name_ctr[$i] . ' ' . $dispatcher_last_name_ctr[$i] ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Equipment</label>
														<select class="form-control" name="equipment[]" multiple>
															<?php $equipment_assoc = DB::getInstance()->query("SELECT * FROM loader_driver_equipment_assoc WHERE driver_id = " . $entry_driver_id);
															$equipment_assoc_counter = 1;
															foreach ($equipment_assoc->results() as $equipment_assoc_data) { 
																# Show driver equipment list ?>
																<option value="<?= $equipment_assoc_data->equipment_id ?>"><?= $driver_equipment_name_did[$equipment_assoc_data->equipment_id] . ' [' . $equipment_assoc_data->quantity . ' unit' . ($equipment_assoc_data->quantity > 1 ? 's' : '') . ']' ?></option> <?php
																$equipment_assoc_counter++;
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-12 text-right">
														<small class="red pull-left">* Required fields</small>
														<button type="submit" class="btn btn-primary">Add</button>
													</div>
												</div>
												<input type="hidden" name="added_by" value="<?= $user->data()->user_id ?>">
												<input type="hidden" name="_hp_add_loader_load" value="1">
												<input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
									  </div>
									</div>
								</div>
							</div> <?php
						}

						# URI: /0/loader?id=n&load_id=n
						if ($_GET['load_id'] && !$_GET['new_checkpoint'] && !$_GET['new_file']) { ?>
							<div class="row">
								<div class="col-lg-12">

									<div class="alert alert-danger">
										<i class="fa fa-warning fa-fw fa-lg"></i>
										Everything is on the <a href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>">new load page</a>
									</div>
									
									<div class="panel panel-primary" style="display: none;">
									  <div class="panel-heading">
									    <h2 class="panel-title">Load entry #<?= $_GET['id'] ?> - Load id #<?= $_GET['load_id'] ?></h2>
									    <p>Added by <?= $_QU_i_name[$load_data_user_id[1]] . ' ' . $_QU_i_last_name[$load_data_user_id[1]] ?> on <?= $load_data_added[1] ?> at <?= $load_data_added_time[1] ?></p>
									  </div>
									  <div class="panel-body">
									    <form action="" method="post">
												<div class="row">
													<div class="form-group form-group-select2 col-sm-12 col-md-4">
														<label class="control-label">Broker company</label>
														<select name="broker_id" style="width:100%" id="broker_selector">
															<option></option>
															<?php for ($i = 1; $i <= $broker_count ; $i++) { ?>
																<option value="<?= $broker_data_id[$i] ?>"<?=  $broker_data_id[$i] == $load_data_broker_data_id[1] ? ' selected="selected"' : '' ; ?>><?= $broker_company_name[$i] ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-4">
														<label class="control-label">Broker's name &amp; number</label>
														<input name="broker_name_number" type="text" class="form-control" value="<?= $load_data_broker_name_number[1] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-4">
														<label class="control-label">Broker's email</label>
														<input name="broker_email" type="email" class="form-control" value="<?= strtolower($load_data_broker_email[1]) ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
												</div>
												<div class="row">
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label">Line haul</label>
														<input name="line_haul" type="number" class="form-control" min="0" step="0.01" value="<?= str_replace(',', '', $load_data_line_haul[1]) ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right">Weight</label>
														<input name="weight" type="number" class="form-control" min="1" value="<?= $load_data_weight[1] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right">Miles</label>
														<input name="miles" type="number" class="form-control" min="1" step="0.1" value="<?= str_replace(',', '', $load_data_miles[1]) ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-2">
														<label class="control-label text-right">Deadhead</label>
														<input name="deadhead" type="number" class="form-control" min="0" step="0.1" value="<?= str_replace(',', '', $load_data_deadhead[1]) ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Avg. diesel price</label>
														<input name="avg_diesel_price" type="number" class="form-control" min="1" step="0.01" value="<?= $load_data_avg_diesel_price[1] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Load #</label>
														<input name="load_number" type="text" class="form-control" value="<?= $load_data_load_number[1] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Reference</label>
														<input name="reference" type="text" class="form-control" value="<?= $load_data_reference[1] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Notes</label>
														<input name="notes" type="text" class="form-control" value="<?= $load_data_notes[1] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Commodity</label>
														<input name="commodity" type="text" class="form-control" value="<?= $load_data_commodity[1] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
													</div>
													<div class="form-group col-sm-12 col-md-3">
														<label class="control-label text-right">Dispatcher</label>
														<select class="form-control" name="user_id"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
															<option></option>
															<?php for ($i=1; $i <= $dispatcher_count ; $i++) { 
																# Show dispatcher's list ?>
																<option value="<?= $dispatcher_user_id_ctr[$i] ?>"<?= $load_data_user_id[1] == $dispatcher_user_id_ctr[$i] ? ' selected' : '' ?>><?= $dispatcher_name_ctr[$i] . ' ' . $dispatcher_last_name_ctr[$i] ?></option> <?php
															} ?>
														</select>
													</div>
													<div class="form-group col-sm-12 col-md-12 text-right">
														<?php if ($load_data_load_lock[1] == 0 && $load_data_load_status[1] == 0) {
														# Show buttons if load is not locked nor deleted ?>
															<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>&update_main_info=1" class="btn btn-primary">Update</a>
															<span data-toggle="tooltip" data-placement="top" title="Delete load" class="btn btn-danger" onclick="location.href='/dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>&delete_load=1'">
																<i class="fa fa-trash-o"></i>
															</span><?php
														} ?>
													</div>
												</div>
												<?php if ($load_data_load_lock[1] == 0 && $load_data_load_status[1] == 0) {
													# Show hidden params if load is not locked nor deleted ?>
													<input type="hidden" name="_hp_update_loader_load" value="1">
													<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
												} ?>
											</form>
									  </div>
									</div>
								</div>
							</div>
							<?php # Display other charges ?>
							<h3 style="display: none;" id="other_charges">Other charges</h3> <?php
							/*if ($load_other_charges_count) {
								for ($i = 1; $i <= $load_other_charges_count ; $i++) { ?>
								 	<form action="" method="post">
										<div class="form-group col-sm-12 col-md-8">
											<select name="item" class="form-control">
												<?php if ($loader_other_charges_count) { ?>
													<option value="">Please choose a charge type</option> <?php
													for ($int = 1; $int <= $loader_other_charges_count ; $int++) { ?>
														<option value="<?= $charge_name[$int] ?>"<?= $load_other_charges_item[$i] == $charge_name[$int] ? ' selected="selected"' : '' ?>><?= $charge_name[$int] ?></option> <?php
													}
												} ?>
											</select>
										</div>
										<div class="form-group col-sm-12 col-md-2">
											<input name="price" class="form-control" type="number" step="0.01" min="0.01" placeholder="Cost ($)" value="<?= $load_other_charges_price[$i] ?>">
										</div>
										<div class="form-group col-sm-12 col-md-1">
											<button type="submit" class="btn btn-primary">Update</button>
										</div>
										<div class="form-group col-sm-12 col-md-1">
											<a href="loader?_hp_delete_loader_load_other_charge=<?= $load_other_charges_data_id[$i] ?>&id=<?= $_GET[id] ?>&load_id=<?= $_GET[load_id] ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span></a>
										</div>
										<input type="hidden" name="data_id" value="<?= $load_other_charges_data_id[$i] ?>">
										<input type="hidden" name="_hp_update_loader_load_other_charge" value="1">
		                <input type="hidden" name="token" value="<?= $csrfToken ?>">
									</form> <?php
								}
							} else { ?>
								<div class="alert alert-warning">
									<i class="fa fa-warning fa-fw fa-lg"></i>
									<strong>Important!</strong> This load doesn't have other charges.
								</div> <?php 
							}*/ ?>

							<form action="" method="post" style="display: none;">
								<div class="form-group col-sm-12 col-md-8">
									<select name="item" class="form-control">
										<?php if ($loader_other_charges_count) { ?>
											<option value="">Please choose a charge type</option> <?php
											for ($i = 1; $i <= $loader_other_charges_count ; $i++) { ?>
												<option value="<?= $charge_name[$i] ?>"><?= $charge_name[$i] ?></option> <?php
											}
										} ?>
									</select>
								</div>
								<div class="form-group col-sm-12 col-md-2">
									<input name="price" class="form-control" type="number" step="0.01" placeholder="Cost ($)">
								</div>
								<div class="form-group col-sm-12 col-md-2">
									<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>&handle_other_charges=1" class="btn btn-primary">Add</a>
								</div>
								<input type="hidden" name="_hp_add_loader_load_other_charge" value="1">
                <input type="hidden" name="token" value="<?= $csrfToken ?>">
							</form> <?php

							# Display checkpoints 
							/*if ($loader_checkpoint_count) { ?>
								<h3>Checkpoints</h3>
								<div class="row">
									<div class="col-sm-12 col-md-12">
										<div class="main-box-body clearfix">
											<div class="panel-group accordion" id="accordion">
												<?php for ($i = 1; $i <= $loader_checkpoint_count ; $i++) { ?>
													<div class="panel panel-<?= $checkpoint_data_type[$i] == 0 ? 'default' : 'primary' ?>" id="checkpoint-block<?= $checkpoint_id[$i] ?>">
														<div class="panel-heading">
															<h4 class="panel-title">
																<a class="accordion-toggle<?= $checkpoint_data_type[$i] == 0 ? ($loader_checkpoint_assoc_count[$i] ? '' : ' yellow') : '' ; ?>"<?= $checkpoint_data_type[$i] == 0 ? ($loader_checkpoint_assoc_count[$i] ? '' : ' style="text-shadow: 1px 1px #000; font-weight:bold;"') : '' ; ?> data-toggle="collapse" data-parent="#accordion" href="#checkpoint<?= $checkpoint_id[$i] ?>">
																	<span class="fa fa-map-marker"></span> Checkpoint <?= $i ?> :: <?= $checkpoint_data_type[$i] == 0 ? 'Picks up' : 'Drops off' ?> in <?= $checkpoint_city[$i] ?>, <?= $state_abbr[$checkpoint_state_id[$i]] ?> <?= $checkpoint_data_type[$i] == 0 ? ($loader_checkpoint_assoc_count[$i] ? '' : '<span class="fa fa-warning"></span> Linked drop off(s) missing!') : '' ; ?>
																</a>
															</h4>
														</div>
														<div id="checkpoint<?= $checkpoint_id[$i] ?>" class="panel-collapse collapse<?= ($checkpoint_id[$i] == $_GET['checkpoint_id'] || $checkpoint_id[$i] == $_GET['delete_checkpoint_id']) ? ' in' : '' ?>">
															<div class="panel-body">
																<form action="" method="post">
																	<div class="row">
																		<div class="col-sm-12 col-md-4">
																			<label class="control-label">Address line 1</label>
																			<input name="line_1" type="text" class="form-control" value="<?= $checkpoint_line_1[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																			<label class="control-label">Address line 2</label>
																			<input name="line_2" type="text" class="form-control" value="<?= $checkpoint_line_2[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																			<label class="control-label">City</label>
																			<input name="city" type="text" class="form-control" value="<?= $checkpoint_city[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																			<div class="col-sm-12 col-md-8">
																				<label class="control-label">State</label>
																				<select name="state_id" style="width:100%" id="state_selector<?= $i ?>">
																					<option></option>
																					<?php for ($si = 1; $si <= $geo_state_count ; $si++) { ?>
																						<option value="<?= $si ?>"<?= $si == $checkpoint_state_id[$i] ? ' selected="selected"' : '' ?>><?= $state_abbr[$si] . ' [' . $state_name[$si] . ']' ?></option> <?php
																					} ?>
																				</select>
																			</div>
																			<div class="col-sm-12 col-md-4">
																				<label class="control-label">Zip code</label>
																				<input name="zip_code" type="text" class="form-control" value="<?= $checkpoint_zip_code[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																			</div>
																		</div>
																		<div class="col-sm-12 col-md-8">
																			<div class="col-sm-12 col-md-6">
																				<label class="control-label">Contact</label>
																				<input name="contact" type="text" class="form-control" value="<?= $checkpoint_contact[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																			</div>
																			<div class="col-sm-12 col-md-6">
																				<label class="control-label">Notes</label>
																				<input name="notes" type="text" class="form-control" value="<?= $checkpoint_notes[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																			</div>
																			<div class="col-sm-12 col-md-6">
																				<label class="control-label">Checkpoint type</label>
																				<select name="data_type" id="data_type" class="form-control"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																					<?= $loader_checkpoint_count > 1 ? '<option></option>' : '' ?>
																					<option value="0"<?= $checkpoint_data_type[$i] == 0 ? ' selected="selected"' : '' ?>>Pick up</option>
																					<?php if ($loader_checkpoint_count > 1) { ?>
																						<option value="1"<?= $checkpoint_data_type[$i] == 1 ? ' selected="selected"' : '' ?>>Drop off</option> <?php 
																					} ?>
																				</select>
																			</div>
																			<div class="col-sm-12 col-md-6">
																				<label class="control-label">Appointment</label>
																				<input name="appointment" type="text" class="form-control" value="<?= $checkpoint_appointment[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																			</div>
																			<div class="col-sm-12 col-md-6">
																				<label for="datepickerDate">Date</label>
																				<div class="input-group">
																					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																					<input name="date" type="text" class="form-control" id="datepickerDate<?= $i ?>" value="<?= $checkpoint_date[$i] ?>"<?= $load_data_load_lock[1] == 1 || $load_data_load_status[1] == 1 ? ' readonly' : '' ?>>
																				</div>
																				<span class="help-block">format mm-dd-yyyy</span>
																			</div>
																			<div class="form-group col-sm-12 col-md-6">
																				<label for="timepicker">Time</label>
																				<div class="input-group input-append bootstrap-timepicker">
																					<input name="time" type="text" class="form-control" id="timepicker<?= $i ?>" value="<?= $checkpoint_time[$i] ?>">
																					<span class="add-on input-group-addon"><i class="fa fa-clock-o"></i></span>
																				</div>
																			</div>
																		</div>
																		<div id="pickups_holder" class="col-sm-12 col-md-12 hidden">
																			<hr>
																			<div class="form-group">
																				<label>Pickups linked to this drop off</label>
																				<select multiple name="checkpoint[]" class="form-control">
																					<?php # Pickups
																					# Checkpoints
																					$loader_checkpoint_pickup = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . ' && data_type = 0 ORDER BY date_time ASC');
																					$loader_checkpoint_pickup_count = $loader_checkpoint_pickup->count();
																					if ($loader_checkpoint_pickup_count) {
																						$pickUpCounter = 1;
																						foreach ($loader_checkpoint_pickup->results() as $loader_checkpoint_pickup_data) {
																							$accordion_checkpoint_assoc = DB::getInstance()->query("SELECT * FROM loader_checkpoint_assoc WHERE checkpoint = $loader_checkpoint_pickup_data->checkpoint_id && checkpoint_id = " . $checkpoint_id[$i]);
																							$accordion_checkpoint_assoc_count = $accordion_checkpoint_assoc->count(); ?>
																							<option value="<?= $loader_checkpoint_pickup_data->checkpoint_id ?>/<?= date('Y-m-d G:i:s', strtotime($loader_checkpoint_pickup_data->date_time)) ?>"<?= $accordion_checkpoint_assoc_count ? ' selected="selected"' : '' ; ?>><?= 'Pick up in ' . html_entity_decode($loader_checkpoint_pickup_data->city) . ', ' . $state_name[$loader_checkpoint_pickup_data->state_id] . '. Date: ' . date('m-d-Y G:i a', strtotime($loader_checkpoint_pickup_data->date_time)) . ' Contact: ' . html_entity_decode($loader_checkpoint_pickup_data->contact) ?></option> <?php
																							$pickUpCounter++;
																						}				
																					} ?>
																				</select>
																			</div>
																		</div>
																		<?php if ($_GET['delete_checkpoint_id'] && $checkpoint_data_type[$i] == 0) { ?>
																			<div class="row">
																				<div class="col-sm-12 col-md-12" style="margin-top: 25px;">
																					<div class="alert alert-danger">
																						<i class="fa fa-warning fa-fw fa-lg"></i>
																						<strong>Warning!</strong> Linked drop offs will be deleted
																					</div>
																				</div>
																			</div> <?php
																		} ?>
																		<div class="form-group col-sm-12 col-md-12 text-right" style="margin-top:25px">
																			<?php if ($load_data_load_lock[1] == 0 && $load_data_load_status[1] == 0) {
																				
																				# Show buttons if not locked nor deleted
																				if (!$_GET['delete_checkpoint_id']) { ?>
																					<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>&checkpoint_data=1" class="btn btn-primary">Update</a>
																					<?php if ($checkpoint_status[$i] == 0) {
																						echo '<a href="/dashboard/loader/load?load_id=' . $_GET['load_id'] . '&checkpoint_data=1" class="btn btn-primary">Mark as complete</a>';
																						echo '<a href="/dashboard/loader/load?load_id=' . $_GET['load_id'] . '&checkpoint_data=1" class="btn btn-danger" style="margin-left: 5px;"><span class="fa fa-trash-o"></span></a>';
																					} else {
																						echo '<a href="/dashboard/loader/load?load_id=' . $_GET['load_id'] . '&checkpoint_data=1" class="btn btn-primary">Resend status change notification</a>';

																						# Display lock button if last checkpoint
																						echo $last_drop_off_checkpoint_id == $checkpoint_id[$i] ? '<a style="margin-left: 5px;" data-toggle="tooltip" data-placement="top" title="Lock load" href="loader?id=' . $_GET['id'] . '&load_id=' . $_GET['load_id'] . '&_hp_update_loader_load=1&lock=1" class="btn btn-primary"><span class="fa fa-lock"></span></a>' : '';
																						
																						echo '<a href="/dashboard/loader/load?load_id=' . $_GET['load_id'] . '&checkpoint_data=1" class="btn btn-danger" style="margin-left: 5px;"><span class="fa fa-trash-o"></span></a>';
																					}
																				} else { ?>
																					<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>" class="btn btn-default">Cancel</a>
																					<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&_hp_delete_loader_checkpoint=<?= $checkpoint_id[$i] ?>" class="btn btn-danger"><span class="fa fa-trash-o"></span> Confirm delete</a> <?php
																				}																				
																			} ?>
																		</div>
																	</div>
																	<input type="hidden" name="_hp_update_loader_checkpoint" value="1">
																	<input type="hidden" name="checkpoint_id" value="<?= $checkpoint_id[$i] ?>">
																	<input type="hidden" name="token" value="<?= $csrfToken ?>">
																</form>
															</div>
														</div>
													</div> <?php
												} ?>
											</div>
										</div>
									</div>
								</div> <?php
							}

							# Files
							if ($loader_file_count) { ?>
								<div class="row">
									<div class="col-sm-12 col-md-12">
										<div class="main-box-body clearfix">
											<div class="row"<?= !$_GET['file_id'] && !$_GET['delete_file_id'] ? ' id="file_display"' : '' ?>>
												<?php for ($i = 1; $i <= $loader_file_count ; $i++) {
													# Displays colored boxes for each file type ?>
													<div class="col-lg-3 col-sm-6 col-xs-12">
														<div class="main-box infographic-box colored <?= substr($file_name[$i], 0, 18) === 'rate-confirmation-' ? 'green' : (substr($file_name[$i], 0, 4) === 'bol-' ? 'purple' : (substr($file_name[$i], 0, 20) === 'payment-confirmation' ? 'emerald' : (substr($file_name[$i], 0, 7) === 'raw-bol' ? 'emerald' : 'grey'))) ?>-bg"<?= $_GET['file_id'] == $file_id[$i] ? ' style="-webkit-box-shadow: 0 8px 6px -6px black;-moz-box-shadow: 0 8px 6px -6px black;box-shadow: 0 8px 6px -6px black;"' : '' ; ?>>
															<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&file_id=<?= $file_id[$i] ?>#file_display"><i class="fa fa-file-<?= $file_extension[$i] == 'pdf' ? 'pdf' : 'image' ?>-o"></i></a>
															<a style="color:#fff;" href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&file_id=<?= $file_id[$i] ?>#file_display"><span class="headline"><?= $file_type[$i] != NULL ? $label_name[$file_type[$i]] : $file_name[$i] ?></span></a>
															<p class="text-right"><span class="fa fa-calendar"></span> <?= $file_added_date[$i] ?><br>
															<span class="fa fa-user"></span> <?= $user_i_name[$file_user_id[$i]] . ' ' . $user_i_last_name[$file_user_id[$i]] ?></p>
														</div>
													</div> <?php
												} ?>
											</div>
										</div>
									</div>
								</div> <?php
								# URI: /0/loader?id=n&load_id=n&file_id=n
								# URI: /0/loader?id=n&load_id=n&delete_file_id=n
								if ($_GET['file_id'] || $_GET['delete_file_id']) {
									# Display file data and options here ?>
									<div id="file_display" class="main-box clearfix">
										<header class="main-box-header clearfix">
										<h2 class="pull-left"><?= $file_type[$i] != NULL ? 'BOL' : $file_name[$i] ?></h2>
										<div class="icon-box pull-right">
											<a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>#file_display" class="btn pull-left"><!-- Close (X) button -->
											<i class="fa fa-close"></i>
											</a>
										</div>
										</header>
										<div class="main-box-body clearfix">
											<?php if (!$_GET['delete_file_id']) {
												# Display data update form on type 0 files (labeless files)
												if ($loader_file_id_type == NULL) { ?>
												 	<form action="" method="post" class="form-inline" role="form">
														<div class="form-group">
															<label class="sr-only">File name</label>
															<input name="file_name" type="text" value="<?= $loader_file_id_extensionless_name ?>" class="form-control" placeholder="File name">
														</div>
														<div class="form-group" id="file_type_holder">
						                  <label class="sr-only">Label</label>
							                <select name="file_type" id="file_type" class="form-control" style="display:inline; margin-right: 5px;">
							                  <option value="0">No label</option>
							                  <?php for ($i = 1; $i <= $loader_file_label_count ; $i++) { ?>
																	<option value="<?= $label_data_id[$i] ?>"><?= $label_name[$i] ?></option> <?php
																} ?>
							                </select>
							              </div>
														<button type="submit" class="btn btn-success">Update</button>
														<a class="btn btn-danger" href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&delete_file_id=<?= $_GET['file_id'] ?>#file_display"><span class="fa fa-trash-o"></span> Delete file</a>
														<input type="hidden" name="_hp_update_loader_file" value="1">
									          <?= $loader_bol_file_count ? '<input type="hidden" name="loader_bol_file" value="' . $bol_file_name . '">' : '' ; ?>
									          <input type="hidden" name="token" value="<?= $csrfToken ?>">
													</form> <?php
												} else {
													# SHOW FILE NAME OR LABEL ?>
													<p class="lead"><?= $label_name[$loader_file_id_type] ?> <a class="btn btn-danger pull-right" href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&delete_file_id=<?= $_GET['file_id'] ?>#file_display"><span class="fa fa-trash-o"></span> Delete file</a></p> <?php
												}
											} else {
												# Display file delete confirmation ?>
												<form action="" method="post" class="form-inline" role="form">
													<button type="submit" class="btn btn-danger"><span class="fa fa-trash-o"></span> Confirm delete?</button>
													<a class="btn btn-default" href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $_GET['load_id'] ?>&file_id=<?= $_GET['delete_file_id'] ?>#file_display">Cancel</a>
													<input type="hidden" name="file_name" value="<?= $loader_file_id_name ?>">
													<input type="hidden" name="_hp_delete_loader_file" value="1">
								          <input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form> <?php
											} ?>
											<?php # This 4 lines of code below need to be studied, these conditionals seem to be doing nothing and the message never displays ?>
											<?= $loader_bol_file_count && $loader_file_id_type != 1 ? '<span class="help-block hidden" id="file_type_helper" style="color:ffa000 ! important;"><i class="icon-warning-sign"></i> This load already has a BOL file, choosing this option will delete the current BOL file and replace it with this one.</span>' : '' ; ?>
											<?= $loader_rate_confirmation_file_count && $loader_file_id_type != 2 ? '<span class="help-block hidden" id="file_type_helper_rate_confirmation" style="color:ffa000 ! important;"><i class="icon-warning-sign"></i> This load already has a Rate confirmation file, choosing this option will delete the current Rate confirmation file and replace it with this one.</span>' : '' ; ?>
											<?= $loader_payment_confirmation_file_count && $loader_file_id_type != 3 ? '<span class="help-block hidden" id="file_type_helper_payment_confirmation" style="color:ffa000 ! important;"><i class="icon-warning-sign"></i> This load already has a payment confirmation file, choosing this option will delete the current payment confirmation file and replace it with this one.</span>' : '' ; ?>
											<?= $loader_raw_bol_file_count && $loader_file_id_type != 4 ? '<span class="help-block hidden" id="file_type_helper_raw_bol" style="color:ffa000 ! important;"><i class="icon-warning-sign"></i> This load already has a raw bol file, choosing this option will delete the current raw bol file and replace it with this one.</span>' : '' ; ?>
											<div class="text-right">
												<small>Added by <?= $user_i_name[$loader_file_id_user_id] . ' ' . $user_i_last_name[$loader_file_id_user_id] ?> on <?= $loader_file_id_added ?></small>
											</div>
											<!-- # Display File -->
											<?php if ($loader_file_id_extension == 'pdf') { ?>
												<object data="http://<?= $domain ?>/files/<?= $loader_file_id_name ?>?rad=<?= date('is') ?>" type="application/pdf" width="100%" height="1200px" style="margin-top: 20px;">
													<p>This browser is not be rendering this file correctly.
													You can <a href="http://<?= $domain ?>/files/<?= $loader_file_id_name ?>">click here to
													download the PDF file.</a></p>
												</object> <?php
											} else { ?>
												<img style="margin-top: 20px;" class="img-responsive img-thumbnail" src="http://<?= $domain ?>/files/<?= $loader_file_id_name ?>?r=<?= date('Gis') ?>"> <?php
											}  ?>
										</div>
									</div> <?php
								}
							}*/ ?>

							<div class="row" id="load-notes" style="display: none;">
								<div class="col-sm-12 col-md-8">
									<div class="main-box clearfix">
										<header class="main-box-header clearfix">
											<h2>Staff notes</h2>
										</header>
										
										<div class="main-box-body clearfix">
											<div class="conversation-wrapper">
												<div class="conversation-content">
													<div class="conversation-inner">
														
														<?php if ($load_note_count) {

															for ($i= 1; $i <= $load_note_count ; $i++) { 
																# Display notes ?>
																<div class="conversation-item item-<?= $user->data()->user_id == $load_note_user_id[$i] ? 'right' : 'left' ?> clearfix">
																	<div class="conversation-user">
																		<span class="fa fa-user"></span>
																	</div>
																	<div class="conversation-body"<?= $load_note_important[$i] == 1 && $load_note_type[$i] == 0 ? ' style="background-color: #e84e40 !important; color: #fff !important; "' : ($load_note_important[$i] == 0 && $load_note_type[$i] == 1 ? ' style="background-color: #8bc34a !important; color: #fff !important; "' : '') ?>>
																		<div class="name">
																			<?= $_QU_i_name[$load_note_user_id[$i]] . ' ' . $_QU_i_last_name[$load_note_user_id[$i]] ?> <?= $load_note_type[$i] == 1 ? '<small><i>[automated]</i></small>' : '' ?>
																		</div>
																		<div class="time hidden-xs"<?= $load_note_important[$i] == 1 || $load_note_type[$i] == 1 ? ' style="color: #fff !important;"' : '' ?>>
																			<?= $load_note_added[$i] ?>
																		</div>
																		<div class="text">
																			<?= $load_note_note[$i] ?>
																		</div>
																	</div>
																</div> <?php
															}
														} ?>
														
													</div>
												</div>
												<div class="conversation-new-message">
													<form action="" method="post">
														<div class="form-group">
															<textarea name="note" class="form-control" rows="2" placeholder="Enter your message..."></textarea>
														</div>
														<div class="form-group">
															<div class="checkbox-nice checkbox-inline">
																<input type="checkbox" id="important_note" name="important_note">
																<label for="important_note">
																	Mark as important
																</label>
															</div>
														</div>
														<div class="clearfix">
															<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $_GET['load_id'] ?>&note_handle=1" class="btn btn-primary">Add note</a>
														</div>
														<input type="hidden" name="_hp_add_loader_load_note" value="1">
                    				<input type="hidden" name="token" value="<?= $csrfToken ?>">
													</form>
												</div>
											</div> 
										</div>
									</div>
								</div>
							</div>

						<?php	# Invoice
						} 

						# URI: /0/loader?id=n&load_id=n&new_checkpoint=1
						elseif ($_GET['load_id'] && $_GET['new_checkpoint']) { ?>
							<div class="row">
								<div class="col-lg-12">
									<div class="panel panel-primary">
									  <div class="panel-heading">
									    <h2 class="panel-title">Load entry #<?= $_GET['id'] ?> - Load id #<?= $_GET['load_id'] ?></h2>
									    <p>Add new checkpoint</p>
									  </div>
									  <div class="panel-body">
									    <form action="" method="post">
												<div class="row">
													<div class="col-sm-12 col-md-4">
														<label class="control-label"><span class="red">* </span>Address line 1</label>
														<input name="line_1" type="text" class="form-control" value="<?= Input::get('line_1') ?>">
														<label class="control-label">Address line 2</label>
														<input name="line_2" type="text" class="form-control" value="<?= Input::get('line_2') ?>">
														<label class="control-label"><span class="red">* </span>City</label>
														<input name="city" type="text" class="form-control" value="<?= Input::get('city') ?>">
														<div class="col-sm-12 col-md-8">
															<label class="control-label"><span class="red">* </span>State</label>
															<select name="state_id" style="width:100%" id="state_selector">
																<option></option>
																<?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
																	<option value="<?= $i ?>"><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-4">
															<label class="control-label"><span class="red">* </span>Zip code</label>
															<input name="zip_code" type="text" class="form-control" value="<?= Input::get('zip_code') ?>">
														</div>
													</div>
													<div class="col-sm-12 col-md-8">
														<div class="col-sm-12 col-md-6">
															<label class="control-label">Contact</label>
															<input name="contact" type="text" class="form-control" value="<?= Input::get('contact') ?>">
														</div>
														<div class="col-sm-12 col-md-6">
															<label class="control-label">notes</label>
															<input name="notes" type="text" class="form-control" value="<?= Input::get('notes') ?>">
														</div>
														<div class="col-sm-12 col-md-6">
															<label class="control-label">Checkpoint type</label>
															<select name="data_type" id="data_type" class="form-control">
																<?= $loader_checkpoint_count > 0 ? '<option></option>' : '' ?>
																<option value="0"<?= Input::get('data_type') == 0 ? ' selected="selected"' : '' ?>>Pick up</option>
																<?php if ($loader_checkpoint_count > 0) { ?>
																	<option value="1"<?= Input::get('data_type') == 1 ? ' selected="selected"' : '' ?>>Drop off</option> <?php 
																} ?>
															</select>
														</div>
														<div class="col-sm-12 col-md-6">
															<label class="control-label">Appointment</label>
															<input name="appointment" type="text" class="form-control" value="<?= Input::get('appointment') ?>">
														</div>
														<div class="col-sm-12 col-md-6">
															<label for="datepickerDate"><span class="red">* </span>Date</label>
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input name="date" type="text" class="form-control" id="datepickerDate" value="<?= Input::get('date') ?>">
															</div>
															<span class="help-block">format mm-dd-yyyy</span>
														</div>
														<div class="form-group col-sm-12 col-md-6">
															<label for="timepicker"><span class="red">* </span>Time</label>
															<div class="input-group input-append bootstrap-timepicker">
																<input name="time" type="text" class="form-control" id="timepicker" value="<?= Input::get('time') ?>">
																<span class="add-on input-group-addon"><i class="fa fa-clock-o"></i></span>
															</div>
														</div>
													</div>
													<div id="pickups_holder" class="col-sm-12 col-md-12 hidden">
														<hr>
														<div class="form-group">
															<label><span class="red">* </span>Select pickups linked to this drop off</label>
															<select multiple name="checkpoint[]" class="form-control">
																<?php # Pickups
																# Checkpoints
																$loader_checkpoint_pickup = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . ' && data_type = 0 ORDER BY date_time ASC');
																$loader_checkpoint_pickup_count = $loader_checkpoint_pickup->count();
																if ($loader_checkpoint_pickup_count) {
																	$pickUpCounter = 1;
																	foreach ($loader_checkpoint_pickup->results() as $loader_checkpoint_pickup_data) { ?>
																		<option value="<?= $loader_checkpoint_pickup_data->checkpoint_id ?>/<?= date('Y-m-d G:i:s', strtotime($loader_checkpoint_pickup_data->date_time)) ?>"><?= 'Pick up in ' . html_entity_decode($loader_checkpoint_pickup_data->city) . ', ' . $state_name[$loader_checkpoint_pickup_data->state_id] . '. Date: ' . date('m-d-Y G:i a', strtotime($loader_checkpoint_pickup_data->date_time)) . ' Contact: ' . html_entity_decode($loader_checkpoint_pickup_data->contact) ?></option> <?php
																		$pickUpCounter++;
																	}				
																} ?>
															</select>
														</div>
													</div>
													<div class="form-group col-sm-12 col-md-12 text-right" style="margin-top: 25px;">
														<small class="red pull-left">* Required fields</small>
														<button type="submit" class="btn btn-primary">Add</button>
													</div>
												</div>
												<input type="hidden" name="_hp_add_loader_checkpoint" value="1">
                        <input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
									  </div>
									</div>
								</div>
							</div> <?php
						} # URI: /0/loader?id=n&load_id=n&new_file=1
						/*elseif ($_GET['load_id'] && $_GET['new_file']) { ?>
							<div class="row">
								<div class="col-lg-12">
									<div class="panel panel-primary">
									  <div class="panel-heading">
									    <h2 class="panel-title">Load entry #<?= $_GET['id'] ?> - Load id #<?= $_GET['load_id'] ?></h2>
									    <p>Add new file</p>
									  </div>
									  <div class="panel-body">
									    <form action="" method="post" enctype="multipart/form-data">
		                    <p><?= !$loader_file_count ? 'This load has no files, upload the first one!' : 'Add file' ?></p>
                        <div class="col-sm-12 col-md-4">
                          <div class="input-group form-group">
                              <input style="display:inline; margin-right: 5px;" type="file" name="file" accept="image/gif, image/jpeg, image/png, application/pdf" class="btn btn-default">
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                        	<div class="form-group"<?= $loader_bol_file_count ? ' id="file_type_holder"' : '' ; ?>>
                              <select name="file_type" id="file_type" class="form-control pull-left" style="display:inline; margin-right: 5px;">
                              	<option value="0">Choose a label</option>
																	<?php for ($i = 1; $i <= $loader_file_label_count ; $i++) { ?>
																		<option value="<?= $label_data_id[$i] ?>"><?= $label_name[$i] ?></option> <?php
																	} ?>
                              </select>
                              <?= $loader_bol_file_count ? '<span class="help-block hidden" id="file_type_helper" style="color:ffa000 ! important;"><i class="icon-warning-sign"></i> This load already has a BOL file, choosing this option will delete the current BOL file and replace it with this one.</span>' : '' ; ?>
															<?= $loader_rate_confirmation_file_count ? '<span class="help-block hidden" id="file_type_helper_rate_confirmation" style="color:ffa000 ! important;"><i class="icon-warning-sign"></i> This load already has a Rate confirmation file, choosing this option will delete the current Rate confirmation file and replace it with this one.</span>' : '' ; ?>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-4">
                        	<div class="input-group form-group">
                  					<button type="submit" class="btn btn-primary pull-right"><?= $_QC_language[107] ?></button>
                          </div>
                        </div>
		                    <input type="hidden" name="_hp_add_loader_file" value="1">
		                    <?php # These conditionals below send an input if file exists, so that they can be deleted if rewriting ?>
		                    <?= $loader_bol_file_count ? '<input type="hidden" name="loader_bol_file" value="' . $bol_file_name . '">' : '' ; ?>
		                    <?= $loader_rate_confirmation_file_count ? '<input type="hidden" name="loader_rate_confirmation_file" value="' . $rate_confirmation_file_name . '">' : '' ; ?>
		                    <?= $loader_payment_confirmation_file_count ? '<input type="hidden" name="loader_payment_confirmation_file" value="' . $payment_confirmation_file_name . '">' : '' ; ?>
		                    <?= $loader_raw_bol_file_count ? '<input type="hidden" name="loader_raw_bol_file" value="' . $raw_bol_file_name . '">' : '' ; ?>
		                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
			                </form>
									  </div>
									</div>
								</div>
							</div> <?php
						}*/

						#########
						# Loads #
						#########
						if ($loader_load_count && !$_GET['load_id']) { ?>
							<div class="row">
								<div class="col-lg-12">
									<div class="main-box clearfix">
										<header class="main-box-header clearfix">
											<h2>Loads</h2>
										</header>
										<div class="main-box-body clearfix table-responsive">
											<table class="table footable toggle-circle-filled" data-page-size="6" data-filter="#filter" data-filter-text-only="true">
												<thead>
													<tr>
														<th>Broker</th>
														<th>Line haul</th>
														<th data-hide="phone" class="text-right">Miles</th>
														<th data-hide="phone" class="text-right">Weight</th>
														<th data-hide="phone">-</th>
														<th data-hide="all" class="text-center">Deadhead</th>
														<th data-hide="all" class="text-right">Commodity</th>
														<th data-hide="all" class="text-right">Notes</th>
														<th data-hide="all" class="text-right">Avg. diesel price</th>
														<th data-hide="all" class="text-right">Load #</th>
														<th data-hide="all" class="text-right">Reference</th>
														<th data-hide="all" class="text-right">Broker's name &amp; number</th>
														<th data-hide="all" class="text-right">Broker's email</th>
														<th data-hide="all" class="text-right">Dispatcher</th>
														<th data-hide="all" class="text-right">Added</th>
													</tr>
												</thead>
												<tbody>
													<?php for ($i = 1; $i <= $loader_load_count ; $i++) { ?>
														<tr<?= $load_data_billing_status[$i] == 1 ? ' class="purple-bg"' : ($load_data_billing_status[$i] == 2 ? ' class="yellow-bg"' : ($load_data_billing_status[$i] == 3 ? ' class="green-bg"' : '')) ?>>
															<td<?= $load_data_billing_status[$i] != 0 ? ' style="color:#fff;"' : '' ?>>
																<?= $broker_id_company_name[$load_data_broker_data_id[$i]] ?>
															</td>
															<td<?= $load_data_billing_status[$i] != 0 ? ' style="color:#fff;"' : '' ?> class="text-right">
																<?= $load_data_line_haul[$i] ?>
															</td>
															<td<?= $load_data_billing_status[$i] != 0 ? ' style="color:#fff;"' : '' ?> class="text-right">
																<?= $load_data_miles[$i] ?>
															</td>
															<td<?= $load_data_billing_status[$i] != 0 ? ' style="color:#fff;"' : '' ?> class="text-right">
																<?= $load_data_weight[$i] ?>
															</td>
															<td class="text-right">
																<?php if (!$_GET['delete_load_id']) { ?>
																	<span class="pull-right label label-danger label-large" style="margin-left: 5px;"><a href="loader?id=<?= $_GET['id'] ?>&delete_load_id=<?= $load_data_load_id[$i] ?>" style="color:#fff;"><span class="fa fa-trash-o"></span></a></span> 
																	<span class="pull-right label label-primary label-large" style="margin-right: 4px;"><a href="loader?id=<?= $_GET['id'] ?>&load_id=<?= $load_data_load_id[$i] ?>" style="color:#fff;"><span class="fa fa-pencil"></span></a></span> <?php
																} else {
																	if ($load_data_load_id[$i] == $_GET['delete_load_id']) { ?>
																		<div class="pull-right">
																			<form action="" method="get">
																				<button type="submit" class="btn btn-danger"><span class="fa fa-trash-o"></span> Confirm delete</button>
																				<a class="btn btn-default" href="loader?id=<?= $_GET['id'] ?>">Cancel</a>
																				<input type="hidden" name="load_number" value="<?= $load_data_load_number[$i] ?>">
																				<input type="hidden" name="_hp_delete_loader_load" value="<?= $load_data_load_id[$i] ?>">
																				<input type="hidden" name="entry_id" value="<?= $_GET['id'] ?>">
																			</form>
																		</div> <?php
																	}
																} ?>
															</td>
															<td>
																<?= $load_data_deadhead[$i] ?>
															</td>
															<td>
																<?= $load_data_commodity[$i] ?>
															</td>
															<td>
																<?= $load_data_notes[$i] ?>
															</td>
															<td>
																<?= '$' . $load_data_avg_diesel_price[$i] ?>
															</td>
															<td>
																<?= $load_data_load_number[$i] ?>
															</td>
															<td>
																<?= $load_data_reference[$i] ?>
															</td>
															<td>
																<?= $load_data_broker_name_number[$i] ?>
															</td>
															<td>
																<a href="mailto:<?= strtolower($load_data_broker_email[$i]) ?>"><?= strtolower($load_data_broker_email[$i]) ?></a>
															</td>
															<td>
																<?= $_QU_i_name[$load_data_user_id[$i]] . ' ' . $_QU_i_last_name[$load_data_user_id[$i]] ?>
															</td>
															<td>
																<?= $load_data_added[$i] . ' <small>[' . $load_data_added_time[$i] . ']</small> ' . 'by ' . $_QU_i_name[$load_data_added_by[$i]] . ' ' . $_QU_i_last_name[$load_data_added_by[$i]] ?>
															</td>
														</tr>
													<?php } ?>
					 							</tbody>
											</table>
										</div>
									</div>
								</div>
							</div> <?php 
						}
					endif;
					include($_SESSION['ProjectPath']."/includes/footer.php") ?>
				</div>
			</div>
		</div>
	</div>
	<!-- global scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.nanoscroller.min.js"></script>
	<!-- this page specific scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/ckeditor/ckeditor.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/modernizr.custom.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/snap.svg-min.js"></script> <!-- For Corner Expand and Loading circle effect only -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/classie.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/notificationFx.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/select2.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/wizard.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.sort.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.paginate.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.filter.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/bootstrap-datepicker.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/bootstrap-timepicker.min.js"></script>
	<!-- theme scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>/js/scripts.js"></script>
	<script type="text/javascript">
		(function() {
			<?php // Notices
			$_QC_module_controller = DB::getInstance()->query("SELECT * FROM _QC_module_controller");
	        foreach ($_QC_module_controller->results() as $_QC_module_controller_data) {
				if (Session::exists($_QC_module_controller_data->controller)) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash($_QC_module_controller_data->controller) ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
				elseif (Session::exists($_QC_module_controller_data->controller.'_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash($_QC_module_controller_data->controller.'_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			}

			// Quickpay invoice flashes
			if (Session::exists('quickpay_invoice_sent')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('quickpay_invoice_sent') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('quickpay_invoice_sent_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('quickpay_invoice_sent_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }

			if (!$_GET) { ?>
				// CKEditor
				CKEDITOR.replace('loader_status_change_notification_template');
				CKEDITOR.replace('schedule_email_body');

				document.getElementById('flag_payment_missing').onchange = function() {
					if (document.getElementById('flag_payment_missing').selectedIndex != 0) {
						//console.log(document.getElementById('flag_payment_missing').value);
						document.getElementById('filter').value = document.getElementById('flag_payment_missing').value;
						document.getElementById("filter").focus();
					}
				}

				document.getElementById('status_change_notification_alert').onchange = function() {
					if (document.getElementById('status_change_notification_alert').selectedIndex != 0) {
						//console.log(document.getElementById('status_change_notification_alert').value);
						document.getElementById('filter').value = document.getElementById('status_change_notification_alert').value;
						document.getElementById("filter").focus();
					}
				} <?php
			} elseif ($_GET['new_checkpoint'] || $_GET['load_id']) { ?>
				document.getElementById('data_type').onchange = function(){
					if (document.getElementById('data_type').selectedIndex == 2) {
						$('#pickups_holder').removeClass("hidden");
					} else {
						$('#pickups_holder').addClass("hidden");
					}
				} <?php
			} elseif ($_GET['new_file']) { ?>
				document.getElementById('file_type').onchange = function(){
					if (document.getElementById('file_type').selectedIndex == 1) {
						$('#file_type_helper').removeClass("hidden");
						$('#file_type_helper_rate_confirmation').addClass("hidden");
					} else if (document.getElementById('file_type').selectedIndex == 2) {
						$('#file_type_helper').addClass("hidden");
						$('#file_type_helper_rate_confirmation').removeClass("hidden");
					} else if (document.getElementById('file_type').selectedIndex == 3) {
						$('#file_type_helper').addClass("hidden");
						$('#file_type_helper_rate_confirmation').addClass("hidden");
					} else {
						$('#file_type_helper').addClass("hidden");
						$('#file_type_helper_rate_confirmation').addClass("hidden");
					}
				}

				document.getElementById('file_type_holder').onchange = function(){
					if (document.getElementById('file_type').selectedIndex == 1) {
						$('#file_type_holder').addClass("has-warning");
					} else if (document.getElementById('file_type').selectedIndex == 2) {
						$('#file_type_holder').addClass("has-warning");
					} else {
						$('#file_type_holder').removeClass("has-warning");
					}
				} <?php
			} elseif ($_GET['file_id']) { ?>
				document.getElementById('file_type').onchange = function(){
					if (document.getElementById('file_type').selectedIndex == 1) {
						$('#file_type_helper').removeClass("hidden");
					} else {
						$('#file_type_helper').addClass("hidden");
					}
				}

				document.getElementById('file_type_holder').onchange = function(){
					if (document.getElementById('file_type').selectedIndex == 1) {
						$('#file_type_holder').addClass("has-warning");
					} else {
						$('#file_type_holder').removeClass("has-warning");
					}
				} <?php
			} ?>

			//nice select boxes
			<?php if ($_GET['new']) { ?>
				$('#new_driver_selector').select2(); <?php
			} ?>
			$('#broker_selector').select2();
			$('#broker_selector1').select2();
			$('#driver_selector').select2();
			$('#charge_selector').select2();
			$('#state_selector').select2();
			<?php for ($i = 1; $i <= $loader_checkpoint_count ; $i++) { ?>
				$('#state_selector<?= $i ?>').select2();
			<?php } ?>

			$('#myWizard').wizard();

			$('.footable').footable();

			//datepicker
			$('#datepickerDate').datepicker({
			  format: 'mm-dd-yyyy'
			});

			//timepicker
			$('#timepicker').timepicker({
				minuteStep: 5,
				showSeconds: false,
				showMeridian: false,
				disableFocus: false,
				showWidget: true
			}).focus(function() {
				$(this).next().trigger('click');
			});

			<?php if ($loader_checkpoint_count) {
				for ($i = 1; $i <= $loader_checkpoint_count ; $i++) { ?>
					//datepicker
					$('#datepickerDate<?= $i ?>').datepicker({
					  format: 'mm-dd-yyyy'
					});

					//timepicker
					$('#timepicker<?= $i ?>').timepicker({
						minuteStep: 5,
						showSeconds: false,
						showMeridian: false,
						disableFocus: false,
						showWidget: true
					}).focus(function() {
						$(this).next().trigger('click');
					}); <?php
				}
			} ?>
		})();
	</script>
</body>
</html>
