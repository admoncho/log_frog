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
<h1 class="pull-left">

  <?php
  if (!$_GET && !isset($_POST['add_broker'])) {
    
    # Display root module title
    echo isset(${$module_name . '_language'}[$php_self_title]) ? ${$module_name . '_language'}[$php_self_title] : '';

  } elseif (isset($_POST['add_broker'])) {

    # Display add new item title
    echo 'Add new broker';

  } elseif ($_GET['broker_id']) {

    # Display factoring company name
    echo $broker_company_name[1];
  }
  ?>

</h1>

<div class="pull-right top-page-ui">

  <?php

  # Show quick access select menu for browsing factoring companies
  if (isset($_GET['broker_id'])) { ?>
    
    <form action="" method="get">
      <div class="form-group">
        <select name="broker_id" class="form-control" onchange="this.form.submit()">
          
          <?php
          for ($i = 1; $i <= $broker_ALT_count ; $i++) { ?>
            
            <option value="<?= $broker_ALT_id[$i] ?>"<?= $broker_ALT_id[$i] == $_GET['broker_id'] ? ' selected' : '' ?>><?= $broker_ALT_status[$i] == 1 ? $broker_ALT_company_name[$i] : '<span class="red">' . $broker_ALT_company_name[$i] . '</span>' ?></option> <?php
          }
          ?>

        </select>
      </div>
    </form> <?php
  }
  ?>

</div>
