<?php 
session_start();
ob_start();

### BEGIN GENERAL DATA (do not edit) ###
$user = new User(); 			// New user instance

$_QC_settings = DB::getInstance()->query("SELECT * FROM _QC_settings");
foreach ($_QC_settings->results() as $_QC_settings_data) {
	$_QC_settings_registration = $_QC_settings_data->registration;
	$_QC_settings_registration == 0 && $_SERVER['REQUEST_URI'] == '/register' ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ; // Turn off registrarion
	$_QC_settings_max_file_size = $_QC_settings_data->max_file_size;
	$_QC_settings_design_bg_image = $_QC_settings_data->design_bg_image;
	$_QC_settings_design_header_quick_message = $_QC_settings_data->design_header_quick_message;
	$_QC_settings_design_header_quick_menu = $_QC_settings_data->design_header_quick_menu;
	$_QC_settings_design_footer_quick_message = $_QC_settings_data->design_footer_quick_message;
}

$_QC_module = DB::getInstance()->query("SELECT * FROM _QC_module WHERE module_name = '" . str_replace(['.php', '-'], ['', ' '], basename($_SERVER['SCRIPT_FILENAME'])) . "'");
foreach ($_QC_module->results() as $_QC_module_data) {
	$_QC_module_id = $_QC_module_data->module_id;
	$_QC_module_name = $_QC_module_data->module_name;
	$_QC_module_uri_base = $_QC_module_data->uri_base;
	$_QC_module_location = $_QC_module_data->location;
	$_QC_module_item_name = $_QC_module_data->item_name;

	# Get $_QC_language data_id
	$_QC_language_item_name = DB::getInstance()->query("SELECT * FROM _QC_language WHERE en = '" . $_QC_module_item_name . "'");
	foreach ($_QC_language_item_name->results() as $_QC_language_item_name_data) {
		$_QC_language_item_name_data_id = $_QC_language_item_name_data->data_id;
	}

	$_QC_language_module_name = DB::getInstance()->query("SELECT * FROM _QC_language WHERE en = '" . $_QC_module_name . "'");
	foreach ($_QC_language_module_name->results() as $_QC_language_module_name_data) {
		$_QC_language_module_name_data_id = $_QC_language_module_name_data->data_id;
	}
}

if ($user->data()->user_id != NULL) {
	$_QU_i_settings = DB::getInstance()->query("SELECT * FROM user_settings WHERE user_id = " . $user->data()->user_id);
	foreach ($_QU_i_settings->results() as $_QU_i_settings_data) {
		
		$config_language = $_QU_i_settings_data->language_id;
		$config_skin	 = $_QU_i_settings_data->theme_id;
		$config_nav		 = $_QU_i_settings_data->nav;
	}

	# QUANTUM GATEWAY CHECKS
	# STEP 1: QUANTUM GATEWAY check for email verification code for user
	$_QG_i = DB::getInstance()->query("SELECT * FROM _QG_i WHERE user_id = " . $user->data()->user_id);
	foreach ($_QG_i->results() as $_QG_i_data) {
		$_QG_email_verification = $_QG_i_data->email_verification;
		$_QG_account_recovery = $_QG_i_data->account_recovery;
		$_QG_status = $_QG_i_data->status;
	}
} else {
	if (!$_GET['lang']) {
		$config_language = 1; // Force en for non-loged users
	} else {
		$config_language = 2; // Force es for non-loged spanish users
	}
}

// QUANTUM CORE Language
$language_pack = DB::getInstance()->query("SELECT * FROM _QC_language");
foreach ($language_pack->results() as $language_pack_data) {
	$config_language == 1 ? $_QC_language[$language_pack_data->data_id] = html_entity_decode($language_pack_data->en) : $_QC_language[$language_pack_data->data_id] = html_entity_decode($language_pack_data->es) ;
}

// _QU_i_group_assoc
$_QU_i_group_assoc = DB::getInstance()->query("SELECT * FROM _QU_i_group_assoc WHERE user_id = " . $user->data()->user_id);
foreach ($_QU_i_group_assoc->results() as $_QU_i_group_assoc_data) {
	$user_i_group = DB::getInstance()->query("SELECT * FROM _QU_i_group WHERE group_id = $_QU_i_group_assoc_data->group_id");
	foreach ($user_i_group->results() as $user_i_group_data) {
		$_QU_i_group = $user_i_group_data->name;
	}
}

// user_i_group_list
$user_i_group_list = DB::getInstance()->query("SELECT * FROM _QU_i_group");
$user_i_group_list_count = $user_i_group_list->count();
foreach ($user_i_group_list->results() as $user_i_group_list_data) {
	$user_i_group_name[$user_i_group_list_data->group_id] = $user_i_group_list_data->name;
}

$user_i_list = DB::getInstance()->query("SELECT * FROM _QU_i");
foreach ($user_i_list->results() as $user_i_list_data) {
	$user_i_name[$user_i_list_data->user_id] = $user_i_list_data->name;
	$user_i_last_name[$user_i_list_data->user_id] = $user_i_list_data->last_name;
}

$module_list = DB::getInstance()->query("SELECT * FROM _QC_module");
foreach ($module_list->results() as $module_list_data) {
	$module_name[$module_list_data->module_id] = $module_list_data->module_name;
	$module_color[$module_list_data->module_id] = $module_list_data->color;
}

# Display quick message?
if ($_QC_settings_design_header_quick_message == 1) {
	$cms_header = DB::getInstance()->query("SELECT * FROM cms_header WHERE header_id = 1");
	foreach ($cms_header->results() as $cms_header_data) {
		$quick_message = str_replace(['<p>','</p>','<a'], ['','','<a style="color:#fff;" '], html_entity_decode($cms_header_data->quick_message));
	}	
}

# Display footer quick message?
if ($_QC_settings_design_footer_quick_message == 1) {
	$cms_footer = DB::getInstance()->query("SELECT * FROM cms_footer WHERE footer_id = 1");
	foreach ($cms_footer->results() as $cms_footer_data) {
		$footer_quick_message = str_replace(['<p>','</p>','<a'], ['','','<a style="color:#fff;" '], html_entity_decode($cms_footer_data->quick_message));
	}	
}

# Logo
$logo_data = DB::getInstance()->query("SELECT * FROM cms_imager_image WHERE use_as_logo = 1");
$logo_data_count = $logo_data->count();
if ($logo_data_count) {
	foreach ($logo_data->results() as $logo_value) {
		$logo_image = $logo_value->file_name;
	}
}

# Favicon
$favicon_data = DB::getInstance()->query("SELECT * FROM cms_imager_image WHERE use_as_favicon = 1");
$favicon_data_count = $favicon_data->count();
if ($favicon_data_count) {
	foreach ($favicon_data->results() as $favicon_value) {
		$favicon_image = $favicon_value->file_name;
	}
}

# geo_state
$geo_state = DB::getInstance()->query("SELECT * FROM geo_state ORDER BY state_id ASC");
$geo_state_count = $geo_state->count();
if ($geo_state_count) {
	foreach ($geo_state->results() as $geo_state_data) {
		$state_abbr[$geo_state_data->state_id] = $geo_state_data->abbr;
		$state_name[$geo_state_data->state_id] = $geo_state_data->name;
	}
}

# geo_county
$geo_county = DB::getInstance()->query("SELECT * FROM geo_county");
$geo_county_count = $geo_county->count();
if ($geo_county_count) {
	foreach ($geo_county->results() as $geo_county_data) {
		$county_name[$geo_county_data->county_id] = $geo_county_data->name;
		$county_state_name = $state_name[$geo_county_data->state_id];
	}
}

# geo_district
$geo_district = DB::getInstance()->query("SELECT geo_district.county_id AS geo_district_county_id, geo_district.district_id, geo_district.name AS geo_district_name, geo_county.state_id, geo_county.county_id AS geo_county_county_id, geo_county.name AS geo_county_name FROM geo_district INNER JOIN geo_county ON geo_district.county_id=geo_county.county_id");
$geo_district_count = $geo_district->count();
if ($geo_district_count) {
	foreach ($geo_district->results() as $geo_district_data) {
		$district_name[$geo_district_data->district_id] = $geo_district_data->geo_district_name;
		$district_county_name = $county_name[$geo_district_data->geo_district_county_id];
		$district_state_name = $state_name[$geo_district_data->state_id];
	}
}

# Navbar
$cms_header_link = DB::getInstance()->query("SELECT * FROM cms_header_link");
foreach ($cms_header_link->results() as $cms_header_link_data) {
    
}

# cms_social_network
$cms_social_network = DB::getInstance()->query("SELECT * FROM cms_social_network WHERE status = 1 ORDER BY data_order ASC");
$cms_social_network_count = $cms_social_network->count();
$cms_social_network_counter = 1;
foreach ($cms_social_network->results() as $cms_social_network_data) {
	$cms_social_network_data_id[$cms_social_network_counter] = $cms_social_network_data->data_id;
	$cms_social_network_uri[$cms_social_network_counter] = $cms_social_network_data->uri;
	$cms_social_network_icon[$cms_social_network_counter] = $cms_social_network_data->icon;
}

# cms_header_link
$cms_header_link = DB::getInstance()->query("SELECT * FROM cms_header_link ORDER BY data_order ASC");
$cms_header_link_count = $cms_header_link->count();
$cms_header_link_counter = 1;
foreach ($cms_header_link->results() as $cms_header_link_data) {
	$cms_header_link_data_id[$cms_header_link_counter] = $cms_header_link_data->data_id;
	$cms_header_link_text[$cms_header_link_counter] = $cms_header_link_data->text;
	$cms_header_link_uri[$cms_header_link_counter] = $cms_header_link_data->uri;
	$cms_header_link_target[$cms_header_link_counter] = $cms_header_link_data->target;
	$cms_header_link_counter++;
}

# cms_contact_phone_number
$cms_contact_phone_number = DB::getInstance()->query("SELECT * FROM cms_contact_phone_number ORDER BY data_id DESC");
$cms_contact_phone_number_count = $cms_contact_phone_number->count();
$cms_contact_phone_number_counter = 1;
foreach ($cms_contact_phone_number->results() as $cms_contact_phone_number_data) {
	$cms_contact_phone_number_data_id[$cms_contact_phone_number_counter] = $cms_contact_phone_number_data->data_id;
	$cms_contact_phone_number_item[$cms_contact_phone_number_counter] = $cms_contact_phone_number_data->item;
	$cms_header_link_counter++;
}

### END GENERAL DATA ###

### NICHE/SITE SPECIFIC DATA ###

// loader_config
$loader_config = DB::getInstance()->query("SELECT * FROM loader_config WHERE config_id = 1");
foreach ($loader_config->results() as $loader_config_data) {
	$linked_checkpoint_max_time_span = $loader_config_data->linked_checkpoint_max_time_span;
}

// Loader language pack
$cms_language_loader = DB::getInstance()->query("SELECT * FROM cms_language_loader");
foreach ($cms_language_loader->results() as $cms_language_loader_data) {
	$config_language == 1 ? $cms_loader_language[$cms_language_loader_data->data_id] = html_entity_decode($cms_language_loader_data->en) : $cms_loader_language[$cms_language_loader_data->data_id] = html_entity_decode($cms_language_loader_data->es) ;
}


// External User
$_QU_e = DB::getInstance()->query("SELECT * FROM _QU_e ORDER BY name ASC");
$_QU_e_count = $_QU_e->count();
$_QU_e_counter = 1;

if ($_QU_e_count) {
	foreach ($_QU_e->results() as $_QU_e_data) {
		
		$_QU_e_name[$_QU_e_data->user_id] = $_QU_e_data->name;
		$_QU_e_last_name[$_QU_e_data->user_id] = $_QU_e_data->last_name;
		$_QU_e_email[$_QU_e_data->user_id] = $_QU_e_data->email;
		$_QU_e_phone_number_01[$_QU_e_data->user_id] = $_QU_e_data->phone_number_01;

		# By counter
		$_QU_e_user_id_ctr[$_QU_e_counter] = $_QU_e_data->user_id;
		$_QU_e_name_ctr[$_QU_e_counter] = ucwords(strtolower($_QU_e_data->name));
		$_QU_e_last_name_ctr[$_QU_e_counter] = ucwords(strtolower($_QU_e_data->last_name));
		$_QU_e_phone_number_01_ctr[$_QU_e_counter] = $_QU_e_data->phone_number_01;
		$_QU_e_email_ctr[$_QU_e_counter] = $_QU_e_data->email;
		$_QU_e_counter++;
	}
}

// Internal User
$_QU_i = DB::getInstance()->query("SELECT * FROM _QU_i ORDER BY name ASC");
$_QU_i_count = $_QU_i->count();
$_QU_i_counter = 1;

if ($_QU_i_count) {
	foreach ($_QU_i->results() as $_QU_i_data) {
		
		$_QU_i_name[$_QU_i_data->user_id] = $_QU_i_data->name;
		$_QU_i_last_name[$_QU_i_data->user_id] = $_QU_i_data->last_name;
		$_QU_i_email[$_QU_i_data->user_id] = $_QU_i_data->email;

		# By counter
		$_QU_i_user_id_ctr[$_QU_i_counter] = $_QU_i_data->user_id;
		$_QU_i_name_ctr[$_QU_i_counter] = ucwords(strtolower($_QU_i_data->name));
		$_QU_i_last_name_ctr[$_QU_i_counter] = ucwords(strtolower($_QU_i_data->last_name));
		$_QU_i_email_ctr[$_QU_i_counter] = $_QU_i_data->email;
		$_QU_e_counter++;
	}
}

// Internal User [DISPATCHERS]
$dispatcher = DB::getInstance()->query("SELECT _QU_i.user_id, _QU_i.name, _QU_i.last_name, _QU_i_group_assoc.group_id, _QU_i_group_assoc.user_id FROM _QU_i INNER JOIN _QU_i_group_assoc ON _QU_i.user_id=_QU_i_group_assoc.user_id WHERE group_id IN (1, 3) ORDER BY name ASC");
$dispatcher_count = $dispatcher->count();
$dispatcher_counter = 1;

if ($dispatcher_count) {
	foreach ($dispatcher->results() as $dispatcher_data) {
		
		$dispatcher_name[$dispatcher_data->user_id] = $dispatcher_data->name;
		$dispatcher_last_name[$dispatcher_data->user_id] = $dispatcher_data->last_name;

		# By counter
		$dispatcher_user_id_ctr[$dispatcher_counter] = $dispatcher_data->user_id;
		$dispatcher_name_ctr[$dispatcher_counter] = ucwords(strtolower($dispatcher_data->name));
		$dispatcher_last_name_ctr[$dispatcher_counter] = ucwords(strtolower($dispatcher_data->last_name));
		$dispatcher_counter++;
	}
}

// External User
// This list excludes users that are already linked with a client profile (in user_e_profile_client_user)
$available_external_user = DB::getInstance()->query("SELECT * FROM _QU_e WHERE user_id NOT IN (SELECT user_id FROM user_e_profile_client_user)");
$available_external_user_count = $available_external_user->count();
$available_external_user_counter = 1;

if ($available_external_user_count) {
	foreach ($available_external_user->results() as $available_external_user_data) {
		
		$available_external_user_name[$available_external_user_data->user_id] = $available_external_user_data->name;
		$available_external_user_last_name[$available_external_user_data->user_id] = $available_external_user_data->last_name;
		$available_external_user_email[$available_external_user_data->user_id] = $available_external_user_data->email;

		# By counter
		$available_external_user_user_id[$available_external_user_counter] = $available_external_user_data->user_id;
		$available_external_user_name[$available_external_user_counter] = ucwords(strtolower($available_external_user_data->name));
		$available_external_user_last_name[$available_external_user_counter] = ucwords(strtolower($available_external_user_data->last_name));
		$available_external_user_email[$available_external_user_counter] = $available_external_user_data->email;
		$available_external_user_counter++;
	}
}

// user_e_language_profile_broker
$user_e_language_profile_broker = DB::getInstance()->query("SELECT * FROM user_e_language_profile_broker");
foreach ($user_e_language_profile_broker->results() as $user_e_language_profile_broker_data) {
	$config_language == 1 ? $user_e_language_broker_profile[$user_e_language_profile_broker_data->data_id] = html_entity_decode($user_e_language_profile_broker_data->en) : $user_e_language_broker_profile[$user_e_language_profile_broker_data->data_id] = html_entity_decode($user_e_language_profile_broker_data->es) ;
}

// user_e_language_profile_driver
$user_e_language_profile_driver = DB::getInstance()->query("SELECT * FROM user_e_language_profile_driver");
foreach ($user_e_language_profile_driver->results() as $user_e_language_profile_driver_data) {
	$config_language == 1 ? $user_e_language_driver_profile[$user_e_language_profile_driver_data->data_id] = html_entity_decode($user_e_language_profile_driver_data->en) : $user_e_language_driver_profile[$user_e_language_profile_driver_data->data_id] = html_entity_decode($user_e_language_profile_driver_data->es) ;
}

// user_e_language_profile_client
$user_e_language_profile_client = DB::getInstance()->query("SELECT * FROM user_e_language_profile_client");
foreach ($user_e_language_profile_client->results() as $user_e_language_profile_client_data) {
	$config_language == 1 ? $user_e_language_client_profile[$user_e_language_profile_client_data->data_id] = html_entity_decode($user_e_language_profile_client_data->en) : $user_e_language_client_profile[$user_e_language_profile_client_data->data_id] = html_entity_decode($user_e_language_profile_client_data->es) ;
}

// user_e_profile_driver
$user_e_profile_driver = DB::getInstance()->query("SELECT * FROM user_e_profile_driver");
foreach ($user_e_profile_driver->results() as $user_e_profile_driver_data) {
	$driver_profile_user_id[$user_e_profile_driver_data->data_id] = $user_e_profile_driver_data->user_id;
}

// loader_file_label
$loader_file_label = DB::getInstance()->query("SELECT * FROM loader_file_label ORDER BY data_id ASC");
$loader_file_label_count = $loader_file_label->count();
$loader_file_label_counter = 1;

foreach ($loader_file_label->results() as $loader_file_label_data) {
	$label_data_id[$loader_file_label_counter] = $loader_file_label_data->data_id;
	$label_name[$loader_file_label_counter] = html_entity_decode($loader_file_label_data->name);
	$loader_file_label_counter++;

	# Grab id from $_GET['upload']
	if ($_GET['upload'] == $label_data_id[$loader_file_label_counter]) {
		
		$upload_label_id = $label_data_id[$loader_file_label_counter];
	}
}

# user_e_profile_client
$limbo_user_e_profile_client = DB::getInstance()->query("SELECT * FROM user_e_profile_client ORDER BY company_name ASC");
$limbo_user_e_profile_client_count = $limbo_user_e_profile_client->count();
$limbo_user_e_profile_client_counter = 1;
if ($limbo_user_e_profile_client_count) {
	foreach ($limbo_user_e_profile_client->results() as $limbo_user_e_profile_client_data) {
		// by counter
		$limbo_client_user_id[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->user_id;
		$limbo_client_data_id[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->data_id;
		$limbo_client_company_name[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->company_name;
		$limbo_client_mc_number[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->mc_number;
		$limbo_client_us_dot_number[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->us_dot_number;
		$limbo_client_ein_number[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->ein_number;
		$limbo_client_phone_number_01[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->phone_number_01;
		$limbo_client_phone_number_02[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->phone_number_02;
		$limbo_client_address_line_1[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->address_line_1;
		$limbo_client_address_line_2[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->address_line_2;
		$limbo_client_city[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->city;
		$limbo_client_state_id[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->state_id;
		$limbo_client_zip_code[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->zip_code;
		$limbo_client_mailing_use_physical[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->mailing_use_physical;
		$limbo_client_billing_address_line_1[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->billing_address_line_1;
		$limbo_client_billing_address_line_2[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->billing_address_line_2;
		$limbo_client_billing_city[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->billing_city;
		$limbo_client_billing_state_id[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->billing_state_id;
		$limbo_client_billing_zip_code[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->billing_zip_code;
		$limbo_client_invoice_color[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->invoice_color;
		$limbo_client_invoice_color[$limbo_user_e_profile_client_counter] = html_entity_decode($limbo_user_e_profile_client_data->invoice_color);
		$limbo_client_status[$limbo_user_e_profile_client_counter] = $limbo_user_e_profile_client_data->status;
		$limbo_client_added[$limbo_user_e_profile_client_counter] = date('M d, Y', strtotime($limbo_user_e_profile_client_data->added));

		// by id
		$limbo_client_user_id_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->user_id;
		$limbo_client_company_name_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->company_name;
		$limbo_client_mc_number_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->mc_number;
		$limbo_client_us_dot_number_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->us_dot_number;
		$limbo_client_ein_number_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->ein_number;
		$limbo_client_phone_number_01_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->phone_number_01;
		$limbo_client_phone_number_02_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->phone_number_02;
		$limbo_client_address_line_1_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->address_line_1;
		$limbo_client_address_line_2_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->address_line_2;
		$limbo_client_city_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->city;
		$limbo_client_state_id_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->state_id;
		$limbo_client_zip_code_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->zip_code;
		$limbo_client_mailing_use_physical_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->mailing_use_physical;
		$limbo_client_billing_address_line_1_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->billing_address_line_1;
		$limbo_client_billing_address_line_2_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->billing_address_line_2;
		$limbo_client_billing_city_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->billing_city;
		$limbo_client_billing_state_id_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->billing_state_id;
		$limbo_client_billing_zip_code_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->billing_zip_code;
		$limbo_client_invoice_color_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->invoice_color;
		$limbo_client_invoice_color_by_data_id[$limbo_user_e_profile_client_data->data_id] = html_entity_decode($limbo_user_e_profile_client_data->invoice_color);
		$limbo_client_rate_id_did[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->rate_id;
		$limbo_client_status_by_data_id[$limbo_user_e_profile_client_data->data_id] = $limbo_user_e_profile_client_data->status;
		$limbo_client_added_by_data_id[$limbo_user_e_profile_client_data->data_id] = date('M d, Y', strtotime($limbo_user_e_profile_client_data->added));

		$limbo_user_e_profile_client_counter++;
	}
}

# client_billing_address
# Gets real addresses after adding the mailing_use_physical checkbox
$client_billing_address = DB::getInstance()->query("SELECT * FROM user_e_profile_client ORDER BY company_name ASC");
$client_billing_address_count = $client_billing_address->count();
$client_billing_address_counter = 1;
if ($client_billing_address_count) {
	foreach ($client_billing_address->results() as $client_billing_address_data) {
		if ($client_billing_address_data->mailing_use_physical == 1) {
			# Use physical

			$client_billing_address_line_1[$client_billing_address_data->data_id] = $client_billing_address_data->address_line_1;
			$client_billing_address_line_2[$client_billing_address_data->data_id] = $client_billing_address_data->address_line_2;
			$client_billing_address_city[$client_billing_address_data->data_id] = $client_billing_address_data->city;
			$client_billing_address_state_id[$client_billing_address_data->data_id] = $client_billing_address_data->state_id;
			$client_billing_address_zip_code[$client_billing_address_data->data_id] = $client_billing_address_data->zip_code;
		} else {
			# Use mailing

			$client_billing_address_line_1[$client_billing_address_data->data_id] = $client_billing_address_data->billing_address_line_1;
			$client_billing_address_line_2[$client_billing_address_data->data_id] = $client_billing_address_data->billing_address_line_2;
			$client_billing_address_city[$client_billing_address_data->data_id] = $client_billing_address_data->billing_city;
			$client_billing_address_state_id[$client_billing_address_data->data_id] = $client_billing_address_data->billing_state_id;
			$client_billing_address_zip_code[$client_billing_address_data->data_id] = $client_billing_address_data->billing_zip_code;
		}
	}
}

# active quickpay brokers
$quickpay_broker = DB::getInstance()->query("SELECT * FROM user_e_profile_broker WHERE quickpay = 1 && status = 1 ORDER BY company_name ASC");
$quickpay_broker_count = $quickpay_broker->count();
$quickpay_broker_counter = 1;
if ($quickpay_broker_count) {
	foreach ($quickpay_broker->results() as $quickpay_broker_data) {
		// by counter
		$quickpay_broker_user_id[$quickpay_broker_counter] = $quickpay_broker_data->user_id;
		$quickpay_broker_data_id[$quickpay_broker_counter] = $quickpay_broker_data->data_id;
		$quickpay_broker_company_name[$quickpay_broker_counter] = html_entity_decode($quickpay_broker_data->company_name);
		$quickpay_broker_contact_name[$quickpay_broker_counter] = $quickpay_broker_data->contact_name;
		$quickpay_broker_contact_phone_number_01[$quickpay_broker_counter] = $quickpay_broker_data->contact_phone_number_01;
		$quickpay_broker_phone_number_01[$quickpay_broker_counter] = $quickpay_broker_data->phone_number_01;
		$quickpay_broker_accounts_payable_number[$quickpay_broker_counter] = $quickpay_broker_data->accounts_payable_number;
		$quickpay_broker_fax_number[$quickpay_broker_counter] = $quickpay_broker_data->fax_number;
		$quickpay_broker_quick_pay_email[$quickpay_broker_counter] = $quickpay_broker_data->quickpay_email;
		$quickpay_broker_address_line_1[$quickpay_broker_counter] = $quickpay_broker_data->address_line_1;
		$quickpay_broker_address_line_2[$quickpay_broker_counter] = $quickpay_broker_data->address_line_2;
		$quickpay_broker_address_line_3[$quickpay_broker_counter] = $quickpay_broker_data->address_line_3;
		$quickpay_broker_city[$quickpay_broker_counter] = $quickpay_broker_data->city;
		$quickpay_broker_state_id[$quickpay_broker_counter] = $quickpay_broker_data->state_id;
		$quickpay_broker_zip_code[$quickpay_broker_counter] = $quickpay_broker_data->zip_code;
		$quickpay_broker_added[$quickpay_broker_counter] = date('M d, Y', strtotime($quickpay_broker_data->added));

		// by data_id
		$quickpay_broker_user_id_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->user_id;
		$quickpay_broker_company_name_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->company_name;
		$quickpay_broker_contact_name_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->contact_name;
		$quickpay_broker_contact_phone_number_01_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->contact_phone_number_01;
		$quickpay_broker_phone_number_01_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->phone_number_01;
		$quickpay_broker_accounts_payable_number_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->accounts_payable_number;
		$quickpay_broker_fax_number_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->fax_number;
		$quickpay_broker_quick_pay_email_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->quickpay_email;
		$quickpay_broker_address_line_1_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->address_line_1;
		$quickpay_broker_address_line_2_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->address_line_2;
		$quickpay_broker_address_line_3_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->address_line_3;
		$quickpay_broker_city_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->city;
		$quickpay_broker_state_id_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->state_id;
		$quickpay_broker_zip_code_by_data_id[$quickpay_broker_data->data_id] = $quickpay_broker_data->zip_code;
		$quickpay_broker_added_by_data_id[$quickpay_broker_data->data_id] = date('M d, Y', strtotime($quickpay_broker_data->added));

		$quickpay_broker_counter++;
	}
}

# broker_quickpay_service_fee
# $broker_id_value holds the broker id, if $_GET['broker_id'] is set, change
# default value
if ($_GET['broker_id']) {
	$broker_id_value = $_GET['broker_id'];
} else {
	$broker_id_value = $_GET['id'];
}

# The following block (# Get ALL broker companies) requires data from this 
# block, it needs to be avobe.
$loader_quickpay_service_fee = DB::getInstance()->query("SELECT * FROM broker_quickpay_service_fee WHERE broker_id = " . $broker_id_value . " ORDER BY fee ASC");
$loader_quickpay_service_fee_count = $loader_quickpay_service_fee->count();
$loader_quickpay_service_fee_counter = 1;

if ($loader_quickpay_service_fee_count) {
	foreach($loader_quickpay_service_fee->results() as $loader_quickpay_service_fee_data) {

		// By counter
		$quickpay_service_fee_data_id[$loader_quickpay_service_fee_counter] = $loader_quickpay_service_fee_data->data_id;
		$quickpay_service_fee[$loader_quickpay_service_fee_counter] = $loader_quickpay_service_fee_data->fee;
		$quickpay_service_method_id[$loader_quickpay_service_fee_counter] = $loader_quickpay_service_fee_data->method_id;
		$quickpay_service_number_of_days[$loader_quickpay_service_fee_counter] = $loader_quickpay_service_fee_data->number_of_days;
		$loader_quickpay_service_fee_counter++;

		// By data_id
		$quickpay_service_fee_data_id_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->data_id;
		$quickpay_service_fee_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->fee;
		$quickpay_service_method_id_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->method_id;
		$quickpay_service_number_of_days_did[$loader_quickpay_service_fee_data->data_id] = $loader_quickpay_service_fee_data->number_of_days;
	}
}

# Get ALL broker companies
$broker_co = DB::getInstance()->query("SELECT * FROM user_e_profile_broker ORDER BY company_name ASC");
$broker_co_count = $broker_co->count();
$broker_co_counter = 1;

if ($broker_co_count) {
	foreach ($broker_co->results() as $broker_co_data) {
		// Brokers by $broker_co_counter
		$broker_co_data_id[$broker_co_counter] = $broker_co_data->data_id;
		$broker_co_name[$broker_co_counter] = html_entity_decode($broker_co_data->company_name);
		$broker_co_contact_name[$broker_co_counter] = htmlspecialchars_decode($broker_co_data->contact_name);
		$broker_co_contact_phone_number_01[$broker_co_counter] = $broker_co_data->contact_phone_number_01;
		$broker_co_phone_number_01[$broker_co_counter] = $broker_co_data->phone_number_01;
		$broker_co_accounts_payable_number[$broker_co_counter] = $broker_co_data->accounts_payable_number;
		$broker_co_fax_number[$broker_co_counter] = $broker_co_data->fax_number;
		$broker_co_quickpay[$broker_co_counter] = $broker_co_data->quickpay;
		$broker_co_quickpay_email[$broker_co_counter] = $broker_co_data->quickpay_email;
		$broker_co_quickpay_service_charge_percentage[$broker_co_counter] = $broker_co_data->quickpay_service_charge_percentage;
		$broker_co_address_line_1[$broker_co_counter] = htmlspecialchars_decode($broker_co_data->address_line_1);
		$broker_co_address_line_2[$broker_co_counter] = htmlspecialchars_decode($broker_co_data->address_line_2);
		$broker_co_address_line_3[$broker_co_counter] = htmlspecialchars_decode($broker_co_data->address_line_3);
		$broker_co_city[$broker_co_counter] = htmlspecialchars_decode($broker_co_data->city);
		$broker_co_state_id[$broker_co_counter] = $broker_co_data->state_id;
		$broker_co_zip_code[$broker_co_counter] = $broker_co_data->zip_code;
		$broker_co_status[$broker_co_counter] = $broker_co_data->status;
		$broker_co_do_not_use_reason[$broker_co_counter] = $broker_co_data->do_not_use_reason;
		$broker_co_added[$broker_co_counter] = $broker_co_data->added;
		$broker_co_user_id[$broker_co_counter] = $broker_co_data->user_id;

		$broker_co_counter++;

		// Brokers by data_id
		$broker_co_name_did[$broker_co_data->data_id] = html_entity_decode($broker_co_data->company_name);
		$broker_co_contact_name_did[$broker_co_data->data_id] = htmlspecialchars_decode($broker_co_data->contact_name);
		$broker_co_contact_phone_number_01_did[$broker_co_data->data_id] = $broker_co_data->contact_phone_number_01;
		$broker_co_phone_number_01_did[$broker_co_data->data_id] = $broker_co_data->phone_number_01;
		$broker_co_accounts_payable_number_did[$broker_co_data->data_id] = $broker_co_data->accounts_payable_number;
		$broker_co_fax_number_did[$broker_co_data->data_id] = $broker_co_data->fax_number;
		$broker_co_quickpay_did[$broker_co_data->data_id] = $broker_co_data->quickpay;
		$broker_co_quickpay_email_did[$broker_co_data->data_id] = $broker_co_data->quickpay_email;
		$broker_co_quickpay_service_charge_percentage_did[$broker_co_data->data_id] = $broker_co_data->quickpay_service_charge_percentage;
		$broker_co_address_line_1_did[$broker_co_data->data_id] = htmlspecialchars_decode($broker_co_data->address_line_1);
		$broker_co_address_line_2_did[$broker_co_data->data_id] = htmlspecialchars_decode($broker_co_data->address_line_2);
		$broker_co_address_line_3_did[$broker_co_data->data_id] = htmlspecialchars_decode($broker_co_data->address_line_3);
		$broker_co_city_did[$broker_co_data->data_id] = htmlspecialchars_decode($broker_co_data->city);
		$broker_co_state_id_did[$broker_co_data->data_id] = $broker_co_data->state_id;
		$broker_co_zip_code_did[$broker_co_data->data_id] = $broker_co_data->zip_code;
		$broker_co_status_did[$broker_co_data->data_id] = $broker_co_data->status;
		$broker_co_do_not_use_reason_did[$broker_co_data->data_id] = $broker_co_data->do_not_use_reason;
		$broker_co_added_did[$broker_co_data->data_id] = $broker_co_data->added;
		$broker_co_user_id_did[$broker_co_data->data_id] = $broker_co_data->user_id;

	} // end of foreach

	# Active radio button lock
	# Locks the active radio button if quickpay is set for broker and there are
	# 0 entries on the "Broker Quickpay Service Fees ($loader_quickpay_service_fee_count)" section.
	if ($broker_co_quickpay_did[$_GET['id']] == 1 && !$loader_quickpay_service_fee_count) {
		$lock_quickpay_no_service_fee = 1;
	}
}

# Factoring companies
$_GET['factoring_company_id'] ? 
	$factoring_company = DB::getInstance()->query("SELECT * FROM factoring_company WHERE data_id = " . $_GET['factoring_company_id']) 
	: $factoring_company = DB::getInstance()->query("SELECT * FROM factoring_company ORDER BY name ASC") ;

$factoring_company_count = $factoring_company->count();
$factoring_company_counter = 1;
if ($factoring_company_count) {
	foreach ($factoring_company->results() as $factoring_company_data) {

		// Counter
		$factoring_company_data_id[$factoring_company_counter] = $factoring_company_data->data_id;
		$factoring_company_name[$factoring_company_counter] = html_entity_decode($factoring_company_data->name);
		$factoring_company_uri[$factoring_company_counter] = $factoring_company_data->uri;
		$factoring_company_invoicing_email[$factoring_company_counter] = $factoring_company_data->invoicing_email;
		$factoring_company_phone_number_01[$factoring_company_counter] = $factoring_company_data->phone_number_01;
		$factoring_company_fax[$factoring_company_counter] = $factoring_company_data->fax;
		$factoring_company_batch_schedule[$factoring_company_counter] = $factoring_company_data->batch_schedule;
		$factoring_company_status[$factoring_company_counter] = $factoring_company_data->status;
		$factoring_company_added[$factoring_company_counter] = date('M d, Y', strtotime($factoring_company_data->added));
		$factoring_company_user_id[$factoring_company_counter] = $factoring_company_data->user_id;

		// data_id
		$factoring_company_name_did[$factoring_company_data->data_id] = html_entity_decode($factoring_company_data->name);
		$factoring_company_uri_did[$factoring_company_data->data_id] = $factoring_company_data->uri;
		$factoring_company_invoicing_email_did[$factoring_company_data->data_id] = $factoring_company_data->invoicing_email;
		$factoring_company_batch_schedule_did[$factoring_company_data->data_id] = $factoring_company_data->batch_schedule;

		$factoring_company_counter++;
	}
}

# Factoring company contacts
$factoring_company_contact = DB::getInstance()->query("SELECT * FROM factoring_company_contact WHERE factoring_company_id = " . $_GET['factoring_company_id'] . " ORDER BY name ASC");
$factoring_company_contact_count = $factoring_company_contact->count();
$factoring_company_contact_counter = 1;

if ($factoring_company_contact_count) {
	foreach ($factoring_company_contact->results() as $factoring_company_contact_data) {
		
		// by counter
		$factoring_company_contact_data_id[$factoring_company_contact_counter] = $factoring_company_contact_data->data_id;
		$factoring_company_contact_name[$factoring_company_contact_counter] = html_entity_decode($factoring_company_contact_data->name);
		$factoring_company_contact_last_name[$factoring_company_contact_counter] = html_entity_decode($factoring_company_contact_data->last_name);
		$factoring_company_contact_title[$factoring_company_contact_counter] = html_entity_decode($factoring_company_contact_data->title);
		$factoring_company_contact_email[$factoring_company_contact_counter] = html_entity_decode($factoring_company_contact_data->email);
		$factoring_company_contact_phone_number_01[$factoring_company_contact_counter] = html_entity_decode($factoring_company_contact_data->phone_number_01);

		$factoring_company_contact_counter++;		
	}
}

# Factoring company address
$factoring_company_address = DB::getInstance()->query("SELECT * FROM factoring_company_address WHERE factoring_company_id = " . $_GET['factoring_company_id']);
$factoring_company_address_count = $factoring_company_address->count();
$factoring_company_address_counter = 1;

if ($factoring_company_address_count) {
	foreach ($factoring_company_address->results() as $factoring_company_address_data) {
		
		// by counter
		$factoring_company_address_data_id[$factoring_company_address_counter] = $factoring_company_address_data->data_id;
		$factoring_company_address_type[$factoring_company_address_counter] = $factoring_company_address_data->address_type;
		$factoring_company_address_line_1[$factoring_company_address_counter] = html_entity_decode($factoring_company_address_data->line_1);
		$factoring_company_address_line_2[$factoring_company_address_counter] = html_entity_decode($factoring_company_address_data->line_2);
		$factoring_company_address_line_3[$factoring_company_address_counter] = html_entity_decode($factoring_company_address_data->line_3);
		$factoring_company_address_city[$factoring_company_address_counter] = html_entity_decode($factoring_company_address_data->city);
		$factoring_company_address_state_id[$factoring_company_address_counter] = $factoring_company_address_data->state_id;
		$factoring_company_address_zip_code[$factoring_company_address_counter] = html_entity_decode($factoring_company_address_data->zip_code);

		$factoring_company_address_counter++;		
	}
}

# Factoring company service fee
$_GET['factoring_company_id'] ? 
	$factoring_company_service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee WHERE factoring_company_id = " . $_GET['factoring_company_id']) 
	: $factoring_company_service_fee = DB::getInstance()->query("SELECT * FROM factoring_company_service_fee") ;
	
$factoring_company_service_fee_count = $factoring_company_service_fee->count();
$factoring_company_service_fee_counter = 1;

if ($factoring_company_service_fee_count) {
	foreach ($factoring_company_service_fee->results() as $factoring_company_service_fee_data) {
		
		// counter
		$factoring_company_service_fee_factoring_company_id[$factoring_company_service_fee_counter] = $factoring_company_service_fee_data->factoring_company_id;
		$factoring_company_service_fee_data_id[$factoring_company_service_fee_counter] = $factoring_company_service_fee_data->data_id;
		$factoring_company_service_fee_fee[$factoring_company_service_fee_counter] = $factoring_company_service_fee_data->fee;
		$factoring_company_service_fee_method_id[$factoring_company_service_fee_counter] = $factoring_company_service_fee_data->method_id;
		$factoring_company_service_fee_number_of_days[$factoring_company_service_fee_counter] = $factoring_company_service_fee_data->number_of_days;

		// data_id
		$factoring_company_service_fee_factoring_company_id_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->factoring_company_id;
		$factoring_company_service_fee_fee_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->fee;
		$factoring_company_service_fee_method_id_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->method_id;
		$factoring_company_service_fee_number_of_days_did[$factoring_company_service_fee_data->data_id] = $factoring_company_service_fee_data->number_of_days;

		$factoring_company_service_fee_counter++;		
	}
}

# Factoring company client assoc
$factoring_company_client_assoc = DB::getInstance()->query("SELECT * FROM factoring_company_client_assoc WHERE client_id = " . $_GET['id']);
$factoring_company_client_assoc_count = $factoring_company_client_assoc->count();
if ($factoring_company_client_assoc_count) {
	foreach ($factoring_company_client_assoc->results() as $factoring_company_client_assoc_data) {

		$factoring_company_client_assoc_factoring_company_id = $factoring_company_client_assoc_data->factoring_company_id;
		$factoring_company_client_assoc_main = $factoring_company_client_assoc_data->main;
		$factoring_company_client_assoc_alt = $factoring_company_client_assoc_data->alt;
		$factoring_company_client_assoc_counter = $factoring_company_client_assoc_data->counter;
	}
}

# Runs only on /0/loader-status-change-notification.php & /0/loader-load-quickpay-invoicing.php
# $_SERVER['SCRIPT_NAME'] == '/0/loader-load-quickpay-invoicing.php' must be dropped after moving to $_SERVER['SCRIPT_NAME'] == '/0/quickpay-invoicing.php'
if ($_SERVER['SCRIPT_NAME'] == '/0/loader-status-change-notification.php' || $_SERVER['SCRIPT_NAME'] == '/0/loader-load-quickpay-invoicing.php' || $_SERVER['SCRIPT_NAME'] == '/0/quickpay-invoicing.php') {
	# Get load_number
 	$loader_load_number = DB::getInstance()->query("SELECT * FROM loader_load WHERE load_id = " . $_GET['load_id']);
 	foreach ($loader_load_number->results() as $loader_load_number_data) {
 		$load_number = html_entity_decode($loader_load_number_data->load_number);
 		$line_haul = $loader_load_number_data->line_haul;
 	}
}

# Runs only on /0/loader-load-quickpay-invoicing.php && /0/loader
# $_SERVER['SCRIPT_NAME'] == '/0/loader-load-quickpay-invoicing.php' must be dropped after moving to $_SERVER['SCRIPT_NAME'] == '/0/quickpay-invoicing.php'
if ($_SERVER['SCRIPT_NAME'] == '/0/loader-load-quickpay-invoicing.php' || $_SERVER['SCRIPT_NAME'] == '/0/loader.php' || $_SERVER['SCRIPT_NAME'] == '/0/quickpay-invoicing.php') {
	# Get first pickup
 	$loader_checkpoint_first_pick_up = DB::getInstance()->query("SELECT date_time FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . " && data_type = 0 ORDER BY date_time ASC LIMIT 1");
 	$loader_checkpoint_first_pick_up_count = $loader_checkpoint_first_pick_up->count();
 	foreach ($loader_checkpoint_first_pick_up->results() as $loader_checkpoint_first_pick_up_data) {
 		$first_pick_up = date('M d, Y', strtotime($loader_checkpoint_first_pick_up_data->date_time));
 	}
 	
 	# Get last drop off
 	$loader_checkpoint_last_drop_off = DB::getInstance()->query("SELECT loader_checkpoint.checkpoint_id, loader_checkpoint.date_time FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . " && data_type = 1 ORDER BY date_time DESC LIMIT 1");
 	$loader_checkpoint_last_drop_off_count = $loader_checkpoint_last_drop_off->count();
 	foreach ($loader_checkpoint_last_drop_off->results() as $loader_checkpoint_last_drop_off_data) {
 		$last_drop_off = date('M d, Y', strtotime($loader_checkpoint_last_drop_off_data->date_time));
 		$last_drop_off_checkpoint_id = $loader_checkpoint_last_drop_off_data->checkpoint_id;
 	}

 	# Checkpoints (pick ups)
	$loader_checkpoint_pick_up = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . ' && data_type = 0 ORDER BY date_time ASC');
	$loader_checkpoint_pick_up_count = $loader_checkpoint_pick_up->count();
	if ($loader_checkpoint_pick_up_count) {
		$checkpointCounter = 1;
		foreach ($loader_checkpoint_pick_up->results() as $loader_checkpoint_pick_up_data) {
			$checkpoint_pick_up_id[$checkpointCounter] = $loader_checkpoint_pick_up_data->checkpoint_id;
			$checkpoint_pick_up_date[$checkpointCounter] = date('M d, Y', strtotime($loader_checkpoint_pick_up_data->date_time));
			$checkpoint_pick_up_time[$checkpointCounter] = date('G:i', strtotime($loader_checkpoint_pick_up_data->date_time));
			$checkpoint_pick_up_line_1[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->line_1);
			$checkpoint_pick_up_line_2[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->line_2);
			$checkpoint_pick_up_city[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->city);
			$checkpoint_pick_up_state_id[$checkpointCounter] = $loader_checkpoint_pick_up_data->state_id;
			$checkpoint_pick_up_zip_code[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->zip_code);
			$checkpoint_pick_up_contact[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->contact);
			$checkpoint_pick_up_appointment[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->appointment);
			$checkpoint_pick_up_notes[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->notes);
			$checkpoint_pick_up_data_type[$checkpointCounter] = $loader_checkpoint_pick_up_data->data_type;
			$checkpoint_pick_up_added[$checkpointCounter] = date('M d, Y', strtotime($loader_checkpoint_pick_up_data->added));
			$checkpoint_pick_up_added_time[$checkpointCounter] = date('h:i:s a', strtotime($loader_checkpoint_pick_up_data->added));
			$checkpoint_pick_up_user_id[$checkpointCounter] = $loader_checkpoint_pick_up_data->user_id;
			$checkpoint_pick_up_status[$checkpointCounter] = $loader_checkpoint_pick_up_data->status;

			$checkpointCounter++;
		}				
	}

	# Checkpoints (drop offs)
	$loader_checkpoint_drop_off = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . ' && data_type = 1 ORDER BY date_time ASC');
	$loader_checkpoint_drop_off_count = $loader_checkpoint_drop_off->count();
	if ($loader_checkpoint_drop_off_count) {
		$checkpointCounter = 1;
		foreach ($loader_checkpoint_drop_off->results() as $loader_checkpoint_drop_off_data) {
			$checkpoint_drop_off_id[$checkpointCounter] = $loader_checkpoint_drop_off_data->checkpoint_id;
			$checkpoint_drop_off_date[$checkpointCounter] = date('M d, Y', strtotime($loader_checkpoint_drop_off_data->date_time));
			$checkpoint_drop_off_time[$checkpointCounter] = date('G:i', strtotime($loader_checkpoint_drop_off_data->date_time));
			$checkpoint_drop_off_line_1[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->line_1);
			$checkpoint_drop_off_line_2[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->line_2);
			$checkpoint_drop_off_city[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->city);
			$checkpoint_drop_off_state_id[$checkpointCounter] = $loader_checkpoint_drop_off_data->state_id;
			$checkpoint_drop_off_zip_code[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->zip_code);
			$checkpoint_drop_off_contact[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->contact);
			$checkpoint_drop_off_appointment[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->appointment);
			$checkpoint_drop_off_notes[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->notes);
			$checkpoint_drop_off_data_type[$checkpointCounter] = $loader_checkpoint_drop_off_data->data_type;
			$checkpoint_drop_off_added[$checkpointCounter] = date('M d, Y', strtotime($loader_checkpoint_drop_off_data->added));
			$checkpoint_drop_off_added_time[$checkpointCounter] = date('h:i:s a', strtotime($loader_checkpoint_drop_off_data->added));
			$checkpoint_drop_off_user_id[$checkpointCounter] = $loader_checkpoint_drop_off_data->user_id;
			$checkpoint_drop_off_status[$checkpointCounter] = $loader_checkpoint_drop_off_data->status;

			$checkpointCounter++;
		}				
	}
}

# loader_trailer_types
# Trailer types examples are: flatbeds and step-decks
$loader_trailer_types = DB::getInstance()->query("SELECT * FROM loader_trailer_types ORDER BY name ASC");
$loader_trailer_types_count = $loader_trailer_types->count();
$loader_trailer_types_counter = 1;

if ($loader_trailer_types_count) {
	foreach ($loader_trailer_types->results() as $loader_trailer_types_data) {
		$trailer_types_data_id[$loader_trailer_types_counter] = $loader_trailer_types_data->data_id;
		$trailer_types_name[$loader_trailer_types_counter] = html_entity_decode($loader_trailer_types_data->name);

		# By data_id
		$trailer_types_name_did[$loader_trailer_types_data->data_id] = html_entity_decode($loader_trailer_types_data->name);

		$loader_trailer_types_counter++;
	}
}

# loader_trailer_deck_material
$loader_trailer_deck_material = DB::getInstance()->query("SELECT * FROM loader_trailer_deck_material ORDER BY name ASC");
$loader_trailer_deck_material_count = $loader_trailer_deck_material->count();
$loader_trailer_deck_material_counter = 1;

if ($loader_trailer_deck_material_count) {
	foreach ($loader_trailer_deck_material->results() as $loader_trailer_deck_material_data) {
		$trailer_deck_material_data_id[$loader_trailer_deck_material_counter] = $loader_trailer_deck_material_data->data_id;
		$trailer_deck_material_name[$loader_trailer_deck_material_counter] = html_entity_decode($loader_trailer_deck_material_data->name);

		# By data_id
		$trailer_deck_material_name_did[$loader_trailer_deck_material_data->data_id] = html_entity_decode($loader_trailer_deck_material_data->name);

		$loader_trailer_deck_material_counter++;
	}
}

# loader_trailer_door_type
$loader_trailer_door_type = DB::getInstance()->query("SELECT * FROM loader_trailer_door_type ORDER BY name ASC");
$loader_trailer_door_type_count = $loader_trailer_door_type->count();
$loader_trailer_door_type_counter = 1;

if ($loader_trailer_door_type_count) {
	foreach ($loader_trailer_door_type->results() as $loader_trailer_door_type_data) {
		$trailer_door_type_data_id[$loader_trailer_door_type_counter] = $loader_trailer_door_type_data->data_id;
		$trailer_door_type_name[$loader_trailer_door_type_counter] = html_entity_decode($loader_trailer_door_type_data->name);

		# By data_id
		$trailer_door_type_name_did[$loader_trailer_door_type_data->data_id] = html_entity_decode($loader_trailer_door_type_data->name);

		$loader_trailer_door_type_counter++;
	}
}

# loader_trailer_roof_type
$loader_trailer_roof_type = DB::getInstance()->query("SELECT * FROM loader_trailer_roof_type ORDER BY name ASC");
$loader_trailer_roof_type_count = $loader_trailer_roof_type->count();
$loader_trailer_roof_type_counter = 1;

if ($loader_trailer_roof_type_count) {
	foreach ($loader_trailer_roof_type->results() as $loader_trailer_roof_type_data) {
		$trailer_roof_type_data_id[$loader_trailer_roof_type_counter] = $loader_trailer_roof_type_data->data_id;
		$trailer_roof_type_name[$loader_trailer_roof_type_counter] = html_entity_decode($loader_trailer_roof_type_data->name);

		# By data_id
		$trailer_roof_type_name_did[$loader_trailer_roof_type_data->data_id] = html_entity_decode($loader_trailer_roof_type_data->name);

		$loader_trailer_roof_type_counter++;
	}
}

# loader_driver_equipment
$loader_driver_equipment = DB::getInstance()->query("SELECT * FROM loader_driver_equipment ORDER BY name ASC");
$loader_driver_equipment_count = $loader_driver_equipment->count();
$loader_driver_equipment_counter = 1;

if ($loader_driver_equipment_count) {
	foreach($loader_driver_equipment->results() as $loader_driver_equipment_data) {

		# By data_id
		$driver_equipment_name_did[$loader_driver_equipment_data->data_id] = $loader_driver_equipment_data->name;

		# By counter
		$driver_equipment_data_id[$loader_driver_equipment_counter] = $loader_driver_equipment_data->data_id;
		$driver_equipment_name[$loader_driver_equipment_counter] = $loader_driver_equipment_data->name;
		$loader_driver_equipment_counter++;
	}
}

# loader_driver_equipment_assoc
if ($_SERVER['SCRIPT_NAME'] == '/0/loader.php') {
	$loader_driver_equipment_assoc = DB::getInstance()->query("SELECT * FROM loader_driver_equipment_assoc WHERE driver_id = " . $_GET['driver_id']);
} else {
	$loader_driver_equipment_assoc = DB::getInstance()->query("SELECT * FROM loader_driver_equipment_assoc WHERE driver_id = " . $_GET['id']);
}

$loader_driver_equipment_assoc_count = $loader_driver_equipment_assoc->count();
$loader_driver_equipment_assoc_counter = 1;

if ($loader_driver_equipment_assoc_count) {
	foreach($loader_driver_equipment_assoc->results() as $loader_driver_equipment_assoc_data) {

		# By counter
		$driver_equipment_assoc_equipment_id[$loader_driver_equipment_assoc_counter] = $loader_driver_equipment_assoc_data->equipment_id;
		$driver_equipment_assoc_data_id[$loader_driver_equipment_assoc_counter] = $loader_driver_equipment_assoc_data->data_id;
		$driver_equipment_assoc_quantity[$loader_driver_equipment_assoc_counter] = $loader_driver_equipment_assoc_data->quantity;
		$loader_driver_equipment_assoc_counter++;
	}
}

# loader_driver_features
$loader_driver_features = DB::getInstance()->query("SELECT * FROM loader_driver_features ORDER BY name ASC");
$loader_driver_features_count = $loader_driver_features->count();
$loader_driver_features_counter = 1;

if ($loader_driver_features_count) {
	foreach($loader_driver_features->results() as $loader_driver_features_data) {

		# By counter
		$driver_features_data_id[$loader_driver_features_counter] = $loader_driver_features_data->data_id;
		$driver_features_name[$loader_driver_features_counter] = $loader_driver_features_data->name;
		$loader_driver_features_counter++;

		# By data_id
		$driver_features_data_id_did[$loader_driver_features_counter] = $loader_driver_features_data->data_id;
		$driver_features_name_did[$loader_driver_features_counter] = $loader_driver_features_data->name;
	}
}

# loader_driver_features_assoc
$loader_driver_features_assoc = DB::getInstance()->query("SELECT * FROM loader_driver_features_assoc WHERE driver_id = " . $_GET['id']);
$loader_driver_features_assoc_count = $loader_driver_features_assoc->count();
$loader_driver_features_assoc_counter = 1;

if ($loader_driver_features_assoc_count) {
	foreach($loader_driver_features_assoc->results() as $loader_driver_features_assoc_data) {

		# By counter
		$driver_feature_feature_id[$loader_driver_features_assoc_counter] = $loader_driver_features_assoc_data->feature_id;
		$driver_feature_data_id[$loader_driver_features_assoc_counter] = $loader_driver_features_assoc_data->data_id;
		$loader_driver_features_assoc_counter++;
	}
}

# loader_quickpay_method_of_payment
$loader_quickpay_method_of_payment = DB::getInstance()->query("SELECT * FROM loader_quickpay_method_of_payment ORDER BY method ASC");
$loader_quickpay_method_of_payment_count = $loader_quickpay_method_of_payment->count();
$loader_quickpay_method_of_payment_counter = 1;

if ($loader_quickpay_method_of_payment_count) {
	foreach($loader_quickpay_method_of_payment->results() as $loader_quickpay_method_of_payment_data) {

		# By counter (note the use of _ctr)
		$quickpay_method_of_payment_data_id_ctr[$loader_quickpay_method_of_payment_counter] = $loader_quickpay_method_of_payment_data->data_id;
		$quickpay_method_of_payment_method_ctr[$loader_quickpay_method_of_payment_counter] = $loader_quickpay_method_of_payment_data->method;
		$loader_quickpay_method_of_payment_counter++;

		# By data_id
		$quickpay_method_of_payment_data_id[$loader_quickpay_method_of_payment_data->data_id] = $loader_quickpay_method_of_payment_data->data_id;
		$quickpay_method_of_payment_method[$loader_quickpay_method_of_payment_data->data_id] = $loader_quickpay_method_of_payment_data->method;
	}
}

# user_e_profile_client_user
// Lists all users associated with a client profile
$user_e_profile_client_user = DB::getInstance()->query("SELECT * FROM user_e_profile_client_user WHERE client_id = " . $_GET['id']);
$user_e_profile_client_user_count = $user_e_profile_client_user->count();
$user_e_profile_client_user_counter = 1;

if ($user_e_profile_client_user_count) {
	foreach($user_e_profile_client_user->results() as $user_e_profile_client_user_data) {

		# By counter (note the use of _ctr)
		$client_user_user_id_ctr[$user_e_profile_client_user_counter] = $user_e_profile_client_user_data->user_id;
		$client_user_client_id_ctr[$user_e_profile_client_user_counter] = $user_e_profile_client_user_data->client_id;
		$client_user_data_id_ctr[$user_e_profile_client_user_counter] = $user_e_profile_client_user_data->data_id;
		$client_user_user_type_ctr[$user_e_profile_client_user_counter] = $user_e_profile_client_user_data->user_type;
		$client_user_user_manager_ctr[$user_e_profile_client_user_counter] = $user_e_profile_client_user_data->user_manager;
		$user_e_profile_client_user_counter++;

		# By data_id
		$client_user_user_id[$user_e_profile_client_user_data->user_id] = $user_e_profile_client_user_data->user_id;
		$client_user_client_id[$user_e_profile_client_user_data->client_id] = $user_e_profile_client_user_data->client_id;
		$client_user_data_id[$user_e_profile_client_user_data->data_id] = $user_e_profile_client_user_data->data_id;
		$client_user_user_type[$user_e_profile_client_user_data->user_type] = $user_e_profile_client_user_data->user_type;
		$client_user_user_manager[$user_e_profile_client_user_data->user_manager] = $user_e_profile_client_user_data->user_manager;
	}
}

# user_e_profile_client_manager
// Lists all owners and owner/operators associated with a client profile
$user_e_profile_client_manager = DB::getInstance()->query("SELECT * FROM user_e_profile_client_user WHERE client_id = " . $_GET['id'] . " && user_type IN (0, 1)");
$user_e_profile_client_manager_count = $user_e_profile_client_manager->count();
$user_e_profile_client_manager_counter = 1;

if ($user_e_profile_client_manager_count) {
	foreach($user_e_profile_client_manager->results() as $user_e_profile_client_manager_data) {

		# By counter (note the use of _ctr)
		$client_manager_user_id_ctr[$user_e_profile_client_manager_counter] = $user_e_profile_client_manager_data->user_id;
		$client_manager_client_id_ctr[$user_e_profile_client_manager_counter] = $user_e_profile_client_manager_data->client_id;
		$client_manager_data_id_ctr[$user_e_profile_client_manager_counter] = $user_e_profile_client_manager_data->data_id;
		$client_manager_user_type_ctr[$user_e_profile_client_manager_counter] = $user_e_profile_client_manager_data->user_type;
		$client_manager_user_manager_ctr[$user_e_profile_client_manager_counter] = $user_e_profile_client_manager_data->user_manager;
		$user_e_profile_client_manager_counter++;

		# By data_id
		$client_manager_user_id[$user_e_profile_client_manager_data->user_id] = $user_e_profile_client_manager_data->user_id;
		$client_manager_client_id[$user_e_profile_client_manager_data->client_id] = $user_e_profile_client_manager_data->client_id;
		$client_manager_data_id[$user_e_profile_client_manager_data->data_id] = $user_e_profile_client_manager_data->data_id;
		$client_manager_user_type[$user_e_profile_client_manager_data->user_type] = $user_e_profile_client_manager_data->user_type;
		$client_manager_user_manager[$user_e_profile_client_manager_data->user_manager] = $user_e_profile_client_manager_data->user_manager;
	}
}

# user_e_profile_client_driver
// Lists all drivers associated with an active client profile
$user_e_profile_client_driver = DB::getInstance()->query("SELECT user_e_profile_client_user.user_id, user_e_profile_client_user.client_id, user_e_profile_client_user.user_type , user_e_profile_client.company_name, user_e_profile_client.status FROM user_e_profile_client_user INNER JOIN user_e_profile_client ON user_e_profile_client_user.client_id=user_e_profile_client.data_id WHERE user_type IN (1, 2) && status = 1");
$user_e_profile_client_driver_count = $user_e_profile_client_driver->count();
$user_e_profile_client_driver_counter = 1;

if ($user_e_profile_client_driver_count) {
	foreach($user_e_profile_client_driver->results() as $user_e_profile_client_driver_data) {

		# By counter (note the use of _ctr)
		$client_driver_user_id_ctr[$user_e_profile_client_driver_counter] = $user_e_profile_client_driver_data->user_id;
		$client_driver_client_id_ctr[$user_e_profile_client_driver_counter] = $user_e_profile_client_driver_data->client_id;
		$client_driver_data_id_ctr[$user_e_profile_client_driver_counter] = $user_e_profile_client_driver_data->data_id;
		$client_driver_user_type_ctr[$user_e_profile_client_driver_counter] = $user_e_profile_client_driver_data->user_type;
		$client_driver_user_manager_ctr[$user_e_profile_client_driver_counter] = $user_e_profile_client_driver_data->user_manager;
		$user_e_profile_client_driver_counter++;

		# By data_id
		$client_driver_user_id[$user_e_profile_client_driver_data->user_id] = $user_e_profile_client_driver_data->user_id;
		$client_driver_client_id[$user_e_profile_client_driver_data->client_id] = $user_e_profile_client_driver_data->client_id;
		$client_driver_data_id[$user_e_profile_client_driver_data->data_id] = $user_e_profile_client_driver_data->data_id;
		$client_driver_user_type[$user_e_profile_client_driver_data->user_type] = $user_e_profile_client_driver_data->user_type;
		$client_driver_user_manager[$user_e_profile_client_driver_data->user_manager] = $user_e_profile_client_driver_data->user_manager;
	}
}

# loader_tractor
$loader_tractor = DB::getInstance()->query("SELECT * FROM loader_tractor WHERE owner_user_id = " . $_GET['id']);
$loader_tractor_count = $loader_tractor->count();
$loader_tractor_counter = 1;

if ($loader_tractor_count) {
	foreach ($loader_tractor->results() as $loader_tractor_data) {
		$tractor_owner_user_id = $loader_tractor_data->owner_user_id;
		$tractor_data_id = $loader_tractor_data->data_id;
		$tractor_number = html_entity_decode($loader_tractor_data->number);
		$tractor_color = html_entity_decode($loader_tractor_data->color);
		$tractor_vin = html_entity_decode($loader_tractor_data->vin);
		$tractor_headrack = $loader_tractor_data->headrack;
		$tractor_year = $loader_tractor_data->year;
		$tractor_make = html_entity_decode($loader_tractor_data->make);
		$tractor_model = html_entity_decode($loader_tractor_data->model);
		$tractor_license_plate = html_entity_decode($loader_tractor_data->license_plate);
		$tractor_trailer_type = $loader_tractor_data->trailer_type;
		$tractor_user_id = $loader_tractor_data->user_id;
		$tractor_added = $loader_tractor_data->added;

		# GET ACTIVE TRAILER DATA
		# loader_trailer
		$loader_trailer = DB::getInstance()->query("SELECT * FROM loader_trailer WHERE tractor_id = " . $tractor_data_id . " && status = 1");
		$loader_trailer_count = $loader_trailer->count();

		if ($loader_trailer_count) {
			foreach ($loader_trailer->results() as $loader_trailer_data) {
				$trailer_data_id = $loader_trailer_data->data_id;
				$trailer_length = $loader_trailer_data->length;
				$trailer_height = number_format($loader_trailer_data->height, 2);
				$trailer_width  = $loader_trailer_data->width;
				$trailer_number = $loader_trailer_data->trailer_number;
				$trailer_license_plate = htmlentities($loader_trailer_data->license_plate, ENT_QUOTES);
				$trailer_gross_weight = number_format($loader_trailer_data->gross_weight, 2);
				$trailer_vin = htmlentities($loader_trailer_data->vin, ENT_QUOTES);
				$trailer_deck_material = $loader_trailer_data->deck_material;
				$trailer_headrack = $loader_trailer_data->headrack;
				$trailer_air_ride = $loader_trailer_data->air_ride;
				$trailer_year = $loader_trailer_data->year;
				$trailer_door_type = $loader_trailer_data->door_type;
				$trailer_roof_type = $loader_trailer_data->roof_type;
				$trailer_bottom_deck = number_format($loader_trailer_data->bottom_deck, 2);
				$trailer_upper_deck = number_format($loader_trailer_data->upper_deck, 2);
				$trailer_goose_neck = $loader_trailer_data->goose_neck;
				$trailer_user_id = $loader_trailer_data->user_id;
				$trailer_added = $loader_trailer_data->added;
			}
		}
	}
}

# user_e_profile_client_user
// Get number of owners
$client_user_owner = DB::getInstance()->query("SELECT * FROM user_e_profile_client_user WHERE client_id = " . $_GET['id'] . " && user_type = 0");
$client_user_owner_count = $client_user_owner->count();

# user_e_profile_client_user
// Get number of owner/operators
$client_user_owner_operator = DB::getInstance()->query("SELECT * FROM user_e_profile_client_user WHERE client_id = " . $_GET['id'] . " && user_type = 1");
$client_user_owner_operator_count = $client_user_owner_operator->count();

# user_e_profile_client_user
// Get number of drivers
$client_user_driver = DB::getInstance()->query("SELECT * FROM user_e_profile_client_user WHERE client_id = " . $_GET['id'] . " && user_type = 2");
$client_user_driver_count = $client_user_driver->count();

# Disable/Enable client activation
# Go on if there is user count
if ($user_e_profile_client_user_count) {
	
	// We can activate if we have an owner and a driver count OR an owner/operator
	if (($client_user_owner_count && $client_user_driver_count) || $client_user_owner_operator_count) {
		$owner_driver_activate = 1;
	}

}

# This should only happen if profile is active and deleting of user was requested
if ($limbo_client_status_by_data_id[$_GET['id']] == 1 && $_GET['delete_loader_client_user']) {

	if ($user_e_profile_client_user_count == 1) {
		# User count == 1 and client profile is active means that this user is an owner/operator.
		# Deleting this user should set profile as inactive inmmediately.
		$deactivate_client = 1;
	} elseif ($user_e_profile_client_user_count > 1) {
		# We have more than 1 user
		if ($client_user_owner_count == 1 && $client_user_driver_count == 1 && $client_user_owner_operator_count == 0) {
			# One owner, one driver, 0 owner/operators
			# This case should deactivate client profile
			$deactivate_client = 1;
		}
	}
}	

# Get quickpay_service_fee_id for quickpay invoicing
$user_e_profile_client_broker_assoc = DB::getInstance()->query("SELECT * FROM client_broker_assoc WHERE client_id = " . $_GET['client_id'] . " && broker_id = " . $_GET['broker_id']);
$user_e_profile_client_broker_assoc_count = $user_e_profile_client_broker_assoc->count();

if ($user_e_profile_client_broker_assoc_count) {
	foreach ($user_e_profile_client_broker_assoc->results() as $user_e_profile_client_broker_assoc_data) {

		# Get broker_quickpay_service_fee data based on data_id
		$invoice_service_fee = DB::getInstance()->query("SELECT * FROM broker_quickpay_service_fee WHERE data_id = " . $user_e_profile_client_broker_assoc_data->quickpay_service_fee_id);
		$invoice_service_fee_count = $invoice_service_fee->count();

		if ($invoice_service_fee_count) {
			foreach ($invoice_service_fee->results() as $invoice_service_fee_data) {
				
				$invoice_service_fee_fee = $invoice_service_fee_data->fee;
				$invoice_service_fee_method_id = $invoice_service_fee_data->method_id;
				$invoice_service_fee_number_of_days = $invoice_service_fee_data->number_of_days;
			}
		}
	}
}

# loader_load
# loader_entry
if ($_GET['deleted_loads']) {
	# This list reflects deleted loads only, WHERE load_status == 1
	$load_list = DB::getInstance()->query("SELECT loader_load.entry_id, loader_load.broker_id, loader_load.load_id, loader_load.load_number, loader_load.line_haul, loader_load.miles, loader_load.billing_status, loader_load.user_id, loader_load.commodity, loader_entry.driver_id, loader_entry.data_id FROM loader_load RIGHT JOIN loader_entry ON loader_load.entry_id=loader_entry.data_id WHERE load_status = 1 ORDER BY data_id DESC");
} elseif ($_GET['all_loads']) {
	# This list reflects all loads, no WHERE clause
	$load_list = DB::getInstance()->query("SELECT loader_load.entry_id, loader_load.broker_id, loader_load.load_id, loader_load.load_number, loader_load.line_haul, loader_load.miles, loader_load.billing_status, loader_load.user_id, loader_load.commodity, loader_entry.driver_id, loader_entry.data_id FROM loader_load RIGHT JOIN loader_entry ON loader_load.entry_id=loader_entry.data_id ORDER BY data_id DESC");
} else {
	# This list reflects active loads only, WHERE billing_status != 3 and load_status == 0
	$load_list = DB::getInstance()->query("SELECT loader_load.entry_id, loader_load.broker_id, loader_load.load_id, loader_load.load_number, loader_load.line_haul, loader_load.miles, loader_load.billing_status, loader_load.user_id, loader_load.commodity, loader_load.load_lock, loader_load.load_status, loader_entry.driver_id, loader_entry.data_id FROM loader_load RIGHT JOIN loader_entry ON loader_load.entry_id=loader_entry.data_id WHERE billing_status != 3 && load_status = 0 ORDER BY data_id DESC");
}
$load_list_count = $load_list->count();
$load_list_counter = 1;

if ($load_list_count) {
	
	foreach ($load_list->results() as $load_list_data) {
		
		# By counter
		// loader_load
		$load_list_entry_id[$load_list_counter] = $load_list_data->entry_id;
		$load_list_broker_id[$load_list_counter] = $load_list_data->broker_id;
		$load_list_load_number[$load_list_counter] = $load_list_data->load_number;
		$load_list_load_id[$load_list_counter] = $load_list_data->load_id;
		$load_list_line_haul[$load_list_counter] = $load_list_data->line_haul;
		$load_list_miles[$load_list_counter] = $load_list_data->miles;
		$load_list_billing_status[$load_list_counter] = $load_list_data->billing_status;
		$load_list_user_id[$load_list_counter] = $load_list_data->user_id;
		$load_list_commodity[$load_list_counter] = html_entity_decode($load_list_data->commodity);
		$load_list_load_lock[$load_list_counter] = $load_list_data->load_lock;
		$load_list_load_status[$load_list_counter] = $load_list_data->load_status;
		// loader_entry
		$load_list_driver_id[$load_list_counter] = $load_list_data->driver_id;

		# By load_id
		// loader_load
		$load_list_commodity_lid[$load_list_data->load_id] = html_entity_decode($load_list_data->commodity);
		$load_list_broker_id_lid[$load_list_data->load_id] = $load_list_data->broker_id;
		$load_list_load_id_lid[$load_list_data->load_id] = $load_list_data->load_id;
		$load_list_load_number_lid[$load_list_data->load_id] = $load_list_data->load_number;
		// loader_entry
		$load_list_driver_id_lid[$load_list_data->load_id] = $load_list_data->driver_id;

		$load_list_counter++;
	}
}

# Get notes per load
$load_note = DB::getInstance()->query("SELECT * FROM loader_load_note WHERE load_id = " . $_GET['load_id'] . " ORDER BY added DESC");
$load_note_count = $load_note->count();
$load_note_counter = 1;

if ($load_note_count) {
	
	foreach ($load_note->results() as $load_note_data) {
		
		# By counter
		// loader_load
		$load_note_data_id[$load_note_counter] = $load_note_data->data_id;
		$load_note_note[$load_note_counter] = $load_note_data->note;
		$load_note_important[$load_note_counter] = $load_note_data->important;
		$load_note_type[$load_note_counter] = $load_note_data->type;
		$load_note_added[$load_note_counter] = date('m/d/Y h:m a', strtotime($load_note_data->added));
		$load_note_user_id[$load_note_counter] = $load_note_data->user_id;

		$load_note_counter++;
	}
}

# Rise a flag if load has 7 days old and hasn't been charged
$flag_payment_missing = DB::getInstance()->query("SELECT * FROM loader_load WHERE DATE(added) <= DATE_SUB(CURDATE(), INTERVAL 10 DAY) && billing_status = 0");
$flag_payment_missing_count = $flag_payment_missing->count();
$flag_payment_missing_counter = 1;

if ($flag_payment_missing_count) {
	foreach ($flag_payment_missing->results() as $flag_payment_missing_data) {
		# Get the relevant data to show these loads
		$flag_payment_missing_entry_id[$flag_payment_missing_counter] = $flag_payment_missing_data->entry_id;
		$flag_payment_missing_broker_id[$flag_payment_missing_counter] = $flag_payment_missing_data->broker_id;
		$flag_payment_missing_load_id[$flag_payment_missing_counter] = $flag_payment_missing_data->load_id;
		$flag_payment_missing_load_number[$flag_payment_missing_counter] = $flag_payment_missing_data->load_number;
		$flag_payment_missing_added[$flag_payment_missing_counter] = date('m d Y', strtotime($flag_payment_missing_data->added));

		$flag_payment_missing_counter++;
	}
}

#loader_quickpay_invoice_counter
// Set get value for client_id, it's not the same parameter accross different URLs
$_SERVER['SCRIPT_NAME'] == '/0/client.php' ? $client_id_value = $_GET['id'] : $client_id_value = $_GET['client_id'] ;

$loader_quickpay_invoice_counter = DB::getInstance()->query("SELECT * FROM loader_quickpay_invoice_counter WHERE broker_id = " . $_GET['broker_id'] . " && client_id = " . $client_id_value);
$loader_quickpay_invoice_counter_count = $loader_quickpay_invoice_counter->count();

if ($loader_quickpay_invoice_counter_count) {
	foreach ($loader_quickpay_invoice_counter->results() as $loader_quickpay_invoice_counter_data) {
		$quickpay_invoice_counter = $loader_quickpay_invoice_counter_data->counter;
	}
}

# Company rate
$company_rate = DB::getInstance()->query("SELECT * FROM company_rate ORDER BY rate ASC");
$company_rate_count = $company_rate->count();
$company_rate_counter = 1;

if ($company_rate_count) {
	foreach ($company_rate->results() as $company_rate_data) {

		$company_rate_data_id[$company_rate_counter] = $company_rate_data->data_id;
		$company_rate_title[$company_rate_counter] = html_entity_decode($company_rate_data->title);
		$company_rate_rate[$company_rate_counter] = $company_rate_data->rate;
		$company_rate_processing_fee[$company_rate_counter] = $company_rate_data->processing_fee;

		$company_rate_counter++;
	}
}


### END NICHE/SITE SPECIFIC DATA ###
