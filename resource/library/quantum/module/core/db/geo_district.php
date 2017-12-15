<?php

# This file name
$this_file_name = basename(__FILE__, '.php');

# geo_district
$geo_district = DB::getInstance()->query("SELECT " . $this_file_name . ".county_id AS " . $this_file_name . "_county_id, " . $this_file_name . ".district_id, " . $this_file_name . ".name AS " . $this_file_name . "_name, geo_county.state_id, geo_county.county_id AS geo_county_county_id, geo_county.name AS geo_county_name FROM " . $this_file_name . " INNER JOIN geo_county ON " . $this_file_name . ".county_id=geo_county.county_id");
$geo_district_count = $geo_district->count();
if ($geo_district_count) {
	foreach ($geo_district->results() as $geo_district_data) {
		$district_name[$geo_district_data->district_id] = $geo_district_data->geo_district_name;
		$district_county_name = $county_name[$geo_district_data->geo_district_county_id];
		$district_state_name = $state_name[$geo_district_data->state_id];
	}
}
