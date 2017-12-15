<?php
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

### Status update scripts for Draft Loads ###

# Get all drafts for today, look for leads which last status is on hold (2) 
# and were created 2 hours ago (120 minutes); to those leads, add a new status 
# of declined (3), add automated note.
$cron_draft = DB::getInstance()->query("
	SELECT * FROM loader_load_draft 
	WHERE added LIKE '" . date('Y-m-d') . "%'");

if ($cron_draft->count()) {

	# Create array of all draft ids
	// $draft_id_array = array();
	
	foreach ($cron_draft->results() as $cron_draft_data) {

		// $draft_id_array[] = $cron_draft_data->id;

		$cron_draft_lead = DB::getInstance()->query("
			SELECT loader_load_draft_lead.draft_id, loader_load_draft_lead.id, loader_load_draft_lead_status.status, loader_load_draft_lead_status.added  FROM loader_load_draft_lead 
			LEFT JOIN loader_load_draft_lead_status ON loader_load_draft_lead.id=loader_load_draft_lead_status.lead_id 
			WHERE draft_id = " . $cron_draft_data->id . " 
			ORDER BY added DESC LIMIT 1");
var_dump($cron_draft_lead);
		/*if ($cron_draft_lead->count()) {
			
			foreach ($cron_draft_lead->results() as $cron_draft_lead_data) {
				
			}
		}*/
	}
}

/*$draft_list = DB::getInstance()->query("SELECT * FROM loader_load_draft_lead_status WHERE added LIKE '" . date('Y-m-d') . "%' && status = 2");
$draft_list_count = $draft_list->count();
$i = 1;

if ($draft_list_count) {

	foreach ($draft_list->results() as $draft_list_data) {

	}
}*/
