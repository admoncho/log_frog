<?php

$validation = $validate->check($_POST, array(
    'manager' => array('required' => true),
    'description' => array('required' => true),
    'amount' => array('required' => true)
));

if ($validation->passed()) {
	$insert = DB::getInstance()->query("INSERT INTO invoice (manager, quantity, description, amount, user_id) VALUES (" . Input::get('manager') . ", 1, '" . htmlentities(Input::get('description'), ENT_QUOTES) . "', '" . Input::get('amount') . "', " . $user->data()->user_id . ")");

	$lastInsertID = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS lastINSERTID FROM invoice");
	foreach ($lastInsertID->results() as $id) {
		$last_id = $id->lastINSERTID;
	}
	
 	$insert->count() ? Session::flash('add_invoice', 'Invoice added successfully') . Redirect::to('invoicing?invoice_id=' . $last_id) : Session::flash('add_invoice_error', $_QC_language[16]) ;
} else {
	Session::flash('add_invoice_error', 'All fields are required.');
}
