<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath']. '/core/init.php';

# Limbo
include($_SESSION['ProjectPath']."/includes/limbo.php");

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

$geo_state_list = DB::getInstance()->query("SELECT * FROM geo_state");
foreach ($geo_state_list->results() as $geo_state_list_data) {
	$state_abbr[$geo_state_list_data->state_id] = $geo_state_list_data->abbr;
}

$user_e_global_list = DB::getInstance()->query("SELECT * FROM _QU_e");
foreach ($user_e_global_list->results() as $user_e_global_list_data) {
	$user_external_name[$user_e_global_list_data->user_id] = $user_e_global_list_data->name;
	$user_external_last_name[$user_e_global_list_data->user_id] = $user_e_global_list_data->last_name;
}

$user_e_group_global = DB::getInstance()->query("SELECT * FROM _QU_e_group");
foreach ($user_e_group_global->results() as $user_e_group_global_data) {
	$glogal_group_name[$user_e_group_global_data->group_id] = $user_e_group_global_data->name;
}

if ($_GET) {
    # Get _hp_ input (ONLY 1 _hp_ INPUT PER FORM)
    foreach ($_GET as $input_name => $value) {
        # $input_name saves the input field name
        # $value saves the input field value (not used for this example)
        if (preg_match('/_hp_/', $input_name)) {
            $_hp_name = $input_name;
        }
    }

    # CONTROLLER CALLS
    // Get controllers
    $_QC_module_controller = DB::getInstance()->query("SELECT * FROM _QC_module_controller WHERE controller = '" . str_replace('_hp_', '', $_hp_name) . "'");
    foreach ($_QC_module_controller->results() as $_QC_module_controller_data) {
        Input::get($_hp_name) ? include_once($_SESSION['ProjectPath']."/includes/controller/$_QC_module_controller_data->controller.php") : '' ;
    }
}

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();

		# Get _hp_ input (ONLY 1 _hp_ INPUT PER FORM)
        foreach ($_POST as $input_name => $value) {
        	# $input_name saves the input field name
        	# $value saves the input field value (not used for this example)
			if (preg_match('/_hp_/', $input_name)) {
				$_hp_name = $input_name;
			}
		}

        # CONTROLLER CALLS
        // Get controllers
        $_QC_module_controller = DB::getInstance()->query("SELECT * FROM _QC_module_controller WHERE controller = '" . str_replace('_hp_', '', $_hp_name) . "'");
        foreach ($_QC_module_controller->results() as $_QC_module_controller_data) {
			Input::get($_hp_name) ? include_once($_SESSION['ProjectPath']."/includes/controller/$_QC_module_controller_data->controller.php") : '' ;
		}

		Input::get('_hp_add_user') ? include_once($_SESSION['ProjectPath']."/includes/controller/user_e_admin_add.php") : '' ;
		Input::get('_hp_delete_user') ? include_once($_SESSION['ProjectPath']."/includes/controller/user_e_admin_delete.php") : '' ;
		Input::get('_hp_activate_user') ? include_once($_SESSION['ProjectPath']."/includes/controller/user_e_admin_activate.php") : '' ;
		Input::get('_hp_update_personal_info') ? include_once($_SESSION['ProjectPath']."/includes/controller/user_e_admin_update_personal_info.php") : '' ;
		Input::get('_hp_update_password') ? include_once($_SESSION['ProjectPath']."/includes/controller/user_e_admin_update_password.php") : '' ;
		Input::get('_hp_user_activation_bypass') ? include_once($_SESSION['ProjectPath']."/includes/controller/user_e_admin_activate.php") : '' ;
		Input::get('_hp_update_user_group') ? include_once($_SESSION['ProjectPath']."/includes/controller/user_e_admin_update_user_group.php") : '' ;
		Input::get('_hp_remove_client_profile') ? include_once($_SESSION['ProjectPath']."/includes/controller/logistic/user_e_remove_client_profile.php") : '' ;
		Input::get('_hp_update_client_profile') ? include_once($_SESSION['ProjectPath']."/includes/controller/logistic/user_e_update_client_profile.php") : '' ;
		Input::get('_hp_create_client_profile') ? include_once($_SESSION['ProjectPath']."/includes/controller/logistic/user_e_admin_create_client_profile.php") : '' ;
		Input::get('_hp_create_driver_profile') ? include_once($_SESSION['ProjectPath']."/includes/controller/logistic/user_e_admin_create_driver_profile.php") : '' ;
		Input::get('_hp_update_driver_profile') ? include_once($_SESSION['ProjectPath']."/includes/controller/logistic/user_e_update_driver_profile.php") : '' ;
		Input::get('_hp_remove_driver_profile') ? include_once($_SESSION['ProjectPath']."/includes/controller/logistic/user_e_remove_driver_profile.php") : '' ;

	}
}

// CHECK FOR USER PROFILES
if ($_GET['id']) {
	// Check if user has a driver profile
	$driver_profile = DB::getInstance()->query("SELECT * FROM user_e_profile_driver WHERE user_id = " . $_GET['id']);
	$driver_profile_count = $driver_profile->count();
	foreach ($driver_profile->results() as $driver_profile_data) {
		$driver_profile_id = $driver_profile_data->data_id;

		// Get client data
		$user_e_profile_client = DB::getInstance()->query("SELECT * FROM user_e_profile_client WHERE data_id = $driver_profile_data->client_id");
		foreach ($user_e_profile_client->results() as $user_e_profile_client_data) {
		 	$client_profile_id = $user_e_profile_client_data->user_id;
		 	$client_profile_data_id = $user_e_profile_client_data->data_id;
		 	$company_name = $user_e_profile_client_data->company_name;
		 	$mc_number = $user_e_profile_client_data->mc_number;
		 	$us_dot_number = $user_e_profile_client_data->us_dot_number;
		 	$ein_number = $user_e_profile_client_data->ein_number;
		 	$phone_number_01 = $user_e_profile_client_data->phone_number_01;
		 	$phone_number_02 = $user_e_profile_client_data->phone_number_02;
		}
	}

	# BROKER REMOVED
	/*// Check if user has a broker profile
	$broker_profile = DB::getInstance()->query("SELECT * FROM user_e_profile_broker WHERE user_id = " . $_GET['id']);
	$broker_profile_count = $broker_profile->count();
	foreach ($broker_profile->results() as $broker_profile_data) {
		$broker_profile_id = $broker_profile_data->data_id;
	}

	// Check if user IS associated to a broker profile
	$user_e_profile_broker_assoc = DB::getInstance()->query("SELECT * FROM user_e_profile_broker_assoc WHERE user_id = " . $_GET['id']);
	$user_e_profile_broker_assoc_count = $user_e_profile_broker_assoc->count();
	if ($user_e_profile_broker_assoc_count) {
		foreach ($user_e_profile_broker_assoc->results() as $user_e_profile_broker_assoc_data) {
			$broker_profile_data_id = $user_e_profile_broker_assoc_data->broker_id;
			$broker_assoc_added = date('M d Y', $user_e_profile_broker_assoc_data->added);
		}
	}*/

	// Get user group
	$_QU_e_group_assoc = DB::getInstance()->query("SELECT * FROM _QU_e_group_assoc WHERE user_id = " . $_GET['id']);
	foreach ($_QU_e_group_assoc->results() as $_QU_e_group_assoc_data) {
		$user_group = $_QU_e_group_assoc_data->group_id;
	}
}

# Query for this user_id's group_id and group name
$_QU_e_group_assoc = DB::getInstance()->query("SELECT * from _QU_e_group_assoc WHERE user_id = " . $_GET['id']);
foreach ($_QU_e_group_assoc->results() as $_QU_e_group_assoc_value) {
	$group_id = $_QU_e_group_assoc_value->group_id;

	// Get name
	$_QU_e_group = DB::getInstance()->query("SELECT * from _QU_e_group WHERE group_id = " . $group_id);
	foreach ($_QU_e_group->results() as $_QU_e_group_value) {
		$group_name = $_QU_e_group_value->name;
	}
}


# BROKER REMOVED
/*Session::exists('add_user_e_broker_profile') 	|| Session::exists('add_user_e_broker_profile_error')		? $pushTab = 'push_tab_broker' : '' ;
Session::exists('update_user_e_broker_profile') || Session::exists('update_user_e_broker_profile_error')	? $pushTab = 'push_tab_broker' : '' ;
Session::exists('add_user_e_broker_assoc') 		|| Session::exists('add_user_e_broker_assoc_error')			? $pushTab = 'push_tab_broker' : '' ;
Session::exists('delete_broker_profile') || Session::exists('delete_broker_profile_error') 					? $pushTab = 'push_tab_broker' : '' ;
Session::exists('add_broker_assoc') || Session::exists('add_broker_assoc_error') 							? $pushTab = 'push_tab_broker' : '' ;
$_GET['delete_broker_profile'] 																				? $pushTab = 'push_tab_broker' : '' ;*/

// Disable $_QC_language[73] if $_GET['deleted']
$_GET['deleted'] ? $_QC_language[73] = '' : '' ;

$csrfToken = Token::generate(); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title></title>
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/nanoscroller.css" />
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/compiled/theme_styles.css" />
	<!-- this page specific styles -->
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/nifty-component.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-default.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-growl.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-bar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-attached.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-other.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-theme.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/compiled/wizard.css">
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/footable.core.css" />
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/html5shiv.js"></script>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
	<![endif]-->
</head>
<body class="<?= $_IA_skin_class[$config_skin] ?>">
	<div class="md-modal md-effect-5" id="modal-new-user">
		<div class="md-content">
			<?php # STEP 1.1 - Add user ?>
			<form role="form" action="" method="post">
				<div class="modal-header">
					<button class="md-close close">&times;</button>
					<h4 class="modal-title"><?= $_QC_language[81] ?></h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<input name="name" class="form-control" type="text" placeholder="<?= $_QC_language[8] ?>">
					</div>
					<div class="form-group">
						<input name="last_name" class="form-control" type="text" placeholder="<?= $_QC_language[9] ?>">	
					</div>
					<div class="form-group">
						<input name="email" class="form-control" type="email" placeholder="<?= $_QC_language[1] ?>">
					</div>
					<div class="form-group">
						<input name="password" type="password" class="form-control" placeholder="<?= $_QC_language[2] ?>">
					</div>
					<div class="form-group">
						<input name="password_again" type="password" class="form-control" placeholder="<?= $_QC_language[10] ?>">
					</div>
					<?php /*# Check how many groups are there
					if ($_QU_e_group_count === 1) {
						# If only one group exists

						# Get group_id
						# Cannot assume group_id === 1
						$_QU_e_group = DB::getInstance()->query("SELECT * FROM _QU_e_group");
						foreach ($_QU_e_group->results() as $_QU_e_group_data) {
							echo '<input type="hidden" name="group_id" value="' . $_QU_e_group_data->group_id . '">';	
						}
					} else {
						# If there are more than one group, ask for it in form ?>
						<select name="group_id" class="form-control">
							<option>User group</option>
							<?php $_QU_e_group = DB::getInstance()->query("SELECT * FROM _QU_e_group");
							foreach ($_QU_e_group->results() as $_QU_e_group_data) { ?>
								<option value="<?= $_QU_e_group_data->group_id ?>"><?= $_QU_e_group_data->name ?></option> <?php
							} ?>
						</select> <?php
					}*/ ?>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-primary"><?= $_QC_language[39] ?></button>
				</div>
				<input type="hidden" name="_hp_add_user" value="1">
				<input type="hidden" name="token" value="<?= $csrfToken ?>">
			</form>
		</div>
	</div>
	<div id="theme-wrapper">
		<?php include($_SESSION['ProjectPath']."/includes/header.php") ?>
		<div id="page-wrapper" class="container<?= $config_nav == 1 ? ' nav-small' : '' ?>">
			<div class="row">
				<?php include($_SESSION['ProjectPath']."/includes/left-panel.php") ?>
				<div id="content-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<?php if (!$_GET['id'] && !$_GET['info']) { ?>
								<div class="row">
									<div class="col-lg-12">
										<ol class="breadcrumb">
											<li><a href="<?= $_SESSION['href_location'] ?>0/"><?= $_QC_language[23] ?></a></li>
											<li class="active"><span><?= $_QC_language[18] ?>s</span></li>
										</ol>
										<div class="clearfix">
											<h1 class="pull-left"><?= $_QC_language[18] ?>s</h1>
											
											<div class="pull-right top-page-ui">
												<?php if ($user->hasPermission('ceo') || $user->hasPermission('quantumSupport')) { ?>
													<button data-toggle="tooltip" data-placement="left" title="<?= $config_language == 1 ? $_QC_language[75] . ' ' . $_QC_language[18] : $_QC_language[75] . ' ' . strtolower($_QC_language[18]) ?>" class="md-trigger btn btn-primary mrg-b-lg" data-modal="modal-new-user"><i class="fa fa-user-plus fa-4"></i></button><?php
												} ?>
												<a data-toggle="tooltip" data-placement="left" title="<?= $config_language == 1 ? $_QC_language[73] . ' ' . $_QC_language[18] . 's' : $_QC_language[18] . 's ' . strtolower($_QC_language[73]) ?>" href="<?= $_SESSION['href_location'] ?>0/user-e<?= !$_GET['deleted'] ? '?deleted=1' : '' ?>" class="md-trigger btn btn-<?= !$_GET['deleted'] ? 'danger' : 'primary' ?> mrg-b-lg"><?= !$_GET['deleted'] ? '<i class="fa fa-user-times"></i>' : '<i class="fa fa-users"></i>' ?></a>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12">
										<div class="main-box no-header clearfix">
											<div class="main-box-body clearfix">
												<div class="table-responsive">
													<div class="filter-block pull-right">
														<div class="form-group pull-left">
															<input type="text" id="filter" class="form-control" placeholder="Search...">
															<i class="fa fa-search search-icon"></i>
														</div>
													</div>
													<table class="table footable toggle-circle-filled" data-page-size="10" data-filter="#filter" data-filter-text-only="true">
														<thead>
															<tr>
																<th><span><?= $_QC_language[8] ?></span></th>
																<th><span><?= $_QC_language[1] ?></span></th>
																<th><span><?= $_QC_language[76] ?></span></th>
																<th class="text-center"><span><?= $_QC_language[77] ?></span></th>
																<th>&nbsp;</th>
															</tr>
														</thead>
														<tbody>
															<?php $_GET['deleted'] ? $user_e_list_operator = '=' : $user_e_list_operator = '!=';
															$user_e_list = DB::getInstance()->query("SELECT _QU_e.user_id, _QU_e.email, _QU_e.name, _QU_e.last_name, _QU_e.added, _QG_e.status FROM _QU_e INNER JOIN _QG_e ON _QU_e.user_id=_QG_e.user_id WHERE status $user_e_list_operator 0");
															foreach ($user_e_list->results() as $user_e_list_data) {

																// GET group_id (profile)
																$_QU_e_group_assoc = DB::getInstance()->query("SELECT _QU_e_group_assoc.group_id, _QU_e_group_assoc.user_id, _QU_e_group.name FROM _QU_e_group_assoc INNER JOIN _QU_e_group ON _QU_e_group_assoc.group_id=_QU_e_group.group_id WHERE user_id = $user_e_list_data->user_id");
																foreach ($_QU_e_group_assoc->results() as $_QU_e_group_assoc_data) {
																	$profile_id = $_QU_e_group_assoc_data->group_id;
																	$profile_name = $_QU_e_group_assoc_data->name;
																} ?>
																<tr id="user<?= $user_e_list_data->user_id ?>">
																	<td>
																		<?php if (file_exists('/home/' . $rootFolder . '/public_html/_q/img/user-e/' . $user_e_list_data->user_id . '.png')) { ?>
								                      <img src="<?= $_SESSION['HtmlDelimiter'] ?>img/user-e/<?= $user_e_list_data->user_id ?>.png" alt=""/><?php
								                    } ?>
																		<a href="user-e?id=<?= $user_e_list_data->user_id ?>" class="user-link"><?= $user_e_list_data->name . ' ' . $user_e_list_data->last_name ?></a>
																		<span class="user-subhead"><?= $user_i_group_name[$user_e_list_data->user_group] ?></span>
																	</td>
																	<td>
																		<a href="mailto:<?= $user_e_list_data->email ?>"><?= $user_e_list_data->email ?></a>
																	</td>
																	<td>
																		<?= date('M d Y', strtotime($user_e_list_data->added)) ?>
																	</td>
																	<td class="text-center">
																		<?php if (!$_GET['deleted']) { ?>
																			<span class="label label-<?= $user_e_list_data->status == 1 ? 'success' : 'warning' ?>"><?= $user_e_list_data->status == 1 ? $_QC_language[78] : $_QC_language[80] ?></span> <?php
																		} else { ?>
																			<span class="label label-danger"><?= $_QC_language[68] ?></span> <?php
																		} ?>
																	</td>
																	<td class="text-right">
																		<?php if (!$_GET['delete'] && !$_GET['deleted']) {
																			if ($user->hasPermission('ceo')) { ?>
																				<a href="user-e?id=<?= $user_e_list_data->user_id ?>" class="table-link">
																					<span class="fa-stack">
																						<i class="fa fa-square fa-stack-2x"></i>
																						<i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
																					</span>
																				</a>
																				<a href="<?= $_SESSION['href_location'] ?>0/user-e?delete=<?= $user_e_list_data->user_id ?>#user<?= $user_e_list_data->user_id ?>" class="table-link danger">
																					<span class="fa-stack">
																						<i class="fa fa-square fa-stack-2x"></i>
																						<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
																					</span>
																				</a><?php
																			}
																		} elseif ($_GET['delete'] && $user_e_list_data->user_id == $_GET['delete']) { ?>
																			<form action="" method="post">
																				<button type="submit" class="btn btn-warning"><?= $_QC_language[67] ?></button>
																				<a href="<?= $_SESSION['href_location'] ?>0/user-e" style="margin:0 5px;" class="btn btn-default"><?= $_QC_language[72] ?></a>
																				<input type="hidden" name="_hp_delete_user" value="1">
																				<input type="hidden" name="user_id" value="<?= $user_e_list_data->user_id ?>">
																				<input type="hidden" name="token" value="<?= $csrfToken ?>">
																			</form> <?php
																		} elseif ($_GET['deleted'] && $_GET['reactivate'] && $user_e_list_data->user_id == $_GET['reactivate']) { ?>
																			<form action="" method="post">
																				<button type="submit" class="btn btn-warning"><?= $_QC_language[71] ?></button>
																				<a href="<?= $_SESSION['href_location'] ?>0/user-e?deleted=1" style="margin:0 5px;" class="btn btn-default"><?= $_QC_language[72] ?></a>
																				<input type="hidden" name="_hp_activate_user" value="1">
																				<input type="hidden" name="user_id" value="<?= $user_e_list_data->user_id ?>">
																				<input type="hidden" name="token" value="<?= $csrfToken ?>">
																			</form> <?php
																		} elseif ($_GET['deleted'] && !$_GET['reactivate']) {
																			if ($user->hasPermission('ceo') || $user->hasPermission('quantumSupport')) { ?>
																				<a href="<?= $_SESSION['href_location'] ?>0/user-e?deleted=1&amp;reactivate=<?= $user_e_list_data->user_id ?>#user<?= $user_e_list_data->user_id ?>" class="btn btn-primary">
																					<?= $_QC_language[70] . ' ' . $_QC_language[18] ?>
																				</a> <?php
																			}
																		} ?>
																	</td>
																</tr><?php
															} ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div><?php
							} elseif ($_GET['id']) {
								$user_e = DB::getInstance()->query("SELECT _QU_e.user_id, _QU_e.email, _QU_e.name, _QU_e.last_name, _QU_e.phone_number_01, _QU_e.city, _QU_e.state_id, _QU_e.zip_code, _QU_e.license_number, _QU_e.added, _QG_e.status, _QG_e.email_verification FROM _QU_e INNER JOIN _QG_e ON _QU_e.user_id=_QG_e.user_id WHERE _QU_e.user_id = " . $_GET['id']);
								foreach ($user_e->results() as $user_e_data) {
									// Check if user has a client profile
									$client_profile = DB::getInstance()->query("SELECT * FROM user_e_profile_client WHERE user_id = $user_e_data->user_id");
									$client_profile_count = $client_profile->count(); ?>
									<div class="row">
										<div class="col-lg-12">
											<ol class="breadcrumb">
												<li><a href="<?= $_SESSION['href_location'] ?>0/"><?= $_QC_language[23] ?></a></li>
												<li><a href="<?= $_SESSION['href_location'] ?>0/user-e"><?= $_QC_language[18] ?></a></li>
												<li class="active"><span><?= $_QC_language[34] ?></span></li>
											</ol>
											<h1><?= $_QC_language[34] ?></h1>
										</div>
									</div>
									<div class="row" id="user-profile">
										<div class="col-lg-3 col-md-4 col-sm-4">
											<div class="main-box clearfix">
												<header class="main-box-header clearfix">
													<h2><?= $user_e_data->name . ' ' . $user_e_data->last_name ?></h2>
												</header>
												<div class="main-box-body clearfix">
													<div class="profile-status">
														<i class="fa fa-circle"></i> <?= $_QC_language[22] ?>
													</div>
													<?php if (file_exists('/home/' . $rootFolder . '/public_html/_q/img/user-e/' . $user_e_data->user_id . '.png')) { ?>
								                        <img src="<?= $_SESSION['HtmlDelimiter'] ?>img/user-e/<?= $user_e_data->user_id ?>.png" alt=""/><?php
								                    } else { ?>
								                    	<div style="position: relative;width: 100%;border-radius: 18%;background-clip: padding-box;text-align: center;">
								                    		<span class="fa fa-user" style="font-size: 3em;"></span>
								                    	</div> <?php
								                    } ?>
													<div class="profile-label">
														<span class="label label-danger">
															<?= $_IA_internal_group_name[$user_e_data->user_group] ?></span>
													</div>
													<div class="profile-stars">
														<?php if ($user_e_data->user_group == 1 || $user_e_data->user_group == 2) {
															echo '<span>Super User</span>';
														} elseif ($user_e_data->user_group == 3) {
															echo '<span>Restricted User</span>';
														} ?>
													</div>
													<div class="profile-since">
														<?= $_QC_language[35] ?>: <?= date('m Y', strtotime($user_e_data->added)) ?>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-9 col-md-8 col-sm-8">
											<div class="main-box clearfix">
												<div class="tabs-wrapper profile-tabs">
													<ul class="nav nav-tabs">
														<li<?= !$pushTab ? ' class="active"' : '' ?>><a href="#account-info" data-toggle="tab"><?= $_QC_language[36] ?></a></li>
													</ul>
													<div class="tab-content">
														<div class="tab-pane fade<?= !$pushTab ? ' in active' : '' ?>" id="account-info">
															<div class="row">
																<div class="col-sm-12 col-md-12">
																	<div class="main-box">
																		<header class="main-box-header clearfix">
																			<h2><?= $_QC_language[38] ?></h2>
																		</header>
																		<div class="main-box-body clearfix">
																			<form action="" method="post" role="form">
																				<div class="row">
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="name"><?= $_QC_language[8] ?></label>
																						<input type="text" class="form-control" name="name" id="name" value="<?= $user_e_data->name ?>" placeholder="<?= $_QC_language[8] ?>">
																					</div>
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="last_name"><?= $_QC_language[9] ?></label>
																						<input type="text" class="form-control" name="last_name" id="last_name" value="<?= $user_e_data->last_name ?>" placeholder="<?= $_QC_language[9] ?>">
																					</div>
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="email"><?= $_QC_language[1] ?></label>
																						<input type="text" class="form-control" id="email" name="email" data-toggle="tooltip" data-placement="bottom" title="<?= $user_e_data->email ?>" value="<?= $user_e_data->email ?>">
																					</div>
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="phone_number_01"><?= $_QC_language[173] ?></label>
																						<input type="text" class="form-control" name="phone_number_01" id="phone_number_01" value="<?= $user_e_data->phone_number_01 ?>" placeholder="<?= $_QC_language[173] ?>">
																					</div>
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="license_number"><?= $_QC_language[254] ?></label>
																						<input type="text" class="form-control" name="license_number" id="license_number" value="<?= $user_e_data->license_number ?>" placeholder="Driver's <?= $_QC_language[254] ?>">
																					</div>
																				</div>
																				<div class="row">
																					<h5 class="text-center">Address</h5>
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="city"><?= $_QC_language[251] ?></label>
																						<input type="text" class="form-control" name="city" id="city" value="<?= $user_e_data->city ?>" placeholder="<?= $_QC_language[251] ?>">
																					</div>
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="state_id"><?= $_QC_language[252] ?></label>
																						<select name="state_id" class="form-control">
																							<option value=""><?= $_QC_language[252] ?></option>
																							<?php for ($si = 1; $si <= $geo_state_count ; $si++) { ?>
																								<option value="<?= $si ?>"<?= $si == $user_e_data->state_id ? ' selected="selected"' : '' ?>><?= $state_abbr[$si] . ' - ' . $state_name[$si] ?></option> <?php
																							} ?>
																						</select>
																					</div>
																					<div class="form-group col-sm-12 col-md-4">
																						<label class="sr-only" for="zip_code"><?= $_QC_language[197] ?></label>
																						<input type="text" class="form-control" name="zip_code" id="zip_code" value="<?= $user_e_data->zip_code ?>" placeholder="<?= $_QC_language[197] ?>">
																					</div>
																				</div>
																				<button type="submit" class="btn btn-success"><?= $_QC_language[39] ?></button>
																				<input type="hidden" name="_hp_update_personal_info" value="1">
																				<input type="hidden" name="user_id" value="<?= $user_e_data->user_id ?>">
																				<input type="hidden" name="token" value="<?= $csrfToken ?>">
																			</form>
																		</div>								
																	</div>
																</div>	
															</div>
															<div class="row">
																<div class="col-sm-12 col-md-12">
																	<div class="main-box">
																		<header class="main-box-header clearfix">
																			<h2><?= $_QC_language[2] ?></h2>
																		</header>
																		<div class="main-box-body clearfix">
																			<form action="" method="post" class="form-inline" role="form">
																				<div class="form-group">
																					<label class="sr-only" for="exampleInputPassword2"><?= $_QC_language[40] ?></label>
																					<input name="newPassword01" type="password" class="form-control" id="inputPasswordNew0" placeholder="<?= $_QC_language[40] ?>">
																				</div>
																				<div class="form-group">
																					<label class="sr-only" for="exampleInputPassword2"><?= $_QC_language[10] ?></label>
																					<input name="newPassword02" type="password" class="form-control" id="inputPasswordNew0" placeholder="<?= $_QC_language[10] ?>">
																				</div>
																				<button type="submit" class="btn btn-success"><?= $_QC_language[39] ?> <?= $_QC_language[2] ?></button>
																				<input type="hidden" name="_hp_update_password" value="1">
																				<input type="hidden" name="user_id" value="<?= $user_e_data->user_id ?>">
																				<input type="hidden" name="token" value="<?= $csrfToken ?>">
																			</form>
																		</div>
																	</div>
																</div>	
															</div>
															<?php if ($user_e_data->email_verification != NULL) {
																// User hasn't confirmed email address ?>
																<div class="row">
																	<div class="col-sm-12 col-md-12">
																		<div class="main-box">
																			<header class="main-box-header clearfix">
																				<h2><?= $_QC_language[98] ?></h2>
																			</header>
																			<div class="main-box-body clearfix">
																				<div class="alert alert-warning fade in">
																					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
																					<i class="fa fa-warning fa-fw fa-lg"></i>
																					<strong><?= $_QC_language[99] ?></strong> 
																					<?= $_QC_language[100] ?> <b><?= $_QC_language[101] ?></b> <?= $_QC_language[102] ?>
																				</div>
																				<form action="" method="post">
																					<button type="submit" class="btn btn-primary btn-block"><?= $_QC_language[103] ?></button>
																					<input type="hidden" name="_hp_user_activation_bypass" value="1">
																					<input type="hidden" name="user_id" value="<?= $user_e_data->user_id ?>">
																					<input type="hidden" name="token" value="<?= $csrfToken ?>">
																				</form>
																			</div>
																		</div>
																	</div>
																</div><?php
															} ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div><?php
								} 
							} ?>
						</div>
					</div>
					<?php include($_SESSION['ProjectPath']."/includes/footer.php") ?>
				</div>
			</div>
		</div>
	</div>
	<div class="md-overlay"></div>
	<!-- global scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.nanoscroller.min.js"></script>
	<!-- this page specific scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/modernizr.custom.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/snap.svg-min.js"></script> <!-- For Corner Expand and Loading circle effect only -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/classie.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/notificationFx.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/modalEffects.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/wizard.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.maskedinput.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.sort.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.paginate.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/footable.filter.js"></script>
	<!-- theme scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>/js/scripts.js"></script>
	<script type="text/javascript">
		(function() {
			<?php // Notices
			$_QC_module_controller = DB::getInstance()->query("SELECT * FROM _QC_module_controller");
	        foreach ($_QC_module_controller->results() as $_QC_module_controller_data) {
				if (Session::exists($_QC_module_controller_data->controller)) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash($_QC_module_controller_data->controller) ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
				elseif (Session::exists($_QC_module_controller_data->controller.'_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash($_QC_module_controller_data->controller.'_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			}

			// Notices
			if (Session::exists('add_user_group')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_user_group') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_user_group')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_user_group') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('add_user')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_user') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('delete_user')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('delete_user') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('activate_user')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('activate_user') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_main_info')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_main_info') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_password')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_password') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_quantum_config')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_quantum_config') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('create_client_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('create_client_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_client_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_client_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('remove_client_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('remove_client_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('delete_client_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('delete_client_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('activate_client_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('activate_client_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('client_switch_to_driver')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('client_switch_to_driver') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('user_activation_bypass')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('user_activation_bypass') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('create_driver_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('create_driver_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('remove_driver_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('remove_driver_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			# BROKER REMOVED
			/*elseif (Session::exists('delete_broker_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('delete_broker_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_broker_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_broker_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('add_broker_assoc')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_broker_assoc') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }*/
			elseif (Session::exists('update_driver_profile')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_driver_profile') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('add_loader_tractor')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_loader_tractor') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_loader_tractor')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_loader_tractor') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }

			elseif (Session::exists('add_loader_trailer')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_loader_trailer') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_loader_trailer')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_loader_trailer') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			// Errors
			elseif (Session::exists('add_user_group_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_user_group_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_user_group_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_user_group_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('add_user_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_user_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('delete_user_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('delete_user_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('activate_user_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('activate_user_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_main_info_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_main_info_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_password_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_password_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_quantum_config_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_quantum_config_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('create_client_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('create_client_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_client_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_client_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('remove_client_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('remove_client_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('delete_client_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('delete_client_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('activate_client_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('activate_client_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('client_switch_to_driver_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('client_switch_to_driver_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('user_activation_bypass_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('user_activation_bypass_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('create_driver_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('create_driver_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('remove_driver_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('remove_driver_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			# BROKER REMOVED
			/*elseif (Session::exists('delete_broker_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('delete_broker_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_broker_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_broker_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('add_broker_assoc_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_broker_assoc_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }*/
			elseif (Session::exists('update_driver_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_driver_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }

			elseif (Session::exists('update_driver_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_driver_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_driver_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_driver_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_driver_profile_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_driver_profile_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }

			elseif (Session::exists('add_loader_tractor_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_loader_tractor_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_loader_tractor_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_loader_tractor_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }

			elseif (Session::exists('add_loader_trailer_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('add_loader_trailer_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('update_loader_trailer_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_loader_trailer_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php } ?>
			
			$('.footable').footable();
			
			$('#myWizard').wizard();
	
			// masked inputs
			$("#maskedDate").mask("99/99/9999");
			$("#maskedPhone").mask("(999) 999-9999");
			$("#phone_number_01").mask("(999) 999-9999? x99999");
			$("#phone_number_02").mask("(999) 999-9999? x99999");
			$("#accounts_payable_number").mask("(999) 999-9999? x99999");
			$("#contact_phone_number_01").mask("(999) 999-9999? x99999");

			// Count characters for fields
            function companyNameLength(value){
                var maxLengthCompanyName = 128;
                var minLengthCompanyName = 1;

                if(value.length > maxLengthCompanyName || value.length < minLengthCompanyName) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('company_name').onkeyup = function(){
                if(!companyNameLength(this.value)) {
                    $('#company_name_holder').removeClass("has-success");
                    $('#company_name_holder').addClass("has-error");
                } else {
                    $('#company_name_holder').addClass("has-success");
                    $('#company_name_holder').removeClass("has-error");
                }
            }

            function addressLine1Length(value){
                var maxLengthAddressLine1 = 128;
                var minLengthAddressLine1 = 1;

                if(value.length > maxLengthAddressLine1 || value.length < minLengthAddressLine1) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('address_line_1').onkeyup = function(){
                if(!addressLine1Length(this.value)) {
                    $('#address_line_1_holder').removeClass("has-success");
                    $('#address_line_1_holder').addClass("has-error");
                } else {
                    $('#address_line_1_holder').addClass("has-success");
                    $('#address_line_1_holder').removeClass("has-error");
                }
            }

            function addressLine2Length(value){
                var maxLengthAddressLine2 = 128;
                var minLengthAddressLine2 = 1;

                if(value.length > maxLengthAddressLine2 || value.length < minLengthAddressLine2) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('address_line_2').onkeyup = function(){
                if(!addressLine2Length(this.value)) {
                    $('#address_line_2_holder').removeClass("has-success");
                    $('#address_line_2_holder').addClass("has-error");
                } else {
                    $('#address_line_2_holder').addClass("has-success");
                    $('#address_line_2_holder').removeClass("has-error");
                }
            }

            function cityLength(value){
                var maxLengthCity = 128;
                var minLengthCity = 1;

                if(value.length > maxLengthCity || value.length < minLengthCity) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('city').onkeyup = function(){
                if(!cityLength(this.value)) {
                    $('#city_holder').removeClass("has-success");
                    $('#city_holder').addClass("has-error");
                } else {
                    $('#city_holder').addClass("has-success");
                    $('#city_holder').removeClass("has-error");
                }
            }

            function zipCodeLength(value){
                var maxLengthZipCode = 128;
                var minLengthZipCode = 1;

                if(value.length > maxLengthZipCode || value.length < minLengthZipCode) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('zip_code').onkeyup = function(){
                if(!zipCodeLength(this.value)) {
                    $('#zip_code_holder').removeClass("has-success");
                    $('#zip_code_holder').addClass("has-error");
                } else {
                    $('#zip_code_holder').addClass("has-success");
                    $('#zip_code_holder').removeClass("has-error");
                }
            }

            function mcNumberLength(value){
                var maxLengthMcNumber = 16;
                var minLengthMcNumber = 1;

                if(value.length > maxLengthMcNumber || value.length < minLengthMcNumber) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('mc_number').onkeyup = function(){
                if(!mcNumberLength(this.value)) {
                    $('#mc_number_holder').removeClass("has-success");
                    $('#mc_number_holder').addClass("has-error");
                } else {
                    $('#mc_number_holder').addClass("has-success");
                    $('#mc_number_holder').removeClass("has-error");
                }
            }

            function usDotNumberLength(value){
                var maxLengthusDotNumber = 16;
                var minLengthusDotNumber = 1;

                if(value.length > maxLengthusDotNumber || value.length < minLengthusDotNumber) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('us_dot_number').onkeyup = function(){
                if(!usDotNumberLength(this.value)) {
                    $('#us_dot_number_holder').removeClass("has-success");
                    $('#us_dot_number_holder').addClass("has-error");
                } else {
                    $('#us_dot_number_holder').addClass("has-success");
                    $('#us_dot_number_holder').removeClass("has-error");
                }
            }

            function einNumberLength(value){
                var maxLengthEinNumber = 16;
                var minLengthEinNumber = 1;

                if(value.length > maxLengthEinNumber || value.length < minLengthEinNumber) {
                    return false;
                } else {
                    return true;
                }
            }

            document.getElementById('ein_number').onkeyup = function(){
                if(!einNumberLength(this.value)) {
                    $('#ein_number_holder').removeClass("has-success");
                    $('#ein_number_holder').addClass("has-error");
                } else {
                    $('#ein_number_holder').addClass("has-success");
                    $('#ein_number_holder').removeClass("has-error");
                }
            }

            		document.getElementById('quickpay').onclick = function(){
				if (document.getElementById('quickpay').checked = true) {
					$('#quickpay_service_charge_holder').removeClass("hidden");
				} else {
					$('#quickpay_service_charge_holder').addClass("hidden");
				}
			}
		})();
	</script>
</body>
</html>
