<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<ol class="breadcrumb">
  <li><a href="<?= $_SESSION['href_location'] ?>dashboard/">Dashboard</a></li>

  <?php
  # Are we on the module's main page?
  if ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/index.php') {
    
    # Give module the active class and remove the link ?>
    <li class="active"><?= str_replace('_', ' ', $module_name) ?></li> <?php
  } elseif ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/schedule.php') {
    
    # Give module the active class and remove the link ?>
    <li><a href="<?= $_SESSION['href_location'] ?>dashboard/<?= str_replace('', '_', $module_name) ?>"><?= str_replace('_', ' ', $module_name) ?></a></li>
    <li>Schedule #<?= $schedule_counter ?></li> <?php
  } else {

    # Else display third breadcrumb ?>

    <li><a href="<?= $_SESSION['href_location'] ?>dashboard/<?= str_replace('', '_', $module_name) ?>"><?= str_replace('_', ' ', $module_name) ?></a></li>

    <?php
  }
  ?>

</ol>

<h1 class="pull-left">

  <?php
  if (isset($_GET['load_id'])) {
    
    # Display root module title
    echo 'Load #' . $load_load_number[1];

  } elseif (isset($_GET['schedule_id'])) {
    
    # Display client/factoring company
    echo $client_id_company_name[$client_assoc_factoring_company_client_id] . ' <span class="fa fa-arrows-h"></span> ' . $factoring_company_id_name[$client_assoc_factoring_company_id];
  } elseif (isset($_GET['draft_id'])) {
    
    echo 'Draft load';
  } elseif ($_SESSION['$clean_php_self'] == '/dashboard/loader/index.php') {
    
    echo 'Main';
  }
  ?>

</h1>

<div class="pull-right top-page-ui" style="position: relative;">

  <?php
  if ($_SESSION['$clean_php_self'] == '/dashboard/loader/load.php') { ?>

    <div class="btn-group">

      <a type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        
        <i class="fa fa-file-pdf-o"></i>

        Files <span class="caret"></span>
      </a>

      <ul class="dropdown-menu">

        <li>
          
          <a class="<?= $rate_confirmation_exists[1] ? 'green' : 'red' ?>" href="load?load_id=<?= $_GET['load_id'] ?>&rate_confirmation=1">

            Rate confirmation
          </a>
        </li>

        <li>
          
          <a class="<?= $bol_exists[1] ? 'green' : 'red' ?>" href="load?load_id=<?= $_GET['load_id'] ?>&bol=1">

            BOL
          </a>
        </li>

        <li>
          
          <a class="<?= $raw_bol_exists[1] ? 'green' : 'red' ?>" href="load?load_id=<?= $_GET['load_id'] ?>&raw_bol=1">

            Raw BOL
          </a>
        </li>

        <li>
          
          <a class="<?= $payment_confirmation_exists[1] ? 'green' : 'red' ?>" href="load?load_id=<?= $_GET['load_id'] ?>&payment_confirmation=1">

            Payment confirmation
          </a>
        </li>

        <li>
          
          <a class="<?= $quickpay_invoice_exists[1] ? 'green' : 'red' ?>" href="load?load_id=<?= $_GET['load_id'] ?>&quickpay_invoice=1">

            Quickpay invoice
          </a>
        </li>
        <!-- <li role="separator" class="divider"></li> -->
      </ul>
    </div>

    <a id="popover_checkpoint" class="btn btn-link" data-toggle="collapse" data-target="#checkpoint_popover">
      
      <span<?= !$checkpoint_id_count ? ' class="red"' : '' ?>>

        <?= $checkpoint_id_count ? '<i class="fa fa-map-marker"></i> ' : '<i class="fa fa-plus"></i> Add ' ?>
        Checkpoint<?= $checkpoint_id_count ? 's ' : ' ' ?>
        <?= $checkpoint_id_count ? '<sup>' . $checkpoint_id_count . '</sup>' : '' ?>
      </span>
    </a>

    <div 
      id="checkpoint_popover" 
      class="collapse<?= $_POST['delete_checkpoint'] || $_GET['checkpoint_status_update'] || $_GET['checkpoint_id'] || $_POST['add_checkpoint'] ? ' in' : '' ?>"
      <?= $_GET['checkpoint_status_update'] ? ' style="width: 750px;"' : '' ?>
      <?= $_GET['checkpoint_id'] ? ' style="width: 300px;"' : '' ?>>

      <form 
        action="" 
        method="post"
        <?= $_GET['checkpoint_id'] 
          || $_POST['add_checkpoint'] 
          || $_GET['checkpoint_status_update'] 
          || !$checkpoint_id_count ? ' class="hidden"' : '' ?>>

        <button class="btn btn-link btn-sm" type="submit" style="position: absolute; top: 0; right: 0;">
          
          <i class="fa fa-plus"></i> Add checkpoint
        </button>
        <input type="hidden" name="add_checkpoint" value="1">
      </form>
      
      <?php 
      if ($_GET['checkpoint_id'] && $user->data()->user_group != 4) { ?>

        <div class="alert alert-info">
          <i class="fa fa-info-circle fa-fw fa-lg"></i>
          Checkpoint update
        </div>
        
        <form action="" method="post">
          
          <div class="form-group">

            <select name="data_type" class="form-control">
              <option value="0"<?= $checkpoint_id_data_type[1] == 0 ? ' selected' : '' ?>> Pickup</option>
              <option value="1"<?= ($checkpoint_id_data_type[1] == 1 ? ' selected' : '') . ($checkpoint_id_count == 1 ? ' class="hidden"' : '') ?>> Destination</option>
            </select>
          </div>

          <div class="form-group">

            <input name="line_1" type="text" class="form-control" value="<?= $checkpoint_id_line_1[1] ?>" placeholder="* Line 1" placeholder="* Line 1">
          </div>

          <div class="form-group">

            <input name="line_2" type="text" class="form-control" value="<?= $checkpoint_id_line_2[1] ?>" placeholder="Line 2" placeholder="Line 2">
          </div>
          
          <div class="row">

            <div class="col-sm-12 col-md-4">
              <div class="form-group">

                <input name="city" type="text" class="form-control" value="<?= $checkpoint_id_city[1] ?>" placeholder="* City">
              </div>
            </div>

            <div class="col-sm-12 col-md-4">

              <select name="state_id" style="width:100%" id="state_selector">
                <?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
                  <option value="<?= $i ?>"<?= $checkpoint_id_state_id[1] == $i ? ' selected' : '' ?>><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
                } ?>
              </select>
            </div>

            <div class="col-sm-12 col-md-4">

              <div class="form-group">

                <input name="zip_code" type="text" class="form-control" value="<?= $checkpoint_id_zip_code[1] ?>" placeholder="* Zip code">
              </div>
            </div>
          </div>

          <div class="form-group">

            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input name="date" type="text" class="form-control" id="datepickerDate" value="<?= $checkpoint_id_date_1[1] ?>">
            </div>
            <span class="help-block">* Date - format mm-dd-yyyy</span>
          </div>

          <div class="form-group">
            
            <div class="input-group input-append bootstrap-timepicker">
              <input name="time" type="text" class="form-control" id="timepicker" value="<?= $checkpoint_id_time_1[1] ?>">
              <span class="add-on input-group-addon"><i class="fa fa-clock-o"></i></span>
            </div>
            <span class="help-block">* Time - format hh-mm</span>
          </div>

          <div class="form-group">

            <input name="contact" type="text" class="form-control" placeholder="Contact" value="<?= $checkpoint_id_contact[1] ?>" placeholder="Contact">
          </div>

          <div class="form-group">

            <input name="notes" type="text" class="form-control" placeholder="Notes" value="<?= $checkpoint_id_notes[1] ?>" placeholder="Notes">
          </div>

          <div class="form-group">

            <input name="appointment" type="text" class="form-control" placeholder="Appointment" value="<?= $checkpoint_id_appointment[1] ?>" placeholder="Appointment">
          </div>

          <div class="col-sm-12 col-md-12 text-right">
            <div class="form-group">

              <button class="btn btn-link"><i class="fa fa-save"></i> Save</button>
              <a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red"> Cancel</a>
            </div>
          </div>

          <input type="hidden" name="_controller_checkpoint" value="update">
          <input type="hidden" name="token" value="<?= $csrfToken ?>">
        </form> <?php
      
      } elseif ($_GET['checkpoint_status_update']) { ?>
        
        <div class="alert alert-info">
          <i class="fa fa-info-circle fa-fw fa-lg"></i>
          Status change notification
        </div>
        
        <form action="" method="post">
          <div class="col-sm-2 col-md-2 text-right">
            To:
          </div>
          <div class="col-sm-10 col-md-10 text-right">
            <div class="form-group">
              <input name="to" class="form-control" value="<?= $load_broker_email[1] ?>" placeholder="Use comma separated values">
            </div>
          </div>
          <div class="col-sm-2 col-md-2 text-right">
            Cc:
          </div>
          <div class="col-sm-10 col-md-10 text-right">
            <div class="form-group">
              <input name="cc" class="form-control" value="<?= $user->data()->email ?>" placeholder="Use comma separated values">
            </div>
          </div>
          <div class="col-sm-2 col-md-2 text-right">
            Subject:
          </div>
          <div class="col-sm-10 col-md-10 text-right">
            <div class="form-group">
              <input name="subject" class="form-control" value="<?= $loader_status_change_notification_subject ?>">
            </div>
          </div>
          <div class="col-sm-12 col-md-12">
            <div class="form-group">
              <textarea id="loader_status_change_notification" name="body" rows="10" cols="80"><?= $loader_status_change_notification ?></textarea>
            </div>
          </div>
          
          <button type="submit" class="btn btn-link ">Send</button>
          <a class="btn btn-link red" href="load?load_id=<?= $_GET['load_id'] ?>">Cancel</a>

          <input type="hidden" name="status" value="2">
          <input type="hidden" name="_controller_checkpoint" value="status">
          <input type="hidden" name="token" value="<?= $csrfToken ?>">
        </form> <?php
      } elseif (!$checkpoint_id_count || $_POST['add_checkpoint']) {
        
        # Show new checkpoint form ?>

        <form action="" method="post">

          <div class="form-group">

            <select name="data_type" class="form-control">
              <option value=""<?= $checkpoint_id_count ? '' : ' class="hidden"' ?>>Choose type</option>
              <option value="9"<?= $checkpoint_id_count ? '' : ' selected' ?>> Pickup</option>
              <option value="1"<?= $checkpoint_id_count ? '' : ' class="hidden"' ?>> Destination</option>
            </select>
          </div>

          <div class="form-group">
            <input name="line_1" type="text" class="form-control" value="<?= Input::get('line_1') ?>" placeholder="* Line 1">
          </div>

          <div class="form-group">
            <input name="line_2" type="text" class="form-control" value="<?= Input::get('line_2') ?>" placeholder="Line 2">
          </div>
            
          <div class="form-group">
            <input name="city" type="text" class="form-control" value="<?= Input::get('city') ?>" placeholder="* City">
          </div>
            
          <div class="row">
            
            <div class="col-sm-12 col-md-7">

              <select name="state_id" style="width:100%" id="state_selector">
                <option>* State</option>
                <?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
                  <option value="<?= $i ?>"><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
                } ?>
              </select>
            </div>
            <div class="col-sm-12 col-md-5">

              <div class="form-group">
                <input name="zip_code" type="text" class="form-control" value="<?= Input::get('zip_code') ?>" placeholder="* Zip code">
              </div>
            </div>
          </div>

          <div class="form-group">

            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input name="date" type="text" class="form-control" id="datepickerDate" value="<?= Input::get('date') ?>">
            </div>
            <span class="help-block"><span class="red">* </span>Date - format mm-dd-yyyy</span>
          </div>

          <div class="form-group">
            
            <div class="input-group input-append bootstrap-timepicker">
              <span class="add-on input-group-addon"><i class="fa fa-clock-o"></i></span>
              <input name="time" type="text" class="form-control" id="timepicker" value="<?= Input::get('time') ?>">
            </div>
            <span class="help-block"><span class="red">* </span>Time - format hh:mm</span>
          </div>

          <div class="form-group">
            <input name="contact" type="text" class="form-control" placeholder="Contact">
          </div>

          <div class="form-group">
            <input name="notes" type="text" class="form-control" placeholder="Notes">
          </div>

          <div class="form-group">
            <input name="appointment" type="text" class="form-control" placeholder="Appointment">
          </div>

          <div class="form-group">

            <button class="btn btn-link"><i class="fa fa-save"></i> Save</button>
            <a href="load?load_id=<?= $_GET['load_id'] ?>" class="btn btn-link red pull-right"> Cancel</a>
          </div>

          <input type="hidden" name="_controller_checkpoint" value="add">
          <input type="hidden" name="token" value="<?= $csrfToken ?>">
        </form> <?php  
      } else {

        if ($checkpoint_id_count) {
            
          for ($i = 1; $i <= $checkpoint_id_count ; $i++) { ?>

            <div class="row<?= $i == 1 ? ' padding-t-20' : '' ?>">

              <div class="col-sm-10 col-md-10">

                <span class="label label-<?= $checkpoint_id_data_type[$i] == 0 ? 'warning' : 'info' ?>">

                  <?= ($checkpoint_id_data_type[$i] == 0 ? 'Pick' : 'Drop') . ' - ' . $checkpoint_id_date_time_4[$i] ?>
                </span>
              </div>

              <div class="col-sm-2 col-md-2">

                <a class="<?= $_POST['delete_checkpoint'] ? ' hidden' : '' ?>" href="load?load_id=<?= $_GET['load_id'] ?>&checkpoint_id=<?= $checkpoint_id_checkpoint_id[$i] ?>" title="Edit this checkpoint"> 
                  <i class="fa fa-pencil"></i>
                </a>
              </div>
            </div>

            <p style="margin: 0;">
              <small>

                <?= $checkpoint_id_line_1[$i] ?>
              </small>
            </p>

            <?= $checkpoint_id_line_2[$i] ? '<p style="margin: 0;"><small>' . $checkpoint_id_line_2[$i] . '</small></p>' : '' ?>

            <p style="margin: 0;">
              <small>

                <?= $checkpoint_id_city[$i] . ', ' . $state_abbr[$checkpoint_id_state_id[$i]] . ' ' . $checkpoint_id_zip_code[$i] ?>
              </small>
            </p>

            <p style="margin: 0;"><small><?= $checkpoint_id_contact[$i] ?></small></p>
            
            <?= $checkpoint_id_appointment[$i] ? '<p style="margin: 0;"><small><b>Appointment: </b>' . $checkpoint_id_appointment[$i] . '</small></p>' : '' ?>
            <?= $checkpoint_id_notes[$i] ? '<p style="margin: 0;"><small><b>Notes: </b>' . $checkpoint_id_notes[$i] . '</small></p>' : '' ?>

            <div class="row">

              <div class="<?= isset($_POST['delete_checkpoint']) ? 'hidden' : 'col-sm-10 col-sm-10' ?>">

                <form action="" method="post" class="<?= $user->data()->user_group == 4 ? ' hidden' : '' ?>"> 

                  <select onchange="this.form.submit()" name="status" class="form-control sm-select" data-toggle="tooltip" data-placement="top" title="Checkpoint status">
                    <option value="0"<?= $checkpoint_id_status[$i] == 0 ? ' selected' : '' ?>> Incomplete</option>
                    <option value="1"<?= $checkpoint_id_status[$i] == 1 ? ' selected' : '' ?>> Complete</option>
                    <option value="2"<?= $checkpoint_id_status[$i] == 2 ? ' selected' : '' ?>> Complete<?= $checkpoint_id_status[$i] == 2 ? ' (notification sent)' : ' &amp; send notification' ?></option>
                  </select>
                  
                  <input type="hidden" name="_controller_checkpoint" value="status">
                  <input type="hidden" name="checkpoint_id" value="<?= $checkpoint_id_checkpoint_id[$i] ?>">
                  <input type="hidden" name="token" value="<?= $csrfToken ?>">
                </form>
              </div>

              <div class="col-sm-<?= isset($_POST['delete_checkpoint']) ? '12' : '2' ?> col-sm-<?= isset($_POST['delete_checkpoint']) ? '12' : '2' ?>">

                <form action="" method="post" class="<?= isset($_POST['delete_checkpoint']) ? ($_POST['delete_checkpoint'] == $checkpoint_id_checkpoint_id[$i] ? '' : ' hidden') : '';  ?>">
                  
                  <button class="btn-link red" data-toggle="tooltip" data-placement="top" title="Delete checkpoint">
                    
                    <?= isset($_POST['delete_checkpoint']) ? ($_POST['delete_checkpoint'] == $checkpoint_id_checkpoint_id[$i] ? 'Confirm delete ' : '') : '';  ?>

                    <i class="fa fa-trash-o"></i>
                  </button>
                  
                  <?php
                  if (isset($_POST['delete_checkpoint'])) {
                    
                    if ($_POST['delete_checkpoint'] == $checkpoint_id_checkpoint_id[$i]) { ?>
                      
                       <a href="load?load_id=<?= $_GET['load_id'] ?>" class="">Cancel</a> <?php
                    }
                  } ?>

                  <input type="hidden" name="delete_checkpoint" value="<?= $checkpoint_id_checkpoint_id[$i] ?>">
                  <input type="hidden" name="data_type" value="<?= $checkpoint_id_data_type[$i] ?>">
                  <?php
                  if (isset($_POST['delete_checkpoint'])) {
                    
                    if ($_POST['delete_checkpoint'] == $checkpoint_id_checkpoint_id[$i]) { ?>
                      
                      <input type="hidden" name="_controller_checkpoint" value="delete">
                      <input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
                    }
                  } ?>
                </form>
              </div>
            </div> <?php

            # Show hr as long as there are 2 checkpoints or more
            if ($checkpoint_id_count > 1) {
              
              # Hide hr from appearing after last checkpoint
              if ($i != $checkpoint_id_count) {
                
                echo '<hr>';
              }
            }
          }
        }
      } ?>

    </div>

    <?php
    # Schedule info is only available if there is a bol and ratecon for this load
    if ($bol_exists[1] && $rate_confirmation_exists[1]) {

      # Dislpay schedule info only if it exists and if this load is in it
      if ($schedule_count && $schedule_load_count) { ?>
        
        <a 
        class="btn btn-link"
        href="#" 
        data-toggle="popover" 
        data-html="true" 
        data-placement="bottom" 
        title='<a href="schedule?schedule_id=<?= $schedule_load_schedule_id ?>">Schedule <b><?= $schedule_counter ?></b></a>'
        data-content='
          <?php
          # Loads title
          echo '<h5>' . $schedule_load_list_count . ' load' . ($schedule_load_list_count > 1 ? 's' : '') . '</h5>';

          # Iterate through loads in schedule
          for ($i = 1; $i <= $schedule_load_list_count ; $i++) { 
            
            echo '<a href="view-load?load_id=' . $schedule_load_list_load_id[$i] . '">Load #' . $schedule_load_list_load_number[$i] . ($schedule_load_list_load_id[$i] == $_GET[load_id] ? ' (this load)' : '') . '</a><br>';
          }

          echo $soar_file ? '<hr>
          <a href="view-load?load_id=' . $_GET['load_id'] . '&schedule=' . $schedule_load_schedule_id . '#schedule" class="green"><span class="fa fa-check"></span> Soar file</a>' : '';
          ?>
        '>
          <span class="fa fa-calendar"></span> Schedule
        </a> <?php
      } else {

        # Display add load to schedule link ?>

        <a 
        class="btn btn-link"
        href="#" 
        data-toggle="popover" 
        data-html="true" 
        data-placement="left" 
        title="Load not in schedule"
        data-content='
          <div>
            <?php
            if (($factoring_company_requires_soar[1] && file_exists($_SESSION['ProjectPath'] . '/files/schedule/bg/' . $load_client_id[1] . '.jpg')) || !$factoring_company_requires_soar[1]) { ?>
                
              <form action="schedule" method="post">
                <div class="row">
                  <div class="form-group">
                    <br>
                    <div class="checkbox-nice checkbox-inline<?= $client_assoc_data_id == 4 || $factoring_company_status_1 != 1 ? ' hidden' : '' ?>">
                      <input type="checkbox" id="checkbox-inl-1" name="create_files"<?= $client_assoc_data_id == 4 ? ' checked' : '' ?>>
                      <label for="checkbox-inl-1">
                        Create files?
                      </label>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-12 col-md-12">
                      <button type="submit" class="btn btn-link<?= $factoring_company_status_1 != 1 ? ' hidden' : '' ?>">
                      <i class="fa fa-plus"></i> Add load to schedule
                      </button>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="client_assoc_id" value="<?= $client_assoc_data_id ?>">
                <input type="hidden" name="client_id" value="<?= $load_client_id[1] ?>">
                <input type="hidden" name="factoring_company_id" value="<?= $client_assoc_factoring_company_id ?>">
                <input type="hidden" name="counter" value="<?= $client_assoc_counter ?>">
                <input type="hidden" name="invoice_counter" value="<?= ($last_invoice_number + 1) ?>">
                <input type="hidden" name="load_id" value="<?= $_GET['load_id'] ?>">
                <input type="hidden" name="requires_soar" value="<?= $factoring_company_requires_soar[1] ? $factoring_company_requires_soar[1] : "0" ?>">
                <input type="hidden" name="_controller_schedule" value="add_load">
                <input type="hidden" name="token" value="<?= $csrfToken ?>">
              </form> <?php
            } else {
              
              echo '<p class="red"> <i class="fa fa-warning"></i> This company requires a SOAR file and there is no background uploaded.</p>';
            }
            ?>

          </div>
          
          <?php
          if ($factoring_company_status_1 != 1) { ?>
            
            <p class="red">
              <a href="factoring-company?factoring_company_id=<?= $client_assoc_factoring_company_id ?>">
                
                <?= $factoring_company_id_name[$client_assoc_factoring_company_id]; ?>
              </a> 
              is inactive!
            </p> <?php
          }
          ?>
        '>
          <span class="fa fa-calendar"></span> Schedule
        </a> <?php
      } 
    } 

    # If either file is missing
    if (!$bol_exists[1] || !$rate_confirmation_exists[1]) { ?>

      <a 
      class="btn btn-link red"
      href="#" 
      data-toggle="tooltip" 
      data-placement="top" 
      title="<?= (!$rate_confirmation_exists[1] ? 'Rate confirmation missing! ' : '') . (!$bol_exists[1] ? ' BOL missing!' : '') ?>">

        <span class="fa fa-calendar"></span> Schedule
      </a>
      
      <?php 
    }
  } elseif ($_SESSION['$clean_php_self'] == '/dashboard/loader/schedule.php') { ?>

    <a 
      id="schedule-files"
      class="btn btn-link"
      href="#" 
      data-toggle="popover" 
      data-html="true" 
      data-placement="<?= $_GET['upload_payment_confirmation'] ? 'left' : 'bottom' ?>" 
      title="<?= $_GET['upload_payment_confirmation'] ? ' Upload payment confirmation' : 'Files' ?>"
      data-content='
        <?php
        if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '.pdf') && $factoring_company_requires_soar[1]) {

          # Hide if located on the last page before sending
          if (!$_GET['fee_option']) {

            # Display view soar button ?>
            <a class="btn btn-link<?= $_GET['upload_payment_confirmation'] ? ' hidden' : '' ?>" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>#soar">

              <span class="fa fa-check"></span> 
              Soar 
            </a>

            <form action="" method="post" class="pull-right<?= $_GET['upload_payment_confirmation'] ? ' hidden' : '' ?>">
              
              <button class="btn btn-link" title="Deletes SOAR file"> 

                <i class="fa fa-trash-o red"></i>
              </button>

              <input type="hidden" name="_controller_schedule" value="kill_soar">
              <input type="hidden" name="token" value="<?= $csrfToken ?>">
            </form>

            <?php

            # For each load
            for ($i=1; $i <= $factoring_company_schedule_load_count ; $i++) { 

              # Set invoice count to 0
              $invoice_count == 0;

              # Declare file name
              $pre_invoice_file_name = strtolower(str_replace([' & ', ' '], ['-', '-'], $first_invoice_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));

              $invoice_file_name = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_file_name);

              # Check if invoices exist only on schedules where client assoc is not 4 (tafs).
              if ($client_assoc_id != 4) {
                
                if (file_exists($schedule_directory . $invoice_file_name)) { 
                  # Display invoice ?>
                  <a class="btn btn-link<?= $_GET['upload_payment_confirmation'] ? ' hidden' : '' ?>" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>#invoice-<?= $load_list_load_id[$i] ?>">
                    
                    <span class="fa fa-check"></span> 
                    Invoice <?= $first_invoice_number ?>
                  </a> <?php

                  # Add to $invoice_count
                  $invoice_count += 1;
                } else { ?>

                  <a class="red" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $first_invoice_number ?>&entry_id=<?= $load_list_entry_id[$i] ?>&load_id=<?= $load_list_load_id[$i] ?>&broker_id=<?= $load_list_broker_id[$i] ?>&invoice_name=<?= $invoice_file_name ?>">
                    Create invoice <?= $first_invoice_number ?>
                  </a> <?php
                }
              }

              # Increment the $first_invoice_number to display next invoice number
              $first_invoice_number++;
            }

            # if $client_assoc_factoring_company_current_counter != $schedule_counter show payment confirmation file upload
            if ($client_assoc_factoring_company_current_counter != $schedule_counter) {

              # Display view file link if file exists
              if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '-payment-confirmation.pdf')) { ?>

                <a class="btn btn-link" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>#payment-confirmation">

                  <span class="fa fa-check"></span> 
                  Payment confirmation
                </a> <?php
              } else {

                # Show payment confirmation upload link
                if (!$_GET['upload_payment_confirmation']) { ?>
                  
                  <a class="btn btn-link red" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>&upload_payment_confirmation=1">

                    <i class="fa fa-close"></i>
                    Payment confirmation
                  </a> <?php
                }
                
                # Show payment confirmation upload form
                if ($_GET['upload_payment_confirmation']) { ?>
                  
                  <form action="" method="post" enctype="multipart/form-data">

                    <div class="">
                      
                      <div class="form-group col-sm-12 col-md-12">
                        <p><input type="file" name="payment_confirmation_file" accept="application/pdf" class="btn btn-link" style="width: 100%;"></p>
                      </div>
                      <div class="form-group col-sm-12 col-md-12">
                        <label>Correct amount paid?</label>
                        <p>
                          <select class="form-control" name="payment_confirmation" id="payment_confirmation">
                            <option value=""></option>
                            <option value="3">Yes</option>
                            <option value="2">No</option>
                          </select>
                        </p>
                      </div>
                      <div class="form-group col-sm-12 col-md-12 hidden has-error" id="payment_confirmation_note_holder">
                        <label>Notes</label>
                        <p>
                          <textarea name="note" class="form-control pull-right red" style="width: 100%;"></textarea>
                        </p>
                      </div>
                      <div class="form-group col-sm-12 col-md-12">
                        
                        <button type="submit" class="btn btn-link"><i class="fa fa-upload"></i> Upload payment confirmation</button>
                        <a href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>" class="btn btn-link red pull-right">Cancel</a>
                      </div>
                    </div>              
                      
                    <input type="hidden" name="_controller_payment_confirmation" value="1">
                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
                  </form> <?php
                }
              }
            }
          }
        } elseif (!$factoring_company_requires_soar[1]) {
          
          # Hide if located on the last page before sending
          if (!$_GET['fee_option']) {

            # For each load
            
            for ($i = 1; $i <= $factoring_company_schedule_load_count ; $i++) { 

              # Set invoice count to 0
              $invoice_count == 0;

              # Declare file name
              $pre_invoice_file_name = strtolower(str_replace([' & ', ' '], ['-', '-'], $first_invoice_number . '-' . $client_id_company_name[$client_assoc_factoring_company_client_id] . '-' . $broker_id_company_name[$load_list_broker_id[$i]] . '.pdf'));

              $invoice_file_name = preg_replace('/[^A-Za-z\d-.]/', '', $pre_invoice_file_name);

              # Check if invoices exist.
              if (file_exists($schedule_directory . $invoice_file_name)) {
                
                # Display invoices, hide if adding payment confirmation file ?>
                <a class="btn btn-link<?= $_GET['upload_payment_confirmation'] ? ' hidden' : '' ?>" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>#invoice-<?= $load_list_load_id[$i] ?>">
                  
                  <span class="fa fa-check"></span> 
                  Invoice <?= $first_invoice_number ?>
                </a> <?php

                # Add to $invoice_count
                $invoice_count += 1;

              } else {

                # Don't display if client_assoc_id = 4
                if ($client_assoc_id != 4) { ?>
                  <a class="red" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $first_invoice_number ?>&entry_id=<?= $load_list_entry_id[$i] ?>&load_id=<?= $load_list_load_id[$i] ?>&broker_id=<?= $load_list_broker_id[$i] ?>&invoice_name=<?= $invoice_file_name ?>">
                    Create invoice <?= $first_invoice_number ?>
                  </a> <?php
                }
              }

              # Increment the $first_invoice_number to display next invoice number
              $first_invoice_number++;
            }

            # if $client_assoc_factoring_company_current_counter != $schedule_counter show payment confirmation file upload
            if ($client_assoc_factoring_company_current_counter != $schedule_counter) {

              # Display view file link if file exists
              if (file_exists($schedule_directory . 'soar-' . $_GET['schedule_id'] . '-payment-confirmation.pdf')) { ?>

                <a class="btn btn-link" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>#payment-confirmation">
                  
                  <span class="fa fa-check"></span> 
                  Payment confirmation
                </a> <?php
              } else { ?>

                <a class="btn btn-link red<?= $_GET['upload_payment_confirmation'] ? ' hidden' : '' ?>" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>&upload_payment_confirmation=1" title="Upload">
                  
                  <span class="fa fa-save"></span>
                  Payment confirmation
                </a> <?php

                # Show payment confirmation upload form
                if ($_GET['upload_payment_confirmation']) { ?>
                  
                  <form action="" method="post" enctype="multipart/form-data">
                    
                    <div class="row">
                      
                      <div class="form-group col-sm-12 col-md-12">
                        <p><input type="file" name="payment_confirmation_file" accept="application/pdf" class="btn btn-link" style="width: 100%;"></p>
                      </div>

                      <div class="form-group col-sm-12 col-md-12">
                        <label>Correct amount paid?</label>
                        <p>
                          <select class="form-control" name="payment_confirmation" id="payment_confirmation">
                            <option value=""></option>
                            <option value="3">Yes</option>
                            <option value="2">No</option>
                          </select>
                        </p>
                      </div>
                      <div class="form-group col-sm-12 col-md-12 hidden has-error" id="payment_confirmation_note_holder">
                        <label>Notes</label>
                        <p>
                          <textarea name="note" class="form-control pull-right red" style="width: 100%;"></textarea>
                        </p>
                      </div>
                      <div class="form-group col-sm-12 col-md-12">
                        <button type="submit" class="btn btn-link"><i class="fa fa-upload"></i> Upload</button>
                        <a href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>" class="btn btn-link red pull-right">Cancel</a>
                      </div>
                    </div>
                    
                    <input type="hidden" name="_controller_payment_confirmation" value="1">
                    <input type="hidden" name="token" value="<?= $csrfToken ?>">
                  </form> <?php
                }
              }
            }
          }
        } else {
          
          # Show create soar link ?>
          <a class="btn btn-link red" href="schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create=1&client_assoc_id=<?= $schedule_client_assoc_id ?>">

            Create Files
          </a> <?php
        } ?>
      '>
        <i class="fa fa-file-pdf-o"></i>

        Files <span class="caret"></span>
      </a> <?php
  } elseif ($_SESSION['$clean_php_self'] == '/dashboard/loader/draft-load.php') { ?>

    <a id="popover_checkpoint" class="btn btn-link" data-toggle="collapse" data-target="#checkpoint_popover">
      
      <span<?= !$draft_checkpoint_id_count ? ' class="red"' : '' ?>>

        <?= $draft_checkpoint_id_count ? '<i class="fa fa-map-marker"></i> ' : '<i class="fa fa-plus"></i> Add ' ?>
        Checkpoint<?= $draft_checkpoint_id_count ? 's ' : ' ' ?>
        <?= $draft_checkpoint_id_count ? '<sup>' . $draft_checkpoint_id_count . '</sup>' : '' ?>
      </span>
    </a>

    <div 
      id="checkpoint_popover" 
      class="collapse
        <?= $_POST['delete_checkpoint'] 
          || $_GET['checkpoint_status_update'] 
          || $_GET['checkpoint_id'] 
          || $_POST['add_checkpoint'] ? ' in' : '' ?>" style="width: 300px;">

      <form 
        action="" 
        method="post"
        <?= $_GET['checkpoint_id'] 
          || $_POST['add_checkpoint'] 
          || $_GET['checkpoint_status_update'] 
          || !$draft_checkpoint_id_count 
          || $draft_accepted ? ' class="hidden"' : '' ?>>

        <button class="btn btn-link btn-sm" type="submit" style="position: absolute; top: 0; right: 0;">

          <i class="fa fa-plus"></i> Add checkpoint
        </button>
        <input type="hidden" name="add_checkpoint" value="1">
      </form>
      
      <?php 
      if ($_GET['checkpoint_id'] && $user->data()->user_group != 4) { ?>

        <div class="alert alert-info">
          <i class="fa fa-info-circle fa-fw fa-lg"></i>
          Checkpoint update
        </div>
        
        <form action="" method="post">
          
          <div class="form-group">

            <select name="data_type" class="form-control">
              <option value="0"<?= $draft_checkpoint_id_data_type[1] == 0 ? ' selected' : '' ?>> Pickup</option>
              <option value="1"<?= ($draft_checkpoint_id_data_type[1] == 1 ? ' selected' : '') . ($draft_checkpoint_id_count == 1 ? ' class="hidden"' : '') ?>> Destination</option>
            </select>
          </div>

          <div class="form-group">

            <input name="city" type="text" class="form-control" value="<?= $draft_checkpoint_id_city[1] ?>" placeholder="* City">
          </div>
          
          <div class="form-group">

            <select name="state_id" style="width:100%" id="state_selector">
              <?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
                <option value="<?= $i ?>"<?= $draft_checkpoint_id_state_id[1] == $i ? ' selected' : '' ?>><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
              } ?>
            </select>
          </div>

          <div class="form-group">

            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input name="date" type="text" class="form-control" id="datepickerDate" value="<?= $draft_checkpoint_id_date[1] ?>">
            </div>
            <span class="help-block">* Date - format mm-dd-yyyy</span>
          </div>

          <div class="form-group">
            
            <div class="input-group input-append bootstrap-timepicker">
              <input name="time" type="text" class="form-control" id="timepicker" value="<?= $draft_checkpoint_id_time[1] ?>">
              <span class="add-on input-group-addon"><i class="fa fa-clock-o"></i></span>
            </div>
            <span class="help-block">Time - format hh-mm</span>
          </div>

          <div class="col-sm-12 col-md-12 text-right">
            <div class="form-group">

              <button class="btn btn-link"><i class="fa fa-save"></i> Save</button>
              <a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="btn btn-link red"> Cancel</a>
            </div>
          </div>

          <input type="hidden" name="_controller_draft_checkpoint" value="update">
          <input type="hidden" name="token" value="<?= $csrfToken ?>">
        </form> <?php
      
      } elseif (!$draft_checkpoint_id_count || $_POST['add_checkpoint']) {
        
        # Show new checkpoint form ?>

        <form action="" method="post">

          <div class="form-group">

            <select name="data_type" class="form-control">
              <option value=""<?= $draft_checkpoint_id_count ? '' : ' class="hidden"' ?>>Choose type</option>
              <option value="9"<?= $draft_checkpoint_id_count ? '' : ' selected' ?>> Pickup</option>
              <option value="1"<?= $draft_checkpoint_id_count ? '' : ' class="hidden"' ?>> Destination</option>
            </select>
          </div>
            
          <div class="form-group">

            <input name="city" type="text" class="form-control" value="<?= Input::get('city') ?>" placeholder="City">
          </div>
            
          <div class="form-group">
            
            <select name="state_id" style="width:100%" id="state_selector">
              <option>State</option>
              <?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
                <option value="<?= $i ?>"><?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?></option> <?php
              } ?>
            </select>
          </div>

          <div class="form-group">

            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              <input name="date" type="text" class="form-control" id="datepickerDate">
            </div>
            <span class="help-block">* Date - format mm-dd-yyyy</span>
          </div>

          <div class="form-group">
            
            <div class="input-group input-append bootstrap-timepicker">
              <input name="time" type="text" class="form-control" id="timepicker">
              <span class="add-on input-group-addon"><i class="fa fa-clock-o"></i></span>
            </div>
            <span class="help-block">Time - format hh-mm</span>
          </div>

          <div class="form-group">

            <button class="btn btn-link"><i class="fa fa-save"></i> Save</button>
            <a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="btn btn-link red pull-right"> Cancel</a>
          </div>

          <input type="hidden" name="_controller_draft_checkpoint" value="add">
          <input type="hidden" name="token" value="<?= $csrfToken ?>">
        </form> <?php  
      } else {

        if ($draft_checkpoint_id_count) {
            
          for ($i = 1; $i <= $draft_checkpoint_id_count ; $i++) { ?>

            <div class="row<?= $i == 1 ? ' padding-t-20' : '' ?>">

              <div class="col-sm-12 col-md-12">

                <span class="label label-<?= $draft_checkpoint_id_data_type[$i] == 0 ? 'warning' : 'info' ?>">

                  <?php

                  echo ($draft_checkpoint_id_data_type[$i] == 0 ? 'Pick' : 'Drop'); ?>
                </span>
              </div>
            </div>

            <p style="margin: 0;">
              <small>

                <?php 
                echo $draft_checkpoint_id_city[$i] . ', ' . $state_abbr[$draft_checkpoint_id_state_id[$i]];

                if ($draft_checkpoint_id_date_time[$i] != '11/30/-0001 0:00') {
                  
                  echo '<br>' . $draft_checkpoint_id_date_time[$i];
                }?>
              </small>
            </p>

            <div class="row">

              <div class="col-sm-<?= isset($_POST['delete_checkpoint']) ? '12' : '2' ?> col-sm-<?= isset($_POST['delete_checkpoint']) ? '12' : '2' ?>">

                <form action="" method="post" class="pull-left<?= isset($_POST['delete_checkpoint']) ? ($_POST['delete_checkpoint'] == $draft_checkpoint_id_checkpoint_id[$i] ? '' : ' hidden') : '';  ?>">
                  
                  <button class="btn-link red" data-toggle="tooltip" data-placement="top" title="Delete checkpoint">
                    
                    <?= isset($_POST['delete_checkpoint']) ? ($_POST['delete_checkpoint'] == $draft_checkpoint_id_checkpoint_id[$i] ? 'Confirm delete ' : '') : '';  ?>

                    <i class="fa fa-trash-o"></i>
                  </button>
                  
                  <?php
                  if (isset($_POST['delete_checkpoint'])) {
                    
                    if ($_POST['delete_checkpoint'] == $draft_checkpoint_id_checkpoint_id[$i]) { ?>
                      
                       <a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="">Cancel</a> <?php
                    }
                  } ?>

                  <input type="hidden" name="delete_checkpoint" value="<?= $draft_checkpoint_id_checkpoint_id[$i] ?>">
                  <input type="hidden" name="data_type" value="<?= $draft_checkpoint_id_data_type[$i] ?>">
                  <?php
                  if (isset($_POST['delete_checkpoint'])) {
                    
                    if ($_POST['delete_checkpoint'] == $draft_checkpoint_id_checkpoint_id[$i]) { ?>
                      
                      <input type="hidden" name="_controller_draft_checkpoint" value="delete">
                      <input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
                    }
                  } ?>
                </form>

                <a 
                  class="<?= $_POST['delete_checkpoint'] ? ' hidden' : '' ?>" 
                  href="draft-load?draft_id=<?= $_GET['draft_id'] ?>&checkpoint_id=<?= $draft_checkpoint_id_checkpoint_id[$i] ?>" 
                  title="Edit this checkpoint"
                  style="margin-left: 15px;"> 

                  <i class="fa fa-pencil"></i>
                </a>
              </div>
            </div> <?php

            # Show hr as long as there are 2 checkpoints or more
            if ($draft_checkpoint_id_count > 1) {
              
              # Hide hr from appearing after last checkpoint
              if ($i != $draft_checkpoint_id_count) {
                
                echo '<hr>';
              }
            }
          }
        }
      } ?>

    </div> <?php 
  } elseif ($_SESSION['$clean_php_self'] == '/dashboard/loader/index.php') {

    # Hide for external users
    if ($user->data()->user_group != 4) { ?>

      <form action="" method="post"<?= $_POST['show_all_drafts'] || !$draft_list_count ? ' class="hidden"' : '' ?>>
        
        <button class="btn btn-link">
          
          Show all drafts
        </button>
        <input type="hidden" name="show_all_drafts" value="1">
      </form> <?php
    }
  } ?>

</div>
