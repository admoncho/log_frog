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

	<?php

	if (Session::exists('create_user')) { ?>

		<div class="col-sm-12 col-md-12">
			<div class="alert alert-success fade in">
		    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		    <i class="fa fa-check fa-fw fa-lg"></i>
		    <?= Session::flash('create_user') ?>
		  </div>
		</div> <?php
	}

	/* REPORTING */ ?>

	<canvas id="canvas" height="100"></canvas>

	<?php

	/* REPORTING */

	# Loop through modules
	for ($i = 1; $i <= $module_list_count ; $i++) {

		
		if ($user->data()->user_group == 4) {
			
			# External user module links
			# Only display loader for now
			if ($module_list_name[$i] == 'loader' || $module_list_name[$i] == 'invoice') { ?>
			 		
				<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
					<a style="display: block;" href="<?= $module_list_path[$i] ?>/">

						<div class="main-box weather-box">
							<header class="main-box-header clearfix">
								<h2 class="pull-left"><?= ucfirst(str_replace('_', ' ', $module_list_name[$i])) ?></h2>
							</header>
							
							<div class="main-box-body clearfix">
								<div class="current">
									<div class="clearfix center-block" style="width: 220px;">
										<span class="<?= $module_list_icon[$i] ?> fa-5x" style="color: #000; text-shadow: 2px 2px 2px #03a9f4;"></span>
									</div>
								</div>

								<div class="next text-center">
									<a style="color: #fff;" href="<?= $module_list_path[$i] ?>/"><?= ucfirst(str_replace('_', ' ', $module_list_name[$i])) ?></a>
								</div>
							</div>
						</div>
					</a>
				</div> <?php
			}
		} else {

			# Internal user module links
			# Don't display core module on this list
			if ($module_list_name[$i] != 'core' && $module_list_name[$i] != 'component') {

				# Show invoice module only to those with invoice permissions
				if ($module_list_name[$i] == 'invoice') {

					# Check user permissions
					if ($user->hasPermission('invoice')) { ?>
			 		
						<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
							<a style="display: block;" href="<?= $module_list_path[$i] ?>/">

								<div class="main-box weather-box">
									<header class="main-box-header clearfix">
										<h2 class="pull-left"><?= ucfirst(str_replace('_', ' ', $module_list_name[$i])) ?></h2>
									</header>
									
									<div class="main-box-body clearfix">
										<div class="current">
											<div class="clearfix center-block" style="width: 220px;">
												<span class="<?= $module_list_icon[$i] ?> fa-5x" style="color: #000; text-shadow: 2px 2px 2px #03a9f4;"></span>
											</div>
										</div>

										<div class="next text-center">
											<a style="color: #fff;" href="<?= $module_list_path[$i] ?>/"><?= ucfirst(str_replace('_', ' ', $module_list_name[$i])) ?></a>
										</div>
									</div>
								</div>
							</a>
						</div> <?php
					}
				} else { ?>

					<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
						<a style="display: block;" href="<?= $module_list_path[$i] ?>/">

							<div class="main-box weather-box">
								<header class="main-box-header clearfix">
									<h2 class="pull-left"><?= ucfirst(str_replace('_', ' ', $module_list_name[$i])) ?></h2>
								</header>
								
								<div class="main-box-body clearfix">
									<div class="current">
										<div class="clearfix center-block" style="width: 220px;">
											<span class="<?= $module_list_icon[$i] ?> fa-5x" style="color: #000; text-shadow: 2px 2px 2px #03a9f4;"></span>
										</div>
									</div>

									<div class="next text-center">
										<a style="color: #fff;" href="<?= $module_list_path[$i] ?>/"><?= ucfirst(str_replace('_', ' ', $module_list_name[$i])) ?></a>
									</div>
								</div>
							</div>
						</a>
					</div> <?php
				}
			}
		}
	}
	?>

</div>

<?php require TEMPLATE_PATH . '/back-end/bottom.php'; ?>