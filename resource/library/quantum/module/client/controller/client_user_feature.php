<?php

# Add item
if (Input::get('_controller_client_user_feature') == 'add_client_feature') {

  $validation = $validate->check($_POST, array(
    'client_user_feature_id' => array('required' => true)
  ));

  if ($validation->passed()) {

 		$add = DB::getInstance()->query("INSERT INTO client_user_feature_assoc (driver_id, feature_id) VALUES (" . $_GET['user_id'] . ", " . Input::get('client_user_feature_id') . ")");

    if ($add->count()) {

    	Session::flash('client', 'User feature updated successfully');
	    Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

    	Session::flash('client_error', $core_language[27]);
    }
  } else {

    Session::flash('client_error', 'Make sure all required fields are filled properly');
  }
}

# Delete item
if (Input::get('_controller_client_user_feature') == 'delete_client_feature') {

  $validation = $validate->check($_POST, array(
    'client_user_feature_assoc_id' => array('required' => true)
  ));

  if ($validation->passed()) {

    # delete
    $delete = DB::getInstance()->query("DELETE FROM client_user_feature_assoc WHERE id = " . Input::get('client_user_feature_assoc_id'));

    if ($delete->count()) {

      Session::flash('client', 'User feature deleted successfully');
      Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

      Session::flash('client_error', $core_language[27]);
    }
  } else {

    Session::flash('client_error', $core_language[27]);
  }
}
