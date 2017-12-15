<?php

$ppg_category = DB::getInstance()->query("SELECT * FROM ppg_category ORDER BY name ASC");
$ppg_category_count = $ppg_category->count();
$i = 1;

# Iterate through table items
foreach ($ppg_category->results() as $ppg_category_data) {
	
	$ppg_category_id[$i] = $ppg_category_data->id;
	$ppg_category_name[$i] = html_entity_decode($ppg_category_data->name);
	$ppg_category_added[$i] = date('m/d/Y', strtotime($ppg_category_data->added));
	$ppg_category_user_id[$i] = $ppg_category_data->user_id;

	# Spot and block adding items that already exist
	if (Input::get('_controller_ppg_category') == 'add' || Input::get('_controller_ppg_category') == 'update') {

		if (htmlentities(Input::get('name'), ENT_QUOTES) == $ppg_category_name[$i]) {
			
			# Duplicate entry
			$ppg_category_duplicate_entry = 1;
		}
	}

	$i++;

	$ppg_category_id_name[$ppg_category_data->id] = html_entity_decode($ppg_category_data->name);
}
