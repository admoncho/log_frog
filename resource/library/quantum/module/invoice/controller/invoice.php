<?php
session_start();
ob_start();

# New invoice item
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

# New invoice
if (Input::get('_controller_invoice') == 'add_new_invoice') {

  $validation = $validate->check($_POST, array(
    'client_id' => array('required' => true),
    'description' => array('required' => true),
    'amount' => array('required' => true)
  ));

  if($validation->passed()) {

		# Add invoice
		$insert_invoice = DB::getInstance()->query("
			INSERT INTO invoice (client_id) 
			VALUES (" . Input::get('client_id') . ")
		");

		if ($insert_invoice->count()) {

			$last_invoice = $insert_invoice->last();

      # Add invoice item
      $insert_invoice_item = DB::getInstance()->query("
        INSERT INTO invoice_item (invoice_id, description, cost) 
        VALUES (" . $last_invoice . ", '" . htmlentities(Input::get('description')) . "', " . Input::get('amount') . ")
      ");

      if ($insert_invoice_item->count()) {
      
        Session::flash('invoice', 'Invoice added successfully');
        Redirect::to('?invoice_id=' . $last_invoice);
      }
    } else {

      Session::flash($this_file_name . '_error', $core_language[27]);
    }
	} else {

		Session::flash('invoice_error', $core_language[27]);
	}
}

# Delete invoice
# Deteled invoices are flagged with a custom string saved in invoice table under 
# the errorMessage column as "Custom - deleted" 
if (Input::get('_controller_invoice') == 'delete_invoice') {

  # Update
  $delete_invoice = DB::getInstance()->query("UPDATE invoice SET 
    errorMessage = 'Custom - deleted' WHERE id = " . $_GET['invoice_id']);

  if ($delete_invoice->count()) {

    Session::flash('invoice', 'Invoice deleted');
    Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/invoice/');
  } else {

    Session::flash('invoice_error', $core_language[27]);
  }
}