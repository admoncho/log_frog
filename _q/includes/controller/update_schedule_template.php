<?php

$validation = $validate->check($_POST, array(
	'schedule_email_subject' => array('required' => true),
	'schedule_email_body' => array('required' => true)
));

if($validation->passed()) {
	if ($schedule_config_count) {
		$update = DB::getInstance()->query("UPDATE factoring_company_schedule_config SET email_subject = '" . htmlentities(Input::get('schedule_email_subject')) . "', email_body = '" . htmlentities(Input::get('schedule_email_body')) . "' WHERE id = 1");
		$update->count() ? Session::flash('update_schedule_template', 'Data updated successfully') . Redirect::to('loader#schedule_template') : Session::flash('update_schedule_template_error', $_QC_language[16]) ;		
	} else {
		$update = DB::getInstance()->query("INSERT INTO factoring_company_schedule_config (email_subject, email_body) VALUES ('" . htmlentities(Input::get('schedule_email_subject')) . "', '" . htmlentities(Input::get('schedule_email_body')) . "')");
		$update->count() ? Session::flash('update_schedule_template', 'Data added successfully') . Redirect::to('loader#schedule_template') : Session::flash('update_schedule_template_error', $_QC_language[16]) ;
	}
} else {
	Session::flash('update_schedule_template_error', $_QC_language[16]);
}
