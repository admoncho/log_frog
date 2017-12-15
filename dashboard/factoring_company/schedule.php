<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_underscore = str_replace('-', '_', basename(__FILE__, '.php'));

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");
?>

<div class="row">
	<div class="col-md-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				panel-heading
			</div>
			<div class="panel-body">
				panel-body
			</div>
			<div class="panel-footer">
				panel-footer
			</div>
		</div>
	</div>	
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
