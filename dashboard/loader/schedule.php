<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

if ($_GET['schedule_id']) { ?>

	<div class="row">

		<?php
		# Show in confirmation page
		if ($_GET['fee_option']) { ?>
			
			<div class="col-sm-12 col-md-12">
				
				<div class="main-box no-header">
					
					<div class="main-box-body clearfix">
						
						<form action="" method="post">

						  <div class="form-group input-group">
				        <span class="input-group-addon">To</span>
				        <input type="text" name="to" class="form-control" value="<?= $factoring_company_id_invoicing_email[$client_assoc_factoring_company_id] ?>">
				      </div>

						  <div class="form-group input-group">
				        <span class="input-group-addon">Cc</span>
				        <input type="text" name="cc" class="form-control" value="admin@logisticsfrog.com<?= isset($driver_manager_id) ? ', ' . implode(', ', $driver_manager_id) : '' ?><?= isset($owner_id) ? ', ' . implode(', ', $owner_id) : '' ?>">
				      </div>

				      <div class="form-group input-group">
				        <span class="input-group-addon">Subject</span>
				        <input type="text" name="subject" class="form-control" value="<?= $schedule_config_email_subject ?>">
				      </div>

				      <div class="form-group">
			    			<textarea id="body" name="body" rows="10" cols="80">
			    				<?= $schedule_config_email_body ?>
			    			</textarea>
			    		</div>

						  <div class="form-group">
				        <button type="submit" class="btn btn-link">Send</button>
				        <a style="margin: 10px 0;" class="pull-right red" href="<?= $_SERVER['HTTP_REFERER'] ?>">Go back</a>
				      </div>

						  <input type="hidden" name="_controller_send_schedule" value="1">
						  <input type="hidden" name="counter" value="<?= $schedule_counter ?>">
						  <input type="hidden" name="token" value="<?= $csrfToken ?>">
						</form>
					</div>
				</div>
			</div> <?php
		}

		# Display form only if soar file exists or if soar is not required 
		if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf') 
			|| !$factoring_company_requires_soar[1]
			|| file_exists($schedule_directory . ($first_invoice_number) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf')) {

			# Form method
			# If there is no $_GET['fee_option'] or there is $_GET['fee_option'] but set to 0
			((!isset($_GET['fee_option'])) || (isset($_GET['fee_option']) && $_GET['fee_option'] < 1)) ? $form_method = 'get' : '';

			# If $_GET['fee_option'] is greater than 0
			$_GET['fee_option'] > 0 ? $form_method = 'post' : $form_method = 'get' ;

			# Display form only if (1) we have loads on schedule, (2) if we have all merged invoices and (3) if this is the current schedule OR if TAFS invoice exists
			if (($factoring_company_schedule_load_count && ($invoice_count == $load_list_count) && ($client_assoc_factoring_company_current_counter == $schedule_counter)) || file_exists($schedule_directory . ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf')) { ?>
				
				<div class="col-sm-12 col-md-12">

					<div class="main-box no-header">

						<div class="main-box-body clearfix">	 	
							<form action="" method="<?= $form_method ?>">
								<input type="hidden" name="schedule_id" value="<?= $_GET['schedule_id'] ?>">
								<div class="form-group">
									
									<?php 
									if ($_GET['fee_option'] == $client_assoc_factoring_company_main) {
										
										# Display text only option chosen
										echo '<p><b>Main service fee</b> <span class="fa fa-arrow-down"></span></p><p>' . $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_main] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_main]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 1 ? ' days' : ' day')) . ']</p>' ;

									} elseif ($_GET['fee_option'] == $client_assoc_factoring_company_alt) {

										# Display text only option chosen
										echo '<p><b>Alternate service fee</b> <span class="fa fa-arrow-down"></span></p><p>' . $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_alt] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_alt]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 1 ? ' days' : ' day')) . ']</p>' ;
									} else {

										# Prompt user to choose fee option ?>
										<select name="fee_option" class="form-control<?= isset($_GET['fee_option']) && $_GET['fee_option'] < 1 ? ' red' : '' ?>"<?= isset($_GET['fee_option']) && $_GET['fee_option'] < 1 ? ' style="border-color: red;"' : '' ?><?= $_GET['fee_option'] > 0 ? ' readonly' : '' ; ?>>
											
											<option value="0">Choose a service fee</option>
											
											<option value="<?= $client_assoc_factoring_company_main ?>">
												Main - <?= $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_main] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_main]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_main] > 1 ? ' days' : ' day')) . ']' ?>
											</option>

											<option value="<?= $client_assoc_factoring_company_alt ?>">
												Alt - <?= $factoring_company_service_fee_fee_did[$client_assoc_factoring_company_alt] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id_did[$client_assoc_factoring_company_alt]] . ' [' . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 0 ? $factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] : '') . ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days_did[$client_assoc_factoring_company_alt] > 1 ? ' days' : ' day')) . ']' ?>
											</option>
										</select> <?php
									} ?>
								</div>
								<div class="form-group">
									<?php 
									# If there is no $_GET['fee_option'] or there is $_GET['fee_option'] but set to 0
									if ((!isset($_GET['fee_option'])) || (isset($_GET['fee_option']) && $_GET['fee_option'] < 1)) { ?>

										<input type="submit" class="btn btn-link pull-right" value="Set service fee"> <?php
									} ?>
								</div>
							</form>
						</div>
					</div>
				</div> <?php
			}
		}
		
		# No loads warning
		if (!$factoring_company_schedule_load_count) { ?>

			<div class="alert alert-warning">
				<i class="fa fa-warning fa-fw fa-lg"></i>
				<strong>Warning!</strong> There are no loads on this schedule.
			</div> <?php
		}

		# If closed schedule
		if ($schedule_payment_confirmation == 3) { ?>

			<div class="alert alert-danger fade in">
				<h4>This schedule is closed <small class="pull-right"><?= $schedule_note_added[$closing_note_counter] ? 'Added ' . $schedule_note_added[$closing_note_counter] . ' by ' . $user_list_id_name[$schedule_note_user_id[$closing_note_counter]] . ' ' . $user_list_id_last_name[$schedule_note_user_id[$closing_note_counter]] : '' ?></small></h4>
				<?= $schedule_note_note[$closing_note_counter] ? '<p>Note:' . $schedule_note_note[$closing_note_counter] . '</p>' : '' ?>
			</div> <?php
		} ?>

		<div class="col-sm-12 col-md-12">

			<div class="main-box no-header<?= $_GET['create'] || $_GET['create_tafs'] || $schedule_payment_confirmation == 2 || $_GET['create_invoice'] ? '' : ' hidden'; ?>">

				<div class="main-box-body clearfix">

					<?php
					# Create file if $_GET['create']
					if ($_GET['create']) {

						# Call create soar controller
						include($module_directory . "controller/add_soar_file.php");
					}

					if ($_GET['create_tafs']) {
						
						# Call add_tafs_invoice controller
						include($module_directory . "controller/add_tafs_invoice.php");
					}

					# If the payment confirmation was uploaded with an incorrect amount
					if ($schedule_payment_confirmation == 2) {
						
						# Show incorrect amount warning and message. ?>

						<div class="alert alert-block alert-danger fade in">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h4>This schedule has payment confirmation but the amount is incorrect! <small class="pull-right">Added <?= $schedule_note_added[$incorrect_amount_counter] ?> by <?= $user_list_id_name[$schedule_note_user_id[$incorrect_amount_counter]] . ' ' . $user_list_id_last_name[$schedule_note_user_id[$incorrect_amount_counter]] ?></small></h4>
							<p>Note: <?= $schedule_note_note[$incorrect_amount_counter] ?></p>
							<form action="" method="post"<?= $_POST['close_schedule'] ? ' class="hidden"' : '' ?>>
								
								<div class="form-group">
									
									<button class="btn btn-link" type="submit">Move to paid/closed schedules</button>
								</div>
								
								<input type="hidden" name="close_schedule" value="1">
          			<input type="hidden" name="token" value="<?= $csrfToken ?>">
							</form>
						</div> <?php

						# If closing schedule
						if ($_POST['close_schedule']) { ?>
							
							<form method="post" action="">
								<div class="form-group has-error">

									<label class="red">Notes</label>
									<textarea name="note" class="form-control red" style="width: 297px;"></textarea>
								</div>

								<button type="submit" class="btn btn-link red">Close schedule</button>
								<input type="hidden" name="_controller_schedule" value="close_schedule">
          			<input type="hidden" name="token" value="<?= $csrfToken ?>">
							</form> <?php 
						}
					}

					# Call create invoice controller if $_GET['create_invoice']
					if ($_GET['create_invoice']) {

						# Hide it
						echo '<div class="hidden">';
						
							# Controller call
							include($module_directory . "controller/create_schedule_invoice.php");
						echo '</div>';
					} ?>
				</div>
			</div>
		</div>

	</div>

	<div class="row">

		<div class="col-sm-12 col-md-12">
			
			<div class="main-box">
				<header class="main-box-header clearfix">
					<h2>Loads on schedule</h2>
				</header>

				<div class="main-box-body clearfix">
					<?php if ($factoring_company_schedule_load_count) {
						
						# Display loads table ?>

						<table id="<?= $module_name ?>-table" class="table" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Broker</th>
									<th>Load #</th>
									<th>Driver</th>
									<th>Line haul</th>
									<th>Pick up</th>
									<th>Delivery</th>
									<th>Pickup</th>
									<th>Delivery</th>
									<th>User</th>
									<th></th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								
								<?php
								for ($i = 1; $i <= $load_list_count ; $i++) { ?>
									
									<tr

										<?php 
										if ($load_list_billing_status[$i] == 0) {
											
											if ($bol_exists[$i]) {
												
												echo ' style="background: #af0b01; color: #fff;"'; // Danger, has bol but hasn't been charged
											}
										} elseif ($load_list_billing_status[$i] == 1) {
											
											echo ' style="background: #9c28b1; color: #fff;"'; // Info, has been charged
										} elseif ($load_list_billing_status[$i] == 2) {
											
											echo ' style="background: #e0b50a;"'; // Warning, has been charged with wrong data
										} elseif ($load_list_billing_status[$i] == 3) {
											
											echo ' style="background: #8bc34a;"'; // Success, charged and closed
										} ?>

									>
										<td>
											<small>
												<?= ucwords($broker_id_company_name[$load_list_broker_id[$i]]) ?>
											</small>
										</td>
										<td>
											<small>
												<?= $load_list_load_number[$i] ?>
											</small>
										</td>
										<td>
											<small data-toggle="tooltip" data-placement="top" title="<?= $user_list_phone_number[$load_list_driver_id[$i]] ?>">
												<?= $user_list_id_name[$load_list_driver_id[$i]] . ' ' . $user_list_id_last_name[$load_list_driver_id[$i]]  ?>
											</small>
										</td>
										<td>
											<small>
												<?= '$ ' . $load_list_line_haul[$i] .' <i class="fa fa-arrows-h"></i> ' . $load_list_miles[$i] ?>
											</small>
										</td>
										<td>
											<small>
												<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_list_first_checkpoint_time[$i] ?>"> <?= $load_list_first_checkpoint_date[$i] ?></span>
											</small>
										</td>
										<td>
											<small>
												<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_list_last_checkpoint_time[$i] ?>"> <?= $load_list_last_checkpoint_date[$i] ?></span>
											</small>
										</td>
										<td>
											<small>
												<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_first_checkpoint_zip_code[$i] ?>"> <?= ucwords(strtolower($load_first_checkpoint_city[$i])) . ', ' . $state_abbr[$load_first_checkpoint_state_id[$i]] ?></span>
											</small>
										</td>
										<td>
											<small>
												<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_last_checkpoint_zip_code[$i] ?>"> <?= ucwords(strtolower($load_last_checkpoint_city[$i])) . ', ' . $state_abbr[$load_last_checkpoint_state_id[$i]] ?></span>
											</small>
										</td>
										<td>
											<small>
												<span> 
													<?= $user_list_id_name[$load_list_user_id[$i]] . ' ' . substr($user_list_id_last_name[$load_list_user_id[$i]], 0, 1) ?>
												</span>
											</small>
										</td>
										<td>
											<a data-toggle="tooltip" data-placement="top" title="View load" href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $load_list_load_id[$i] ?>">
												<i class="fa fa-cube"></i>
											</a>
										</td>

										<td>

											<form action="" method="post">
												
												<button type="submit" class="btn btn-link" data-toggle="tooltip" data-placement="top" title="Delete load from schedule">

													<i class="fa fa-trash-o red"></i>
												</button>

											  <input type="hidden" name="load_id" value="<?= $load_list_load_id[$i] ?>">
												<input type="hidden" name="_controller_schedule" value="delete_load">
											  <input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
										</td>

									</tr> <?php
								}
								?>

							</tbody>
						</table> <?php
					} else {

						# Display warning ?>

						<div class="alert alert-warning">
							<i class="fa fa-warning fa-fw fa-lg"></i>
							<strong>Warning!</strong> There are no loads on this schedule.
						</div> <?php
					} ?>

				</div>
			</div>
		</div>
	</div>

	<?php

	# Display TAFS invoice
	if (file_exists($schedule_directory . ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf')) { ?>
		<embed<?= !$_GET['fee_option'] ? ' id="tafs-invoice "' : ' ' ?>src="<?= $_SESSION["href_location"] ?>files/schedule/<?= ($first_invoice_number - 1) . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[1]] . '.pdf' ?>?r=<?= date('Gis') ?>" width="100%" height="1015px"> <?php
	}

	# Display soar payment confirmation file
	if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '-payment-confirmation.pdf')) { ?>
		<embed<?= !$_GET['fee_option'] ? ' id="payment-confirmation"' : ' ' ?>src="<?= $_SESSION["href_location"] ?>files/schedule/soar-<?= $_GET['schedule_id'] ?>-payment-confirmation.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
	}
	
	# Display soar file
	if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf')) { ?>
		<embed<?= $_GET['fee_option'] ? '' : ' id="soar"' ?> src="<?= $_SESSION["href_location"] ?>files/schedule/soar-<?= $_GET['schedule_id'] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
	}

	# Get last invoice_number counter for this schedule id
	$first_invoice_2 = DB::getInstance()->query("SELECT invoice_number FROM factoring_company_schedule_load WHERE schedule_id = " . $_GET['schedule_id'] . " ORDER BY invoice_number ASC LIMIT 1");

	foreach ($first_invoice_2->results() as $first_invoice_2_data) {
		$first_invoice_2_number = $first_invoice_2_data->invoice_number;
	}

	for ($i=1; $i <= $load_list_count ; $i++) { 

		# Declare file name
		$pre_invoice_file_name = strtolower(str_replace([' & ', ' '], ['-', '-'], $first_invoice_2_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));
		$invoice_file_name = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_file_name);

		# Check if merged invoice exists for every load in schedule
		if (file_exists($schedule_directory . $invoice_file_name)) { ?>
			<embed <?= $_GET['fee_option'] ? '': 'id="invoice-' . $load_list_load_id[$i] . '"' ?> 
				src="<?= $_SESSION["href_location"] ?>files/schedule/<?= $invoice_file_name ?>?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
		}

		$first_invoice_2_number++;
	}
}
?>

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
