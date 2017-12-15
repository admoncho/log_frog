<?php

# Add item
if (Input::get('_controller_client_user_equipment') == 'add_client_equipment') {

  $validation = $validate->check($_POST, array(
    'client_user_equipment_id' => array('required' => true), 
    'quantity' => array('required' => true)
  ));

  if ($validation->passed()) {

 		$add = DB::getInstance()->query("INSERT INTO client_user_equipment_assoc (driver_id, equipment_id, quantity) VALUES (" . $_GET['user_id'] . ", " . Input::get('client_user_equipment_id') . ", " . Input::get('quantity') . ")");

    if ($add->count()) {

    	Session::flash('client', 'User equipment updated successfully');
	    Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

    	Session::flash('client_error', $core_language[27]);
    }
  } else {

    Session::flash('client_error', 'Make sure all required fields are filled properly');
  }
}

# Update item
if (Input::get('_controller_client_user_equipment') == 'update_client_equipment') {

  $validation = $validate->check($_POST, array(
    'quantity' => array('required' => true)
  ));

  if ($validation->passed()) {

    # Update
    $update = DB::getInstance()->query("UPDATE client_user_equipment_assoc SET quantity = " . Input::get('quantity') . " WHERE id = " . $_GET['edit_equipment']);

    if ($update->count()) {

      Session::flash('client', 'User equipment updated successfully');
      Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

      Session::flash('client_error', $core_language[27]);
    }
  } else {

    Session::flash('client_error', 'Make sure all required fields are filled properly');
  }
}

# Delete item
if (Input::get('_controller_client_user_equipment') == 'delete_client_equipment') {

  $validation = $validate->check($_POST, array(
    'client_user_equipment_assoc_id' => array('required' => true)
  ));

  if ($validation->passed()) {

    # delete
    $delete = DB::getInstance()->query("DELETE FROM client_user_equipment_assoc WHERE id = " . Input::get('client_user_equipment_assoc_id'));

    if ($delete->count()) {

      Session::flash('client', 'User equipment deleted successfully');
      Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

      Session::flash('client_error', $core_language[27]);
    }
  } else {

    Session::flash('client_error', $core_language[27]);
  }
}
