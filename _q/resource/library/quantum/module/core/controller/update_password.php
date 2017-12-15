<?php

# controller call
$validation = $validate->check($_POST, array(
  'password' => array('required' => true),
  'password_1' => array(
    'required' => true,
    'min' => 6),
  'password_2' => array(
    'required' => true,
    'matches' => 'password_1')
));

if($validation->passed()) {
  $bcrypt = new Bcrypt;

  # Go on if current password matches
  if($bcrypt->verify(Input::get('password'), $user->data()->password)){

    $update = DB::getInstance()->query("UPDATE user SET password = '". $bcrypt->hash(Input::get('password_1')) ."' WHERE id = " . $user->data()->id);
    $update->count() ? Session::flash('update_password', $core_language[20] . ' ' . strtolower($core_language[29]) . ' ' . strtolower($core_language[30])) : Session::flash('update_password_error', $core_language[27]) ;
    $update->count() ? Redirect::to('account') : '' ;
  } else {
    Session::flash('update_password_error', $core_language[31]);
    Redirect::to('account?edit_password=1');
  }
} else {
  Session::flash('update_password_error', $core_language[32]);
}