<?php

# Core notifications (they are called from all modules)
include(LIBRARY_PATH . "/quantum/module/core/inc/notification.php");

# Show update body notifications if Session::exists
if (Session::exists('invoice')) {

	include COMPONENT_PATH . 'alert_dismissible_success.txt';
		echo Session::flash('invoice');
	echo '</div>';
} 

if (Session::exists('invoice_error')) {

	include COMPONENT_PATH . 'alert_dismissible_danger.txt';
		echo Session::flash('invoice_error');
	echo '</div>';
}
