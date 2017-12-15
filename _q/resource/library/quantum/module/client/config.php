<?php

# db/geo_state.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_state.php");

# db/geo_county.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_county.php");

# db/geo_district.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_district.php");

# db/user.php
include(LIBRARY_PATH . "/quantum/module/user/db/user.php");

# db/ppg.php
include(LIBRARY_PATH . "/quantum/module/core/db/ppg.php");

if (isset($_GET['client_id'])) {
	
	include(LIBRARY_PATH . "/quantum/module/broker/db/broker.php");
	include(LIBRARY_PATH . "/quantum/module/broker/db/broker_quickpay_service_fee.php");
	include(LIBRARY_PATH . "/quantum/module/loader/db/quickpay_method_of_payment.php");
	include($module_directory . "db/client_user_equipment_assoc.php");
	include($module_directory . "db/client_user_equipment.php");
	include($module_directory . "db/client_user_feature_assoc.php");
	include($module_directory . "db/client_user_feature.php");
}

# db/user.php
include(LIBRARY_PATH . "/quantum/module/user/db/user.php");

# db/client_insurance_company.php
include($module_directory . "db/client_insurance_company.php");

# db/client.php
include($module_directory . "db/client.php");

# db/client.php
include(LIBRARY_PATH . "/quantum/module/client/db/nav_client.php");

if ($_GET['user_id'] || $_POST['client_card']) {

	# db/client_trailer_deck_material.php
	include($module_directory . "db/client_trailer_deck_material.php");

	# db/client_trailer_door_type.php
	include($module_directory . "db/client_trailer_door_type.php");

	# db/client_trailer_roof_type.php
	include($module_directory . "db/client_trailer_roof_type.php");

	# db/client_trailer_type.php
	include($module_directory . "db/client_trailer_type.php");
	
	# db/client_user_tractor.php
	include($module_directory . "db/client_user_tractor.php");

	# db/client_tractor_trailer.php
	include($module_directory . "db/client_tractor_trailer.php");

	include(LIBRARY_PATH . "/quantum/module/user/db/user_phone_number.php");
	
	include(LIBRARY_PATH . "/quantum/module/client/db/client_card.php");
}

# db/factoring_company.php
include(LIBRARY_PATH . "/quantum/module/factoring_company/db/factoring_company.php");

# db/loader_quickpay_invoice_counter.php
include(LIBRARY_PATH . "/quantum/module/loader/db/quickpay_invoice_counter.php");

# Post controller
include(LIBRARY_PATH . "/quantum/module/core/inc/post-controller.php");
