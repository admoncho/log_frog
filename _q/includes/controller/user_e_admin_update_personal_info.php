<?php
session_start();
ob_start();

$validation = $validate->check($_POST, array(
    'name' => array('required' => true),
    'last_name' => array('required' => true),
    'email' => array('required' => true),
    'phone_number_01' => array('required' => true)
));

if ($validation->passed()) {

	# Set value to state_id if not chosen
	Input::get('state_id') ? $state_id = Input::get('state_id') : $state_id = '';

	$update = DB::getInstance()->query("UPDATE _QU_e SET name = '". htmlentities(Input::get('name'), ENT_QUOTES) ."', last_name = '". htmlentities(Input::get('last_name'), ENT_QUOTES) ."', email = '". htmlentities(Input::get('email'), ENT_QUOTES) ."', phone_number_01 = '". htmlentities(Input::get('phone_number_01'), ENT_QUOTES) ."', city = '". htmlentities(Input::get('city'), ENT_QUOTES) ."', state_id = '" . $state_id . "', zip_code = '" . htmlentities(Input::get('zip_code'), ENT_QUOTES) ."', license_number = '" . Input::get('license_number') . "' WHERE user_id = " . Input::get('user_id'));
	$update->count() ? Session::flash('update_main_info', $_QC_language[28]) . Redirect::to($_SESSION['HtmlDelimiter'] . '0/user-e?id='.$_GET['id']) : Session::flash('update_main_info_error', $_QC_language[16]) ;
} else {
	Session::flash('update_main_info_error', $_QC_language[27]);
}
