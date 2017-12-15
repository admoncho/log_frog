<?php

$validation = $validate->check($_POST, array(
	'broker_id' => array('required' => true),
	'load_number' => array(
		'required' => true,
		'unique_load_number' => 'loader_load'),
	'broker_name_number' => array('required' => true),
	'broker_email' => array(
		'required' => true,
		'min' => 6,
		'max' => 120,
		'email' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'),
	'weight' => array('required' => true),
	'miles' => array('required' => true),
	'avg_diesel_price' => array('required' => true),
	'user_id' => array('required' => true)
));

# Check if we are receiving a valid file
if(($_FILES["file"]["type"] == "image/jpeg" 
  || $_FILES["file"]["type"] == "image/jpg" 
  || $_FILES["file"]["type"] == "image/png" 
  || $_FILES["file"]["type"] == "image/gif"
  || $_FILES["file"]["type"] == "application/pdf") && $_FILES["file"]["size"] < $_QC_settings_max_file_size){
  if ($_FILES["file"]["error"] > 0){
    Session::flash('add_loader_file_error', 'There was a problem with the rate confirmation file, please try again');
  } else {
  	$file_type = 2;

		# This converts "fat.pdf.jpg" to jpg and fat.pdf to pdf (gets chars after last .)
		$extension = strtolower(preg_replace('/^(.*[.])/', '', $_FILES["file"]["name"]));
  }
}

# If data is valid and we have file data
if($validation->passed() && $file_type && $extension) {

	# Create equipment string
	$equipment = json_encode($_POST['equipment']);

	# Add loader entry
	$add_loader_entry = DB::getInstance()->query("INSERT INTO loader_entry (driver_id, user_id, added_by) VALUES (" . $_GET['driver_id'] . ", " . Input::get('user_id') . ", " . Input::get('added_by') . ")");

	# Get the loader entry data_id we just inserted
	$last_added_id = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS last_added_id FROM loader_entry");
  foreach ($last_added_id->results() as $id) {
    $last_id = $id->last_added_id;
  }

  # Add load after adding entry
  if ($add_loader_entry->count()) {
  	
  	$add_loader_load = DB::getInstance()->query("INSERT INTO loader_load (entry_id, broker_id, load_number, broker_name_number, broker_email, line_haul, weight, miles, deadhead, avg_diesel_price, reference, commodity, equipment, notes, added_by, user_id) VALUES (" . $last_id . ", " . Input::get('broker_id') . ", '" . htmlentities(Input::get('load_number'), ENT_QUOTES) . "', '" . htmlentities(Input::get('broker_name_number'), ENT_QUOTES) . "', '" . Input::get('broker_email') . "', '" . Input::get('line_haul') . "', " . Input::get('weight') . ", '" . Input::get('miles') . "', '" . Input::get('deadhead') . "', '" . Input::get('avg_diesel_price') . "', '" . htmlentities(Input::get('reference'), ENT_QUOTES) . "', '" . htmlentities(Input::get('commodity'), ENT_QUOTES) . "', '" . $equipment . "', '" . htmlentities(Input::get('notes'), ENT_QUOTES) . "', " . Input::get('added_by') . ", " . Input::get('user_id') . ")");

  	# Get the loader load data_id we just inserted
		$last_load_id = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS last_load_id FROM loader_load");
	  foreach ($last_load_id->results() as $id) {
	    $last_load_id = $id->last_load_id;
	  }

	  # Make name with rate confirmation label
    $name = 'rate-confirmation-' . $last_id . '-' . $last_load_id . '.' . $extension;

    # Add file to table loader_file
    $add_loader_file = DB::getInstance()->query("INSERT INTO loader_file (load_id, file_name, file_type, user_id) VALUES ('" . $last_load_id . "', '" . $name . "', " . $file_type . ", " . $user->data()->user_id . ")");

    if ($add_loader_file->count()) {

      // Upload file if data has been added to table
      move_uploaded_file($_FILES["file"]["tmp_name"], $file_directory.'_temp'.$user->data()->user_id);
      rename($file_directory.'_temp'.$user->data()->user_id, $file_directory.$name);

      Session::flash('add_loader_load', 'Load data added successfully');
      Redirect::to('view-load?load_id=' . $last_load_id);
    }
  }
} else {
	Session::flash('add_loader_entry_error', $_QC_language[16]);
}
