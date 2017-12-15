<?php 
session_start();
ob_start();

# Add file
if (Input::get('_controller_draft_ratecon') == 'add') {

  if(($_FILES["file"]["type"] == "application/pdf") && $_FILES["file"]["size"] < 3000000){

    if ($_FILES["file"]["error"] > 0){
      
      Session::flash('loader_error', $core_language[27]);
    } else {

      # Upload file
      move_uploaded_file($_FILES["file"]["tmp_name"], $draft_rate_con_path . $_GET['draft_rate_con'] . '.pdf');

      Session::flash('loader', 'File added successfully');
      Redirect::to('?draft_rate_con=' . $_GET['draft_rate_con']);
    }
  } else {
    
    Session::flash('loader_error', $core_language[27]);
  }
}

# Delete file
if (Input::get('_controller_draft_ratecon') == 'delete_file') {

  # Kill image
  if (file_exists($draft_rate_con_path . $_GET['draft_rate_con'] . '.pdf')) {
    
    unlink($draft_rate_con_path . $_GET['draft_rate_con'] . '.pdf');
  }

  Session::flash('loader', 'File deleted successfully');
  Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/loader/?draft_rate_con=' . $_GET['draft_rate_con']);
}
