<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = 'factoring_company.php';
$this_file_name_underscore = 'factoring_company.php';

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
				
				if (!isset($factoring_company_count)) {

					include COMPONENT_PATH . 'alert_simple_warning.txt' ?>
					
			      There are no factoring companies yet.
			    </div> <?php
				} else {

					# Hide if adding new company
					if (!isset($_POST['add_factoring_company'])) {

						if (!$factoring_company_count) {
							
							include COMPONENT_PATH . 'alert_simple_info.txt';
								echo "There are no factoring companies to display.</div>";
						} ?>

						<div class="filter-block" style="position: absolute; top: 21px; right:280px; z-index: 9;">
							<div class="form-group pull-left">
								<form action="" method="post">
									<input type="text" name="name" class="form-control" placeholder="Add factoring company - Name" style="min-width: 230px;">
									<button type="submit" class="btn btn-link" href=""><i class="fa fa-plus search-icon"></i></button>
									<input type="hidden" name="add_factoring_company" value="1">
								</form>
							</div>
						</div> <?php
					}

					# Add factoring company
					if (isset($_POST['add_factoring_company'])) { ?>
						
						<form action="" method="post">
							<div class="row">
								<div class="form-group col-sm-12 col-md-4">
									<label><span class="red">* </span>Name</label>
									<input name="name" class="form-control" type="text" value="<?= $_POST['name'] ?>">
								</div>
								<div class="form-group col-sm-12 col-md-4">
									<label><span class="red">* </span>Website</label>
									<input name="uri" class="form-control" type="text">
								</div>
								<div class="form-group col-sm-12 col-md-4">
									<label><span class="red">* </span>Invoicing email</label>
									<input name="invoicing_email" class="form-control" type="text">
								</div>
								<div class="form-group col-sm-12 col-md-4">
									<label><span class="red">* </span>Phone Number</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-phone"></i></span>
										<input name="phone_number_01" class="form-control" type="text" id="maskedPhoneNumber">
									</div>
								</div>
								<div class="form-group col-sm-12 col-md-4">
									<label>Fax Number</label>
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-fax"></i></span>
										<input name="fax" class="form-control" type="text" id="maskedFaxNumber">
									</div>
								</div>
								<div class="form-group col-sm-12 col-md-2">
									<label><span class="red">* </span>Batch/Schedule</label>
									<select name="batch_schedule" class="form-control">
										<option></option>
										<option value="1">Batch</option>
										<option value="2">Schedule</option>
									</select>
								</div>
								<div class="form-group col-sm-12 col-md-2">
									<label><span class="red">* </span>Requires SOAR</label>
									<select name="requires_soar" class="form-control">
										<option value="1">Yes</option>
										<option value="2">No</option>
									</select>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-12">
									<button name="submit" class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
									<a class="btn btn-link red pull-right" href="<?= str_replace('.php', '', $_SERVER['PHP_SELF']) ?>">Cancel</a>
								</div>
							</div>
							<input type="hidden" name="_controller_factoring_company" value="add_factoring_company">
              <input type="hidden" name="token" value="<?= $csrfToken ?>">
						</form> <?php
					} else {

						# Display list if there are items
						if ($factoring_company_count) { ?>
						 	
						 	<!-- <table class="table footable toggle-circle-filled" data-page-size="10" data-filter="#filter" data-filter-text-only="true"> -->
						 	<table id="<?= $module_name ?>-table" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>Company name</th>
										<th>Website</th>
										<th>Invoicing email</th>
										<th>Phone number</th>
									</tr>
								</thead>
								<tbody>
									<?php for ($i=1; $i <= $factoring_company_count ; $i++) { ?>
										<tr>
											<td><a href="factoring-company?factoring_company_id=<?= $factoring_company_id[$i] ?>"<?= $factoring_company_status[$i] == 0 ? ' class="red"' : '' ?>><?= $factoring_company_name[$i] ?></a></td>
											<td><?= $factoring_company_uri[$i] ?></td>
											<td><?= $factoring_company_invoicing_email[$i] ?></td>
											<td><?= $factoring_company_phone_number_01[$i] ?></td>
										</tr> <?php
									} ?>
								</tbody>
							</table> <?php
						}
					}
				} ?>

			</div>
		</div>

	</div>
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
