<?php 
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.maskedinput.min.js"></script>

<?php
session_start();
ob_start();
if ($_SESSION['$clean_php_self'] == '/dashboard/invoice/index.php') { ?>

	<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.js"></script>
	<script src="<?= $_SESSION["href_location"] ?>js/dataTables.fixedHeader.js"></script>
	<script src="<?= $_SESSION["href_location"] ?>js/dataTables.tableTools.js"></script>
	<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.bootstrap.js"></script> <?php
}?>

<script type="text/javascript">

	(function() {

		$('#driver_id_select').select2();

		$("[data-toggle=popover]").popover();

		<?php
		if ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/index.php') { ?>

			var table = $('#<?= $module_name ?>-table').dataTable({
				'info': false,
				'Dom': 'lfr<"clearfix">tip'
			});
			
		  // var tt = new $.fn.dataTable.TableTools( table ); This removes the buttons
			$( tt.fnContainer() ).insertBefore('div.dataTables_wrapper'); <?php
		}
		?>

		$("#maskedFaxNumber").mask("(999) 999-9999");
	})();
</script>
