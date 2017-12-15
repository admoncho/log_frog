<?php

# Add/update item
if (Input::get('_controller_client_tractor_trailer') == 'add_client_tractor_trailer' || Input::get('_controller_client_tractor_trailer') == 'edit_client_tractor_trailer') {

  if (Input::get('_controller_client_tractor_trailer') == 'add_client_tractor_trailer') {
		
		# Add client_tractor_trailer
    $add = DB::getInstance()->query("INSERT INTO client_tractor_trailer (
    	tractor_id
    	, trailer_type
    	, trailer_number
    	, vin
    	, headrack
    	, year
    	, make
    	, model
    	, license_plate
    	, length
    	, height
    	, width
    	, gross_weight
    	, deck_material
    	, air_ride
    	, door_type
    	, roof_type
    	, bottom_deck
    	, upper_deck
    	, goose_neck
    	, user_id) 

    	VALUES (
    		" . Input::get('tractor_id') . "
    	, '" . Input::get('trailer_type') . "'
    	, '" . Input::get('trailer_number') . "'
    	, '" . htmlentities(Input::get('vin'), ENT_QUOTES) . "'
    	, " . Input::get('headrack') . "
    	, '" . Input::get('year') . "'
    	, '" . htmlentities(Input::get('make'), ENT_QUOTES) . "'
    	, '" . htmlentities(Input::get('model'), ENT_QUOTES) . "'
    	, '" . htmlentities(Input::get('license_plate'), ENT_QUOTES) . "'
    	, '" . Input::get('length') . "'
    	, '" . Input::get('height') . "'
    	, '" . Input::get('width') . "'
    	, '" . Input::get('gross_weight') . "'
    	, '" . Input::get('deck_material') . "'
    	, '" . Input::get('air_ride') . "'
    	, '" . Input::get('door_type') . "'
    	, '" . Input::get('roof_type') . "'
    	, '" . Input::get('bottom_deck') . "'
    	, '" . Input::get('upper_deck') . "'
    	, '" . Input::get('goose_neck') . "'
    	, " . $user->data()->id . "
    	)");

    if ($add->count()) {

    	Session::flash('client', 'Trailer added successfully');
     	Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

    	Session::flash('client_error', $core_language[27]);
    }
 	} elseif (Input::get('_controller_client_tractor_trailer') == 'edit_client_tractor_trailer') {

 		# Update
 		$update_main = DB::getInstance()->query("UPDATE client_tractor_trailer SET 

 			trailer_type = '" . Input::get('trailer_type') . "', 
			trailer_number = " . Input::get('trailer_number') . ", 
			vin = '" . htmlentities(Input::get('vin'), ENT_QUOTES) . "', 
			headrack = '" . Input::get('headrack') . "', 
			year = '" . Input::get('year') . "', 
			make = '" . htmlentities(Input::get('make'), ENT_QUOTES) . "', 
      model = '" . htmlentities(Input::get('model'), ENT_QUOTES) . "', 
			license_plate = '" . Input::get('license_plate') . "', 
			length = '" . Input::get('length') . "', 
			height = '" . Input::get('height') . "', 
			width = '" . Input::get('width') . "', 
			gross_weight = '" . Input::get('gross_weight') . "', 
			deck_material = '" . Input::get('deck_material') . "', 
			air_ride = '" . Input::get('air_ride') . "', 
			door_type = '" . Input::get('door_type') . "', 
			roof_type = '" . Input::get('roof_type') . "', 
			bottom_deck = '" . Input::get('bottom_deck') . "', 
			upper_deck = '" . Input::get('upper_deck') . "', 
			goose_neck = '" . Input::get('goose_neck') . "'

    WHERE tractor_id = " . Input::get('tractor_id'));

    if ($update_main->count()) {

    	Session::flash('client', 'Trailer updated successfully');
	    Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

    	Session::flash('client_error', $core_language[27]);
    }
 	}
}
