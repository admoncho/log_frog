<?php
  session_start();
  ob_start();
  $_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# client_user_tractor
if ($_GET['user_id'] && $_SESSION['$clean_php_self'] == '/dashboard/client/client.php') {
	
	# This clause is when you are editing a client user
	$clause = " WHERE driver_id = " . $_GET['user_id'];
} elseif (isset($_POST['client_card']) && isset($_POST['client_card_user_id'])) {

	# This clause is for the client card
	$clause = " WHERE driver_id = " . $_POST['client_card_user_id'];
}

$client_user_tractor = DB::getInstance()->query("SELECT * FROM client_user_tractor" . $clause);
$client_user_tractor_count = $client_user_tractor->count();
$i = 1;

if ($client_user_tractor_count) {
	
	# Iterate through items
	foreach ($client_user_tractor->results() as $client_user_tractor_data) {
		
		$client_user_tractor_driver_id[$i] = $client_user_tractor_data->driver_id;
		$client_user_tractor_id[$i] = $client_user_tractor_data->id;
		$client_user_tractor_number[$i] = html_entity_decode($client_user_tractor_data->number);
		$client_user_tractor_color[$i] = html_entity_decode($client_user_tractor_data->color);
		$client_user_tractor_vin[$i] = html_entity_decode($client_user_tractor_data->vin);
		$client_user_tractor_headrack[$i] = $client_user_tractor_data->headrack; // 1 yes 2 no
		$client_user_tractor_year[$i] = $client_user_tractor_data->year;
		$client_user_tractor_make[$i] = html_entity_decode($client_user_tractor_data->make);
		$client_user_tractor_model[$i] = html_entity_decode($client_user_tractor_data->model);
		$client_user_tractor_weight[$i] = $client_user_tractor_data->weight;
		$client_user_tractor_sleeper[$i] = $client_user_tractor_data->sleeper; // 1 yes 2 no
		$client_user_tractor_name_on_the_side[$i] = html_entity_decode($client_user_tractor_data->name_on_the_side);
		$client_user_tractor_license_plate[$i] = html_entity_decode($client_user_tractor_data->license_plate);
		$client_user_tractor_trailer_type[$i] = $client_user_tractor_data->trailer_type;
		$client_user_tractor_user_id[$i] = $client_user_tractor_data->user_id;
		$client_user_tractor_added[$i] = date('m d, Y', strtotime($client_user_tractor_data->added));
		$i++;
	}
}
