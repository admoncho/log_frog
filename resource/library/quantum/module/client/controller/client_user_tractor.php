<?php

# Add/update item
if (Input::get('_controller_client_user_tractor') == 'add_client_user_tractor' || Input::get('_controller_client_user_tractor') == 'edit_client_user_tractor') {

  if (Input::get('_controller_client_user_tractor') == 'add_client_user_tractor') {
		
		# Add client insurance
    $add = DB::getInstance()->query("INSERT INTO client_user_tractor (driver_id, number, color, vin, headrack, year, make, model, weight, sleeper, name_on_the_side, license_plate, user_id) VALUES (" . $_GET['user_id'] . ", '" . htmlentities(Input::get('number'), ENT_QUOTES) . "', '" . htmlentities(Input::get('color'), ENT_QUOTES) . "', '" . htmlentities(Input::get('vin'), ENT_QUOTES) . "', " . Input::get('headrack') . ", '" . Input::get('year') . "', '" . htmlentities(Input::get('make'), ENT_QUOTES) . "', '" . htmlentities(Input::get('model'), ENT_QUOTES) . "', '" . Input::get('weight') . "', " . Input::get('sleeper') . ", '" . htmlentities(Input::get('name_on_the_side'), ENT_QUOTES) . "', '" . Input::get('license_plate') . "', " . $user->data()->id . ")");

    if ($add->count()) {

    	Session::flash('client', 'Driver tractor added successfully');
     	Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

    	Session::flash('client_error', $core_language[27]);
    }
 	} elseif (Input::get('_controller_client_user_tractor') == 'edit_client_user_tractor') {

 		# Update
 		$update_main = DB::getInstance()->query("UPDATE client_user_tractor SET 
      number = '" . htmlentities(Input::get('number'), ENT_QUOTES) . "', 
      color = '" . htmlentities(Input::get('color'), ENT_QUOTES) . "', 
      vin = '" . htmlentities(Input::get('vin'), ENT_QUOTES) . "', 
      headrack = " . Input::get('headrack') . ", 
      year = " . Input::get('year') . ", 
      make = '" . htmlentities(Input::get('make'), ENT_QUOTES) . "', 
      model = '" . htmlentities(Input::get('model'), ENT_QUOTES) . "', 
     	weight = '" . Input::get('weight') . "', 
     	sleeper = " . Input::get('sleeper') . ", 
     	name_on_the_side = '" . htmlentities(Input::get('name_on_the_side'), ENT_QUOTES) . "', 
     	license_plate = '" . Input::get('license_plate') . "'

    WHERE driver_id = " . $_GET['user_id']);   		

    if ($update_main->count()) {

    	Session::flash('client', 'Driver tractor updated successfully');
	    Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

    	Session::flash('client_error', $core_language[27]);
    }
 	}
}
