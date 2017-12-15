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
    <li class="active">User account</li>

    <?php
  }
  ?>

</ol>
<h1 class="pull-left">

  <?= $php_self_string == '/dashboard/' . $module_name . '/index.php' ? 'User list' : '' ?>
  <?= $_GET['user_id'] ? $user_list_name[1] . ' ' . $user_list_last_name[1] : '' ?>

</h1>

<div class="pull-right top-page-ui">

</div>
