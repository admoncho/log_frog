<?php

# Show update body notifications if Session::exists
if (Session::exists('core')) {

	include COMPONENT_PATH . 'alert_dismissible_success.txt';
		echo Session::flash('core');
	echo '</div>';
} 

if (Session::exists('core_error')) {

	include COMPONENT_PATH . 'alert_dismissible_danger.txt';
		echo Session::flash('core_error');
	echo '</div>';
}
