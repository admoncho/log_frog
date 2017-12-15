<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';


Redirect::to($_SESSION['HtmlDelimiter'] . 'login');

### THIS FILE SEEMS TO BE NOT NECESSARY

if(Input::exists()) {
	
    if(Token::check(Input::get('token'))) {

      $remember = (Input::get('remember') === 'on') ? true : false;
      $login = $user->login(Input::get('email'), Input::get('password'), $remember);
      $login ? Redirect::to($_SESSION['HtmlDelimiter'] . '0/') : Session::flash('log_in_error', $_QC_language[14]) ;
    }
}

$csrfToken = Token::generate();

?>

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
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-default.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-growl.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-bar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-attached.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-other.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-theme.css"/>
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon"/>
	<!--[if lt IE 9]>
		<script src="<?= str_replace('http:', '', $cdn) ?>js/html5shiv.js"></script>
		<script src="<?= str_replace('http:', '', $cdn) ?>src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
	<![endif]-->
</head>
<body id="login-page">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div id="login-box">
					<div id="login-box-holder">
						<div class="row">
							<div class="col-xs-12">
								<header id="login-header">
									<div id="login-logo" class="text-center" style="font-family: 'Orbitron', sans-serif; font-size: 22px;">
										QUANTUM
									</div>
								</header>
								<div id="login-box-inner">
									<form role="form" action="" method="post">
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-user"></i></span>
											<input name="email" class="form-control" type="text" placeholder="<?= $_QC_language[1] ?>">
										</div>
										<div class="input-group">
											<span class="input-group-addon"><i class="fa fa-key"></i></span>
											<input name="password" type="password" class="form-control" placeholder="<?= $_QC_language[2] ?>">
										</div>
										<div id="remember-me-wrapper">
											<div class="row">
												<div class="col-xs-6">
													<div class="checkbox-nice">
														<input name="remember" type="checkbox" id="remember-me" checked="checked" />
														<label for="remember-me">
															<?= $_QC_language[3] ?>
														</label>
													</div>
												</div>
												<a href="<?= $_SESSION['href_location'] ?>recovery<?= $_GET['lang'] ? '?lang=es' : '' ?>" id="login-forget-link" class="col-xs-6">
													<?= $_QC_language[4] ?>
												</a>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12">
												<button type="submit" class="btn btn-success col-xs-12"><?= $_QC_language[5] ?></button>
											</div>
										</div>
										<div id="remember-me-wrapper">
											<div class="row">
												<div class="col-xs-6">
													
												</div>
												<a href="<?= $_SESSION['href_location'] ?><?= $_GET['lang'] ? '' : '?lang=es' ?>" id="login-forget-link" class="col-xs-6">
													<?= $_GET['lang'] ? 'English' : 'Español' ?>
												</a>
											</div>
										</div>
										<input type="hidden" name="token" value="<?= $csrfToken ?>">
									</form>
								</div>
							</div>
						</div>
					</div>
					<div id="login-box-footer">
						<div class="row">
							<div class="col-xs-12">
								<?php if ($_QC_settings_registration == 1) {
									echo $_QC_language[6]; ?>
									<a href="<?= $_SESSION['href_location'] ?>register<?= $_GET['lang'] ? '?lang=es' : '' ?>">
										<?= $_QC_language[7] ?>
									</a> <?php
								} ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- global scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/jquery.nanoscroller.min.js"></script>
	<!-- this page specific scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/modernizr.custom.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/snap.svg-min.js"></script> <!-- For Corner Expand and Loading circle effect only -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/classie.js"></script>
	<script src="<?= str_replace('http:', '', $cdn) ?>js/notificationFx.js"></script>
	<!-- theme scripts -->
	<script src="<?= str_replace('http:', '', $cdn) ?>js/scripts.js"></script>
	<script type="text/javascript">
		(function() {
			$('#email').tooltip();

			<?php // Notices
			if (Session::exists('registered')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('registered') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('update_password')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('update_password') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
            // Errors
            elseif (Session::exists('log_in_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('log_in_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php } ?>
		})();
	</script>
</body>
</html>
