<?php
if ($user->data()->user_group == 4) {
	
	# External user footer ?>

	<footer id="footer-bar" class="row" style="opacity: 1;">
		
		<p id="footer-copyright" class="col-xs-12">
			
			Powered by quantuMMonkey!
		</p>
	</footer> <?php
} else {

	# Internal user footer ?>
	<footer id="quantum-footer" class="row">

		<div class="col-sm-12 col-md-2">
			
			<form action="" method="post">
	          
	      <select name="client_card" class="form-control sm-select" style="margin-top: 6px;" onchange="this.form.submit();">
	        <option>Client Card</option>
	        
	        <?php
	        for ($i = 1; $i <= $nav_client_count ; $i++) { ?>
	          
	          <option value="<?= $nav_client_data_id[$i] ?>"<?= $_POST['client_card'] == $nav_client_data_id[$i] ? ' selected' : '' ?>>
	          	
	          	<?= $nav_client_company_name[$i] ?>
	          </option> <?php
	        }
	        ?>
	      </select>
	    </form>
		</div>

		<div class="col-sm-12 col-md-6<?= $_POST['client_card'] ? '' : 'hidden' ?>">
			
			<sub>MC</sub>
			<?= $client_card_mc_number ?>

			<sub>US DOT</sub>
			<?= $client_card_us_dot_number ?>

			<sub>EIN</sub>
			<?= $client_card_ein_number ?>

			<?= $client_card_scac_code ? '<sub>SCAC</sub> ' . $client_card_scac_code : '' ?>
			<?= $client_card_chr_t ? '<sub>CHR</sub> T' . $client_card_chr_t : '' ?>

			<?= $client_card_main_contact ? ' | ' . $client_card_main_contact : '' ?>
			<?= $client_card_phone_number_01 ? ' | <i class="fa fa-phone"></i> ' . $client_card_phone_number_01 : '' ?>
			<?= $client_card_phone_number_02 ? ' | <i class="fa fa-phone"></i> ' . $client_card_phone_number_02 : '' ?>
			<?= $client_card_phone_number_03 ? ' | <i class="fa fa-phone"></i> ' . $client_card_phone_number_03 : '' ?>

			 | 

			<span data-toggle="tooltip" data-placement="right" title="Address" id="address-marker">
				
				<i 
					class="fa fa-map-marker" 
				 	data-toggle="popover" 
				 	title="Address" 
				 	data-html="true" 
				 	data-placement="top" 
				 	data-content='
					 	<?php
		    		# Loop through addresses
		    		for ($i = 1; $i <= $client_card_address_count ; $i++) {

		    			# hr after first label
		    			echo $i > 1 ? '<hr>' : '';

		    			# Type label
		    			echo '<small><b>' . ($client_card_address_type[$i] == 1 ? 'Physical' : 'Mailing') . '</b></small>' ?>
		    			
		    			<p style="margin: 0;"><small><?= $client_card_address_line_1[$i] ?></small></p>
		    			<p style="margin: 0;"><small><?= $client_card_address_line_2[$i] ?></small></p>
		    			<?= $client_card_address_line_3[$i] ? '<p style="margin: 0;"><small>' . $client_card_address_line_3[$i] . '</small></p>' : '' ?>
		    			<p style="margin: 0;"><small><?= $client_card_address_city[$i] . ' ' . $state_abbr[$client_card_address_state_id[$i]] . ', ' . $client_card_address_zip_code[$i] ?></small></p> <?php
		    		}
		    		?>
				 	'>
				 	
				</i>
			</span>
		</div>

		<div class="col-sm-12 col-md-2 text-right<?= $_POST['client_card'] ? '' : 'hidden' ?>">

			<?php
			if ($_POST['client_card_user_id']) {
				
				# Show user data ?>

				<a href="mailto: <?= $user_list_id_email[$_POST['client_card_user_id']] ?>"> <?= $user_list_id_email[$_POST['client_card_user_id']] ?></a>

				<?php
				# Loop through phone numbers
				for ($i = 1; $i <= $user_phone_number_count ; $i++) { 

					echo ' | <i class="fa fa-phone"></i> ' . $user_phone_number_phone_number[$i];

					# Comma separated as long as we have more than 1 and we're not in the last item
					echo $user_phone_number_count > 1 && $i != $user_phone_number_count ? ', ' : '';
				}

				# DOB
				if ($user_list_id_dob[$_POST['client_card_user_id']] != '11/30/-0001') { ?>

					 | 

					<span data-toggle="tooltip" data-placement="top" title="DOB (mm/dd/yyyy)">

						<?= $user_list_id_dob[$_POST['client_card_user_id']] ?>
					</span> <?php
				} ?>

				<form action="" method="post" class="pull-right">
					
					<button type="submit" class="btn-link red" data-toggle="tooltip" data-toggle="top" title="Close user">

						<i class="fa fa-close"></i>
					</button>

					<input type="hidden" name="client_card" value="<?= $_POST['client_card'] ?>">
				</form> <?php

			} else {

				# Show Insurance and Factoring co. links ?>

				<a
					href="#" 
				 	data-toggle="popover" 
				 	title="Insurance" 
				 	data-html="true" 
				 	data-placement="top" 
				 	data-content='
					 	
					 	<div class="col-sm-12 col-md-12">

			    		<h3><?= $client_insurance_producer ?></h3>

			    		<?php
							echo '<b>Phone number: </b>' . $client_insurance_producer_phone_number . '<br>';
							echo '<b>Fax number: </b>' . $client_insurance_producer_fax_number . '<br>';
							echo '<b>Email: </b>' . $client_insurance_producer_email . '<br>';
				    	?>

			    		<h4>Insurance Company</h4>
			    		<p><?= $client_insurance_company_id_name[$client_insurance_insurance_company_id]; ?></p>
			    		<p><b>Website: </b> <?= $client_insurance_website ? $client_insurance_website : '<i class="red">N/A</i>' ?></p>
			    		<p><b>Username: </b> <?= $client_insurance_website_username ? $client_insurance_website_username : '<i class="red">N/A</i>' ?></p>
			    		<p><b>Password: </b> <?= $client_insurance_website_password ? $client_insurance_website_password : '<i class="red">N/A</i>' ?></p>

			    		<h4>Other</h4>
			    		
			    		<p><b>Vin: </b> <?= $client_insurance_vin_number ? $client_insurance_vin_number : '<i class="red">N/A</i>' ?></p>
			    	</div>

	  		 		<!-- Some automated sizing required as cannot find a way to adjust size on this element -->
						<span style="opacity: 0;">1234567891011121314</span>
				 	'>
				 	Insurance
				</a>

				| 

				<a
					href="#" 
				 	data-toggle="popover" 
				 	title="Factoring Company" 
				 	data-html="true" 
				 	data-placement="top" 
				 	data-content='
					 	
					 	<div class="col-sm-12 col-md-12">

				    	<h3><?= $factoring_company_name_alt[1] ?></h3>

				  		<small>
			  				<i>
			  					<a target="_blank" href="<?= $factoring_company_uri[1] ?>"><?= $factoring_company_uri[1] ?></a>
			  				</i>
			  			</small><br>

							<small>
								<i>
									<a href="mailto:<?= $factoring_company_invoicing_email[1] ?>"><?= $factoring_company_invoicing_email[1] ?></a> 
								</i>
							</small><br>

							<i class="fa fa-phone"></i> 
							
							<small>
								<i>
									<?= $factoring_company_phone_number_01[1] ?>
								</i>
							</small><br>

							<i class="fa fa-fax"></i> 
							
							<small>
								<i>
									<?= $factoring_company_fax[1] ?>
								</i>
							</small><br>

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
						</div>

	  		 		<!-- Some automated sizing required as cannot find a way to adjust size on this element -->
						<span style="opacity: 0;">1234567891011121314</span>
				 	'>
				 	Factoring Company
				</a> <?php
			}
			?>
				
		</div>

		<div class="col-sm-12 col-md-2<?= $_POST['client_card'] ? '' : 'hidden' ?>">

			<?php
			if ($_POST['client_card_user_id']) {

				# Show user tabs ?>
				
			 	<div class="cc_user_tabs cc_user_tractor<?= $client_user_tractor_count ? '' : ' hidden' ?>">
			 		
			 		<span 
					 	data-toggle="popover" 
					 	title="Tractor" 
					 	data-html="true" 
					 	data-placement="top" 
					 	data-content='
						 	
						 	<div class="row">

								<div class="col-sm-12 col-md-12">

									<p><small><b>Number:</b> <?= $client_user_tractor_number[1] ?></small></p>
									<p><small><b>Color:</b> <?= $client_user_tractor_color[1] ?></small></p>
									<p><small><b>Sleeper:</b> <?= $client_user_tractor_sleeper[1] ? '<i class="green">Yes</i>' : '<i class="red">No</i>' ?></small></p>
									<p><small><b>Headrack:</b> <?= $client_user_tractor_headrack[1] == 1 ? '<i class="green">Yes</i>' : '<i class="red">No</i>' ?></small></p>
									<p><small><b>Year:</b> <?= $client_user_tractor_year[1] ?></small></p>
									<?= $client_user_tractor_weight[1] ? '<p><small><b>Weight:</b> ' . $client_user_tractor_weight[1] . '</small></p>' : '' ?>
									<p><small><b>Make:</b> <?= $client_user_tractor_make[1] ?></small></p>
									<p><small><b>Model:</b> <?= $client_user_tractor_model[1] ?></small></p>
									<p><small><b>Vin:</b> <?= $client_user_tractor_vin[1] ?></small></p>
									<p><small><b>Name on the side:</b> <?= $client_user_tractor_name_on_the_side[1] ?></small></p>
									<p><small><b>License plate:</b> <?= $client_user_tractor_license_plate[1] ?></small></p>
									<small style="color: #888; font-style: italic;">
										Added by <?= $user_list_id_name[$client_user_tractor_user_id[1]] . ' ' . $user_list_id_last_name[$client_user_tractor_user_id[1]] ?> on <?= $client_user_tractor_added[1] ?>
									</small>

									<!-- Some automated sizing required as cannot find a way to adjust size on this element -->
									<span style="opacity: 0;">1234567891011121314</span>
				    		</div>

							</div>
					 	'>
					 	Tractor
					</span>
			 	</div>
			  
			  <div class="cc_user_tabs cc_user_trailer<?= $client_tractor_trailer_count ? '' : ' hidden' ?>">
			  	
			  	<span 
					 	data-toggle="popover" 
					 	title="Trailer" 
					 	data-html="true" 
					 	data-placement="top" 
					 	data-content='
						 	
						 	<div class="row">
			    			
			    			<div class="col-sm-12 col-md-12">

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

									<!-- Some automated sizing required as cannot find a way to adjust size on this element -->
									<span style="opacity: 0;">1234567891011121314</span>
				    		</div>

			    		</div>
					 	'>
					 	Trailer
					</span>
			  </div>

			  <div class="cc_user_tabs cc_user_equipment<?= $client_user_equipment_assoc_count ? '' : ' hidden' ?>">
			  	
			  	<span 
					 	data-toggle="popover" 
					 	title="Equipment" 
					 	data-html="true" 
					 	data-placement="top" 
					 	data-content='
						 	
						 	<?php
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
		  		 		} ?>

		  		 		<!-- Some automated sizing required as cannot find a way to adjust size on this element -->
							<span style="opacity: 0;">1234567891011121314</span>
					 	'>
					 	Equipment
					</span>
			  </div>

			  <div class="cc_user_tabs cc_user_features<?= $client_user_feature_assoc_count ? '' : ' hidden' ?>">
			  	
			  	<span 
					 	data-toggle="popover" 
					 	title="Features" 
					 	data-html="true" 
					 	data-placement="top" 
					 	data-content='
						 	
						 	<?php
							# Loop through feature association
		  		 		for ($i = 1; $i <= $client_user_feature_assoc_count ; $i++) { ?>

		  		 			<p>
		  		 				<small>

		  		 					<span class="fa fa-check green"></span>

		  		 					<?= $client_user_feature_id_name[$client_user_feature_assoc_feature_id[$i]] ?>
		  		 				</small>
		  		 			</p> <?php
		  		 		} ?>

		  		 		<!-- Some automated sizing required as cannot find a way to adjust size on this element -->
							<span style="opacity: 0;">1234567891011121314</span>
					 	'>
					 	Features
					</span>
			  </div>

				 <?php
			}

			if ($_SERVER['REMOTE_ADDR'] == '186.26.115.213') { ?>
			 	
			 	<i 
					id="cc-user-popover" 
					class="fa fa-users" 
				 	data-toggle="popover" 
				 	title="User data" 
				 	data-html="true" 
				 	data-placement="top" 
				 	data-content='
					 	<?php
		    		# Loop through users
		    		for ($i = 1; $i <= $cc_client_user_count ; $i++) { ?>
		    			
		    			<div class="row" style="min-width: 300px;">

		    				<?= $i > 1 ? '<hr>' : ''; ?>

		    				<div class="col-sm-12 col-md-7">
		    					
		    					<p style="margin: 0;">
				    				<small>

				    					<b><?= $user_list_id_name[$cc_client_user_user_id[$i]] . ' ' . $user_list_id_last_name[$cc_client_user_user_id[$i]] ?></b> | 
				    					<?= $cc_client_user_user_type[$i] == 0 ? 'Owner' : ($cc_client_user_user_type[$i] == 1 ? 'Owner/Operator' : 'Driver'); ?>
				    				</small>
			    				</p>
		    				</div>

		    				<div class="col-sm-12 col-md-5">
		    					
		    					<form action="" method="post">
				    					
			    					<select name="display_data" class="form-control sm-select" style="width: 90px; margin-left: -20px;" onchange="this.form.submit();">
		    							<option value="">Choose data</option>
		    							<option value="tractor">Tractor</option>
										  <option value="trailer">Trailer</option>
										  <option value="equipment">Equipment</option>
										  <option value="features">Features</option>
		    						</select>

		    						<input type="hidden" name="client_card_user_id" value="<?= $cc_client_user_user_id[$i] ?>">
		    						<input type="hidden" name="client_card" value="<?= $_POST['client_card'] ?>">
			    				</form>
		    				</div>

		    				<div class="col-sm-12 col-md-7<?= $cc_client_user_user_id[$i] == $_POST['client_card_user_id'] ? '' : ' hidden' ?>">

		    				Hello tractor!
		    				</div>
		    			</div> <?php
		    		}
		    		?>
				 	'>
				 	
				</i> <?php
			} else { ?>

				<form action="" method="post">
					    				
					<select class="form-control sm-select" style="width: 135px; margin-top: 6px;" name="client_card_user_id" onchange="this.form.submit();">
						<option>User data</option>

						<?php
						# Loop through users
						for ($i = 1; $i <= $cc_client_user_count ; $i++) { ?>
							
							<option value="<?= $cc_client_user_user_id[$i] ?>"<?= $cc_client_user_user_id[$i] == $_POST['client_card_user_id'] ? ' selected' : '' ?>>

								<?= $cc_client_user_user_id[$i] == $_POST['client_card_user_id'] ? ' < ' : '' ?>
								<?= $user_list_id_name[$cc_client_user_user_id[$i]] . ' ' . $user_list_id_last_name[$cc_client_user_user_id[$i]] ?>
							</option> <?php
						}
						?>

					</select>

					<input type="hidden" name="client_card" value="<?= $_POST['client_card'] ?>">
				</form> <?php
			} ?>

		</div>

	</footer> <?php
}
