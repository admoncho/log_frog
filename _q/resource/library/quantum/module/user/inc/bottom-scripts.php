<?php
  session_start();
  ob_start();
  $_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.maskedinput.min.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/dataTables.fixedHeader.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/dataTables.tableTools.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.bootstrap.js"></script> 

<script type="text/javascript">
	(function() {

		$("[data-toggle=popover]").popover();

		<?php
                session_start();
                ob_start();
		if ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/index.php') { ?>

			var table = $('#<?= $module_name ?>-table').dataTable({
				'info': false,
				'Dom': 'lfr<"clearfix">tip'
			});
			
		  // var tt = new $.fn.dataTable.TableTools( table ); This removes the buttons
			$( tt.fnContainer() ).insertBefore('div.dataTables_wrapper'); <?php
		}
		?>

		$("#phone_number").mask("(999) 999-9999");
		$("#phone_number_cr").mask("9999-9999");
		$("#dob").mask("99/99/9999");
	})();
</script>
