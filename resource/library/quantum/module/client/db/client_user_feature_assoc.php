<?php

# Clause
isset($_GET['user_id']) && !isset($_GET['client_card']) ? $feature_clause = " WHERE driver_id = " . $_GET['user_id'] : "";
isset($_GET['user_id']) && isset($_GET['client_card']) ? $feature_clause = " WHERE driver_id = " . $_POST['client_card_user_id'] : "";
!isset($_GET['user_id']) && isset($_POST['client_card']) ? $feature_clause = " WHERE driver_id = " . $_POST['client_card_user_id'] : "";

# client_user_feature_assoc
$client_user_feature_assoc = DB::getInstance()->query("SELECT * FROM client_user_feature_assoc " . $feature_clause);
$client_user_feature_assoc_count = $client_user_feature_assoc->count();
$i = 1;

if ($client_user_feature_assoc_count) {
	
	# Iterate through items
	foreach ($client_user_feature_assoc->results() as $client_user_feature_assoc_data) {
		
		$client_user_feature_assoc_driver_id[$i] = $client_user_feature_assoc_data->driver_id;
		$client_user_feature_assoc_feature_id[$i] = $client_user_feature_assoc_data->feature_id;
		$client_user_feature_assoc_id[$i] = $client_user_feature_assoc_data->id;

		$client_user_feature_assoc_feature_id_array[] = $client_user_feature_assoc_data->feature_id;
		$i++;
	}
}
