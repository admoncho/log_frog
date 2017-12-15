<?php

if ($_GET['add_equipment']) {
	
	if (!$client_user_equipment_assoc_count) {
		
		$clause = "";
	} else {

		$clause = " WHERE id NOT IN (" . implode(', ', $client_user_equipment_assoc_equipment_id_array) . ")";
	}
} else {

	$clause = " ORDER BY name ASC";
}

# client_user_equipment
$client_user_equipment = DB::getInstance()->query("SELECT * FROM client_user_equipment" . $clause);
$client_user_equipment_count = $client_user_equipment->count();
$i = 1;

if ($client_user_equipment_count) {
	
	# Iterate through items
	foreach ($client_user_equipment->results() as $client_user_equipment_data) {
		
		$client_user_equipment_id[$i] = $client_user_equipment_data->id;
		$client_user_equipment_name[$i] = html_entity_decode($client_user_equipment_data->name);
		$i++;

		$client_user_equipment_id_name[$client_user_equipment_data->id] = html_entity_decode($client_user_equipment_data->name);
	}
}
