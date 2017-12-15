<?php

if ($_GET['add_feature']) {
	
	if (!$client_user_feature_assoc_count) {
		
		$clause = "";
	} else {

		$clause = " WHERE id NOT IN (" . implode(', ', $client_user_feature_assoc_feature_id_array) . ")";
	}
} else {

	$clause = " ORDER BY name ASC";
}

# client_user_feature
$client_user_feature = DB::getInstance()->query("SELECT * FROM client_user_feature" . $clause);
$client_user_feature_count = $client_user_feature->count();
$i = 1;

if ($client_user_feature_count) {
	
	# Iterate through items
	foreach ($client_user_feature->results() as $client_user_feature_data) {
		
		$client_user_feature_id[$i] = $client_user_feature_data->id;
		$client_user_feature_name[$i] = html_entity_decode($client_user_feature_data->name);
		$i++;

		$client_user_feature_id_name[$client_user_feature_data->id] = html_entity_decode($client_user_feature_data->name);
	}
}
