<?php

# Core notifications (they are called from all modules)
include(LIBRARY_PATH . "/quantum/module/core/inc/notification.php");

# Show update body notifications if Session::exists
if (Session::exists('loader')) {

	include COMPONENT_PATH . 'alert_dismissible_success.txt';
		echo Session::flash('loader');
	echo '</div>';
} 

if (Session::exists('loader_error')) {

	include COMPONENT_PATH . 'alert_dismissible_danger.txt';
		echo Session::flash('loader_error');
	echo '</div>';
}
