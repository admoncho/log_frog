<?php
session_start();
ob_start();
$_SESSION['$clean_php_self'] = str_replace($_SESSION['IndexLocation'], "", $_SERVER['PHP_SELF']);
?>
<script src="<?= $_SESSION["href_location"] ?>js/select2.min.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/ckeditor/ckeditor.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/dataTables.fixedHeader.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/dataTables.tableTools.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/jquery.dataTables.bootstrap.js"></script> 
<script src="<?= $_SESSION["href_location"] ?>js/bootstrap-datepicker.js"></script>
<script src="<?= $_SESSION["href_location"] ?>js/bootstrap-timepicker.min.js"></script>

<script type="text/javascript">
	
	$(document).ready(function() {

		//nice select boxes
		$('#driver_id_select').select2();
		$('#driver_id_select2').select2();
		$('#broker_id_select').select2();

		//datepicker
		$('#datepickerDate').datepicker({
		  format: 'mm-dd-yyyy'
		});

		$('#datepickerPickDate').datepicker({
		  format: 'mm-dd-yyyy'
		});

		$('#datepickerDropDate').datepicker({
		  format: 'mm-dd-yyyy'
		});

		//timepicker
		$('#timepicker').timepicker({
			minuteStep: 5,
			showSeconds: false,
			showMeridian: false,
			disableFocus: false,
			showWidget: true
		}).focus(function() {
			$(this).next().trigger('click');
		});

		//timepicker
		$('#timepickerPick').timepicker({
			minuteStep: 5,
			showSeconds: false,
			showMeridian: false,
			disableFocus: false,
			showWidget: true
		}).focus(function() {
			$(this).next().trigger('click');
		});

		//timepicker
		$('#timepickerDrop').timepicker({
			minuteStep: 5,
			showSeconds: false,
			showMeridian: false,
			disableFocus: false,
			showWidget: true
		}).focus(function() {
			$(this).next().trigger('click');
		});

		$("[data-toggle=popover]").popover();

		<?php
                
		echo $_GET['upload_payment_confirmation'] ? '$("#schedule-files").popover(\'show\');' : '';
		echo $_POST['client_card_user_id'] ? '$("#cc-user-popover").popover(\'show\');' : '';

		if ($_GET['upload_payment_confirmation'] || ($_GET['payment_confirmation'] && !$payment_confirmation_exists[1])) { ?>

			document.getElementById('payment_confirmation').onchange = function(){
				if (document.getElementById('payment_confirmation').selectedIndex == 2) {
					$('#payment_confirmation_note_holder').removeClass("hidden");
				} else {
					$('#payment_confirmation_note_holder').addClass("hidden");
				}
			} <?php
		}

		if ($processing_file) { ?>

			$("html, body").animate({ scrollTop: $('#files').offset().top }, 700);

			var wrapper = $('<div/>').css({'display': 'none','overflow':'hidden'});
	    var fileInput = $(':file').wrap(wrapper);

	    fileInput.change(function(){
	      $this = $(this);
	      $('#file').text($this.val());
	    })

	    $('#file').click(function(){
	      fileInput.click();
	    }).show(); <?php
		}

		if ($_GET['draft_rate_con']) { ?>

			var wrapper = $('<div/>').css({'display': 'none','overflow':'hidden'});
	    var fileInput = $(':file').wrap(wrapper);

	    fileInput.change(function(){
	      $this = $(this);
	      $('#file').text($this.val());
	    })

	    $('#file').click(function(){
	      fileInput.click();
	    }).show(); <?php
		}

		if ($_GET['edit_other_charges']) { ?>

			$("html, body").animate({ scrollTop: $('#other-charges').offset().top }, 700); <?php
		} ?>

		$('#state_selector').select2();
		$('#pick_state_selector').select2();
		$('#drop_state_selector').select2();

		<?php 
                session_start();
                ob_start();
		if ($_GET['load_id']) { 

			if ($checkpoints_synced) { ?>

				// Redirect to display checkpoints after syncing
				window.location = 'load?load_id=<?= $_GET['load_id'] ?>'; <?php
			}
		}

		if ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/index.php') {
			if (!isset($_POST['add_load'])) { ?>

				var table = $('#<?= $module_name ?>-table').dataTable({
					'info': true,
					'order': [[ 0, "desc" ]],
					'Dom': 'lfr<"clearfix">tip',
					'iDisplayLength': 25
				});
				
			  // var tt = new $.fn.dataTable.TableTools( table ); This removes the buttons
				$( tt.fnContainer() ).insertBefore('div.dataTables_wrapper'); <?php
			}
		}

		if ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/schedule.php') {

			# If next to last item
			$_GET['counter'] == ($factoring_company_schedule_load_count - 1) ? $last_item = 1 : '' ;

			# Being on $_GET['create'] means we have created the soar file, create invoices is next
			# Being on $_GET['create_no_soar'] means we have skipped creating the soar file, create invoices is next
			if ($_GET['create'] || $_GET['create_no_soar']) { ?>
				
				window.location = 'schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $schedule_invoice_number[1] ?>&entry_id=<?= $load_list_entry_id[1] ?>&load_id=<?= $load_list_load_id[1] ?>&schedule_broker_id=<?= $load_list_broker_id[1] ?>&invoice_name=<?= $invoice_name[1] ?>&counter=1<?= $factoring_company_schedule_load_count == 1 ? '&last_item=1' : '' ?>'; <?php
			}

			# Run after getting rid of $_GET['create']
			if ($_GET['create_invoice'] && !$_GET['create'] && !$_GET['last_item']) {

				# Run as long as there are loads in schedule_id
				if ($_GET['counter'] <= $factoring_company_schedule_load_count) { ?>

					window.location = 'schedule?schedule_id=<?= $_GET['schedule_id'] ?>&create_invoice=<?= $_GET['create_invoice'] + 1 ?>&entry_id=<?= $load_list_entry_id[$_GET['counter'] + 1] ?>&load_id=<?= $load_list_load_id[$_GET['counter'] + 1] ?>&schedule_broker_id=<?= $load_list_broker_id[$_GET['counter'] + 1] ?>&invoice_name=<?= $invoice_name[$_GET['counter'] + 1] ?>&counter=<?= $_GET['counter'] + 1 ?>&last_item=<?= $last_item ?>'; <?php
				}
			}

			# Back to main after making TAFS invoice
			if ($_GET['create_tafs']) { ?>

				window.location = 'schedule?schedule_id=<?= $_GET['schedule_id'] ?>'; <?php
			}

			if ($_GET['last_item']) {

				# Go back to schedule ?>
				window.location = 'schedule?schedule_id=<?= $_GET['schedule_id'] ?>'; <?php
			}

			# CKEditor for schedule mail body
			if ($_GET['fee_option']) { ?>
				
				// CKEditor
				CKEDITOR.replace('body'); <?php
			} ?>

			$('.footable').footable();


			document.getElementById('payment_confirmation').onchange = function(){
				if (document.getElementById('payment_confirmation').selectedIndex == 2) {
					$('#payment_confirmation_note_holder').removeClass("hidden");
				} else {
					$('#payment_confirmation_note_holder').addClass("hidden");
				}
			} <?php
		}

		if ($_SESSION['$clean_php_self'] == '/dashboard/' . $module_name . '/draft-load.php') { ?>

			document.getElementById('lead-status').onchange = function(){

				
				if (document.getElementById('lead-status').selectedIndex == 2 || document.getElementById('lead-status').selectedIndex == 3) {

					$('#lead-note').addClass("has-error");
				} else {

					$('#lead-note').removeClass("has-error");
				}
			} <?php
		}
		?>

		CKEDITOR.replace('loader_status_change_notification');

	});

</script>