<?php 
session_start();
ob_start();
# Redirect if no query string
if (!$_SERVER['QUERY_STRING']) { ?>
	
	<script type="text/javascript">
		
		window.location = "/dashboard/loader/"
	</script> <?php
}

require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_underscore = str_replace('-', '_', basename(__FILE__, '.php'));

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");
?>

<div class="row">
	<div class="col-sm-12 col-md-12">

		<div class="row">
			<div class="col-sm-12 col-md-6">
				
				<div class="row">

					<div class="col-sm-12 col-md-5 text-right">

						<p class="lead" style="margin-bottom: 0;">

							<?php
							
							if ($checkpoint_id_city[1]) {
							 	
								echo $checkpoint_id_city[1] . ', ' . $state_abbr[$checkpoint_id_state_id[1]];
							} else {

								echo '<i class="red"><i class="fa fa-warning"></i> Info missing</i>';
							}
							?>
							
						</p>
						<small><?= $checkpoint_id_date_time_2[1] ?></small>
					</div>
					<div class="col-sm-12 col-md-2 text-center">
						
						<p class="lead" style="margin-bottom: 0;"><i class="fa fa-arrows-h"></i></p>
					</div>
					<div class="col-sm-12 col-md-5">

						<p class="lead" style="margin-bottom: 0;">

							<?php
							if ($checkpoint_id_city[$checkpoint_id_count] && $checkpoint_id_count > 1) {
								
								echo $checkpoint_id_city[$checkpoint_id_count] . ', ' . $state_abbr[$checkpoint_id_state_id[$checkpoint_id_count]];
							} else {

								echo '<i class="red"><i class="fa fa-warning"></i> Info missing</i>';
							}
							?>
							
						</p>
						<small><?= $checkpoint_id_city[$checkpoint_id_count] && $checkpoint_id_count > 1 ? $checkpoint_id_date_time_2[$checkpoint_id_count] : '' ?></small>
					</div>
					
					<div class="col-sm-12 col-md-12">

						<p class="text-center">
							
							<small>

								<?php
								if ($user->data()->user_group == 4) {

									# Show broker name
									echo $broker_id_company_name[$load_broker_id[1]];
								} else {

									# Show broker name in link ?>

									<a href="<?= $_SESSION['href_location'] ?>dashboard/broker/broker?broker_id=<?= $load_broker_id[1] ?>">
										<?= $broker_id_company_name[$load_broker_id[1]] ?>
									</a> <?php
								}	
								?>

							</small>
							
						</p>

					</div>

					<div class="col-sm-12 col-md-4">
						
						<p<?= $load_deadhead[1] != '0.0' ? ' class="red"' : '' ?>>
							<small>

								<b><?= $load_deadhead[1] != '0.0' ? 'DH: ' . str_replace('.0', '', $load_deadhead[1]) . 'm' : 'no deadhead' ?></b>

							</small>
						</p>

						<p>
							<small>

								<b>W: <?= str_replace([',000', '.00'], ['k', ''], number_format($load_weight[1], 2)) . ' lbs'; ?></b>

							</small>
						</p>

						<!-- <p>
							<small>

								Avg diesel price $<?= $load_avg_diesel_price[1] ?>

							</small>
						</p> -->

					</div>

					<div class="col-sm-12 col-md-4">

						<p class="text-center lead" style="font-size: 2em; color: #006400; margin-bottom: 5px;">
							
							<b>$<?= number_format($load_line_haul[1], 2); ?></b>
							
						</p>

						<p class="text-center">
							
							<b><?= $load_miles[1] ?> m @ $<?= number_format($load_line_haul[1] / $load_miles[1], 2) ?>/m</b>

						</p>
						
						<p class="text-center lead">
							<small>
								
								<?= $load_reference[1] ? '<b>Pick Up #: </b><span class="red">' . $load_reference[1] . '</span>' : '' ?>

							</small>
						</p>

					</div>

					<div class="col-sm-12 col-md-4 text-right">

						<?php
						if ($user->data()->user_group != 4) {
							
							# Edit and Delete button
							if ($load_load_status[1] == 0) { ?>
									
								<a href="load?load_id=<?= $_GET['load_id'] ?>&edit_main=1" class="btn btn-link btn-sm<?= $_GET['edit_main'] ? ' hidden' : '' ?>">
									Edit main data <i class="fa fa-pencil"></i>
								</a>

								<?php
								# Show add other charges button here since the other charges panel is hidden when there is no data
								if (!$other_charges_count && !$_GET['edit_other_charges']) { ?>
									
									<a href="load?load_id=<?= $_GET['load_id'] ?>&edit_other_charges=1" class="btn btn-link btn-sm<?= $_GET['edit_main'] ? ' hidden' : '' ?>">
										Add other charges <i class="fa fa-plus"></i>
									</a> <?php
								}
								?>

								<form action="" method="post" class="<?= $_GET['edit_main'] ? ' hidden' : '' ?>">
										
									<button class="btn btn-link btn-sm red pull-right"> Delete load <i class="fa fa-trash-o"></i></button>
									<input type="hidden" name="load_number" value="<?= $load_load_number[1] ?>">
									<input type="hidden" name="_controller_loader" value="delete_load">
									<input type="hidden" name="token" value="<?= $csrfToken ?>">
								</form> <?php
							}
						}
						?>

					</div>

				</div>
			</div>

			<div class="col-sm-12 col-md-6">

				<ul class="list-group">

					<?php
		  		# Show send info form
	  			if ($_GET['send_info']) {

	  				# Show send info form ?>

	  				<li class="list-group-item">

		  				<div class="alert alert-info">
								<i class="fa fa-info-circle fa-fw fa-lg"></i>
								Send load info
								<a class="pull-right red" href="load?load_id=<?= $_GET['load_id'] ?>">Cancel</a>
							</div>

		  				<form action="" method="post">
							  <div class="form-group input-group">
				          <span class="input-group-addon">To</span>
				          <input type="text" name="to" class="form-control" value="<?= $user_list_id_email[$entry_driver_id[1]] ?>">
					      </div>
							  <div class="form-group input-group">
				          <span class="input-group-addon">Cc</span>
				          <input type="text" name="cc" class="form-control" value="<?= $user->data()->email ?>">
					      </div>

					      <h1 class="text-center">logisticsfrog.com</h1>

				      	<div class="col-sm-12 col-md-6">
                  <p style="color: #59ab02;"><b><?= $user_list_id_name[$entry_driver_id[1]] . ' ' . $user_list_id_last_name[$entry_driver_id[1]] ?></b></p>
                  <p style="color: #59ab02;"><b><?= $client_id_company_name[$load_client_id[1]] ?></b></p>
                  <p style="color: #59ab02;"><b><?= $broker_id_company_name[$load_broker_id[1]] ?></b></p>
                  <?= $commodity ?>
                  <?= $notes ?>
                </div>

                <div class="col-sm-12 col-md-6" style="margin-bottom: 20px;">
                  <p><b>Price:</b> $ <?= $load_line_haul[1] ?></p>
                  <p><b>Miles:</b> <?= number_format($load_miles[1], 2) ?></p>
                  <p><b>Per loaded mile: </b> $ <?= number_format(str_replace(',', '', $load_line_haul[1]) / $load_miles[1], 2) ?></p>
                  <p><b>Weight:</b> <?= number_format($load_weight[1], 0) ?> lbs</p>
                  <p><b>Deadhead:</b> <?= $load_deadhead[1] ?></p>
                  <p><b>Reference: </b><?= $load_reference[1] ?></p>
                </div>

				      	<table cellpadding="0" cellspacing="0" border="0" width="100%">
						      <tbody>

						      	<tr><!-- title -->
                      <td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 18px; letter-spacing: 0px; text-align: center; color:#000;">   
                        <strong>Pick up Information</strong>
                      </td>
                    </tr>
                    
                    <?php 
					          for ($i = 1; $i <= $checkpoint_id_count; $i++) {

					          	# Display pick ups only
					          	if ($checkpoint_id_data_type[$i] == 0) {
					          		
					              # Display line separator only if we have more than 1 address and if we are on the second address+
					          		if ($checkpoint_id_count > 1 && $i > 1) {  ?>

				                  <tr><!-- line -->
				                    <td width="100%" height="1" bgcolor="#d9d9d9"></td>
				                  </tr><?php
					              } ?>

					              <tr>
				                  <td width="100%">

				                      <?= $checkpoint_id_count > 1 ? '<h4>Address '.$i.'</h4>' : '' ?>

				                      <p style="line-height: 9px;"><?= $checkpoint_id_line_1[$i] ?></p>
				                      <p style="line-height: 9px;"><?= $checkpoint_id_line_2[$i] ?></p>
				                      <p style="line-height: 9px;"><?= $checkpoint_id_city[$i] ?>, <?= $state_abbr[$checkpoint_id_state_id[$i]] ?> <?= $checkpoint_id_zip_code[$i] ?></p>
				                      <hr>
				                      <p  style="line-height: 9px;">Date: <?= $checkpoint_id_date_time_2[$i] ?></p>
				                      <p  style="line-height: 9px;"><?= $checkpoint_id_contact[$i] ? '<p  style="line-height: 9px;">Contact: '. $checkpoint_id_contact[$i] .'</p>' : '' ?>
				                      <?= $checkpoint_id_appointment[$i] ? '<p  style="line-height: 9px;">Appointment: '. $checkpoint_id_appointment[$i] .'</p>' : '' ?>
				                      <?= $checkpoint_id_notes[$i] ? '<p>Notes: ' . $checkpoint_id_notes[$i] . '</p>' : '' ?></p>
				                  </td>
					              </tr><?php
					          	}
					          }
					          ?>

					          <tr><!-- title -->
                      <td width="100%" style="font-family: helvetica, Arial, sans-serif; font-size: 18px; letter-spacing: 0px; text-align: center; color:#000;">   
                        <strong>Destination Information</strong>
                      </td>
                    </tr>
                    
                    <?php 
					          for ($i = 1; $i <= $checkpoint_id_count; $i++) {

					          	# Display drop offs only
					          	if ($checkpoint_id_data_type[$i] == 1) {
					          		
					              # Display line separator only if we have more than 1 address and if we are on the second address+
					          		if ($checkpoint_id_count > 1 && $i > 1) {  ?>

				                  <tr><!-- line -->
				                    <td width="100%" height="1" bgcolor="#d9d9d9"></td>
				                  </tr><?php
					              } ?>

					              <tr>
					              	<td width="100%">

				                      <?= $checkpoint_id_count > 1 ? '<h4>Address '.$i.'</h4>' : '' ?>

				                      <p style="line-height: 9px;"><?= $checkpoint_id_line_1[$i] ?></p>
				                      <p style="line-height: 9px;"><?= $checkpoint_id_line_2[$i] ?></p>
				                      <p style="line-height: 9px;"><?= $checkpoint_id_city[$i] ?>, <?= $state_abbr[$checkpoint_id_state_id[$i]] ?> <?= $checkpoint_id_zip_code[$i] ?></p>
				                      <hr>
				                      <p  style="line-height: 9px;">Date: <?= $checkpoint_id_date_time_2[$i] ?></p>
				                      <p  style="line-height: 9px;"><?= $checkpoint_id_contact[$i] ? '<p  style="line-height: 9px;">Contact: '. $checkpoint_id_contact[$i] .'</p>' : '' ?>
				                      <?= $checkpoint_id_appointment[$i] ? '<p  style="line-height: 9px;">Appointment: '. $checkpoint_id_appointment[$i] .'</p>' : '' ?>
				                      <?= $checkpoint_id_notes[$i] ? '<p>Notes: ' . $checkpoint_id_notes[$i] . '</p>' : '' ?></p>
				                  </td>
					              </tr><?php
					          	}
					          }
					          ?>

						      </tbody>
						    </table>

							  <div class="form-group">
				          <button type="submit" class="btn btn-link">Send</button>
				          <a class="btn btn-link pull-right red" href="load?load_id=<?= $_GET['load_id'] ?>">Cancel</a>
					      </div>
							  <textarea name="pick_up_info" class="hide">

						      <?php 
				          for ($i = 1; $i <= $checkpoint_id_count; $i++) {

				          	# Display pick ups only
				          	if ($checkpoint_id_data_type[$i] == 0) {
				          		
				              # Display line separator only if we have more than 1 address and if we are on the second address+
				          		if ($checkpoint_id_count > 1 && $i > 1) {  ?>

			                  <tr><!-- line -->
			                    <td width="100%" height="1" bgcolor="#d9d9d9"></td>
			                  </tr><?php
				              } ?>

				              <tr>
			                  <td width="100%" style=" font-size: 14px; line-height: 12px; font-family:helvetica, Arial, sans-serif; text-align: left; color:#000; padding: 0 15px;">

			                      <?= $checkpoint_id_count > 1 ? '<h3>Address '.$i.'</h3>' : '' ?>

			                      <p><?= $checkpoint_id_line_1[$i] ?><br>
			                      <?= $checkpoint_id_line_2[$i] ?><br>
			                      <?= $checkpoint_id_city[$i] ?>, <?= $state_abbr[$checkpoint_id_state_id[$i]] ?> <?= $checkpoint_id_zip_code[$i] ?></p>
			                      <p>Date: <?= $checkpoint_id_date_time_2[$i] ?></p>
			                      <p><?= $checkpoint_id_contact[$i] ? 'Contact: '. $checkpoint_id_contact[$i] .'<br>' : '' ?>
			                      <?= $checkpoint_id_appointment[$i] ? 'Appointment: '. $checkpoint_id_appointment[$i] .'<br>' : '' ?>
			                      <?= $checkpoint_id_notes[$i] ? 'Notes: ' . $checkpoint_id_notes[$i] : '' ?></p>
			                  </td>
				              </tr><?php
				          	}
				          }
				          ?>

							  </textarea>
							  <textarea name="drop_off_info" class="hide">
						      
						      <?php 
				          for ($i = 1; $i <= $checkpoint_id_count; $i++) {

				          	# Display drop offs only
				          	if ($checkpoint_id_data_type[$i] == 1) {
				          		
				              # Display line separator only if we have more than 1 address and if we are on the second address+
				          		if ($checkpoint_id_count > 1 && $i > 1) {  ?>

			                  <tr><!-- line -->
			                    <td width="100%" height="1" bgcolor="#d9d9d9"></td>
			                  </tr><?php
				              } ?>

				              <tr>
			                  <td width="100%" style=" font-size: 14px; line-height: 12px; font-family:helvetica, Arial, sans-serif; text-align: left; color:#000; padding: 0 15px;">

			                      <?= $checkpoint_id_count > 1 ? '<h3>Address '.$i.'</h3>' : '' ?>

			                      <p><?= $checkpoint_id_line_1[$i] ?><br>
			                      <?= $checkpoint_id_line_2[$i] ?><br>
			                      <?= $checkpoint_id_city[$i] ?>, <?= $state_abbr[$checkpoint_id_state_id[$i]] ?> <?= $checkpoint_id_zip_code[$i] ?></p>
			                      <p>Date: <?= $checkpoint_id_date_time_2[$i] ?></p>
			                      <p><?= $checkpoint_id_contact[$i] ? 'Contact: '. $checkpoint_id_contact[$i] .'<br>' : '' ?>
			                      <?= $checkpoint_id_appointment[$i] ? 'Appointment: '. $checkpoint_id_appointment[$i] .'<br>' : '' ?>
			                      <?= $checkpoint_id_notes[$i] ? 'Notes: ' . $checkpoint_id_notes[$i] : '' ?></p>
			                  </td>
				              </tr><?php
				          	}
				          }
				          ?>

							  </textarea>
							  <input type="hidden" name="line_haul" value="<?= $load_line_haul[1] ?>">
							  <input type="hidden" name="miles" value="<?= $load_miles[1] ?>">
							  <input type="hidden" name="weight" value="<?= $load_weight[1] ?>">
							  <input type="hidden" name="deadhead" value="<?= $load_deadhead[1] ?>">
							  <input type="hidden" name="reference" value="<?= $load_reference[1] ?>">
							  <input type="hidden" name="driver_id" value="<?= $entry_driver_id[1] ?>">
							  <input type="hidden" name="broker_id" value="<?= $load_broker_id[1] ?>">
							  <input type="hidden" name="commodity" value="<?= $load_commodity[1] ?>">
							  <input type="hidden" name="notes" value="<?= $load_notes[1] ?>">
							  <input type="hidden" name="_controller_send_load_info" value="1">
							  <input type="hidden" name="token" value="<?= $csrfToken ?>">
							</form>
						</li> <?php
	  			} elseif ($_GET['edit_main']) {

	  				# Edit main data ?>
	  				<li class="list-group-item">

	  					<div class="alert alert-info">
								<i class="fa fa-info-circle fa-fw fa-lg"></i>
								Main data update
							</div>

							<form action="" method="post">

								<div class="row">
									<div class="form-group form-group-select2 col-sm-12 col-md-6">
										<label class="control-label">Driver</label>
										<select name="driver_id" style="width:100%" id="driver_id_select">

											<?php for ($i = 1; $i <= $driver_list_count ; $i++) {

												# Show only active client drivers
				          			if ($driver_list_client_status[$i] == 1) { ?>
													
													<option value="<?= $driver_list_user_id[$i] ?>"<?= $entry_driver_id[1] == $driver_list_user_id[$i] ? ' selected="selected"' : '' ; ?>>

														<?= $user_list_id_name[$driver_list_user_id[$i]] . ' ' . $user_list_id_last_name[$driver_list_user_id[$i]] ?> 

														[<?= $client_ALT_id_company_name[$driver_list_client_id[$i]] ?>]
														
													</option> <?php
				          			}
											} ?>
										</select>
									</div>

									<div class="form-group form-group-select2 col-sm-12 col-md-6">
										<label class="control-label">Broker company</label>
										<select name="broker_id" style="width:100%" id="broker_id_select">

											<?php for ($i = 1; $i <= $broker_count ; $i++) { ?>
												<option value="<?= $broker_data_id[$i] ?>"<?=  $broker_data_id[$i] == $load_broker_id[1] ? ' selected="selected"' : '' ; ?>><?= $broker_company_name[$i] ?></option> <?php
											} ?>
										</select>
									</div>

									<div class="form-group col-sm-12 col-md-6">
										<label class="control-label">Broker's name &amp; number</label>
										<input name="broker_name_number" type="text" class="form-control" value="<?= $load_broker_name_number[1] ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-6">
										<label class="control-label">Broker's email</label>
										<input name="broker_email" type="email" class="form-control" value="<?= strtolower($load_broker_email[1]) ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label">Line haul</label>
										<input name="line_haul" type="number" class="form-control" min="0" step="0.01" value="<?= str_replace(',', '', $load_line_haul[1]) ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Weight</label>
										<input name="weight" type="number" class="form-control" min="1" value="<?= $load_weight[1] ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Miles</label>
										<input name="miles" type="number" class="form-control" min="1" step="0.1" value="<?= str_replace(',', '', $load_miles[1]) ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Deadhead</label>
										<input name="deadhead" type="number" class="form-control" min="0" step="0.1" value="<?= str_replace(',', '', $load_deadhead[1]) ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<!-- <div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Avg. diesel price</label>
										<input name="avg_diesel_price" type="number" class="form-control" min="1" max="9.99" step="0.01" value="<?= $load_avg_diesel_price[1] ?>"<?//= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div> -->
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Load #</label>
										<input name="load_number" type="text" class="form-control" value="<?= $load_load_number[1] ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Reference</label>
										<input name="reference" type="text" class="form-control" value="<?= $load_reference[1] ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Notes</label>
										<input name="notes" type="text" class="form-control" value="<?= $load_notes[1] ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Commodity</label>
										<input name="commodity" type="text" class="form-control" value="<?= $load_commodity[1] ?>"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>
									</div>
									<div class="form-group col-sm-12 col-md-3">
										<label class="control-label text-right">Dispatcher</label>
										<select class="form-control" name="user_id"<?= $load_load_lock[1] == 1 || $load_load_status[1] == 1 ? ' readonly' : '' ?>>

											<?php for ($i = 1; $i <= $user_list_count ; $i++) { 

												# Show dispatcher's list
												# Dispatcher are in user group 1 and 3
												if ($user_list_user_group[$i] == 1 || $user_list_user_group[$i] == 3) { ?>

													<option value="<?= $user_list_id[$i] ?>"<?= $load_user_id[1] == $user_list_id[$i] ? ' selected' : '' ?>><?= $user_list_name[$i] . ' ' . $user_list_last_name[$i] ?></option> <?php
												}
											} ?>
										</select>
									</div>

									<div class="form-group col-sm-12 col-md-12">
										
										<?php 

										if ($load_load_lock[1] == 0 && $load_load_status[1] == 0) {
										
											# Show buttons if load is not locked nor deleted ?>
											<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> Save</button>
											
											<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red"> Cancel</a>

											<?php
										} ?>
									</div>
								</div>

								<?php if ($load_load_lock[1] == 0 && $load_load_status[1] == 0) {
									# Show hidden params if load is not locked nor deleted ?>
									<input type="hidden" name="entry_id" value="<?= $load_entry_id[1] ?>">
									<input type="hidden" name="entry_driver_id" value="<?= $entry_driver_id[1] ?>">
									<input type="hidden" name="_controller_loader" value="update_load">
									<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
								} ?>

							</form>
	  				</li> <?php
	  			}
		  		?>

				  <li class="list-group-item">
				  	<small>
				  		<b>Driver: </b> 

				  		<?php

				  		# Driver name
				  		echo $user_list_id_name[$entry_driver_id[1]] . ' ' . $user_list_id_last_name[$entry_driver_id[1]] . ' - ';
				  		# Client name and mc
				  		echo $client_id_company_name[$load_client_id[1]] . ' <span data-toggle="tooltip" data-placement="top" title="MC#">[' . $client_id_mc_number[$load_client_id[1]] . ']</span>'; ?>

				  	</small>

			  		<?php
			  		# Show send info button if not sending
		  			if (!$_GET['send_info'] && $user->data()->user_group != 4) { ?>

		  				<a data-toggle="tooltip" data-placement="top" title="
									
									<?php
									if ($checkpoint_data_type_0_exists && $checkpoint_data_type_0_exists) {
									  	
									  	echo 'Send load info';
									  } else {

									  	echo 'Checkpoints missing, cannot send info';
									  }
									?>
								" 
								class="btn btn-link pull-right" 
								href="<?= $checkpoint_data_type_0_exists && $checkpoint_data_type_0_exists ? 'load?load_id=' . $_GET['load_id'] . '&send_info=1' : ''?>">
								
								<span class="fa fa-envelope-o<?= $checkpoint_data_type_0_exists && $checkpoint_data_type_0_exists ? '' : ' red' ?>"></span>
							</a> <?php
		  			}
			  		?>

				  </li>
				  <?= $load_commodity[1] ? '<li class="list-group-item"><small><b>Commodity: </b>' . $load_commodity[1] . '</small></li>' : ''; ?>
				  <?= $load_notes[1] ? '<li class="list-group-item"><small><b>Notes: </b>' . $load_notes[1] . '</small></li>' : ''; ?>
				  <?= $load_billing_date[1] != '11/30/-0001' ? '<li class="list-group-item"><small><b>Billing: </b>' . $load_billing_date[1] . '</small></li>' : ''; ?>
				  <?= $load_load_status[1] == 1 ? '<li class="list-group-item list-group-item-danger"><small><b>Status: </b>Deleted</small></li>' : ''; ?>
				  
				  <li class="list-group-item list-group-item-default">
				  	<small>
				  		<b>Dispatcher: </b><?= $user_list_id_name[$load_user_id[1]] . ' ' . $user_list_id_last_name[$load_user_id[1]]; ?> 
				  		[<i style="color: #888;">Added by <?= $load_added_by[1] == $load_user_id[1] ? 'dispatcher' : $user_list_id_name[$load_added_by[1]] . ' ' . $user_list_id_last_name[$load_added_by[1]] ?> on <?= $load_added[1] . ' at ' . $load_added_time[1] ?></i>]
				  	</small>
				  </li>

				  <li class="list-group-item list-group-item-default">
				  	<small>
				  		<b>Broker: </b><?= $load_broker_name_number[1]; ?> 

				  		 <a href="mailto:<?= $load_broker_email[1] ?>"><?= $load_broker_email[1] ?></a>

				  		<a class="btn btn-link red pull-right<?= $user->data()->user_group == 4 ? ' hidden' : '' ?>" href="<?= $_SESSION['href_location'] ?>0/view-load?load_id=<?= $_GET['load_id'] ?>">Old page</a>
				  	</small>
				  </li>

				</ul>
			</div>
		</div>
		
		<div class="panel-group accordion" id="accordionCheckpoint" style="margin-bottom: 5px;">
			
			<div class="panel panel-primary">
				<div class="panel-heading" id="files">
					<h4 class="panel-title">
						<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
							Files
						</a>
					</h4>
				</div>
				<div id="collapse3" class="panel-collapse collapse<?= $processing_file ? ' in' : '' ?>">
					<div class="panel-body">

						<div class="btn-group btn-group-justified" role="group" style="margin-bottom: 20px;">

							  <div class="btn-group" role="group">
							  	<a href="load?load_id=<?= $_GET['load_id'] ?>&rate_confirmation=1" type="button" class="btn btn-<?= $rate_confirmation_exists[1] ? 'primary' : ($_GET['rate_confirmation'] ? 'warning' : 'default') ?>">
							  		Rate confirmation
							  	</a>
							  </div>

							  <div class="btn-group" role="group">
							  	<a href="load?load_id=<?= $_GET['load_id'] ?>&bol=1" type="button" class="btn btn-<?= $bol_exists[1] ? 'primary' : ($_GET['bol'] ? 'warning' : 'default') ?>">
							  		BOL
							  	</a>
							  </div>

							  <div class="btn-group" role="group">
							  	<a href="load?load_id=<?= $_GET['load_id'] ?>&raw_bol=1" type="button" class="btn btn-<?= $raw_bol_exists[1] ? 'primary' : ($_GET['raw_bol'] ? 'warning' : 'default') ?>">
							  		Raw BOL
							  	</a>
							  </div>

							  <div class="btn-group" role="group">
							  	<a href="load?load_id=<?= $_GET['load_id'] ?>&payment_confirmation=1" type="button" class="btn btn-<?= $payment_confirmation_exists[1] ? 'primary' : ($_GET['payment_confirmation'] ? 'warning' : 'default') ?>">
							  		Payment confirmation
							  	</a>
							  </div>

							  <div class="btn-group" role="group">
							  	<a href="load?load_id=<?= $_GET['load_id'] ?>&quickpay_invoice=1" type="button" class="btn btn-<?= $quickpay_invoice_exists[1] ? 'primary' : ($_GET['quickpay_invoice'] ? 'warning' : 'default') ?>">
							  		Quickpay invoice
							  	</a>
							  </div>
						</div>

						<?php

						if ($_GET['rate_confirmation'] && $rate_confirmation_exists[1]) {
						 	
						 	# Show file delete form and display file ?>

						 	<form action="" method="post">
						 		
						 		<div class="form-group text-right col-sm-6 col-md-6">
						 			
						 			<button class="btn btn-link red" data-toggle="tooltip" data-placement="top" title="This cannot be undone!"> 
						 				<i class="fa fa-trash-o"></i> Delete file
						 			</button>	
						 		</div>

						 		<div class="form-group col-sm-6 col-md-6">
						 			<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link"> Close file</a>	
						 		</div>
						 		<input type="hidden" name="_controller_loader_file" value="delete_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="rate-confirmation-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form>

							<embed 
								id="rate-confirmation" 
								src="<?= $_SESSION["href_location"] ?>files/rate-confirmation-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf?r=<?= date('Gis') ?>" 
								width="100%" 
								height="1001px"> <?php
						} elseif ($_GET['rate_confirmation'] && !$rate_confirmation_exists[1]) {

							# Show warning and file upload form ?>

							<div class="alert alert-warning">
								<i class="fa fa-warning fa-fw fa-lg"></i>
								File missing!
							</div>

							<form action="" method="post" enctype="multipart/form-data">
						 		
						 		<div class="form-group text-center">

									<a class="btn btn-link" id="file" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Choose file">
										<span style="font-size: 38px;" class="fa fa-upload"></span>
									</a>
									
								</div>
								
								<div class="form-group text-center">
									
									<button class="btn btn-link" type="submit"> Upload</button>
									<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red">Cancel</a>
								</div>
								
								<input id="file-prompt" type="file" name="file" style="display: none;" accept="application/pdf"/>
						 		<input type="hidden" name="_controller_loader_file" value="add_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="rate-confirmation-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
						 		<input type="hidden" name="file_type" value="2">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form> <?php
						}

						if ($_GET['bol'] && $bol_exists[1]) {
						 	
						 	# Show file delete form and display file ?>

						 	<form action="" method="post">
						 		
						 		<div class="form-group text-right col-sm-6 col-md-6">
						 			
						 			<button class="btn btn-link red" data-toggle="tooltip" data-placement="top" title="This cannot be undone!"> 
						 				<i class="fa fa-trash-o"></i> Delete file
						 			</button>	
						 		</div>

						 		<div class="form-group col-sm-6 col-md-6">
						 			<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link"> Close file</a>	
						 		</div>
						 		<input type="hidden" name="_controller_loader_file" value="delete_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="bol-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form>

							<embed id="bol" src="<?= $_SESSION["href_location"] ?>files/bol-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
						} elseif ($_GET['bol'] && !$bol_exists[1]) {

							# Show warning and file upload form ?>

							<div class="alert alert-warning">
								<i class="fa fa-warning fa-fw fa-lg"></i>
								File missing!
							</div>

							<form action="" method="post" enctype="multipart/form-data">
						 		
						 		<div class="form-group text-center">

									<a class="btn btn-link" id="file" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Choose file">
										<span style="font-size: 38px;" class="fa fa-upload"></span>
									</a>
									
								</div>
								<div class="form-group text-center">
									
									<button class="btn btn-link" type="submit"> Upload</button>
									<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red">Cancel</a>
								</div>
								
								<input id="file-prompt" type="file" name="file" style="display: none;" accept="application/pdf"/>
						 		<input type="hidden" name="_controller_loader_file" value="add_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="bol-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
						 		<input type="hidden" name="file_type" value="1">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form> <?php
						}

						if ($_GET['raw_bol'] && $raw_bol_exists[1]) {
						 	
						 	# Show file delete form and display file ?>

						 	<form action="" method="post">
						 		
						 		<div class="form-group text-right col-sm-6 col-md-6">
						 			
						 			<button class="btn btn-link red" data-toggle="tooltip" data-placement="top" title="This cannot be undone!"> 
						 				<i class="fa fa-trash-o"></i> Delete file
						 			</button>	
						 		</div>

						 		<div class="form-group col-sm-6 col-md-6">
						 			<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link"> Close file</a>	
						 		</div>
						 		<input type="hidden" name="_controller_loader_file" value="delete_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="raw-bol-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form>

							<embed id="raw-bol" src="<?= $_SESSION["href_location"] ?>files/raw-bol-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
						} elseif ($_GET['raw_bol'] && !$raw_bol_exists[1]) {

							# Show warning and file upload form ?>

							<div class="alert alert-warning">
								<i class="fa fa-warning fa-fw fa-lg"></i>
								File missing!
							</div>

							<form action="" method="post" enctype="multipart/form-data">
						 		
						 		<div class="form-group text-center">

									<a class="btn btn-link" id="file" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Choose file">
										<span style="font-size: 38px;" class="fa fa-upload"></span>
									</a>
									
								</div>
								<div class="form-group text-center">
									
									<button class="btn btn-link" type="submit"> Upload</button>
									<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red">Cancel</a>
								</div>
								
								<input id="file-prompt" type="file" name="file" style="display: none;" accept="application/pdf"/>
						 		<input type="hidden" name="_controller_loader_file" value="add_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="raw-bol-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
						 		<input type="hidden" name="file_type" value="4">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form> <?php
						}

						if ($_GET['payment_confirmation'] && $payment_confirmation_exists[1]) {
						 	
						 	# Show file delete form and display file ?>

						 	<form action="" method="post">
						 		
						 		<div class="form-group text-right col-sm-6 col-md-6">
						 			
						 			<button class="btn btn-link red" data-toggle="tooltip" data-placement="top" title="This cannot be undone!"> 
						 				<i class="fa fa-trash-o"></i> Delete file
						 			</button>	
						 		</div>

						 		<div class="form-group col-sm-6 col-md-6">
						 			<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link"> Close file</a>	
						 		</div>
						 		<input type="hidden" name="_controller_loader_file" value="delete_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="payment-confirmation-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form>

							<embed id="payment-confirmation" src="<?= $_SESSION["href_location"] ?>files/payment-confirmation-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
						} elseif ($_GET['payment_confirmation'] && !$payment_confirmation_exists[1]) {

							# Show warning and file upload form ?>

							<div class="alert alert-warning">
								<i class="fa fa-warning fa-fw fa-lg"></i>
								File missing!
							</div>

							<form action="" method="post" enctype="multipart/form-data">
						 		
						 		<div class="row">

						 			<div class="col-sm-12 col-md-6 col-md-offset-3">

							 			<div class="form-group text-center">

											<a class="btn btn-link" id="file" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Choose file">

												<span style="font-size: 38px;" class="fa fa-upload"></span>
											</a>
											
										</div>

										<div class="form-group text-center">

											<label>Correct amount paid?</label>
		                  <select class="form-control" name="payment_confirmation" id="payment_confirmation">
		                    <option value=""></option>
		                    <option value="3">Yes</option>
		                    <option value="2">No</option>
		                  </select>
										</div>

										<div class="form-group hidden has-error" id="payment_confirmation_note_holder">
		                  <label>Notes</label>
		                  <p>
		                    <textarea name="note" class="form-control pull-right red"></textarea>
		                  </p>
		                </div>

										<div class="form-group text-center">
											
											<button class="btn btn-link" type="submit"> Upload</button>
											<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red">Cancel</a>
										</div>
									</div>
						 		</div>
								
								<input id="file-prompt" type="file" name="file" style="display: none;" accept="application/pdf"/>
						 		<input type="hidden" name="_controller_loader_file" value="add_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/">
						 		<input type="hidden" name="file_name" value="payment-confirmation-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
						 		<input type="hidden" name="file_type" value="3">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form> <?php
						}

						if ($_GET['quickpay_invoice'] && $quickpay_invoice_exists[1]) {
						 	
						 	# Show file delete form and display file ?>

						 	<form action="" method="post">
						 		
						 		<div class="form-group text-right col-sm-6 col-md-6">
						 			
						 			<button class="btn btn-link red" data-toggle="tooltip" data-placement="top" title="This cannot be undone!"> 
						 				<i class="fa fa-trash-o"></i> Delete file
						 			</button>	
						 		</div>

						 		<div class="form-group col-sm-6 col-md-6">
						 			<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link"> Close file</a>	
						 		</div>
						 		<input type="hidden" name="_controller_loader_file" value="delete_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/quickpay-invoices/">
						 		<input type="hidden" name="file_name" value="invoice-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form>

							<embed id="invoice" src="<?= $_SESSION["href_location"] ?>files/quickpay-invoices/invoice-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1001px"> <?php
						} elseif ($_GET['quickpay_invoice'] && !$quickpay_invoice_exists[1]) {

							# Show warning and file upload form ?>

							<div class="alert alert-warning">
								<i class="fa fa-warning fa-fw fa-lg"></i>
								File missing!
							</div>

							<form action="" method="post" enctype="multipart/form-data">
						 		
						 		<div class="form-group text-center">

									<a class="btn btn-link" id="file" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Choose file">
										<span style="font-size: 38px;" class="fa fa-upload"></span>
									</a>
									
								</div>
								<div class="form-group text-center">
									
									<button class="btn btn-link" type="submit"> Upload</button>
									<a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red">Cancel</a>
								</div>
								
								<input id="file-prompt" type="file" name="file" style="display: none;" accept="application/pdf"/>
						 		<input type="hidden" name="_controller_loader_file" value="add_file">
						 		<input type="hidden" name="path" value="/home/logistic/public_html/files/quickpay-invoices/">
						 		<input type="hidden" name="file_name" value="invoice-<?= $load_entry_id[1] . '-' . $load_load_id[1] ?>.pdf">
						 		<input type="hidden" name="file_type" value="4">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form> <?php
						}

						?>
						
					</div>
				</div>
			</div>

			<?php
			# Other charges are only displayed if there is data to show or if adding the first one
			if ($other_charges_count || $_GET['edit_other_charges']) { ?>
				
				<div class="panel panel-primary">
					<div class="panel-heading" id="other-charges">
						<h4 class="panel-title">
							<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
								Other charges

								<?= $other_charges_price_total ? '($' . $other_charges_price_total . ')' : ''; ?>
							</a>
						</h4>
					</div>
					<div id="collapse4" class="panel-collapse collapse<?= $_GET['edit_other_charges'] ? ' in' : '' ?>">
						<div class="panel-body">						

							<?php
							# No other charges alert
							if (!$other_charges_count) { ?>
								
								<div class="alert alert-info">
									<i class="fa fa-info-circle fa-fw fa-lg"></i>
									This load doesn't have other charges.
								</div> <?php
							}
							?>

							<form action="" method="post" class="form-inline">
								
								<div class="form-group">

									<select name="other_charge_id" class="form-control">
										<option value="">Add other charge</option>

										<?php 
										for ($i = 1; $i <= $other_charges_list_count ; $i++) { ?>
											
											<option value="<?= $charge_data_id[$i] ?>"><?= $charge_name[$i] ?></option> <?php
										} ?>
									</select>
								</div>
								
								<div class="form-group">

									<input name="price" class="form-control" type="number" step="0.01" placeholder="Cost ($)">
								</div>

								<div class="form-group">

									<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> Save</button>
								</div>
								<input type="hidden" name="_controller_loader" value="add_other_charge">
		  					<input type="hidden" name="token" value="<?= $csrfToken ?>">
							</form>
							<hr>

							<?php
							# Show other charges
							if ($other_charges_count) { ?>

								<div class="col-sm-12 col-md-6">
									
									<table class="table table-bordered" cellspacing="0" width="100%">
										<thead>
											<tr>
												<th><span>Charge</span></th>
												<th><span>Cost</span></th>
												<th></th>
											</tr>
										</thead>
										<tbody>
									
											<?php 
											for ($i = 1; $i <= $other_charges_count ; $i++) {  ?>
											
												<tr>
													<td><?= $charge_id_name[$other_charges_id[$i]] ?></td>
													<td class="text-right">$<?= $other_charges_price[$i] ?></td>
													<td style="width: 45px;">

														<form action="" method="post">
															
															<button type="submit" class="btn btn-link red" data-toggle="tooltip" data-placement="top" title="Delete this charge">
																<i class="fa fa-trash-o"></i>
															</button>
															<input type="hidden" name="_controller_loader" value="delete_other_charge">
															<input type="hidden" name="other_charge_id" value="<?= $other_charges_data_id[$i] ?>">
		  												<input type="hidden" name="token" value="<?= $csrfToken ?>">
														</form>
													</td>
												</tr> <?php
											} ?>

											<tr>
												<td class="text-right"><b>Total</b></td>
												<td class="text-right"><b>$<?= number_format($other_charges_price_total, 2) ?></b></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
								<?php
							}
							?>
							
						</div>
					</div>
				</div> <?php
			}
			?>
		</div>

		<div class="panel-group accordion<?= $user->data()->user_group == 4 ? ' hidden' : '' ?>" id="accordionNotes">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordionNotes" href="#collapse5">
							Notes
						</a>
					</h4>
				</div>
				<div id="collapse5" class="panel-collapse collapse in">
					<div class="panel-body">
						
						<?php
						for ($i= 1; $i <= $load_note_count ; $i++) { 
							# Display notes ?>
							<p style="line-height:7px;<?= $load_note_important[$i] == 1 && $load_note_type[$i] == 0 ? ' color: #e84e40 !important;' : ($load_note_important[$i] == 0 && $load_note_type[$i] == 1 ? ' color: #8bc34a !important;' : '') ?>">
								<small>
									<b><?= $user_list_id_name[$load_note_user_id[$i]] . ' ' . $user_list_id_last_name[$load_note_user_id[$i]] ?> <?= $load_note_type[$i] == 1 ? '<small><i>[automated]</i></small>' : '' ?></b>
									<b class="pull-right"><?= $load_note_added[$i] . ' at ' . $load_note_added_time[$i] ?></b>
								</small>
							</p>
							<p>
								<small>
									<i>
										<?= $load_note_note[$i] ?>
									</i>
								</small>
							</p><hr> <?php
						}
						?>

						<form action="" method="post">
							<div class="form-group">
								<textarea name="note" class="form-control" rows="2" placeholder="Add new note"></textarea>
							</div>
							<div class="form-group">
								<div class="checkbox-nice checkbox-inline">
									<input type="checkbox" id="important_note" name="important_note">
									<label for="important_note">
										Mark as important
									</label>
								</div>
							</div>
							<div class="clearfix">
								<button type="submit" class="btn btn-primary">Add note</button>
							</div>
							<input type="hidden" name="_controller_loader" value="add_staff_note">
		  				<input type="hidden" name="token" value="<?= $csrfToken ?>">
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
			