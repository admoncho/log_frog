<?php

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_dash = str_replace('_', '-', basename(__FILE__, '.php'));

# New item
if (Input::get('_controller_' . $this_file_name) == 'add_broker') {

  if (isset($broker_duplicate_entry)) {
    
    # THIS FLASH IS NOT WORKING
    Session::flash($this_file_name, 'Broker already exists');
    Redirect::to('broker');
  }

  $validation = $validate->check($_POST, array(
    'company_name' => array('required' => true),
    'phone_number_01' => array('required' => true),
    'quickpay' => array('required' => true)
  ));

  if($validation->passed()) {

    # Set quickpay
    Input::get('quickpay') == 1 ? $quickpay = 1 : $quickpay = 0;

    # Add to database
    $insert = DB::getInstance()->query("INSERT INTO broker (company_name, phone_number_01, quickpay, user_id) VALUES ('" . htmlentities(Input::get('company_name'), ENT_QUOTES) . "', '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "', " . $quickpay . ", " . $user->data()->id . ")");

    if ($insert->count()) {
      
      Session::flash($this_file_name, 'Broker added successfully');
      Redirect::to('broker?broker_id=' . $insert->last());
    } else {

      Session::flash($this_file_name . '_error', $core_language[27]);
    }
  } else {
      Session::flash($this_file_name . '_error', $core_language[27]);
  }
}

# Update item
if (Input::get('_controller_' . $this_file_name) == 'update_' . $this_file_name) {

  $validation = $validate->check($_POST, array(
      'company_name' => array('required' => true),
      'phone_number_01' => array('required' => true),
      'quickpay' => array('required' => true)
  ));

  if($validation->passed()) {

    # Set quickpay
    Input::get('quickpay') == 1 ? $quickpay = 1 : $quickpay = 0;
    
    # Set values for inputs that are required if quickpay is 1
    Input::get('quickpay') == 1 ? $quickpay_email = Input::get('quickpay_email') : $quickpay_email = '';
    Input::get('quickpay') == 1 ? $accounts_payable_number = Input::get('accounts_payable_number') : $accounts_payable_number = '';
    // Input::get('quickpay') == 1 ? $quickpay_service_charge_percentage = Input::get('quickpay_service_charge_percentage') : $quickpay_service_charge_percentage = '';
    
    # Set status
    Input::get('status') == 1 ? $status = 1 : (Input::get('status') == 2 ? $status = 0 : $status = 2);

    # Set fax number
    # When deleting all numbers from a from fax, it will display like so: (___) ___-____
    Input::get('fax_number') == '(___) ___-____' ? $fax_number = '' : $fax_number = Input::get('fax_number');

    # Do quickpay checks
    if ($quickpay == 1 && (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $quickpay_email) || $accounts_payable_number == '')) {
      # Error, these values are required for quickpay enabled brokers
      Session::flash($this_file_name . '_error', 'For quickpay enabled brokers, the fields quickpay email and accounts payable number are required.');
    } else {
      # Update to database
      $update = DB::getInstance()->query("UPDATE broker SET 
        company_name = '" . htmlentities(Input::get('company_name'), ENT_QUOTES) . "', 
        phone_number_01 = '" . htmlentities(Input::get('phone_number_01'), ENT_QUOTES) . "', 
        accounts_payable_number = '" . htmlentities($accounts_payable_number, ENT_QUOTES) . "', 
        fax_number = '" . htmlentities($fax_number, ENT_QUOTES) . "', 
        quickpay = " . $quickpay . ", 
        quickpay_email = '" . $quickpay_email . "', 
        status = " . $status . ",
        do_not_use_reason = '" . htmlentities(Input::get('do_not_use_reason'), ENT_QUOTES) . "'
        WHERE data_id = " . $_GET['broker_id']);

      $update->count() ? Session::flash($this_file_name, 'Broker updated successfully') . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET['broker_id']) : Session::flash($this_file_name . '_error', $core_language[27]) ;
    }
  } else {
      Session::flash($this_file_name . '_error', $core_language[27]);
  }
}

# Delete item
if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_' . $this_file_name) {

  $delete = DB::getInstance()->query("DELETE FROM " . $this_file_name . " WHERE data_id = " . $_GET['broker_id']);
  $delete->count() ? Session::flash($this_file_name, 'Broker deleted successfully') . Redirect::to($this_file_name) : Session::flash($this_file_name . '_error', $core_language[27]) ;
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
      Session::flash($this_file_name . '_error', 'There was an error when trying to set this broker as inactive, please try again.');
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
    $add = DB::getInstance()->query("INSERT INTO " . $this_file_name . "_quickpay_service_fee (" . $this_file_name . "_id, fee, method_id, number_of_days, user_id) VALUES (" . $_GET[$this_file_name . '_id'] . ", '" . Input::get('fee') . "', " . Input::get('method_id') . ", " . Input::get('number_of_days') . ", " . $user->data()->id . ")");
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

    $update = DB::getInstance()->query("UPDATE broker_quickpay_service_fee SET fee = '" . Input::get('fee') . "', method_id = " . Input::get('method_id') . ", number_of_days = " . Input::get('number_of_days') . " WHERE data_id = " . Input::get('data_id'));
    $update->count() ? Session::flash($this_file_name, $core_language[26]) . Redirect::to($this_file_name . '?' . $this_file_name . '_id=' . $_GET[$this_file_name . '_id']) : Session::flash($this_file_name . '_error', $core_language[75]) ;
  } else {
    Session::flash($this_file_name . '_error', $core_language[75]);
  }
}

# Delete service fee
if (isset($_GET['_controller_' . $this_file_name]) && $_GET['_controller_' . $this_file_name] == 'delete_service_fee') {

  # We need to count the number of entries for this item, if the count is 1,
  # then this item needs to be set as inactive (ONLY IF CURRENT STATUS IS ACTIVE).
  if (${$this_file_name . '_address_count'} === 1 && ${$this_file_name . '_status'}[1] == 1) {
    
    # Set item as inactive
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
