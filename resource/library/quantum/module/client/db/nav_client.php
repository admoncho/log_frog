<?php

# This is a quick client db connection with the purpose of creating a quick navigation client list.

# nav client
$nav_client = DB::getInstance()->query("SELECT client.data_id, client.company_name FROM client WHERE status = 1 ORDER BY company_name ASC");
$nav_client_count = $nav_client->count();
$i = 1;

if ($nav_client_count) {
	
	# Iterate through items
	foreach ($nav_client->results() as $nav_client_data) {
		
		$nav_client_data_id[$i] = $nav_client_data->data_id;
		$nav_client_company_name[$i] = $nav_client_data->company_name;
		$i++;
	}
}
