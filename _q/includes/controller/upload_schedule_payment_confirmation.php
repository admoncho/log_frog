<?php

# Check for valid file types and size (less than 7,000,000 bytes)
if(($_FILES["payment_confirmation_file"]["type"] == "application/pdf") && $_FILES["payment_confirmation_file"]["size"] < 7000000) {
  
  # If file handling errors
  if ($_FILES["payment_confirmation_file"]["error"] > 0){

    # Display error
    Session::flash('upload_schedule_payment_confirmation_error', 'There was an error uploading the file');
  } else {

    # No file handling errors

    # Steven Picado - 01/20/16
    # If uploading a payment confirmation with an incorrect amount, the validation is different
    if (Input::get('payment_confirmation') == 2) {
      
      # Show validation with note
      $validation = $validate->check($_POST, array(
        'payment_confirmation' => array('required' => true),
        'note' => array('required' => true)
      ));
    } else {

      # Show validation without note
      $validation = $validate->check($_POST, array(
        'payment_confirmation' => array('required' => true)
      ));
    }

    if($validation->passed()) {

      # file_name formatting
      # The name of the payment confirmation has to be formatted like so: soar-[$_GET['schedule_id']]-payment-confirmation.pdf
      $file_name = 'soar-' . $_GET['schedule_id'] . '-payment-confirmation.pdf';

      # Upload file if data has been added to table
      move_uploaded_file($_FILES["payment_confirmation_file"]["tmp_name"], $schedule_directory . $file_name);

      # Update table with payment_confirmation, payment_confirmation_added and notes if available
      $update = DB::GetInstance()->query("UPDATE factoring_company_schedule SET payment_confirmation = " . Input::get('payment_confirmation') . ", payment_confirmation_added = '" . date('Y-m-d H:i:s') . "' WHERE data_id = " . $_GET['schedule_id']);

      # Update billing status for all loads depending on payment confirmation type (correct amount or incorrect amount)
      if ($update->count()) {

        if (Input::get('payment_confirmation') == 2) {

          Input::get('important_note') == 'on' ? $important_note = 1 : $important_note = 0;

          # Add note
          $insert = DB::getInstance()->query("INSERT INTO factoring_company_schedule_note (schedule_id, note, important, type, user_id) VALUES (" . $_GET['schedule_id'] . ", '" . htmlentities(Input::get('note'), ENT_QUOTES) . "', " . $important_note . ", 2, " . $user->data()->user_id . ")");
        }
        
        # Update billing status for all loads in schedule
        for ($i=1; $i <= $load_list_count ; $i++) {

          # Updates billing_status and load_lock
          $update_billing_status = DB::getInstance()->query("UPDATE loader_load SET billing_status = " . Input::get('payment_confirmation') . ", load_lock = 1 WHERE load_id IN (" . implode(', ', $load_id) . ")");

          # Create $amount variable to display in note correct/incorrect amount
          Input::get('payment_confirmation') == 2 ? $amount = 'Incorrect' : $amount = 'Correct';
          
          # Add note for each load
          $insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, type, user_id) VALUES (" . $load_list_load_id[$i] . ", 'Payment confirmation uploaded (" . $amount . " amount).', 0, 1, " . $user->data()->user_id . ")");
          
          # Make count of loads updated
          $update_billing_status_count += 1;
        }

        # If we update billing status for all loads in schedule
        if ($load_list_count == $update_billing_status_count) {
          Session::flash('upload_schedule_payment_confirmation', 'File uploaded successfully.');
          Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id']);
        }
      }      
    } else {
      Session::flash('upload_schedule_payment_confirmation_error', 'There was an error, data was missing.');
    }
  }
} else {
  Session::flash('upload_schedule_payment_confirmation_error', 'The file is not valid or it superseeds the size quota (max: 7MB)');
}
