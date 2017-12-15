<?php
session_start();
ob_start();
?>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.maskedinput.min.js"></script>

<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/dataTables.fixedHeader.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/dataTables.tableTools.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.bootstrap.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/bootstrap-datepicker.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/moment.min.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/daterangepicker.js"></script>

<script>
$(document).ready(function() {

	//daterange picker
	$('#datepickerDateRange').daterangepicker();

	var table = $('#reports-table').dataTable({
		'info': true,
		'order': [[ 0, "desc" ]],
		'Dom': 'lfr<"clearfix">tip',
		'iDisplayLength': 25
	});

	document.getElementById("reports-table_length").setAttribute("style", "margin-left: 15px;");
	document.getElementById("reports-table_filter").setAttribute("style", "margin-right: 15px;");
	
  // var tt = new $.fn.dataTable.TableTools( table ); // Comment out to remove buttons
	$( tt.fnContainer() ).insertBefore('div.dataTables_wrapper');
});
</script>

