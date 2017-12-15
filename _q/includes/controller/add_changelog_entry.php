<?php

$validation = $validate->check($_POST, array(
  'entry' => array('required' => true),
  'type' => array('required' => true),
  'status' => array('required' => true)
));

if($validation->passed()) {

	$add = DB::getInstance()->query("INSERT INTO changelog (entry, type, status, user_id) VALUES ('" . htmlentities(Input::get('entry'), ENT_QUOTES) . "', " . Input::get('type') . ", " . Input::get('status') . ", " . $user->data()->user_id . ")");
	$add->count() ? Session::flash('add_changelog_entry', 'Entry added successfully') . Redirect::to('changelog') : Session::flash('add_changelog_entry_error', $_QC_language[16]) ;
} else {
  xSession::flash('add_changelog_entry_error', $_QC_language[16]);
}

