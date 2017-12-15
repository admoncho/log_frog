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

	<?php if (isset($_GET['edit_personal_info'])) {
		
		# Editing personal info ?>
		<div class="col-sm-12 col-md-6">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2 class="pull-left"><?= $core_language[5] ?></h2>
				</header>
				
				<div class="main-box-body clearfix">
					<form action="" method="post">
						<div class="form-group">
							<label><?= $core_language[9] ?></label>
							<input type="text" name="name" value="<?= $user->data()->name ?>" class="form-control">
						</div>
						<div class="form-group">
							<label><?= $core_language[10] ?></label>
							<input type="text" name="last_name" value="<?= $user->data()->last_name ?>" class="form-control">
						</div>
						<div class="form-group">
							<label><?= $core_language[11] ?></label>
							<input type="text" name="email" value="<?= $user->data()->email ?>" class="form-control">
						</div>
						<div class="form-group text-right">
							<button type="submit" class="btn btn-primary"><?= $core_language[17] ?></button>
							<a style="margin: 15px;" class="pull-right red" href="account"><?= $core_language[18] ?></a>
						</div>
						<input type="hidden" name="_controller_update_personal_info" value="1">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
				</div>
			</div>
		</div>

		<!-- <div class="col-sm-12 col-md-6">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2 class="pull-left"><?= $core_language[70] ?></h2>
				</header>
				
				<div class="main-box-body clearfix">
					<form action="" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<input type="file" name="image" accept="image/jpg, image/jpeg" class="btn btn-default pull-left" style="margin-right: 10px;">
							<button type="submit" class="btn btn-primary"><?= $core_language[21] ?></button>
						</div>
						<input type="hidden" name="_controller_update_profile_picture" value="add">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>

					<?php
					# If user has avatar
					// echo file_exists($img_content_directory . '/user/avatar/' . $user->data()->id . '.jpg') ? '<img src="<?= $_SESSION['HtmlDelimiter'] ?>img/content/user/avatar/' . $user->data()->id . '.jpg' . '?r=' . date('Gis') . '" alt="" class="profile-img img-responsive center-block alt="" />' : '';	

					?>

				</div>
			</div>
		</div> --> <?php
	} else {

		# Read-only data ?>

		<div class="col-sm-12 col-md-6<?= $_GET ? ($_GET['edit_password'] || $_GET['edit_quantum_settings'] ? ' hidden' : '') : '' # Only check if there is 1 get parameter ?>">
			<div class="main-box clearfix">
				<header class="main-box-header clearfix">
					<h2 class="pull-left"><?= $core_language[5] ?></h2>
					
					<div class="icon-box pull-right">

						<?php
						# Hide button if editing
						echo isset($_GET['edit_personal_info']) ? '' : '<a href="account?edit_personal_info=1" class="btn pull-left" data-toggle="tooltip" data-placement="top" title="' . $core_language[25] . '"><i class="fa fa-edit"></i></a>';
						?>

					</div>
				</header>
				
				<div class="main-box-body clearfix">
					<p><b><?= $core_language[9] ?>:</b> <?= $user->data()->name ?></p>
					<p><b><?= $core_language[10] ?>:</b> <?= $user->data()->last_name ?></p>
					<p><b><?= $core_language[11] ?>:</b> <?= $user->data()->email ?> <a href="account?edit_password=1" class="pull-right red"><?= $core_language[19] . ' ' . strtolower($core_language[20]) ?></a> </p>
				</div>
			</div>
		</div> <?php
	}
	?>

	<div class="col-sm-12 col-md-6<?= $_GET ? ($_GET['edit_quantum_settings'] ? '' : ' hidden') : '' # Only check if there is 1 get parameter ?>">
		<div class="main-box clearfix">
			<header class="main-box-header clearfix">
				<h2 class="pull-left"><?= $core_language[6] ?></h2>
				

				<div class="icon-box pull-right">

					<?php
					# Hide button if editing
					echo isset($_GET['edit_quantum_settings']) ? '' : '<a href="account?edit_quantum_settings=1" class="btn pull-left" data-toggle="tooltip" data-placement="top" title="' . $core_language[25] . '"><i class="fa fa-edit"></i></a>';
					?>

				</div>
			</header>
			
			<div class="main-box-body clearfix">

				<?php
				if (isset($_GET['edit_quantum_settings'])) {
					
					# Editing personal info ?>
					<form action="" method="post">
						<div class="form-group">
							<label><?= $core_language[12] ?></label>
							<select name="language_id" class="form-control">
								<option value="1"<?= $settings_language_id == 1 ? ' selected' : '' ?>><?= $core_language[7] ?></option>
								<option value="2"<?= $settings_language_id == 2 ? ' selected' : '' ?>><?= $core_language[8] ?></option>
							</select>
						</div>
						<div class="form-group">
							<label><?= $core_language[13] ?></label>
							<select name="nav" class="form-control">
								<option value="1"<?= $settings_nav == 1 ? ' selected' : '' ?>><?= $core_language[15] ?></option>
								<option value="2"<?= $settings_nav == 2 ? ' selected' : '' ?>><?= $core_language[16] ?></option>
							</select>
						</div>
						<!-- <div class="form-group">
							<label><?= $core_language[14] ?></label>
							<select name="theme_id" class="form-control">

								<?php
								# Loop through themes
								/*for ($i=1; $i <= $quantum_theme_count ; $i++) { ?>
									<option value="<?= $theme_id[$i] ?>"<?= $settings_theme_id == $theme_id[$i] ? ' selected' : '' ?>><?= $theme_name[$i] ?></option> <?php
								}*/
								?>

							</select>
						</div> -->
						<div class="form-group text-right">
							<button type="submit" class="btn btn-primary"><?= $core_language[17] ?></button>
							<a style="margin: 15px;" class="pull-right red" href="account"><?= $core_language[18] ?></a>
						</div>
						<input type="hidden" name="_controller_update_quantum_settings" value="1">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form> <?php
				} else {

					# Read-only data ?>
					<p><b><?= $core_language[12] ?>:</b> <?= $settings_language_id == 1 ? $core_language[7] : $core_language[8] ?></p>
					<p><b><?= $core_language[13] ?>:</b> <?= $settings_nav == 1 ? $core_language[15] : $core_language[16] ?></p>
					<!-- <p><b><?= $core_language[14] ?>:</b> <?= $theme_name[$settings_theme_id] ?></p> --> <?php
				}
				?>

			</div>
		</div>
	</div>
	<div class="col-sm-12 col-md-6<?= $_GET ? ($_GET['edit_password'] ? '' : ' hidden') : ' hidden' # Only check if there is 1 get parameter ?>">
		<div class="main-box clearfix">
			<header class="main-box-header clearfix">
				<h2 class="pull-left"><?= $settings_language_id == 1 ? $core_language[21] . ' ' . strtolower($core_language[20]) : $core_language[21] . ' ' . strtolower($core_language[20]) ?></h2>
			</header>
			
			<div class="main-box-body clearfix">
				<form action="" method="post">
					<div class="form-group">
						<label><?= $settings_language_id == 1 ? $core_language[22] . ' ' . strtolower($core_language[20]) : $core_language[20] . ' ' . strtolower($core_language[22]) ?></label>
						<input type="password" name="password" class="form-control">
					</div>
					<div class="form-group">
						<label><?= $settings_language_id == 1 ? $core_language[23] . ' ' . strtolower($core_language[20]) : $core_language[20] . ' ' . strtolower($core_language[23]) ?></label>
						<input type="password" name="password_1" class="form-control">
					</div>
					<div class="form-group">
						<label><?= $settings_language_id == 1 ? $core_language[24] . ' ' . strtolower($core_language[23]) . ' ' . strtolower($core_language[20]) : $core_language[24] . ' ' . strtolower($core_language[20]) . ' ' . strtolower($core_language[23]) ?></label>
						<input type="password" name="password_2" class="form-control">
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary"><?= $core_language[17] ?></button>
						<a style="margin: 15px;" class="pull-right red" href="account"><?= $core_language[18] ?></a>
					</div>
					<input type="hidden" name="_controller_update_password" value="1">
					<input type="hidden" name="token" value="<?= $csrfToken ?>">
				</form>
			</div>
		</div>
	</div>
</div>

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
