<?php

# Change log data
$changelog_all = DB::getInstance()->query("SELECT * FROM changelog GROUP BY added ORDER BY added DESC");
$changelog_all_count = $changelog_all->count();
$i = 1;

foreach ($changelog_all->results() as $changelog_all_data) {
	
	$changelog_all_id[$i] 								= $changelog_all_data->id;
	$changelog_all_entry[$i] 							= html_entity_decode($changelog_all_data->entry);
	$changelog_all_type[$i] 							= $changelog_all_data->type;
	$changelog_all_status[$i] 						= $changelog_all_data->status;
	$changelog_all_added[$i] 							= date('m d, Y', strtotime($changelog_all_data->added));
	$changelog_all_added_unformatted[$i] 	= date('Y-m-d', strtotime($changelog_all_data->added));
	$changelog_all_update_date[$i] 				= date('m d, Y', strtotime($changelog_all_data->update_date));
	$changelog_all_user_id[$i] 						= $changelog_all_data->user_id;

	# Increment counter
	$i++;
}
