<?php

# controller call
$validation = $validate->check($_POST, array(
  'email' => array(
    'required' => true,
    'min' => 6,
    'max' => 120,
    'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/',
    'unique' => 'user'),
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
  
  # Update
  $update = DB::getInstance()->query("UPDATE user SET name = '" . htmlentities(Input::get('name'), ENT_QUOTES) . "', last_name = '" . htmlentities(Input::get('last_name'), ENT_QUOTES) . "', email = '" . Input::get('email') . "' WHERE id = " . $user->data()->id);
  $update->count() ? Session::flash('update_personal_info', $core_language[26]) : Session::flash('update_personal_info_error', $core_language[27]) ;
  Redirect::to('account');
} else {
  Session::flash('update_personal_info_error', $core_language[27] . ', ' . strtolower($core_language[28]));
}