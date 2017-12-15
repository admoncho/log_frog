<?php

$validation = $validate->check($_POST, array(
  'note' => array('required' => true)
));

if($validation->passed()) {

	Input::get('important_note') == 'on' ? $important_note = 1 : $important_note = 0;

	# Add to database
	# This is also used in controller: add_loader_load_status_change_notification, file: loader-load-quickpay-invoicing, controller: send_loader_load
	$insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, user_id) VALUES (" . $_GET['load_id'] . ", '" . htmlentities(Input::get('note'), ENT_QUOTES) . "', " . $important_note . ", " . $user->data()->id . ")");

	$insert->count() ? Session::flash('add_loader_loade_note', 'Broker added successfully') . Redirect::to('view-load?load_id=' . $_GET['load_id'] . '#staff_notes') : Session::flash('add_loader_loade_note_error', $_QC_language[16]) ;
} else {
    Session::flash('add_loader_loade_note_error', $_QC_language[16]);
}
