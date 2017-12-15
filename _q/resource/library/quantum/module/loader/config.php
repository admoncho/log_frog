<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
# db/geo_state.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_state.php");

# db/geo_county.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_county.php");

# db/geo_district.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_district.php");

# db/user.php
include(LIBRARY_PATH . "/quantum/module/user/db/user.php");

# db/user_phone_number.php
include(LIBRARY_PATH . "/quantum/module/user/db/user_phone_number.php");

# db/broker.php
include(LIBRARY_PATH . "/quantum/module/broker/db/broker.php");

# db/ppg.php
include(LIBRARY_PATH . "/quantum/module/core/db/ppg.php");

# db/loader.php
include($module_directory . "db/loader.php");

# db/draft_load.php
include($module_directory . "db/draft_load.php");

# db/draft_load_lead.php
include($module_directory . "db/draft_load_lead.php");

# schedule_id data
$_GET['schedule_id'] ? require($module_directory . "db/schedule_id.php") : '' ;

# db/client.php
include(LIBRARY_PATH . "/quantum/module/client/db/client.php");

# Schedule invoice name
for ($i = 1; $i <= $factoring_company_schedule_load_count ; $i++) { 
	
	# Declare file name
	$pre_invoice_name[$i] = strtolower(str_replace([' & ', ' '], ['-', '-'], ($schedule_invoice_number[$i]) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));

	$invoice_name[$i] = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_name[$i]);
}

# schedule_id data
$_GET['schedule_id'] ? require($module_directory . "db/quickpay_method_of_payment.php") : '' ;

if ($_GET['checkpoint_status_update']) {

	# db/loader_config.php
	include($module_directory . "db/loader_config.php");
}

# factoring_company data
$_SESSION['$clean_php_self'] == '/dashboard/loader/schedule.php' ? require(LIBRARY_PATH . "/quantum/module/factoring_company/db/factoring_company.php") : '' ;
$_SESSION['$clean_php_self'] == '/dashboard/loader/load.php' ? require(LIBRARY_PATH . "/quantum/module/factoring_company/db/factoring_company.php") : '' ;

# schedule_id data
$_GET['schedule_id'] ? require($module_directory . "db/schedule_config.php") : '' ;

# db/client.php
include(LIBRARY_PATH . "/quantum/module/client/db/nav_client.php");

# db/client_card.php
# Run only if called
if ($_POST['client_card']) {
	
	include(LIBRARY_PATH . "/quantum/module/client/db/client_trailer_type.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_trailer_deck_material.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_trailer_roof_type.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_trailer_door_type.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_insurance_company.php");
	include(LIBRARY_PATH . "/quantum/module/user/db/user_phone_number.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_card.php");
	include(LIBRARY_PATH . "/quantum/module/factoring_company/db/factoring_company.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_user_equipment_assoc.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_user_equipment.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_user_feature_assoc.php");
	include(LIBRARY_PATH . "/quantum/module/client/db/client_user_feature.php");
}

# Post controller
include(LIBRARY_PATH . "/quantum/module/core/inc/post-controller.php");

# Include mPDF Class
include($_SESSION['ProjectPath'] . "/mpdf/mpdf.php");

# Create new mPDF Document
$mpdf = new mPDF();
