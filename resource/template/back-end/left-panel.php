<?php
session_start();
ob_start();
?>
<div id="nav-col">
	<section id="col-left" class="col-left-nano">
		<div id="col-left-inner" class="col-left-nano-content">
			<div id="user-left-box" class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">
				<?php # Check files
				
				if (file_exists($img_content_directory . '/user/avatar/' . $user->data()->id . '.jpg')) { ?>
          <img alt="" src="<?= $_SESSION["href_location"] ?>img/content/user/avatar/<?= $user->data()->id . '.jpg' .'?r='.date('Gis') ?>" /><?php
        } else { ?>
        	<div style="position: relative;width: 50px;max-width: 50px;float: left;margin-right: 15px;border-radius: 18%;background-clip: padding-box;text-align: center;">
        		<span class="fa fa-user" style="font-size: 3em;"></span>
        	</div> <?php
        } ?>
				<div class="user-box">
					<span class="name">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<?= $user->data()->name . ' ' . $user->data()->last_name ?>
							<i class="fa fa-angle-down"></i>
						</a>
						<ul class="dropdown-menu">
							<li><a href="<?= $_SESSION['href_location'] ?>dashboard/account"><i class="fa fa-cog"></i><?= $core_language[2] ?></a></li>
							<li><a href="<?= $_SESSION['href_location'] ?>logout"><i class="fa fa-power-off"></i><?= $core_language[3] ?></a></li>
						</ul>
					</span>
					<span class="status">
						<i class="fa fa-circle"></i> <?= $core_language[4] ?>
					</span>
				</div>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">	
				<ul class="nav nav-pills nav-stacked">

					<li>
						<a href="<?= $_SESSION['href_location'] ?>dashboard/" data-toggle="tooltip" data-placement="right" title="Dashboard">
							<i class="fa fa-dashboard"></i>
							<span><?= $core_language[1] ?></span>
						</a>
					</li>

					<?php
					for ($i = 1; $i <= $module_list_count ; $i++) {

						if ($user->data()->user_group == 4) {
			
							# External user module links
							# Only display loader
							if ($module_list_name[$i] == 'loader') { ?>
			
								<li>
									<a href="<?= $_SESSION['href_location'] ?>dashboard/<?= $module_list_path[$i] ?>" data-toggle="tooltip" data-placement="right" title="<?= $module_list_id[$i] == 1 ? 'Dashboard' : str_replace('_', ' ', ucfirst($module_list_name[$i])) ?>">
										<i class="<?= $module_list_icon[$i] ?>"></i>
										<span><?// This is disabled = $module_list_id[$i] == 1 ? 'Dashboard' : ucfirst($module_list_name[$i]) ?></span>
									</a>
								</li> <?php
							}
						} else {

							# Internal user module links
							# Hide core and component
							if ($module_list_name[$i] != 'core' && $module_list_name[$i] != 'component') {

								# Show invoice module only to those with invoice permissions
								if ($module_list_name[$i] == 'invoice') {
								 	
								 	# Check user permissions
								 	if ($user->hasPermission('invoice')) { ?>
								 		
								 		<li>
											<a href="<?= $_SESSION['href_location'] ?>dashboard/<?= $module_list_path[$i] ?>" data-toggle="tooltip" data-placement="right" title="<?= $module_list_id[$i] == 1 ? 'Dashboard' : str_replace('_', ' ', ucfirst($module_list_name[$i])) ?>">
												<i class="<?= $module_list_icon[$i] ?>"></i>
												<span><?// This is disabled = $module_list_id[$i] == 1 ? 'Dashboard' : ucfirst($module_list_name[$i]) ?></span>
											</a>
										</li> <?php 
								 	}
								} elseif ($module_list_name[$i] == 'reports') {

									if ($user->hasPermission('reports')) {

										# Only internal users can see reports
										if ($user->data()->user_group != 4) { ?>
									 		
									 		<li>
												<a href="<?= $_SESSION['href_location'] ?>dashboard/<?= $module_list_path[$i] ?>" data-toggle="tooltip" data-placement="right" title="<?= $module_list_id[$i] == 1 ? 'Dashboard' : str_replace('_', ' ', ucfirst($module_list_name[$i])) ?>">
													<i class="<?= $module_list_icon[$i] ?>"></i>
													<span><?// This is disabled = $module_list_id[$i] == 1 ? 'Dashboard' : ucfirst($module_list_name[$i]) ?></span>
												</a>
											</li> <?php 	
										}
									}
								} else { ?>

									<li>
										<a href="<?= $_SESSION['href_location'] ?>dashboard/<?= $module_list_path[$i] ?>" data-toggle="tooltip" data-placement="right" title="<?= $module_list_id[$i] == 1 ? 'Dashboard' : str_replace('_', ' ', ucfirst($module_list_name[$i])) ?>">
											<i class="<?= $module_list_icon[$i] ?>"></i>
											<span><?// This is disabled = $module_list_id[$i] == 1 ? 'Dashboard' : ucfirst($module_list_name[$i]) ?></span>
										</a>
									</li> <?php
								}
							}
						}
					} ?>
					
					<!-- <li>
						<a href="/dashboard/account">
							<i class="fa fa-cog"></i>
							<span><?= $core_language[2] ?></span>
						</a>
					</li> -->
				</ul>
			</div>
		</div>
	</section>
	<div id="nav-col-submenu"></div>
</div>
