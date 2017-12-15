<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);

# No session pages
if (isset($no_session)) { ?>

    </div>
  </div>
  <!-- global scripts -->
  <script src="<?= $_SESSION["href_location"] ?>js/ccjquery.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/jquery.nanoscroller.min.js"></script>
  <!-- this page specific scripts -->
  <script src="<?= $_SESSION["href_location"] ?>js/modernizr.custom.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/snap.svg-min.js"></script> <!-- For Corner Expand and Loading circle effect only -->
  <script src="<?= $_SESSION["href_location"] ?>js/classie.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/notificationFx.js"></script>
  <!-- theme scripts -->
  <script src="<?= $_SESSION["href_location"] ?>js/scripts.js"></script> <?php
  
} else {

  # Logged in only pages ?>
            </div>
          </div>
          <?php include(TEMPLATE_PATH . "/back-end/footer.php") ?>
        </div>
      </div>
    </div>
  </div>

  <!-- global scripts -->
  <script src="<?= $_SESSION["href_location"] ?>js/jquery.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/jquery.nanoscroller.min.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/demo.js"></script>
  <!-- this page specific scripts -->
  <script src="<?= $_SESSION["href_location"] ?>js/modernizr.custom.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/snap.svg-min.js"></script> <!-- For Corner Expand and Loading circle effect only -->
  <script src="<?= $_SESSION["href_location"] ?>js/classie.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/notificationFx.js"></script>
  <script src="<?= $_SESSION["href_location"] ?>js/jquery.maskedinput.min.js"></script>
  <!-- theme scripts -->
  <script src="<?= $_SESSION["href_location"] ?>js/scripts.js"></script>

  <?php
  session_start();
  ob_start();
  /* REPORTING */

  /*if ($_SESSION['$clean_php_self'] == '/dashboard/reports/index.php') {

    // Get month days
    // $days_this_month = date("t"); ?>
     
    <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>

    <script>
    // var MONTHS = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var config = {
        type: 'line',
        data: {
            labels: [<?php 
              for ($i = 1; $i <= $days_this_month; $i++) {

                // Don't display the comma on the last day
                if ($i != $days_this_month) {
                  echo '"' . $i . '",';
                } else {
                  echo '"' . $i . '"';
                }
              }?>],
            datasets: [{
                label: "Loads",
                data: [<?php 
                for ($i = 1; $i <= $days_this_month; $i++) {

                  # $i needs to have a zero added if it only has one digit
                  if ($i < 10) {
                    $day_value = '0' . $i;
                  } else {
                    $day_value = $i;
                  }

                  // Don't display the comma on the last day
                  # Loads graph
                  $load_graph = DB::getInstance()->query("
                    SELECT load_id 
                    FROM loader_load 
                    WHERE load_status = 0 && first_checkpoint LIKE '" . date('Y') . "-" . date('m') . "-" . $day_value . "%'");
                  $load_graph_count = $load_graph->count();

                  if ($load_graph_count) {
                    
                    echo $load_graph_count . ",";
                  }
                }?>],
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                fill: false,
                borderDash: [5, 5],
                pointRadius: 15,
                pointHoverRadius: 10,
            }]
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
            },
            hover: {
                mode: 'index'
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Month'
                    }
                }],
                yAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: 'Loads'
                    }
                }]
            },
            title: {
                display: true,
                text: 'Load Report - <?= date('m Y') ?>'
            }
        }
    };

    window.onload = function() {
        var ctx = document.getElementById("canvas").getContext("2d");
        window.myLine = new Chart(ctx, config);
    };
    </script> <?php 
  }*/ 

  /* REPORTING */

  ### Bottom scripts ###
  include($module_directory . "inc/bottom-scripts.php");
}
?>

<!-- Zone map modal -->
<div class="modal fade" id="zm" tabindex="-1" role="dialog" aria-labelledby="zm_label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="zm_label">Zone map</h4>
      </div>
      <div class="modal-body">
        
        <img src="<?= $_SESSION["href_location"] ?>img/zm.jpg" class="img-responsive">
      </div>
      <div class="modal-footer">
        
        <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- How to modal -->
<div class="modal fade" id="ht" tabindex="-1" role="dialog" aria-labelledby="ht_label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="ht_label">How tos</h4>
      </div>
      <div class="modal-body">
        
        <?php

        # Include ppgs
        include(LIBRARY_PATH . "/quantum/module/core/inc/ppg.php");
        ?>
      </div>
    </div>
  </div>
</div>

</body>
</html>
