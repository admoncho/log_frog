<?php
session_start();
ob_start();

# This file name
$this_file_name = basename(__FILE__, '.php');

# Add loader entry/load if !isset($load_number_exists)
if (Input::get('_controller_' . $this_file_name) == 'add_load' && !isset($load_number_exists)) {

  $validation = $validate->check($_POST, array(
    'broker_id' => array('required' => true),
    'load_number' => array('required' => true),
    'broker_name_number' => array('required' => true),
    'broker_email' => array(
      'required' => true,
      'min' => 6,
      'max' => 120,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
    'weight' => array('required' => true),
    'miles' => array('required' => true),
    'avg_diesel_price' => array('required' => true),
    'user_id' => array('required' => true)
  ));

  # If data is valid and we have file data
  if($validation->passed()) {

    # Check if we are receiving a valid file (only if draft_id is not coming)
    if (!Input::get('draft_id')) {
      
      if($_FILES["file"]["type"] == "application/pdf"){

        if ($_FILES["file"]["error"] > 0){

          Session::flash('loader_error', 'There was a problem with the rate confirmation file, please try again');
        }
      }
    }

    # Create equipment string
    $equipment = json_encode($_POST['equipment']);

    # Add loader entry
    $add_loader_entry = DB::getInstance()->query("INSERT INTO loader_entry (driver_id, user_id, added_by) VALUES (" . $_POST['driver_id'] . ", " . Input::get('user_id') . ", " . Input::get('added_by') . ")");

    # Add load after adding entry
    if ($add_loader_entry->count()) {

      $add_loader_entry_last_id = $add_loader_entry->last();
      
      $add_loader_load = DB::getInstance()->query("
        INSERT INTO loader_load (
          entry_id
          , draft_id
          , broker_id
          , load_number
          , broker_name_number
          , broker_email
          , line_haul
          , weight
          , miles
          , deadhead
          , avg_diesel_price
          , reference
          , commodity
          , equipment
          , notes
          , added_by
          , user_id) 
        VALUES (" . $add_loader_entry_last_id . "
          , '" . Input::get('draft_id') . "'
          , " . Input::get('broker_id') . "
          , '" . htmlentities(Input::get('load_number'), ENT_QUOTES) . "'
          , '" . htmlentities(Input::get('broker_name_number'), ENT_QUOTES) . "'
          , '" . Input::get('broker_email') . "'
          , '" . Input::get('line_haul') . "'
          , " . Input::get('weight') . "
          , '" . Input::get('miles') . "'
          , '" . Input::get('deadhead') . "'
          , '" . Input::get('avg_diesel_price') . "'
          , '" . htmlentities(Input::get('reference'), ENT_QUOTES) . "'
          , '" . htmlentities(Input::get('commodity'), ENT_QUOTES) . "'
          , '" . $equipment . "'
          , '" . htmlentities(Input::get('notes'), ENT_QUOTES) . "'
          , " . Input::get('added_by') . "
          , " . Input::get('user_id') . ")");

      $add_loader_load_last_id = $add_loader_load->last();

      # Mark draft as loaded
      $draft_loaded = DB::getInstance()->query("UPDATE loader_load_draft SET loaded = 1 WHERE id = " . Input::get('draft_id'));

      # Make name with rate confirmation label
      $name = 'rate-confirmation-' . $add_loader_entry_last_id . '-' . $add_loader_load_last_id . '.pdf';

      # Add file to table loader_file (file type 2 rate confirmation)
      $add_loader_file = DB::getInstance()->query("INSERT INTO loader_file (load_id, file_name, file_type, user_id) VALUES ('" . $add_loader_load_last_id . "', '" . $name . "', 2, " . $user->data()->id . ")");

      if ($add_loader_file->count()) {

        # If draft_id 
        if (Input::get('draft_id')) {

          // Move file from draft ratecon location
          rename($draft_rate_con_path . Input::get('draft_id') . '.pdf', $file_directory . $name);
        } else {

          // Upload file if data has been added to table
          move_uploaded_file($_FILES["file"]["tmp_name"], $file_directory.'_temp' . $user->data()->id);
          rename($file_directory.'_temp' . $user->data()->id, $file_directory . $name);
        }

        Session::flash('loader', 'Load data added successfully');
        Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/loader/load?load_id=' . $add_loader_load_last_id . '&rate_confirmation=1');
      }
    }
  } else {
    
    Session::flash('loader_error', $core_language[27]);
  }

}

# Update load
if (Input::get('_controller_' . $this_file_name) == 'update_load') {

  $validation = $validate->check($_POST, array(
    'driver_id' => array('required' => true),
    'broker_id' => array('required' => true),
    'load_number' => array('required' => true),
    'broker_name_number' => array('required' => true),
    'broker_email' => array(
      'required' => true,
      'min' => 6,
      'max' => 120,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
    'weight' => array('required' => true),
    'miles' => array('required' => true),
    'avg_diesel_price' => array('required' => true)
  ));

  if($validation->passed()) {

    # If driver_id is changing, update before updating loader_load
    if (Input::get('driver_id') != Input::get('entry_driver_id')) {
      
      $driver_update = DB::getInstance()->query("UPDATE loader_entry SET driver_id = " . Input::get('driver_id') . " WHERE data_id = " . Input::get('entry_id'));
      $driver_update_count = $driver_update->count();
    }
    
    $update = DB::getInstance()->query("UPDATE loader_load SET broker_id = " . Input::get('broker_id') . ", load_number = '" . htmlentities(Input::get('load_number'), ENT_QUOTES) . "', broker_name_number = '" . htmlentities(Input::get('broker_name_number'), ENT_QUOTES) . "', broker_email = '" . strtolower(Input::get('broker_email')) . "', line_haul = '" . Input::get('line_haul') . "', weight = " . Input::get('weight') . ", miles = '" . Input::get('miles') . "', deadhead = '" . Input::get('deadhead') . "', avg_diesel_price = '" . Input::get('avg_diesel_price') . "', reference = '" . htmlentities(Input::get('reference'), ENT_QUOTES) . "', commodity = '" . htmlentities(Input::get('commodity'), ENT_QUOTES) . "', notes = '" . htmlentities(Input::get('notes'), ENT_QUOTES) . "', user_id = '" . Input::get('user_id') . "' WHERE load_id = " . $_GET['load_id']);
    
    if ($update->count() && !$driver_update_count) {
      
      Session::flash('loader', 'Load\'s main data updated successfully');
      Redirect::to('load?load_id=' . $_GET['load_id']);
    } else {

      if (Input::get('driver_id') == Input::get('entry_driver_id')) {

        Session::flash('loader_error', 'Form sent with no changes, no updates saved to the database.');
      } elseif ($driver_update_count) {
        
        Session::flash('loader', 'Load\'s main data updated successfully');
        Redirect::to('load?load_id=' . $_GET['load_id']);
      }
    }
  } else {
    
    Session::flash('loader_error', $core_language[27]);
  }
}

# Delete load
if (Input::get('_controller_' . $this_file_name) == 'delete_load') {

  $update = DB::getInstance()->query("UPDATE loader_load SET load_number = '" . Input::get('load_number') . "-deleted', load_status = 1 WHERE load_id = " . $_GET['load_id']);

  if ($update->count()) {
    
    Session::flash('loader', 'Load deleted successfully');
    Redirect::to('load?load_id=' . $_GET['load_id']);
  } else {

    Session::flash('loader_error', $core_language[27]);
  }
}

# Add other charge
if (Input::get('_controller_' . $this_file_name) == 'add_other_charge') {

  $validation = $validate->check($_POST, array(
    'other_charge_id' => array('required' => true),
    'price' => array('required' => true)
  ));

  if($validation->passed()) {
    $insert = DB::getInstance()->query("INSERT INTO loader_load_other_charges (load_id, other_charge_id, price, user_id) VALUES (" . Input::get('load_id') . ", '" . Input::get('other_charge_id') . "', '" . Input::get('price') . "', " . $user->data()->id . ")");
    
    if ($insert->count()) {
      
      Session::flash('loader', 'Data added successfully');
      Redirect::to('load?load_id=' . $_GET['load_id']);
    } else {
      
      Session::flash('loader_error', $core_language[27]);
    }
  } else {

    Session::flash('loader_error', $core_language[27]);
  }
}

# Delete other charge
if (Input::get('_controller_' . $this_file_name) == 'delete_other_charge') {

  $delete = DB::getInstance()->query("DELETE FROM loader_load_other_charges WHERE data_id = " . Input::get('other_charge_id'));
  
  if ($delete->count()) {
    
    Session::flash('loader', 'Charge deleted successfully');
    Redirect::to('load?load_id=' . $_GET['load_id']);
  } else {
   
   Session::flash('loader_error', $core_language[27]);
  }
}

# Add staff note
if (Input::get('_controller_' . $this_file_name) == 'add_staff_note') {

  $validation = $validate->check($_POST, array(
    'note' => array('required' => true)
  ));

  if($validation->passed()) {

    Input::get('important_note') == 'on' ? $important_note = 1 : $important_note = 0;

    # Add to database
    # This is also used in controller: add_loader_load_status_change_notification, file: loader-load-quickpay-invoicing, controller: send_loader_load
    $insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, user_id) VALUES (" . $_GET['load_id'] . ", '" . htmlentities(Input::get('note'), ENT_QUOTES) . "', " . $important_note . ", " . $user->data()->id . ")");

    if ($insert->count()) {
      
      Session::flash('loader', 'Note added successfully');
      Redirect::to('load?load_id=' . $_GET['load_id']);
    } else {

      Session::flash('loader_error', $core_language[27]) ;
    }
  } else {
      Session::flash('loader_error', $core_language[27]);
  }
}
