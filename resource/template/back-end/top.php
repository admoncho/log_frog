<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title></title> 

  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/nanoscroller.css" />
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/compiled/theme_styles.css" />

  <!-- this page specific styles -->
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/ns-default.css"/>
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/ns-style-growl.css"/>
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/ns-style-bar.css"/>
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/ns-style-attached.css"/>
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/ns-style-other.css"/>
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/ns-style-theme.css"/>
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/select2.css" /> 
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/libs/daterangepicker.css" />

  <!-- Custom -->
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/custom.css"/>

  <?php
  session_start();
  ob_start();
  if ($_SESSION['$clean_php_self'] == '/dashboard/index2.php') { ?>
     
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
    <script src="<?= $_SESSION["href_location"] ?>resource/template/back-end/js/utils.js"></script>

    <style>
      canvas {
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
      }
    </style> <?php
  } ?>

  <style type="text/css">
    
    .modal-backdrop {
      z-index: -1;
    }
  </style>
  
  <?php

  ### Head Scripts ###
  # Declare file location and name
  # This line was replaced for the one right below, test behavior on blog before removing
  # $head_scripts = $_SESSION['ProjectPath'] . preg_replace('([^/]+$)', '', $php_self_string) . "/inc/head-scripts.php";
  $head_scripts = TEMPLATE_PATH . "/back-end/head-scripts.php";

  # head scripts
  include($head_scripts);

  # Banco Nacional scripts
  if ($_SESSION['$clean_php_self'] == '/dashboard/invoice/index.php' && $_GET['invoice_id']) { ?>
    
    <!-- PRODUCTION ENVIRONMENT -->
    <script type="text/javascript" src="https://vpayment.verifika.com/VPOS2/js/modalcomercio.js" ></script>
    <!-- TEST ENVIRONMENT -->
    <!-- <script type="text/javascript" src="https://integracion.alignetsac.com/VPOS2/js/modalcomercio.js" ></script> -->
     <?php
  }
  ?>

  <link type="image/x-icon" href="favicon.png" rel="shortcut icon" />
  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
  <link href='//fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
  <!--[if lt IE 9]>
    <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/html5shiv.js"></script>
    <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
  <![endif]-->
</head>
<body class="theme-whbl"<?= isset($no_session) ? ' id="login-page"' : '' ?>>

<?php
# No session pages
if (isset($no_session)) { ?>

  <div class="container">
    <div class="row"> <?php
} else {

  # Logged in only pages ?>
  <div id="theme-wrapper">
    <?php include(TEMPLATE_PATH . "/back-end/header.php") ?>
    <div id="page-wrapper" class="container nav-small">
      <div class="row">
        <?php include(TEMPLATE_PATH . "/back-end/left-panel.php") ?>
        <div id="content-wrapper">
          <div class="row">
            <div class="col-lg-12">
              <div class="row">
                <div class="col-lg-12">
                  <?php include(LIBRARY_PATH . "/quantum/module/" . $module_name . "/inc/breadcrumbs-title.php") ?>
                </div>
              </div>

              <?php

              # If title or breadcrumb missing
              /*if (!$php_self_title && !$php_self_breadcrumb) { ?>
                
                <div class="row">
                  <div class="col-sm-12 col-md-12">
                    <div class="main-box clearfix">
                      <header class="main-box-header clearfix">
                        <h2 class="pull-left red"><span class="fa fa-warning"></span> Lang items missing for this url</h2>
                      </header>
                      
                      <div class="main-box-body clearfix">
                        <form action="" method="post">
                          <div class="form-group">
                            <input type="text" class="form-control" name="title_en" placeholder="Title english">
                          </div>
                          <div class="form-group">
                            <input type="text" class="form-control" name="title_es" placeholder="T&iacute;tulo espa&ntilde;ol">
                          </div>
                          <div class="form-group">
                            <input type="text" class="form-control" name="breadcrumb_en" placeholder="Breadcrumb english">
                          </div>
                          <div class="form-group">
                            <input type="text" class="form-control" name="breadcrumb_es" placeholder="Breadcrumb espa&ntilde;ol">
                          </div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save</button>
                          </div>
                          <input type="hidden" name="_controller_add_language_items" value="1">
                          <input type="hidden" name="token" value="<?= $csrfToken ?>">
                        </form>
                      </div>
                    </div>
                  </div>
                </div> <?php
              }*/
}
?>
