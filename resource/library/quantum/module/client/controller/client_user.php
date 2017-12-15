<?php

# Update item
if (Input::get('_controller_client_user') == 'edit_client_user') {

  $validation = $validate->check($_POST, array(
    'user_type' => array('required' => true)
  ));

  if ($validation->passed()) {

  	Input::get('user_type') == 9 ? $user_type_value = 0 : '';
  	Input::get('user_type') < 9 ? $user_type_value = Input::get('user_type') : '';

		# Update
 		$update_client_user = DB::getInstance()->query("UPDATE client_user SET user_type = " . $user_type_value . " WHERE user_id = " . $_GET['user_id']);
 		$update_client_user_count = $update_client_user->count();

    if ($update_client_user_count) {

    	Session::flash('client', 'Client user updated successfully');
	    Redirect::to('client?client_id=' . $_GET['client_id'] . '&user_id=' . $_GET['user_id']);
    } else {

    	Session::flash('client_error', $core_language[27]);
    }
  } else {

    Session::flash('client_error', 'Make sure all required fields are filled properly');
  }
}
