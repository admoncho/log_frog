<?php

# Sets what type of file we are receiving (rate confirmation, bol, ...)
Input::get('file_type') == 0 ? $file_type = 'NULL' : $file_type = Input::get('file_type');
# File extension
# This converts "fat.pdf.jpg" to jpg and fat.pdf to pdf (gets chars after last .)
$extension = strtolower(preg_replace('/^(.*[.])/', '', $_FILES["file"]["name"]));
# File name
if (Input::get('file_type') == 0) {
    # File with no label
    $name = Input::get('entry_id') . '-' . $_GET['load_id'] . '-' . $user->data()->user_id . '-' . date('Gis') . '.' . $extension;
} elseif (Input::get('file_type') == 1) {
    # File with BOL label
    $name = 'bol-' . Input::get('entry_id') . '-' . $_GET['load_id'] . '.' . $extension;
} elseif (Input::get('file_type') == 2) {
    # File with rate confirmation label
    $name = 'rate-confirmation-' . Input::get('entry_id') . '-' . $_GET['load_id'] . '.' . $extension;
} elseif (Input::get('file_type') == 3) {
    # File with payment confirmation label
    $name = 'payment-confirmation-' . Input::get('entry_id') . '-' . $_GET['load_id'] . '.' . $extension;
} elseif (Input::get('file_type') == 4) {
    # File with raw bol label
    $name = 'raw-bol-' . Input::get('entry_id') . '-' . $_GET['load_id'] . '.' . $extension;
}

# Delete current BOL file if exists and new file_type comes as 1 (BOL)
if (Input::get('file_type') == 1 && file_exists($file_directory . $name)) {
	# Delete current BOL loader_file
	$delete = DB::getInstance()->query("DELETE FROM loader_file WHERE file_id = " . $bol_file_id);

  if ($delete->count()) {
  	# Delete current BOL file
  	if (file_exists($file_directory . $bol_file_name)) {
      
      // unlink($file_directory . $bol_file_name);
    }
  }
}

# Delete current Rate Confirmation file if exists and new file_type comes as 2 (Rate Confirmation)
if (Input::get('file_type') == 2 && file_exists($file_directory . $name)) {
    # Delete current Rate Confirmation loader_file
    $delete = DB::getInstance()->query("DELETE FROM loader_file WHERE file_id = " . $rate_confirmation_file_id);

    if ($delete->count()) {
      # Delete current Rate Confirmation file
      if (file_exists($file_directory . $rate_confirmation_file_name)) {
        unlink($file_directory . $rate_confirmation_file_name);
      }
    }
}

# Delete current payment confirmation file if exists and new file_type comes as 3 (payment confirmation)
if (Input::get('file_type') == 3 && file_exists($file_directory . $name)) {
    # Delete current payment confirmation loader_file
    $delete = DB::getInstance()->query("DELETE FROM loader_file WHERE file_id = " . $payment_confirmation_file_id);

    if ($delete->count()) {
        # Delete current payment confirmation file
        if (file_exists($file_directory . $payment_confirmation_file_name)) {
            unlink($file_directory . $payment_confirmation_file_name);
        }
    }
}

# Delete current raw bol file if exists and new file_type comes as 4 (raw bol)
if (Input::get('file_type') == 4 && file_exists($file_directory . $name)) {
    # Delete current raw bol loader_file
    $delete = DB::getInstance()->query("DELETE FROM loader_file WHERE file_id = " . $raw_bol_file_id);

    if ($delete->count()) {
        # Delete current raw bol file
        if (file_exists($file_directory . $raw_bol_file_name)) {
            unlink($file_directory . $raw_bol_file_name);
        }
    }
}

if(($_FILES["file"]["type"] == "application/pdf") && $_FILES["file"]["size"] < $_QC_settings_max_file_size){
    if ($_FILES["file"]["error"] > 0){
        Session::flash('add_loader_file_error', $_QC_language[147]);
    } else {

        $insert = DB::getInstance()->query("INSERT INTO loader_file (load_id, file_name, file_type, user_id) VALUES ('" . $_GET['load_id'] . "', '" . $name . "', " . $file_type . ", " . $user->data()->user_id . ")");

        if ($insert->count()) {

            // Upload file if data has been added to table
            move_uploaded_file($_FILES["file"]["tmp_name"], $file_directory.'_temp'.$user->data()->user_id);
            rename($file_directory.'_temp'.$user->data()->user_id, $file_directory.$name);

            Session::flash('add_loader_file', 'File added successfully');
            Redirect::to('view-load?load_id=' . $_GET['load_id']);
        } else {
            Session::flash('add_loader_file_error', $_QC_language[16]);
        }
    }
} else {
    Session::flash('add_loader_file_error', $_QC_language[16]);
}
