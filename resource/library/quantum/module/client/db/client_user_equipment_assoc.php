<?php

# Clause
isset($_GET['user_id']) && !isset($_GET['client_card']) ? $equipment_clause = " WHERE driver_id = " . $_GET['user_id'] : "";
isset($_GET['user_id']) && isset($_GET['client_card']) ? $equipment_clause = " WHERE driver_id = " . $_POST['client_card_user_id'] : "";
!isset($_GET['user_id']) && isset($_POST['client_card']) ? $equipment_clause = " WHERE driver_id = " . $_POST['client_card_user_id'] : "";

# client_user_equipment_assoc
$client_user_equipment_assoc = DB::getInstance()->query("SELECT * FROM client_user_equipment_assoc " . $equipment_clause);
$client_user_equipment_assoc_count = $client_user_equipment_assoc->count();
$i = 1;

if ($client_user_equipment_assoc_count) {
	
	# Iterate through items
	foreach ($client_user_equipment_assoc->results() as $client_user_equipment_assoc_data) {
		
		$client_user_equipment_assoc_driver_id[$i] = $client_user_equipment_assoc_data->driver_id;
		$client_user_equipment_assoc_equipment_id[$i] = $client_user_equipment_assoc_data->equipment_id;
		$client_user_equipment_assoc_id[$i] = $client_user_equipment_assoc_data->id;
		$client_user_equipment_assoc_quantity[$i] = $client_user_equipment_assoc_data->quantity;

		$client_user_equipment_assoc_equipment_id_array[] = $client_user_equipment_assoc_data->equipment_id;
		$i++;
	}
}
