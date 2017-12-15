<?php

### POST ###
if (Input::exists()) {
  if(Token::check(Input::get('token'))) {
    $validate = new Validate();
    # Get _controller_ input (ONLY 1 _controller_ INPUT PER FORM)
    foreach ($_POST as $input_name => $value) {
      # $input_name saves the input field name
      # $value saves the input field value (not used for this example)
      if (preg_match('/_controller_/', $input_name)) {
        $_controller_name = $input_name;
      }
    }

    # Some controllers are stored in the core module, this is because their function is more global than on a module basis.
    if ($_controller_name == '_controller_add_language_items') {

      # Add core controller
      include(LIBRARY_PATH . "/quantum/module/core/controller/" . str_replace('_controller_', '', $_controller_name) . ".php");
    } else {

      # Include controller being posted
      Input::get($_controller_name) ? include($module_directory . "controller/" . str_replace('_controller_', '', $_controller_name) . ".php") : '' ;
    }
  }
} else {

  ### GET ###
  # - 1 Check if a _controller_ is set
  if (strpos($_SERVER['QUERY_STRING'], '_controller_') == true) {
      
    # - 2 Make array with all query strings
    $query_string = explode('&', $_SERVER['QUERY_STRING']);
    
    # - 3 Loop through strings
    foreach($query_string as $_controller_string) {

      # - 4 Find the controller
      if (substr($_controller_string, 0, 12) == '_controller_') {

        # - 5 Remove controller value
        $_controller_string_value = explode('=', $_controller_string);

        # - 6 Remove '_controller_' from controller string value
        $_controller_name = str_replace('_controller_', '', $_controller_string_value[0]);

        # - 7 Include controller
        include($module_directory . "controller/" . str_replace('_controller_', '', $_controller_name) . ".php");
      }
    }    
  }
}
