<?php

# client_trailer_type
$client_trailer_type = DB::getInstance()->query("SELECT * FROM client_trailer_type ORDER BY name ASC");
$client_trailer_type_count = $client_trailer_type->count();
$i = 1;

if ($client_trailer_type_count) {
	
	# Iterate through items
	foreach ($client_trailer_type->results() as $client_trailer_type_data) {
		
		$client_trailer_type_id[$i] = $client_trailer_type_data->id;
		$client_trailer_type_name[$i] = html_entity_decode($client_trailer_type_data->name);
		$client_trailer_type_user_id[$i] = $client_trailer_type_data->user_id;
		$client_trailer_type_added[$i] = date('m-d-Y', strtotime($client_trailer_type_data->added));
		$i++;

		$client_trailer_type_id_name[$client_trailer_type_data->id] = html_entity_decode($client_trailer_type_data->name);
	}
}
