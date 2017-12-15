<?php 
session_start();
ob_start();
?>
<header class="navbar" id="header-navbar">
	<div class="container">
		<a href="<?= $_SESSION['href_location'] ?>dashboard/" id="logo" class="navbar-brand text-center">
			QUANTUM
		</a>
		<div class="clearfix">
			<button class="navbar-toggle" data-target=".navbar-ex1-collapse" data-toggle="collapse" type="button">
				<span class="sr-only">Toggle navigation</span>
				<span class="fa fa-bars"></span>
			</button>		
			<div class="nav-no-collapse navbar-left pull-left hidden-sm hidden-xs">
				<ul class="nav navbar-nav pull-left">
					<!-- <li>
						<a class="btn" id="make-small-nav">
							<i class="fa fa-bars"></i>
						</a>
					</li> -->

					<?php 
					# Hide for external users
					if ($user->data()->user_group != 4) {
						
						if ($ppg_list_count) { ?>
						 	
							<li class="dropdown hidden-xs">
								<a class="btn dropdown-toggle" data-toggle="dropdown">
									<i class="fa fa-book"></i> PPGs
									<i class="fa fa-caret-down"></i>
								</a>
								<ul class="dropdown-menu">

									<?php
									for ($i = 1; $i <= $ppg_list_count; $i++) { ?>
									 	
									 	<li class="item">
											<a href="<?= $_SESSION['href_location'] ?>dashboard/core/ppg?file_name=<?= $ppg_list_file_name[$i] ?>">
												<?= $ppg_list_title[$i] ?>
											</a>
										</li> <?php
									}
									?>

									<li class="item">

										<div style="padding: 0 10px;">
											
											<form action="" method="post">
					
												<div class="form-group">
													
											   	<input data-toggle="tooltip" data-placement="top" title="Press enter to save" name="title" class="form-control" type="text" placeholder="Add new item. Be specific.">
												</div>

												<input type="hidden" name="_controller_ppg" value="add">
											  <input type="hidden" name="token" value="<?= $csrfToken ?>">
											</form>
										</div>
									</li>
										
								</ul>
							</li> <?php
						}  else { ?>

							<a class="btn btn-link" style="color: #fff; margin-top: 10px;" href="#" data-toggle="modal" data-target="#ht">
								<i class="fa fa-book"></i> How to
							</a> <?php
						}
					}
					?>

					<a class="btn btn-link" style="color: #fff; margin-top: 10px;" href="#" data-toggle="modal" data-target="#zm">
						<i class="fa fa-map"></i> ZM
					</a>

				</ul>
			</div>
			
			<div class="nav-no-collapse pull-right" id="header-nav">
				<ul class="nav navbar-nav pull-right">
					<li class="dropdown profile-dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							
							<?= file_exists($img_content_directory . '/user/avatar/' . $user->data()->id . '.jpg') ? '<img src="'. $_SESSION['HtmlDelimiter'] .'img/content/user/avatar/' . $user->data()->id . '.jpg' . '?r='.date('Gis') . '" alt="" />' : '<div style="position: relative;width: 50px;max-width: 50px;float: left;margin-right: 15px;border-radius: 18%;background-clip: padding-box;text-align: center;"><span class="fa fa-user" style="font-size: 2em;"></span></div>'; ?>

							<span class="hidden-xs"><?= $user->data()->name . ' ' . $user->data()->last_name ?></span> <b class="caret"></b>
						</a>
						<ul class="dropdown-menu dropdown-menu-right">
							<li><a href="<?= $_SESSION['href_location'] ?>dashboard/account"><i class="fa fa-cog"></i><?= $core_language[2] ?></a></li>
							<li><a href="<?= $_SESSION['href_location'] ?>logout"><i class="fa fa-power-off"></i><?= $core_language[3] ?></a></li>
						</ul>
					</li>
					<li class="hidden-xxs">
						<a class="btn" href="<?= $_SESSION['href_location'] ?>logout">
							<i class="fa fa-power-off"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</header>