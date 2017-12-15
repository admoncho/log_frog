<?php

# item clause
if (isset($_GET['user_id'])) {
	
	$clause = " WHERE user_id = " . $_GET['user_id'];
} elseif (isset($_POST['client_card_user_id'])) {
	
	$clause = " WHERE user_id = " . $_POST['client_card_user_id'];
} elseif (isset($_GET['checkpoint_status_update'])) {
	
	$clause = " WHERE user_id = " . $user->data()->id;
} else {
	
	$clause = "";
} 

# Clause specific table connection
$user_phone_number = DB::getInstance()->query("SELECT * FROM user_phone_number" . $clause);
$user_phone_number_count = $user_phone_number->count();
$i = 1;

# Iterate through table items
foreach ($user_phone_number->results() as $user_phone_number_data) {
	
	$user_phone_number_user_id[$i] = $user_phone_number_data->user_id;
	$user_phone_number_id[$i] = $user_phone_number_data->id;
	$user_phone_number_phone_number[$i] = $user_phone_number_data->phone_number;
	$i++;

	$user_phone_number_user_id_phone_number[$user_phone_number_data->user_id] = $user_phone_number_data->phone_number;
}
