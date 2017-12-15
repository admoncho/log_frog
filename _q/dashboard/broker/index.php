<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = 'broker.php';
$this_file_name_underscore = 'broker.php';

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
				if (!isset($_POST['add_broker'])) {

					if (!$broker_count) {
						
						include COMPONENT_PATH . 'alert_simple_info.txt';
							echo "There are no brokers to display.</div>";
					} ?>

					<div class="filter-block" style="position: absolute; top: 21px; right:280px; z-index: 9;">
						<div class="form-group pull-left">
							<form action="" method="post">
								<input type="text" name="name" class="form-control" placeholder="Add broker - Name" style="min-width: 230px;">
								<button type="submit" class="btn btn-link" href=""><i class="fa fa-plus search-icon"></i></button>
								<input type="hidden" name="add_broker" value="1">
							</form>
						</div>
					</div> <?php
				}

				# Add broker
				if (isset($_POST['add_broker'])) { ?>
					
					<div class="col-sm-12 col-md-3">
						<form action="" method="post">
							<div class="form-group">
								<label><span class="red">* </span>Company name</label>
								<input name="company_name" class="form-control" type="text" value="<?= $_POST['name'] ?>">
							</div>
							<div class="form-group">
								<label><span class="red">* </span>Phone Number</label>
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-phone"></i></span>
									<input name="phone_number_01" class="form-control" type="text" id="maskedPhoneNumber">
								</div>
							</div>
							<div class="form-group">
								<label><span class="red">* </span>Quickpay</label>
								<div class="radio">
									<input type="radio" name="quickpay" id="quickpay1" value="1">
									<label for="quickpay1">
										Yes
									</label>
								</div>
								<div class="radio">
									<input type="radio" name="quickpay" id="quickpay2" value="2" checked="">
									<label for="quickpay2">
										No
									</label>
								</div>
							</div>
							<div class="col-sm-12 col-md-12">
								<button name="submit" class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
								<a class="btn btn-link red pull-right" href="<?= str_replace('.php', '', $_SERVER['PHP_SELF']) ?>">Cancel</a>
							</div>
							<input type="hidden" name="_controller_broker" value="add_broker">
	            <input type="hidden" name="token" value="<?= $csrfToken ?>">
						</form>
					</div> <?php
				} else {

					# Display list if there are items
					if ($broker_count) { ?>

					 	<table id="<?= $module_name ?>-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Company name</th>
									<th>Phone number</th>
									<th>Fax</th>
									<th>Quickpay</th>
								</tr>
							</thead>
							<tbody>
								<?php for ($i=1; $i <= $broker_count ; $i++) { ?>

									<tr>
										<td><a href="broker?broker_id=<?= $broker_data_id[$i] ?>"<?= $broker_status[$i] == 0 ? ' class="red"' : '' ?>><?= $broker_company_name[$i] ?></a></td>
										<td><?= $broker_phone_number_01[$i] ?></td>
										<td><?= $broker_fax_number[$i] ?></td>
										<td><?= $broker_quickpay[$i] == 1 ? '<i class="fa fa-check green"></i>' : ''; ?></td>
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
