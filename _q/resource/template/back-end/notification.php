<?php
session_start();
ob_start();
/*# Core notifications (they are called from all modules)
include($_SESSION['ProjectPath']."/inc/core-notification.php");
include(TEMPLATE_PATH . "/back-end/notification.php");*/

# Other notifications that are displaid in the core module

# Add file manager entry notifications
if (Session::exists('cms_file_manager')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('cms_file_manager') ?>
	</div> <?php
} elseif (Session::exists('cms_file_manager_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('cms_file_manager_error') ?>
	</div> <?php
}

# Add changelog entry notifications
if (Session::exists('content')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('content') ?>
	</div> <?php
} elseif (Session::exists('content_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('content_error') ?>
	</div> <?php
}

# Add changelog entry notifications
if (Session::exists('add_changelog_entry')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('add_changelog_entry') ?>
	</div> <?php
} elseif (Session::exists('add_changelog_entry_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('add_changelog_entry_error') ?>
	</div> <?php
}

# Resend email verification code notifications
if (Session::exists('resend_email_verification_code')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('resend_email_verification_code') ?>
	</div> <?php
} elseif (Session::exists('resend_email_verification_code_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('resend_email_verification_code_error') ?>
	</div> <?php
}

# Show email confirmation notification and form if email has not been confirmed
/*if ($settings_email_verification) { ?>
 	
 	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= $core_language[48] ?>
		<div class="row" style="margin-top: 20px;">
			<div class="col-sm-12 col-md-4 col-md-offset-4 text-center">
				<form action="" method="post">
					<div class="form-group">
						<input type="number" name="email_verification" placeholder="<?= $core_language[44] ?>" class="form-control text-center">
					</div>
					<button type="submit" class="btn btn-primary"><?= $core_language[49] ?></button>
					<input type="hidden" name="_controller_verify_email_address" value="1">
					<input type="hidden" name="token" value="<?= $csrfToken ?>">
				</form>
			</div>
			<div class="col-sm-12 col-md-12 text-right">
				<?= $core_language[50] . ' ' . strtolower($core_language[44]) ?>? 
				<form action="" method="post" class="pull-right">
					<button type="submit" class="btn btn-link"><?= $core_language[51] ?></button>
					<input type="hidden" name="_controller_resend_email_verification_code" value="1">
					<input type="hidden" name="token" value="<?= $csrfToken ?>">
				</form>
			</div>
		</div>
	</div> <?php
}*/

# Show user create notifications
if (Session::exists('create_user')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('create_user') ?>
	</div> <?php
} elseif (Session::exists('create_user_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('create_user_error') ?>
	</div> <?php
}

# Show login error
if (Session::exists('log_in_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('log_in_error') ?>
	</div> <?php
}

# Show account recovery notifications
if (Session::exists('recover_account')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('recover_account') ?>
	</div> <?php
} elseif (Session::exists('recover_account_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('recover_account_error') ?>
	</div> <?php
}

# Show update personal info notification
if (Session::exists('update_personal_info')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('update_personal_info') ?>
	</div> <?php
} elseif (Session::exists('update_personal_info_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('update_personal_info_error') ?>
	</div> <?php
} 

# Show update quantum settings notification
if (Session::exists('update_quantum_settings')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('update_quantum_settings') ?>
	</div> <?php
} elseif (Session::exists('update_quantum_settings_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('update_quantum_settings_error') ?>
	</div> <?php
} 

# Show update password notification
if (Session::exists('update_password')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('update_password') ?>
	</div> <?php
} elseif (Session::exists('update_password_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('update_password_error') ?>
	</div> <?php
} 

# Show email verification notifications
if (Session::exists('verify_email_address')) { ?>
	
	<div class="alert alert-success fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-check fa-fw fa-lg"></i>
		<?= Session::flash('verify_email_address') ?>
	</div> <?php
} elseif (Session::exists('verify_email_address_error')) { ?>
	
	<div class="alert alert-danger fade in">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-times-circle fa-fw fa-lg"></i>
		<?= Session::flash('verify_email_address_error') ?>
	</div> <?php
}
