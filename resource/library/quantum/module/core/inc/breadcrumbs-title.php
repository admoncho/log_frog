<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
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

    <li><a href="<?= $_SESSION['href_location'] ?>dashboard/"><?= str_replace('_', ' ', $module_name) ?></a></li>

    <?php
    # Display extra breadcrumb if editing driver data
    if ($_GET['user_id']) { ?>
      
      <li><a href="<?= $_SESSION['href_location'] ?>dashboard/client/client?client_id=<?= $_GET['client_id'] ?>"><?= $client_company_name[1] ?></a></li> <?php
    }
  }
  ?>

</ol>
<h1 class="pull-left">

  <?php
  
  if ($_SESSION['$clean_php_self'] == '/dashboard/index.php') {
    
    echo "Dashboard";
  } elseif ($_SESSION['$clean_php_self'] == '/dashboard/core/ppg_category.php') {

    # Display root module title
    echo 'PPG Category';
  }
  ?>

</h1>

<div class="pull-right top-page-ui">

  <?php

  if (!isset($_GET['delete']) && isset($_GET['file_name'])) {

    # Show delete button if process is not initiated ?>

    <a class="btn btn-link red" href="ppg?file_name=<?= $_GET['file_name'] ?>&delete=1"><i class="fa fa-trash-o"></i> Delete</a> <?php
  } elseif (isset($_GET['delete']) && isset($_GET['file_name'])) {

    # Show delete confirmation ?>

    <form action="" method="post">
      
      <button class="btn btn-link red" type="submit"><i class="fa fa-trash-o"></i> Confirm Delete</button>
      <a href="ppg?file_name=<?= $_GET['file_name'] ?>"> Cancel</a>

      <input type="hidden" name="_controller_ppg" value="delete">
      <input type="hidden" name="token" value="<?= $csrfToken ?>">
    </form> <?php
  }
  ?>

</div>
