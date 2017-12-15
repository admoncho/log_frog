<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");

//Parametros Configuración
# TEST ENVIRONMENT
/*$acquirerId = '99';
$idCommerce = '8438';*/
# PRODUCTION ENVIRONMENT
$acquirerId = '12';
$idCommerce = '7652';

/*
LAST USED TEST ENVIRONMENT INVOICE ID = 163
*/

$purchaseOperationNumber = sprintf("%'.09d", $_GET['invoice_id']);
// $purchaseOperationNumber = '000000090';

# Check if purchase amount has decimals, if it comes with a dot (.) then yes
/*if (strpos($invoice_id_item_total_amount, '.') !== false) {
  
  $purchaseAmount = $invoice_id_item_total_amount;
} else {

  $purchaseAmount = $invoice_id_item_total_amount . '00';
}*/

$purchaseCurrencyCode = '840';

# Clave SHA-2 de VPOS2
# PRODUCTION ENVIRONMENT 
$claveSecreta = 'SJTsJZHChEBhFwWt_552953787';
# TEST ENVIRONMENT 
# $claveSecreta = 'EVBFVcDPLLCsUYACut-37263742782';

//VERSION PHP >= 5.3
//echo openssl_digest('', 'sha512');
//VERSION PHP < 5.3
//echo hash('sha512', '$acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta');
$purchaseVerification = openssl_digest($acquirerId . $idCommerce . $purchaseOperationNumber . $purchaseAmount . $purchaseCurrencyCode . $claveSecreta, 'sha512');

# Include module notification file
include $module_directory . 'inc/notification.php';

echo $purchaseAmount;
?>

<div class="row">
	<div class="col-sm-12 col-md-<?= $_GET['invoice_id'] ? '5' : '12' ?>">

		<div class="panel panel-default">

			<div class="panel-body<?= $_GET['new_invoice'] ? '' : ' hidden' ?>">

				<div class="row">

					<div class="col-sm-12 col-md-12 text-center">

						<h4 class="text-success">Add new invoice</h4>
					</div>
					
					<form action="" method="post">
	          
	          <div class="col-sm-12 col-md-3">
							
							<select name="client_id" class="form-control">
				        <option>Choose client</option>
				        
				        <?php
				        for ($i = 1; $i <= $new_invoice_client_count ; $i++) { ?>
				          
				          <option value="<?= $new_invoice_client_data_id[$i] ?>">
				          	
				          	<?= $new_invoice_client_company_name[$i] ?>
				          </option> <?php
				        }
				        ?>
				      </select>
						</div>

						<div class="col-sm-12 col-md-5">
							
							<input type="text" name="description" class="form-control" placeholder="Description - You can add more items once you create it">
						</div>

						<div class="col-sm-12 col-md-2">
							
							<input type="number" min="1" name="amount" class="form-control" placeholder="Amount">
						</div>

						<div class="col-sm-12 col-md-2">
							
							<button type="submit" class="btn btn-link">Save</button>
							<a href="<?= $_SESSION['href_location'] ?>dashboard/invoice/" class="btn btn-link red">Cancel</a>
						</div>

						<input type="hidden" name="_controller_invoice" value="add_new_invoice">
		      	<input type="hidden" name="token" value="<?= $csrfToken ?>">
			    </form>
				</div>
			</div>

		  <div class="table-responsive">
					
				<?php

				# Only internal users can see invoice counts
				if ($user->data()->user_group != 4) { ?>

					<div class="row" style="padding: 15px 15px 0 0">

						<div class="col-sm-12 col-md-4">
							<form action="" method="post" <?= isset($_POST['date_range']) ? ' class="hidden"' : '' ?>>
								
								<div class="form-group col-md-8">
									<div class="input-group">
										<span class="input-group-addon"><i class="fa fa-calendar-o"></i></span>
										<input 
											type="text" 
											name="date_range" 
											class="form-control" 
											id="datepickerDateRange" 
											placeholder="Amount by date range">
									</div>
								</div>

								<div class="form-group col-md-4">
									<input type="submit" name="submit" value="Send" class="btn btn-link">
								</div>

							</form>

							<?php

							if (isset($_POST['date_range'])) { ?>
								<p class="lead text-info">
									<?= $start_month . '/' . $start_day . '/' . $start_year ?> 
									to  
									<?= $end_month . '/' . $end_day . '/' . $end_year ?> 
									<b>$ <?= number_format($date_range_invoice_purchaseAmount, 2) ?></b>
									<small><a href="<?= $_SESSION['href_location'] ?>dashboard/invoice/">reset</a></small>
								</p> <?php
							}
							?>
						</div>

						<div class="col-sm-12 col-md-8 text-right">
							<p class="lead text-info">
								<small>Today</small> <b>$<?= number_format($today_invoice_purchaseAmount, 2) ?></b> 
								<small>This Month</small> <b>$<?= number_format($month_invoice_purchaseAmount, 2) ?></b> 
								<small>This year</small> <b>$<?= number_format($year_invoice_purchaseAmount, 2) ?></b> 
							</p>
						</div>
					</div>
					
				<?php } ?>

		  	<table class="table table-hover" id="invoice-table">
			  	<thead>
						<tr>
							<th class="hidden"></th>
							<th><small>Invoice Number</small></th>
							<th><small>Company</small></th>
							<th><small>Rate type</small></th>
							<th><small>Amount</small></th>
							<th><small>Status</small></th>
							<th class="text-right">
								<small>
									
									<?php

									# Only internal users can add invoices
									if ($user->data()->user_group != 4) { ?>
									 	
									 	<a href="?new_invoice=1"<?= $_GET['new_invoice'] ? ' class="hidden"' : '' ?>>
									 		
									 		<i class="fa fa-plus"></i> Add invoice
									 	</a> <?php
									}
									?>

								</small>
							</th>
						</tr>
					</thead>

					<tbody>

						<?php

						for ($i = 1; $i <= $invoice_count; $i++) { ?>
							
							<tr<?= $_GET['invoice_id'] == $invoice_id[$i] ? ' class="active"' : '' ?>>
								
								<td class="hidden">
								<!-- This hidden td is to kill different formatting on the first item -->
								</td>

								<td>

									<small>

										<?= $invoice_id[$i] ?>
									</small>
								</td>

								<td>

									<small>

										<?= $invoice_client_id_company_name[$invoice_client_id[$i]] ?>
									</small>
								</td>

								<td>

									<small>

										<?php

										if ($invoice_rate_type[$i] == 1 && $invoice_rate[$i] == 150) {
											
											echo "Full Service";
										} elseif ($invoice_rate_type[$i] == 1 && $invoice_rate[$i] == 100) {

											echo "Back office Services";
										} elseif ($invoice_rate_type[$i] == 2) {

											echo $invoice_rate[$i] . '% Commission';
										}
										?>
									</small>
								</td>
								
								<td>

									<small>

										<?= '$' . $total_cost[$i] ?>
									</small>
								</td>

								<td>

									<small>

										<?php

										if ($invoice_errorCode[$i] > 0) {
											
											echo '<span class="red">Cancelled</span>';
										} else {

											if ($invoice_authorizationCode[$i] > 0) {
												
												echo '<span class="green">Paid</span>';
											} else {

												echo '<span class="text-warning">Unpaid</span>';
											}
										}
										?>

									</small>
								</td>

								<td class="text-right">

									<small data-toggle="tooltip" data-placement="top" title="View invoice">

										<a href="?invoice_id=<?= $invoice_id[$i] ?>">
											
											<span class="fa fa-arrow-right"></span>
										</a>
									</small>
								</td>
							</tr> <?php
						} ?>

					</tbody>
			  </table>
				
			</div>
		</div>

	</div>

	<div class="col-sm-12 col-md-7<?= $_GET['invoice_id'] ? '' : ' hidden' ?>">

		<div class="panel panel-default">

			<div class="panel panel-body" style="margin-bottom: 0;">

				<?php 
				if ($invoice_get_id_rejected != 'Nov 30, -0001') { ?>
					
					<div class="col-sm-12 col-md-12">
						
						<div class="alert alert-danger text-center" role="alert">

							<b>This invoice is cancelled or it was rejected!</b>
						</div>
					</div> <?php
				}

				if ($invoice_get_id_paid != 'Nov 30, -0001') {  ?>
					
					<div class="col-sm-12 col-md-12">
						
						<div class="alert alert-success text-center" role="alert">

							<b>This invoice was paid on <?= $invoice_get_id_paid ?></b>
						</div>
					</div> <?php
				 } ?>

				<div class="col-sm-12 col-md-4">

					<small>
						
						Logistics Frog<br>
						844-345-3764
					</small>
				</div>

				<div class="col-sm-12 col-md-4 text-center">

					<img src="<?= $_SESSION["href_location"] ?>img/logo.png?r=<?= date('Gis') ?>"><br>
					<small>
						
						<?= $invoice_client_id_company_name[$invoice_get_id_client_id] ?>
					</small>
					
				</div>

				<div class="col-sm-12 col-md-4 text-right">

					<small>
						
						<?= $invoice_get_id_added ?><br>
						Invoice <?= $_GET['invoice_id'] ?><br>
						<a href="<?= $_SESSION['href_location'] ?>dashboard/invoice/" class="red">Close invoice</a>

						<?php

						# Delete invoice button
						# Only show to internal users
						if ($user->data()->user_group != 4) {

							# Only show delete button on unpaid invoices
							if ($invoice_get_id_paid == 'Nov 30, -0001' && $invoice_get_id_rejected == 'Nov 30, -0001') { ?>
							 	
								| 
							 	<a 
							 		href="?invoice_id=<?= $_GET['invoice_id'] ?>&delete_invoice=1" 
							 		data-toggle="tooltip" 
							 		data-placement="top" 
							 		title="Delete this invoice" 
							 		<?= $_GET['delete_invoice'] ? ' class="hidden"' : '' ?>
							 		
							 		<i class="fa fa-trash-o red"></i>
							 	</a> 

							 	<form action="" method="post"<?= $_GET['delete_invoice'] ? '' : ' class="hidden"' ?>>
							 		
							 		<button class="btn btn-link btn-sm red">Delete invoice</button>

							 		 | 
							 		<a class="btn btn-link btn-sm" href="?invoice_id=<?= $_GET['invoice_id'] ?>"> Cancel</a>

							 		<input type="hidden" name="_controller_invoice" value="delete_invoice">
			      			<input type="hidden" name="token" value="<?= $csrfToken ?>">
							 	</form> <?php
							}
						}
						?>
					</small>
				</div>

				<div class="col-sm-12 col-md-12<?= $invoice_id_load_count ? '' : ' hidden' ?>">

					<div class="well well-sm text-center" style="margin-bottom: 0;">
			  		
			  		Load report <?= $invoice_get_id_added_prev_friday ?> - 
			  		<?= $invoice_get_id_added_next_sunday ?>
			  	</div>
			  </div>
			</div>

		  <div class="table-responsive">
				  
			  <table class="table table-hover<?= $invoice_id_load_count ? '' : ' hidden' ?>">
			  	
			  	<thead>
						<tr>
							<th class="hidden"></th>
							<th><small>Date</small></th>
							<th><small>Broker</small></th>
							<th><small>Driver</small></th>
							<th><small>Origin - Destination</small></th>
							<th class="text-right"><small>Paid rate</small></th>
						</tr>
					</thead>

					<tbody>

						<?php

						for ($i = 1; $i <= $invoice_id_load_count; $i++) { ?>
							
							<tr>
								
								<td class="hidden">
									<!-- This hidden td is to kill different formatting on the first item -->
								</td>

								<td>

									<small>

										<?= $invoice_id_load_first_checkpoint[$i] ?>
									</small>
								</td>

								<td>

									<small>

										<?= $invoice_id_load_broker_company_name[$i] ?>
									</small>
								</td>

								<td>

									<small>

										<?= $user_list_id_name[$invoice_id_load_driver_id[$i]] . ' ' . $user_list_id_last_name[$invoice_id_load_driver_id[$i]] ?>
									</small>
								</td>

								<td>

									<small>
										<?= $first_checkpoint_city[$i] . ', ' . $state_abbr[$first_checkpoint_state_id[$i]] . ' - ' .
										$last_checkpoint_city[$i] . ', ' . $state_abbr[$last_checkpoint_state_id[$i]] ?>
									</small>
								</td>
								
								<td class="text-right">

									<small>

										<?= '$' . $invoice_id_load_line_haul[$i] ?>
									</small>
								</td>
							</tr> <?php
						} ?>

						<tr>
								
							<td class="hidden">
								<!-- This hidden td is to kill different formatting on the first item -->
							</td>

							<td></td>

							<td></td>

							<td></td>

							<td>

								<small>
									<b>Total</b>
								</small>
							</td>
							
							<td class="text-right">

								<small>

									<b><?= '$' . number_format($invoice_id_load_line_haul_total, 2) ?></b>
								</small>
							</td>
						</tr>
					</tbody>
			  </table>

			  <div class="well well-sm text-center" style="margin: 15px;">
		  		
		  		Invoice charges
		  	</div>
				  
			  <table class="table table-hover">
			  	
			  	<thead>
						<tr>
							<th class="hidden"></th>
							<th><small>Description</small></th>
							<th class="text-right"><small>Amount due</small></th>
						</tr>
					</thead>

					<tbody>

						<?php

						for ($i = 1; $i <= $invoice_id_item_count; $i++) { ?>
							
							<tr>
								
								<td class="hidden">
									<!-- This hidden td is to kill different formatting on the first item -->
								</td>

								<td>
									<small>

										<?php
										# Only show delete charge button on unpaid invoices
										if ($invoice_get_id_paid == 'Nov 30, -0001' && $invoice_get_id_rejected == 'Nov 30, -0001') { ?>

										 	<a 
												class="red<?= $invoice_id_item_default_charge[$i] == 1 || $user->data()->user_group == 4 ? ' hidden' : '' ?>" 
												href="?invoice_id=9&_controller_invoice=delete_invoice_item&item_id=<?= $invoice_id_item_id[$i] ?>" 
												data-toggle="tooltip" 
												data-placement="top" 
												title="Delete charge">
												
												<span class="fa fa-times"></span> 	
											</a> <?php
										}
										?>
										
										<?= $invoice_id_item_description[$i] ?>
									</small>
								</td>
								
								<td class="text-right">

									<small>

										<?= '$' . $invoice_id_item_cost[$i] ?>
									</small>
								</td>
							</tr> <?php
						} ?>

					</tbody>
			  </table>

			  <form action="" method="post"<?= (isset($invoice_get_id_errorMessage) && $invoice_get_id_errorMessage != '') || $user->data()->user_group == 4 ? ' class="hidden"' : '' ?>>
			  	
			  	<div class="form-group col-sm-12 col-md-10">
						<input type="text" name="description" class="form-control" placeholder="Add new item to invoice - Description">
					</div>

			  	<div class="form-group col-sm-12 col-md-2">

						<input 
							type="number" 
							min="1" 
							step="0.01" 
							name="amount" 
							class="form-control" 
							placeholder="Amount" 
							data-toggle="tooltip"
							data-placement="top" 
							title="Enter to save">
					</div>

					<input type="submit" value="1" class="hidden">
					
					<input type="hidden" name="_controller_invoice" value="add_invoice_item">
		      <input type="hidden" name="token" value="<?= $csrfToken ?>">
			  </form>

			  <table class="table table-hover">

					<tbody>

						<tr>
							
							<td class="hidden">
								<!-- This hidden td is to kill different formatting on the first item -->
							</td>

							<td class="col-sm-12 col-md-10 text-right">

								<small>

									<b>Total amount due</b>
								</small>
							</td>
							
							<td class="col-sm-12 col-md-2 text-right">

								<small>

									<b><?= '$' . number_format($invoice_id_item_total_amount, 2) ?></b>
								</small>
							</td>
						</tr>

					</tbody>
			  </table>

			  <!-- Only show Terms and Conditions on invoices that have not been paid nor rejected (only unpaid) -->
			  <div 
			  	class="well well-sm<?= $invoice_get_id_paid == 'Nov 30, -0001' && $invoice_get_id_rejected == 'Nov 30, -0001' ? '' : ' hidden' ?>" 
			  	style="margin: 0 15px;">
		  			
		  		<div class="row">
		  			<div class="col-sm-12 col-md-11 text-right">
		  				
		  				<small>
		  					
		  					I <b>HAVE READ</b> and I <b>AGREE</b> to the <span class="red">Terms and Conditions</span>
		  				</small><br>
		  				<small>
		  					He <b>LEIDO</b> y estoy de <b>ACUERDO</b> con los <span class="red">Terminos y Condiciones</span>
		  				</small>
		  			</div>

		  			<div class="col-sm-12 col-md-1">
		  				
		  				<form action="" method="get">
		  					<input type="hidden" name="invoice_id" value="<?= $_GET['invoice_id'] ?>">
		  					<div class="input-group">
						      <span class="">

						        <input 
						        	name="agree_terms_conditions" 
						        	type="checkbox" 
						        	aria-label="" 
						        	onclick="this.form.submit()" 
						        	<?= $_GET['agree_terms_conditions'] ? 'checked' : '' ?>>
						      </span>
						    </div>
		  				</form>
		  			</div>

		  			<div class="col-sm-12 col-md-12">

		  				<embed width="100%" src="<?= $_SESSION["href_location"] ?>terms-and-conditions"></embed>
		  			</div>
		  		</div>
		  	</div>

				<!--Envío de parametros a V-POS2-->
			  <form name="f1" id="f1" action="#" method="post" class="alignet-form-vpos2<?= isset($invoice_get_id_errorMessage)  && $invoice_get_id_errorMessage != '' ? ' hidden' : '' ?>">

			  	<input type="hidden" name ="acquirerId" value="<?= $acquirerId; ?>" />
					<input type="hidden" name ="idCommerce" value="<?= $idCommerce; ?>" />
					<input type="hidden" name="purchaseOperationNumber" value="<?= $purchaseOperationNumber ?>" />
					<input type="hidden" name="purchaseAmount" value="<?= $purchaseAmount ?>" />
					<input type="hidden" name="purchaseCurrencyCode" value="<?= $purchaseCurrencyCode; ?>" />
					<input type="hidden" name="language" value="EN" />
					<input type="hidden" name="shippingFirstName" value="<?= $user_list_id_name[$invoice_manager_id] ?>" />
					<input type="hidden" name="shippingLastName" value="<?= $user_list_id_last_name[$invoice_manager_id] ?>" />
					<input type="hidden" name="shippingEmail" value="<?= $user_list_id_email[$invoice_manager_id] ?>" />
					<input type="hidden" name="shippingAddress" value="<?= $invoice_location_line_1 ?>" />
					<input type="hidden" name="shippingZIP" value="<?= $invoice_location_zip_code ?>" />
					<input type="hidden" name="shippingCity" value="<?= $invoice_location_city ?>" />
					<input type="hidden" name="shippingState" value="<?= $state_abbr[$invoice_location_state_id] ?>" />
					<input type="hidden" name="shippingCountry" value="US" />
					<input type="hidden" name="descriptionProducts" value="<?= $description_product ?>" />
					<input type="hidden" name="programmingLanguage" value="PHP" />
					<!--Ejemplo envío campos reservados en parametro reserved1.-->
					<input type="hidden" name="reserved1" value="1" />
					<input type="hidden" name="reserved23" value="<?= $invoice_client_id_company_name[$invoice_get_id_client_id] ?>" />
					<input type="hidden" name="purchaseVerification" value="<?= $purchaseVerification; ?>" />
			  	
			  	<div class="form-group text-right" style="margin-right: 10px;">
						
						<input 
							class="btn btn-link" 
							type="button" 
							onclick="javascript:AlignetVPOS2.openModal('', '1')" 
							value="Pay Invoice" 
							<?= $_GET['agree_terms_conditions'] ? '' : 'disabled' ?>>
					</div>
						<!-- TEST ENVIRONMENT onclick="javascript:AlignetVPOS2.openModal('https://integracion.alignetsac.com/')"  -->
						<!-- PRODUCTION ENVIRONMENT onclick="javascript:AlignetVPOS2.openModal('', '1')"  -->
			  </form>
			</div>

			<div class="panel-footer">

				<small class="text-muted">
					Autorizado por resoluci&oacute;n #11-97 gaceta n&uacute;mero 171 del 09/09/97 de la DGT.<br>
					Inversiones Cerro Modesto S.A.<br>
					C&eacute;dula Jur&iacute;dica 3-101-733145<br>
					Cartago, Costa Rica
				</small>
			</div>
		</div>

	</div>

	
</div>

<?php

require TEMPLATE_PATH .'/back-end/bottom.php';

?>

