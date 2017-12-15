<?php

# This file name
$this_file_name = basename(__FILE__, '.php');

# geo_state
$geo_state = DB::getInstance()->query("SELECT * FROM " . $this_file_name . " ORDER BY state_id ASC");
$geo_state_count = $geo_state->count();
if ($geo_state_count) {
	foreach ($geo_state->results() as $geo_state_data) {
		$state_abbr[$geo_state_data->state_id] = $geo_state_data->abbr;
		$state_name[$geo_state_data->state_id] = $geo_state_data->name;
	}
}
