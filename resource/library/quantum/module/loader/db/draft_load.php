<?php

# loader_load_draft
$draft = DB::getInstance()->query("SELECT * FROM loader_load_draft WHERE id = " . $_GET['draft_id']);
$draft_count = $draft->count();
$i = 1;

if ($draft_count) {
	
	# Iterate through items
	foreach ($draft->results() as $draft_data) {
		
		$draft_id[$i] = $draft_data->id;
		$draft_initial_rate[$i] = number_format($draft_data->initial_rate, 2);
		$draft_final_rate_1[$i] = $draft_data->final_rate;
		$draft_deadhead[$i] = number_format($draft_data->deadhead, 1);
		$draft_weight[$i] = number_format($draft_data->weight, 1);
		$draft_broker_name_number[$i] = html_entity_decode($draft_data->broker_name_number);
		$draft_broker_email[$i] = $draft_data->broker_email;
		$draft_note[$i] = html_entity_decode($draft_data->note);
		$draft_user_id[$i] = $draft_data->user_id;
		$draft_added[$i] = date('m/d/Y', strtotime($draft_data->added));
		$draft_added_time[$i] = date('G:i', strtotime($draft_data->added));
		$i++;
	}
}

# draft_id specific
# /dashboard/loader/draft-load?draft_id=n
if (isset($_GET['draft_id'])) {
	
	if ($_GET['checkpoint_id']) {
		
		# Get all data related to this checkpoint_id	
		$draft_checkpoint_id = DB::getInstance()->query("
			SELECT * FROM loader_load_draft_checkpoint 
			WHERE load_draft_id = " . $_GET['draft_id'] . " && checkpoint_id = " . $_GET['checkpoint_id']);	

	} elseif ($_GET['checkpoint_status_update']) {
		
		# Get all data related to this checkpoint_id	
		$draft_checkpoint_id = DB::getInstance()->query("
			SELECT * FROM loader_load_draft_checkpoint 
			WHERE load_draft_id = " . $_GET['draft_id'] . " && checkpoint_id = " . $_GET['checkpoint_status_update']);	

	} else {
		
		# Get all data related to this draft_id	
		$draft_checkpoint_id = DB::getInstance()->query("
			SELECT * FROM loader_load_draft_checkpoint 
			WHERE load_draft_id = " . $_GET['draft_id'] . " ORDER BY data_type ASC");
	}

	$draft_checkpoint_id_count = $draft_checkpoint_id->count();
	$i = 1;

	foreach ($draft_checkpoint_id->results() as $draft_checkpoint_id_data) {
		
		$draft_checkpoint_id_draft_id[$i] = $draft_checkpoint_id_data->load_draft_id;
		$draft_checkpoint_id_checkpoint_id[$i] = $draft_checkpoint_id_data->checkpoint_id;
		$draft_checkpoint_id_date_time[$i] = date('m/d/Y', strtotime($draft_checkpoint_id_data->date_time));
		$draft_checkpoint_id_date[$i] = date('m-d-Y', strtotime($draft_checkpoint_id_data->date_time));
		$draft_checkpoint_id_time[$i] = date('G:i', strtotime($draft_checkpoint_id_data->date_time));
		$draft_checkpoint_id_city[$i] = $draft_checkpoint_id_data->city;
		$draft_checkpoint_id_state_id[$i] = $draft_checkpoint_id_data->state_id;
		$draft_checkpoint_id_data_type[$i] = $draft_checkpoint_id_data->data_type; // 0 pick up 1 dropoff
		$draft_checkpoint_id_added[$i] = date('m/d/Y G:i', strtotime($draft_checkpoint_id_data->added));
		$draft_checkpoint_id_user_id[$i] = $draft_checkpoint_id_data->user_id;

		# Check there are at least 2 checkpoints
		if ($draft_checkpoint_id_count > 1) {
			
			# Make sure one is a pick up and one is a dropoff
			$draft_checkpoint_id_data_type[$i] == 0 ? $draft_checkpoint_data_type_0_exists = 1 : '';
			$draft_checkpoint_id_data_type[$i] == 1 ? $draft_checkpoint_data_type_1_exists = 1 : '';
		}

		# Set $pick_drop_ready
		if ((isset($draft_checkpoint_data_type_0_exists) && $draft_checkpoint_data_type_0_exists == 1) 
			&& (isset($draft_checkpoint_data_type_1_exists) && $draft_checkpoint_data_type_1_exists == 1)) {
			
			$pick_drop_ready = 1;
		}

		$i++;
	}
}
