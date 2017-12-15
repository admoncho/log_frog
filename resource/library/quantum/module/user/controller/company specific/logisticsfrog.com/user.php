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
    'ein_number' => array('required' => true),
    'rate_id' => array('required' => true)
  ));

  if($validation->passed()) {

    Input::get('mailing_use_physical') == 'on' ? $mailing_use_physical = 1 : $mailing_use_physical = 0;
  
    $update = DB::getInstance()->query("UPDATE " . $this_file_name . " SET 
      company_name = '" . Input::get('company_name') . "', 
      mc_number = '" . Input::get('mc_number') . "', 
      us_dot_number = '" . Input::get('us_dot_number') . "', 
      ein_number = '" . Input::get('ein_number') . "', 
      phone_number_01 = '" . Input::get('phone_number_01') . "', 
      phone_number_02 = '" . Input::get('phone_number_02') . "', 
      address_line_1 = '" . Input::get('address_line_1') . "', 
      address_line_2 = '" . Input::get('address_line_2') . "', 
      city = '" . Input::get('city') . "', 
      state_id = '" . Input::get('state_id') . "', 
      zip_code = '" . Input::get('zip_code') . "', 
      mailing_use_physical = " . $mailing_use_physical . ", 
      billing_address_line_1 = '" . Input::get('billing_address_line_1') . "', 
      billing_address_line_2 = '" . Input::get('billing_address_line_2') . "', 
      billing_city = '" . Input::get('billing_city') . "', 
      billing_state_id = '" . Input::get('billing_state_id') . "', 
      billing_zip_code = '" . Input::get('billing_zip_code') . "', 
      invoice_color = '" . htmlentities(Input::get('invoice_color'), ENT_QUOTES) . "', 
      rate_id = " . Input::get('rate_id') . ", 
      status = '" . Input::get('status') . "' 

    WHERE data_id = " . $_GET['client_id']);

    $update->count() ? Session::flash($this_file_name, 'Client profile updated successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['client_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
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
    $add = DB::getInstance()->query("INSERT INTO " . $this_file_name . "_address (" . $this_file_name . "_id, address_type, line_1, line_2, line_3, city, state_id, zip_code, user_id) VALUES (" . $_GET[$this_file_name . '_id'] . ", " . Input::get('address_type') . ", '" . htmlentities(Input::get('line_1'), ENT_QUOTES) . "', '" . htmlentities(Input::get('line_2'), ENT_QUOTES) . "', '" . htmlentities(Input::get('line_3'), ENT_QUOTES) . "', '" . htmlentities(Input::get('city'), ENT_QUOTES) . "', " . Input::get('state_id') . ", '" . htmlentities(Input::get('zip_code'), ENT_QUOTES) . "', " . $user->data()->id . ")");
    $add->count() ? Session::flash($this_file_name, 'Address added successfully') . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name, $core_language[27]) ;
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

    $update = DB::getInstance()->query("UPDATE " . $this_file_name . "_address SET 
      address_type = " . Input::get('address_type') . ", 
      line_1 = '" . htmlentities(Input::get('line_1'), ENT_QUOTES) . "', 
      line_2 = '" . htmlentities(Input::get('line_2'), ENT_QUOTES) . "', 
      line_3 = '" . htmlentities(Input::get('line_3'), ENT_QUOTES) . "', 
      city = '" . htmlentities(Input::get('city'), ENT_QUOTES) . "', 
      state_id = '" . Input::get('state_id') . "', 
      zip_code = '" . htmlentities(Input::get('zip_code'), ENT_QUOTES) . "'
      WHERE data_id = " . $_GET['address_id']);

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
      
      # If data was added successfully AND Input::get('counter') is coming, create entry for loader_quickpay_invoice_counter
      $add = DB::getInstance()->query("INSERT INTO loader_quickpay_invoice_counter (broker_id, client_id, counter) VALUES (" . Input::get('broker_id') . ", " . Input::get('client_id') . ", '" . Input::get('counter') . "')");
      $add->count() ? Session::flash($this_file_name, 'association added successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['client_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
    } else {

      # If data was added successfully AND Input::get('counter') is NOT coming, flash success message
      $insert->count() ? Session::flash($this_file_name, 'association added successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
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
