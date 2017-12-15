<?php 
session_start();
ob_start();
# Redirect if no query string
if (!$_SERVER['QUERY_STRING']) { ?>
	<script type="text/javascript">
		window.location = "/dashboard/broker/"
	</script> <?php
}

require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = basename(__FILE__, '.php');
$this_file_name_underscore = str_replace('-', '_', basename(__FILE__, '.php'));

if (isset($_GET[$this_file_name_underscore . '_id'])) {
	
	$this_id = $this_file_name_underscore . '_id=' . $_GET[$this_file_name_underscore . '_id'];
}

# Automate address adding process
# Only run if no address count
/*if (!$broker_address_count) {
	
	$add = DB::getInstance()->query("INSERT INTO broker_address (broker_id, address_type, line_1, line_2, line_3, city, state_id, zip_code, user_id) VALUES (" . $broker_data_id[1] . ", 1, '" . htmlentities($broker_address_line_1[1], ENT_QUOTES) . "', '" . htmlentities($broker_address_line_2[1], ENT_QUOTES) . "', '" . htmlentities($broker_address_line_3[1], ENT_QUOTES) . "', '" . htmlentities($broker_city[1], ENT_QUOTES) . "', " . $broker_state_id[1] . ", '" . $broker_zip_code[1] . "', " . $user->data()->id . ")");

	if ($add->count()) {
	  
	  Session::flash($this_file_name, 'Address added successfully');
	  Redirect::to('broker?broker_id=' . $_GET['broker_id']);
	}
}*/

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");
?>

<div class="row">
	<div class="col-md-3 col-sm-6">

		<div class="panel panel-<?= isset($_GET['edit_main']) ? 'danger' : 'primary'?>">
			<div class="panel-heading">
		    <p><?= isset($_GET['edit_main']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>Main</p>
		  </div>

		  <?php
	  	# Data display if [not updating || updating something other than this]
	  	if (!isset($_GET['edit_main'])) { ?>
	  		
	  		<ul class="list-group">
					<li href="#" class="list-group-item"><i class="fa fa-phone"></i> <small><i><?= ${$this_file_name_underscore . '_phone_number_01'}[1] ?></i></small></li>

					<?php
					# Fax number
					if (${$this_file_name_underscore . '_fax_number'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<i class="fa fa-fax"></i> <small><i><?= ${$this_file_name_underscore . '_fax_number'}[1] ?></i></small>
						</li> <?php
					}

					# Quickpay email
					if (${$this_file_name_underscore . '_quickpay_email'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<a href="mailto:<?= ${$this_file_name_underscore . '_quickpay_email'}[1] ?>">
										<?= ${$this_file_name_underscore . '_quickpay_email'}[1] ?>
									</a>
									<span style="color: #888;"> [Quickpay]</span>
								</i>
							</small>
						</li> <?php
					}

					# Accounts payable number
					if (${$this_file_name_underscore . '_accounts_payable_number'}[1]) { ?>
						
						<li href="#" class="list-group-item">
							<small>
								<i>
									<?= ${$this_file_name_underscore . '_accounts_payable_number'}[1] ?> 
									<span style="color: #888;">[Accounts payable]</span>
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
							<label>Company name</label>
							<input name="company_name" class="form-control" type="text" value="<?= $broker_company_name[1] ?>">
						</div>
						<div class="form-group">
							<label>Phone Number</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<input name="phone_number_01" class="form-control" type="text" value="<?= $broker_phone_number_01[1] ?>" id="maskedPhoneNumber">
							</div>
						</div>
						<div class="form-group">
							<label>Fax number</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-fax"></i></span>
								<input name="fax_number" class="form-control" type="text" value="<?= $broker_fax_number[1] ?>"  id="maskedFaxNumber">
							</div>
						</div>
						<div class="form-group<?= $broker_quickpay[1] == 0 ? ' hidden' : '' ?>" id="quickpay_email_holder">
							<label>Quickpay email</label>
							<input name="quickpay_email" class="form-control" type="email" value="<?= $broker_quickpay_email[1] ?>">
						</div>
						<div class="form-group<?= $broker_quickpay[1] == 0 ? ' hidden' : '' ?>" id="accounts_payable_number_holder">
							<label>Accounts payable number</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-phone"></i></span>
								<input name="accounts_payable_number" class="form-control" type="text" value="<?= $broker_accounts_payable_number[1] ?>" id="maskedAccountsPayableNumber">
							</div>
						</div>
						<div class="row">
							<div class="form-group col-sm-12 col-md-6">
								<label>Quickpay</label>
								<div class="radio">
									<input type="radio" name="quickpay" id="quickpay1" value="1"<?= $broker_quickpay[1] == 1 ? ' checked=""' : '' ?>>
									<label for="quickpay1">
										Yes
									</label>
								</div>
								<div class="radio">
									<input type="radio" name="quickpay" id="quickpay2" value="2"<?= $broker_quickpay[1] == 0 ? ' checked=""' : '' ?>>
									<label for="quickpay2">
										No
									</label>
								</div>
							</div>
							<div class="form-group col-sm-12 col-md-6">
								<div class="radio">
									<input 
										type="radio" 
										name="status" 
										id="status1" 
										value="1"
										
										<?php 

											# Auto check if status is active
											echo $broker_status[1] == 1 ? ' checked=""' : '';

											# Disable active radio if quickpay is set and quickpay email and/or accounts payable # are missing and $lock_quickpay_no_service_fee
											if ($broker_quickpay[1] == 1 
													&& (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $broker_quickpay_email[1]) 
													|| $broker_accounts_payable_number[1] == '' 
													|| $lock_quickpay_no_service_fee)) {
												
												echo ' disabled="disabled"';
											} ?>
										>
									<label for="status1"<?= $broker_quickpay[1] == 1 && (!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $broker_quickpay_email[1]) || $broker_accounts_payable_number[1] == '') ? ' data-toggle="tooltip" data-placement="top" title="In order to activate status for quickpay enabled brokers, the fields quickpay email and accounts payable number are required."' : '' ?>>
										Active
									</label>
								</div>
								<div class="radio">
									<input type="radio" name="status" id="status2" value="2"<?= $broker_status[1] == 0 ? ' checked=""' : '' ?>>
									<label for="status2">
										Inactive
									</label>
								</div>
								<div class="radio">
									<input type="radio" name="status" id="status3" value="3"<?= $broker_status[1] == 2 ? ' checked=""' : '' ?>>
									<label for="status3" class="red">
										<b>DO NOT USE</b>
									</label>
								</div>
								<div class="<?= $broker_do_not_use_reason[1] && $broker_status[1] == 2  ? '' : 'hidden' ?>" id="doNotUse">
									<label>Please provide a reason</label>
									<input name="do_not_use_reason" id="do_not_use_reason" type="text" class="form-control" value="<?= $broker_do_not_use_reason[1] ?>">
								</div>
							</div>
						</div>
						<div class="col-sm-12 col-md-12">
							<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div>
						<input type="hidden" name="_controller_<?= $this_file_name ?>" value="update_broker">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
		  	</div> <?php
	  	}
	  	
	  	# Hide panel-footer if not updating or if updating something other than this section
	  	if (!isset($_GET['edit_main'])) { ?>

	  		<div class="panel-footer">
		  		<small>
							<?php
							if (!isset($_GET['delete_broker'])) {
								
								echo ${$this_file_name_underscore . '_status'}[1] == 1 ? '<span class="green"><b>' . $core_language[82] . '</b></span>' : '<span class="red"><span class="fa fa-warning"></span> ' . $core_language[83] . '</span>';
								echo ${$this_file_name_underscore . '_quickpay'}[1] == 1 ? '<span>| </span><span>Quickpay</span>' : ' <s>Quickpay</s>';
							} ?>

							<span<?= !isset($_GET['delete_broker']) ? ' class="pull-right"' : '' ?>>
								
								<?php 
								echo !isset($_GET['delete_broker']) ? '<a href="' . $this_file_name . '?' . $this_id . '&edit_main=1"> <i class="fa fa-pencil"></i></a>' : '';

								if (isset($_GET['delete_broker'])) {
									# Display second delete button ?>
									<a href="<?= $this_file_name ?>?<?= $this_file_name ?>_id=<?= $_GET['broker_id'] ?>&_controller_broker=delete_<?= $this_file_name ?>" class="btn btn-link red"><span class="fa fa-trash-o"></span> Delete</a> <?php
								} else {
									# Display first delete button ?>
									<a href="<?= $this_file_name ?>?<?= $this_file_name ?>_id=<?= $_GET['broker_id'] ?>&delete_broker=1" class="btn btn-link red"><span class="fa fa-trash-o"></span></a><?php
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

				<div class="panel panel-<?= isset($_GET['edit_address']) || isset($_GET['add_address']) || isset($_GET['delete_address']) ? 'danger' : 'primary' ?>">
					<div class="panel-heading">
				    <p>
				    	<?= isset($_GET['edit_address']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>
				    	<?= isset($_GET['add_address']) ? '<b><i class="fa fa-warning"></i> ADDING NEW </b>' : ''?>
				    	<?= isset($_GET['delete_address']) ? '<b><i class="fa fa-warning"></i> DELETING </b>' : ''?>
				    	Address
				    	<?= isset($_GET['add_address']) ? '<small class="pull-right" style="margin-top: 10px;">* required</small>' : '' ?>
				    </p>
				  </div>

				  <?php

				  # Update address
				  if (isset($_GET['edit_address'])) { ?>

				  	<div class="panel-body">
					  	<form action="" method="post">
						  	<div class="form-group">
						    	<label class="sr-only">Address type</label>
									<select name="address_type" class="form-control">
										<option value="">* Address type</option>
										<option value="1"<?= $_GET['address_type'] == 1 ? 'selected' : '' ?>>Physical</option>
										<option value="2"<?= $_GET['address_type'] == 2 ? 'selected' : '' ?>>Mailing</option>
									</select>
								</div>
								<div class="form-group">
									<label class="sr-only">Line 1</label>
									<input type="text" name="line_1" class="form-control" placeholder="* Line 1" value="<?= $_GET['line_1'] ?>">
								</div>
								<div class="form-group">
									<label class="sr-only">Line 2</label>
									<input type="text" name="line_2" class="form-control" placeholder="* Line 2" value="<?= $_GET['line_2'] ?>">
								</div>
								<div class="form-group">
									<label class="sr-only">Line 3</label>
									<input type="text" name="line_3" class="form-control" placeholder="Line 3" value="<?= $_GET['line_3'] ?>">
								</div>
								<div class="row">
									<div class="form-group col-sm-12 col-md-4">
										<label class="sr-only">City</label>
										<input type="text" name="city" class="form-control" placeholder="* City" value="<?= $_GET['city'] ?>">
									</div>
									<div class="form-group col-sm-12 col-md-4">
										<label class="sr-only">State</label>
										<select name="state_id" style="width:100%" id="state_selector" class="form-control">
											<option>* State</option>
											<?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
												<option value="<?= $i ?>"<?= $i == $_GET['state_id'] ? 'selected' : '' ?>><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
											} ?>
										</select>
									</div>
									<div class="form-group col-sm-12 col-md-4">
										<label class="sr-only">Zip code</label>
										<input type="text" name="zip_code" class="form-control" placeholder="* Zip code" value="<?= $_GET['zip_code'] ?>">
										<small class="pull-right" style="color: #888; margin-top: 10px;">* required</small>
									</div>
								  <div class="form-group col-sm-12 col-md-12">
									    <button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
											<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
								  </div>
								</div>
								<input type="hidden" name="address_data_id" value="1">
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
							<a class="btn btn-link red" href="<?= $this_file_name ?>?<?= $this_id ?>&_controller_<?= $this_file_name_underscore ?>=delete_address&address_id=<?= $_GET['address_id'] ?>"><i class="fa fa-trash-o"></i> Delete</a>
							<a class="btn btn-link pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div> <?php
				  }

				  # Show address list
				  if (!isset($_GET['add_address']) && !isset($_GET['address_id']) && !isset($_GET['delete_address'])) { ?>
				  	<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">
							<?php for ($i=1; $i <= ${$this_file_name_underscore . '_address_count'} ; $i++) { ?>

								<p style="line-height: 7px;">
									<b style="<?= ${$this_file_name_underscore . '_address_type'}[$i] == 1 ? 'color: #9c27b0;' : 'color: #8bc34a;' ?>"><?= ${$this_file_name_underscore . '_address_line_1'}[$i] ?></b> 
									<span class="pull-right">
										
										<a class="<?= isset($_GET['delete_address']) ? ' hidden' : '' ?>" href="<?= $this_file_name ?>?<?= $this_id ?>&edit_address=1&address_id=<?= ${$this_file_name_underscore . '_address_data_id'}[$i] ?>&address_type=<?= ${$this_file_name_underscore . '_address_type'}[$i] ?>&line_1=<?= ${$this_file_name_underscore . '_address_line_1'}[$i] ?>&line_2=<?= ${$this_file_name_underscore . '_address_line_2'}[$i] ?>&line_3=<?= ${$this_file_name_underscore . '_address_line_3'}[$i] ?>&city=<?= ${$this_file_name_underscore . '_address_city'}[$i] ?>&state_id=<?= ${$this_file_name_underscore . '_address_state_id'}[$i] ?>&zip_code=<?= ${$this_file_name_underscore . '_address_zip_code'}[$i] ?>"><span class="fa fa-pencil"></span></a>
										
										<a class="red" href="<?= $this_file_name ?>?<?= $this_id ?>&<?= isset($_GET['delete_address']) ? '_controller_' . $this_file_name_underscore : 'delete_address' ?>=<?= isset($_GET['delete_address']) ? 'delete_address' : ${$this_file_name_underscore . '_address_data_id'}[$i] ?>&address_id=<?= ${$this_file_name_underscore . '_address_data_id'}[$i] ?>"<?= ${$this_file_name_underscore . '_address_count'} === 1 ? ' data-toggle="tooltip" data-placement="bottom" title="This is the only item on this list, deleting it will set this factoring company as inactive."' : '' ?>>
											<?= !isset($_GET['delete_address']) ? '<span class="fa fa-trash-o"></span> ' : '' ?>
											<?= isset($_GET['delete_address']) && $_GET['delete_address'] == ${$this_file_name_underscore . '_address_data_id'}[$i] ? ' Delete' : '' ?>
										</a>
										<a class="btn btn-link<?= isset($_GET['delete_address']) && $_GET['delete_address'] == ${$this_file_name_underscore . '_address_data_id'}[$i] ? '' : ' hidden' ?>" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
									</span>
								</p>
								<p style="line-height: 7px;"><small><i><?= ${$this_file_name_underscore . '_address_line_2'}[$i] ?></i></small></p>
								<?= ${$this_file_name_underscore . '_address_line_3'}[$i] ? '<p style="line-height: 7px;"><small></i>' . ${$this_file_name_underscore . '_address_line_3'}[$i] . '</i></small></p>' : '' ?>
								<p style="line-height: 7px;"><small><i><?= ${$this_file_name_underscore . '_address_city'}[$i] . ', ' . $state_abbr[${$this_file_name_underscore . '_address_state_id'}[$i]] . ' ' . ${$this_file_name_underscore . '_address_zip_code'}[$i] ?></i></small></p><hr> <?php
							} ?>

						</div> <?php
				  }

				  # If not updating/adding/deleting
				  if (!isset($_GET['address_id']) && !isset($_GET['delete_address'])) { ?>

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
				  
				  <div class="panel-body">
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

		# Add service fee
	  if (isset($_GET['add_service_fee'])) { ?>

	  	<div class="panel panel-info">
				<div class="panel-heading">
					<p>
			    	Add service fee
			    	<small class="pull-right" style="margin-top: 10px;">* required</small>
			    </p>
			  </div>

		  	<div class="panel-body">
		  		<form action="" method="post">
						<div class="form-group">
							<input type="number" min="0" step="0.01" name="fee" class="form-control" placeholder="* Service fee">
						</div>
						<div class="form-group">
							<select name="method_id" class="form-control">
								<option>* Method of payment</option>
								<?php for ($i=1; $i <= $quickpay_method_of_payment_count ; $i++) { ?>
									<option value="<?= $quickpay_method_of_payment_data_id[$i] ?>"><?= $quickpay_method_of_payment_method[$i] ?></option> <?php
								} ?>
							</select>
						</div>
						<div class="form-group">
							<input type="number" min="0" name="number_of_days" class="form-control" placeholder="* Number of days. 0 = same day">
						</div>
						<div class="form-group">
							<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
						</div>
						<input type="hidden" name="new" value="1">
						<input type="hidden" name="_controller_<?= $this_file_name_underscore ?>" value="add_service_fee">
		        <input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
		  	</div>
		  </div> <?php
	  } else {

	  	# Display service fee data
			if ($broker_quickpay_service_fee_count) { ?>

				<div class="panel panel-<?= isset($_GET['edit_service_fee']) || isset($_GET['delete_service_fee']) ? 'danger' : 'primary' ?>">
					<div class="panel-heading">
						<p>
				    	<?= isset($_GET['edit_service_fee']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>
				    	<?= isset($_GET['delete_service_fee']) ? '<b><i class="fa fa-warning"></i> DELETING </b>' : ''?>
				    	Service fee
				    </p>
				  </div>

				  <?php

				  # Edit service fee
				  if (isset($_GET['service_fee_id']) && !isset($_GET['delete_service_fee'])) { ?>

				  	<div class="panel-body">
					  	<form action="" method="post">
								<div class="row">
									<div class="form-group col-sm-6 col-md-6">
										<label>Service fee</label>
										<input type="number" min="0.01" step="0.01" name="fee" class="form-control" value="<?= $broker_quickpay_service_fee_fee[1] ?>">
									</div>
									<div class="form-group col-sm-6 col-md-6">
										<label>Days</label>
										<input type="number" min="0" name="number_of_days" class="form-control" placeholder="Number of days. 0 = same day" value="<?= $broker_quickpay_service_fee_number_of_days[1] ?>">
									</div>
								</div>
								<div class="form-group">
									<label>Method of payment</label>
									<select name="method_id" class="form-control">
										<?php for ($v=1; $v <= $quickpay_method_of_payment_count ; $v++) { ?>
											<option value="<?= $quickpay_method_of_payment_data_id[$v] ?>"<?= $broker_quickpay_service_fee_method_id[1] == $quickpay_method_of_payment_data_id[$v] ? ' selected="selected"' : '' ?>><?= $quickpay_method_of_payment_method[$v] ?></option> <?php
										} ?>
									</select>
								</div>
								<div class="form-group">
									<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
									<a class="btn btn-link red pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
								</div>
								<input type="hidden" name="data_id" value="<?= $broker_quickpay_service_fee_data_id[1] ?>">
								<input type="hidden" name="_controller_broker" value="update_service_fee">
		            <input type="hidden" name="token" value="<?= $csrfToken ?>">
							</form>
						</div> <?php
				  }

				  # Delete service fee
				  if (isset($_GET['delete_service_fee']) && isset($_GET['service_fee_id'])) { ?>

				  	<div class="panel-body">
				  		<p style="line-height: 7px;">
								<small>
									<i>
										<?= ${$this_file_name_underscore . '_service_fee_fee'}[1] ?>% 
										via <?= $quickpay_method_of_payment_method[${$this_file_name_underscore . '_service_fee_method_id'}[1]] ?> 
								
										<?php 

										echo ${$this_file_name_underscore . '_service_fee_number_of_days'}[1] > 0 ? '[' . ${$this_file_name_underscore . '_service_fee_number_of_days'}[1] : '[';
										
										if (${$this_file_name_underscore . '_service_fee_number_of_days'}[1] == 0) {
											
											echo "Same day]";
										} elseif (${$this_file_name_underscore . '_service_fee_number_of_days'}[1] == 1) {

											echo "day]";
										} else {

											echo "days]";
										} ?>

									</i>
								</small>
							</p><hr>
							
							<a class="btn btn-link red" href="<?= $this_file_name ?>?<?= $this_id ?>&_controller_<?= $this_file_name_underscore ?>=delete_service_fee&service_fee_id=<?= $_GET['service_fee_id'] ?>"><i class="fa fa-trash-o"></i> Delete</a>
							<a class="btn btn-link pull-right" href="<?= $this_file_name ?>?<?= $this_id ?>">Cancel</a>
				  	</div> <?php
				  }

				  # Show service fee list
				  if (!isset($_GET['add_service_fee']) && !isset($_GET['service_fee_id']) && !isset($_GET['delete_service_fee'])) { ?>

				  	<div class="panel-body" style="max-height: 160px; min-height: 160px; overflow-y: auto;">

					  	<?php
					  	for ($i=1; $i <= $broker_quickpay_service_fee_count ; $i++) { ?>

								<p style="line-height: 7px;">
									<small>
										<i>
											<?= $broker_quickpay_service_fee_fee[$i] ?>% 
											via <?= $quickpay_method_of_payment_method[$broker_quickpay_service_fee_method_id[$i]];

											echo ' [';
											
											if ($broker_quickpay_service_fee_number_of_days[$i] == 0) {
												
												echo "same day]";
											} elseif ($broker_quickpay_service_fee_number_of_days[$i] == 1) {

												echo $broker_quickpay_service_fee_number_of_days[$i] . " day]";
											} else {

												echo $broker_quickpay_service_fee_number_of_days[$i] . " days]";
											} ?>

										</i>
									</small>
									
									<span class="pull-right"> 
										<a href="<?= $this_file_name ?>?<?= $this_id ?>&edit_quickpay_service_fee&service_fee_id=<?= $broker_quickpay_service_fee_data_id[$i] ?>"><i class="fa fa-pencil"></i></a>
										<a class="red" href="<?= $this_file_name ?>?<?= $this_id ?>&deletequickpay_service_fee=1&service_fee_id=<?= $broker_quickpay_service_fee_data_id[$i] ?>"><i class="fa fa-trash-o"></i></a>
									</span>
								</p><hr> <?php
							}
					  	?>

						</div> <?php
				  }

				  # If not updating/adding/deleting
				  if (!isset($_GET['service_fee_id']) && !isset($_GET['add_service_fee']) && !isset($_GET['delete_service_fee'])) { ?>

				  	<div class="panel-footer">

						  <div class="row">
						  	<div class="col-sm-12 col-md-12 text-right">
						  		<a href="<?= $this_file_name ?>?<?= $this_id ?>&add_service_fee=1"><i class="fa fa-plus" data-toggle="tooltip" data-placement="top" title="Add new service fee"></i></a>
						  	</div>
						  </div>
					  </div> <?php
				  }
				  ?>

				</div> <?php
			} else {

				# Display no service fee warning ?>
				<div class="panel panel-danger">
					<div class="panel-heading">
						<p>Service fee</p>
					</div>
				  
				  <div class="panel-body">
				  	<div class="text-center">
				  		<p class="red"><i class="fa fa-warning"></i> There is no service fee data to show.</p>
							<span class="red"><a class="btn btn-link" href="<?= $this_file_name ?>?<?= $this_id ?>&add_service_fee=1"><i class="fa fa-plus"></i> Add first item</a></span>
				  	</div>
					</div>
				</div> <?php
			}
	  } ?>

	</div>
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
