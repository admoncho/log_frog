<?php
session_start();
ob_start();

# Add/update item
if (Input::get('_controller_draft_load') == 'add_draft' || Input::get('_controller_draft_load') == 'update_draft') {

  if (Input::get('_controller_draft_load') == 'add_draft') {
    
    $validation = $validate->check($_POST, array(
      'initial_rate' => array('required' => true),
      'weight' => array('required' => true),
      'pick_date' => array('required' => true), 
      'drop_date' => array('required' => true)
    ));
  } elseif (Input::get('_controller_draft_load') == 'update_draft') {
    
    $validation = $validate->check($_POST, array(
      'initial_rate' => array('required' => true),
      'weight' => array('required' => true)
    ));
  }


  if ($validation->passed()) {

    # Handle date
    $pick_date_unit         = explode("-", $_POST['pick_date']);
    $pick_time_unit         = explode(":", $_POST['pick_time']);
    $pick_checkpoint_year   = $pick_date_unit[2];
    $pick_checkpoint_month  = $pick_date_unit[0];
    $pick_checkpoint_day    = $pick_date_unit[1];
    $pick_checkpoint_hour   = $pick_time_unit[0];
    $pick_checkpoint_minute = $pick_time_unit[1];
    $pick_date_time = $pick_date_unit[2] . '-' . $pick_date_unit[0] . '-' . $pick_date_unit[1] . ' ' . $pick_time_unit[0] . ':' . $pick_time_unit[1] . ':00';

    $drop_date_unit         = explode("-", $_POST['drop_date']);
    $drop_time_unit         = explode(":", $_POST['drop_time']);
    $drop_checkpoint_year   = $drop_date_unit[2];
    $drop_checkpoint_month  = $drop_date_unit[0];
    $drop_checkpoint_day    = $drop_date_unit[1];
    $drop_checkpoint_hour   = $drop_time_unit[0];
    $drop_checkpoint_minute = $drop_time_unit[1];
    $drop_date_time = $drop_date_unit[2] . '-' . $drop_date_unit[0] . '-' . $drop_date_unit[1] . ' ' . $drop_time_unit[0] . ':' . $drop_time_unit[1] . ':00';

  	if (Input::get('_controller_draft_load') == 'add_draft') {
			
      # Add draft
	    $add = DB::getInstance()->query("
        INSERT INTO loader_load_draft (
          initial_rate
          , deadhead
          , weight
          , broker_name_number
          , note, user_id) 
        VALUES (
          '" . Input::get('initial_rate') . "'
          , '" . Input::get('deadhead') . "'
          , '" . Input::get('weight') . "'
          , '" . htmlentities(Input::get('broker_name_number'), ENT_QUOTES) . "'
          , '" . htmlentities(Input::get('note'), ENT_QUOTES) . "'
          , " . $user->data()->id . "
        )");

	    if ($add->count()) {

	    	$add_last_id = $add->last();

	    	$add_pick = DB::getInstance()->query("
          INSERT INTO loader_load_draft_checkpoint (
            load_draft_id, 
            date_time, 
            city, 
            state_id, 
            data_type, 
            user_id) 
          VALUES (
            " . $add_last_id . ", 
            '" . $pick_date_time . "', 
            '" . htmlentities(Input::get('draft_pick_city'), ENT_QUOTES) . "', 
            " . Input::get('draft_pick_state_id') . ", 
            0, 
            " . $user->data()->id . "
          )
        ");

        $add_drop = DB::getInstance()->query("
          INSERT INTO loader_load_draft_checkpoint (
            load_draft_id, 
            date_time, 
            city, 
            state_id, 
            data_type, 
            user_id) 
          VALUES (
            " . $add_last_id . ", 
            '" . $drop_date_time . "', 
            '" . htmlentities(Input::get('draft_drop_city'), ENT_QUOTES) . "', 
            " . Input::get('draft_drop_state_id') . ", 
            1, 
            " . $user->data()->id . "
          )
        ");

	    	Session::flash('loader', 'Draft added successfully');
	     	Redirect::to('draft-load?draft_id=' . $add_last_id);
	    } else {

	    	Session::flash('loader_error', $core_language[27]);
	    }
   	} elseif (Input::get('_controller_draft_load') == 'update_draft') {

   		# Update
   		$update_main = DB::getInstance()->query("
        UPDATE loader_load_draft 
        SET 
          initial_rate = '" . Input::get('initial_rate') . "'
          , deadhead = '" . Input::get('deadhead') . "'
          , weight = '" . Input::get('weight') . "'
          , broker_name_number = '" . htmlentities(Input::get('broker_name_number'), ENT_QUOTES) . "'
          , broker_email = '" . htmlentities(Input::get('broker_email'), ENT_QUOTES) . "'
          , note = '" . htmlentities(Input::get('note'), ENT_QUOTES) . "'
	    WHERE id = " . $_GET['draft_id']);

	    if ($update_main->count()) {

	    	Session::flash('loader', 'Draft updated successfully');
		    Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
	    } else {

	    	Session::flash('loader_error', $core_language[27]);
	    }
   	}
	    
  } else {

    Session::flash('loader_error', 'Make sure all required fields are filled properly');
  }
}

# Delete item
if (Input::get('_controller_draft_load') == 'delete_draft') {

  $delete = DB::getInstance()->query("DELETE FROM loader_load_draft WHERE id = " . Input::get('draft_id'));

  if ($delete->count()) {
      
    # Kill image
    if (file_exists($draft_rate_con_path . Input::get('draft_id') . '.pdf')) {
      
      unlink($draft_rate_con_path . Input::get('draft_id') . '.pdf');
    }

    Session::flash('loader', 'Draft deleted successfully');
    Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/loader/');
  } else {

    Session::flash('loader_error', $core_language[27]);
  }
}
