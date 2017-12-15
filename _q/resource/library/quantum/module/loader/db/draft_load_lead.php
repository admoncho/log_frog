<?php

# Clause
if ($_GET['accept']) {
	
	$clause = " WHERE id = " . $_GET['lead_id'];
} else {

	$clause = " WHERE draft_id = " . $_GET['draft_id'] . " ORDER BY last_modified DESC";
}

# loader_load_draft_lead
$draft_lead = DB::getInstance()->query("SELECT * FROM loader_load_draft_lead" . $clause);
$draft_lead_count = $draft_lead->count();
$i = 1;
if ($draft_lead_count) {
	
	# Iterate through items
	foreach ($draft_lead->results() as $draft_lead_data) {
		
		$draft_lead_draft_id[$i] = $draft_lead_data->draft_id;
		$draft_lead_driver_id[$i] = $draft_lead_data->driver_id;
		$draft_lead_id[$i] = $draft_lead_data->id;
		$draft_lead_last_modified[$i] = date('M d Y G:i', strtotime($draft_lead_data->last_modified));

		# Get client id
		$draft_client = DB::getInstance()->query("SELECT * FROM client_user WHERE user_id = " . $draft_lead_driver_id[$i]);

		foreach ($draft_client->results() as $draft_client_data) {

			$draft_client_id[$i] = $draft_client_data->client_id;
		}

		# Is draft accepted already?
		# loader_load_draft_lead_status
		$draft_lead_status_1 = DB::getInstance()->query("SELECT * FROM loader_load_draft_lead_status WHERE lead_id = " . $draft_lead_id[$i] . " && status = 4");
		
		if ($draft_lead_status_1->count()) {
			
			# $draft_accepted is used to toggle display of certain elements if set, 
			# it also holds the driver id of the accepted lead.
			$draft_accepted = $draft_lead_driver_id[$i];
		}
		
		$i++;
	}
}
