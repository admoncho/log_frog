<?php

$validation = $validate->check($_POST, array(
  'name' => array('required' => true),
  'uri' => array('required' => true),
  'invoicing_email' => array(
  	'required' => true,
  	'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
  'phone_number_01' => array('required' => true),
	'batch_schedule' => array('required' => true)
));

if($validation->passed()) {

	# This field brings a 2 instead of 0 for validation purposes, set right value on var
	Input::get('requires_soar') == 1 ? $requires_soar = 1 : $requires_soar = 0 ;

	# Add to database
	$insert = DB::getInstance()->query("INSERT INTO factoring_company (name, uri, invoicing_email, phone_number_01, fax, batch_schedule, requires_soar, user_id) VALUES ('" . htmlentities(Input::get('name'), ENT_QUOTES) . "', '" . htmlentities(Input::get('uri'), ENT_QUOTES) . "', '" . Input::get('invoicing_email') . "', '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "', '" . htmlentities(Input::get('fax'), ENT_QUOTES) . "', " . Input::get('batch_schedule') . ", " . $requires_soar . ", " . $user->data()->user_id . ")");

	$lastInsertID = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS lastINSERTID FROM factoring_company");
	foreach ($lastInsertID->results() as $id) {
		$last_id = $id->lastINSERTID;
	}

	$insert->count() ? Session::flash('add_factoring_company', 'Factoring company added successfully') . Redirect::to('factoring-company?factoring_company_id=' . $last_id) : Session::flash('add_factoring_company_error', $_QC_language[16]) ;
} else {
    Session::flash('add_factoring_company_error', $_QC_language[16]);
}
