<?php

# controller call
$validation = $validate->check($_POST, array(
  'language_id' => array('required' => true),
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
  $insert_last_id = DB::getInstance()->_lastId;
    
  if ($insert->count()) {
    
    # User_settings entry
    $addSettings = DB::getInstance()->query("INSERT INTO user_settings (user_id, language_id) VALUES ($insert_last_id, " . Input::get('language_id') . ")");

    Session::flash('create_user', $core_language[47]);

    # Auto login after successful account creation
    include $module_directory . 'controller/login.php';

  } else {
    Session::flash('create_user_error', $core_language[27] . ', ' . strtolower($core_language[28]));
  }
} else {
  Session::flash('create_user_error', $core_language[27] . ', ' . strtolower($core_language[28]));
}