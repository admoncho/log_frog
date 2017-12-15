<?php

# Schedule config
$schedule_config = DB::getInstance()->query("SELECT * FROM factoring_company_schedule_config WHERE id = 1");

foreach ($schedule_config->results() as $schedule_config_data) {
	
	# If Soar is required
	if ($factoring_company_requires_soar[1]) {
		
		# Throw original code
		$schedule_config_email_subject 	= str_replace(['{{client_id}}', '{{soar_number}}', '{{payment_method}}'], [$client_id_company_name[$client_assoc_factoring_company_client_id], $schedule_counter, $quickpay_method_of_payment_method[$service_fee_method_id]], html_entity_decode($schedule_config_data->email_subject));
		$schedule_config_email_body 		= str_replace(['{{client_id}}', '{{soar_number}}', '{{payment_method}}'], [$client_id_company_name[$client_assoc_factoring_company_client_id], $schedule_counter, $quickpay_method_of_payment_method[$service_fee_method_id]], html_entity_decode($schedule_config_data->email_body));
	} else {

		# Throw original code without the soar part
		$schedule_config_email_subject 	= str_replace(['{{client_id}}', ' - Schedule of Accounts receivable #{{soar_number}}', '{{payment_method}}'], [$client_id_company_name[$client_assoc_factoring_company_client_id], '', $quickpay_method_of_payment_method[$service_fee_method_id]], html_entity_decode($schedule_config_data->email_subject));
		$schedule_config_email_body 		= str_replace(['{{client_id}}', '{{soar_number}}', '{{payment_method}}'], [$client_id_company_name[$client_assoc_factoring_company_client_id], '', $quickpay_method_of_payment_method[$service_fee_method_id]], html_entity_decode($schedule_config_data->email_body));
	}
}
