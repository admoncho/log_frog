<?php

# New merged invoice being added

# Check for valid file types and size (less than 7,000,000 bytes)
if(($_FILES["invoice"]["type"] == "application/pdf") && $_FILES["invoice"]["size"] < 7000000) {
  
  # If file handling errors
  if ($_FILES["invoice"]["error"] > 0){

    # Display error
    Session::flash('add_merged_invoice_error', 'There was an error uploading the file');
  } else {

    # No file handling errors

    # file_name formatting
    # The name of the invoice has to be formatted like so: invoice-[invoice number]-[client name]-[broker name].pdf
    $file_name = Input::get('file_name');

    # Upload image if data has been added to table
    move_uploaded_file($_FILES["invoice"]["tmp_name"], $schedule_directory . '_temp/' . $user->data()->user_id);

    # Raname and move out of _temp
    rename($schedule_directory . '_temp/' . $user->data()->user_id, $schedule_directory . $file_name);

    /* # Kill pre invoice
    # Kill pre invoice must run when sending the schedule
    if (file_exists($schedule_directory . Input::get('pre_invoice_file_name'))) {
        unlink($schedule_directory . Input::get('pre_invoice_file_name'));
    }*/

    Session::flash('add_merged_invoice', 'Invoice added succesfully');
    Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id']);
  }
} else {
  Session::flash('add_merged_invoice_error', 'The file is not valid or it superseeds the MB quota (max: 7)');
}
