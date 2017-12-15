<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");

?>

<div class="row">
	<div class="col-sm-12 col-md-12">

		<div class="panel panel-default">

		  <div class="table-responsive">
					
				<div class="row" style="padding: 15px">

					<div class="col-lg-4 col-sm-6 col-xs-12">
						<div class="main-box infographic-box colored green-bg">
							<i class="fa fa-money"></i>
							<span class="headline">Rate sum</span>
							<span class="value" style="font-size: 1.7em;">$ <?= number_format($load_report_line_haul_sum, 2) ?></span>
						</div>
					</div>

					<div class="col-lg-4 col-sm-6 col-xs-12">
						<div class="main-box infographic-box colored emerald-bg">
							<i class="fa fa-cube"></i>
							<span class="headline">Loads / Avg rate</span>
							<span class="value" style="font-size: 1.7em;"><?= number_format($load_report_count) ?> / $<?= number_format($load_report_line_haul_sum / $load_report_count, 2) ?></span>
						</div>
					</div>

					<div class="col-lg-4 col-sm-6 col-xs-12">
						<div class="main-box infographic-box colored red-bg">
							<i class="fa fa-road"></i>
							<span class="headline">Miles</span>
							<span class="value" style="font-size: 1.7em;"><?= number_format($load_report_miles_sum, 2) ?></span>
						</div>
					</div>

					<!-- <div class="col-lg-3 col-sm-6 col-xs-12">
						<div class="main-box infographic-box colored purple-bg">
							<i class="fa fa-truck"></i>
							<span class="headline">Trucks / Avg rate</span>
							<span class="value" style="font-size: 1.7em;"><?= $load_report_driver_count ?> / $<?= number_format($load_report_line_haul_sum / $load_report_driver_count, 2) ?></span>
						</div>
					</div> -->
				</div>

		  	<table class="table table-hover" id="reports-table">
			  	<thead>
						<tr>
							<th class="hidden"></th>
							<th><small>Driver</small></th>
							<th><small>Origin</small></th>
							<th><small>Destination</small></th>
							<th><small>Rate</small></th>
							<th><small>Miles</small></th>
							<th><small>Pick up date</small></th>
							<th><small>Destination date</small></th>
							<th></th>
						</tr>
					</thead>

					<tbody>

						<?php

						for ($i = 1; $i <= $load_report_count; $i++) { ?>
							
							<tr>
								
								<td class="hidden">
								<!-- This hidden td is to kill different formatting on the first item -->
								</td>

								<td>

									<small>

										<?= $user_list_id_name[$load_report_entry_driver_id[$i]] . ' ' . $user_list_id_last_name[$load_report_entry_driver_id[$i]]  ?>
									</small>
								</td>

								<td>

									<small data-toggle="tooltip" data-placement="top" title="<?= $load_first_checkpoint_zip_code[$i] ?>">

										<?= $load_first_checkpoint_city[$i] . ', ' . $state_abbr[$load_first_checkpoint_state_id[$i]] ?>
									</small>
								</td>

								<td>

									<small data-toggle="tooltip" data-placement="top" title="<?= $load_last_checkpoint_zip_code[$i] ?>">

										<?= $load_last_checkpoint_city[$i] . ', ' . $state_abbr[$load_last_checkpoint_state_id[$i]] ?>
									</small>
								</td>

								<td class="text-right">

									<small>

										$ <?= $load_report_line_haul_format_1[$i] ?>
									</small>
								</td>

								<td class="text-right">

									<small>

										<?= $load_report_miles[$i] ?>
									</small>
								</td>

								<td>

									<small>

										<?= $load_report_first_checkpoint_date_time[$i] ?>
									</small>
								</td>

								<td>

									<small>

										<?= $load_report_last_checkpoint_date_time[$i] ?>
									</small>
								</td>

								<td>
									
									<a data-toggle="tooltip" data-placement="top" title="" href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $load_report_load_id[$i] ?>" data-original-title="View load">
										<i class="fa fa-cube"></i>
									</a>
								</td>
							</tr> <?php
						} ?>

					</tbody>
			  </table>
				
			</div>
		</div>
	</div>	
</div>

<?php

require TEMPLATE_PATH .'/back-end/bottom.php';

?>

