<?php 
session_start();
ob_start();
# Redirect if no query string
if (!$_SERVER['QUERY_STRING']) { ?>
	<script type="text/javascript">
		window.location = "/dashboard/user/"
	</script> <?php
}

require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';
?>

<div class="row">
	<div class="col-md-3 col-sm-6">

		<div class="panel panel-<?= isset($_GET['edit_main']) ? 'danger' : 'default'?>">
			<div class="panel-heading">
		    
		    <p><?= isset($_GET['edit_main']) ? '<b><i class="fa fa-warning"></i> UPDATING </b>' : ''?>Main</p>
		  </div>

		  <?php
	  	# Data display if [not updating || updating something other than this]
	  	if (!isset($_GET['edit_main'])) { ?>
	  		
	  		<ul class="list-group">

	  			<li href="#" class="list-group-item">
						<small>
							<i>
								<span class="fa fa-user"></span>

								<?= $user_list_name[1] . ' ' . $user_list_last_name[1] ?>
								- dob <?= $user_list_dob[1] ?>
							</i>
						</small>
					</li>

					<li href="#" class="list-group-item">
						<small>
							<i>

								<?= $user_list_email[1] ?>
							</i>
						</small>
					</li>

					<li href="#" class="list-group-item">
						<small>
							<i>

								<?= $user_group_id_name[$user_list_user_group[1]] ?>
							</i>
						</small>
					</li>

				</ul> <?php

	  	} 

	  	# Update main data (password excluded)
	  	if (isset($_GET['edit_main']) && $_GET['edit_main'] == 1) { ?>
	  		
	  		<div class="panel-body">
		  		<form action="" method="post" role="form">
						<div class="form-group">
							<input name="name" type="text" class="form-control" id="name" value="<?= $user_list_name[1] ?>" placeholder="First name">
						</div>
						
						<div class="form-group">
							<input name="last_name" type="text" class="form-control" id="last_name" value="<?= $user_list_last_name[1] ?>" placeholder="Last name">
						</div>

						<div class="form-group">									
							<div class="input-group">
	              <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
	              <input name="email" class="form-control" type="email" placeholder="Email" value="<?= $user_list_email[1] ?>">
	            </div>
	          </div>

	          <div class="form-group">									
							<label for="dob">Date of birth</label>
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								<input type="text" class="form-control" name="dob" id="dob" placeholder="mm/dd/YYYY" value="<?= $user_list_dob[1] ?>">
							</div>
	          </div>

	          <div class="form-group">

							<select class="form-control" name="user_group">
								<option>User group</option>

								<?php
								for ($i = 1; $i <= $user_group_count; $i++) { ?>
									
									<option value="<?= $user_group_group_id[$i] ?>"<?= $user_list_user_group[1] == $user_group_group_id[$i] ? ' selected' : '' ?>><?= $user_group_name[$i] ?></option> <?php
								}
								?>
							</select>
						</div>

						<div class="form-group">
							<button name="submit" class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="user?user_id=<?= $_GET['user_id'] ?>">Cancel</a>
						</div>

						<input type="hidden" name="_controller_user" value="update_user">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
		  	</div> <?php
	  	} elseif (isset($_GET['edit_main']) && $_GET['edit_main'] == 2) {

	  		# Update password ?>
	  		
	  		<div class="panel-body">
		  		<form action="" method="post" role="form">
						
						<div class="form-group">
	            <div class="input-group">
	              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
	              <input name="password" type="password" class="form-control" placeholder="Password">
	            </div>
	          </div>
            
            <div class="form-group">
	            <div class="input-group">
	              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
	              <input name="password_again" type="password" class="form-control" placeholder="Repeat password">
	            </div>
	          </div>

						<div class="form-group">
							<button name="submit" class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="user?user_id=<?= $_GET['user_id'] ?>">Cancel</a>
						</div>

						<input type="hidden" name="_controller_user" value="update_user_password">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
		  	</div> <?php

	  	}
	  	
	  	# Hide panel-footer if not updating or if updating something other than this section
	  	if (!isset($_GET['edit_main'])) { ?>

	  		<div class="panel-footer" style="max-height: 41px;">
		  		<small>

						<a href="user?user_id=<?= $_GET['user_id'] ?>&edit_main=1"> <i class="fa fa-pencil"></i></a>
						<a class="red pull-right" href="user?user_id=<?= $_GET['user_id'] ?>&edit_main=2"> <i class="fa fa-pencil"></i> Edit password</a>
					</small>
				</div> <?php
	  	}
	  	?>

		</div>
	</div>

	<div class="col-md-3 col-sm-6">

		<div class="panel panel-default">
			<div class="panel-heading">
		    
		    <p>Phone number</p>
		  </div>

		  <?php
	  	# Data display
	  	if (!isset($_GET['add_phone_number'])) { ?>
	  		
	  		<ul class="list-group" style="min-height: 121px; max-height: 121px; overflow-y: auto;">

	  			<?php
	  			if ($user_phone_number_count) {
	  				
	  				for ($i = 1; $i <= $user_phone_number_count ; $i++) { ?>
		  				
		  				<li href="#" class="list-group-item">
		  					<form action="" method="post" class="pull-left">
									
									<button data-toggle="tooltip" data-placement="right" title="Delete number" style="margin-top: -5px;" type="submit" class="btn btn-link red">
										<i class="fa fa-trash-o"></i>
									</button>
									<input type="hidden" name="user_phone_number_id" value="<?= $user_phone_number_id[$i] ?>">
									<input type="hidden" name="_controller_user" value="delete_user_phone_number">
									<input type="hidden" name="token" value="<?= $csrfToken ?>">
								</form>

								<small>
									<i class="fa fa-phone"></i>
									<i>

										<?= $user_phone_number_phone_number[$i] ?>
									</i>
								</small>
							</li> <?php
		  			}
	  			} else { ?>
		  				
	  				<li href="#" class="list-group-item">
							<small>
								<i>

									<span class="red"><i class="fa fa-warning"></i> There are no phone numbers for this user.</span>
								</i>
							</small>
						</li> <?php
	  			}
	  			?>

				</ul> <?php

	  	}

	  	# Add phone number
	  	if (isset($_GET['add_phone_number'])) { ?>
	  		
	  		<div class="panel-body">
		  		<form action="" method="post" role="form">
						<div class="form-group">
							<label for="phone_number">Add new number</label>
							<input name="phone_number" type="text" class="form-control" id="phone_number<?= $user_list_user_group[1] == 4 || $user_list_user_group[1] == 1 || $user_list_user_group[1] == 3 ? '' : '_cr' ?>">
						</div>
						
						<div class="form-group">
							<button name="submit" class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
							<a class="btn btn-link red pull-right" href="user?user_id=<?= $_GET['user_id'] ?>">Cancel</a>
						</div>

						<input type="hidden" name="_controller_user" value="add_user_phone_number">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
		  	</div> <?php
	  	}
	  	
	  	# Hide panel-footer if not updating or if updating something other than this section
	  	if (!isset($_GET['add_phone_number'])) { ?>

	  		<div class="panel-footer" style="max-height: 41px;">
		  		<small>

						<a href="user?user_id=<?= $_GET['user_id'] ?>&add_phone_number=1" data-toggle="tooltip" data-placement="top" title="Add number"> <i class="fa fa-plus"></i></a>
					</small>
				</div> <?php
	  	}
	  	?>

		</div>
	</div>
</div>

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
