<?php

# client_tractor_trailer
$client_tractor_trailer = DB::getInstance()->query("SELECT * FROM client_tractor_trailer WHERE tractor_id = " . $client_user_tractor_id[1]);
$client_tractor_trailer_count = $client_tractor_trailer->count();

if ($client_tractor_trailer_count) {
	
	# Iterate through items
	foreach ($client_tractor_trailer->results() as $client_tractor_trailer_data) {

		$client_tractor_trailer_tractor_id = $client_tractor_trailer_data->tractor_id;
		$client_tractor_trailer_id = $client_tractor_trailer_data->id;
		$client_tractor_trailer_length = $client_tractor_trailer_data->length;
		$client_tractor_trailer_height = $client_tractor_trailer_data->height;
		$client_tractor_trailer_width = $client_tractor_trailer_data->width;
		$client_tractor_trailer_trailer_number = $client_tractor_trailer_data->trailer_number;
		$client_tractor_trailer_license_plate = html_entity_decode($client_tractor_trailer_data->license_plate);
		$client_tractor_trailer_gross_weight = $client_tractor_trailer_data->gross_weight;
		$client_tractor_trailer_vin = html_entity_decode($client_tractor_trailer_data->vin);
		$client_tractor_trailer_deck_material = $client_tractor_trailer_data->deck_material;
		$client_tractor_trailer_headrack = $client_tractor_trailer_data->headrack;
		$client_tractor_trailer_air_ride = $client_tractor_trailer_data->air_ride;
		$client_tractor_trailer_year = $client_tractor_trailer_data->year;
		$client_tractor_trailer_make = html_entity_decode($client_tractor_trailer_data->make);
		$client_tractor_trailer_model = html_entity_decode($client_tractor_trailer_data->model);
		$client_tractor_trailer_door_type = $client_tractor_trailer_data->door_type;
		$client_tractor_trailer_roof_type = $client_tractor_trailer_data->roof_type;
		$client_tractor_trailer_trailer_type = $client_tractor_trailer_data->trailer_type;
		$client_tractor_trailer_bottom_deck = $client_tractor_trailer_data->bottom_deck;
		$client_tractor_trailer_upper_deck = $client_tractor_trailer_data->upper_deck;
		$client_tractor_trailer_goose_neck = $client_tractor_trailer_data->goose_neck;
		$client_tractor_trailer_status = $client_tractor_trailer_data->status;
		$client_tractor_trailer_user_id = $client_tractor_trailer_data->user_id;
		$client_tractor_trailer_added = date('m-d-Y', strtotime($client_tractor_trailer_data->added));
	}
}
