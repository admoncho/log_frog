<?php

# soar: schedule of accounts receivable

for ($i=1; $i <= $soar_num_pages ; $i++) { 
	# Start buffer to save PHP variables and HTML tags into a variable
	ob_start(); ?>

	<p class="hidden" style="position: absolute; top: 0; left: 666px; z-index: 9; width: 30px;"><?= $schedule_counter ?></p>
	<p class="hidden" style="position: absolute; top: 92px; left: 65px; z-index: 9;"><?= date('m/d/Y') ?></p>
	<p class="hidden" style="position: absolute; top: 92px; left: 665px; z-index: 9;"><?= $i . ' of ' . $soar_num_pages ?></p>
	<?= $i == 1 ? '<p class="hidden" style="position: absolute; top: 122px; left: 150px; z-index: 9;">' . number_format($schedule_amount, 2) . '</p>' : '' ?>
	<p class="hidden" style="position: absolute; top: 933px; left: 460px; z-index: 9;"><?= date('m/d/Y') ?></p>
	<?= $i == $soar_num_pages ? '<p class="hidden" style="position: absolute; top: 725px; left: 665px; z-index: 9;">' . number_format($schedule_amount, 2) . '</p>' : '' ?>
	<div class="hidden" style="position: absolute; top: 325px; left: 10px; z-index: 9;">
		<table style="z-index: 9; border-collapse: collapse;">

			<?php

			# Current load count value for each page
			# Page 1 value = 1, other pages, take page number ($i) add a 0 to the string and remove 9
			$i == 1 ? $lc_current_value = 1 : $lc_current_value = $i . 0 - 9;
			# Current page top value
			# Page 1 value = 10, other pages, take page number ($i) add a 0 to the string
			$i == 1 ? $lc_current_page_top = 10 : $lc_current_page_top = $i . 0;

			for ($lc = $lc_current_value; $lc <= $lc_current_page_top ; $lc++) {

				# Go on adding items as long as $lc is <= to $factoring_company_schedule_load_count
				if ($lc <= $factoring_company_schedule_load_count) { ?>
					
					<tr>
				    <td style="width: 131px; padding: 9px 0 8px 5px;"><?= $first_invoice_number ?></td>
				    <td style="width: 88px; padding: 9px 0 8px 5px; font-size: 12px;"><?= date('m/d/Y') ?></td> 
				    <td style="width: 263px; padding: 9px 0 8px 5px; font-size: 10px;"><?= strtolower($broker_id_company_name[$load_list_broker_id[$lc]]) ?></td>
				    <td style="width: 156px; padding: 9px 0 8px 5px; font-size: 10px;"><?= $load_list_load_number[$lc] ?></td>
				    <td style="width: 119px; padding: 9px 0 8px 5px;">$ <?= number_format(($load_list_line_haul[$lc] - $sum_other_charge[$load_list_load_id[$lc]]), 2) ?></td>
				  </tr> <?php
				}

				# Increment last invoice number
				$first_invoice_number++;
			}
			?>

		</table>
	</div>


	<div class="hidden" style="position: absolute; top: 0; left: 0; width: 785px; height: 1001px; background: url(http://<?= str_replace('quantum.', '', $_SERVER['HTTP_HOST']) ?>/files/schedule/bg/<?= $client_assoc_factoring_company_client_id ?>.jpg);"></div> <?php

	# End buffer to save PHP variables and HTML tags into a variable
	$html = ob_get_contents();

	# Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
	$mpdf->WriteHTML(utf8_encode($html));

	$i < $soar_num_pages ? $mpdf->AddPage() : '';
}

# Save content in var
$content = $mpdf->Output('', 'S');

# Save soar file
$save_soar = $mpdf->Output('/home/' . $rootFolder . '/public_html/files/schedule/soar-' . $_GET['schedule_id'] . '.pdf','F');
