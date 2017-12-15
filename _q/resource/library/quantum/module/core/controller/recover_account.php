<?php
session_start();
ob_start();

# Email is only sent on step 1
if (Input::get('email')) {

  $validation = $validate->check($_POST, array(
    'email' => array(
      'required' => true,
      'min' => 6,
      'max' => 120,
      'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'
    )
  ));

  if($validation->passed()) {
    # Step 1
    
    # Check if email sent belongs to an account
    $check = DB::getInstance()->query("SELECT * FROM user WHERE email = '" . Input::get('email') . "'");

    # Go on if email is found
    if ($check->count()) {

      # Get user data
      foreach ($check->results() as $check_data) {
        $user_id = $check_data->id;
      }

      # Create recovery numeric string
      $recovery = mt_rand(10000, 99999);

      # Update table user_settings' recover field with recovery numeric string
      $add_recover_string = DB::getInstance()->query("UPDATE user_settings SET recover = " . $recovery . " WHERE user_id = $user_id");

      # Go on if data was saved successfully
      if ($add_recover_string->count()) {
        
        # Send recovery email

        # SPL-compatible autoloader for PHPMailer
        require(LIBRARY_PATH . "/PHPMailer/PHPMailerAutoload.php");
        
        # Create new PHPMailer instance
        $mail = new PHPMailer;

        # Set email format to HTML
        $mail->isHTML(true);

        # From address
        $mail->From = 'no-reply@'. $domain;

        # EMail title
        $mail->FromName = $domain;
        
        # Add a recipient
        $mail->AddAddress(Input::get('email'));

        $mail->Subject = $core_language[33] . ' ' . strtolower($core_language[34]);
        $mail->Body    = $mail->Body    = $core_language[35] . ' ' . strtolower($core_language[36]) . ',
        <p>' . $core_language[37] . ' ' . $domain . '</p>
        <p>' . $core_language[38] . '.</p>
        <p><a href="http://' . $_SERVER['HTTP_HOST'] . '/recover-account?e=' . Input::get('email') . '&r=' . $recovery . '&language_id=' . $_GET['language_id'] . '">' . $core_language[33] . ' ' . strtolower($core_language[34]) . '</a></p>
        <p>' . $core_language[39] . '.</p>
        <p>Gracias!</p>';
        
        $mail->AltBody = '';

        if(!$mail->send()) {

          # Error
          Session::flash('recover_account_error', $core_language[27] . ' ' . strtolower($core_language[28])) ;
        } else {

          # Success
          Session::flash('recover_account', $core_language[40]) ;
        }
      }
    } else {

      # Error
      Session::flash('recover_account_error', $core_language[41] . ', ' . $core_language[28] . '.') ;
    }
  } else {
    
    # Error
    Session::flash('recover_account_error', $core_language[27] . ' ' . strtolower($core_language[28])) ;
  }
} elseif (Input::get('password')) {
  # password is only sent on step 2

  # Step 2

  # Validate class
  $validate = new Validate();

  # controller call
  $validation = $validate->check($_POST, array(
    'password' => array(
      'required' => true,
      'min' => 6),
    'repeat_password' => array(
      'required' => true,
      'matches' => 'password')
  ));

  if($validation->passed()) {

    # Bcrypt class
    $bcrypt = new Bcrypt;

    # Update password
    $update_password = DB::getInstance()->query("UPDATE user SET password = '" . $bcrypt->hash(Input::get('password')) . "' WHERE id = " . Input::get('user_id'));

    # Go on if password was updated
    if ($update_password->count()) {
      
      # Remove recovery code
      $update_settings = DB::getInstance()->query("UPDATE user_settings SET recover = '' WHERE user_id = " . $user_id);

      # Session flash
      Session::flash('recover_account', $core_language[42]);
      Redirect::to($_SESSION['HtmlDelimiter'] . 'login');
    } else {

      # Session flash
      Session::flash('recover_account_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  } else {

    # Session flash
    Session::flash('recover_account_error', $core_language[43] . '. Make sure they are at least 6 characters long.');
  }
} elseif (!Input::get('email') && !Input::get('password')) {

  if ($_GET['r']) {
    
    # Session flash if sending second step form empty ($_GET['r'] present)
    Session::flash('recover_account_error', 'The password fields cannot be empty');
  } else {

    # Session flash if sending first step form empty
    Session::flash('recover_account_error', 'The email field cannot be empty');
  }
}
