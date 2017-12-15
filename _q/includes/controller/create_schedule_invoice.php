<?php 
session_start();
ob_start();
?>
<?php 

# Create invoice

# Broker
$broker = DB::getInstance()->query("SELECT * FROM broker_address WHERE broker_id = " . $_GET['broker_id'] . " && address_type = 1");
$broker_count = $broker->count();

if ($broker_count) {
	foreach ($broker->results() as $broker_data) {
		
		$broker_address_line_1 = html_entity_decode($broker_data->line_1);
		$broker_address_line_2 = html_entity_decode($broker_data->line_2);
		$broker_address_line_3 = html_entity_decode($broker_data->line_3);
		$broker_city = html_entity_decode($broker_data->city);
		$broker_state_id = $broker_data->state_id;
		$broker_zip_code = $broker_data->zip_code;

		$broker_counter++;
	}
}

# Get load_number and line haul
$loader_load_number = DB::getInstance()->query("SELECT * FROM loader_load WHERE load_id = " . $_GET['load_id']);
foreach ($loader_load_number->results() as $loader_load_number_data) {
	$load_number = html_entity_decode($loader_load_number_data->load_number);
	$line_haul = $loader_load_number_data->line_haul;
}

# List available other charges
$other_charges_list = DB::getInstance()->query("SELECT * FROM loader_other_charges ORDER BY name ASC");
$other_charges_list_count = $other_charges_list->count();
$i = 1;

if ($other_charges_list_count) {

	foreach ($other_charges_list->results() as $other_charges_list_data) {
		
		$charge_data_id[$i] = $other_charges_list_data->data_id;
		$charge_name[$i] = $other_charges_list_data->name;
		$i++;

		$charge_id_name[$other_charges_list_data->data_id] = $other_charges_list_data->name;
	}
}

# Get other charges
$load_other_charges = DB::getInstance()->query("SELECT * FROM loader_load_other_charges WHERE load_id = " . $_GET['load_id'] . " ORDER BY price DESC");
$load_other_charges_count = $load_other_charges->count();
$load_other_charges_counter = 1;
if ($load_other_charges_count) {
	foreach ($load_other_charges->results() as $load_other_charges_data) {
		$load_other_charges_data_id[$load_other_charges_counter] = $load_other_charges_data->data_id;
		$load_other_charges_charge_id[$load_other_charges_counter] = $load_other_charges_data->other_charge_id;
		$load_other_charges_price[$load_other_charges_counter] = $load_other_charges_data->price;

		$load_other_charges_counter++;
	}

	# Get subtotal
	$sum = 0;
	for ($i = 1; $i <= $load_other_charges_count ; $i++) {
		$sum+= $load_other_charges_price[$i];
	}
}

$subtotal = $line_haul + $sum;

# Checkpoints (pick ups)
$loader_checkpoint_pick_up = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . ' && data_type = 0 ORDER BY date_time ASC');
$loader_checkpoint_pick_up_count = $loader_checkpoint_pick_up->count();
if ($loader_checkpoint_pick_up_count) {
	$checkpointCounter = 1;
	foreach ($loader_checkpoint_pick_up->results() as $loader_checkpoint_pick_up_data) {

		$checkpoint_pick_up_city[$checkpointCounter] = html_entity_decode($loader_checkpoint_pick_up_data->city);
		$checkpoint_pick_up_state_id[$checkpointCounter] = $loader_checkpoint_pick_up_data->state_id;

		$checkpointCounter++;
	}				
}

# Checkpoints (drop offs)
$loader_checkpoint_drop_off = DB::getInstance()->query("SELECT * FROM loader_checkpoint WHERE load_id = " . $_GET['load_id'] . ' && data_type = 1 ORDER BY date_time ASC');
$loader_checkpoint_drop_off_count = $loader_checkpoint_drop_off->count();
if ($loader_checkpoint_drop_off_count) {
	$checkpointCounter = 1;
	foreach ($loader_checkpoint_drop_off->results() as $loader_checkpoint_drop_off_data) {

		$checkpoint_drop_off_city[$checkpointCounter] = html_entity_decode($loader_checkpoint_drop_off_data->city);
		$checkpoint_drop_off_state_id[$checkpointCounter] = $loader_checkpoint_drop_off_data->state_id;

		$checkpointCounter++;
	}				
}

# Save invoice_color in var
$client_id_invoice_color[$client_assoc_factoring_company_client_id] ? $invoice_color = $client_id_invoice_color[$client_assoc_factoring_company_client_id] : $invoice_color = 'b00b00';

# Start buffer to save PHP variables and HTML tags into a variable
ob_start(); 

echo '<html>
	<head>
		<style type="text/css">
			body {font-family: arial;}
			ul {list-style-type: none; clear: both;}

			.background-1 {background: #' . $invoice_color . '; color: #fff;}
			.background-2 {background: #a6a6a6; color: #fff;}

			.amount {text-align: center;}
			.line-haul {margin: 10px; border: 1px solid #888888; clear: both; min-height: 80px;}
			.line-haul h3 {text-align: center; margin: 0;}
			.line-haul p {margin: 3px 0;}
			.invoice-details th, .invoice-details td {padding: 3px 5px;}
		</style>
	</head>
	<body>
		<h2 style="text-align: right;">Load #' . $load_number . '</h2>
		<div class="background-1" style="float: left; width: 33%; text-align: center; margin: 0 ! important; height: 85px;">
			<h2>' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '</h2>
			<p style="margin: 0;">MC# ' . $client_id_mc_number[$client_assoc_factoring_company_client_id] . '</p>
		</div>
		<div class="background-2" style="float: left; width: 33%; background: #a6a6a6; text-align: center; margin: 0 ! important; height: 85px;">
			<h1>INVOICE</h1>
		</div>
		<div class="background-2" style="float: left; width: 33%; background: #a6a6a6; margin: 0 ! important; height: 85px;">
			<p style="text-align: right; margin: 10px 10px 3px 0;">908-731-6031</p>
			<p style="text-align: right; margin: 0 10px 3px 0;">admin@logisticsfrog.com</p>
			<p style="text-align: right; margin: 0 10px 3px 0;">F 888-311-5487</p>
		</div>
		<p style="text-align: right; margin-right: 10px; margin-top: 10px;"><b>Invoice Date:</b> ' . date('m/d/Y') . '</p>
		<p style="text-align: right; margin-right: 10px ; margin-bottom: 10px;"><b>Invoice number:</b> ' . $_GET['create_invoice'] . '</p>
		<ul style="min-height: 110px;">
			<li style="float: left; width: 66%;">
				<p><b>Bill to:</b></p>
				' . $broker_address_line_1 . '<br> ' . $broker_address_line_2 . '<br> ' . $broker_address_line_3 . '<br>
				' . $broker_city . ', ' . $state_abbr[$broker_state_id] . ', ' . $broker_zip_code . '
			</li>
			<li class="background-1 amount" style="float: left; width: 33%;">
				<p>Amount due:</p>
				<h2><i>$' . number_format($subtotal, 2) . '</i></h2>
			</li>
		</ul>
		<div class="line-haul" style="margin-bottom: 10px;">
			<h3 class="background-1">Line haul</h3>
			<ul>
				<li style="float: left; width: 50%; text-align: center;">';
					for ($cpc = 1; $cpc <= $loader_checkpoint_pick_up_count ; $cpc++) { 
						echo '<p>' . $checkpoint_pick_up_city[$cpc] . ', ' . $state_abbr[$checkpoint_pick_up_state_id[$cpc]] . '</p>';
					}
				echo '</li>
				<li style="float: left; width: 50%; text-align: center;">'; 
					for ($cpc = 1; $cpc <= $loader_checkpoint_drop_off_count ; $cpc++) { 
						echo '<p>' . $checkpoint_drop_off_city[$cpc] . ', ' . $state_abbr[$checkpoint_drop_off_state_id[$cpc]] . '</p>';
					}
				echo '</li>
			</ul>
		</div>
		<table class="invoice-details" style="width:100%; border-collapse: collapse;">
		  	<tr class="background-1" style="border: 1px solid #' . $invoice_color . ';">
			    <th style="color: #fff;">Item</th>
			    <th style="color: #fff;">Rate</th>
		  	</tr>
		  	<tr style="border: 1px solid #888888;">
			    <td>Line haul</td>
			    <td style="text-align: right;">$ ' . number_format($line_haul, 2) . '</td>
		  	</tr>';
		  	if ($load_other_charges_count) {
				for ($oc = 1; $oc <= $load_other_charges_count ; $oc++) { ?>
				 	<tr style="border: 1px solid #888888;">
						<td>
							<?= $charge_id_name[$load_other_charges_charge_id[$oc]] ?>
						</td>
						<td style="text-align: right;">
							$ <?= number_format($load_other_charges_price[$oc], 2) ?>
						</td>
					</tr> <?php
				}
			}
		echo '</table>
		<table style="width:100%; margin-top: 20px;">
		  	<tr style="text-align: right;">
			    <td><b>Subtotal:</b></td>
			    <td style="text-align: right">$' . number_format($subtotal, 2) . '</td>
		  	</tr>
		  	<tr style="text-align: right;">
			    <td><h2>Total:</h2></td>
			    <td style="text-align: right"><h2><i>$' . number_format($subtotal, 2) . '</i></h2></td>
		  	</tr>
		</table>
		<img src="'. $_SESSION['HtmlDelimiter'] .'img/imager/logo.jpg" width="63" height="54" style="float: right;">
		<div class="background-1" style="margin: 80px 0 10px 0;">
			<p style="text-align: center; margin: 5px;">THANK YOU FOR YOUR BUSINESS</p>
		</div>
	</body>
</html>';

$html = ob_get_contents();
// End buffer to save PHP variables and HTML tags into a variable

//Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
$mpdf->WriteHTML(utf8_encode($html));

# Save content in var
$content = $mpdf->Output('', 'S');

# Save invoice file
$save_invoice = $mpdf->Output($schedule_directory . '_' . $_GET['load_id'] . '_soar-' . $_GET['schedule_id'] . '.pdf','F');

# Merge files
# Create array with files to be merged, invoice (just created), ratecon and bol
$file_array= array($schedule_directory . '_' . $_GET['load_id'] . '_soar-' . $_GET['schedule_id'] . '.pdf', $file_directory . 'rate-confirmation-' . $_GET['entry_id'] . '-' . $_GET['load_id'] . '.pdf', $file_directory . 'bol-' . $_GET['entry_id'] . '-' . $_GET['load_id'] . '.pdf');

$outputName = $schedule_directory . $_GET['invoice_name'];

$cmd = "gs -q -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -sOutputFile=$outputName ";

# Add each pdf file to the end of the command
foreach($file_array as $file) {
  $cmd .= $file." ";
}

$result = shell_exec($cmd);

# Kill pre invoice
if (file_exists($schedule_directory . '_' . $_GET['load_id'] . '_soar-' . $_GET['schedule_id'] . '.pdf')) {
  unlink($schedule_directory . '_' . $_GET['load_id'] . '_soar-' . $_GET['schedule_id'] . '.pdf');
}