<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module config file
include $module_directory . 'config.php';

if (Session::exists('create_user_error')) { ?>
  
  <div class="alert alert-danger fade in">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <i class="fa fa-times-circle fa-fw fa-lg"></i>
    <?= Session::flash('create_user_error') ?>
  </div> <?php
}

?>

<div class="col-xs-12">

  <div id="login-box">
    <div class="row">
      <div class="col-xs-12">
        <header id="login-header">
          <div id="login-logo" class="text-center" style="font-family: 'Orbitron', sans-serif; font-size: 22px;">
            QUANTUM
          </div>
        </header>
        <div id="login-box-inner">

          <form role="form" action="" <?= $_GET['language_id'] ? 'method="post"' : 'method="get"' ?>>
            <div class="form-group">
              <select name="language_id" class="form-control"<?= $_GET['language_id'] ? '' : ' onChange="this.form.submit();"' ?>>
                <?= $_GET['language_id'] ? '' : '<option value="">Language / Lenguaje</option>' ?>
                <option value="1"<?= $_GET['language_id'] == 1 ? ' selected' : '' ?>>English</option>
                <option value="2"<?= $_GET['language_id'] == 2 ? ' selected' : '' ?>>Espa&ntilde;ol</option>
              </select>
            </div>
            <div class="input-group<?= $_GET['language_id'] ? '' : ' hidden' ?>">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input <?= $_GET['language_id'] ? '' : 'disabled' ?> name="name" class="form-control" type="text" placeholder="<?= $core_language[9] ?>">
            </div>
            <div class="input-group<?= $_GET['language_id'] ? '' : ' hidden' ?>">
              <span class="input-group-addon"><i class="fa fa-user"></i></span>
              <input <?= $_GET['language_id'] ? '' : 'disabled' ?> name="last_name" class="form-control" type="text" placeholder="<?= $core_language[10] ?>">
            </div>
            <div class="input-group<?= $_GET['language_id'] ? '' : ' hidden' ?>">
              <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
              <input <?= $_GET['language_id'] ? '' : 'disabled' ?> name="email" class="form-control" type="email" placeholder="<?= $core_language[11] ?>">
            </div>
            <div class="input-group<?= $_GET['language_id'] ? '' : ' hidden' ?>">
              <span class="input-group-addon"><i class="fa fa-lock"></i></span>
              <input <?= $_GET['language_id'] ? '' : 'disabled' ?> name="password" type="password" class="form-control" placeholder="<?= $core_language[20] ?>">
            </div>
            <div class="input-group<?= $_GET['language_id'] ? '' : ' hidden' ?>">
              <span class="input-group-addon"><i class="fa fa-unlock-alt"></i></span>
              <input <?= $_GET['language_id'] ? '' : 'disabled' ?> name="password_again" type="password" class="form-control" placeholder="<?= $core_language[24] . ' ' . strtolower($core_language[20]) ?>">
            </div><div class="row">
              <div class="col-xs-12">
                <button type="submit" class="btn btn-primary col-xs-12"><?= $_GET['language_id'] ? ($_GET['language_id'] == 1 ? $core_language[63] . ' ' . strtolower($core_language[64]) : 'Next / Siguiente') : 'Next / Siguiente' ?></button>
              </div>
            </div>
            <div id="remember-me-wrapper">
              <div class="row">
                <div class="col-xs-6">
                  
                </div>
              </div>
            </div>
            <?= $_GET['language_id'] ? '<input type="hidden" name="_controller_create_user" value="1">' : '' ?>
            <?= $_GET['language_id'] ? '<input type="hidden" name="token" value="' . $csrfToken . '">' : '' ?>
          </form>
        </div>
        <div id="login-box-footer">
          <div class="row">
            <div class="col-xs-12">
              <?= $_GET['language_id'] ? $core_language[65] : 'Already have an account' ?>?
              <a href="<?= $_SESSION['href_location'] ?>login">
                <?= $_GET['language_id'] ? $core_language[60] : 'Login' ?>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require TEMPLATE_PATH . '/back-end/bottom.php'; ?>
