<?php
session_start();
ob_start();
# New item
if (Input::get('_controller_ppg_category') == 'add') {

  if (isset($ppg_category_duplicate_entry)) {
    
    Session::flash('core_error', 'A PPG category with this name already exists');
    Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/core/ppg_category');
  } else {

    $validation = $validate->check($_POST, array(
      'name' => array('required' => true)
    ));

    if($validation->passed()) {

      # Clear double space
      $double_white_space = preg_replace('/\s{2,}/', ' ', Input::get('name'));
      
      # Clear leading and trailing white space
      $leading_trailing_white_space = preg_replace('/^[ \t]+|[ \t]+$/', '', $double_white_space);

      $insert = DB::getInstance()->query("INSERT INTO ppg_category (name, user_id) VALUES ('" . htmlentities($leading_trailing_white_space, ENT_QUOTES) . "', " . $user->data()->id . ")");
        
      if ($insert->count()) {

        Session::flash('core', 'PPG Category added successfully');
        Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/core/ppg_category');
      } else {
        
        Session::flash('core_error', $core_language[27] . ', ' . strtolower($core_language[28]));
      }
    } else {
      
      Session::flash('core_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  }
}

# Update item
if (Input::get('_controller_ppg_category') == 'update') {

  if (isset($ppg_category_duplicate_entry)) {
    
    Session::flash('core_error', 'A PPG category with this name already exists');
    Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/core/ppg_category');
  } else {

    $validation = $validate->check($_POST, array(
      'name' => array('required' => true)
    ));

    if($validation->passed()) {

      # Clear double space
      $double_white_space = preg_replace('/\s{2,}/', ' ', Input::get('name'));
      
      # Clear leading and trailing white space
      $leading_trailing_white_space = preg_replace('/^[ \t]+|[ \t]+$/', '', $double_white_space);

      $update = DB::getInstance()->query("UPDATE ppg_category SET name = '" . htmlentities($leading_trailing_white_space, ENT_QUOTES) . "' WHERE id = " . $_GET['ppg_category_id']);
        
      if ($update->count()) {

        Session::flash('core', 'PPG Category updated successfully');
        Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/core/ppg_category');
      } else {
        
        Session::flash('core_error', $core_language[27] . ', ' . strtolower($core_language[28]));
      }
    } else {
      
      Session::flash('core_error', $core_language[27] . ', ' . strtolower($core_language[28]));
    }
  }
}
