<?php

$validation = $validate->check($_POST, array(
  'method_id' => array('required' => true)
));

if($validation->passed()) {

	# Add to database
	$insert = DB::getInstance()->query("INSERT INTO factoring_company_service_fee (factoring_company_id, fee, method_id, number_of_days, user_id) VALUES (" . $_GET['factoring_company_id'] . ", '" . Input::get('fee') . "', " . Input::get('method_id') . ", " . Input::get('number_of_days') . ", " . $user->data()->user_id . ")");
	$insert->count() ? Session::flash('add_loader_factoring_company_service_fee', 'Service fee added successfully') . Redirect::to('factoring-company?factoring_company_id=' . $_GET['factoring_company_id'] . '#factoring-company-service-fee') : Session::flash('add_loader_factoring_company_service_fee_error', $_QC_language[16]) ;
} else {
    Session::flash('add_loader_factoring_company_service_fee_error', $_QC_language[16]);
}
