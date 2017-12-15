<?php 
session_start();
ob_start();
?>
<div class="row">

	<div class="col-md-12 col-sm-12">

		<div class="main-box clearfix" style="border: 1px solid #e84e40;">
			
			<header class="main-box-header clearfix">
				<h2 style="color: #e84e40;" class="pull-left">Client Card - <?= $client_card_company_name ?></h2>
				
				<div class="icon-box pull-right">

					<a class="btn btn-link red pull-left" href="<?= str_replace(['index.php', '.php'], ['', ''], $_SERVER['PHP_SELF']) ?><?= $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '' ?>"> 
						
						Close client
					</a>

					<a style="margin: 0 10px;" class="btn btn-link pull-left" href="<?= $_SESSION['href_location'] ?>dashboard/client/client?client_id=<?= $_POST['client_card'] ?>"> 
						
						<i class="fa fa-pencil"></i> Edit client
					</a>

					<form action="" method="post" class="pull-left">
          
          <div class="form-group">
            
            <select name="client_card" class="form-control" onchange="this.form.submit();">
              <option>Quick switch</option>
              
              <?php
              for ($i = 1; $i <= $nav_client_count ; $i++) { ?>
                
                <option value="<?= $nav_client_data_id[$i] ?>"><?= $nav_client_company_name[$i] ?></option> <?php
              }
              ?>
            </select>

          </div>
        </form>

				</div>
			</header>

			<div class="main-box-body clearfix">
				
				<ul class="nav nav-pills nav-justified" role="tablist">
				  <li role="presentation"<?= $_POST['client_card_user_id'] ? '' : ' class="active"' ?>><a href="#main_data" aria-controls="main_data" role="tab" data-toggle="tab">Main</a></li>
				  
				  <li role="presentation"<?= $_POST['client_card_user_id'] ? ' class="active"' : '' ?> style="max-height: 40px;">
				  	
				  	<a href="#user_data" aria-controls="user_data" role="tab" data-toggle="tab">

							<?php
							if ($_POST['client_card_user_id']) { ?>
								
								<form action="" method="post">
			    				
			    				<div class="form-group" style="margin: 0;">
			    					
			    					<select class="form-control" name="client_card_user_id" onchange="this.form.submit();">
			    						<option>Select user</option>

			    						<?php
			    						# Loop through users
			    						for ($i = 1; $i <= $cc_client_user_count ; $i++) { ?>
			    							
			    							<option value="<?= $cc_client_user_user_id[$i] ?>"<?= $cc_client_user_user_id[$i] == $_POST['client_card_user_id'] ? ' selected' : '' ?>><?= $user_list_id_name[$cc_client_user_user_id[$i]] . ' ' . $user_list_id_last_name[$cc_client_user_user_id[$i]] ?></option> <?php
			    						}
			    						?>

			    					</select>
			    				</div>

			    				<input type="hidden" name="client_card" value="<?= $_POST['client_card'] ?>">
			    			</form> <?php
							} else {

								echo 'Users';
							} ?>
				  	</a>
				  </li>

				  <li role="presentation"><a href="#insurance_data" aria-controls="insurance_data" role="tab" data-toggle="tab">Insurance</a></li>

				  <li role="presentation">
			  		
			  		<a href="#factoring_company_data" aria-controls="factoring_company_data" role="tab" data-toggle="tab">Factoring Companies</a>
			  	</li>
				  
				</ul>

				<div class="tab-content tab-content-custom-1">
			    <div role="tabpanel" class="tab-pane fade<?= $_POST['client_card_user_id'] ? '' : ' in active' ?>" id="main_data">

			    	<div class="row">
			    		<div class="col-sm-12 col-md-3" style="border-right: 1px solid #eee; min-height: 135px; max-height: 135px;">
			    			
			    			<h5 class="text-center">Numbers</h5>
			    			<p style="margin: 0;"><small><b>MC:</b> <?= $client_card_mc_number ?></small></p>
			    			<p style="margin: 0;"><small><b>US DOT:</b> <?= $client_card_us_dot_number ?></small></p>
			    			<p style="margin: 0;"><small><b>EIN:</b> <?= $client_card_ein_number ?></small></p>
			    			<?= $client_card_scac_code ? '<p style="margin: 0;"><small><b>SCAC: </b>' . $client_card_scac_code . '</small></p>' : '' ?>
			    			<?= $client_card_chr_t ? '<p style="margin: 0;"><small><b>CHR </b> T' . $client_card_chr_t . '</small></p>' : '' ?>
			    		</div>

			    		<div class="col-sm-12 col-md-3" style="border-right: 1px solid #eee; min-height: 135px; max-height: 135px;">
			    			
			    			<h5 class="text-center">Contact info</h5>
			    			<p style="margin: 0;"><small><b>Main contact: </b> <?= $client_card_main_contact ?></small></p>
			    			<?= $client_card_phone_number_01 ? '<p style="margin: 0;"><small><b>Phone number 1</b>: ' . $client_card_phone_number_01 . '</small></p>' : '' ?>
								<?= $client_card_phone_number_02 ? '<p style="margin: 0;"><small><b>Phone number 2</b>: ' . $client_card_phone_number_02 . '</small></p>' : '' ?>
								<?= $client_card_phone_number_03 ? '<p style="margin: 0;"><small><b>Phone number 3 (per safer)</b>: ' . $client_card_phone_number_03 . '</small></p>' : '' ?>
			    		</div>

			    		<div class="col-sm-12 col-md-3" style="border-right: 1px solid #eee; min-height: 135px; max-height: 135px; overflow-y: auto;">

				    		<h5 class="text-center">Address</h5>

				    		<?php
				    		# Loop through addresses
				    		for ($i = 1; $i <= $client_card_address_count ; $i++) {

				    			# hr after first label
				    			echo $i == 2 ? '<hr>' : '';

				    			# Type label
				    			echo '<small><b>' . ($client_card_address_type[$i] == 1 ? 'Physical' : 'Mailing') . '</b></small>' ?>
				    			
				    			<p style="margin: 0;"><small><?= $client_card_address_line_1[$i] ?></small></p>
				    			<p style="margin: 0;"><small><?= $client_card_address_line_2[$i] ?></small></p>
				    			<?= $client_card_address_line_3[$i] ? '<p style="margin: 0;"><small>' . $client_card_address_line_3[$i] . '</small></p>' : '' ?>
				    			<p style="margin: 0;"><small><?= $client_card_address_city[$i] . ' ' . $state_abbr[$client_card_address_state_id[$i]] . ', ' . $client_card_address_zip_code[$i] ?></small></p> <?php
				    		}
				    		?>

			    		</div>

			    		<div class="col-sm-12 col-md-3" style="min-height: 135px; max-height: 135px;">
			    			
			    			<h5 class="text-center">Other data</h5>

								<?= $client_card_invoice_color ? '<p style="margin: 0;"><small><b>Invoice color: </b> <span style="color: #' . $client_card_invoice_color . '">' . $client_card_invoice_color . '</span></small></p>' : '' ?>

								<p style="margin: 0;"><small><b>Status: </b> <?= $client_card_status == 1 ? '<span class="green">Active<span>' : '<span class="red">Inactive<span>' ?></small></p>

								<?= $client_card_formation_date != 'Nov 30, -0001' ? '<p style="margin: 0;"><small><b>Formation date: </b> ' . $client_card_formation_date . '</small></p>' : '' ?>
								<?= $client_card_added ? '<p style="margin: 0;"><small><b>Added: </b> ' . $client_card_added . '</small></p>' : '' ?>
			    		</div>
			    	</div>

			    </div>
			    
			    <div role="tabpanel" class="tab-pane fade<?= $_POST['client_card_user_id'] ? ' in active' : '' ?>" id="user_data">

				    <?php
				    # User prompt
				    if (!$_POST['client_card_user_id']) { ?>
				    	
				    	<div class="row">

				    		<div class="col-sm-12 col-md-6 col-md-offset-3" style="padding-top: 50px;">

				    			<form action="" method="post">
				    				
				    				<div class="form-group">
				    					
				    					<select class="form-control" name="client_card_user_id" onchange="this.form.submit();">
				    						<option>Select user</option>

				    						<?php
				    						# Loop through users
				    						for ($i = 1; $i <= $cc_client_user_count ; $i++) { ?>
				    							
				    							<option value="<?= $cc_client_user_user_id[$i] ?>"><?= $user_list_id_name[$cc_client_user_user_id[$i]] . ' ' . $user_list_id_last_name[$cc_client_user_user_id[$i]] ?></option> <?php
				    						}
				    						?>

				    					</select>
				    				</div>

				    				<input type="hidden" name="client_card" value="<?= $_POST['client_card'] ?>">
				    			</form>

				    		</div>

				    	</div> <?php
				    } else {

				    	# Display user data ?>

				    	<div class="row">

				    		<div class="col-sm-12 col-md-4">

				    			<b>Email: </b>

				    			<a href="mailto: <?= $user_list_id_email[$_POST['client_card_user_id']] ?>"> <?= $user_list_id_email[$_POST['client_card_user_id']] ?></a>
				    		</div>

				    		<div class="col-sm-12 col-md-4 text-center">

				    			<b>Phone number<?= $user_phone_number_count > 1 ? 's' : '' ?>: </b>

				    			<?php
				    			# Loop through phone numbers
				    			for ($i = 1; $i <= $user_phone_number_count ; $i++) { 

				    				echo $user_phone_number_phone_number[$i];

				    				# Comma separated as long as we have more than 1 and we're not in the last item
				    				echo $user_phone_number_count > 1 && $i != $user_phone_number_count ? ', ' : '';
				    			}
				    			?>

				    		</div>

				    		<div class="col-sm-12 col-md-4 text-right">

				    			<b>DOB <small>mm/dd/yyyy</small>: </b>

				    			<?= $user_list_id_dob[$_POST['client_card_user_id']] != '11/30/-0001' ? $user_list_id_dob[$_POST['client_card_user_id']] : '<i class="red">N/A</i>' ?>
				    		</div>

				    		<div class="col-sm-12 col-md-12">
				    			<hr>
				    		</div>

								<div class="col-sm-12 col-md-4">

									<h5>Tractor</h5>

									<div class="row">

										<?php
						    		if ($client_user_tractor_count) { ?>
						    			
						    			<div class="col-sm-12 col-md-5">

												<p><small><b>Number:</b> <?= $client_user_tractor_number[1] ?></small></p>
												<p><small><b>Color:</b> <?= $client_user_tractor_color[1] ?></small></p>
												<p><small><b>Sleeper:</b> <?= $client_user_tractor_sleeper[1] ? '<i class="green">Yes</i>' : '<i class="red">No</i>' ?></small></p>
												<p><small><b>Headrack:</b> <?= $client_user_tractor_headrack[1] == 1 ? '<i class="green">Yes</i>' : '<i class="red">No</i>' ?></small></p>
												<p><small><b>Year:</b> <?= $client_user_tractor_year[1] ?></small></p>
												<?= $client_user_tractor_weight[1] ? '<p><small><b>Weight:</b> ' . $client_user_tractor_weight[1] . '</small></p>' : '' ?>
							    		</div>
							    		<div class="col-sm-12 col-md-7" style="border-right: 1px solid #eee;">

												<p><small><b>Make:</b> <?= $client_user_tractor_make[1] ?></small></p>
												<p><small><b>Model:</b> <?= $client_user_tractor_model[1] ?></small></p>
												<p><small><b>Vin:</b> <?= $client_user_tractor_vin[1] ?></small></p>
												<p><small><b>Name on the side:</b> <?= $client_user_tractor_name_on_the_side[1] ?></small></p>
												<p><small><b>License plate:</b> <?= $client_user_tractor_license_plate[1] ?></small></p>
												<small style="color: #888; font-style: italic;">
													Added by <?= $user_list_id_name[$client_user_tractor_user_id[1]] . ' ' . $user_list_id_last_name[$client_user_tractor_user_id[1]] ?> on <?= $client_user_tractor_added[1] ?>
												</small>
							    		</div> <?php
						    		} else {

						    			echo '<div class="col-sm-12 col-md-12">';
							    			include COMPONENT_PATH . 'alert_simple_warning.txt';
													echo 'There is no tractor for this user!';
												echo '</div>';
											echo '</div>';
						    		}
						    		?>

									</div>
								</div>

								<div class="col-sm-12 col-md-4">

									<h5>Trailer</h5>

						    	<div class="row">

						    		<?php
						    		if ($client_tractor_trailer_count) { ?>
						    			
						    			<div class="col-sm-12 col-md-5">

												<p><small><b>Trailer type: </b> <?= $client_trailer_type_id_name[$client_tractor_trailer_trailer_type] ?></small></p>
												<p><small><b>Year: </b> <?= $client_tractor_trailer_year ?></small></p>
												<p><small><b>Make: </b> <?= $client_tractor_trailer_make ?></small></p>

							    			<?php

							    			echo $client_tractor_trailer_length ? '<p><small><b>Length:</b> ' . $client_tractor_trailer_length . ' feet</small></p>' : '';
												echo $client_tractor_trailer_height > 0 ? '<p><small><b>Height:</b> ' . $client_tractor_trailer_height . ' feet</small></p>' : '';
												echo $client_tractor_trailer_width > 0 ? '<p><small><b>Width:</b> ' . $client_tractor_trailer_width . ' inches</small></p>' : '';
												echo $client_tractor_trailer_license_plate ? '<p><small><b>License plate:</b> ' . $client_tractor_trailer_license_plate . '</small></p>' : '';
												echo $client_tractor_trailer_gross_weight > 0 ? '<p><small><b>Gross weight:</b> ' . $client_tractor_trailer_gross_weight . '</small></p>' : '';
												echo $client_tractor_trailer_vin ? '<p><small><b>Vin:</b> ' . $client_tractor_trailer_vin . '</small></p>' : '';
												echo $client_tractor_trailer_deck_material ? '<p><small><b>Deck material:</b> ' . $client_trailer_deck_material_id_name[$client_tractor_trailer_deck_material] . '</small></p>' : '';

												?>

							    		</div>

							    		<div class="col-sm-12 col-md-5">

							    			<p><small><b>Trailer number: </b> <?= $client_tractor_trailer_trailer_number ?></small></p>
												<p><small><b>Headrack: </b> <?= $client_tractor_trailer_headrack == 1 ? '<i class="green">Yes</i>' : '<i class="red">No</i>' ?></small></p>

								    		<?php
								    		echo $client_tractor_trailer_model ? '<p><small><b>model:</b> ' . $client_tractor_trailer_model . '</small></p>' : '';
												echo $client_tractor_trailer_door_type ? '<p><small><b>door_type:</b> ' . $client_trailer_door_type_id_name[$client_tractor_trailer_door_type] . '</small></p>' : '';
												echo $client_tractor_trailer_roof_type ? '<p><small><b>roof_type:</b> ' . $client_trailer_id_roof_type_name[$client_tractor_trailer_roof_type] . '</small></p>' : '';
												echo $client_tractor_trailer_bottom_deck > 0 ? '<p><small><b>bottom_deck:</b> ' . $client_tractor_trailer_bottom_deck . '</small></p>' : '';
												echo $client_tractor_trailer_upper_deck > 0 ? '<p><small><b>upper_deck:</b> ' . $client_tractor_trailer_upper_deck . '</small></p>' : '';
												?>

												<p><small><b>Air ride:</b> <?= $client_tractor_trailer_air_ride == 1 ? '<i class="green">Yes</i>' : '<i class="red">No</i>'; ?> </small></p>
												<p><small><b>Goose neck:</b> <?= $client_tractor_trailer_goose_neck == 1 ? '<i class="green">Yes</i>' : '<i class="red">No</i>'; ?> </small></p>

												<small style="color: #888; font-style: italic;">
													Added by <?= $user_list_id_name[$client_tractor_trailer_user_id] . ' ' . $user_list_id_last_name[$client_tractor_trailer_user_id] ?> on <?= $client_tractor_trailer_added ?>
												</small>
							    		</div> <?php
						    		} else {

						    			echo '<div class="col-sm-12 col-md-12">';
							    			include COMPONENT_PATH . 'alert_simple_warning.txt';
													echo 'There is no trailer for this user!';
												echo '</div>';
											echo '</div>';
						    		}
						    		?>

						    	</div>
						    </div>

						    <div class="col-sm-12 col-md-2">

									<h5>Equipment</h5>

						    	<div class="row">

						    		<div class="col-sm-12 col-md-12"<?= $client_user_equipment_assoc_count ? ' style="min-height: 150px; max-height: 150px; overflow-y: auto;"' : '' ?>>

											<?php
											if ($client_user_equipment_assoc_count) {
												
												# Loop through equipment association
							  		 		for ($i = 1; $i <= $client_user_equipment_assoc_count ; $i++) { ?>

							  		 			<p>
							  		 				<small>

							  		 					<span class="label label-primary" style="margin-right: 5px;"> 

							  		 						<?= $client_user_equipment_assoc_quantity[$i] ?>
							  		 					</span>

							  		 					<?= $client_user_equipment_id_name[$client_user_equipment_assoc_equipment_id[$i]] ?>
							  		 				</small>
							  		 			</p> <?php
							  		 		}
											} else {
							  		 		
						  		 			include COMPONENT_PATH . 'alert_simple_warning.txt';
													echo 'There is no equipment for this user!';
												echo '</div>';
											} ?>
						    		</div>
						    	</div>
						    </div>

						    <div class="col-sm-12 col-md-2">

									<h5>Features</h5>

						    	<div class="row">

						    		<div class="col-sm-12 col-md-12"<?= $client_user_feature_assoc_count ? ' style="min-height: 150px; max-height: 150px; overflow-y: auto;"' : '' ?>>

											<?php
											if ($client_user_feature_assoc_count) {
												
												# Loop through feature association
							  		 		for ($i = 1; $i <= $client_user_feature_assoc_count ; $i++) { ?>

							  		 			<p>
							  		 				<small>

							  		 					<span class="fa fa-check green"></span>

							  		 					<?= $client_user_feature_id_name[$client_user_feature_assoc_feature_id[$i]] ?>
							  		 				</small>
							  		 			</p> <?php
							  		 		}
											} else {
							  		 		
						  		 			include COMPONENT_PATH . 'alert_simple_warning.txt';
													echo 'There are no feature for this user!';
												echo '</div>';
											} ?>
						    		</div>
						    	</div>
						    </div>

						    <div class="col-sm-12 col-md-12 text-right">

						    	<form action="" method="post">

						    		<button type="submit" class="btn btn-link red">Close user</button>
						    		<input type="hidden" name="client_card" value="<?= $_POST['client_card'] ?>">
						    	</form>
						    </div>
				    	</div> <?php
				    }
				    ?>

			    </div>

			    <div role="tabpanel" class="tab-pane fade" id="insurance_data">

				    <div class="row">
				    	
				    	<div class="col-sm-12 col-md-4" style="border-right: 1px solid #eee;">

				    		<h5>Producer</h5>

				    		<?php
								echo $client_insurance_producer . '<br>';
								echo '<b>Phone number: </b>' . $client_insurance_producer_phone_number . '<br>';
								echo '<b>Fax number: </b>' . $client_insurance_producer_fax_number . '<br>';
								echo '<b>Email: </b>' . $client_insurance_producer_email . '<br>';
					    	?>
				    	</div>

				    	<div class="col-sm-12 col-md-4" style="border-right: 1px solid #eee;">

				    		<h5>Insurance Company</h5>
				    		<p><?= $client_insurance_company_id_name[$client_insurance_insurance_company_id]; ?></p>
				    		<p><b>Website: </b> <?= $client_insurance_website ? $client_insurance_website : '<i class="red">N/A</i>' ?></p>
				    		<p><b>Username: </b> <?= $client_insurance_website_username ? $client_insurance_website_username : '<i class="red">N/A</i>' ?></p>
				    		<p><b>Password: </b> <?= $client_insurance_website_password ? $client_insurance_website_password : '<i class="red">N/A</i>' ?></p>
				    	</div>

				    	<div class="col-sm-12 col-md-4" style="border-right: 1px solid #eee;">

				    		<h5>Other</h5>
				    		
				    		<p><b>Vin: </b> <?= $client_insurance_vin_number ? $client_insurance_vin_number : '<i class="red">N/A</i>' ?></p>
				    	</div>
				    </div>
				    	
			    </div>

			    <div role="tabpanel" class="tab-pane fade" id="factoring_company_data">

				    <div class="row">
				    	
				    	<div class="col-sm-12 col-md-4">

					    	<h5>Main</h5>

					    	<ul class="list-group">

					    		<li href="#" class="list-group-item">
					    			
					    			<i class="fa fa-bank"></i> 
					    			
					    			<small>
					    				<i>
					    					<a target="_blank" href="#"><?= $factoring_company_name_alt[1] ?></a>
					    				</i>
					    			</small>
					    		</li>

						  		<li href="#" class="list-group-item">
						  			
						  			<i class="fa fa-globe"></i> 
						  			
						  			<small>
						  				<i>
						  					<a target="_blank" href="<?= $factoring_company_uri[1] ?>"><?= $factoring_company_uri[1] ?></a>
						  				</i>
						  			</small>
						  		</li>

									<li href="#" class="list-group-item">
										
										<i class="fa fa-at"></i> 
										
										<small>
											<i>
												<a href="mailto:<?= $factoring_company_invoicing_email[1] ?>"><?= $factoring_company_invoicing_email[1] ?></a> 
											</i>
										</small>
									</li>

									<li href="#" class="list-group-item">
										
										<i class="fa fa-phone"></i> 
										
										<small>
											<i>
												<?= $factoring_company_phone_number_01[1] ?>
											</i>
										</small>
									</li>

									<li href="#" class="list-group-item">
										
										<i class="fa fa-fax"></i> 
										
										<small>
											<i>
												<?= $factoring_company_fax[1] ?>
											</i>
										</small>
									</li>

									<li href="#" class="list-group-item">
										
										<small>
											<i>
												
												<?php
												if ($factoring_company_open_hour[1] != '00:00:00' && $factoring_company_close_hour[1] != '00:00:00') { ?>
													
													Opens from <?php
													echo date('G:i', strtotime($factoring_company_open_hour[1]));
													echo ' to ';
													echo date('G:i', strtotime($factoring_company_close_hour[1]));
													echo ' (' . $factoring_company_time_zone[1] . ')';
												} else { ?>

													<span class="red">
														<i class="fa fa-warning"></i> Operation hours missing!
													</span> <?php
												}
												?>
											</i>
										</small>
									</li>
								</ul>
							</div>

							<div class="col-sm-12 col-md-4" style="border-right: 1px solid #eee; min-height: 290px; max-height: 290px; overflow-y: auto;">

					    	<h5>Address</h5>

					    	<?php
				    		# Loop through addresses
				    		for ($i = 1; $i <= $factoring_company_address_count ; $i++) {

				    			# hr after first label
				    			echo $i == 2 ? '<hr>' : '';

				    			# Type label
				    			echo '<small><b>' . ($factoring_company_address_type[$i] == 1 ? 'Physical' : 'Mailing') . '</b></small>' ?>
				    			
				    			<p style="margin: 0;"><small><?= $factoring_company_address_line_1[$i] ?></small></p>
				    			<p style="margin: 0;"><small><?= $factoring_company_address_line_2[$i] ?></small></p>
				    			<?= $factoring_company_address_line_3[$i] ? '<p style="margin: 0;"><small>' . $factoring_company_address_line_3[$i] . '</small></p>' : '' ?>
				    			<p style="margin: 0;"><small><?= $factoring_company_address_city[$i] . ' ' . $state_abbr[$factoring_company_address_state_id[$i]] . ', ' . $factoring_company_address_zip_code[$i] ?></small></p> <?php
				    		}
				    		?>

				    		<h5>Contact</h5>

					    	<?php
				    		# Loop through contacts
				    		for ($i = 1; $i <= $factoring_company_contact_count ; $i++) {

				    			# hr after first item
				    			echo $i == 2 ? '<hr>' : ''; ?>
				    			
				    			<p style="margin: 0;"><small><b><?= $factoring_company_contact_name[$i] . ' ' . $factoring_company_contact_last_name[$i] ?></b></small></p>
				    			<?= $factoring_company_contact_title[$i] ? '<p style="margin: 0;"><small>' . $factoring_company_contact_title[$i] . '</small></p>' : '' ?>
				    			<p style="margin: 0;"><small> <a href="mailto:<?= $factoring_company_contact_email[$i] ?>"><?= $factoring_company_contact_email[$i] ?></a></small></p>
				    			<p style="margin: 0;"><small><?= $factoring_company_contact_phone_number_01[$i] ?></small></p> <?php
				    		}
				    		?>
							</div>
				    </div>
			    </div>
			  </div>

			</div>
		</div>

	</div>

</div>
