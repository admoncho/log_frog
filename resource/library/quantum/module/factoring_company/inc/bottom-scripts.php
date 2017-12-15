<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<script src="<?= $_SESSION["href_location"] ?>js/bootstrap-timepicker.min.js"></script>
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

		$("#maskedPhoneNumber").mask("(999) 999-9999? x99999");
		$("#maskedFaxNumber").mask("(999) 999-9999");
		$("#maskedContactPhoneNumber").mask("(999) 999-9999? x99999");

		//timepicker
		$('#time-from').timepicker({
			minuteStep: 5,
			showSeconds: false,
			showMeridian: false,
			disableFocus: false,
			showWidget: true
		}).focus(function() {
			$(this).next().trigger('click');
		});

		$('#time-to').timepicker({
			minuteStep: 5,
			showSeconds: false,
			showMeridian: false,
			disableFocus: false,
			showWidget: true
		}).focus(function() {
			$(this).next().trigger('click');
		});
	})();
</script>
