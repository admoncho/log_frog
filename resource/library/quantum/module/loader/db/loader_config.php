<?php

$loader_config = DB::getInstance()->query("SELECT * FROM loader_config");

foreach ($loader_config->results() as $loader_config_data) {
	
	$loader_status_change_notification_subject = str_replace(
		['{{client_id}}', '{{load_number}}', '{{picked up|delivered}}', '{{city}}', '{{state_id}}', '{{first_contact_number}}'], 
		[$client_id_company_name[$load_client_id[1]], $load_load_number[1], ($checkpoint_id_data_type[1] == 0 ? 'picked up' : 'delivered'), $checkpoint_id_city[1], $state_abbr[$checkpoint_id_state_id[1]], $user_phone_number_phone_number[1]], html_entity_decode($loader_config_data->loader_status_change_notification_subject));
	
	$loader_status_change_notification = str_replace(['{{client_id}}', '{{load_number}}', '{{picked up|delivered}}', '{{city}}', '{{state_id}}', '{{first_contact_number}}'], [$client_id_company_name[$load_client_id[1]], $load_load_number[1], ($checkpoint_id_data_type[1] == 1 ? 'delivered' : 'picked up'), $checkpoint_id_city[1], $state_abbr[$checkpoint_id_state_id[1]], $user_phone_number_phone_number[1]], html_entity_decode($loader_config_data->loader_status_change_notification_template));
}
