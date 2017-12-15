<?php

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_dash = str_replace('_', '-', basename(__FILE__, '.php'));

# New item
if (Input::get('_controller_' . $this_file_name) == 'add_client') {

  $validation = $validate->check($_POST, array(
    'company_name' => array('required' => true, 'unique_company_name' => 'client'),
    'mc_number' => array('required' => true, 'unique_mc_number' => 'client'),
    'us_dot_number' => array('required' => true, 'unique_us_dot_number' => 'client'),
    'ein_number' => array('required' => true, 'unique_ein_number' => 'client')
  ));

  if ($validation->passed()) {
    $insert = DB::getInstance()->query("INSERT INTO client (company_name, mc_number, us_dot_number, ein_number) VALUES ('" . htmlentities(Input::get('company_name'), ENT_QUOTES) . "', '" . Input::get('mc_number') . "', '" . Input::get('us_dot_number') . "', '" . Input::get('ein_number') . "')");

    $lastInsertID = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS lastINSERTID FROM client");
    foreach ($lastInsertID->results() as $id) {
      $last_id = $id->lastINSERTID;
    }
    
    $insert->count() ? Session::flash($this_file_name, 'Client profile added successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $last_id) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  } else {
    Session::flash($this_file_name . '_error', 'Make sure the comany name, MC Number, US DOT Number and EIN Number are not taken');
  }
}

# Update item
if (Input::get('_controller_' . $this_file_name) == 'update_' . $this_file_name) {


  $validation = $validate->check($_POST, array(
    'company_name' => array('required' => true),
    'mc_number' => array('required' => true),
    'us_dot_number' => array('required' => true),
    'ein_number' => array('required' => true)
  ));

  if($validation->passed()) {

    # Check chr_t, if set, it should have 7 digits
    if (Input::get('chr_t') && strlen(Input::get('chr_t')) != 7) {
      
      $chr_t_error = 1;
    }

    if (!$chr_t_error) {
      
      # Break formation_date apart
      list($month, $day, $year) = explode('/', Input::get('formation_date'));
    
      $update = DB::getInstance()->query("UPDATE " . $this_file_name . " SET 
        company_name = '" . Input::get('company_name') . "', 
        mc_number = '" . Input::get('mc_number') . "', 
        us_dot_number = '" . Input::get('us_dot_number') . "', 
        ein_number = '" . Input::get('ein_number') . "', 
        chr_t = '" . Input::get('chr_t') . "', 
        main_contact = '" . htmlentities(Input::get('main_contact'), ENT_QUOTES) . "', 
        phone_number_01 = '" . Input::get('phone_number_01') . "', 
        phone_number_02 = '" . Input::get('phone_number_02') . "', 
        phone_number_03 = '" . Input::get('phone_number_03') . "', 
        invoice_color = '" . htmlentities(Input::get('invoice_color'), ENT_QUOTES) . "', 
        rate_id = '" . Input::get('rate_id') . "', 
        scac_code = '" . Input::get('scac_code') . "', 
        status = '" . Input::get('status') . "', 
        formation_date = '" . $year . '-' . $month . '-' . $day . "'

      WHERE data_id = " . $_GET['client_id']);

      $update->count() ? Session::flash($this_file_name, 'Client profile updated successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['client_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ; 
    } else {

      # chr_t error
      Session::flash($this_file_name . '_error', 'CHR T number must be 7 digits long!');  
    }
  } else {
    
    Session::flash($this_file_name . '_error', $core_language[27]);
  }
}

# Delete item
if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_' . $this_file_name) {

  $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . " WHERE data_id = " . $_GET['client_id']);
  $delete->count() ? Session::flash($this_file_name, 'Client deleted successfully') . Redirect::to('client') : Session::flash($this_file_name . '_error', $core_language[27]) ;
}

# Add address
if (Input::get('_controller_' . $this_file_name) == 'add_address') {

  $validation = $validate->check($_POST, array(
    'address_type' => array('required' => true),
    'line_1' => array('required' => true),
    'line_2' => array('required' => true),
    'city' => array('required' => true),
    'state_id' => array('required' => true),
    'zip_code' => array('required' => true)
  ));

  if($validation->passed()) {

    # Add to database
    $add = DB::getInstance()->query("
      INSERT INTO " . $this_file_name . "_address (
        " . $this_file_name . "_id, address_type, line_1, line_2, line_3, city, state_id, zip_code, user_id
      ) VALUES (
        " . $_GET[$this_file_name . '_id'] . "
        , " . Input::get('address_type') . "
        , '" . htmlentities(Input::get('line_1'), ENT_QUOTES) . "'
        , '" . htmlentities(Input::get('line_2'), ENT_QUOTES) . "'
        , '" . htmlentities(Input::get('line_3'), ENT_QUOTES) . "'
        , '" . htmlentities(Input::get('city'), ENT_QUOTES) . "'
        , " . Input::get('state_id') . "
        , '" . htmlentities(Input::get('zip_code'), ENT_QUOTES) . "'
        , " . $user->data()->id . "
      )
    ");

    if ($add->count()) {
      
      Session::flash($this_file_name, 'Address added successfully');
      Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']);
    } else {

      Session::flash($this_file_name, $core_language[27]);
    }
  } else {
    Session::flash($this_file_name, $core_language[27]);
    # Error redirects block the ability to populate fields with Input::get(''), to keep the data on fields so users don't have to re-enter the data,
    # pass values on $_GET.
    Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id'] . '&address_type=' . Input::get('address_type') . '&line_2=' . Input::get('line_2') . '&line_3=' . Input::get('line_3') . '&city=' . Input::get('city') . '&state_id=' . Input::get('state_id') . '&zip_code=' . Input::get('zip_code'));
  }
}

# Update address
if (Input::get('_controller_' . $this_file_name) == 'update_address') {

  $validation = $validate->check($_POST, array(
    'address_type' => array('required' => true),
    'line_1' => array('required' => true),
    'line_2' => array('required' => true),
    'city' => array('required' => true),
    'state_id' => array('required' => true),
    'zip_code' => array('required' => true)
  ));

  if($validation->passed()) {

    Input::get('mailing_use_physical') == 'on' ? $mailing_use_physical = 1 : $mailing_use_physical = 0 ;

    $update = DB::getInstance()->query("UPDATE " . $this_file_name . "_address SET 
      address_type = " . Input::get('address_type') . ", 
      line_1 = '" . htmlentities(Input::get('line_1'), ENT_QUOTES) . "', 
      line_2 = '" . htmlentities(Input::get('line_2'), ENT_QUOTES) . "', 
      line_3 = '" . htmlentities(Input::get('line_3'), ENT_QUOTES) . "', 
      city = '" . htmlentities(Input::get('city'), ENT_QUOTES) . "', 
      state_id = '" . Input::get('state_id') . "', 
      zip_code = '" . htmlentities(Input::get('zip_code'), ENT_QUOTES) . "',
      mailing_use_physical = " . $mailing_use_physical . "
      WHERE data_id = " . $_POST['address_id']);

    $update->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name, $core_language[16]) ;
  } else {
    Session::flash($this_file_name . '_error', $core_language[27]);
  }
}

# Delete address
if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_address') {

  # We need to count the number of entries for this item, if the count is 1,
  # then this item needs to be set as inactive (ONLY IF CURRENT STATUS IS ACTIVE).
  if (${$this_file_name . '_address_count'} === 1 && ${$this_file_name . '_status'}[1] == 1) {
    # Set item as inactive
    $update = DB::getInstance()->query("UPDATE " . $this_file_name . " SET status = 0 WHERE data_id = " . $_GET[$this_file_name . '_id']);

    if ($update->count()) {
      # Go on if update was successfull
      $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_address WHERE data_id = " . $_GET['address_id']);
      $delete->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
    } else {
      # Else, error
      Session::flash($this_file_name . '_error', 'There was an error when trying to set this client as inactive, please try again.');
    }
  } else {
    # Just delete
    $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_address WHERE data_id = " . $_GET['address_id']);
    $delete->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  }
}

# Add broker assoc
if (Input::get('_controller_' . $this_file_name) == 'add_broker_assoc') {

  # Add if there are no replicas (replicas checked on db/client.php)
  if (!$client_broker_assoc_count) {
    
    $insert = DB::getInstance()->query("INSERT INTO client_broker_assoc (client_id, broker_id, quickpay_service_fee_id) VALUES (" . Input::get('client_id') . ", " . Input::get('broker_id') . ", " . Input::get('quickpay_service_fee_id') . ")");

    if ($insert->count() && !isset($loader_quickpay_invoice_counter_count)) {
      
      # If data was added successfully AND there is no $loader_quickpay_invoice_counter_count, create entry for loader_quickpay_invoice_counter
      $add = DB::getInstance()->query("INSERT INTO loader_quickpay_invoice_counter (broker_id, client_id, counter) VALUES (" . Input::get('broker_id') . ", " . Input::get('client_id') . ", '" . Input::get('counter') . "')");
      $add->count() ? Session::flash($this_file_name, 'association added successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['client_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
    } else {

      # If data was added successfully AND Input::get('counter') is NOT coming, flash success message
      $insert->count() ? Session::flash($this_file_name, 'association added successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['client_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
    }
  } else {
    
    Session::flash($this_file_name . '_error', $core_language[27]) ;    
    Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['client_id']);
  }
}

# Delete broker assoc
if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_broker_assoc') {
  
  $delete = DB::getInstance()->query("DELETE FROM client_broker_assoc WHERE data_id = " . $_GET['broker_assoc_id']);
  $delete->count() ? Session::flash($this_file_name, 'Item deleted successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
}

# Add client user
if (Input::get('_controller_' . $this_file_name) == 'add_client_user') {

  # Send back if adding a driver without a manager
  if (Input::get('user_type') == 2 && !Input::get('user_manager')) {
    
    Session::flash($this_file_name . '_error', 'You can\'t add a driver without a manager');
    Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']);
  }

  $validation = $validate->check($_POST, array(
    'user_id' => array('required' => true),
    'user_type' => array('required' => true)
  ));

  if ($validation->passed()) {

    # Set 0 back for owner value "9"
    Input::get('user_type') == '9' ? $user_type = 0 : $user_type = Input::get('user_type');

    $insert = DB::getInstance()->query("INSERT INTO client_user (user_id, client_id, user_type, user_manager) VALUES (" . Input::get('user_id') . ", " . $_GET['client_id'] . ", " . $user_type . ", '" . Input::get('user_manager') . "')"); 
    $insert->count() ? Session::flash($this_file_name, 'User added successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  } else {
    
    Session::flash($this_file_name . '_error', 'The user and user type are required.');
  }
}

# Delete client user
if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_client_user') {
  
  /*$delete = DB::getInstance()->query("DELETE FROM client_broker_assoc WHERE data_id = " . $_GET['broker_assoc_id']);
  $delete->count() ? Session::flash($this_file_name, 'Item deleted successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;*/

  $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_user WHERE user_id = " . $_GET[$this_file_name . '_user_id']);

  # Check if we need to deactivate this client's profile upon user deletion
  if ($delete->count() && isset($deactivate_client)) {
    
    # Deactivate client profile
    $update = DB::getInstance()->query("UPDATE " . $this_file_name . " SET status = 0 WHERE data_id = " . $_GET[$this_file_name . '_id']);
    $update->count() ? Session::flash($this_file_name, 'User removed successfully, client account deactivated successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  }

  $delete->count() ? Session::flash($this_file_name, 'User removed successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
}

# Add factoring company assoc
if (Input::get('_controller_' . $this_file_name) == 'add_client_factoring_company') {

  # Set counters in case they come empty
  Input::get('counter') ? $counter = Input::get('counter') : $counter = 1;
  Input::get('invoice_counter') ? $invoice_counter = Input::get('invoice_counter') : $invoice_counter = 1;

  if (!isset($_FILES["invoice_background"])) {
    
    # Add without image, soar file not required by this factoring company
    # No file handling errors

    $validation = $validate->check($_POST, array(
      'factoring_company_id' => array('required' => true),
      'main' => array('required' => true),
      'alt' => array('required' => true)
    ));

    if($validation->passed()) {

      # Add to database
      $insert = DB::getInstance()->query("INSERT INTO factoring_company_client_assoc (factoring_company_id, client_id, main, alt, counter, invoice_counter, user_id) VALUES (" . Input::get('factoring_company_id') . ", " . $_GET['client_id'] . ", " . Input::get('main') . ", " . Input::get('alt') . ", " . $counter . ", " . $invoice_counter . ", " . $user->data()->id . ")");
      
      $last_insert_id = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS last_insert_id FROM factoring_company_client_assoc");
      foreach ($last_insert_id->results() as $id) {
        $last_id = $id->last_insert_id;
      }

      if ($insert->count()) {

        Session::flash($this_file_name, 'Factoring company association added successfully');
        Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']);
      }
    } else {
      Session::flash($this_file_name . '_error', $core_language[27]);
    }
  } else {

    # Check for valid file types and size (less than 7,000,000 bytes)
    if(($_FILES["invoice_background"]["type"] == "image/jpg" || $_FILES["invoice_background"]["type"] == "image/jpeg") && $_FILES["invoice_background"]["size"] < 7000000) {
      
      # If file handling errors
      if ($_FILES["invoice_background"]["error"] > 0){

        # Display error
        Session::flash($this_file_name . '_error', 'There was an error uploading the file');
      } else {

        # No file handling errors

        $validation = $validate->check($_POST, array(
          'factoring_company_id' => array('required' => true),
          'main' => array('required' => true),
          'alt' => array('required' => true)
        ));

        if($validation->passed()) {

          # Add to database
          $insert = DB::getInstance()->query("
            
            INSERT INTO 
            factoring_company_client_assoc (factoring_company_id, client_id, main, alt, counter, invoice_counter, user_id) 
            VALUES (
              " . Input::get('factoring_company_id') . ", 
              " . $_GET['client_id'] . ", 
              " . Input::get('main') . ", 
              " . Input::get('alt') . ", 
              " . $counter . ", 
              " . $invoice_counter . ", 
              " . $user->data()->id . "
            )
          ");

          $last_insert_id = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS last_insert_id FROM factoring_company_client_assoc");
          foreach ($last_insert_id->results() as $id) {
            $last_id = $id->last_insert_id;
          }

          if ($insert->count()) {
            
            # file_name
            $file_name = $last_id . '.jpg';

            # Upload image if data has been added to table
            move_uploaded_file($_FILES["invoice_background"]["tmp_name"], $schedule_bg_dir . $file_name);

            Session::flash($this_file_name, 'Factoring company association added successfully');
            Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']);
          }
        } else {
          Session::flash($this_file_name . '_error', $core_language[27]);
        }
      }
    }
  }
}
