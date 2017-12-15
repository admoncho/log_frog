<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# New user instance
$user = new User();

### Session/!session scripts ###
# Pages where users cannot be logged, therefore there is no session
if ($_SESSION['$clean_php_self'] == '/login.php' || $_SESSION['$clean_php_self'] == '/recover-account.php' || $_SESSION['$clean_php_self'] == '/create-account.php') {

	# Declare var
	$no_session = 1;

	# Redirect loged users if accessing pages that are used when not logged only
	$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/') : '' ;

	# Force lang in these pages if not set
	!isset($_GET['language_id']) ? Redirect::to(str_replace('.php', '', $_SERVER['PHP_SELF']) . '?language_id=1') : '';
} 

# Pages where users must be logged in to access
if (substr($_SESSION['$clean_php_self'], 0, 11) == '/dashboard/') {
	
	# Redirect if not logged
	!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . 'login') : '' ;

	### User settings  ###
	# quantum_theme
	$quantum_theme = DB::getInstance()->query("SELECT * FROM quantum_theme ORDER BY name ASC");
	$quantum_theme_count = $quantum_theme->count();
	$quantum_theme_counter = 1;

	foreach ($quantum_theme->results() as $quantum_theme_data) {
		$theme_id[$quantum_theme_counter] = $quantum_theme_data->id;
		$theme_name[$quantum_theme_counter] = $quantum_theme_data->name;
		$theme_class[$quantum_theme_counter] = $quantum_theme_data->class;
		$quantum_theme_counter++;

		$theme_class_bid[$quantum_theme_data->id] = $quantum_theme_data->class;
	}

	# user_settings
	$user_settings = DB::getInstance()->query("SELECT * FROM user_settings WHERE user_id = " . $user->data()->user_id);

	foreach ($user_settings->results() as $user_settings_data) {
		$settings_email_verification	= $user_settings_data->email_verification;
		$settings_theme_id 						= $user_settings_data->theme_id;
		$settings_nav			 						= $user_settings_data->nav;
		$settings_language_id 				= $user_settings_data->language_id;
	}
	### eo User settings  ###
}
### eo Session/!session scripts ###

### PHP_SELF ###

# Remove from PHP_SELF all but "/"
# 2 "/" we are in the core module
# 3 "/" we are in another module
$module_slash = preg_replace('/[^\/]/', '', $_SESSION['$clean_php_self']);
$module_slash_count = strlen($module_slash);

# Declare module name
# str_replace to remove '/dashboard/' and '/' and preg_replace to remove everything after the last slash if not on core module
$module_slash_count == 3 ? $module_name = str_replace(['/dashboard/', '/'], ['/', ''], preg_replace('([^/]+$)', '', $_SESSION['$clean_php_self'])) : $module_name = 'core';

# Check if module exists on module table
$module = DB::getInstance()->query("SELECT * FROM module WHERE name = '" . $module_name . "'");

# If found
if ($module->count()) {
	
	# Get module data
	foreach ($module->results() as $module_data) {
		
		$module_id = $module_data->id;
	}
} else {

	# Add module to module table
	$add_module = DB::getInstance()->query("INSERT INTO module (name) VALUES ('" . $module_name . "')");
}

# Redirect external users trying to access restricted modules
if ($user->data()->user_group == 4) {
	
	if ($module_name == 'factoring_company' || $module_name == 'broker' || $module_name == 'client' || $module_name == 'user') {
		
		Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/');
	}
}

# Check if item exists
$php_self = DB::getInstance()->query("SELECT * FROM php_self WHERE php_self_string = '" . $_SESSION['$clean_php_self'] . "'");

# If no count
if (!$php_self->count()) {

	# Create first entry
	$add_php_self = DB::getInstance()->query("INSERT INTO php_self (module_id, php_self_string) VALUES ($module_id, '" . $_SESSION['$clean_php_self'] . "')");
	$add_php_self->count() ? Redirect::to($_SERVER['PHP_SELF']) : '';
	
} else {

	# Else get data
	foreach ($php_self->results() as $php_self_data) {
		$php_self_id 					= $php_self_data->id;
		$php_self_string 			= $php_self_data->php_self_string;
		$php_self_title 			= $php_self_data->title_language_match_key;
		$php_self_breadcrumb 	= $php_self_data->breadcrumb_language_match_key;
	}
}

### eo PHP_SELF ###

### File directories ###

$img_content_directory = Config::get('paths/img/content');
$img_layout_directory = Config::get('paths/img/layout');
$dashboard_directory = Config::get('paths/dashboard/dashboard');
$module_directory = LIBRARY_PATH . '/quantum/module/' . $module_name . '/';
$front_end_template_directory = TEMPLATE_PATH . '/front-end';
$back_end_template_directory = TEMPLATE_PATH . '/back-end';
$file_directory = str_replace('_q/', '', $_SESSION['ProjectPath'] . '/files/');

# Directories below are the new method, we can't assume all files that can be 
# uploaded are images, changing /img/ to /file/ in root directories

## LOGISTICS ##

# bol [pdf]
$bol_dir = $_SESSION['ProjectPath'] . '/file/pdf/bol/';
# payment confirmation [pdf]
$payment_confirmation_dir = $_SESSION['ProjectPath'] . '/file/pdf/payment_confirmation/';
# quickpay invoice [pdf]
$quickpay_invoice_dir = $_SESSION['ProjectPath'] . '/file/pdf/quickpay_invoice/';
# rate confirmation [pdf]
$rate_confirmation_dir = $_SESSION['ProjectPath'] . '/file/pdf/rate_confirmation/';
# raw bol [pdf]
$raw_bol_dir = $_SESSION['ProjectPath'] . '/file/pdf/raw_bol/';
# schedule [pdf] (soar file) ej: soar-152-payment-confirmation.pdf
$schedule_dir = $_SESSION['ProjectPath'] . '/file/pdf/schedule/';
# schedule bg [jpg]
$schedule_bg_dir = $_SESSION['ProjectPath'] . '/file/jpg/schedule_bg/';
# schedule invoice [pdf]
$schedule_invoice_dir = $_SESSION['ProjectPath'] . '/file/pdf/schedule_invoice/';
# schedule payment confirmation [pdf]
$schedule_payment_confirmation_dir = $_SESSION['ProjectPath'] . '/file/pdf/schedule_payment_confirmation/';


## eo LOGISTICS ##

### eo File directories ###

if ($module_slash >= 2) {
	
	### Module config ###

	# Declare file location and name
	$module_config = $module_directory . 'config.php';

	# Include only if file has data to include, check file size, it cannot be 0.
	filesize($module_config) > 0 ? include($module_config) : '' ;

	### eo Module config ###	
}

### Environment language ###

# Add language scripts only on pages that require it
if ($_SESSION['$clean_php_self'] == '/login.php' 
		|| $_SESSION['$clean_php_self'] == '/recover-account.php' 
			|| $_SESSION['$clean_php_self'] == '/create-account.php'
				|| substr($_SESSION['$clean_php_self'], 0, 11) == '/dashboard/') {

	# Only retrieve language in user settings or $_GET['language_id'] for non-session pages
	isset($settings_language_id) ? $settings_language_id_value = $settings_language_id : $settings_language_id_value = $_GET['language_id'] ;

	# The core language is the only one that is loaded in this file, the reason 
	# behind it is becuase the core language can be used in other modules and in general areas.
	$core_lang = DB::getInstance()->query("SELECT * FROM core_language WHERE language_id = 1");

	foreach ($core_lang->results() as $core_lang_data) {
		
		# Get language item by match key
		$core_language[$core_lang_data->match_key] = html_entity_decode($core_lang_data->item);
	}

	# Module language
	# Only retrieve language in user settings
	$module_language = DB::getInstance()->query("SELECT * FROM " . $module_name . "_language WHERE language_id = $settings_language_id_value");

	foreach ($module_language->results() as $module_language_data) {
		
		# Get language item by match key
		${$module_name . '_language'}[$module_language_data->match_key] = html_entity_decode($module_language_data->item);
	}
}

### eo Environment language ###

# Always include module list
include($_SESSION['ProjectPath'] . "/resource/library/quantum/module/core/db/module.php");

# Include module config file
# The module config file MUST be on top of $csrfToken
# First check if it exists, if it doesn't, this is a new module being constructed, create file dependencies
if (!file_exists($module_directory . 'config.php')) {

	# Create module folders on quantum
	!file_exists($module_directory) ? mkdir($module_directory, 0755) : '';
	!file_exists($module_directory . 'controller') ? mkdir($module_directory . 'controller', 0755) : '';
	!file_exists($module_directory . 'db') ? mkdir($module_directory . 'db', 0755) : '';
	!file_exists($module_directory . 'inc') ? mkdir($module_directory . 'inc', 0755) : '';

	$config_file_data = '<?php 
# db
include($module_directory . "db/' . $module_name . '.php");

# Post controller
include(LIBRARY_PATH . "/quantum/module/core/inc/post-controller.php");
';
	$controller_file_data = '<?php ';
	$db_file_data = '<?php ';
	$breadcrumbs_title_file_data = '';
	$notification_file_data = '<?php ';
	$bottom_scripts_file_data = '';

	echo file_put_contents($module_directory . 'config.php', $config_file_data) == false ? "Cannot create file (".basename($module_directory . 'config.php').")" : '';
	echo file_put_contents($module_directory . 'controller/' . $module_name . '.php', $controller_file_data) == false ? "Cannot create file (".basename($module_directory . 'controller/' . $module_name . '.php').")" : '';
	echo file_put_contents($module_directory . 'db/' . $module_name . '.php', $db_file_data) == false ? "Cannot create file (".basename($module_directory . 'db/' . $module_name . '.php').")" : '';
	echo file_put_contents($module_directory . 'inc/breadcrumbs-title.php', $breadcrumbs_title_file_data) == false ? "Cannot create file (".basename($module_directory . 'inc/breadcrumbs-title.php').")" : '';
	echo file_put_contents($module_directory . 'inc/notification.php', $notification_file_data) == false ? "Cannot create file (".basename($module_directory . 'inc/notification.php').")" : '';
	echo file_put_contents($module_directory . 'inc/bottom-scripts.php', $bottom_scripts_file_data) == false ? "Cannot create file (".basename($module_directory . 'inc/bottom-scripts.php').")" : '';

	# Make language table
	$make_table = DB::getInstance()->query("CREATE TABLE " . $module_name . "_language (
	 language_id int(11) NOT NULL,
	 id int(11) NOT NULL AUTO_INCREMENT,
	 match_key int(11) NOT NULL,
	 item text COLLATE utf8_unicode_ci NOT NULL,
	 PRIMARY KEY (id),
	 KEY language_id (language_id)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
}

include $module_directory . 'config.php';

# Generate token
$csrfToken = Token::generate();

# RUN ONLY IN PAGES THAT REQUIRE ACCESS CONTROL
# Template top
if ($quantum_no_session_templating == 1) {

	# use quantum design
	if (substr($_SESSION['$clean_php_self'], 0, 11) == '/dashboard/'
		|| $_SESSION['$clean_php_self'] == '/login.php' 
			|| $_SESSION['$clean_php_self'] == '/recover-account.php' 
				|| $_SESSION['$clean_php_self'] == '/create-account.php') {
		
		# General file
		require $back_end_template_directory . '/top.php';
	}
} else {

	# Use template design
	if (substr($_SESSION['$clean_php_self'], 0, 11) == '/dashboard/') {
		
		# Add general file
		require $back_end_template_directory . '/top.php';
	}
}
