<?php

# Handle date
/*if ($_POST['date'] != '') {
	# code...
}*/
$date_unit         = explode("-", $_POST['date']);
$time_unit         = explode(":", $_POST['time']);
$checkpoint_year   = $date_unit[2];
$checkpoint_month  = $date_unit[0];
$checkpoint_day    = $date_unit[1];
$checkpoint_hour   = $time_unit[0];
$checkpoint_minute = $time_unit[1];
$date_time = $date_unit[2] . '-' . $date_unit[0] . '-' . $date_unit[1] . ' ' . $time_unit[0] . ':' . $time_unit[1] . ':00';

# Add checkpoint
if (Input::get('_controller_draft_checkpoint') == 'add') {

	$validation = $validate->check($_POST, array(
		'date' => array('required' => true),
		'city' => array('required' => true),
		'state_id' => array('required' => true)
	));	

	if($validation->passed()) {

		# data_type value
		Input::get('data_type') == 9 ? $data_type_value = 0 : $data_type_value = 1;

		$add = DB::getInstance()->query("
			INSERT INTO loader_load_draft_checkpoint (
				load_draft_id, 
				date_time, 
				city, 
				state_id, 
				data_type, 
				user_id) 
			VALUES (
				" . $_GET['draft_id'] . ", 
				'" . $date_time . "', 
				'" . htmlentities(Input::get('city'), ENT_QUOTES) . "', 
				" . Input::get('state_id') . ", 
				" . $data_type_value . ", 
				" . $user->data()->id . "
			)
		");

		if ($add->count()) {
			
			Session::flash('loader', 'Checkpoint added successfully');
			Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
		} else {

			Session::flash('loader_error', $core_language[27]);
		}
	} else {

		Session::flash('loader_error', $core_language[27]);
	}
}

# Update checkpoint
if (Input::get('_controller_draft_checkpoint') == 'update') {

	$validation = $validate->check($_POST, array(
		'date' => array('required' => true),
		'city' => array('required' => true),
		'state_id' => array('required' => true)
	));	

	if($validation->passed()) {
		
		$update = DB::getInstance()->query("UPDATE loader_load_draft_checkpoint SET 
      date_time = '" . $date_time . "', 
      city = '" . htmlentities(Input::get('city'), ENT_QUOTES) . "', 
      state_id = '" . Input::get('state_id') . "', 
      data_type = '" . Input::get('data_type') . "'

    WHERE checkpoint_id = " . $_GET['checkpoint_id']);

    if ($update->count()) {
    	
    	Session::flash('loader', 'Checkpoint updated successfully');
    	Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
    } else {

    	Session::flash('loader_error', $core_language[27]);
    }
	}
}

# Delete checkpoint
if (Input::get('_controller_draft_checkpoint') == 'delete') {

	$delete = DB::getInstance()->query("DELETE FROM loader_load_draft_checkpoint WHERE checkpoint_id = " . $_POST['delete_checkpoint']);

	if ($delete->count()) {
		
		Session::flash('loader', 'Item deleted successfully');
		Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
	} else {

		Session::flash('loader_error', $core_language[27]);
	}
}
