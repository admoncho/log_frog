<?php 
session_start();
ob_start();
?>
<?php
# No session pages
if (isset($no_session)) { ?>

    </div>
  </div>
  <!-- global scripts -->
  <script src="<?= $_SESSION["href_location"] ?>js/jquery.js"></script>
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
