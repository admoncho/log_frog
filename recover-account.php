<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module config file
include $module_directory . 'config.php';

?>

<div class="col-xs-12">
  <div id="login-box">
    <div id="login-box-holder">

      <?php
      if (!isset($_GET['e']) && !isset($_GET['r'])) {
        # Display first step data

        # Show recovery email sent alert
        echo Session::exists('recovery_sent') ? '<div class="alert alert-success" role="alert">' . Session::flash('recovery_sent') . '</div>' : '' ;
      }
      ?>

      <div class="row">
        <div class="col-xs-12">
          <header id="login-header">
            <div id="login-logo" class="text-center" style="font-family: 'Orbitron', sans-serif; font-size: 22px;">
              QUANTUM
            </div>
          </header>
          <div id="login-box-inner" class="with-heading">
            
            <?php if (!isset($_GET['e']) && !isset($_GET['r'])) {
              # Display first step titles ?>
              <h4><?= $core_language[33] . ' ' . strtolower($core_language[34]) ?></h4>
              <small><?= $core_language[57] ?>.</small> <?php
            } else {
              # Display second step titles ?>
              <h4><?= $core_language[33] . ' ' . strtolower($core_language[34]) . ' - ' . $core_language[21] . ' ' . strtolower($core_language[20]) ?></h4>
              <small><?= $core_language[58] ?>.</small> <?php
            } ?>

            <form role="form" action="" method="post">
              <?php if (!isset($_GET['e']) && !isset($_GET['r'])) { ?>
                <div class="input-group reset-pass-input">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input name="email" class="form-control" type="text" placeholder="<?= $core_language[11] ?>" value="<?= Input::get('email'); ?>">
                </div>
                <div class="row">
                  <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary col-xs-12"><?= $core_language[59] ?></button>
                  </div>
                  <div class="col-xs-12">
                    <br/>
                    <?= $_GET['language_id'] == 1 ? '<a class="pull-left" href="?language_id=2">Espa&ntilde;ol</a>' : '<a class="pull-left" href="?language_id=1">English</a>' ?>
                    <a href="<?= $_SESSION['href_location'] ?>login" id="login-forget-link" class="forgot-link pull-right"><?= $core_language[60] ?></a>
                  </div>
                </div><?php
              } else {
                
                # Get account with same email and recovery code
                $account = DB::getInstance()->query("SELECT user.id FROM user INNER JOIN user_settings ON user.id=user_settings.user_id WHERE recover = '" . $_GET['r'] . "' && email = '" . $_GET['e'] . "'");

                if ($account->count()) {

                  # Get id
                  foreach ($account->results() as $account_data) {
                    $user_id = $account_data->id;  
                  } ?>

                  <div class="input-group reset-pass-input">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input name="password" class="form-control" type="password" placeholder="<?= $core_language[23] . ' ' . strtolower($core_language[20]) ?>">
                  </div>
                  <div class="input-group reset-pass-input">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <input name="repeat_password" class="form-control" type="password" placeholder="<?= $core_language[24] . ' ' . strtolower($core_language[23]) . ' ' . strtolower($core_language[20]) ?>">
                  </div>
                  <div class="row">
                    <div class="col-xs-12">
                      <button type="submit" class="btn btn-primary col-xs-12"><?= $core_language[21] . ' ' . strtolower($core_language[20]) ?></button>
                    </div>
                    <div class="col-xs-12">
                      <br/>
                      <a href="<?= $_SESSION['href_location'] ?>login" id="login-forget-link" class="forgot-link col-xs-12"><?= $core_language[60] ?></a>
                    </div>
                  </div>
                  <input type="hidden" name="user_id" value="<?= $user_id ?>"><?php
                }
              } ?>
              <input type="hidden" name="_controller_recover_account" value="1">
              <input type="hidden" name="token" value="<?= $csrfToken; ?>">
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require TEMPLATE_PATH . '/back-end/bottom.php'; ?>
