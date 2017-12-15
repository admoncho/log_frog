<?php
  session_start();
  ob_start();
  $_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# New item
if (Input::get('_controller_ppg') == 'add') {

  if (isset($ppg_duplicate_entry)) {
    
    Session::flash('loader_error', 'A how to item with this name already exists');
    Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/' . $_SESSION['$clean_php_self'] . '/');
  } else {

    $validation = $validate->check($_POST, array(
      'title' => array('required' => true)
    ));

    if($validation->passed()) {

      # Clear double space
      $double_white_space = preg_replace('/\s{2,}/', ' ', Input::get('title'));
      
      # Clear leading and trailing white space
      $leading_trailing_white_space = preg_replace('/^[ \t]+|[ \t]+$/', '', $double_white_space);
      
      # Make all lower caps, replace special characters and replace spaces for dashes
      $pre_file_name_1 = strtolower(str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'á', 'é', 'í', 'ó', 'ú', 'Ñ', 'ñ', ' '], ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u', 'N', 'n', '-'], $leading_trailing_white_space));
      
      # Remove all characters but letters, digits and dashes
      $pre_file_name_2 = preg_replace('/[^A-Za-z\d-]/', '', $pre_file_name_1);
      
      # Clear any occurrences of double dashes
      $pre_file_name_3 = str_replace('--', '-', $pre_file_name_2);
      
      # Remove dashes if they are the last character of the string
      $file_name = rtrim($pre_file_name_3, "-");

      # Check db for duplicate file names
      $ppg_table = DB::getInstance()->query("SELECT * FROM ppg WHERE file_name = '$file_name'");

      # Continue if there are no matches
      if (!$ppg_table->count()) {
        
        $insert = DB::getInstance()->query("INSERT INTO ppg (title, file_name, user_id) VALUES ('" . htmlentities($leading_trailing_white_space, ENT_QUOTES) . "', '" . $file_name . "', " . $user->data()->id . ")");
          
        if ($insert->count()) {

          Session::flash('loader', 'How to item added successfully');
          Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/core/ppg?file_name=' . $file_name);
        } else {
          
          Session::flash('loader_error', $core_language[27] . ', ' . strtolower($core_language[28]));
        }
      } else {

        Session::flash('loader_error', 'This how to title already exists, please choose something different.');
      }
    } else {
      
      Session::flash('loader_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  }
}

# Delete item
if (Input::get('_controller_ppg') == 'delete') {

  # Delete from DB
  $delete = DB::getInstance()->query("DELETE FROM ppg WHERE file_name = '" . str_replace('_', '-', $_GET['file_name']) . "'");

  if ($delete->count()) {
    
    # Kill file
    if (file_exists($ppg_file_name_alt)) {
      
      unlink($ppg_file_name_alt);
    }

    Session::flash('ppg', 'Item deleted successfully');
    Redirect::to('ppg?file_name=' . $_GET['file_name']);
  }
}

# Add pdf
if (Input::get('_controller_ppg') == 'add_pdf') {

  # Check for valid file types and size (less than 7,000,000 bytes)
  if($_FILES["file"]["type"] == "application/pdf") {
    
    # If file handling errors
    if ($_FILES["file"]["error"] > 0){

      # Display error
      Session::flash('loader_error', 'There was an error.');
    } else {

      # No file handling errors

      # Upload image if data has been added to table
      $file_path = $_SESSION['ProjectPath'] . '/files/';

      move_uploaded_file($_FILES["file"]["tmp_name"], $file_path . '/ppg/' . $_GET['file_name'] . '.pdf');

      Session::flash('loader', 'File uploaded successfully.');
      Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/core/ppg?file_name=' . $_GET['file_name']);
    }
  } else {

    Session::flash('loader_error', 'There was an error.');
  }
}
