<?php

# db/ppg_category.php
include(LIBRARY_PATH . "/quantum/module/core/db/ppg_category.php");

if ($_GET['file_name']) {
	
	$clause = " WHERE file_name = '" . str_replace('_', '-', $_GET['file_name'] . "'");
} else {

	$clause = " ORDER BY title ASC";
}

$ppg = DB::getInstance()->query("SELECT * FROM ppg" . $clause);
$ppg_count = $ppg->count();
$i = 1;

# Iterate through table items
foreach ($ppg->results() as $ppg_data) {
	
	$ppg_id[$i] = $ppg_data->id;
	$ppg_title[$i] = html_entity_decode($ppg_data->title);
	$ppg_file_name[$i] = $ppg_data->file_name;
	$ppg_added[$i] = date('m/d/Y', strtotime($ppg_data->added));
	$ppg_user_id[$i] = $ppg_data->user_id;

	# Spot and block adding items that already exist
	if (Input::get('_controller_ppg') == 'add_ppg') {

		if (htmlentities(Input::get('title'), ENT_QUOTES) == $ppg_title[$i]) {
			
			# Duplicate entry
			$ppg_duplicate_entry = 1;
		}
	}

	$i++;

	$ppg_id_title[$ppg_data->id] = html_entity_decode($ppg_data->title);
}

# Alternate list for the header dropdown menu
$ppg_list = DB::getInstance()->query("SELECT * FROM ppg ORDER BY title ASC");
$ppg_list_count = $ppg_list->count();
$i = 1;

# Iterate through table items
foreach ($ppg_list->results() as $ppg_list_data) {
	
	$ppg_list_id[$i] = $ppg_list_data->id;
	$ppg_list_title[$i] = html_entity_decode($ppg_list_data->title);
	$ppg_list_file_name[$i] = $ppg_list_data->file_name;

	$i++;
}
