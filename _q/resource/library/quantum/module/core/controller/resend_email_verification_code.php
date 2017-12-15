<?php

$email_verification = mt_rand(10000, 99999); // 5 digit activation code 

$update = DB::getInstance()->query("UPDATE user_settings SET email_verification = " . $email_verification . " WHERE user_id = " . $user->data()->id);
  
if ($update->count()) {
  # Send activation link
  $mailer_to    = $user->data()->email;
  $mailer_subject = $core_language[44];
  $mailer_message = "<p>" . $core_language[54] . ":</p>
  <p>" . $email_verification . "</p>
  <p>" . $core_language[39] . ".</p>";
  
  $headers = "MIME-Version: 1.0" . "\r\n";
  $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
  $mailer_email = 'no-reply@'.$domain;
  $headers .= 'From: ' .$mailer_email. "\r\n";
  'Reply-To:'.$mailer_email."\r\n" .
  'X-Mailer: PHP/' . phpversion();

  mail($mailer_to, $mailer_subject, $mailer_message, $headers);

  # STEP 6: Session flash
  Session::flash('resend_email_verification_code', $core_language[55] . ' ' . $core_language[30] . '. ' . $core_language[56]);
} else {
  Session::flash('resend_email_verification_code_error', $core_language[27] . ', ' . strtolower($core_language[28]));
}