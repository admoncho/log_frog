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

# db/quickpay_method_of_payment.php
include($_SESSION['ProjectPath'] . "/resource/library/quantum/module/loader/db/quickpay_method_of_payment.php");

# db/broker_quickpay_service_fee.php
include($_SESSION['ProjectPath'] . "/resource/library/quantum/module/broker/db/broker_quickpay_service_fee.php");

# db/broker.php
include($module_directory . "db/broker.php");

# db/client.php
include(LIBRARY_PATH . "/quantum/module/client/db/nav_client.php");

# db/ppg.php
include(LIBRARY_PATH . "/quantum/module/core/db/ppg.php");

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
