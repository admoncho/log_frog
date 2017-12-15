<?php

# controller call
$validation = $validate->check($_POST, array(
  'email_verification' => array('required' => true)
));

if($validation->passed()) {

	# Pass only if code matches
	if ($settings_email_verification == Input::get('email_verification')) {
		
		# Update
	  $update = DB::getInstance()->query("UPDATE user_settings SET email_verification = 0 WHERE user_id = " . $user->data()->id);
	  $update->count() ? Session::flash('verify_email_address', $core_language[52] . ' ' . $core_language[30]) : Session::flash('verify_email_address_error', $core_language[27]) ;
	  Redirect::to('account');
	} else {
		Session::flash('verify_email_address_error', $settings_language_id == 1 ? ($core_language[53] . ' ' . strtolower($core_language[44])) : ($core_language[44] . ' ' . strtolower($core_language[53])));
	}
} else {
  Session::flash('verify_email_address_error', $core_language[27] . ', ' . strtolower($core_language[28]));
}