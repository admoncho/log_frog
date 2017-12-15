<?php

# Add item
if (Input::get('_controller_draft_lead') == 'add') {

  $validation = $validate->check($_POST, array(
    'driver_id' => array('required' => true), 
    'status' => array('required' => true)
  ));

  if ($validation->passed()) {

  	# Return user if status is 2 or 3 and note is empty
  	if ((Input::get('status') == 2 || Input::get('status') == 3) && Input::get('note') == '') {
  		
  		Session::flash('loader_error', 'You need to add a note for on hold or declined leads!');
	    Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
  	} else {

  		# Add lead
	    $add = DB::getInstance()->query("
        INSERT INTO loader_load_draft_lead (draft_id, driver_id, last_modified) 

        VALUES (
          " . $_GET['draft_id'] . "
          , " . Input::get('driver_id') . "
          , '" . date('Y-m-d G:i:s') . "')
      ");

	    if ($add->count()) {
      
        # Last id
        $last_id = $add->last();

        # Add lead status
        $add_status = DB::getInstance()->query("
          INSERT INTO loader_load_draft_lead_status (lead_id, status, note, user_id) 

          VALUES (
            " . $last_id . "
            , " . Input::get('status') . "
            , '" . htmlentities(Input::get('note'), ENT_QUOTES) . "'
            , " . $user->data()->id . ")
        ");

        if ($add->count()) {
          
          Session::flash('loader', 'Lead added successfully');
          Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
        }
	    } else {

	    	Session::flash('loader_error', $core_language[27]);
	    }
  	}	    
  } else {

    Session::flash('loader_error', 'Make sure all required fields are filled properly');
  }
}

# put_on_hold
if (Input::get('_controller_draft_lead') == 'put_on_hold') {
  
  $validation = $validate->check($_POST, array(
    'lead_id' => array('required' => true), 
    'note' => array('required' => true)
  ));

  if ($validation->passed()) {

    # Add status
    $add_status = DB::getInstance()->query("INSERT INTO loader_load_draft_lead_status (lead_id, status, note, user_id) VALUES (" . Input::get('lead_id') . ", 2, '" . htmlentities(Input::get('note'), ENT_QUOTES) . "', " . $user->data()->id . ")");

    if ($add_status->count()) {

      # Update last_modified for lead
      $update_last_modified = DB::getInstance()->query("
        UPDATE loader_load_draft_lead 
        SET last_modified = '" . date('Y-m-d G:i:s') . "' 
        WHERE id = " . Input::get('lead_id')
      );

      if ($update_last_modified->count()) {
        
        Session::flash('loader', 'Lead added successfully');
        Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
      }
    } else {

      Session::flash('loader_error', 'There was an error, data was not added.');
      Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
    }
  } else {

    Session::flash('loader_error', 'Make sure all required fields are filled properly');
  }  
}

# decline
if (Input::get('_controller_draft_lead') == 'decline') {
  
  $validation = $validate->check($_POST, array(
    'lead_id' => array('required' => true), 
    'note' => array('required' => true)
  ));

  if ($validation->passed()) {

    # Add status
    $add_status = DB::getInstance()->query("INSERT INTO loader_load_draft_lead_status (lead_id, status, note, user_id) VALUES (" . Input::get('lead_id') . ", 3, '" . htmlentities(Input::get('note'), ENT_QUOTES) . "', " . $user->data()->id . ")");

    if ($add_status->count()) {

      # Update last_modified for lead
      $update_last_modified = DB::getInstance()->query("
        UPDATE loader_load_draft_lead 
        SET last_modified = '" . date('Y-m-d G:i:s') . "' 
        WHERE id = " . Input::get('lead_id')
      );

      if ($update_last_modified->count()) {
        
        Session::flash('loader', 'Lead set as declined successfully');
        Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
      }
    } else {

      Session::flash('loader_error', 'There was an error.');
      Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
    }
  } else {

    Session::flash('loader_error', 'Make sure all required fields are filled properly');
  }  
}

# accept
if (Input::get('_controller_draft_lead') == 'accept') {
  
  # Add status
  $add_status = DB::getInstance()->query("
    INSERT INTO loader_load_draft_lead_status (lead_id, status, user_id) 
    VALUES (" . $_GET['lead_id'] . ", 4, " . $user->data()->id . ")
  ");

  if ($add_status->count()) {

    # Update last_modified for lead
    $update_last_modified = DB::getInstance()->query("
      UPDATE loader_load_draft_lead 
      SET last_modified = '" . date('Y-m-d G:i:s') . "' 
      WHERE id = " . Input::get('lead_id')
    );

    if ($update_last_modified->count()) {
      
      Session::flash('loader', 'Lead accepted successfully');
      Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
    }
  } else {

    Session::flash('loader_error', 'There was an error.');
    Redirect::to('draft-load?draft_id=' . $_GET['draft_id']);
  }  
}
