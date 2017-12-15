<?php

# controller call
$validation = $validate->check($_POST, array(
  'language_id' => array('required' => true),
  'nav' => array('required' => true),
  'theme_id' => array('required' => true)
));

if($validation->passed()) {
  
  # Update
  $update = DB::getInstance()->query("UPDATE user_settings SET theme_id = " . Input::get('theme_id') . ", nav = " . Input::get('nav') . ", language_id = " . Input::get('language_id') . " WHERE user_id = " . $user->data()->id);
  $update->count() ? Session::flash('update_quantum_settings', $core_language[26]) : Session::flash('update_quantum_settings_error', $core_language[27]) ;
  Redirect::to('account');
} else {
  Session::flash('update_quantum_settings_error', $core_language[27] . ', ' . strtolower($core_language[28]));
}