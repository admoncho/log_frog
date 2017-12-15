<?php

# client_insurance_company
isset($_GET['client_insurance_company_id']) ? $clause = " WHERE id = " . $_GET['client_insurance_company_id'] : $clause = " ORDER BY name ASC";
$client_insurance_company = DB::getInstance()->query("SELECT * FROM client_insurance_company" . $clause);
$client_insurance_company_count = $client_insurance_company->count();
$i = 1;

if ($client_insurance_company_count) {
	
	# Iterate through items
	foreach ($client_insurance_company->results() as $client_insurance_company_data) {
		
		$client_insurance_company_id[$i] = $client_insurance_company_data->id;
		$client_insurance_company_name[$i] = html_entity_decode($client_insurance_company_data->name);
		$i++;

		$client_insurance_company_id_name[$client_insurance_company_data->id] = html_entity_decode($client_insurance_company_data->name);
	}
}
