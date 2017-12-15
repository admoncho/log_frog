<?php

$validation = $validate->check($_POST, array(
  'name' => array('required' => true),
  'uri' => array('required' => true),
  'invoicing_email' => array(
  	'required' => true,
  	'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
  'phone_number_01' => array('required' => true),
  'batch_schedule' => array('required' => true),
  'requires_soar' => array('required' => true)
));

if($validation->passed()) {

	# This field brings a 2 instead of 0 for validation purposes, set right value on var
	Input::get('requires_soar') == 1 ? $requires_soar = 1 : $requires_soar = 0 ;

	# Update to database
	$update = DB::getInstance()->query("UPDATE factoring_company SET 
		name = '" . htmlentities(Input::get('name'), ENT_QUOTES) . "', 
		uri = '" . htmlentities(Input::get('uri'), ENT_QUOTES) . "', 
		invoicing_email = '" . htmlentities(Input::get('invoicing_email'), ENT_QUOTES) . "', 
		phone_number_01 = '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "', 
		fax = '" . htmlentities(Input::get('fax'), ENT_QUOTES) . "', 
		batch_schedule = " . Input::get('batch_schedule') . ", 
		requires_soar = " . $requires_soar . ", 
		status = " . Input::get('status') . "
		WHERE data_id = " . $_GET['factoring_company_id']);

	$update->count() ? Session::flash('update_loader_factoring_company', $_QC_language[79]) . Redirect::to('factoring-company?factoring_company_id=' . $_GET['factoring_company_id']) : Session::flash('update_loader_factoring_company_error', $_QC_language[16]) ;
} else {
    Session::flash('update_loader_factoring_company_error', $_QC_language[16]);
}
