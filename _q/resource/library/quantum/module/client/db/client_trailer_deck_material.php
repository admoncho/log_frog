<?php

# client_trailer_deck_material
$client_trailer_deck_material = DB::getInstance()->query("SELECT * FROM client_trailer_deck_material ORDER BY name ASC");
$client_trailer_deck_material_count = $client_trailer_deck_material->count();
$i = 1;

if ($client_trailer_deck_material_count) {
	
	# Iterate through items
	foreach ($client_trailer_deck_material->results() as $client_trailer_deck_material_data) {
		
		$client_trailer_deck_material_id[$i] = $client_trailer_deck_material_data->id;
		$client_trailer_deck_material_name[$i] = html_entity_decode($client_trailer_deck_material_data->name);
		$client_trailer_deck_material_user_id[$i] = $client_trailer_deck_material_data->user_id;
		$client_trailer_deck_material_added[$i] = date('m-d-Y', strtotime($client_trailer_deck_material_data->added));
		$i++;

		$client_trailer_deck_material_id_name[$client_trailer_deck_material_data->id] = html_entity_decode($client_trailer_deck_material_data->name);
	}
}
