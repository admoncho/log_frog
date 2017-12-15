<?php

# Get broker address
$broker_address = DB::getInstance()->query("SELECT * FROM broker WHERE data_id = " . $load_list_broker_id[1]);

foreach ($broker_address->results() as $broker_address_data) {
	
	$broker_address_line_1 = html_entity_decode($broker_address_data->company_name);
}

# Get broker address THIS IS TEMPORARY
$broker_address_alt = DB::getInstance()->query("SELECT * FROM broker_address WHERE broker_id = " . $load_list_broker_id[1]);

foreach ($broker_address_alt->results() as $broker_address_alt_data) {
	
	$broker_address_alt_line_2 = html_entity_decode($broker_address_alt_data->line_2);
	$broker_address_alt_line_3 = html_entity_decode($broker_address_alt_data->line_3);
	$broker_address_alt_city = html_entity_decode($broker_address_alt_data->city);
	$broker_address_alt_state_id = $broker_address_alt_data->state_id;
	$broker_address_alt_zip_code = $broker_address_alt_data->zip_code;
}

# Get checkpoints
$checkpoint = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $load_list_load_id[1] . " ORDER BY date_time ASC");
$checkpoint_count = $checkpoint->count();
$i = 1;

$checkpoint_count = $checkpoint->count();
if ($checkpoint_count) {
	
	foreach ($checkpoint->results() as $checkpoint_data) {

		$checkpoint_city[$i] = ucfirst(strtolower(html_entity_decode($checkpoint_data->city)));
		$checkpoint_state_id[$i] = $checkpoint_data->state_id;
		$i++;
	}
}

# Get other charges data for this load
$invoice_other_charges = DB::getInstance()->query("SELECT * FROM loader_load_other_charges WHERE load_id = " . $load_list_load_id[1]);
$invoice_other_charges_count = $invoice_other_charges->count();
$i = 1;

if ($invoice_other_charges_count) {
	
	foreach ($invoice_other_charges->results() as $invoice_other_charges_data) {
		
		$invoice_other_charges_item[$i] = html_entity_decode($invoice_other_charges_data->item);
		$invoice_other_charges_price[$i] = $invoice_other_charges_data->price;
		# Sum other charge by load_id
		$sum_other_charge[$i] += $invoice_other_charges_data->price;

		$i++;
	}
}

# Start buffer to save PHP variables and HTML tags into a variable
ob_start(); ?>

<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 135px; left: 645px; z-index: 9;"><?= date('m/d/Y') ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 150px; left: 645px; z-index: 9; width: 30px;"><?= $first_invoice_number ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 165px; left: 645px; z-index: 9;"><?= $load_list_load_number[1] ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 180px; left: 645px; z-index: 9;"><?= $user_list_name[$client_driver_user_id[$load_list_driver_id[1]]] . ' ' . $user_list_last_name[$client_driver_user_id[$load_list_driver_id[1]]] ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 198px; left: 110px; z-index: 9; width: 500px;"><?= $broker_address_line_1 ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 212px; left: 110px; z-index: 9;"><?= $broker_address_alt_line_2 . (isset($broker_address_alt_line_3) ? ', ' . $broker_address_alt_line_3 : '') ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 228px; left: 110px; z-index: 9;"><?= $broker_address_alt_city . ', ' . $state_abbr[$broker_address_alt_state_id] . ' ' . $broker_address_alt_zip_code ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 322px; left: 220px; z-index: 9;"><?= $checkpoint_city[1] . ', ' . $state_abbr[$checkpoint_state_id[1]] ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 345px; left: 220px; z-index: 9;"><?= $checkpoint_city[$checkpoint_count] . ', ' . $state_abbr[$checkpoint_state_id[$checkpoint_count]] ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 372px; left: 645px; z-index: 9;">$ <?= $load_list_line_haul[1] ?></p>
<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 758px; left: 655px; z-index: 9; width: 30px;"><?= $load_list_line_haul[1] - $sum_other_charge[$load_list_load_id[1]] ?></p>

<?php
for ($i = 1; $i <= $invoice_other_charges_count ; $i++) {

	# Display detention time in its box
	if ($invoice_other_charges_item[$i] == 'Detention time') { ?>

		<p class="hidden" style="font-family: 'Ubuntu', sans-serif; font-size: 10px; position: absolute; top: 420px; left: 645px; z-index: 9;">$ <?= $invoice_other_charges_price[$i] ?></p> <?php
	}
} ?>

<div class="hidden" style="position: absolute; top: 480px; left: 375px; z-index: 9;">
	<table style="z-index: 9; border-collapse: collapse;">

		<?php

		for ($i = 1; $i <= $invoice_other_charges_count ; $i++) {

			# Don't display detention here
			if ($invoice_other_charges_item[$i] != 'Detention time') { ?>
				
				<tr>
			    <td style="padding: 6px 0; width: 270px; font-family: 'Ubuntu', sans-serif; font-size: 10px;"><?= $invoice_other_charges_item[$i] ?></td>
			    <td style="padding: 6px 0; width: 40px; font-family: 'Ubuntu', sans-serif; font-size: 10px;">$ <?= $invoice_other_charges_price[$i] ?></td> 
			  </tr> <?php
			}
		}
		?>

	</table>
</div>

<div class="hidden" style="position: absolute; top: 0; left: 0; width: 785px; height: 1015px; background: url(http://<?= str_replace('quantum.', '', $_SERVER['HTTP_HOST']) ?>/files/schedule/bg/<?= $_GET['load_client_id'] ?>.jpg);"></div> <?php

# End buffer to save PHP variables and HTML tags into a variable
$html = ob_get_contents();

# Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));

# Save content in var
$content = $mpdf->Output('', 'S');

# Save invoice file
$save_invoice = $mpdf->Output($schedule_directory . ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf','F');
