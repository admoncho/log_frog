<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = 'client.php';
$this_file_name_underscore = 'client.php';

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
				# Hide if adding new company
				if (!isset($_POST['add_client'])) {

					if (!$client_count) {
						
						include COMPONENT_PATH . 'alert_simple_info.txt';
							echo "There are no clients to display.</div>";
					} ?>

					<div class="filter-block" style="position: absolute; top: 21px; right:390px; z-index: 9;">
						<div class="form-group pull-left" style="margin-bottom: 0;">
							<form action="" method="post">
								<input type="text" name="name" class="form-control" placeholder="Add client - Name" style="min-width: 230px;">
								<button type="submit" class="btn btn-link" href=""><i class="fa fa-plus search-icon"></i></button>
								<input type="hidden" name="add_client" value="1">
							</form>
						</div>
					</div>

					<div class="filter-block" style="position: absolute; top: 21px; right:280px; z-index: 9;">

						<!-- status form -->
				    <form action="" method="post" class="form-inline pull-right">
				      <div class="form-group<?= isset($_POST['status']) ? ($_POST['status'] == 9 ? ' has-error' : '') : '' ?>">
				        <select name="status" class="form-control" onchange="this.form.submit()">

				          <option value="1">Active</option>
				          <option value="9"<?= isset($_POST['status']) ? ($_POST['status'] == 9 ? ' selected' : '') : '' ?>>Inactive</option>
				        </select>
				      </div>
				    </form>
				  </div> <?php
				}

				# Add client
				if (isset($_POST['add_client'])) { ?>
					
					<div class="col-sm-12 col-md-3">
						<div class="panel panel-default">	

							<div class="panel-body">
									
								<form action="" method="post" role="form">
									<div class="form-group" id="company_name_holder">
										<label class="control-label" for="company_name">Company name</label>
										<input name="company_name" type="text" class="form-control" id="company_name" value="<?= $_POST['name'] ?>">
									</div>
									<div class="form-group" id="mc_number_holder">
										<label class="control-label" for="mc_number">MC Number</label>
										<input name="mc_number" type="text" class="form-control" id="mc_number">
									</div>
									<div class="form-group" id="us_dot_number_holder">
										<label class="control-label" for="us_dot_number">US DOT Number</label>
										<input name="us_dot_number" type="text" class="form-control" id="us_dot_number">
									</div>
									<div class="form-group" id="ein_number_holder">
										<label class="control-label" for="ein_number">EIN Number</label>
										<input name="ein_number" type="text" class="form-control" id="ein_number">
									</div>
									<div class="form-group">
										<button name="submit" class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
										<a class="btn btn-link red pull-right" href="<?= str_replace('.php', '', $_SERVER['PHP_SELF']) ?>">Cancel</a>
									</div>
									<input type="hidden" name="user_id" value="<?= $user_e_data->user_id ?>">
									<input type="hidden" name="_controller_client" value="add_client">
									<input type="hidden" name="token" value="<?= $csrfToken ?>">
								</form>
							</div>

						</div>
					</div> <?php
				} else {

					# Display list if there are items
					if ($client_count) { ?>
						
						<table id="<?= $module_name ?>-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Company name</th>
									<th>MC</th>
									<th>EIN</th>
									<th>USDOT</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i=1; $i <= $client_count ; $i++) { ?>
									<tr>
										<td><a href="client?client_id=<?= $client_data_id[$i] ?>"<?= $client_status[$i] == 0 ? ' class="red"' : '' ?>><?= $client_company_name[$i] ?></a></td>
										<td><?= $client_mc_number[$i] ?></td>
										<td><?= $client_ein_number[$i] ?></td>
										<td><?= $client_us_dot_number[$i] ?></td>
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
