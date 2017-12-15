<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = 'loader.php';
$this_file_name_underscore = 'loader.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");

?>

<div class="row">

	<?php
	# Hide filters when adding a new load
	if (!isset($_POST['add_load'])) { ?>

		<div class="col-sm-12 col-md-6" style="margin-bottom: 5px;">

			<?php
	    # Clear filters if any are set
	    if (isset($_POST['limit']) || isset($_POST['load_status']) || isset($_POST['broker_id']) || isset($_POST['load_number']) || isset($_POST['driver_id'])) {
	      echo '<a ' .  $_SESSION['href_location'] . 'dashboard/loader/" class="btn btn-link red">Clear filters</a>';  
	    } ?>
	    
	    <form action="" method="post" class="form-inline pull-right">
	      <div class="form-group<?= isset($_POST['load_status']) ? ($_POST['load_status'] == 1 ? ' has-error' : '') : '' ?>">
	        <select name="load_status" class="form-control" onchange="this.form.submit()">
	          <option value="9">Active</option>
	          <option value="1"<?= isset($_POST['load_status']) ? ($_POST['load_status'] == 1 ? ' selected' : '') : '' ?>>Deleted</option>
	        </select>
	      </div>
	      <?= isset($_POST['limit']) ? '<input type="hidden" name="limit" value="' . $_POST['limit'] . '">' : '' ?>
	      <?= isset($_POST['broker_id']) ? '<input type="hidden" name="broker_id" value="' . $_POST['broker_id'] . '">' : '' ?>
	      <?= isset($_POST['driver_id']) ? '<input type="hidden" name="driver_id" value="' . $_POST['driver_id'] . '">' : '' ?>
	    </form>

	    <form action="" method="post" class="form-inline pull-right">
	      <div class="form-group" style="margin-right: 4px;">
	        <input 
	          type="number" name="limit" min="1" max="<?= $load_ALT_count ?>" 
	          value="<?= isset($_POST['limit']) ? $_POST['limit'] : 25; ?>" 
	          data-toggle="tooltip" data-placement="top" title="Items displayed - MAX: <?= $load_ALT_count ?>" 
	          class="red form-control"
	        >
	      </div>
	      <?= isset($_POST['load_status']) ? '<input type="hidden" name="load_status" value="' . $_POST['load_status'] . '">' : '' ?>
	      <?= isset($_POST['broker_id']) ? '<input type="hidden" name="broker_id" value="' . $_POST['broker_id'] . '">' : '' ?>
	      <?= isset($_POST['driver_id']) ? '<input type="hidden" name="driver_id" value="' . $_POST['driver_id'] . '">' : '' ?>
	    </form>

	    <form action="" method="post" class="form-inline pull-right">
	      <div class="form-group" style="margin-right: 4px;">
	        <button data-toggle="tooltip" data-placement="top" title="Add new load" class="btn btn-link" type="submit"><i class="fa fa-plus"></i></button>
	      </div>
	      <input type="hidden" name="add_load" value="1">
	    </form>

	  </div>
	  <div class="col-sm-12 col-md-2" style="margin-bottom: 5px;">
	    
	    <form action="" method="post" class="form-inline pull-left" style="width: 100%;">
	      <div class="form-group form-group-select2" style="width: 100%;">
	        <select name="broker_id" onchange="this.form.submit()" style="width: 100%;" id="broker_select">
	          <option>Broker</option>
	          
	          <?php
	          for ($i = 1; $i <= $broker_ALT_count ; $i++) {

	            # Show active brokers
	            if ($broker_ALT_status[$i] == 1) { ?>
	            
	              <option value="<?= $broker_ALT_id[$i] ?>"<?= isset($_POST['broker_id']) ? ($broker_ALT_id[$i] == $_POST['broker_id'] ? ' selected' : '') : '' ?>><?= $broker_ALT_company_name[$i] ?></option> <?php
	            }
	          }
	          ?>

	        </select>
	      </div>
	      <?= isset($_POST['limit']) ? '<input type="hidden" name="limit" value="' . $_POST['limit'] . '">' : '' ?>
	      <?= isset($_POST['load_status']) ? '<input type="hidden" name="load_status" value="' . $_POST['load_status'] . '">' : '' ?>
	      <?= isset($_POST['driver_id']) ? '<input type="hidden" name="driver_id" value="' . $_POST['driver_id'] . '">' : '' ?>
	    </form>

	  </div>
	  <div class="col-sm-12 col-md-2" style="margin-bottom: 5px;">

	    <form action="" method="post" class="form-inline pull-left" style="width: 100%;">

	      <div class="form-group form-group-select2" style="width: 100%;">
	        <select name="load_number" onchange="this.form.submit()" style="width: 100%;" id="load_number_select">
	        <option>Load #</option>
	          
	          <?php
	          for ($i = 1; $i <= $load_ALT_count ; $i++) { ?>
	            
	            <option value="<?= $load_ALT_load_number[$i] ?>"<?= isset($_POST['load_number']) ? ($load_ALT_load_number[$i] == $_POST['load_number'] ? ' selected' : '') : '' ?>><?= $load_ALT_load_number[$i] ?></option> <?php
	          }
	          ?>

	        </select>
	      </div>
	    </form>

	  </div>
	  <div class="col-sm-12 col-md-2" style="margin-bottom: 5px;">

	    <form action="" method="post" class="form-inline pull-left" style="width: 100%;">
	      <div class="form-group form-group-select2" style="width: 100%;">
	        <select name="driver_id" onchange="this.form.submit()" style="width: 100%;" id="driver_id_select">
	        <option value="">Driver</option>
	          
	          <?php
	          for ($i = 1; $i <= $driver_list_count ; $i++) { ?>
	            
	            <option value="<?= $driver_list_user_id[$i] ?>"<?= isset($_POST['driver_id']) ? ($driver_list_user_id[$i] == $_POST['driver_id'] ? ' selected' : '') : '' ?>><?= $user_list_name[$driver_list_user_id[$i]] . ' ' . $user_list_last_name[$driver_list_user_id[$i]] ?></option> <?php
	          }
	          ?>

	        </select>
	      </div>
	      <?= isset($_POST['limit']) ? '<input type="hidden" name="limit" value="' . $_POST['limit'] . '">' : '' ?>
	      <?= isset($_POST['load_status']) ? '<input type="hidden" name="load_status" value="' . $_POST['load_status'] . '">' : '' ?>
	      <?= isset($_POST['broker_id']) ? '<input type="hidden" name="broker_id" value="' . $_POST['broker_id'] . '">' : '' ?>
	    </form>

	  </div> <?php
	}
	?>

	<div class="col-sm-12 col-md-12">

		<?php
		# Add load
		if (isset($_POST['add_load'])) { ?>
			
			<div class="panel panel-default">
				<div class="panel-body">
					
					<form action="" method="post" class="form-inline pull-left" style="width: 100%;">
			      <div class="form-group form-group-select2" style="width: 100%;">
			        <select name="driver_id" onchange="this.form.submit()" style="width: 100%;" id="driver_id_select">
			        <option value="">Driver</option>
			          
			          <?php
			          for ($i = 1; $i <= $driver_list_count ; $i++) { ?>
			            
			            <option value="<?= $driver_list_user_id[$i] ?>"><?= $user_list_name[$driver_list_user_id[$i]] . ' ' . $user_list_last_name[$driver_list_user_id[$i]] ?></option> <?php
			          }
			          ?>

			        </select>
			      </div>
			    </form>

				</div>
			</div> <?php
		} else {

			# Display table if there are results
			if ($load_count) { ?>
				
				<div class="panel panel-default">
					
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th><span>Broker</span></th>
									<th><span>Load #</span></th>
									<th><span>Driver</span></th>
									<th><span>LINE HAUL <i class="fa fa-arrows-h"></i> MILES</span></th>
									<th><span>ORIGIN</span></th>
									<th><span>DESTINATION</span></th>
									<th><span>ORIGIN</span></th>
									<th><span>DESTINATION</span></th>
									<th><span>USER</span></th>
									<th></th>
								</tr>
							</thead>
							<tbody>

							<?php
							for ($i = 1; $i <= $load_count ; $i++) { ?>
								
								<tr>
									<td>
										<small>
											<?= ucwords($broker_id_company_name[$load_broker_id[$i]]) ?>
										</small>
									</td>
									<td>
										<small>
											<?= $load_load_number[$i] ?>
										</small>
									</td>
									<td>
										<small>
											<?= $user_list_name[$entry_driver_id[$i]] . ' ' . $user_list_last_name[$entry_driver_id[$i]] ?>
										</small>
									</td>
									<td>
										<small>
											<?= '$ ' . $load_line_haul_format_1[$i] .' <i class="fa fa-arrows-h"></i> ' . $load_miles[$i] ?>
										</small>
									</td>
									<td>
										<small>
											<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_first_checkpoint_time[$i] ?>"> <?= $load_first_checkpoint_date[$i] ?></span>
										</small>
									</td>
									<td>
										<small>
											<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_last_checkpoint_time[$i] ?>"> <?= $load_last_checkpoint_date[$i] ?></span>
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
											<span> <?= $user_list_name[$load_user_id[$i]] . ' ' . substr($user_list_last_name[$load_user_id[$i]], 0, 1) ?></span>
										</small>
									</td>
									<td style="position: relative;">
										<div style="width: 20px; position: absolute; top: 5px; left: 0;">
											<a href="load?load_id=<?= $load_load_id[$i] ?>"><i class="fa fa-eye"></i></a>
											<a href="#"><i class="fa fa-cube"></i></a>
										</div>
									</td>
								</tr> <?php
							}
							?>

							</tbody>
						</table>
					</div>

				</div> <?php
			} else {

				include COMPONENT_PATH . 'alert_simple_danger.txt';
				 echo 'There are no results to show under those filters!</div>';
			}
		}
		?>

	</div>
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
