<?php 
session_start();
ob_start();
# Redirect if no query string
if (!$_SERVER['QUERY_STRING']) { ?>
	<script type="text/javascript">
		window.location = "/dashboard/client/"
	</script> <?php
}

require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_underscore = str_replace('-', '_', basename(__FILE__, '.php'));

if (isset($_GET[$this_file_name_underscore . '_id'])) {
	
	$this_id = $this_file_name_underscore . '_id=' . $_GET[$this_file_name_underscore . '_id'];
}

# Include module notification file
include $module_directory . 'inc/notification.php';

if ($_GET['user_id']) {
	
	# Include module notification file
	include 'inc/client_driver_data.php';
}
?>

<!-- This row hides when editing driver data -->
<div class="row<?= $_GET['user_id'] ? ' hidden' : '' ?>">
	<div class="col-md-3 col-sm-6">

		<div class="panel panel-<?= isset($_GET['edit_main']) ? 'danger' : 'default'?>">
			<div class="panel-heading">
		    <p><?= isset($_GET['edit_main']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>Main</p>
		  </div>

		  <?php
	  	# Data display if [not updating || updating something other than this]
	  	if (!isset($_GET['edit_main'])) { ?>
	  		
	  		<ul class="list-group" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

					<?php
					# Phone number 1
					if (${$this_file_name_underscore . '_main_contact'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<span class="fa fa-user"></span>
									<?= ${$this_file_name_underscore . '_main_contact'}[1] ?>
								</i>
							</small>
						</li> <?php
					}

					# Phone number 1
					if (${$this_file_name_underscore . '_phone_number_01'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<span class="fa fa-phone"></span>
									<?= ${$this_file_name_underscore . '_phone_number_01'}[1] ?>
								</i>
							</small>
						</li> <?php
					}

					# Phone number 2
					if (${$this_file_name_underscore . '_phone_number_02'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<span class="fa fa-phone"></span>
									<?= ${$this_file_name_underscore . '_phone_number_02'}[1] ?>
								</i>
							</small>
						</li> <?php
					}

					# MC number
					if (${$this_file_name_underscore . '_mc_number'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<?= ${$this_file_name_underscore . '_mc_number'}[1] ?>
									<span style="color: #888;"> [MC]</span>
								</i>
							</small>
						</li> <?php
					}

					# US DOT number
					if (${$this_file_name_underscore . '_us_dot_number'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<?= ${$this_file_name_underscore . '_us_dot_number'}[1] ?>
									<span style="color: #888;"> [US DOT]</span>
								</i>
							</small>
						</li> <?php
					}

					# EIN number
					if (${$this_file_name_underscore . '_ein_number'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<?= ${$this_file_name_underscore . '_ein_number'}[1] ?> 
									<span style="color: #888;">[EIN]</span>
								</i>
							</small>
						</li> <?php
					}

					# chr_t number
					if (${$this_file_name_underscore . '_chr_t'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<?= ${$this_file_name_underscore . '_chr_t'}[1] ?> 
									<span style="color: #888;">[CHR T]</span>
								</i>
							</small>
						</li> <?php
					}
					?>

				</ul> <?php

	  	} 

	  	# Update main
	  	if (isset($_GET['edit_main'])) { ?>
	  		
	  		<div class="panel-body">
		  		<form action="" method="post">
						<div class="form-group">
							<label class="control-label" for="company_name">Company Name</label>
							<input name="company_name" type="text" class="form-control" id="company_name" value="<?= $client_company_name[1] ?>">
						</div>
						<div class="row">
							<div class="form-group col-sm-12 col-md-6 text-center">
								<label class="control-label" for="mc_number">MC</label>
								<input name="mc_number" type="text" class="form-control" id="mc_number" value="<?= $client_mc_number[1] ?>">
							</div>
							<div class="form-group col-sm-12 col-md-6 text-center">
								<label class="control-label" for="us_dot_number">US DOT</label>
								<input name="us_dot_number" type="text" class="form-control" id="us_dot_number" value="<?= $client_us_dot_number[1] ?>">
							</div>
							<div class="form-group col-sm-12 col-md-6 text-center">
								<label class="control-label" for="ein_number">EIN</label>
								<input name="ein_number" type="text" class="form-control" id="ein_number" value="<?= $client_ein_number[1] ?>">
							</div>
							<div class="form-group col-sm-12 col-md-6 text-center">
								<label class="control-label" for="chr_t">CHR T</label>
								<input name="chr_t" type="number" max="9999999" class="form-control" id="chr_t" value="<?= $client_chr_t[1] ?>">
							</div>
							<div class="form-group col-sm-12 col-md-6 text-center">
								<label class="control-label" for="scac_code">SCAC Code</label>
								<input name="scac_code" type="text" class="form-control" id="scac_code" value="<?= $client_scac_code[1] ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="main_contact">Main contact</label>
							<input name="main_contact" type="text" class="form-control" id="main_contact" value="<?= $client_main_contact[1] ?>">
						</div>
						<div class="form-group">
							<label class="control-label" for="phone_number_01">Phone number 1</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<input name="phone_number_01" type="text" class="form-control" id="phone_number_01" value="<?= $client_phone_number_01[1] ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone_number_02">Phone number 2</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<input name="phone_number_02" type="text" class="form-control" id="phone_number_02" value="<?= $client_phone_number_02[1] ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="phone_number_03">Phone number 3 (per safer)</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<input name="phone_number_03" type="text" class="form-control" id="phone_number_03" value="<?= $client_phone_number_03[1] ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="control-label" for="formation_date">Formation date (mm/dd/yyyy)</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="formation_date" class="form-control" id="formation_date" value="<?= $client_formation_date_1[1] ?>">
							</div>
						</div>
						<div class="form-group hidden">
							<div class="row">
								<div class="col-sm-12 col-md-6">
									<label class="control-label">Rate</label>
									<select name="rate_id" class="form-control">
										<?= $client_rate_id[1] == 0 ? '<option></option>' : '' ?>
										<?php for ($i=1; $i <= $company_rate_count ; $i++) { ?>
											<option value="<?= $company_rate_data_id[$i] ?>"<?= $client_rate_id[1] == $company_rate_data_id[$i] ? ' selected' : '' ?>><?= $company_rate_processing_fee[$i] != NULL ? '$ ' . (number_format($company_rate_rate[$i] + ($company_rate_rate[$i] / 100 * $company_rate_processing_fee[$i]), 2)) . ' - ' . $company_rate_title[$i] . ' + processing fee' : '$ ' . $company_rate_rate[$i] . ' - ' . $company_rate_title[$i] ?></option> <?php
										} ?>
									</select>
								</div>
								<div class="col-sm-12 col-md-6">
									<label class="control-label">Invoice color</label>
									<input name="invoice_color" type="text" class="form-control text-center" value="<?= $client_invoice_color[1] ?>"<?= strlen($client_invoice_color[1]) == 6 ? 'style="background-color: #' . $client_invoice_color[1] . '; color: #fff"' : '' ?>>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Status</label>
							<div class="radio">
								<input type="radio" name="status" id="status1" value="1"<?= $client_status[1] == 1 ? ' checked=""' : '' ; ?><?= !isset($owner_driver_activate) ? 'disabled' : '' ?>>
								<label for="status1">
									<?= !isset($owner_driver_activate) ? '<span style="text-decoration: line-through; color: #ccc">Active</span> <i data-toggle="tooltip" data-placement="top" title="Owner and/or drivers missing" class="fa fa-question-circle"></i>' : 'Active' ?>
								</label>
							</div>
							<div class="radio">
								<input type="radio" name="status" id="status2" value="0"<?= $client_status[1] == 0 ? ' checked=""' : '' ; ?>>
								<label for="status2">
									Inactive
								</label>
							</div>
						</div>
						<div class="col-sm-6 col-md-12">
							<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div>
						<input type="hidden" name="_controller_<?= $this_file_name ?>" value="update_client">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
		  	</div> <?php
	  	}
	  	
	  	# Hide panel-footer if not updating or if updating something other than this section
	  	if (!isset($_GET['edit_main'])) { ?>

	  		<div class="panel-footer" style="max-height: 41px;">
		  		<small>
							<?php
							if (!isset($_GET['delete_client'])) {
								
								echo ${$this_file_name_underscore . '_status'}[1] == 1 ? '<span class="green"><b>' . $core_language[82] . '</b></span>' : '<span class="red"><span class="fa fa-warning"></span> ' . $core_language[83] . '</span>';
							} ?>

							<span>
								
								<?php 
								echo !isset($_GET['delete_client']) ? '<a href="' . $this_file_name . '?' . $this_id . '&edit_main=1"> <i class="fa fa-pencil"></i></a>' : '';

								if (isset($_GET['delete_client'])) {
									# Display second delete button ?>
									<a href="<?= $this_file_name ?>?<?= $this_file_name ?>_id=<?= $_GET['client_id'] ?>&_controller_client=delete_<?= $this_file_name ?>" class="btn btn-link red"><span class="fa fa-trash-o"></span> Delete</a> <?php
								} else {
									# Display first delete button ?>
									<a href="<?= $this_file_name ?>?<?= $this_file_name ?>_id=<?= $_GET['client_id'] ?>&delete_client=1" class="btn btn-link red"><span class="fa fa-trash-o"></span></a><?php
								} ?>

							</span>
					</small>
				</div> <?php
	  	}
	  	?>

		</div>
	</div>

	<div class="col-md-3 col-sm-6">

		<?php 

		# Add address
	  if (isset($_GET['add_address'])) { ?>

	  	<div class="panel panel-info">
				<div class="panel-heading">
					<p>
			    	Add address
 			    	<small class="pull-right" style="margin-top: 10px;">* required</small>
			    </p>
			  </div>
		  	<div class="panel-body">
						
					<form action="" method="post">
				  	<div class="form-group">
				    	<label class="sr-only">Address type</label>
							<select name="address_type" class="form-control">
								<option value="">* Address type</option>
								<option value="1">Physical</option>
								<option value="2">Mailing</option>
							</select>
						</div>
						<div class="form-group">
							<label class="sr-only">Line 1</label>
							<input type="text" name="line_1" class="form-control" placeholder="* Line 1" value="<?= ${$this_file_name_underscore . '_company_name'}[1] ?>">
						</div>
						<div class="form-group">
							<label class="sr-only">Line 2</label>
							<input type="text" name="line_2" class="form-control" placeholder="* Line 2">
						</div>
						<div class="form-group">
							<label class="sr-only">Line 3</label>
							<input type="text" name="line_3" class="form-control" placeholder="Line 3">
						</div>
						<div class="row">
							<div class="form-group col-sm-12 col-md-4">
								<label class="sr-only">City</label>
								<input type="text" name="city" class="form-control" placeholder="* City">
							</div>
							<div class="form-group col-sm-12 col-md-4">
								<label class="sr-only">State</label>
								<select name="state_id" style="width:100%" id="state_selector" class="form-control">
									<option>* State</option>
									<?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
										<option value="<?= $i ?>"><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
									} ?>
								</select>
							</div>
							<div class="form-group col-sm-12 col-md-4">
								<label class="sr-only">Zip code</label>
								<input type="text" name="zip_code" class="form-control" placeholder="* Zip code">
							</div>
							<div class="form-group">
								<button class="btn btn-link"><i class="fa fa-plus"></i> Add address</button>
								<a class="red btn btn-link pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
							</div>
						</div>
						<input type="hidden" name="_controller_<?= $this_file_name_underscore ?>" value="add_address">
				    <input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>

				</div>
			</div> <?php
	  } else {

	  	# Display address data
			if (${$this_file_name_underscore . '_address_count'}) { ?>

				<div class="panel panel-<?= isset($_POST['edit_address']) || isset($_GET['add_address']) || isset($_GET['delete_address']) ? 'danger' : 'default' ?>">
					<div class="panel-heading">
				    <p>
				    	<?= isset($_POST['edit_address']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>
				    	<?= isset($_GET['add_address']) ? '<b><i class="fa fa-warning"></i> ADDING NEW </b>' : ''?>
				    	<?= isset($_GET['delete_address']) ? '<b><i class="fa fa-warning"></i> DELETING </b>' : ''?>
				    	Address
				    	<?= isset($_GET['add_address']) ? '<small class="pull-right" style="margin-top: 10px;">* required</small>' : '' ?>
				    </p>
				  </div>

				  <?php

				  # Update address
				  if (isset($_POST['edit_address'])) { ?>

				  	<div class="panel-body">
					  	<form action="" method="post">
						  	<div class="form-group">
						    	<label class="sr-only">Address type</label>
									<select name="address_type" class="form-control">
										<option value="">* Address type</option>
										<option value="1"<?= $_POST['address_type'] == 1 ? 'selected' : '' ?>>Physical</option>
										<option value="2"<?= $_POST['address_type'] == 2 ? 'selected' : '' ?>>Mailing</option>
									</select>
								</div>
								<div class="form-group">
									<label class="sr-only">Line 1</label>
									<input type="text" name="line_1" class="form-control" placeholder="* Line 1" value="<?= $_POST['line_1'] ?>">
								</div>
								<div class="form-group">
									<label class="sr-only">Line 2</label>
									<input type="text" name="line_2" class="form-control" placeholder="* Line 2" value="<?= $_POST['line_2'] ?>">
								</div>
								<div class="form-group">
									<label class="sr-only">Line 3</label>
									<input type="text" name="line_3" class="form-control" placeholder="Line 3" value="<?= $_POST['line_3'] ?>">
								</div>
								<div class="row">
									<div class="form-group col-sm-12 col-md-4">
										<label class="sr-only">City</label>
										<input type="text" name="city" class="form-control" placeholder="* City" value="<?= $_POST['city'] ?>">
									</div>
									<div class="form-group col-sm-12 col-md-4">
										<label class="sr-only">State</label>
										<select name="state_id" style="width:100%" id="state_selector" class="form-control">
											<option>* State</option>
											<?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
												<option value="<?= $i ?>"<?= $i == $_POST['state_id'] ? 'selected' : '' ?>><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
											} ?>
										</select>
									</div>
									<div class="form-group col-sm-12 col-md-4">
										<label class="sr-only">Zip code</label>
										<input type="text" name="zip_code" class="form-control" placeholder="* Zip code" value="<?= $_POST['zip_code'] ?>">
									</div>

									<?php
									# Show only if physical address
									if ($_POST['address_type'] == 1) { ?>
										
										<div class="form-group col-sm-12 col-md-12">

											<div class="checkbox-nice">
												<input type="checkbox" id="checkbox-1"<?= $_POST['mailing_use_physical'] ? ' checked="checked"' : '' ?> name="mailing_use_physical" />
												<label for="checkbox-1">
													Use physical address as mailing
												</label>
											</div>

											<small class="pull-right" style="color: #888; margin-top: 10px;">* required</small>
										</div> <?php
									}
									?>

								  <div class="form-group col-sm-12 col-md-12">
									    <button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
											<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
								  </div>
								</div>

								<input type="hidden" name="address_id" value="<?= $_POST['address_id'] ?>">
								<input type="hidden" name="_controller_<?= $this_file_name_underscore ?>" value="update_address">
		            <input type="hidden" name="token" value="<?= $csrfToken ?>">
							</form>
						</div> <?php
				  }

				  # Delete address
				  if (isset($_GET['delete_address']) && isset($_GET['address_id'])) { ?>
				  	
				  	<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
							
							<p style="line-height: 7px;">
								<b style="<?= ${$this_file_name_underscore . '_address_type'}[1] == 1 ? 'color: #9c27b0;' : 'color: #8bc34a;' ?>"><?= ${$this_file_name_underscore . '_address_line_1'}[1] ?></b> 
							</p>
							<p style="line-height: 7px;"><?= ${$this_file_name_underscore . '_address_line_2'}[1] ?></p>
							<?= ${$this_file_name_underscore . '_address_line_3'}[1] ? '<p style="line-height: 7px;">' . ${$this_file_name_underscore . '_address_line_3'}[1] . '</p>' : '' ?>
							<p style="line-height: 7px;"><?= ${$this_file_name_underscore . '_address_city'}[1] . ', ' . $state_abbr[${$this_file_name_underscore . '_address_state_id'}[1]] . ' ' . ${$this_file_name_underscore . '_address_zip_code'}[1] ?></p><hr>
							<a class="btn btn-link red" href="<?= $this_file_name ?>?<?= $this_id ?>&_controller_<?= $this_file_name_underscore ?>=delete_address&address_id=<?= $_GET['address_id'] ?>"><i class="fa fa-trash	-o"></i> Delete</a>
							<a class="btn btn-link pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div> <?php
				  }

				  # Show address list
				  if (!isset($_GET['add_address']) && !isset($_POST['address_id']) && !isset($_GET['delete_address'])) { ?>
				  	<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
							
							<?php for ($i=1; $i <= ${$this_file_name_underscore . '_address_count'} ; $i++) { ?>

								<p style="line-height: 7px;">
									<b style="<?= ${$this_file_name_underscore . '_address_type'}[$i] == 1 ? 'color: #9c27b0;' : 'color: #8bc34a;' ?>"><?= ${$this_file_name_underscore . '_address_line_1'}[$i] ?></b> 
									<span class="pull-right">
										
										<a onclick="edit_address_form_<?= $i ?>.submit()"<?= isset($_GET['delete_address']) ? ' class="hidden"' : '' ?>><span class="fa fa-pencil"></span></a>

										<a class="red" href="<?= $this_file_name ?>?<?= $this_id ?>&<?= isset($_GET['delete_address']) ? '_controller_' . $this_file_name_underscore : 'delete_address' ?>=<?= isset($_GET['delete_address']) ? 'delete_address' : ${$this_file_name_underscore . '_address_data_id'}[$i] ?>&address_id=<?= ${$this_file_name_underscore . '_address_data_id'}[$i] ?>"<?= ${$this_file_name_underscore . '_address_count'} === 1 ? ' data-toggle="tooltip" data-placement="bottom" title="This is the only item on this list, deleting it will set this factoring company as inactive."' : '' ?>>
											<?= !isset($_GET['delete_address']) ? '<span class="fa fa-trash-o"></span> ' : '' ?>
											<?= isset($_GET['delete_address']) && $_GET['delete_address'] == ${$this_file_name_underscore . '_address_data_id'}[$i] ? ' Delete' : '' ?>
										</a>
										<a class="btn btn-link<?= isset($_GET['delete_address']) && $_GET['delete_address'] == ${$this_file_name_underscore . '_address_data_id'}[$i] ? '' : ' hidden' ?>" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>

										<form action="" method="post" name="edit_address_form_<?= $i ?>">
											<input type="hidden" name="address_id" value="<?= ${$this_file_name_underscore . '_address_data_id'}[$i] ?>">
											<input type="hidden" name="address_type" value="<?= ${$this_file_name_underscore . '_address_type'}[$i] ?>">
											<input type="hidden" name="line_1" value="<?= ${$this_file_name_underscore . '_address_line_1'}[$i] ?>">
											<input type="hidden" name="line_2" value="<?= ${$this_file_name_underscore . '_address_line_2'}[$i] ?>">
											<input type="hidden" name="line_3" value="<?= ${$this_file_name_underscore . '_address_line_3'}[$i] ?>">
											<input type="hidden" name="city" value="<?= ${$this_file_name_underscore . '_address_city'}[$i] ?>">
											<input type="hidden" name="state_id" value="<?= ${$this_file_name_underscore . '_address_state_id'}[$i] ?>">
											<input type="hidden" name="zip_code" value="<?= ${$this_file_name_underscore . '_address_zip_code'}[$i] ?>">
											<input type="hidden" name="mailing_use_physical" value="<?= ${$this_file_name_underscore . '_address_mailing_use_physical'}[$i] ?>">
											<input type="hidden" name="edit_address" value="1">
										</form>
									</span>
								</p>
								<p style="line-height: 7px;"><small><i><?= ${$this_file_name_underscore . '_address_line_2'}[$i] ?></i></small></p>
								<?= ${$this_file_name_underscore . '_address_line_3'}[$i] ? '<p style="line-height: 7px;"><small></i>' . ${$this_file_name_underscore . '_address_line_3'}[$i] . '</i></small></p>' : '' ?>
								<p style="line-height: 7px;"><small><i><?= ${$this_file_name_underscore . '_address_city'}[$i] . ', ' . $state_abbr[${$this_file_name_underscore . '_address_state_id'}[$i]] . ' ' . ${$this_file_name_underscore . '_address_zip_code'}[$i] ?></i></small></p><hr> <?php
							} ?>

						</div> <?php
				  }

				  # If not updating/adding/deleting
				  if (!isset($_POST['address_id']) && !isset($_GET['delete_address'])) { ?>

				  	<div class="panel-footer">

						  <div class="row">
						  	<div class="col-sm-12 col-md-12 text-right">
						  		<a href="<?= $this_file_name ?>?<?= $this_id ?>&add_address=1"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Add new address"></i></a>
						  	</div>
						  </div>
					  </div> <?php
				  }
				  ?>

				</div> <?php
			} else {

				# Display no address warning ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<p>Address</p>
					</div>
				  
				  <div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
				  	<div class="text-center">
				  		<p class="red"><i class="fa fa-warning"></i> There is no address data to show.</p>
							<span class="red"><a class="btn btn-link" href="<?= $this_file_name ?>?<?= $this_id ?>&add_address=1"><i class="fa fa-plus"></i> Add first item</a></span>
				  	</div>
					</div>
				</div> <?php
			}
	  } ?>

	</div>

	<div class="col-md-3 col-sm-6">

		<?php

		# Add broker association
	  if (isset($_GET['add_broker_assoc'])) { ?>

	  	<div class="panel panel-info">
				<div class="panel-heading">
					<p>
			    	Add broker association
			    	<small class="pull-right" style="margin-top: 10px;">* required</small>
			    </p>
			  </div>

		  	<div class="panel-body">
		  		
			  	<form action="" method="post">
			  		<div class="form-group">
				  		<select name="broker_id" class="form-control" onchange="this.form.submit()">
								<option value="0">Choose a broker</option>

								<?php for ($i=1; $i <= $broker_count ; $i++) { ?>
									<option value="<?= $broker_data_id[$i] ?>"<?= isset($_POST['broker_id']) ? ($broker_data_id[$i] == $_POST['broker_id'] ? 'selected="selected"' : '') : '' ?>><?= $broker_company_name[$i] ?></option> <?php
								} ?>

							</select>
			  		</div>

			  		<?php
			  		if (isset($_POST['broker_id'])) { ?>
			  			
			  			<div class="form-group">
				  			<select name="quickpay_service_fee_id" class="form-control">
									<option>Choose a payment option</option>
									
									<?php for ($i=1; $i <= $loader_quickpay_service_fee_count ; $i++) { ?>
										
										<option value="<?= $quickpay_service_fee_data_id[$i] ?>"><?= $quickpay_service_fee[$i] . '% via ' . $quickpay_method_of_payment_method[$quickpay_service_method_id[$i]] . ' (' . ($quickpay_service_number_of_days[$i] == 0 ? 'Same day' : ($quickpay_service_number_of_days[$i] . ' day' . ($quickpay_service_number_of_days[$i] == 1 ? '' : 's')))  . ') ' ?></option> <?php
									} ?>

								</select>
				  		</div>

				  		<div class="form-group">
				  			<input type="number" min="1" name="counter" placeholder="Quickpay invoice counter (optional)" class="form-control">
				  		</div> <?php
			  		}
			  		?>

			  		<div class="col-sm-12 col-md-12">

							<?= isset($_POST['broker_id']) ? '<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>' : '' ?>

							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div>

						<?php
			  		if (isset($_POST['broker_id'])) { ?>

							<input type="hidden" name="_controller_<?= $this_file_name ?>" value="add_broker_assoc">
							<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
						} ?>
			  	</form>

		  	</div>
		  </div> <?php
	  } else {

	  	# Display broker association data
			if (${$this_file_name_underscore . '_broker_assoc_count'}) { ?>

				<div class="panel panel-<?= isset($_GET['edit_broker_assoc']) || isset($_GET['delete_broker_assoc']) ? 'danger' : 'default' ?>">
					<div class="panel-heading">
						<p>
				    	<?= isset($_GET['edit_broker_assoc']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>
				    	<?= isset($_GET['delete_broker_assoc']) ? '<b><i class="fa fa-warning"></i> DELETING </b>' : ''?>
				    	Broker association
				    </p>
				  </div>

				  <?php

				  # Delete broker association
				  if (isset($_GET['delete_broker_assoc']) && isset($_GET['broker_assoc_id'])) { ?>

				  	<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

				  		<p style="line-height: 7px;">
								<small>
									<i>
										
										<?= $broker_id_company_name[$client_broker_assoc_broker_id[1]] ?><br><br>
										<?= str_replace('.00', '', $quickpay_service_fee_did[$client_broker_assoc_quickpay_service_fee_id[1]]) . '% - ' . $quickpay_method_of_payment_method[$quickpay_service_method_id_did[$client_broker_assoc_quickpay_service_fee_id[1]]] . ' - ' . ($quickpay_service_number_of_days_did[$client_broker_assoc_quickpay_service_fee_id[1]] == 0 ? 'Same day' : ($quickpay_service_number_of_days_did[$client_broker_assoc_quickpay_service_fee_id[1]] . ' day' . ($quickpay_service_number_of_days_did[$client_broker_assoc_quickpay_service_fee_id[1]] == 1 ? '' : 's'))) ?>

									</i>
								</small>
							</p><hr>

							<a class="btn btn-link red" href="<?= $this_file_name ?>?<?= $this_id ?>&_controller_<?= $this_file_name_underscore ?>=delete_broker_assoc&broker_assoc_id=<?= $_GET['broker_assoc_id'] ?>"><i class="fa fa-trash-o"></i> Delete</a>
							<a class="btn btn-link pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>

						</div> <?php
				  }

				  # Show broker association list
				  if (!isset($_GET['add_broker_assoc']) && !isset($_GET['broker_assoc_id']) && !isset($_GET['delete_broker_assoc'])) { ?>

				  	<div class="table-responsive" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
				  		<table class="table table-hover">
				  			<tbody>

						  		<?php
							  	for ($i=1; $i <= ${$this_file_name . '_broker_assoc_count'} ; $i++) { ?>

							  		<tr>
											<td>
												
												<small>
													<?= $broker_id_company_name[$client_broker_assoc_broker_id[$i]] ?><br>
													<?= str_replace('.00', '', $quickpay_service_fee_did[$client_broker_assoc_quickpay_service_fee_id[$i]]) . '% - ' . $quickpay_method_of_payment_method[$quickpay_service_method_id_did[$client_broker_assoc_quickpay_service_fee_id[$i]]] . ' - ' . ($quickpay_service_number_of_days_did[$client_broker_assoc_quickpay_service_fee_id[$i]] == 0 ? 'Same day' : ($quickpay_service_number_of_days_did[$client_broker_assoc_quickpay_service_fee_id[$i]] . ' day' . ($quickpay_service_number_of_days_did[$client_broker_assoc_quickpay_service_fee_id[$i]] == 1 ? '' : 's'))) ?>
												</small>

											</td>
											<td>
												<a class="red btn btn-link" href="<?= $this_file_name ?>?<?= $this_id ?>&delete_broker_assoc=1&broker_assoc_id=<?= ${$this_file_name . '_broker_assoc_data_id'}[$i] ?>"><i class="fa fa-trash-o"></i></a>
											</td>
										</tr> <?php
									}
							  	?>

							  </tbody>
					  	</table>
						</div> <?php
				  }

				  # If not updating/adding/deleting
				  if (!isset($_GET['broker_assoc_id']) && !isset($_GET['add_broker_assoc']) && !isset($_GET['delete_broker_assoc'])) { ?>

				  	<div class="panel-footer">

						  <div class="row">
						  	<div class="col-sm-12 col-md-12 text-right">
						  		<a href="<?= $this_file_name ?>?<?= $this_id ?>&add_broker_assoc=1"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Add new broker association"></i></a>
						  	</div>
						  </div>
					  </div> <?php
				  }
				  ?>

				</div> <?php
			} else {

				# Display no broker association warning ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<p>Broker association</p>
					</div>
				  
				  <div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
				  	<div class="text-center">
				  		<p class="red"><i class="fa fa-warning"></i> There is no broker association data to show.</p>
							<span class="red"><a class="btn btn-link" href="<?= $this_file_name ?>?<?= $this_id ?>&add_broker_assoc=1"><i class="fa fa-plus"></i> Add first item</a></span>
				  	</div>
					</div>
				</div> <?php
			}
	  } ?>

	</div>

	<div class="col-md-3 col-sm-6">

		<?php

		# Add client user
	  if (isset($_GET['add_client_user'])) { ?>

	  	<div class="panel panel-info">
				<div class="panel-heading">
					<p>
			    	Add client user
			    	<small class="pull-right" style="margin-top: 10px;">* required</small>
			    </p>
			  </div>

		  	<div class="panel-body">
		  		
			  	<form action="" method="post">

			  		<?php
						if (!isset($_POST['user_id'])) {

							# First step ?>
							
							<div class="form-group">
								<select name="user_id" class="form-control" onchange="this.form.submit()">
									<option>* Choose a user</option>
									
									<?php for ($i = 1; $i <= $available_external_user_count ; $i++) { ?>

										<option value="<?= $available_external_user_id_user_id[$i] ?>"<?= isset($_POST['user_id']) ? $available_external_user_id_user_id[$i] == $_POST['user_id'] ? 'selected' : '' : '' ?>><?= $available_external_user_id_name[$i] . ' ' . $available_external_user_id_last_name[$i] ?></option> <?php
									} ?>

								</select>
							</div>
							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a> <?php 

						} else {

							# Second step ?>

							<p class="text-center"><?= $available_external_user_name[$_POST['user_id']] . ' ' . $available_external_user_last_name[$_POST['user_id']] ?></p>

							<div class="form-group">
								<select name="user_type" id="user_type" class="form-control">
									<option value="">* User type</option>
									<option value="9">Owner</option><!-- "9" used instead of "0" to pass validation -->
									<option value="1">Owner/Operator</option>
									<?= $client_user_count == 0 ? '' : '<option value="2">Driver</option>' ?>
								</select>
							</div>

							<?php 
							# Show only when there is already an owner or owner/operator
							if ($client_user_count != 0) { ?>

								<div class="form-group hidden" id="user_manager_holder">
									<select name="user_manager" class="form-control" id="user_type">
										<option value="">User manager</option>

										<?php

										# Loop through managers
										for ($i=1; $i <= $client_manager_count ; $i++) { ?>
											<option value="<?= $client_manager_user_id[$i] ?>"<?= $client_manager_count == 1 ? ' selected' : '' ?>><?= $user_list_id_name[$client_manager_user_id[$i]] . ' ' . $user_list_id_last_name[$client_manager_user_id[$i]] ?></option> <?php
										} ?>

									</select>
								</div> <?php
							} ?>

							<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
							<input type="hidden" name="user_id" value="<?= $_POST['user_id'] ?>">
							<input type="hidden" name="_controller_<?= $this_file_name ?>" value="add_client_user">
							<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
						}
						?>

					</form>

		  	</div>
		  </div> <?php
	  } else {

	  	# Display client user data
			if (${$this_file_name_underscore . '_user_count'}) { ?>

				<div class="panel panel-<?= isset($_GET['edit_client_user']) || isset($_GET['delete_client_user']) ? 'danger' : 'default' ?>">
					<div class="panel-heading">
						<p>
				    	<?= isset($_GET['edit_client_user']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>
				    	<?= isset($_GET['delete_client_user']) ? '<b><i class="fa fa-warning"></i> DELETING </b>' : ''?>
				    	Client user
				    </p>
				  </div>

				  <?php

				  # Delete client user
				  if (isset($_GET['delete_client_user']) && isset($_GET['client_user_id'])) { ?>

				  	<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

				  		<p style="line-height: 7px;">
								<small>
									<i>
										
										<?= $user_list_id_name[$_GET['client_user_id']] . ' ' . $user_list_id_last_name[$_GET['client_user_id']] ?>

									</i>
								</small>
							</p><hr>

							<a class="btn btn-link red" href="<?= $this_file_name ?>?<?= $this_id ?>&_controller_<?= $this_file_name_underscore ?>=delete_client_user&client_user_id=<?= $_GET['client_user_id'] ?>"><i class="fa fa-trash-o"></i> Delete</a>
							<a class="btn btn-link pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>

						</div> <?php
				  }

				  # Show client user list
				  if (!isset($_GET['add_client_user']) && !isset($_GET['client_user_id']) && !isset($_GET['delete_client_user'])) { ?>

				  	<div class="table-responsive" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
							<table class="table table-hover">
								<tbody>							

						  		<?php
							  	for ($i=1; $i <= ${$this_file_name . '_user_count'} ; $i++) { ?>

							  		<tr>
											<td>
												
												<small><?= $user_list_id_name[$client_user_user_id[$i]] . ' ' . $user_list_id_last_name[$client_user_user_id[$i]] ?></small>

											</td>
											<td>

												<small>
													<?= $client_user_user_type[$i] == 0 ? 'Owner' : ($client_user_user_type[$i] == 1 ? 'Owner/operator' : 'Driver'); ?>
													<?= $client_user_user_manager[$i] && $client_user_user_type[$i] == 2 ? ' <span class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" title="Managed by ' . $user_list_id_name[$client_user_user_manager[$i]] . ' ' . $user_list_id_last_name[$client_user_user_manager[$i]] . '"></span>' : '' ?>
												</small>

											</td>
											<td>
												<?php
												# Show if user is owner/owner/operator
												if ($client_user_user_type[$i] == 0 || $client_user_user_type[$i] == 1) {

													# Show only if there is no driver count under this manager
													# If there is driver count, this user (manager) cannot be deleted
													if (!$is_user_manager_count[$i]) { ?>
													
														<span> 
															<a class="red" href="<?= $this_file_name ?>?<?= $this_id ?>&delete_client_user=1&client_user_id=<?= $client_user_user_id[$i] ?>"><i class="fa fa-trash-o"></i></a>
														</span> <?php
													}
												}	elseif ($client_user_user_type[$i] == 2) { ?>

													<span> 
														<a class="red" href="<?= $this_file_name ?>?<?= $this_id ?>&delete_client_user=1&client_user_id=<?= $client_user_user_id[$i] ?>"><i class="fa fa-trash-o"></i></a>
													</span> <?php 
												}	
												?>
											</td>
											
											<td>
												<a href="client?client_id=<?= $_GET['client_id'] ?>&user_id=<?= $client_user_user_id[$i] ?>" data-toggle="tooltip" data-placement="top" title="Edit user data"> 
													
													<i class="fa fa-pencil"></i>
												</a>
											</td>
												
										</tr> <?php
									} 
							  	?>

					  		</tbody>
							</table>

						</div> <?php
				  }

				  # If not updating/adding/deleting
				  if (!isset($_GET['client_user_id']) && !isset($_GET['add_client_user']) && !isset($_GET['delete_client_user'])) { ?>

				  	<div class="panel-footer">

						  <div class="row">
						  	<div class="col-sm-12 col-md-12 text-right">
						  		<a href="<?= $this_file_name ?>?<?= $this_id ?>&add_client_user=1"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Add client user"></i></a>
						  	</div>
						  </div>
					  </div> <?php
				  }
				  ?>

				</div> <?php
			} else {

				# Display no client user warning ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<p>client user</p>
					</div>
				  
				  <div class="panel-body">
				  	<div class="text-center">
				  		<p class="red"><i class="fa fa-warning"></i> There is no client user data to show.</p>
							<span class="red"><a class="btn btn-link" href="<?= $this_file_name ?>?<?= $this_id ?>&add_client_user=1"><i class="fa fa-plus"></i> Add first item</a></span>
				  	</div>
					</div>
				</div> <?php
			}
	  } ?>

	</div>
</div>

<!-- This row hides when editing driver data -->
<div class="row<?= $_GET['user_id'] ? ' hidden' : '' ?>">
	
	<div class="col-md-3 col-sm-6">

		<?php 

		# Add factoring company
	  if (isset($_GET['add_factoring_company'])) { ?>

	  	<div class="panel panel-info">
				<div class="panel-heading">
					<p>
			    	Add factoring company
 			    	<small class="pull-right" style="margin-top: 10px;">* required</small>
			    </p>
			  </div>
		  	<div class="panel-body">
						
					<form action="" method="post"<?= isset($_POST['main']) && $factoring_company_id_requires_soar[$_POST['factoring_company_id']] ? ' enctype="multipart/form-data"' : '' ?>>

						<?php
						if (!isset($_POST['factoring_company_id'])) {
							
							# First step
							# Shows factoring company prompt only ?>

							<div class="form-group">
								<select name="factoring_company_id" class="form-control" onchange="this.form.submit()">
									<option value="">* Choose company</option>

									<?php if ($factoring_company_count) {
										for ($i = 1; $i <= $factoring_company_count ; $i++) {

											# Only let pass if factoring company is active
											if ($factoring_company_status[$i] == 1) { ?>

												<option value="<?= $factoring_company_id[$i] ?>"><?= $factoring_company_name[$i] ?></option> <?php
											}
										}
									} ?>

								</select>
							</div>
							<div class="form-group">
									<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
								</div> <?php
						} else {

							# Second step
							# Shows non-editable factoring company
							# Shows primary service fee option prompt ?>

							<p class="text-center"><?= $factoring_company_id_name[$_POST['factoring_company_id']] ?></p>

							<div class="form-group">
								<select<?= !isset($_POST['main']) ? ' name="main"' : ' disabled' ?> class="form-control" onchange="this.form.submit()">
									<option value="">Primary service fee option</option>
									
									<?php if ($factoring_company_service_fee_count) {
										for ($i = 1; $i <= $factoring_company_service_fee_count ; $i++) {

											# Only let pass this factoring company's data
											if ($factoring_company_service_fee_factoring_company_id[$i] == $_POST['factoring_company_id'] ) { ?>

												<option value="<?= $factoring_company_service_fee_data_id[$i] ?>"<?= isset($_POST['main']) ? $factoring_company_service_fee_data_id[$i] == $_POST['main'] ? ' selected' : '' : '' ?>><?= $factoring_company_service_fee_fee[$i] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id[$i]] . ' [' . ($factoring_company_service_fee_number_of_days[$i] > 0 ? $factoring_company_service_fee_number_of_days[$i] : '') . ($factoring_company_service_fee_number_of_days[$i] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days[$i] > 1 ? ' days' : ' day')) . ']' ?></option> <?php
											}
										}
									} ?>

								</select>
							</div>
							<div class="form-group">
								<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
							</div> <?php

							if (isset($_POST['main'])) {
								
								# Third step
								# Shows non-editable factoring company
								# Shows non-editable primary service fee option
								# Shows Alternate service fee option
								# Shows Next schedule counter
								# Shows Next invoice counter
								# Shows Requires soar prompt? ?>

								<div class="form-group">
									<select name="alt" class="form-control">
										<option value="">Alternate service fee option</option>

										<?php 
										if ($factoring_company_service_fee_count) {
											
											for ($i = 1; $i <= $factoring_company_service_fee_count ; $i++) {

												# Only let pass this factoring company's data
												if ($factoring_company_service_fee_factoring_company_id[$i] == $_POST['factoring_company_id'] ) {
													
													# Only let pass those different from main service fee chosen
													if ($factoring_company_service_fee_data_id[$i] != $_POST['main']) { ?>

														<option value="<?= $factoring_company_service_fee_data_id[$i] ?>"><?= $factoring_company_service_fee_fee[$i] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_method_id[$i]] . ' [' . ($factoring_company_service_fee_number_of_days[$i] > 0 ? $factoring_company_service_fee_number_of_days[$i] : '') . ($factoring_company_service_fee_number_of_days[$i] == 0 ? 'Same day' : ($factoring_company_service_fee_number_of_days[$i] > 1 ? 'days' : 'day')) . ']' ?></option> <?php
													}
												}
											}
										} ?>

									</select>
								</div>

								<div class="form-group">
									<input data-toggle="tooltip" data-placement="top" title="Enter the next schedule number to be used" type="number" name="counter" min="1" class="form-control" placeholder="Next schedule counter">
								</div>

								<div class="form-group">
									<input data-toggle="tooltip" data-placement="top" title="Enter the next invoice number to be used" type="number" name="invoice_counter" min="1" class="form-control" placeholder="Next invoice counter">
								</div>

								<?php
								# Requires soar?
								if ($factoring_company_id_requires_soar[$_POST['factoring_company_id']]) { ?>
														
									<div class="form-group">
										<label>Empty soar file</label>
										<input type="file" name="invoice_background" accept="image/jpg" class="btn btn-default btn-block">
									</div><?php
								} ?>

								<div class="form-group">
									<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
									<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
								</div> <?php
							}
						}

						echo isset($_POST['factoring_company_id']) ? '<input type="hidden" name="factoring_company_id" value="' . $_POST['factoring_company_id'] . '">' : '';
						
						if (isset($_POST['main'])) { ?>
							
							<input type="hidden" name="main" value="<?= $_POST['main'] ?>">
							<input type="hidden" name="_controller_<?= $this_file_name ?>" value="add_client_factoring_company">
							<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
						} ?>
					</form>

				</div>
			</div> <?php
	  } else {

	  	# Display factoring company data
			if ($factoring_company_client_assoc_count) { ?>

				<div class="panel panel-<?= isset($_GET['edit_factoring_company']) || isset($_GET['add_factoring_company']) || isset($_GET['delete_factoring_company']) ? 'danger' : 'default' ?>">
					<div class="panel-heading">
				    <p>
				    	<?= isset($_GET['edit_factoring_company']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>
				    	<?= isset($_GET['add_factoring_company']) ? '<b><i class="fa fa-warning"></i> ADDING NEW </b>' : ''?>
				    	<?= isset($_GET['delete_factoring_company']) ? '<b><i class="fa fa-warning"></i> DELETING </b>' : ''?>
				    	Factoring company
				    	<?= isset($_GET['add_factoring_company']) ? '<small class="pull-right" style="margin-top: 10px;">* required</small>' : '' ?>
				    </p>
				  </div>

				  <?php

				  # Update factoring company
				  if (isset($_GET['edit_factoring_company'])) { ?>

				  	<!-- PENDING --> <?php
				  }

				  # Delete factoring company
				  if (isset($_GET['delete_factoring_company']) && isset($_GET['factoring_company_id'])) { ?>
				  	
				  	<!-- PENDING --> <?php
				  }

				  # Show factoring company data
				  if (!isset($_GET['factoring_company_id']) && !isset($_GET['delete_factoring_company'])) { ?>

				  	<ul class="list-group" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

				  		<li href="#" class="list-group-item">
					  		<small>
									
									<b><?= $factoring_company_id_name[$factoring_company_client_assoc_factoring_company_id] ?></b>
								</small>
							</li>

							<li href="#" class="list-group-item">
					  		<small>
									
									<b>Main</b>
									<br>
									<?= $factoring_company_service_fee_id_fee[$factoring_company_client_assoc_main] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_id_method_id[$factoring_company_client_assoc_main]] . ' [' . ($factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_main] == 0 ? 'Same day' : ($factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_main] == 1 ? $factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_main] . ' day' : $factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_main] . ' days')) ?>]
								</small>
							</li>

							<li href="#" class="list-group-item">
					  		<small>
									
									<b>Alternate</b>
									<br>
									<?= $factoring_company_service_fee_id_fee[$factoring_company_client_assoc_alt] . '% via ' . $quickpay_method_of_payment_method[$factoring_company_service_fee_id_method_id[$factoring_company_client_assoc_alt]] . ' [' . ($factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_alt] == 0 ? 'Same day' : ($factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_alt] == 1 ? $factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_alt] . ' day' : $factoring_company_service_fee_id_number_of_days[$factoring_company_client_assoc_alt] . ' days')) ?>]
								</small>
							</li>

							<li href="#" class="list-group-item">
					  		<small>
									
									<b>Current <?= $factoring_company_id_batch_schedule[$factoring_company_client_assoc_factoring_company_id] == 1 ? 'batch' : 'schedule' ?> #: </b>
									<br>
									<?= $factoring_company_client_assoc_counter ?>
								</small>
							</li>
						</ul> <?php
				  }

				  # If not updating/adding/deleting
				  if (!isset($_GET['factoring_company_id']) && !isset($_GET['delete_factoring_company'])) { ?>

				  	<div class="panel-footer">
				  	<p class="red"><i class="fa fa-warning"></i> Upgrades pending!</p>
					  </div> <?php
				  }
				  ?>

				</div> <?php
			} else {

				# Display no factoring company warning ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<p>Factoring company</p>
					</div>
				  
				  <div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
				  	<div class="text-center">
				  		<p class="red"><i class="fa fa-warning"></i> There is no factoring company data to show.</p>
							<span class="red"><a class="btn btn-link" href="<?= $this_file_name ?>?<?= $this_id ?>&add_factoring_company=1"><i class="fa fa-plus"></i> Create association</a></span>
				  	</div>
					</div>
				</div> <?php
			}
	  } ?>

	</div>

	<div class="col-md-3 col-sm-6">

		<div class="panel panel-<?= $schedule_all_client_id_count ? 'default' : 'danger' ?>">
			<div class="panel-heading">
				<p>Schedule data</p>
			</div>

			<?php
			# Check if there are schedules to show
			if ($schedule_all_client_id_count) { ?>
				
				<div class="table-responsive" style="max-height: 160px; min-height: 210px; overflow-y: auto;">
					<table class="table">
						<thead>
							<tr>
								<th><span>#</span></th>
								<th class="text-center"><span>Loads</span></th>
								<th class="text-center"><span>Status</span></th>
								<th class="text-right"><span>Added</span></th>
							</tr>
						</thead>
						<tbody>

							<?php
							# Loop through all schedules for this client
							for ($i = 1; $i <= $schedule_all_client_id_count ; $i++) { ?>
								<tr>
									<td>
										<small>
											<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/schedule?schedule_id=<?= $schedule_all_client_id_data_id[$i] ?>"><?= $schedule_all_client_id_counter[$i] ?></a>
										</small>
									</td>
									<td class="text-center">
										<small>
											<?= $schedule_all_client_id_load_count ?>
										</small>
									</td>
									<td class="text-center">
										<small>
											<span class="label label-<?= $schedule_all_client_id_counter[$i] == $schedule_all_client_id_current_counter[$i] ? 'warning' : 'success' ?>"><?= $schedule_all_client_id_counter[$i] == $schedule_all_client_id_current_counter[$i] ? 'Open' : 'Sent' ?></span>
										</small>
									</td>
									<td class="text-right">
										<small>
											<?= $schedule_all_client_id_created[$i] ?>
										</small>
									</td>
								</tr> <?php 
							} ?>

						</tbody>
					</table>
				</div> <?php
			} else { ?>

				<div class="panel-body">

					<span class="red"><i class="fa fa-warning"></i> There is no schedule data to show!</span>
				</div> <?php
			}
			?>

		</div>
	</div>

	<div class="col-md-3 col-sm-6" id="insurance">

		<?php

		# Add Insurance info
	  if (isset($_GET['add_insurance_info']) || isset($_GET['edit_insurance_info'])) { ?>

	  	<div class="panel panel-info">
				<div class="panel-heading">
					<p>
			    	<?= $_GET['add_insurance_info'] ? 'Add' : 'Edit' ?> insurance info
			    	<small class="pull-right" style="margin-top: 10px;">* required</small>
			    </p>
			  </div>

		  	<div class="panel-body">
		  		
			  	<form action="" method="post">
			  		
			  		<div class="form-group">
				  		<select name="insurance_company_id" class="form-control">
								<option value="0">* Choose an insurance company</option>

								<?php for ($i = 1; $i <= $client_insurance_company_count ; $i++) { ?>
									
									<option value="<?= $client_insurance_company_id[$i] ?>"<?= $_GET['edit_insurance_info'] && $client_insurance_insurance_company_id == $client_insurance_company_id[$i] ? ' selected' : '' ?>>

										<?= $client_insurance_company_name[$i] ?>
									</option> <?php
								} ?>

							</select>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="producer" placeholder="* Producer" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_producer . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="producer_phone_number" placeholder="* Producer phone number" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_producer_phone_number . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="producer_fax_number" placeholder="* Producer fax number" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_producer_fax_number . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="producer_email" placeholder="* Producer emails (comma separated)" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_producer_email . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="website" placeholder="Website" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_website . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="website_username" placeholder="Website_username" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_website_username . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="website_password" placeholder="Website_password" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_website_password . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input type="text" name="vin_number" placeholder="Vin numbers (comma separated)" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_vin_number . '"' : '' ?>>
			  		</div>

			  		<hr><h5 class="text-center">Auto insurance</h5>

		  			<div class="form-group">
			  			
			  			<input type="text" name="auto_insurance" placeholder="* Policy #" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_policy_number[1] . '"' : '' ?>>
			  		</div>

		  			<div class="form-group">
			  			
			  			<input type="number" name="auto_insurance_amount" placeholder="* Amount" step="0.01" class="form-control" min="1000000" max="2000000"<?= $_GET['edit_insurance_info'] ? ' value="' . str_replace(',', '', $client_insurance_type_amount[1]) . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">

							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="auto_issuing_date" id="auto_issuing_date" class="form-control" placeholder="* Issuing date (mm/dd/yyyy)"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_issuing_date[1] . '"' : '' ?>>
							</div>
						</div>

						<div class="form-group">

							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="auto_expiration_date" id="auto_expiration_date" class="form-control" placeholder="* Expiration date (mm/dd/yyyy)"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_expiration_date[1] . '"' : '' ?>>
							</div>
						</div>

						<hr><h5 class="text-center">Cargo insurance</h5>

		  			<div class="form-group">
			  			
			  			<input type="text" name="cargo_insurance" placeholder="* Policy #" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_policy_number[2] . '"' : '' ?>>
			  		</div>

		  			<div class="form-group">
			  			
			  			<input type="number" name="cargo_insurance_amount" placeholder="* Amount" class="form-control" min="100000"<?= $_GET['edit_insurance_info'] ? ' value="' . str_replace(',', '', $client_insurance_type_amount[2]) . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">

							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="cargo_issuing_date" id="cargo_issuing_date" class="form-control" placeholder="* Issuing date (mm/dd/yyyy)"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_issuing_date[2] . '"' : '' ?>>
							</div>
						</div>

						<div class="form-group">

							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="cargo_expiration_date" id="cargo_expiration_date" class="form-control" placeholder="* Expiration date (mm/dd/yyyy)"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_expiration_date[2] . '"' : '' ?>>
							</div>
						</div>

			  		<hr><h5 class="text-center">Commercial insurance</h5>

			  		<div class="form-group">
			  			
			  			<input type="text" name="commercial_insurance" placeholder="Policy #" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_policy_number[3] . '"' : '' ?>>
			  		</div>

		  			<div class="form-group">
			  			
			  			<input type="number" name="commercial_insurance_amount" placeholder="Amount" class="form-control"<?= $_GET['edit_insurance_info'] ? ' value="' . str_replace(',', '', $client_insurance_type_amount[3]) . '"' : '' ?>>
			  		</div>

			  		<div class="form-group">

							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="commercial_issuing_date" id="commercial_issuing_date" class="form-control" placeholder="Issuing date (mm/dd/yyyy)"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_issuing_date[3] . '"' : '' ?>>
							</div>
						</div>

						<div class="form-group">

							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" name="commercial_expiration_date" id="commercial_expiration_date" class="form-control" placeholder="Expiration date (mm/dd/yyyy)"<?= $_GET['edit_insurance_info'] ? ' value="' . $client_insurance_type_expiration_date[3] . '"' : '' ?>>
							</div>
						</div>

			  		<div class="col-sm-12 col-md-12">

							<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div>

						<input type="hidden" name="_controller_client_insurance" value="<?= $_GET['add_insurance_info'] ? 'add' : 'edit' ?>_insurance_info">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
			  	</form>

		  	</div>
		  </div> <?php
	  } else {

	  	# Display Insurance info
			if ($client_insurance_count) { ?>

				<div class="panel panel-<?= isset($_GET['edit_client_insurance']) ? 'danger' : 'default' ?>">
					<div class="panel-heading">
						<p>
				    	<?= isset($_GET['edit_client_insurance']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>
				    	Insurance info
				    </p>
				  </div>

				  <ul class="list-group" style="max-height: 170px; min-height: 160px; overflow-y: auto;">

			  		<li href="#" class="list-group-item">
				  		<small>
								
								<b><?= $client_insurance_company_id_name[$client_insurance_insurance_company_id] ?></b>
							</small>
						</li>

						<li href="#" class="list-group-item">
				  		<small>
								
								<b><?= $client_insurance_producer ?></b> <i>[producer]</i>
								<p>
									<span data-toggle="tooltip" data-placement="bottom" title="Phone number">
										<i class="fa fa-phone"></i> <?= $client_insurance_producer_phone_number ?>
									</span>
									<span data-toggle="tooltip" data-placement="bottom" title="Fax number">
										<i class="fa fa-fax"></i> <?= $client_insurance_producer_fax_number ?>
									</span>
								</p>
								<p><b>Email: </b><?= $client_insurance_producer_email ?></p>
								<?= $client_insurance_website ? '<p><b>Website: </b>' . $client_insurance_website . '</p>' : '' ?>
								<?= $client_insurance_website_username ? '<p><b>Username: </b>' . $client_insurance_website_username . '</p>' : '' ?>
								<?= $client_insurance_website_password ? '<p><b>Password: </b>' . $client_insurance_website_password . '</p>' : '' ?>
							</small>
						</li>

						<li href="#" class="list-group-item">
				  		<small>

								<?php
								for ($i = 1; $i <= $client_insurance_type_count ; $i++) {

									# If policy number is blank, it means this is a type 3 insurance (commercial), it is optional.
									if ($client_insurance_type_policy_number[$i]) { ?>
										
										<p>
											<b><?= $type_label[$i] ?> Insurance</b><br> 
											<?= $client_insurance_type_policy_number[$i] ?> 
											($<?= $client_insurance_type_amount[$i] ?>)<br>
											Issued <?= $client_insurance_type_issuing_date[$i] ?><br>
											Expiration <?= $client_insurance_type_expiration_date[$i] ?>
										</p> <?php
									}
								}
								?>
									
							</small>
						</li>

					</ul>

				  <div class="panel-footer">

					  <div class="row">
					  	<div class="col-sm-12 col-md-12 text-right">
					  		
					  		<a href="<?= $this_file_name ?>?<?= $this_id ?>&edit_insurance_info=1">
					  			
					  			<i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit insurance info"></i>
					  		</a>
					  	</div>
					  </div>
				  </div>

				</div> <?php
			} else {

				# Display no Insurance info warning ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<p>Insurance info</p>
					</div>
				  
				  <div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
				  	<div class="text-center">
				  		<p class="red"><i class="fa fa-warning"></i> There is no insurance info.</p>
							<span class="red"><a class="btn btn-link" href="<?= $this_file_name ?>?<?= $this_id ?>&add_insurance_info=1"><i class="fa fa-plus"></i> Add insurance info</a></span>
				  	</div>
					</div>
				</div> <?php
			}
	  } ?>

	</div>

	<div class="col-md-3 col-sm-6" id="invoice">

		<?php

		# Edit invoice settings
	  if (isset($_GET['edit_invoice_settings'])) { ?>

	  	<div class="panel panel-danger">
				<div class="panel-heading">
					<p>
			    	Edit invoice settings
			    	<small class="pull-right" style="margin-top: 10px;">* required</small>
			    </p>
			  </div>

		  	<div class="panel-body">
		  		
			  	<form action="" method="post">
			  		
			  		<div class="form-group">
				  		<select name="rate_type" class="form-control">
								<option value="0"<?= ($client_rate_type[1] && $client_rate[1]) ? ' class="hidden"' : '' ?>>* Rate type</option>

								<option value="1"<?= $client_rate_type[1] == 1 ? ' selected' : '' ?>>Fixed fee</option>
								<option value="2"<?= $client_rate_type[1] == 2 ? ' selected' : '' ?>>Percentage</option>

							</select>
			  		</div>

			  		<div class="form-group">
			  			
			  			<input 
			  				type="number" 
			  				name="rate" 
			  				placeholder="* Rate amount/percentage" 
			  				class="form-control"
			  				<?= $client_rate[1] != '' ? ' value="' . $client_rate[1] . '"' : '' ?>>
			  		</div>

			  		<div class="col-sm-12 col-md-12">

							<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div>

						<input type="hidden" name="_controller_edit_invoice_settings" value="edit_invoice_settings">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
			  	</form>

		  	</div>
		  </div> <?php
	  } else { ?>

	  	<div class="panel panel-default">
				<div class="panel-heading">
					<p>

			    	Invoice settings
			    </p>
			  </div>

			  <ul class="list-group" style="max-height: 170px; min-height: 160px; overflow-y: auto;">

		  		<li href="#" class="list-group-item">
			  		<small>
							
							<?php

							if ($client_rate_type[1] && $client_rate[1]) {
								
								echo ($client_rate_type[1] == 1 ? 'Fixed fee: ' : 'Percentage: ') . ($client_rate_type[1] == 1 ? '$' . number_format($client_rate[1]) : $client_rate[1] . '%');
							} else {

								# Missing data
								echo '<p class="red">Missing data!</p>';
							}
							?>
							
						</small>
					</li>

				</ul>

			  <div class="panel-footer">

				  <div class="row">
				  	<div class="col-sm-12 col-md-12 text-right">
				  		
				  		<a href="<?= $this_file_name ?>?<?= $this_id ?>&edit_invoice_settings=1">
				  			
				  			<i class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Edit invoice settings"></i>
				  		</a>
				  	</div>
				  </div>
			  </div>

			</div> <?php
	  } ?>

	</div>

</div>

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
