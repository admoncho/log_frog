<?php

# Add/update item
if (Input::get('_controller_edit_invoice_settings') == 'edit_invoice_settings') {

  $validation = $validate->check($_POST, array(
    'rate_type' => array('required' => true), 
    'rate' => array('required' => true)
  ));

  if ($validation->passed()) {

	  # Update
 		$update_invoice_settings = DB::getInstance()->query("UPDATE client SET 
      rate_type = " . Input::get('rate_type') . ", 
      rate = '" . Input::get('rate') . "'

    WHERE data_id = " . $_GET['client_id']);

 		if ($update_invoice_settings->count()) {
 			
 			Session::flash($this_file_name, 'Invoice settings updated successfully');
 			Redirect::to('client?client_id=' . $_GET['client_id']);
 		} else {

 			Session::flash($this_file_name . '_error', $core_language[27]);
 		}
  } else {

    Session::flash('client_error', 'Make sure all required fields are filled properly');
  }
}
