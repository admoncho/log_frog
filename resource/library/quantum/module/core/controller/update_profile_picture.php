<?php 

# This file name
$this_file_name = basename(__FILE__, '.php');

# Add gallery item
if (Input::get('_controller_' . $this_file_name) == 'add') {

  # Check for valid file types and size (less than 7,000,000 bytes)
  if(($_FILES["image"]["type"] == "image/jpeg" || $_FILES["image"]["type"] == "image/jpg") && $_FILES["image"]["size"] < 7000000) {

    # If file handling errors
    if ($_FILES["image"]["error"] > 0){

      # Display error
      Session::flash($this_file_name . '_error', 'Se produjo un error subiendo la imagen');
    } else {

      # No file handling errors

      # Upload image if data has been added to table
      move_uploaded_file($_FILES["image"]["tmp_name"], $img_content_directory . '/user/avatar/' . $user->data()->id  . '.jpg');

      Session::flash($this_file_name, 'Imagen agregada satisfactoriamente');

      Redirect::to('account');
    }
  } else {
    Session::flash($this_file_name . '_error', 'La imagen no es v&aacute;lida o sobrepasa los limites de peso en MB (max: 7)');
  }
}
