<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");

?>

<div class="row">
	<div class="col-sm-12 col-md-<?= $_GET['invoice_id'] ? '5' : '12' ?>">

		<div class="panel panel-default">

		  <div class="table-responsive<?= $_GET['new_invoice'] ? ' hidden' : '' ?>">
				  
			  <table class="table table-hover">
			  	
			  	<thead>
						<tr>
							<th class="hidden"></th>
							<th><small>Invoice Number</small></th>
							<th><small>Company</small></th>
							<th><small>Rate type</small></th>
							<th><small>Amount</small></th>
							<th><small>Status</small></th>
							<th></th>
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

										<?= $invoice_status[$i] == 1 ? '<span class="green">Paid</span>' : '<span class="red">Unpaid</span>' ?>
									</small>
								</td>

								<td>

									<small data-toggle="tooltip" data-placement="top" title="View invoice">

										<a href="?invoice_id=<?= $invoice_id[$i] ?>">
											
											<span class="fa fa-arrow-right"></span>
										</a>
									</small>
								</td>
							</tr> <?php
						} ?>

			  </table>
			</div>
		</div>

	</div>

	<div class="col-sm-12 col-md-7<?= $_GET['invoice_id'] ? '' : ' hidden' ?>">

		<div class="panel panel-default">

			<div class="panel panel-body" style="margin-bottom: 0;">

				<div class="col-sm-12 col-md-4">

					<small>
						
						Logistics Frog<br>
						844-345-3764
					</small>
				</div>

				<div class="col-sm-12 col-md-4 text-center">

					<img src="<?= $_SESSION['HtmlDelimiter'] ?>img/logo.png?r=<?= date('Gis') ?>"><br>
					<small>
						
						<?= $invoice_client_id_company_name[$invoice_get_id_client_id] ?>
					</small>
					
				</div>

				<div class="col-sm-12 col-md-4 text-right">

					<small>
						
						<?= $invoice_get_id_added ?><br>
						Invoice <?= $invoice_get_id_id ?>
					</small>
				</div>

				<div class="col-sm-12 col-md-12">

					<div class="well well-sm text-center" style="margin-bottom: 0;">
			  		
			  		Load report <?= $invoice_get_id_added_prev_friday ?> - 
			  		<?= $invoice_get_id_added_next_sunday ?>
			  	</div>
			  </div>
			</div>

		  <div class="table-responsive<?= $_GET['new_invoice'] ? ' hidden' : '' ?>">
				  
			  <table class="table table-hover">
			  	
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

			  </table>

			  <div class="well well-sm text-center" style="margin: 0 15px;">
		  		
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

										<a 
											class="red<?= $invoice_id_item_default_charge[$i] == 1 ? ' hidden' : '' ?>" 
											href="?invoice_id=9&_controller_invoice=delete_invoice_item&item_id=<?= $invoice_id_item_id[$i] ?>" 
											data-toggle="tooltip" 
											data-placement="top" 
											title="Delete charge">
											
											<span class="fa fa-times"></span> 	
										</a>
										
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

			  </table>

			  <form action="" method="post">
			  	
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

									Total amount due
								</small>
							</td>
							
							<td class="col-sm-12 col-md-2 text-right">

								<small>

									<?= '$' . number_format($invoice_id_item_total_amount, 2) ?>
								</small>
							</td>
						</tr>
			  </table>

				<!--Envío de parametros a V-POS2-->
			  <form name="f1" id="f1" action="#" method="post" class="alignet-form-vpos2">

			  	<input type="hidden" name ="acquirerId" value="<?= $acquirerId; ?>" />
					<input type="hidden" name ="idCommerce" value="<?= $idCommerce; ?>" />
					<input type="hidden" name="purchaseOperationNumber" value="<?= $purchaseOperationNumber; ?>" />
					<input type="hidden" name="purchaseAmount" value="<?= $purchaseAmount; ?>" />
					<input type="hidden" name="purchaseCurrencyCode" value="<?= $purchaseCurrencyCode; ?>" />
					<input type="hidden" name="language" value="SP" />                
					<input type="hidden" name="shippingFirstName" value="Steven" />
					<input type="hidden" name="shippingLastName" value="Picado" />
					<input type="hidden" name="shippingEmail" value="stevenpicado@gmail.com" />
					<input type="hidden" name="shippingAddress" value="Direccion ABC" />
					<input type="hidden" name="shippingZIP" value="ZIP 123" />
					<input type="hidden" name="shippingCity" value="CITY ABC" />
					<input type="hidden" name="shippingState" value="STATE ABC" />
					<input type="hidden" name="shippingCountry" value="PE" />                
					<!--Parametro para la Integracion con Pay-me. Contiene el valor del parametro codCardHolderCommerce.-->
					<input type="hidden" name="userCommerce" value="modalprueba1" /> <!-- 0101010101 -->
					<!--Parametro para la Integracion con Pay-me. Contiene el valor del parametro codAsoCardHolderWallet.-->
					<input type="hidden" name="userCodePayme" value="8--580--4390" /> <!-- 5--420--2340 -->
					<input type="hidden" name="descriptionProducts" value="Producto ABC" />
					<input type="hidden" name="programmingLanguage" value="PHP" />
					<!--Ejemplo envío campos reservados en parametro reserved1.-->
					<input type="hidden" name="reserved1" value="Valor Reservado ABC" />
					<input type="hidden" name="purchaseVerification" value="<?= $purchaseVerification; ?>" />
			  	
			  	<div class="form-group text-right" style="margin-right: 10px;">
						
						<input 
							class="btn btn-link" 
							type="button" 
							onclick="javascript:AlignetVPOS2.openModal('https://integracion.alignetsac.com/')" 
							value="Pay Invoice">
					</div>
			  </form>


			</div>
		</div>

	</div>

	
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
