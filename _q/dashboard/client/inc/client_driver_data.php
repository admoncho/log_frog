<div class="row">

	<div class="col-md-3 col-sm-6">

		<div class="panel panel-<?= isset($_GET['edit_client_user']) ? 'danger' : 'default'?>">
			
			<div class="panel-heading">
		    
		    <p><?= isset($_GET['edit_client_user']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>Client user</p>
		  </div>

		  <?php
	  	# Data display if not updating
	  	if (!isset($_GET['edit_client_user'])) { ?>

	  		<div class="panel-body" style="max-height: 119px; min-height: 119px; overflow-y: auto;">

	  			<h5><?= $user_list_id_name[$_GET['user_id']] . ' ' . $user_list_id_last_name[$_GET['user_id']] ?> </h5>
	  			<small>
	  				
	  				<?= $client_user_user_type[1] == 0 ? 'Owner' : ($client_user_user_type[1] == 1 ? 'Owner/operator' : 'Driver') ?>
	  				 | 
	  				<?= $client_company_name[1] ?>
	  			</small>
	  		</div> <?php
	  	} 

	  	# Update main
	  	if (isset($_GET['edit_client_user'])) { ?>
	  		
	  		<div class="panel-body">
		  		<form action="" method="post">

						<div class="form-group">
							<label class="control-label">User type <?= $manager_count ?></label>
							<select name="user_type" class="form-control">

								<option value="9"<?= $client_user_user_type[1] == 0 ? ' selected' : '' ?>>Owner</option>
								<option value="1"<?= $client_user_user_type[1] == 1 ? ' selected' : '' ?>>Owner/operator</option>
								<option value="2"<?= $client_user_user_type[1] == 2 ? ' selected' : '' ?>>Driver</option>
							</select>
						</div>

						<div class="form-group">
							
							<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>">Cancel</a>
						</div>

						<input type="hidden" name="_controller_client_user" value="edit_client_user">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
		  	</div> <?php
	  	}
	  	
	  	# Hide panel-footer if not updating
	  	if (!isset($_GET['edit_client_user'])) { ?>

	  		<div class="panel-footer" style="max-height: 41px;">
		  		<small>

							<span>
								
								<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&edit_client_user=1"> <i class="fa fa-pencil"></i></a>

							</span>
					</small>
				</div> <?php
	  	}
	  	?>

		</div>
	</div>

	<?php

	# Tractor, trailer and equipment data is only available for $client_user_user_type[$i] 1 and 2 (0 is an owner)
	if ($client_user_user_type[1] > 0) { ?>
		
		<div class="col-md-3 col-sm-6">

			<div class="panel panel-<?= isset($_GET['edit_tractor']) ? 'danger' : 'default'?>">
				
				<div class="panel-heading">
			    
			    <p><?= isset($_GET['edit_tractor']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>Tractor data</p>
			  </div>

			  <?php
		  	# Data display if [not updating || updating something other than this]
		  	if (!isset($_GET['edit_tractor'])) {

		  		if ($client_user_tractor_count) { ?>
		  		 	
		  		 	<ul class="list-group" style="max-height: 241px; min-height: 241px; overflow-y: auto;">

			  			<li href="#" class="list-group-item">

			  				<div class="row">
			  					
			  					<div class="col-sm-12 col-md-8">
				  					<small>
											<i>
												<b>Tractor #</b>
												<?= $client_user_tractor_number[1] != 0 ? $client_user_tractor_number[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-4">
				  					<small>
											<i>
												<b>Color</b>: 
												<?= $client_user_tractor_color[1] != '' ? $client_user_tractor_color[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-8">
				  					<small>
											<i>
												<b>Vin #</b>
												<?= $client_user_tractor_vin[1] != 0 ? $client_user_tractor_vin[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-4">
				  					<small>
											<i>
												<b>Year: </b>
												<?= $client_user_tractor_year[1] != 0 ? $client_user_tractor_year[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-8">
				  					<small>
											<i>
												<b>License plate</b>: 
												<?= $client_user_tractor_license_plate[1] != 0 ? $client_user_tractor_license_plate[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-4">
				  					<small>
											<i>
												<b>Make</b>: 
												<?= $client_user_tractor_make[1] != 0 ? $client_user_tractor_make[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-8">
				  					<small>
											<i>
												<b>Model</b>
												<?= $client_user_tractor_model[1] != '' ? $client_user_tractor_model[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-4">
				  					<small>
											<i>
												<b>Weight</b>: 
												<?= $client_user_tractor_weight[1] != 0 ? $client_user_tractor_weight[1] : '<i class="red">N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-8">
				  					<small>
											<i>
												<b>Headrack</b>: 
												<?= $client_user_tractor_headrack[1] == 1 ? 'Yes' : 'No' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-4">
				  					<small>
											<i>
												<b>Sleeper</b>
												<?= $client_user_tractor_sleeper[1] == 1 ? 'Yes' : 'No' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">
								<small>
									<i>
										<b>Name on the side</b>
										<?= $client_user_tractor_name_on_the_side[1] != 0 ? $client_user_tractor_name_on_the_side[1] : '<i class="red">N/A</i>' ?>
									</i>
								</small>
							</li>

						</ul> <?php
		  		} else { 

		  		# Display no data warning ?>
					<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

				  	<div class="text-center">
				  		
				  		<p class="red"><i class="fa fa-warning"></i> There is no data to show.</p>
							<span class="red">
								
								<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&edit_tractor=1"> <i class="fa fa-plus"></i> Add data</a>
							</span>
				  	</div>
					</div> <?php
		  		}

		  	} 

		  	# Update main
		  	if (isset($_GET['edit_tractor'])) { ?>
		  		
		  		<div class="panel-body">

			  		<form action="" method="post">
							
							<div class="row">
								
								<div class="col-sm-6 col-md-6">
									
									<div class="form-group">
										<label class="control-label" for="number">Tractor #</label>
										<input name="number" type="text" class="form-control" id="number" value="<?= $client_user_tractor_number[1] ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="color">Tractor color</label>
										<input name="color" type="text" class="form-control" id="color" value="<?= $client_user_tractor_color[1] ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="vin">Tractor vin #</label>
										<input name="vin" type="text" class="form-control" id="vin" value="<?= $client_user_tractor_vin[1] ?>">
									</div>

									<div class="form-group">
										<label class="control-label">Headrack</label>
										<select name="headrack" class="form-control">
											<option value="1"<?= $client_user_tractor_headrack[$i] == 1 ? ' selected' : '' ?>>Yes</option>
											<option value="2"<?= $client_user_tractor_headrack[$i] == 2 ? ' selected' : '' ?>>No</option>
										</select>
									</div>

									<div class="form-group">
										<label class="control-label" for="year">Year</label>
										<input name="year" type="number" max="<?= date('Y') ?>" class="form-control" id="year" value="<?= $client_user_tractor_year[1] ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="make">Make</label>
										<input name="make" type="text" class="form-control" id="make" value="<?= $client_user_tractor_make[1] ?>">
									</div>	
								</div>

								<div class="col-sm-6 col-md-6">
									
									<div class="form-group">
										<label class="control-label" for="model">Model</label>
										<input name="model" type="text" class="form-control" id="model" value="<?= $client_user_tractor_model[1] ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="weight">Weight</label>
										<input name="weight" type="number" class="form-control" id="weight" value="<?= $client_user_tractor_weight[1] ?>">
									</div>

									<div class="form-group">
										<label class="control-label">Sleeper</label>
										<select name="sleeper" class="form-control">
											<option value="1"<?= $client_user_tractor_sleeper[$i] == 1 ? ' selected' : '' ?>>Yes</option>
											<option value="2"<?= $client_user_tractor_sleeper[$i] == 2 ? ' selected' : '' ?>>No</option>
										</select>
									</div>

									<div class="form-group">
										<label class="control-label" for="name_on_the_side">Name on the side</label>
										<input name="name_on_the_side" type="text" class="form-control" id="name_on_the_side" value="<?= $client_user_tractor_name_on_the_side[1] ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="license_plate">License plate</label>
										<input name="license_plate" type="text" class="form-control" id="license_plate" value="<?= $client_user_tractor_license_plate[1] ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-12">

									<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
									<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>&user_id=<?= $_GET['user_id'] ?>">Cancel</a>
								</div>
							</div>

							<input type="hidden" name="_controller_client_user_tractor" value="<?= $client_user_tractor_count ? 'edit' : 'add' ?>_client_user_tractor">
							<input type="hidden" name="token" value="<?= $csrfToken ?>">
						</form>
			  	</div> <?php
		  	}
		  	
		  	# Hide panel-footer if not updating or if updating something other than this section
		  	if (!isset($_GET['edit_tractor']) && $client_user_tractor_count) { ?>

		  		<div class="panel-footer" style="max-height: 41px;">
			  		<small>

								<span>
									
									<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&edit_tractor=1"> <i class="fa fa-pencil"></i></a>

								</span>
						</small>
					</div> <?php
		  	}
		  	?>

			</div>
		</div>

		<div class="col-md-3 col-sm-6">

			<div class="panel panel-<?= isset($_GET['edit_trailer']) ? 'danger' : 'default'?>">
				
				<div class="panel-heading">
			    
			    <p><?= isset($_GET['edit_trailer']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>Trailer data</p>
			  </div>

			  <?php
		  	# Data display if [not updating || updating something other than this]
		  	if (!isset($_GET['edit_trailer'])) {

		  		if ($client_tractor_trailer_count) { ?>
		  		 	
		  		 	<ul class="list-group" style="max-height: 241px; min-height: 241px; overflow-y: auto;">

			  			<li href="#" class="list-group-item">

			  				<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Trailer #: </b>
												<?= $client_tractor_trailer_trailer_number != 0 ? $client_tractor_trailer_trailer_number : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Trailer type: </b>
												<?= $client_trailer_type_id_name[$client_tractor_trailer_trailer_type] != '' ? $client_trailer_type_id_name[$client_tractor_trailer_trailer_type] : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Width: </b>
												<?= $client_tractor_trailer_width != 0 ? $client_tractor_trailer_width : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Length: </b>
												<?= $client_tractor_trailer_length != 0 ? $client_tractor_trailer_length : '<i class="red"> N/A</i>' ?> 
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>License plate: </b>
												<?= $client_tractor_trailer_license_plate != '' ? $client_tractor_trailer_license_plate : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Gross weight: </b>
												<?= $client_tractor_trailer_gross_weight != '0.00' ? $client_tractor_trailer_gross_weight : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Vin #: </b>
												<?= $client_tractor_trailer_vin != '' ? $client_tractor_trailer_vin : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Deck material: </b>
												<?= $client_trailer_deck_material_id_name[$client_tractor_trailer_deck_material] ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">

								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Headrack: </b>
												<?= $client_tractor_trailer_headrack == 1 ? 'Yes' : 'No' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Air ride: </b>
												<?= $client_tractor_trailer_air_ride == 1 ? 'Yes' : 'No' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">
								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Year: </b>
												<?= $client_tractor_trailer_year != 0 ? $client_tractor_trailer_year : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Door type: </b>
												<?= $client_trailer_door_type_id_name[$client_tractor_trailer_door_type] != '' ? $client_trailer_door_type_id_name[$client_tractor_trailer_door_type] : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">
								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Roof type: </b>
												<?= $client_tractor_trailer_roof_type != 0 ? $client_tractor_trailer_roof_type : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Bottom deck: </b>
												<?= $client_tractor_trailer_bottom_deck != '0.00' ? $client_tractor_trailer_bottom_deck : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">
								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Upper deck: </b>
												<?= $client_tractor_trailer_upper_deck != '0.00' ? $client_tractor_trailer_upper_deck : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Goose neck: </b>
												<?= $client_tractor_trailer_goose_neck == 1 ? 'Yes' : 'No' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

							<li href="#" class="list-group-item">
								<div class="row">
			  					
			  					<div class="col-sm-12 col-md-7">
				  					<small>
											<i>
												<b>Make: </b>
												<?= $client_tractor_trailer_make != '' ? $client_tractor_trailer_make : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>

				  				<div class="col-sm-12 col-md-5">
				  					<small>
											<i>
												<b>Height: </b>
												<?= $client_tractor_trailer_height != '0.00' ? $client_tractor_trailer_height : '<i class="red"> N/A</i>' ?>
											</i>
										</small>
				  				</div>
			  				</div>
							</li>

						</ul> <?php
		  		} else { 

		  		# Display no data warning ?>

						<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

					  	<div class="text-center">
					  		
					  		<p class="red"><i class="fa fa-warning"></i> There is no data to show.</p>

					  		<?php
					  		if ($client_user_tractor_count) { ?>
					  			
					  			<span class="red">
										
										<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&edit_trailer=1"> <i class="fa fa-plus"></i> Add data</a>
									</span> <?php
					  		} else { ?>

					  			<p class="red"><i class="fa fa-warning"></i> You must have tractor data saved in order to add trailer data.</p> <?php
					  		}
					  		?>
									
					  	</div>
						</div> <?php
		  		}

		  	} 

		  	# Update main
		  	if (isset($_GET['edit_trailer'])) { ?>
		  		
		  		<div class="panel-body">
			  	
			  		<form action="" method="post">

							<div class="row">

								<div class="col-sm-12 col-md-6">

									<div class="form-group">
										<label class="control-label" for="trailer_number">Trailer #</label>
										<input name="trailer_number" type="number" class="form-control" id="trailer_number" value="<?= $client_tractor_trailer_trailer_number ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="year">Year</label>
										<input name="year" type="number" max="<?= date('Y') ?>" class="form-control" id="year" value="<?= $client_tractor_trailer_year ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="make">Make</label>
										<input name="make" type="text" class="form-control" id="make" value="<?= $client_tractor_trailer_make ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="vin">Vin #</label>
										<input name="vin" type="text" class="form-control" id="vin" value="<?= $client_tractor_trailer_vin ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="license_plate">License plate</label>
										<input name="license_plate" type="text" class="form-control" id="license_plate" value="<?= $client_tractor_trailer_license_plate ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="length">Length</label>
										<input name="length" type="number" class="form-control" id="length" value="<?= $client_tractor_trailer_length ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="height">Height</label>
										<input name="height" type="number" class="form-control" id="height" value="<?= $client_tractor_trailer_height ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="width">Width</label>
										<input name="width" type="number" class="form-control" id="width" value="<?= $client_tractor_trailer_width ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="gross_weight">Gross weight</label>
										<input name="gross_weight" type="number" class="form-control" id="gross_weight" value="<?= $client_tractor_trailer_gross_weight ?>">
									</div>
								</div>

								<div class="col-sm-12 col-md-6">

									<div class="form-group">
										<label class="control-label" for="bottom_deck">Bottom deck</label>
										<input name="bottom_deck" type="number" class="form-control" id="bottom_deck" value="<?= $client_tractor_trailer_bottom_deck ?>">
									</div>

									<div class="form-group">
										<label class="control-label" for="upper_deck">Upper deck</label>
										<input name="upper_deck" type="number" class="form-control" id="upper_deck" value="<?= $client_tractor_trailer_upper_deck ?>">
									</div>

									<div class="form-group">
										<label class="control-label">Door type</label>
										<select name="door_type" class="form-control">
											<option></option>

											<?php
											for ($i = 1; $i <= $client_trailer_door_type_count ; $i++) { ?>
												
												<option value="<?= $client_trailer_door_type_id[$i] ?>"<?= $client_tractor_trailer_door_type == $client_trailer_door_type_id[$i] ? ' selected' : '' ?>><?= $client_trailer_door_type_name[$i] ?></option> <?php
											}
											?>

										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Deck material</label>
										<select name="deck_material" class="form-control">

											<?php
											for ($i = 1; $i <= $client_trailer_deck_material_count ; $i++) { ?>
												
												<option value="<?= $client_trailer_deck_material_id[$i] ?>"<?= $client_tractor_trailer_deck_material == $client_trailer_deck_material_id[$i] ? ' selected' : '' ?>><?= $client_trailer_deck_material_name[$i] ?></option> <?php
											}
											?>

										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Roof type</label>
										<select name="roof_type" class="form-control">
											<option></option>

											<?php
											for ($i = 1; $i <= $client_trailer_roof_type_count ; $i++) { ?>
												
												<option value="<?= $client_trailer_roof_type_id[$i] ?>"<?= $client_tractor_trailer_roof_type == $client_trailer_roof_type_id[$i] ? ' selected' : '' ?>><?= $client_trailer_roof_type_name[$i] ?></option> <?php
											}
											?>

										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Trailer type</label>
										<select name="trailer_type" class="form-control">
											<option></option>

											<?php
											for ($i = 1; $i <= $client_trailer_type_count ; $i++) { ?>
												
												<option value="<?= $client_trailer_type_id[$i] ?>"<?= $client_tractor_trailer_trailer_type == $client_trailer_type_id[$i] ? ' selected' : '' ?>><?= $client_trailer_type_name[$i] ?></option> <?php
											}
											?>

										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Goose neck</label>
										<select name="goose_neck" class="form-control">
											<option value="1"<?= $client_tractor_trailer_goose_neck == 1 ? ' selected' : '' ?>>Yes</option>
											<option value="2"<?= $client_tractor_trailer_goose_neck == 2 ? ' selected' : '' ?>>No</option>
										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Headrack</label>
										<select name="headrack" class="form-control">
											<option value="1"<?= $client_tractor_trailer_headrack == 1 ? ' selected' : '' ?>>Yes</option>
											<option value="2"<?= $client_tractor_trailer_headrack == 2 ? ' selected' : '' ?>>No</option>
										</select>
									</div>

									<div class="form-group">
										<label class="control-label">Air ride</label>
										<select name="air_ride" class="form-control">
											<option value="1"<?= $client_tractor_trailer_air_ride == 1 ? ' selected' : '' ?>>Yes</option>
											<option value="2"<?= $client_tractor_trailer_air_ride == 2 ? ' selected' : '' ?>>No</option>
										</select>
									</div>
								</div>
							</div>

							<div class="col-sm-12 col-md-12">

								<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
								<a class="btn btn-link red pull-right" href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>">Cancel</a>
							</div>

							<input type="hidden" name="tractor_id" value="<?= $client_user_tractor_id[1] ?>">
							<input type="hidden" name="_controller_client_tractor_trailer" value="<?= $client_tractor_trailer_count ? 'edit' : 'add' ?>_client_tractor_trailer">
							<input type="hidden" name="token" value="<?= $csrfToken ?>">
						</form>
			  	</div> <?php
		  	}
		  	
		  	# Hide panel-footer if not updating or if updating something other than this section
		  	if (!isset($_GET['edit_trailer']) && $client_tractor_trailer_count) { ?>

		  		<div class="panel-footer" style="max-height: 41px;">
			  		<small>

							<span>
								
								<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&edit_trailer=1"> <i class="fa fa-pencil"></i></a>
							</span>
						</small>
					</div> <?php
		  	}
		  	?>

			</div>
		</div>

		<div class="col-md-3 col-sm-6">

			<div class="panel panel-<?= isset($_GET['edit_equipment']) ? 'danger' : 'default'?>">
				
				<div class="panel-heading">
			    
			    <p><?= isset($_GET['edit_equipment']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>User equipment</p>
			  </div>

			  <?php
		  	# Data display if [not updating || updating something other than this]
		  	if (!isset($_GET['add_equipment'])) {

		  		if ($client_user_equipment_assoc_count) { ?>

		  			<div class="table-responsive" style="max-height: 241px; min-height: 241px; overflow-y: auto;">
						  
						  <table class="table table-bordered">
						    
						  	<tr>
							    <th>Item</th>
							    <th class="text-right">Qty</th>
							    <th></th>
							  </tr>

							  <?php
			  		 		# Loop through equipment association
			  		 		for ($i = 1; $i <= $client_user_equipment_assoc_count ; $i++) { ?>

			  		 			<tr<?= isset($_GET['edit_equipment']) ? ($_GET['edit_equipment'] == $client_user_equipment_assoc_id[$i] ? '' : ' class="hidden"') : '' ?>>
								    <td<?= $i == $client_user_equipment_assoc_count ? ' style="border-bottom: 1px solid #e7ebee;"' : '' ?>>
								    	<small>
								    		<?= $client_user_equipment_id_name[$client_user_equipment_assoc_equipment_id[$i]] ?>
								    	</small>
								    </td>

								    <td class="text-right"<?= $i == $client_user_equipment_assoc_count ? ' style="border-bottom: 1px solid #e7ebee;"' : '' ?>>
								    	<small>
								    		<?php 
								    		if ($_GET['edit_equipment']) {
								    			
								    			# Show update form ?>

								    			<form action="" method="post">

								    				<div class="form-group" style="margin-bottom: 0;">

								    					<input type="number" min="1" name="quantity" placeholder="quantity" class="form-control text-center" value="<?= $client_user_equipment_assoc_quantity[$i] ?>" style="width:120px;">
								    				</div>
								    				
								    				<button type="submit" class="btn btn-link">Save</button>
								    				<input type="hidden" name="_controller_client_user_equipment" value="update_client_equipment">
														<input type="hidden" name="token" value="<?= $csrfToken ?>">
								    			</form> <?php
								    		} else {

								    			# Show qty
								    			echo $client_user_equipment_assoc_quantity[$i];
								    		} ?>
								    	</small>
								    </td>

								    <td style="width: 90px;<?= $i == $client_user_equipment_assoc_count ? ' border-bottom: 1px solid #e7ebee;' : '' ?>">

								    	<?php
								    	if ($_GET['edit_equipment']) {

								    		# Show cancel link ?>

								    		<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>" class="red" style="cursor: pointer;"> 
									    		
									    		Cancel
									    	</a> <?php
								    	} else {

								    		# Show update, delete links ?>

								    		<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&edit_equipment=<?= $client_user_equipment_assoc_id[$i] ?>" data-toggle="tooltip" data-placement="top" title="Edit quantity" class="btn btn-link btn-sm pull-left" style="cursor: pointer;">

									    		<i class="fa fa-pencil"></i>
									    	</a>

									    	<form action="" method="post">

									    		<button class="btn btn-link btn-sm red" data-toggle="tooltip" data-placement="top" title="Delete equipment" style="cursor: pointer;"> 
										    		<i class="fa fa-trash-o"></i>
										    	</button>

										    	<input type="hidden" name="client_user_equipment_assoc_id" value="<?= $client_user_equipment_assoc_id[$i] ?>">
										    	<input type="hidden" name="_controller_client_user_equipment" value="delete_client_equipment">
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
									    	</form> <?php
								    	}
								    	?>

								    </td>
								  </tr> <?php
			  		 		}
			  		 		?>

						  </table>
						</div> <?php
		  		} else { 

		  		# Display no data warning ?>
					<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

				  	<div class="text-center">
				  		
				  		<p class="red"><i class="fa fa-warning"></i> There is no data to show.</p>

				  		<span class="red">
								
								<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&add_equipment=1"> <i class="fa fa-plus"></i> Add data</a>
							</span>
								
				  	</div>
					</div> <?php
		  		}

		  	} 

		  	# Add equipment
		  	if (isset($_GET['add_equipment'])) { ?>
		  		
		  		<div class="panel-body">
			  		
			  		<form action="" method="post">
			  			
			  			<div class="form-group">

			  				<select name="client_user_equipment_id" class="form-control" onchange="this.form.submit()">
			  					<option<?= $_POST['client_user_equipment_id'] ? ' class="hidden"' : '' ?>>Add equipment</option>

			  					<?php
			  					# Loop through client user equipment
			  					for ($i = 1; $i <= $client_user_equipment_count ; $i++) { ?>
			  						
			  						<option value="<?= $client_user_equipment_id[$i] ?>"<?= $client_user_equipment_id[$i] == $_POST['client_user_equipment_id'] ? ' selected' : '' ?>>
			  							
			  							<?= $client_user_equipment_name[$i] ?>
			  						</option> <?php
			  					}
			  					?>
			  				</select>
			  			</div>

			  			<?php
			  			# Request quantity after getting client user equipment id
			  			if ($_POST['client_user_equipment_id']) { ?>
			  				
			  				<div class="form-group">

			  					<input type="number" name="quantity" min="1" class="form-control" placeholder="Quantity">
			  				</div>

			  				<div class="form-group">
				  				<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> Save</button>

				  				<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>" class="btn btn-link red pull-right">Cancel</a>
				  			</div>

				  			<input type="hidden" name="_controller_client_user_equipment" value="add_client_equipment">
								<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
			  			} else { ?>

			  				<div class="form-group">

				  				<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>" class="btn btn-link red pull-right">Cancel</a>
				  			</div> <?php
			  			}
			  			?>
			  		</form>

			  	</div> <?php
		  	}
		  	
		  	# Hide panel-footer if not updating or if updating something other than this section
		  	if (!isset($_GET['add_equipment']) && $client_user_equipment_assoc_count) { ?>

		  		<div class="panel-footer" style="max-height: 41px;">
			  		<small>

							<span>
								
								<?php
								# If driver has all available equipment items
								if ($client_user_equipment_assoc_count == $client_user_equipment_count) { ?>
									
									<a data-toggle="tooltip" data-placement="top" title="Driver has all equipment items" href="#"> 
										
										<i class="fa fa-plus" style="color: #888"></i>
									</a> <?php
								} else { ?>

									<a data-toggle="tooltip" data-placement="top" title="Add equipment" href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&add_equipment=1"> 
										
										<i class="fa fa-plus"></i>
									</a> <?php
								}	
								?>

							</span>
						</small>
					</div> <?php
		  	}
		  	?>

			</div>
		</div>

		<div class="col-md-3 col-sm-6">

			<div class="panel panel-<?= isset($_GET['edit_feature']) ? 'danger' : 'default'?>">
				
				<div class="panel-heading">
			    
			    <p><?= isset($_GET['edit_feature']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>User features</p>
			  </div>

			  <?php
		  	# Data display if [not updating || updating something other than this]
		  	if (!isset($_GET['add_feature'])) {

		  		if ($client_user_feature_assoc_count) { ?>

		  			<div class="table-responsive" style="max-height: 241px; min-height: 241px; overflow-y: auto;">
						  
						  <table class="table table-bordered">
						    
						  	<tr>
							    <th>Item</th>
							    <th></th>
							  </tr>

							  <?php
			  		 		# Loop through feature association
			  		 		for ($i = 1; $i <= $client_user_feature_assoc_count ; $i++) { ?>

			  		 			<tr>
								    <td<?= $i == $client_user_feature_assoc_count ? ' style="border-bottom: 1px solid #e7ebee;"' : '' ?>>
								    	<small>
								    		<?= $client_user_feature_id_name[$client_user_feature_assoc_feature_id[$i]] ?>
								    	</small>
								    </td>

								    <td style="width: 90px;<?= $i == $client_user_feature_assoc_count ? ' border-bottom: 1px solid #e7ebee;' : '' ?>">

								    	<form action="" method="post">

								    		<button class="btn btn-link btn-sm red" data-toggle="tooltip" data-placement="top" title="Delete feature" style="cursor: pointer;"> 
									    		<i class="fa fa-trash-o"></i>
									    	</button>

									    	<input type="hidden" name="client_user_feature_assoc_id" value="<?= $client_user_feature_assoc_id[$i] ?>">
									    	<input type="hidden" name="_controller_client_user_feature" value="delete_client_feature">
												<input type="hidden" name="token" value="<?= $csrfToken ?>">
								    	</form>

								    </td>
								  </tr> <?php
			  		 		}
			  		 		?>

						  </table>
						</div> <?php
		  		} else { 

		  		# Display no data warning ?>
					<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

				  	<div class="text-center">
				  		
				  		<p class="red"><i class="fa fa-warning"></i> There is no data to show.</p>

				  		<span class="red">
								
								<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&add_feature=1"> <i class="fa fa-plus"></i> Add data</a>
							</span>
								
				  	</div>
					</div> <?php
		  		}

		  	} 

		  	# Add feature
		  	if (isset($_GET['add_feature'])) { ?>
		  		
		  		<div class="panel-body">
			  		
			  		<form action="" method="post">
			  			
			  			<div class="form-group">

			  				<select name="client_user_feature_id" class="form-control">
			  					<option<?= $_POST['client_user_feature_id'] ? ' class="hidden"' : '' ?>>Add feature</option>

			  					<?php
			  					# Loop through client user feature
			  					for ($i = 1; $i <= $client_user_feature_count ; $i++) { ?>
			  						
			  						<option value="<?= $client_user_feature_id[$i] ?>"<?= $client_user_feature_id[$i] == $_POST['client_user_feature_id'] ? ' selected' : '' ?>>
			  							
			  							<?= $client_user_feature_name[$i] ?>
			  						</option> <?php
			  					}
			  					?>
			  				</select>
			  			</div>

			  			<div class="form-group">
			  				<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> Save</button>

			  				<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>" class="btn btn-link red pull-right">Cancel</a>
			  			</div>

			  			<input type="hidden" name="_controller_client_user_feature" value="add_client_feature">
							<input type="hidden" name="token" value="<?= $csrfToken ?>">
			  		</form>

			  	</div> <?php
		  	}
		  	
		  	# Hide panel-footer if not updating or if updating something other than this section
		  	if (!isset($_GET['add_feature']) && $client_user_feature_assoc_count) { ?>

		  		<div class="panel-footer" style="max-height: 41px;">
			  		<small>

							<span>
								
								<?php
								# If driver has all available feature items
								if ($client_user_feature_assoc_count == $client_user_feature_count) { ?>
									
									<a data-toggle="tooltip" data-placement="top" title="Driver has all feature items" href="#"> 
										
										<i class="fa fa-plus" style="color: #888"></i>
									</a> <?php
								} else { ?>

									<a data-toggle="tooltip" data-placement="top" title="Add feature" href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $_GET['user_id'] ?>&add_feature=1"> 
										
										<i class="fa fa-plus"></i>
									</a> <?php
								}	
								?>

							</span>
						</small>
					</div> <?php
		  	}
		  	?>

			</div>
		</div> <?php

	}
	?>

</div>