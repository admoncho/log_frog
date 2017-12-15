<?php

# This file may not be being used...

/*# This file name
$this_file_name = basename(__FILE__, '.php');



# Checkpoints
$load = DB::getInstance()->query("SELECT * FROM loader_checkpoint" . $clause);

$load_count = $load->count();
$i = 1;

if ($load_count) {
	
	foreach ($load->results() as $load_data) {
		
		$load_entry_id[$i] = $load_data->entry_id;
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
		$load_load_lock[$i] = $load_data->load_lock;
		$load_load_status[$i] = $load_data->load_status;
		$load_added[$i] = date('m/d/Y', strtotime($load_data->added));
		$load_added_by[$i] = $load_data->added_by;
		$load_user_id[$i] = $load_data->user_id;

		$entry_driver_id[$i] = $load_data->driver_id;

		$i++;
	}
}*/
