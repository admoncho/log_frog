<?php
session_start();
ob_start();

# New user
if (Input::get('_controller_user') == 'add_user') {

  # controller call
  $validation = $validate->check($_POST, array(
    'email' => array(
      'required' => true,
      'min' => 6,
      'max' => 120,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/',
      'unique' => 'user'),
    'password' => array(
      'required' => true,
      'min' => 6),
    'password_again' => array(
      'required' => true,
      'matches' => 'password'),
    'name' => array(
      'required' => true,
      'min' => 1,
      'max' => 50),
    'last_name' => array(
      'required' => true,
      'min' => 2,
      'max' => 50)
  ));

  if($validation->passed()) {

    $bcrypt = new Bcrypt;

    $insert = DB::getInstance()->query("INSERT INTO user (email, password, name, last_name) VALUES ('" . Input::get('email') . "', '" . $bcrypt->hash(Input::get('password')) . "', '" . htmlentities(Input::get('name'), ENT_QUOTES) . "', '" . htmlentities(Input::get('last_name'), ENT_QUOTES) . "')");
    $insert_last_id = $insert->last();
      
    if ($insert->count()) {
      
      # User_settings entry
      $addSettings = DB::getInstance()->query("INSERT INTO user_settings (user_id, language_id) VALUES ($insert_last_id, 1)");

      Session::flash('user_control', $core_language[47]);

      if (Input::get('added_from') == 'internal') {
        
        Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/user/user?user_id=' . $insert_last_id);
      } else {
        
        # Auto login after successful account creation
        include $module_directory . 'controller/login.php';
      }
    } else {
      
      Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  } else {
    
    Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
  }
}

# Update user
if (Input::get('_controller_user') == 'update_user') {

  # Break dob appart
  list($dob_month, $dob_day, $dob_year) = explode('/', Input::get('dob'));

  # controller call
  $validation = $validate->check($_POST, array(
    'email' => array('required' => true),
    'name' => array(
      'required' => true,
      'min' => 1,
      'max' => 50),
    'last_name' => array(
      'required' => true,
      'min' => 2,
      'max' => 50), 
    'user_group' => array('required' => true)
  ));

  if($validation->passed()) {

    $update = DB::getInstance()->query("UPDATE user SET user_group = " . Input::get('user_group') . ", email = '" . Input::get('email') . "', name = '" . Input::get('name') . "', last_name = '" . Input::get('last_name') . "', dob = '" . $dob_year . "-" . $dob_month . "-" . $dob_day . "' WHERE id = " . $_GET['user_id']);
    
    if ($update->count()) {

      Session::flash('user_control', 'User account updated successfully');
      Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/user/user?user_id=' . $_GET['user_id']);
    } else {
      
      Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  } else {
    
    Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
  }
}

# Update user password
if (Input::get('_controller_user') == 'update_user_password') {

  # controller call
  $validation = $validate->check($_POST, array(
    'password' => array(
      'required' => true,
      'min' => 6),
    'password_again' => array(
      'required' => true,
      'matches' => 'password')
    
  ));

  if($validation->passed()) {

    $bcrypt = new Bcrypt;

    $update = DB::getInstance()->query("UPDATE user SET password = '" . $bcrypt->hash(Input::get('password')) . "' WHERE id = " . $_GET['user_id']);
      
    if ($update->count()) {

      Session::flash('user_control', 'User password updated successfully');
      Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/user/user?user_id=' . $_GET['user_id']);
    } else {
      
      Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  } else {
    
    Session::flash('user_control_error', 'Make sure the passwords match!');
  }
}

# New phone number
if (Input::get('_controller_user') == 'add_user_phone_number') {

  # controller call
  $validation = $validate->check($_POST, array(
    'phone_number' => array('required' => true)
  ));

  if($validation->passed()) {

    $insert = DB::getInstance()->query("INSERT INTO user_phone_number (user_id, phone_number) VALUES (" . $_GET['user_id'] . ", '" . Input::get('phone_number') . "')");

      
    if ($insert->count()) {
      
      Session::flash('user_control', 'Phone number added successfully');
      Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/user/user?user_id=' . $_GET['user_id']);
    } else {
      
      Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  } else {
    
    Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
  }
}

# Delete phone number
if (Input::get('_controller_user') == 'delete_user_phone_number') {

  # controller call
  $validation = $validate->check($_POST, array(
    'user_phone_number_id' => array('required' => true)
  ));

  if($validation->passed()) {

    $delete = DB::getInstance()->query("DELETE FROM user_phone_number WHERE id = " . Input::get('user_phone_number_id'));
      
    if ($delete->count()) {
      
      Session::flash('user_control', 'Phone number deleted successfully');
      Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/user/user?user_id=' . $_GET['user_id']);
    } else {
      
      Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  } else {
    
    Session::flash('user_control_error', $core_language[27] . ', ' . strtolower($core_language[28]));
  }
}
