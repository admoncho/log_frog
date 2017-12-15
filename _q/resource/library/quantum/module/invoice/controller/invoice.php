<?php

# New item
if (Input::get('_controller_invoice') == 'add_invoice_item') {

  $validation = $validate->check($_POST, array(
    'description' => array('required' => true),
    'amount' => array('required' => true)
  ));

  if($validation->passed()) {

		# Add invoice items
		$insert = DB::getInstance()->query("
			INSERT INTO invoice_item (invoice_id, description, cost) 
			VALUES (" . $_GET['invoice_id'] . ", '" . htmlentities(Input::get('description')) . "', " . Input::get('amount') . ")
		");

		if ($insert->count()) {
      
      Session::flash('invoice', 'Item added successfully');
      Redirect::to('?invoice_id=' . $_GET['invoice_id']);
    } else {

      Session::flash($this_file_name . '_error', $core_language[27]);
    }
	} else {

		Session::flash('invoice_error', $core_language[27]);
	}
}

# Delete item
if (Input::get('_controller_invoice') == 'delete_invoice_item') {

	$delete = DB::getInstance()->query("
		DELETE FROM invoice_item 
		WHERE id = " . $_GET['item_id']);
  
 	if ($delete->count()) {
 		
 		Session::flash($this_file_name, 'Charge deleted successfully from invoice.');
 		Redirect::to('?invoice_id=' . $_GET['invoice_id']);
 	} else {

 		Session::flash($this_file_name . '_error', $core_language[27]);
 	}
}