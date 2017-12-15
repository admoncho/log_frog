<?php
session_start();
ob_start();
if ($_GET['_hp_delete_load_from_schedule']) {
	$delete = DB::getInstance()->query("DELETE FROM factoring_company_schedule_load WHERE schedule_id = " . $_GET['schedule_id'] . " && load_id = " . $_GET['load_id']);
	
	if ($delete->count()) {
		
		# Delete all files
		# Call the delete files controller from here, so that the controller can be used else where.
		include($_SESSION['ProjectPath']."/includes/controller/delete_schedule_files.php");
	}

	if ($delete->count() && !$all_files_deleted) {
		
		# If load was deleted from schedule but not all files were deleted
		Session::flash('delete_load_from_schedule', 'Load removed from schedule successfully, some files were not deleted!');
		Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id']);
	} elseif (condition) {
		
		# If load was deleted from schedule and all files were deleted
		Session::flash('delete_load_from_schedule', 'Load removed from schedule successfully, all files were deleted!');
		Redirect::to('factoring-company-schedule?schedule_id=' . $_GET['schedule_id']);
	}

	$delete->count() && !$all_files_deleted ?  : Session::flash('delete_load_from_schedule_error', $_QC_language[16]) ;
}
