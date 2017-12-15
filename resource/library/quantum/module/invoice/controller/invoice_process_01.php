<?php
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

/*
First pick up on Friday and last pick on next Thursday that delivers Thursday or Friday.
This means the code has to check for the loads picked on last Thursday that didn't deliver until last Saturday+ and added to this invoice
and leave for next week loads that were picked yesterday (Thursday) that deliver tomorrow (Saturday+) (all of this from the perspective 
of invoices created automatically on Fridays).

### IMPORTANT ###
This is the final list of weekly invoices, the code that synchronizes 
checkpoint datetimes on the load page (loader) must be fixed or checked 
as it may not be functioning properly.
*/

# Set last friday's date
$last_friday = date('Y-m-d G:i:s', strtotime('last Friday'));

# Set this friday's date
$this_friday = date('Y-m-d G:i:s', strtotime('Friday'));

# Set last Sunday's date
$last_sunday = date('Y-m-d G:i:s', strtotime('last Sunday'));

# Set this Sunday's date
$this_sunday = date('Y-m-d G:i:s', strtotime('Sunday'));

# Get all loads from the current week
# - All loads that are picked between 00:00 last friday and 00:00 this Friday.
# (WHERE first_checkpoint > '" . $last_friday . "' && first_checkpoint < '" . $this_friday . "')
# - All loads that are dropped until this Sunday at 00:00
# (&& last_checkpoint < '" . $this_sunday . "')
$week_loads_01 = DB::getInstance()->query("
	SELECT loader_load.entry_id, loader_load.load_id, loader_entry.driver_id, client_user.client_id 
	FROM loader_load 
	LEFT JOIN loader_entry ON loader_load.entry_id=loader_entry.data_id 
	LEFT JOIN client_user ON loader_entry.driver_id=client_user.user_id 
	WHERE first_checkpoint > '" . $last_friday . "' && first_checkpoint < '" . $this_friday . "'
	&& last_checkpoint < '" . $this_sunday . "'
");

# For each load, (1) add data to invoice_week_load table
foreach ($week_loads_01->results() as $week_loads_01_data) {
	
	$insert = DB::getInstance()->query("
		INSERT INTO invoice_week_load (client_id, load_id, driver_id, week_number) 
		VALUES (" . $week_loads_01_data->client_id . "
			, " . $week_loads_01_data->load_id . "
			, " . $week_loads_01_data->driver_id . "
			, " . date('W') . ")
	");
}

# Get all loads from the current week
# - All loads that were dropped last Sunday who's pick up was before last Friday at 00:00
$week_loads_02 = DB::getInstance()->query("
	SELECT * FROM loader_load 
	WHERE first_checkpoint < '" . $last_friday . "' && last_checkpoint > '" . $last_sunday . "'
");

# For each load, (1) add data to invoice_week_load table
foreach ($week_loads_02->results() as $week_loads_02_data) {

	$insert = DB::getInstance()->query("
		INSERT INTO invoice_week_load (client_id, load_id, driver_id) 
		VALUES (" . $week_loads_02_data->client_id . ", " . $week_loads_02_data->load_id . ", '" . $week_loads_02_data->driver_id . "')
	");
}
