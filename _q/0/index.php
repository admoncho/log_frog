<?php 
session_start();
ob_start();
# init.php
require $_SESSION['ProjectPath']. '/core/init.php';

# Limbo
include($_SESSION['ProjectPath']."/includes/limbo.php");

# THIS BE THE END OF THIS FILE
Redirect::to($_SESSION['HtmlDelimiter'] . 'dashboard/');

# Redirect if not logged
!$user->isLoggedIn() ? Redirect::to($_SESSION['HtmlDelimiter'] . '') : '' ;

# Controller calls
include($_SESSION['ProjectPath']."/includes/controller-calls.php");

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		$validate = new Validate();

		# CONTROLLER CALLS
		Input::get('_hp_confirm_email') ? include_once($_SESSION['ProjectPath']."/includes/controller/QUANTUM_GATEWAY_i_confirm_email.php") : '' ;
	}
}

# CONTROLLER CALLS
# STEP 3.2: QUANTUM GATEWAY Table _QG_i update (updates email_verification field to a new 5 digit code if resend button was used)
$_GET['resend_email_verification_code'] ? include_once($_SESSION['ProjectPath']."/includes/controller/QUANTUM_GATEWAY_i_resend_email_verification_code.php") : '' ;

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
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-default.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-growl.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-bar.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-attached.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-other.css"/>
	<link rel="stylesheet" type="text/css" href="<?= str_replace('http:', '', $cdn) ?>css/libs/ns-style-theme.css"/>
	<link type="image/x-icon" href="favicon.png" rel="shortcut icon" />
	<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
	<!--[if lt IE 9]>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/html5shiv.js"></script>
		<script src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
	<![endif]-->
</head>
<body class="<?= $_IA_skin_class[$config_skin] ?>">
	<div id="theme-wrapper">
		<?php include($_SESSION['ProjectPath']."/includes/header.php") ?>
		<div id="page-wrapper" class="container<?= $config_nav == 1 ? ' nav-small' : '' ?>">
			<div class="row">
				<?php include($_SESSION['ProjectPath']."/includes/left-panel.php") ?>
				<div id="content-wrapper">
					<div class="row">
						<div class="col-lg-12">
							<div class="row">
								<div class="col-lg-12">
									<ol class="breadcrumb">
										<li class="active"><span><?= $_QC_language[23] ?></span></li>
									</ol>
									<h1><?= $_QC_language[23] ?></h1>
								</div>
							</div>
							<?php include($_SESSION['ProjectPath']."/includes/email-verification.php"); ?>
							<div class="row">
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box">
										<i class="fa fa-cogs red-bg"></i>
										<a style="color:#000;" href="cms"><span class="headline"><?= $_QC_language[85] ?></span></a>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box">
										<i class="fa fa-cubes emerald-bg"></i>
										<a style="color:#000;" href="<?= $_SESSION['href_location'] ?>0/loader"><span class="headline"><?= $_QC_language[250] ?> old</span></a>
										<a style="color:#000;" href="<?= $_SESSION['href_location'] ?>dashboard/loader/"><span class="headline"> new</span></a>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box">
										<i class="fa fa-user-secret purple-bg"></i>
										<a style="color:#000;" href="user-i"><span class="headline"><?= $_QC_language[248] ?></span></a>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box">
										<i class="fa fa-user green-bg"></i>
										<a style="color:#000;" href="user-e"><span class="headline"><?= $_QC_language[249] ?></span></a>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
								<div class="main-box infographic-box colored green-bg">
										<a href="<?= $_SESSION['href_location'] ?>dashboard/client"><i class="fa fa-users"></i></a>
										<span class="headline"><a style="color: inherit" href="<?= $_SESSION['href_location'] ?>dashboard/client">Clients</a></span>
										<span class="value"><?= $limbo_user_e_profile_client_count ?></span>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored red-bg">
										<a href="broker"><i class="fa fa-rocket"></i></a>
										<span class="headline">
											<a style="color: inherit" href="<?= $_SESSION['href_location'] ?>dashboard/broker/">Brokers</a>
										</span>
										<span class="value"><?= $broker_co_count ?></span>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored emerald-bg">
										<a href="<?= $_SESSION['href_location'] ?>dashboard/factoring_company"><i class="fa fa-bank"></i></a>
										<span class="headline"><a style="color: inherit" href="<?= $_SESSION['href_location'] ?>dashboard/factoring_company">Factoring Companies</a></span>
										<span class="value"><?= $factoring_company_count ?></span>
									</div>
								</div>
								<div class="col-lg-3 col-sm-6 col-xs-12">
									<div class="main-box infographic-box colored purple-bg">
										<a href="tractors-trailers-equipment"><i class="fa fa-truck"></i></a>
										<span class="headline"><a style="color: inherit" href="tractors-trailers-equipment">Tractor &amp; trailers</a></span>
										<span class="value">-</span>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php include($_SESSION['ProjectPath']."/includes/footer.php") ?>
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
	<script src="<?= str_replace('http:', '', $cdn) ?>/js/scripts.js"></script>
	<script type="text/javascript">
		(function() {
			<?php // Notices
			if (Session::exists('email_verification')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('email_verification') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			elseif (Session::exists('email_verification_resend_code')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('email_verification_resend_code') ?></p>',layout:'growl',effect:'genie',type:'notice',});notification.show(); <?php }
			// Errors
			elseif (Session::exists('email_verification_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('email_verification_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php }
			elseif (Session::exists('email_verification_resend_code_error')) { ?> var notification = new NotificationFx({message:'<p><?= Session::flash('email_verification_resend_code_error') ?></p>',layout:'growl',effect:'genie',type:'error',});notification.show(); <?php } ?>
		})();
	</script>
</body>
</html>
