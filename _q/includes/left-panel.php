<?php 
session_start();
ob_start();
?>
<div id="nav-col">
	<section id="col-left" class="col-left-nano">
		<div id="col-left-inner" class="col-left-nano-content">
			<div id="user-left-box" class="clearfix hidden-sm hidden-xs dropdown profile2-dropdown">
				<?php # Check files
				file_exists('/home/' . $rootFolder . '/public_html/_q/img/user-i/' . $user->data()->user_id.'.jpg') ? $ext = '.jpg' : '' ;
				file_exists('/home/' . $rootFolder . '/public_html/_q/img/user-i/' . $user->data()->user_id.'.jpeg') ? $ext = '.jpeg' : '' ;
				file_exists('/home/' . $rootFolder . '/public_html/_q/img/user-i/' . $user->data()->user_id.'.png') ? $ext = '.png' : '' ;
				file_exists('/home/' . $rootFolder . '/public_html/_q/img/user-i/' . $user->data()->user_id.'.gif') ? $ext = '.gif' : '' ;
				if (file_exists('/home/' . $rootFolder . '/public_html/_q/img/user-i/' . $user->data()->user_id.$ext)) { ?>
                    <img alt="" src="<?= $_SESSION["href_location"] ?>img/user-i/<?= $user->data()->user_id.$ext.'?r='.date('Gis') ?>" /><?php
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
							<li><a href="<?= $_SESSION['href_location'] ?>account"><i class="fa fa-cog"></i><?= $_QC_language[19] ?></a></li>
							<li><a href="<?= $_SESSION['href_location'] ?>logout"><i class="fa fa-power-off"></i><?= $_QC_language[21] ?></a></li>
						</ul>
					</span>
					<span class="status">
						<i class="fa fa-circle"></i> <?= $_QC_language[22] ?>
					</span>
				</div>
			</div>
			<div class="collapse navbar-collapse navbar-ex1-collapse" id="sidebar-nav">	
				<ul class="nav nav-pills nav-stacked">
					<li>
						<a href="<?= $_SESSION['href_location'] ?>0/">
							<i class="fa fa-dashboard"></i>
							<span><?= $_QC_language[23] ?></span>
						</a>
					</li>

					<li>
						<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/">
							<i class="fa fa-truck"></i>
							<span>Loader</span>
						</a>
					</li>

					<li>
						<a href="<?= $_SESSION['href_location'] ?>dashboard/client/">
							<i class="fa fa-users"></i>
							<span>Clients</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</section>
	<div id="nav-col-submenu"></div>
</div>
