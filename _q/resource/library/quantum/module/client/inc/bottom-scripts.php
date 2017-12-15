<?php 
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
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
		} elseif ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/client.php') {

			if (isset($_GET['add_insurance_info']) || isset($_GET['edit_insurance_info'])) { ?>

				$("html, body").animate({ scrollTop: $('#insurance').offset().top }, 700); <?php
			}
		}
		?>

		$("#maskedPhoneNumber").mask("(999) 999-9999? x99999");
		$("#maskedFaxNumber").mask("(999) 999-9999");
		$("#maskedContactPhoneNumber").mask("(999) 999-9999? x99999");
		$("#formation_date").mask("99/99/9999");
		$("#auto_issuing_date").mask("99/99/9999");
		$("#auto_expiration_date").mask("99/99/9999");
		$("#cargo_issuing_date").mask("99/99/9999");
		$("#cargo_expiration_date").mask("99/99/9999");
		$("#commercial_issuing_date").mask("99/99/9999");
		$("#commercial_expiration_date").mask("99/99/9999");

		document.getElementById('user_type').onchange = function(){
			
			if (document.getElementById('user_type').selectedIndex == 3) {

				$('#user_manager_holder').removeClass("hidden");
			} else {

				$('#user_manager_holder').addClass("hidden");
			}
		}

	})();
</script>
