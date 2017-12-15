<?php

$days_this_month = date("t");

if ($_POST['date_range']) {

	$start_month = substr($_POST['date_range'], 0, 2);
	$start_day = substr($_POST['date_range'], 3, 2);
	$start_year = substr($_POST['date_range'], 6, 4);
	$end_month = substr($_POST['date_range'], 13, 2);
	$end_day = substr($_POST['date_range'], 16, 2);
	$end_year = substr($_POST['date_range'], 19, 4);
	
	# Date range form posted
	$where = " 
		WHERE load_status = 0 
		&& first_checkpoint >= '" . $start_year . "-" . $start_month . "-" . $start_day . " 00:00:00' 
		&& last_checkpoint <= '" . $end_year . "-" . $end_month . "-" . $end_day . " 23:59:59'";
} else {

	# Display this month's data
	$where = " WHERE load_status = 0 && first_checkpoint LIKE '" . date('Y') . "-" . date('m') . "%'";
}

# Get load report
$load_report = DB::getInstance()->query("
  SELECT 	
  	loader_entry.driver_id
  	, loader_load.load_id
  	, loader_load.line_haul
  	, loader_load.miles
  	, loader_load.first_checkpoint
  	, loader_load.last_checkpoint
  FROM loader_load 
  LEFT JOIN loader_entry ON loader_load.entry_id=loader_entry.data_id
  $where");

$load_report_count = $load_report->count();
$load_report_line_haul_sum = 0;
$i = 1;

if ($load_report_count) {
	
	foreach ($load_report->results() as $load_report_data) {
		
		$load_report_entry_driver_id[$i] = $load_report_data->driver_id;
		$load_report_load_id[$i] = $load_report_data->load_id;
		$load_report_line_haul[$i] = $load_report_data->line_haul; // This is used for mathematical ops, leave clean
		$load_report_line_haul_sum += $load_report_data->line_haul;
		$load_report_line_haul_format_1[$i] = number_format($load_report_data->line_haul, 2);
		$load_report_miles[$i] = substr($load_report_data->miles, -2) == '.0' ? number_format($load_report_data->miles, 0) : $load_report_data->miles; // Cleans '.0'
		$load_report_miles_sum += $load_report_data->miles;
		$load_report_first_checkpoint_date_time[$i] = date('m-d-Y', strtotime($load_report_data->first_checkpoint));
		$load_report_last_checkpoint_date_time[$i] = date('m-d-Y', strtotime($load_report_data->last_checkpoint));

		# First checkpoint
		$load_first_checkpoint = DB::getInstance()->query("
			SELECT * FROM loader_checkpoint 
			WHERE load_id = " . $load_report_load_id[$i] . " && data_type = 0 ORDER BY date_time ASC LIMIT 1");

		if ($load_first_checkpoint->count()) {
			
			foreach ($load_first_checkpoint->results() as $load_first_checkpoint_data) {
				
				$load_first_checkpoint_date[$i] = date('m/d/y', strtotime($load_first_checkpoint_data->date_time));
				$load_first_checkpoint_time[$i] = date('G:i', strtotime($load_first_checkpoint_data->date_time));
				$load_first_checkpoint_city[$i] = html_entity_decode($load_first_checkpoint_data->city);
				$load_first_checkpoint_state_id[$i] = $load_first_checkpoint_data->state_id;
				$load_first_checkpoint_zip_code[$i] = $load_first_checkpoint_data->zip_code;
			}
		}

		# Last checkpoint
		$load_last_checkpoint = DB::getInstance()->query("
			SELECT * FROM loader_checkpoint 
			WHERE load_id = " . $load_report_load_id[$i] . " && data_type = 1 ORDER BY date_time DESC LIMIT 1");

		if ($load_last_checkpoint->count()) {
			
			foreach ($load_last_checkpoint->results() as $load_last_checkpoint_data) {
				
				$load_last_checkpoint_date[$i] = date('m/d/y', strtotime($load_last_checkpoint_data->date_time));
				$load_last_checkpoint_date_1[$i] = date('Y/m/d', strtotime($load_last_checkpoint_data->date_time)); // Used for fixing datatables date sorting issue
				$load_last_checkpoint_time[$i] = date('G:i', strtotime($load_last_checkpoint_data->date_time));
				$load_last_checkpoint_city[$i] = html_entity_decode($load_last_checkpoint_data->city);
				$load_last_checkpoint_state_id[$i] = $load_last_checkpoint_data->state_id;
				$load_last_checkpoint_zip_code[$i] = $load_last_checkpoint_data->zip_code;
			}
		}

		$i++;
	}
}

# Get load report driver count
$load_report_driver_total = DB::getInstance()->query("
  SELECT loader_entry.driver_id, COUNT(Distinct driver_id) As count_drivers
  FROM loader_load 
  LEFT JOIN loader_entry ON loader_load.entry_id=loader_entry.data_id
  $where");

$load_report_driver_total_count = $load_report_driver_total->count();

if ($load_report_driver_total_count) {
	
	foreach ($load_report_driver_total->results() as $load_report_driver_total_data) {
		
		$load_report_driver_count = $load_report_driver_total_data->count_drivers;
	}
}