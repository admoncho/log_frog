<?php

# Core notifications (they are called from all modules)
include(LIBRARY_PATH . "/quantum/module/core/inc/notification.php");

# Show update body notifications if Session::exists
if (Session::exists($this_file_name)) {

	include COMPONENT_PATH . 'alert_dismissible_success.txt';
		echo Session::flash($this_file_name);
	echo '</div>';
} 

if (Session::exists($this_file_name . '_error')) {

	include COMPONENT_PATH . 'alert_dismissible_danger.txt';
		echo Session::flash($this_file_name . '_error');
	echo '</div>';
}
