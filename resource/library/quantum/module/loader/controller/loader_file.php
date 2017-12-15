<?php 

# Add file
if (Input::get('_controller_loader_file') == 'add_file') {

  if(($_FILES["file"]["type"] == "application/pdf") && $_FILES["file"]["size"] < 3000000){

    if ($_FILES["file"]["error"] > 0){
      
      Session::flash('loader_error', $core_language[27]);
    } else {

      # Payment confirmation is handled differently
      if (Input::get('file_type') == 3) {
        
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

          # Add file data to table
          $insert = DB::getInstance()->query("INSERT INTO loader_file (load_id, file_name, file_type, user_id) VALUES (" . $_GET['load_id'] . ", '" . Input::get('file_name') . "', " . Input::get('file_type') . ", " . $user->data()->id . ")");

          if ($insert->count()) {

            # Toggle automated/manual note
            Input::get('note') ? $note = 'Partial payment received with note: ' . Input::get('note') : $note = 'Payment received succesfully';

            # Toggle billing status value
            Input::get('note') ? $billing_status = 2 : $billing_status = 3;

            # Add note
            $add_note = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, type, user_id) VALUES (" . $_GET['load_id'] . ", '" . '<p>' . $note . '</p>' . "', 0, 1, " . $user->data()->id . ")");

            # Update billing status and lock load
            $update = DB::getInstance()->query("UPDATE loader_load SET billing_status = $billing_status, billing_date = '" . date('Y/m/d') . "', load_lock = 1 WHERE load_id = " . $_GET['load_id']);

            # Upload file if data has been added to table
            move_uploaded_file($_FILES["file"]["tmp_name"], Input::get('path') . Input::get('file_name'));

            Session::flash('loader', 'File added successfully');
            Redirect::to('load?load_id=' . $_GET['load_id'] . '&' . $file_get_value . '=1');
          } else {

            Session::flash('loader_error', $core_language[27]);
          }
        }

      } else {

        $insert = DB::getInstance()->query("INSERT INTO loader_file (load_id, file_name, file_type, user_id) VALUES (" . $_GET['load_id'] . ", '" . Input::get('file_name') . "', " . Input::get('file_type') . ", " . $user->data()->id . ")");

        if ($insert->count()) {

          # Upload file if data has been added to table
          move_uploaded_file($_FILES["file"]["tmp_name"], Input::get('path') . Input::get('file_name'));

          Session::flash('loader', 'File added successfully');
          Redirect::to('load?load_id=' . $_GET['load_id'] . '&' . $file_get_value . '=1');
        } else {

          Session::flash('loader_error', $core_language[27]);
        }
      }
    }
  } else {
    
    Session::flash('loader_error', $core_language[27]);
  }
}

# Delete file
if (Input::get('_controller_loader_file') == 'delete_file') {

  $delete = DB::getInstance()->query("DELETE FROM loader_file WHERE file_name = '" . Input::get('file_name') . "'");

  if ($delete->count()) {
      
      # Kill image
      if (file_exists(Input::get('path') . Input::get('file_name'))) {
        
        unlink(Input::get('path') . Input::get('file_name'));
      }

      Session::flash('loader', 'File deleted successfully');
      Redirect::to('load?load_id=' . $_GET['load_id']);
  } else {

      Session::flash('loader_error', $core_language[27]);
  }
}
