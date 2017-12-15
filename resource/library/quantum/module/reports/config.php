<?php
session_start();
ob_start();

# db/geo_state.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_state.php");

# db/geo_county.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_county.php");

# db/geo_district.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_district.php");

# db/user.php
include(LIBRARY_PATH . "/quantum/module/user/db/user.php");

# db/reports.php
include(LIBRARY_PATH . "/quantum/module/reports/db/reports.php");

# Post controller
include(LIBRARY_PATH . "/quantum/module/core/inc/post-controller.php");

# Redirect internal users who don't have access to the invoice module (this 
# only applies to direct url visits, otherwise they couldn't be accessing).
# Check user permissions
if ($user->data()->user_group != 4) {
	if (!$user->hasPermission('invoice')) {

		Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/');
	}
}
