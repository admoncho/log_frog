<?php

# db/geo_state.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_state.php");

# db/geo_county.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_county.php");

# db/geo_district.php
include(LIBRARY_PATH . "/quantum/module/core/db/geo_district.php");

# db/user.php
include(LIBRARY_PATH . "/quantum/module/user/db/user.php");

# db/loader_quickpay_method_of_payment.php
# THIS FILE BELONGS TO A DIFFERENT MODULE, USING IT HERE FOR CONVENIENCE, MUST MOVE!
include($module_directory . "db/loader_quickpay_method_of_payment.php");

# db/factoring_company.php
!isset($_GET['n']) ? include($module_directory . "db/factoring_company.php") : '';

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
