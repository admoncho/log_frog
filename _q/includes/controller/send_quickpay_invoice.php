<?php 
session_start();
ob_start();
echo "string";

$validation = $validate->check($_POST, array(
    'to' => array(
    	'required' => true, 
    	'to' => '/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/'
    ),
    'subject' => array('required' => true),
    'body' => array('required' => true)
));

if($validation->passed()) {


	$mail->From = 'admin@logisticsfrog.com';
	$mail->FromName = 'QUICKPAY - ' . $client_id_company_name[$_GET['client_id']] . '.';
	$mail->AddAddress(Input::get('to')); // Add a recipient

	$mail->addReplyTo('admin@logisticsfrog.com', 'Quickpay');

	# CC
	#split string into array seperated by ', '
	$cc_array = explode(', ', Input::get('cc'));
	foreach($cc_array as $cc_value) { //loop over values 
	  $mail->addCC($cc_value);
	}

	# Set attachment path
	$rate_confirmation = str_replace('_q/', '', $_SESSION['ProjectPath'] . '/files/rate-confirmation-' . $_GET['entry_id'] . '-' . $_GET['load_id'] . '.pdf');
	$bol = str_replace('_q/', '', $_SESSION['ProjectPath'] . '/files/bol-' . $_GET['entry_id'] . '-' . $_GET['load_id'] . '.pdf');

	# Add attachments
	$mail->addAttachment($rate_confirmation);
	$mail->addAttachment($bol);

	# Set email format to HTML
	$mail->isHTML(true);

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
			<h2 style="text-align: right;">Quickpay - Load #' . $load_number . '</h2>
			<div class="background-1" style="float: left; width: 33%; text-align: center; margin: 0 ! important; height: 85px;">
				<h2>' . $client_id_company_name[$_GET['client_id']] . '</h2>
				<p style="margin: 0;">MC# ' . $client_id_mc_number[$_GET['client_id']] . '</p>
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
			<p style="text-align: right; margin-right: 10px ; margin-bottom: 10px;"><b>Invoice number:</b> ' . ($quickpay_invoice_counter + 1) . '</p>
			<ul style="min-height: 110px;">
				<li style="float: left; width: 66%;">
					<p><b>Bill to:</b></p>
					' . $broker_address_line_1[1] . ',<br> ' . $broker_address_line_2[1] . '<br>
					' . $broker_address_city[1] . ', ' . $state_name[$broker_address_state_id[1]] . '
				</li>
				<li class="background-1 amount" style="float: left; width: 33%;">
					<p>Amount due:</p>
					<h2><i>$' . number_format($subtotal - (($subtotal / 100) * $invoice_service_fee_fee), 2) . '</i></h2>
				</li>
			</ul>
			<div class="line-haul" style="margin-bottom: 10px;">
				<h3 class="background-1">Line haul</h3>
				<ul>
					<li style="float: left; width: 50%; text-align: center;">';
						for ($i = 1; $i <= $loader_checkpoint_pick_up_count ; $i++) { 
							echo '<p>' . $checkpoint_pick_up_city[$i] . ', ' . $state_abbr[$checkpoint_pick_up_state_id[$i]] . '</p>';
						}
					echo '</li>
					<li style="float: left; width: 50%; text-align: center;">'; 
						for ($i = 1; $i <= $loader_checkpoint_drop_off_count ; $i++) { 
							echo '<p>' . $checkpoint_drop_off_city[$i] . ', ' . $state_abbr[$checkpoint_drop_off_state_id[$i]] . '</p>';
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
					for ($i = 1; $i <= $load_other_charges_count ; $i++) { ?>
					 	<tr style="border: 1px solid #888888;">
							<td>
								<?= $load_other_charges_item[$i] ?>
							</td>
							<td style="text-align: right;">
								$ <?= number_format($load_other_charges_price[$i], 2) ?>
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
				    <td><b>Service charge (' . $invoice_service_fee_fee . '%):</b></td>
				    <td style="text-align: right">($' . number_format(($subtotal / 100) * $invoice_service_fee_fee, 2) . ')</td>
			  	</tr>
			  	<tr style="text-align: right;">
				    <td><h2>Total:</h2></td>
				    <td style="text-align: right"><h2><i>$' . number_format($subtotal - (($subtotal / 100) * $invoice_service_fee_fee), 2) . '</i></h2></td>
			  	</tr>
			</table>
			<p>If sending paper checks, please send them to our mailing address:</p>
			' . $client_billing_address_line_1[$_GET['client_id']] . '<br> ' . $client_billing_address_line_2[$_GET['client_id']] . '<br>
			' . $client_billing_address_city[$_GET['client_id']] . ', ' . $state_name[$client_billing_address_state_id[$_GET['client_id']]] . ' ' . $client_billing_address_zip_code[$_GET['client_id']] 
                                . '<img src="'. $_SESSION["HtmlDelimiter"] . 'img/imager/logo.jpg" width="63" height="54" style="float: right;">
			<div class="background-1" style="margin: 80px 0 10px 0;">
				<p style="text-align: center; margin: 5px;">THANK YOU FOR YOUR BUSINESS</p>
			</div>
		</body>
	</html>';

	# End buffer to save PHP variables and HTML tags into a variable
	$html = ob_get_contents();

	# Here convert the encode for UTF-8, if you prefer the ISO-8859-1 just change for $mpdf->WriteHTML($html);
	$mpdf->WriteHTML(utf8_encode($html));

	$content = $mpdf->Output('', 'S');

	# Attach invoice on the fly
	$mail->addStringAttachment($content, 'invoice-' . $_GET['entry_id'] . '-' . $_GET['load_id'] . '-' . ($quickpay_invoice_counter + 1) . '.pdf');

	$mail->Subject = Input::get('subject');
	$mail->Body    = Input::get('body');
	$mail->AltBody = '';

	if(!$mail->send()) {
	  // echo 'Message could not be sent.';
	  // echo 'Mailer Error: ' . $mail->ErrorInfo;
		Session::flash('quickpay_invoice_sent_error', 'There was an error sending the invoice, please try again.') ;
		Redirect::to('loader?id=' . $_GET['entry_id'] . '&load_id=' . $_GET['load_id']);
	} else {
		
		# Update counter
		$update_counter = DB::getInstance()->query("UPDATE loader_quickpay_invoice_counter SET counter = " . ($quickpay_invoice_counter + 1) . " WHERE broker_id = " . $_GET['broker_id'] . " && client_id = " . $_GET['client_id']);

		# Save invoice to server
		$save_invoice = $mpdf->Output('/home/' . $rootFolder . '/public_html/files/quickpay-invoices/invoice-' . $_GET['entry_id'] . '-' . $_GET['load_id'] . '-' . ($quickpay_invoice_counter + 1) . '.pdf','F');
		
		# Add note
		$insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, type, user_id) VALUES (" . $_GET['load_id'] . ", 'Invoice sent', 0, 1, " . $user->data()->user_id . ")");

	  // Change billing_status to 1 (billed) and add billing_date
		$billing_status_update = DB::getInstance()->query("UPDATE loader_load SET billing_status = 1, billing_date = '" . date('Y/m/d') . "' WHERE load_id = " . $_GET['load_id']);
		$billing_status_update->count() ? Session::flash('quickpay_invoice_sent', 'Invoice sent successfully!') . Redirect::to('loader?id=' . $_GET['entry_id'] . '&load_id=' . $_GET['load_id'] . '#load-notes') : Session::flash('quickpay_invoice_sent_error', 'There was an error sending the invoice, please try again.') ;
	}
} ?>