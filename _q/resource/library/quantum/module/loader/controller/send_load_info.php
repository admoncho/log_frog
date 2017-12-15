<?php

$validation = $validate->check($_POST, array(
  'to' => array('required' => true)
));

if($validation->passed()) {

  // Get fields and clear white space
  $toField = preg_replace('/\s+/', '', Input::get('to'));
  $ccField = preg_replace('/\s+/', '', Input::get('cc'));
  // Create array
  $toArray = explode(",", $toField);
  $ccArray = explode(",", $ccField);
  // Count addresses per field
  $toCount = count($toArray);
  $ccCount = count($ccArray);
  // Address data
  $pick_up_info = Input::get('pick_up_info');
  $drop_off_info = Input::get('drop_off_info');

  Input::get('commodity') ? $commodity = '<p><b>Commodity:</b> '.Input::get('commodity').'</p>' : '';
  Input::get('notes') ? $notes = '<p><b>Notes:</b> '.Input::get('notes').'</p>' : '';

  // Check for valid to addresses
  for ($i=0; $i < $toCount; $i++) { 
    // check for valid emails
    if (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $toArray[$i])) {
      $invalidTo = 1;
    }
  }

  // Check for valid cc addresses ONLY if not empty
  if ($ccField) {
    for ($i=0; $i < $ccCount; $i++) { 
      // check for valid emails
      if (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $ccArray[$i])) {
        $invalidCc = 1;
      }
    }
  }

  // Go on if all addresses are valid
  if (!$invalidTo && !$invalidCc) {
    $to  = $toField;
    $cc  = $ccField;
    $subject = 'Logisticsfrog Load Information';
    $message  .= '
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
  <head>
      <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
      <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
      <title></title>
  </head>
  <body style="margin:0; padding:0; width:100%; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%;">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" style="border:0; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
      <tbody>
        <tr>
          <td align="center" style="border-collapse: collapse;">
            <!-- ROW LOGO -->
            <table cellpadding="0" cellspacing="0" border="0" width="560" style="border:0; border-collapse:collapse; background-color:#ffffff; border-radius:6px;">
              <tbody>
                <tr>
                  <td style="border-collapse:collapse; vertical-align:middle; text-align center; padding:20px;">
                    <!-- Headline Header -->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                      <tbody>
                        <tr><!-- logo -->
                          <td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 18px; letter-spacing: 0px; text-align: center;">  
                            <a href="" style="text-decoration: none;color:#59ab02;">
                              <h1>logisticsfrog.com <i class="fa fa-road"></i></h1>
                            </a>
                          </td>
                        </tr>
                        <tr><!-- spacer before the line -->
                          <td width="100%" height="20"></td>
                        </tr>
                        <tr><!-- line -->
                          <td width="100%" height="1" bgcolor="#d9d9d9"></td>
                        </tr>
                        <tr><!-- spacer after the line -->
                          <td width="100%" height="30"></td>
                        </tr>
                        <tr>
                          <td width="100%" style=" font-size: 14px; line-height: 24px; font-family:helvetica, Arial, sans-serif; text-align: left; color:#888888;">
                            <div style="float:left;width:50%;padding:0;margin:0;">
                              <div style="padding-right: 10px;">
                                <p style="color: #59ab02;"><b>'. $user_list_id_name[Input::get('driver_id')] . ' ' . $user_list_id_last_name[Input::get('driver_id')] .'</b></p>
                                <p style="color: #59ab02;"><b>' . $client_id_company_name[$load_client_id[1]] . '</b></p>
                                <p style="color: #59ab02;"><b>' . $broker_id_company_name[$load_broker_id[1]] . '</b></p>
                                '. $commodity .'
                                '. $notes .'
                              </div>
                            </div>
                            <div style="float:left;width:50%;padding:0;margin:0;">
                              <p><b>Price:</b> $ '. Input::get('line_haul') .'</p>
                              <p><b>Miles:</b> '.number_format(Input::get('miles'), 2).'</p>
                              <p><b>Per loaded mile: </b> $ '. number_format(str_replace(',', '', Input::get('line_haul')) / Input::get('miles'), 2) .'</p>
                              <p><b>Weight:</b> '. number_format(Input::get('weight'), 0) .' lbs</p>
                              <p><b>Deadhead:</b> '. Input::get('deadhead') .'</p>
                              <p><b>Reference: </b>'. Input::get('reference') .'</p>
                            </div>
                          </td>
                        </tr>
                        <tr>
                          <td width="100%" height="15"></td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- /Headline Header -->
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- /ROW LOGO -->
            <!-- Space -->
            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
              <tbody>
                <tr>
                  <td width="100%" height="30"></td>
                </tr>
              </tbody>
            </table>
            <!-- /Space -->
            <!-- ROW 6 IMGS -->
            <table cellpadding="0" cellspacing="0" border="0" width="560" style="border:0; border-collapse:collapse; background-color:#ffcc81; color: #fff; border-radius:6px; padding: 0 15px;">
              <tbody>
                <tr>
                  <td style="border-collapse:collapse; vertical-align:middle; text-align center; padding:20px;">
                    <!-- Headline Header -->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                      <tbody>
                        <tr><!-- spacing top -->
                          <td width="100%" height="20"></td>
                        </tr>
                        <tr><!-- title -->
                          <td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 18px; letter-spacing: 0px; text-align: center; color:#000;">   
                            <strong>Pick up Information</strong>
                          </td>
                        </tr>
                        '. $pick_up_info .'
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- Space -->
            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
              <tbody>
                <tr>
                  <td width="100%" height="30"></td>
                </tr>
              </tbody>
            </table>
            <!-- /Space -->
            <table cellpadding="0" cellspacing="0" border="0" width="560" style="border:0; border-collapse:collapse; background-color:#8de38d; border-radius:6px; ">
              <tbody>
                <tr>
                  <td style="border-collapse:collapse; vertical-align:middle; text-align center; padding:20px;">
                    <!-- Headline Header -->
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                      <tbody>
                        <tr><!-- spacing top -->
                          <td width="100%" height="20"></td>
                        </tr>
                        <tr><!-- title -->
                          <td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 18px; letter-spacing: 0px; text-align: center; color:#000;">   
                            <strong>Destination Information</strong>
                          </td>
                        </tr>
                        '. $drop_off_info .'
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>

            <!-- /ROW 6 IMGS -->
            <!-- Space -->
            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
              <tbody>
                <tr>
                  <td width="100%" height="30"></td>
                </tr>
              </tbody>
            </table>
            <!-- /Space -->
            
            <!-- Space -->
            <table width="100%" border="0" cellpadding="0" cellspacing="0" align="left" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
              <tbody>
                <tr>
                  <td width="100%" height="30"></td>
                </tr>
              </tbody>
            </table>
            <!-- /Space -->
            <!-- ROW FOOTER -->
            <table cellpadding="0" cellspacing="0" border="0" width="560" style="border:0; border-collapse:collapse; background-color:#ffffff; border-radius:6px;">
              <tbody>
                <tr>
                  <td style="border-collapse:collapse; vertical-align:middle; text-align center; padding:20px;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
                      <tbody>
                        <tr><!-- copyright -->
                          <td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 16px; text-align: center; line-height: 24px;">    
                            <center><a style="color:#59ab02;text-decoration:none;" href="">logisticsfrog.com</a></center>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
            <!-- /ROW FOOTER -->
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>';
                      
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    $headers .= 'From: ' . $user->data()->email . "\r\n";
    $headers .= 'Cc: ' . $cc . "\r\n";
    'Reply-To:'.$to."\r\n" .
    'X-Mailer: PHP/' . phpversion();

    //send email
    mail($to, $subject, $message, $headers);

    # Add note
    $insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, type, user_id) VALUES (" . $_GET['load_id'] . ", 'Load info sent to driver.', 0, 1, " . $user->data()->id . ")");

    Session::flash('loader', 'Email sent!');
    Redirect::to('load?load_id=' . $_GET['load_id']);
  }  
} else {
  Session::flash('loader_error', 'You must specify a "To" address.');
}
