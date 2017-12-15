<?php 
session_start();
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title><?= $core_language[1] ?></title>
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

  <!-- Datatables -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
  <script type="text/javascript" language="javascript" src="//code.jquery.com/jquery-1.12.3.js"></script>
  <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>

  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/compiled/theme_styles.css" />

  <!-- Custom -->
  <link rel="stylesheet" type="text/css" href="<?= $_SESSION['href_location'] ?>css/quantum/custom.css"/>

  <style type="text/css">
    #file {
      display:none;
    }
  </style>
  
  <?php
  session_start();
  ob_start();
  ### Head Scripts ###
  # Declare file location and name
  # This line was replaced for the one right below, test behavior on blog before removing
  # $head_scripts = $_SESSION['ProjectPath'] . preg_replace('([^/]+$)', '', $php_self_string) . "/inc/head-scripts.php";
  $head_scripts = TEMPLATE_PATH . "/back-end/head-scripts.php";

  # head scripts
  include($head_scripts);
  ?>

  <link type="image/x-icon" href="favicon.png" rel="shortcut icon" />
  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300|Titillium+Web:200,300,400' rel='stylesheet' type='text/css'>
  <link href='//fonts.googleapis.com/css?family=Orbitron' rel='stylesheet' type='text/css'>
  <!--[if lt IE 9]>
    <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/html5shiv.js"></script>
    <script src="<?= $_SESSION['HtmlDelimiter'] ?>js/respond.min.js""></script>
  <![endif]-->
</head>
<body class="<?= $theme_class[$settings_theme_id] ?>"<?= isset($no_session) ? ' id="login-page"' : '' ?>>

<?php
# No session pages
if (isset($no_session)) { ?>

  <div class="container">
    <div class="row"> <?php
} else {

  # Logged in only pages ?>
  <div id="theme-wrapper">
    
    <?php include(TEMPLATE_PATH . "/back-end/header.php") ?>
    
    <div id="page-wrapper" class="container<?= $settings_nav == 2 ? ' nav-small' : '' ?>">
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

              
}
?>
