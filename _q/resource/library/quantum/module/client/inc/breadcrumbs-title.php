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
    # Display extra breadcrumb if editing driver data
    if ($_GET['user_id']) { ?>
      
      <li><a href="<?= $_SESSION['href_location'] ?>dashboard/client/client?client_id=<?= $_GET['client_id'] ?>"><?= $client_company_name[1] ?></a></li> <?php
    }
  }
  ?>

</ol>
<h1 class="pull-left">

  <?php
  if (!$_GET && !isset($_POST['add_client'])) {
    
    # Display root module title
    echo isset(${$module_name . '_language'}[$php_self_title]) ? ${$module_name . '_language'}[$php_self_title] : '';

  } elseif (isset($_POST['add_client'])) {

    # Display add new item title
    echo 'Add new factoring company';

  } elseif ($_GET['client_id']) {

    if ($_GET['user_id']) {

      # Display user_id name and lastname
      echo $user_list_id_name[$_GET['user_id']] . ' ' . $user_list_id_last_name[$_GET['user_id']];
    } else {
      
      # Display company name
      echo $client_company_name[1];
    }
  }
  ?>

</h1>

<div class="pull-right top-page-ui">

  <?php

  # Show quick access select menu for browsing factoring companies
  if (isset($_GET['client_id'])) {

    # Hide when editing user_id data
    if (!isset($_GET['user_id'])) { ?>
    
      <form action="" method="get">
        <div class="form-group">
          <select name="client_id" class="form-control" onchange="this.form.submit()">
            
            <?php
            for ($i = 1; $i <= $client_ALT_count ; $i++) { ?>
              
              <option value="<?= $client_ALT_id[$i] ?>"<?= $client_ALT_id[$i] == $_GET['client_id'] ? ' selected' : '' ?>><?= $client_ALT_status[$i] == 1 ? $client_ALT_company_name[$i] : '<span class="red">' . $client_ALT_company_name[$i] . '</span>' ?></option> <?php
            }
            ?>

          </select>
        </div>
      </form> <?php
    } else {

      # Show back to client button
      echo '<a href="client?client_id=' . $_GET['client_id'] . '" class="btn btn-link pull-right red"> Back to client page</a>';
    }
  }
  ?>

</div>
