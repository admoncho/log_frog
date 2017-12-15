<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = 'user.php';
$this_file_name_underscore = 'user.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");

?>

<div class="row">
	<div class="col-sm-12 col-md-12">

		<div class="main-box no-header clearfix">
			<div class="main-box-body clearfix">
	
				<?php
				# Hide if adding new user
				if (!isset($_POST['add_user'])) {

					if (!$user_list_count) {
						
						include COMPONENT_PATH . 'alert_simple_info.txt';
							echo "There are no users to display.</div>";
					} ?>

					<div class="filter-block" style="position: absolute; top: 21px; right:280px; z-index: 9;">
						<div class="form-group pull-left" style="margin-bottom: 0;">
							<form action="" method="post">
								
								<button data-toggle="tooltip" data-placement="top" title="Add user" type="submit" class="btn btn-link" href=""><i class="fa fa-plus search-icon"></i></button>
								<input type="hidden" name="add_user" value="1">
							</form>
						</div>
					</div> <?php
				}

				# Add user
				if (isset($_POST['add_user'])) {
					
					include COMPONENT_PATH . 'alert_simple_info.txt';
						echo "Fill the form below to add a new user, all fields are required.</div>"; ?>
					
					<div class="col-sm-12 col-md-3">
						<div class="panel panel-default">	

							<div class="panel-body">
									
								<form action="" method="post" role="form">
									<div class="form-group">
										<input name="name" type="text" class="form-control" id="name" value="<?= $_POST['name'] ?>" placeholder="First name">
									</div>
									
									<div class="form-group">
										<input name="last_name" type="text" class="form-control" id="last_name" value="<?= $_POST['last_name'] ?>" placeholder="Last name">
									</div>
	
									<div class="form-group">									
										<div class="input-group">
				              <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
				              <input name="email" class="form-control" type="email" placeholder="Email">
				            </div>
				          </div>

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

										<select class="form-control" name="user_group">
											<option>User group</option>

											<?php
											for ($i = 1; $i <= $user_group_count; $i++) { ?>
												
												<option value="<?= $user_group_group_id[$i] ?>"><?= $user_group_name[$i] ?></option> <?php
											}
											?>
										</select>
									</div>

									<div class="form-group">
										<button name="submit" class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
										<a class="btn btn-link red pull-right" href="<?= str_replace('.php', '', $_SERVER['PHP_SELF']) ?>">Cancel</a>
									</div>

									<input type="hidden" name="_controller_user" value="add_user">
									<input type="hidden" name="added_from" value="internal">
									<input type="hidden" name="token" value="<?= $csrfToken ?>">
								</form>
							</div>

						</div>
					</div> <?php
				} else {

					# Display list if there are items
					if ($user_list_count) { ?>
						
						<table id="<?= $module_name ?>-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>Last Name</th>
									<th>Email</th>
									<th>User group</th>
									<th>Added</th>
								</tr>
							</thead>
							<tbody>

								<?php for ($i=1; $i <= $user_list_count ; $i++) { ?>
									<tr>
										<td>
											
											<?php 
											if ($is_online_count[$i]) {
											 	
											 	echo '<i style="font-size: 0.7em;" class="fa fa-circle green" data-toggle="tooltip" data-placement="top" title="User online"></i>';
											} else {

												echo '<i style="color: #ccc; font-size: 0.7em;" class="fa fa-circle" data-toggle="tooltip" data-placement="top" title="User offline"></i>';
											} ?>

											<a href="user?user_id=<?= $user_list_id[$i] ?>"<?= $user_list_status[$i] != 1 ? ' class="red"' : '' ?>><?= $user_list_name[$i] ?></a>
										</td>
										<td><a href="user?user_id=<?= $user_list_id[$i] ?>"<?= $user_list_status[$i] != 1 ? ' class="red"' : '' ?>><?= $user_list_last_name[$i] ?></a></td>
										<td><?= $user_list_email[$i] ?></td>
										<td><?= $user_group_id_name[$user_list_user_group[$i]] ?></td>
										<td><?= $user_list_added[$i] ?></td>
									</tr> <?php
								} ?>

							</tbody>
						</table> <?php
					}
				}
				?>

			</div> 
		</div> 
	</div> 
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
