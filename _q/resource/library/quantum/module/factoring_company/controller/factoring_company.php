<?php

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_dash = str_replace('_', '-', basename(__FILE__, '.php'));

# New item
if (Input::get('_controller_' . $this_file_name) == 'add_factoring_company') {

  $validation = $validate->check($_POST, array(
    'name' => array('required' => true),
    'uri' => array('required' => true),
    'invoicing_email' => array(
      'required' => true,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
    'phone_number_01' => array('required' => true),
    'batch_schedule' => array('required' => true)
  ));

  if($validation->passed()) {

    # This field brings a 2 instead of 0 for validation purposes, set right value on var
    Input::get('requires_soar') == 1 ? $requires_soar = 1 : $requires_soar = 0 ;

    # Add to database
    $add = DB::getInstance()->query("INSERT INTO " . $this_file_name . " (name, uri, invoicing_email, phone_number_01, fax, batch_schedule, requires_soar, user_id) VALUES ('" . htmlentities(Input::get('name'), ENT_QUOTES) . "', '" . htmlentities(Input::get('uri'), ENT_QUOTES) . "', '" . Input::get('invoicing_email') . "', '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "', '" . htmlentities(Input::get('fax'), ENT_QUOTES) . "', " . Input::get('batch_schedule') . ", " . $requires_soar . ", " . $user->data()->id . ")");

    $lastInsertID = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS lastINSERTID FROM " . $this_file_name);
    foreach ($lastInsertID->results() as $id) {
      $last_id = $id->lastINSERTID;
    }

    $add->count() ? Session::flash($this_file_name, 'Factoring company added successfully') . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $last_id) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  } else {
    Session::flash($this_file_name . '_error', $core_language[27]);
  }
}

# Update item
if (Input::get('_controller_' . $this_file_name) == 'update') {

  $validation = $validate->check($_POST, array(
    'name' => array('required' => true),
    'uri' => array('required' => true),
    'invoicing_email' => array(
      'required' => true,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
    'phone_number_01' => array('required' => true),
    'batch_schedule' => array('required' => true),
    'requires_soar' => array('required' => true)
  ));

  if($validation->passed()) {

    # This field brings a 2 instead of 0 for validation purposes, set right value on var
    Input::get('requires_soar') == 1 ? $requires_soar = 1 : $requires_soar = 0 ;

    # Update to database
    $update = DB::getInstance()->query("UPDATE " . $this_file_name . " SET 
      name = '" . htmlentities(Input::get('name'), ENT_QUOTES) . "', 
      uri = '" . htmlentities(Input::get('uri'), ENT_QUOTES) . "', 
      invoicing_email = '" . htmlentities(Input::get('invoicing_email'), ENT_QUOTES) . "', 
      phone_number_01 = '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "', 
      fax = '" . htmlentities(Input::get('fax'), ENT_QUOTES) . "', 
      batch_schedule = " . Input::get('batch_schedule') . ", 
      requires_soar = " . $requires_soar . ", 
      open_hour = '" . Input::get('open_hour') . "', 
      close_hour = '" . Input::get('close_hour') . "', 
      time_zone = '" . htmlentities(Input::get('time_zone'), ENT_QUOTES) . "', 
      status = " . Input::get('status') . "
      WHERE data_id = " . $_GET[$this_file_name . '_id']);

    $update->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  } else {
    Session::flash($this_file_name . '_error', $core_language[27]);
  }
}

# Add contact
if (Input::get('_controller_' . $this_file_name) == 'add_contact') {

  $validation = $validate->check($_POST, array(
    'name' => array('required' => true),
    'last_name' => array('required' => true),
    'email' => array(
      'required' => true,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
    'phone_number_01' => array('required' => true)
  ));

  if($validation->passed()) {

    # Add to database
    $add = DB::getInstance()->query("INSERT INTO " . $this_file_name . "_contact (" . $this_file_name . "_id, name, last_name, title, email, phone_number_01) VALUES (" . $_GET[$this_file_name . '_id'] . ", '" . htmlentities(Input::get('name'), ENT_QUOTES) . "', '" . htmlentities(Input::get('last_name'), ENT_QUOTES) . "', '" . htmlentities(Input::get('title'), ENT_QUOTES) . "', '" . Input::get('email') . "', '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "')");
    $add->count() ? Session::flash($this_file_name, 'Factoring company contact added successfully') . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  } else {
    Session::flash($this_file_name . '_error', $core_language[27]);
    # Error redirects block the ability to populate fields with Input::get(''), to keep the data on fields so users don't have to re-enter the data,
    # pass values on $_GET.
    Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id'] . '&name=' . Input::get('name') . '&last_name=' . Input::get('last_name') . '&title=' . Input::get('title') . '&email=' . Input::get('email') . '&phone_number_01=' . Input::get('phone_number_01'));
  }
}

# Edit contact
if (Input::get('_controller_' . $this_file_name) == 'update_contact') { 
  $validation = $validate->check($_POST, array(
    'name' => array('required' => true),
    'last_name' => array('required' => true),
    'email' => array(
      'required' => true,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
    'phone_number_01' => array('required' => true)
  ));

  if($validation->passed()) {

    # Update to database
    $update = DB::getInstance()->query("UPDATE " . $this_file_name . "_contact SET 
      name = '" . htmlentities(Input::get('name'), ENT_QUOTES) . "', 
      last_name = '" . htmlentities(Input::get('last_name'), ENT_QUOTES) . "', 
      title = '" . htmlentities(Input::get('title'), ENT_QUOTES) . "', 
      email = '" . htmlentities(Input::get('email'), ENT_QUOTES) . "', 
      phone_number_01 = '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "'
      WHERE data_id = " . Input::get('contact_data_id'));

    $update->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_contact_error', $core_language[27]) ;
  } else {
    Session::flash($this_file_name . '_contact_error', $core_language[27]);
  }
}

# Delete contact
if (Input::get('_controller_' . $this_file_name) == 'delete_contact') {

  # We need to count the number of entries for this factoring company, if the count is 1,
  # then this factoring company needs to be set as inactive (ONLY IF CURRENT STATUS IS ACTIVE).
  if (${$this_file_name . '_contact_count'} === 1 && ${$this_file_name . '_status'}[1] == 1) {

    # Set factoring company as inactive
    $update = DB::getInstance()->query("UPDATE ' . $this_file_name . ' SET status = 0 WHERE data_id = " . $_GET[$this_file_name . '_id']);

    if ($update->count()) {
      # Go on if update was successfull
      $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_contact WHERE data_id = " . $_GET['contact_id']);
      $delete->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[75]) ;
    } else {
      # Else, error
      Session::flash($this_file_name . '_error', 'There was an error when trying to set this factoring company inactive, please try again.');
    }
  } else {
    # Just delete
    $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_contact WHERE data_id = " . $_GET['contact_id']);
    $delete->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[75]) ;
  }
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
    $add->count() ? Session::flash($this_file_name, 'Factoring company address added successfully') . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name, $core_language[27]) ;
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

  # We need to count the number of entries for this factoring company, if the count is 1,
  # then this factoring company needs to be set as inactive (ONLY IF CURRENT STATUS IS ACTIVE).
  if (${$this_file_name . '_address_count'} === 1 && ${$this_file_name . '_status'}[1] == 1) {
    # Set factoring company as inactive
    $update = DB::getInstance()->query("UPDATE " . $this_file_name . " SET status = 0 WHERE data_id = " . $_GET[$this_file_name . '_id']);

    if ($update->count()) {
      # Go on if update was successfull
      $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_address WHERE data_id = " . $_GET['address_id']);
      $delete->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
    } else {
      # Else, error
      Session::flash($this_file_name . '_error', 'There was an error when trying to set this factoring company as inactive, please try again.');
    }
  } else {
    # Just delete
    $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_address WHERE data_id = " . $_GET['address_id']);
    $delete->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
  }
}

# Add service fee
if (Input::get('_controller_' . $this_file_name) == 'add_service_fee') {

  $validation = $validate->check($_POST, array(
    'method_id' => array('required' => true)
  ));

  if($validation->passed()) {

    # Add to database
    $add = DB::getInstance()->query("INSERT INTO " . $this_file_name . "_service_fee (" . $this_file_name . "_id, fee, method_id, number_of_days, user_id) VALUES (" . $_GET[$this_file_name . '_id'] . ", '" . Input::get('fee') . "', " . Input::get('method_id') . ", " . Input::get('number_of_days') . ", " . $user->data()->id . ")");
    $add->count() ? Session::flash($this_file_name, 'Service fee added successfully') . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[75]) ;
  } else {
      Session::flash($this_file_name . '_error', $core_language[75]);
  }
}

# Update service fee
if (Input::get('_controller_' . $this_file_name) == 'update_service_fee') {

  $validation = $validate->check($_POST, array(
    'fee' => array('required' => true),
    'method_id' => array('required' => true)
  ));

  if($validation->passed()) {

    $update = DB::getInstance()->query("UPDATE " . $this_file_name . "_service_fee SET fee = '" . Input::get('fee') . "', method_id = " . Input::get('method_id') . ", number_of_days = " . Input::get('number_of_days') . " WHERE data_id = " . Input::get('data_id'));
    $update->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to('factoring-company?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[75]) ;
  } else {
    Session::flash($this_file_name . '_error', $core_language[75]);
  }
}

# Delete service fee
if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_service_fee') {

  # We need to count the number of entries for this factoring company, if the count is 1,
  # then this factoring company needs to be set as inactive (ONLY IF CURRENT STATUS IS ACTIVE).
  if (${$this_file_name . '_address_count'} === 1 && ${$this_file_name . '_status'}[1] == 1) {
    
    # Set factoring company as inactive
    $update = DB::getInstance()->query("UPDATE " . $this_file_name . " SET status = 0 WHERE data_id = " . $_GET[$this_file_name . '_id']);

    if ($update->count()) {
      
      # Go on if update was successfull
      $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_service_fee WHERE data_id = " . $_GET['service_fee_id']);
      $delete->count() ? Session::flash($this_file_name, $core_language[93]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[75]) ;
    } else {
      # Else, error
      Session::flash($this_file_name . '_error', $core_language[75]);
    }
  } else {
    # Just delete
    $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . "_service_fee WHERE data_id = " . $_GET['service_fee_id']);
    $delete->count() ? Session::flash($this_file_name, $core_language[93]) . Redirect::to($this_file_name_dash . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[75]) ;
  }
}
