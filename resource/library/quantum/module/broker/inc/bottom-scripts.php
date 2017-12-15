<?php
  session_start();
  ob_start();
  $_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.maskedinput.min.js"></script>

<?php
if ($_SESSION['$clean_php_self'] == '/dashboard/broker/index.php') { ?>

	<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.js"></script>
	<script src="<?= $_SESSION["href_location"] ?>js/dataTables.fixedHeader.js"></script>
	<script src="<?= $_SESSION["href_location"] ?>js/dataTables.tableTools.js"></script>
	<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.bootstrap.js"></script> <?php
}?>

<script type="text/javascript">
	(function() {

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

		$("#maskedPhoneNumber").mask("(999) 999-9999? x99999");
		$("#maskedFaxNumber").mask("(999) 999-9999");
		$("#maskedContactPhoneNumber").mask("(999) 999-9999? x99999");

		document.getElementById('quickpay1').onchange = function(){
			if (document.getElementById('quickpay1').checked) {
				$('#service-fee').removeClass("hidden");
				$('#quickpay_email_holder').removeClass("hidden");
				$('#accounts_payable_number_holder').removeClass("hidden");
			}
		}

		document.getElementById('quickpay2').onchange = function(){
			if (document.getElementById('quickpay2').checked) {
				$('#service-fee').addClass("hidden");
				$('#quickpay_email_holder').addClass("hidden");
				$('#accounts_payable_number_holder').addClass("hidden");
			}
		}

		document.getElementById('status1').onchange = function(){
			if (document.getElementById('status1').checked) {
				$('#doNotUse').addClass("hidden");
			}
		}

		document.getElementById('status2').onchange = function(){
			if (document.getElementById('status2').checked) {
				$('#doNotUse').addClass("hidden");
			}
		}

		document.getElementById('status3').onchange = function(){
			if (document.getElementById('status3').checked) {
				$('#doNotUse').removeClass("hidden");

				// Disable update button if status "DO NOT USE" is selected and reason is empty
				if (document.getElementById('do_not_use_reason').value == '') {
					document.getElementById("update").disabled = true;
				};
			}
		}

		document.getElementById('do_not_use_reason').onkeydown = function(){
			if (document.getElementById('do_not_use_reason').value != '') {
				document.getElementById("update").disabled = false;
			}
		}
	})();
</script>
