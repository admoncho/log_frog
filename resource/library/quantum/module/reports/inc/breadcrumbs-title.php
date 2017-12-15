<?php 
session_start();
ob_start();
?>
<ol class="breadcrumb">
  <li><a href="<?= $_SESSION['href_location'] ?>dashboard/">Dashboard</a></li>

  <?php
  # Are we on the module's main page?
  if ($php_self_string == '/dashboard/' . $module_name . '/index.php') {
    
    # Give module the active class and remove the link ?>
    <li class="active"><?= str_replace('_', ' ', $module_name) ?></li> <?php
  } else {

    # Else display third breadcrumb ?>

    <li><a href="<?= $_SESSION['href_location'] ?>dashboard/<?= str_replace('', '_', $module_name) ?>"><?= str_replace('_', ' ', $module_name) ?></a></li>

    <?php
  }
  ?>

</ol>

<h1 class="pull-left">Reports</h1>

<div class="top-page-ui">
  <div class="pull-right text-right col-sm-12 col-md-7">
    
    <form action="" method="post" <?= isset($_POST['date_range']) ? ' class="hidden"' : '' ?>>
      
      <div class="form-group col-md-10">
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
          <input 
            type="text" 
            name="date_range" 
            class="form-control" 
            id="datepickerDateRange" 
            placeholder="Date range">
        </div>
      </div>

      <div class="form-group col-md-2">
        <input type="submit" name="submit" value="Send" class="btn btn-link">
      </div>

    </form>

    <?php

    if (isset($_POST['date_range'])) { ?>
      <p class="text-info">
        Report from 
        <b><?= $start_month . '/' . $start_day . '/' . $start_year ?> </b>
        to  
        <b><?= $end_month . '/' . $end_day . '/' . $end_year ?> </b>
        <small><a href="<?= $_SESSION['href_location'] ?>dashboard/reports/">reset</a></small>
      </p> <?php
    } ?>

  </div>
</div>
