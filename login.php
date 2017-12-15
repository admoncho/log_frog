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
                <input name="email" class="form-control" type="text" placeholder="<?= $core_language[11] ?>">
              </div>
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input name="password" type="password" class="form-control" placeholder="<?= $core_language[20] ?>">
              </div>
              <div id="remember-me-wrapper">
                <div class="row">
                  <div class="col-xs-6">
                    <div class="checkbox-nice">
                      <input name="remember" type="checkbox" id="remember-me" checked="checked" />
                      <label for="remember-me">
                        <?= $core_language[61] ?>
                      </label>
                    </div>
                  </div>
                  <a href="<?= $_SESSION['href_location'] ?>recover-account<?= $_GET['lang'] ? '?lang=es' : '' ?>" id="login-forget-link" class="col-xs-6">
                    <?= $core_language[62] ?>
                  </a>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <button type="submit" class="btn btn-primary col-xs-12"><?= $core_language[60] ?></button>
                </div>
              </div>
              <div id="remember-me-wrapper">
                <div class="row">
                  <div class="col-xs-6">
                    
                  </div>
                </div>
              </div>
              <input type="hidden" name="_controller_login" value="1">
              <input type="hidden" name="token" value="<?= $csrfToken ?>">
            </form>
          </div>
        </div>
      </div>
    </div>
    <div id="login-box-footer">
      <div class="row">
        <div class="col-xs-12">
          <a href="<?= $_SESSION['href_location'] ?>create-account?language_id=2">
            <?= $core_language[63] . ' ' . strtolower($core_language[64]) ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require TEMPLATE_PATH . '/back-end/bottom.php'; ?>
