<?php

# This file name
$this_file_name = basename(__FILE__, '.php');

# geo_county
$geo_county = DB::getInstance()->query("SELECT * FROM " . $this_file_name);
$geo_county_count = $geo_county->count();
if ($geo_county_count) {
	foreach ($geo_county->results() as $geo_county_data) {
		$county_name[$geo_county_data->county_id] = $geo_county_data->name;
		$county_state_name = $state_name[$geo_county_data->state_id];
	}
}
