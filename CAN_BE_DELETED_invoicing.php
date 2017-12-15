<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'].'/core/init.php';

$user = new User();

# geo_state
$geo_state = DB::getInstance()->query("SELECT * FROM geo_state ORDER BY state_id ASC");
$geo_state_count = $geo_state->count();
if ($geo_state_count) {
  foreach ($geo_state->results() as $geo_state_data) {
    $state_abbr[$geo_state_data->state_id] = $geo_state_data->abbr;
    $state_name[$geo_state_data->state_id] = $geo_state_data->name;
  }
}

# Get invoices
if (!$_GET) {
  # Lists all invoices
  $invoice = DB::getInstance()->query("SELECT * FROM invoice WHERE manager = " . $user->data()->user_id . " ORDER BY status ASC, added DESC");
} elseif ($_GET['invoice_id']) {
  # Get invoice_id
  $invoice = DB::getInstance()->query("SELECT * FROM invoice WHERE manager = " . $user->data()->user_id . " && data_id = " . $_GET['invoice_id']);
}

$invoice_count = $invoice->count();
$invoice_counter = 1;

if ($invoice_count) {
  foreach ($invoice->results() as $invoice_data) {
    $invoice_manager[$invoice_counter] = $invoice_data->manager;
    $invoice_data_id[$invoice_counter] = $invoice_data->data_id;
    $invoice_quantity[$invoice_counter] = $invoice_data->quantity;
    $invoice_description[$invoice_counter] = html_entity_decode($invoice_data->description);
    $invoice_amount[$invoice_counter] = $invoice_data->amount;
    $invoice_status[$invoice_counter] = $invoice_data->status;
    $invoice_added[$invoice_counter] = date('M d, Y', strtotime($invoice_data->added));
    $invoice_paid[$invoice_counter] = date('M d, Y', strtotime($invoice_data->paid));
    $invoice_counter++;
  }
}

# Get company name
if ($_GET['invoice_id']) {
  $company = DB::getInstance()->query("SELECT user_e_profile_client_user.client_id, 
                                            user_e_profile_client.company_name,
                                            user_e_profile_client.mailing_use_physical, 
                                            user_e_profile_client.address_line_1, 
                                            user_e_profile_client.address_line_2, 
                                            user_e_profile_client.city, 
                                            user_e_profile_client.state_id, 
                                            user_e_profile_client.zip_code, 
                                            user_e_profile_client.billing_address_line_1, 
                                            user_e_profile_client.billing_address_line_2, 
                                            user_e_profile_client.billing_city, 
                                            user_e_profile_client.billing_state_id, 
                                            user_e_profile_client.billing_zip_code
                                            FROM user_e_profile_client_user 
                                            INNER JOIN user_e_profile_client 
                                            ON user_e_profile_client_user.client_id=user_e_profile_client.data_id 
                                            WHERE user_id = " . $invoice_manager[1]);

  foreach ($company->results() as $company_data) {
    $company_name = html_entity_decode($company_data->company_name);

    if ($company_data->mailing_use_physical == 1) {
      $company_address_line_1 = html_entity_decode($company_data->address_line_1);
      $company_address_line_2 = html_entity_decode($company_data->address_line_2);
      $company_address_city = html_entity_decode($company_data->city);
      $company_address_state_id = $company_data->state_id;
      $company_address_zip_code = html_entity_decode($company_data->zip_code);
    } else {
      $company_address_line_1 = html_entity_decode($company_data->billing_address_line_1);
      $company_address_line_2 = html_entity_decode($company_data->billing_address_line_2);
      $company_address_city = html_entity_decode($company_data->billing_city);
      $company_address_state_id = $company_data->billing_state_id;
      $company_address_zip_code = html_entity_decode($company_data->billing_zip_code);
    }
  }
}

$csrfToken = Token::generate();

if ($user->isLoggedIn()) {
  # Display user dashboard ?>
    <!DOCTYPE html>
      <!--[if IE 9 ]><html class="ie9"><![endif]-->
      <head>
          <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
          <meta name="format-detection" content="telephone=no">
          <meta charset="UTF-8">

          <title>logisticsfrog.com - My invoices</title>
              
          <!-- CSS -->
          <link href="<?= $_SESSION['href_location'] ?>css/bootstrap.min.css" rel="stylesheet">
          <link href="<?= $_SESSION['href_location'] ?>css/animate.min.css" rel="stylesheet">
          <link href="<?= $_SESSION['href_location'] ?>css/font-awesome.min.css" rel="stylesheet">
          <link href="<?= $_SESSION['href_location'] ?>css/form.css" rel="stylesheet">
          <link href="<?= $_SESSION['href_location'] ?>css/calendar.css" rel="stylesheet">
          <link href="<?= $_SESSION['href_location'] ?>css/style.css" rel="stylesheet">
          <link href="<?= $_SESSION['href_location'] ?>css/icons.css" rel="stylesheet">
          <link href="<?= $_SESSION['href_location'] ?>css/generics.css" rel="stylesheet"> 
          <link href="<?= $_SESSION['href_location'] ?>css/custom-frog.css" rel="stylesheet"> 
          <link href="<?= $_SESSION['href_location'] ?>css/custom-frog-print.css" rel="stylesheet"> 
      </head>
      <body id="skin-tectile">
        <?php include($_SESSION['ProjectPath']."/includes/sa-header.php") ?>
        <div class="clearfix"></div>
        
        <section id="main" class="p-relative" role="main">
            <?php include($_SESSION['ProjectPath']."/includes/sa-sidebar.php") ?>        
            <!-- Content -->
            <section id="content" class="container">
                <?php if (!$_GET) {
                  # Display invoices ?>
                  <!-- Breadcrumb -->
                  <ol class="breadcrumb hidden-xs">
                      <li class="active">My invoices</li>
                  </ol>
                  
                  <h4 class="page-title">MY INVOICES</h4>
                  <div class="block-area" id="tableHover">
                    <div class="row">
                      <div class="col-md-12">
                        <h3 class="block-title">My invoices</h3>
                        <?php if ($invoice_count) { ?>
                          <div class="table-responsive overflow">
                            <table class="table table-bordered table-hover tile" style="font-size: 16px;">
                              <thead>
                                <tr>
                                  <th>Invoice ID</th>
                                  <th><span class="frog-text-color fa fa-calendar"></span> Added</th>
                                  <th><span class="frog-text-color fa fa-calendar"></span> Paid</th>
                                  <th>Quantity</th>
                                  <th>Description</th>
                                  <th><span class="frog-text-color fa fa-dollar"></span> Amount</th>
                                  <th>Status</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php for ($i = 1 ; $i <= $invoice_count ; $i++) { ?>
                                  <tr>
                                    <td><a class="btn btn-primary btn-block" href="<?= $_SESSION['href_location'] ?>invoicing?invoice_id=<?= $invoice_data_id[$i] ?>"><?= $invoice_data_id[$i] ?></a></td>
                                    <td><?= $invoice_added[$i] ?></td>
                                    <td><?= $invoice_paid[$i] != 'Nov 30, -0001' ? $invoice_paid[$i] : '' ?></td>
                                    <td class="text-right"><?= $invoice_quantity[$i] ?></td>
                                    <td><?= $invoice_description[$i] ?></td>
                                    <td class="text-right">&dollar; <?= number_format(($invoice_amount[$i] * $invoice_quantity[$i]), 2) ?></td>
                                    <td>
                                      <span class="label label-<?= $invoice_status[$i] == 1 ? 'success' : 'warning' ?>"><?= $invoice_status[$i] == 1 ? 'Paid' : 'Pending' ?></span> 
                                      <?php if ($invoice_status[$i] == 0) {
                                        # Display payment form 
                                        // Live environment
                                        // https://www.paypal.com/cgi-bin/webscr
                                        // Sandbox NEW
                                        // https://www.sandbox.paypal.com/cgi-bin/webscr ?>
                                        <div class="pull-right">
                                          <form name="reserveForm" id="reserveForm" method="post" action="https://www.paypal.com/cgi-bin/webscr">
                                            <input type="submit" name="submit" value="Pay invoice" class="btn btn-primary">
                                            <input type="hidden" name="cmd" value="_xclick">
                                            <input type="hidden" name="business" value="marco@logisticsfrog.com">
                                            <input type="hidden" name="item_name" value="<?= $invoice_description[$i] ?>">
                                            <input type="hidden" name="amount" value="<?= $invoice_amount[$i] * $invoice_quantity[$i] ?>">
                                            <input type="hidden" name="first_name" value="<?= $user->data()->name ?>" />
                                            <input type="hidden" name="last_name" value="<?= $user->data()->last_name ?>" />
                                            <input type="hidden" name="email" value="<?= $user->data()->email ?>" />
                                            <input type="hidden" name="invoice" value="<?= $invoice_data_id[$i] ?>" />
                                          </form>
                                        </div> <?php
                                      } ?>
                                      </td>
                                  </tr> <?php
                                } ?>
                              </tbody>
                            </table>
                          </div> <?php
                        } else {
                          # Show no invoices warning ?>
                          <div class="alert alert-warning" role="alert">There are no invoices to show!</div> <?php
                        } ?>
                        <div class="clearfix"></div>
                      </div>
                      <div class="col-sm-12 col-md-12 text-right visible-xs">
                        <span class="icon" style="margin-right: -10px;">&#61815;</span>
                        <span class="icon">&#61815;</span>
                      </div>
                    </div>
                  </div> <?php
                } elseif ($_GET['invoice_id']) { ?>
                  <!-- Breadcrumb -->
                  <ol class="breadcrumb hidden-xs">
                      <li><a href="<?= $_SESSION['href_location'] ?>invoicing">My invoices</a></li>
                      <li class="active">Invoice #<?= $_GET['invoice_id'] ?></li>
                  </ol>

                  <h4 class="page-title">MY INVOICES</h4>

                  <!-- Extra small devices back button -->
                  <a id="invoice-back-button" href="<?= $_SESSION['href_location'] ?>invoicing" class="hidden-sm hidden-md hidden-lg btn" style="position: fixed; right: 7px; top: 50px; z-index: 9;">Back</a>

                  <div class="block-area">
                    <div class="row">
                      <div class="panel panel-primary">
                        <div class="panel-body">
                          <div class="col-md-4">
                            <h4>logisticsfrog.com</h4>
                          </div>
                          <div class="col-md-4">
                            <h4><?= $company_name ?></h4>
                            <p><?= $company_address_line_2 ?></p>
                            <p style="line-height: 5px;"><?= $company_address_city ?>, <?= $state_abbr[$company_address_state_id] ?> <?= $company_address_zip_code ?></p>
                          </div>
                          <div class="col-md-4 text-right">
                            <h4>Invoice # <?= $_GET['invoice_id'] ?></h4>
                            <p>Invoice date: <?= $invoice_added[1] ?></p>
                            <?= $invoice_paid[1] != 'Nov 30, -0001' ? '<p>Paid date: ' . $invoice_paid[1] . '</p>' : '<p>Payment pending</p>' ?>
                          </div>
                          <div class="table-responsive col-md-12">
                            <table class="table">
                              <thead>
                                <tr>
                                  <th><span>Description</span></th>
                                  <th class="text-center"><span>Quantity</span></th>
                                  <th class="text-center"><span>Unit price</span></th>
                                  <th class="text-center"><span>Total</span></th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td><?= $invoice_description[1] ?></td>
                                  <td class="text-center"><?= $invoice_quantity[1] ?></td>
                                  <td class="text-center">&dollar; <?= $invoice_amount[1] ?></td>
                                  <td class="text-center">&dollar; 
                                  <?= number_format(($invoice_amount[1] * $invoice_quantity[1]), 2) ?></td>
                                </tr>
                              </tbody>
                            </table>
                            <hr >
                            <div class="col-sm-12 col-md-12 text-right">
                              <h3>Total: &dollar; <?= number_format(($invoice_amount[1] * $invoice_quantity[1]), 2) ?></h3>
                              <?php if ($invoice_status[1] == 0) {
                                # Display payment form 
                                // Live environment
                                // https://www.paypal.com/cgi-bin/webscr
                                // Sandbox NEW
                                // https://www.sandbox.paypal.com/cgi-bin/webscr ?>
                                <form name="reserveForm" id="reserveForm" method="post" action="https://www.paypal.com/cgi-bin/webscr">
                                  <input type="submit" name="submit" value="Pay invoice" class="btn btn-primary">
                                  <input type="hidden" name="cmd" value="_xclick">
                                  <input type="hidden" name="business" value="marco@logisticsfrog.com">
                                  <input type="hidden" name="item_name" value="<?= $invoice_description[1] ?>">
                                  <input type="hidden" name="amount" value="<?= $invoice_amount[1] * $invoice_quantity[1] ?>">
                                  <input type="hidden" name="first_name" value="<?= $user->data()->name ?>" />
                                  <input type="hidden" name="last_name" value="<?= $user->data()->last_name ?>" />
                                  <input type="hidden" name="email" value="<?= $user->data()->email ?>" />
                                  <input type="hidden" name="invoice" value="<?= $invoice_data_id[1] ?>" />
                                </form> <?php
                              } ?>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div> <?php
                } ?>
            </section>

            <!-- Older IE Message -->
            <!--[if lt IE 9]>
                <div class="ie-block">
                    <h1 class="Ops">Ooops!</h1>
                    <p>You are using an outdated version of Internet Explorer, upgrade to any of the following web browser in order to access the maximum functionality of this website. </p>
                    <ul class="browsers">
                        <li>
                            <a href="https://www.google.com/intl/en/chrome/browser/">
                                <img src="img/browsers/chrome.png" alt="">
                                <div>Google Chrome</div>
                            </a>
                        </li>
                        <li>
                            <a href="http://www.mozilla.org/en-US/firefox/new/">
                                <img src="img/browsers/firefox.png" alt="">
                                <div>Mozilla Firefox</div>
                            </a>
                        </li>
                        <li>
                            <a href="http://www.opera.com/computer/windows">
                                <img src="img/browsers/opera.png" alt="">
                                <div>Opera</div>
                            </a>
                        </li>
                        <li>
                            <a href="http://safari.en.softonic.com/">
                                <img src="img/browsers/safari.png" alt="">
                                <div>Safari</div>
                            </a>
                        </li>
                        <li>
                            <a href="http://windows.microsoft.com/en-us/internet-explorer/downloads/ie-10/worldwide-languages">
                                <img src="img/browsers/ie.png" alt="">
                                <div>Internet Explorer(New)</div>
                            </a>
                        </li>
                    </ul>
                    <p>Upgrade your browser for a Safer and Faster web experience. <br/>Thank you for your patience...</p>
                </div>   
            <![endif]-->
        </section>
        
        <!-- Javascript Libraries -->
        <!-- jQuery -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/jquery.min.js"></script> <!-- jQuery Library -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/jquery-ui.min.js"></script> <!-- jQuery UI -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/jquery.easing.1.3.js"></script> <!-- jQuery Easing - Requirred for Lightbox + Pie Charts-->

        <!-- Bootstrap -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/bootstrap.min.js"></script>

        <!-- Charts -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.js"></script> <!-- Flot Main -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.time.js"></script> <!-- Flot sub -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.animator.min.js"></script> <!-- Flot sub -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts/jquery.flot.resize.min.js"></script> <!-- Flot sub - for repaint when resizing the screen -->

        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/sparkline.min.js"></script> <!-- Sparkline - Tiny charts -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/easypiechart.js"></script> <!-- EasyPieChart - Animated Pie Charts -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/charts.js"></script> <!-- All the above chart related functions -->

        <!-- Map -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/maps/jvectormap.min.js"></script> <!-- jVectorMap main library -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/maps/usa.js"></script> <!-- USA Map for jVectorMap -->

        <!--  Form Related -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/icheck.js"></script> <!-- Custom Checkbox + Radio -->

        <!-- UX -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/scroll.min.js"></script> <!-- Custom Scrollbar -->

        <!-- Other -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/calendar.min.js"></script> <!-- Calendar -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/feeds.min.js"></script> <!-- News Feeds -->
        

        <!-- All JS functions -->
        <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/functions.js"></script>
      </body>
  </html><?php
} else {
  # Else, user not loged in, redirect to homepage
  Redirect::to($_SESSION['HtmlDelimiter'] . '');
} ?>
