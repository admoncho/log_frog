<?php 
session_start();
ob_start();
# Redirect if no query string
if (!$_SERVER['QUERY_STRING']) { ?>
	
	<script type="text/javascript">
		
		window.location = "/dashboard/loader/"
	</script> <?php
}

require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

?>

<div class="row">
	<div class="col-sm-12 col-md-6">
		
		<div class="row">

			<div class="col-sm-12 col-md-5 text-right">

				<p class="lead" style="margin-bottom: 0;">

					<?php
					
					if ($draft_checkpoint_id_city[1]) {
					 	
						echo $draft_checkpoint_id_city[1] . ', ' . $state_abbr[$draft_checkpoint_id_state_id[1]];
					} else {

						echo '<i class="red"><i class="fa fa-warning"></i> Info missing</i>';
					}
					?>
					
				</p>

				<small>
					
					<?php 
					if ($draft_checkpoint_id_date_time[1] != '11/30/-0001 0:00') {
						
						echo $draft_checkpoint_id_date_time[1];
					}?>

				</small>
			</div>

			<div class="col-sm-12 col-md-2 text-center">
				
				<p class="lead" style="margin-bottom: 0;"><i class="fa fa-arrows-h"></i></p>

				<?php

				# Display warning if multiple checkpoints detected
				if ($draft_checkpoint_id_count > 2) { ?>
					
					<i class="fa fa-warning red" data-toggle="tooltip" data-position="top" title="This draft has multiple picks/drops"></i> <?php
				}
				?>
			</div>

			<div class="col-sm-12 col-md-5">

				<p class="lead" style="margin-bottom: 0;">

					<?php
					if ($draft_checkpoint_id_city[$draft_checkpoint_id_count] && $draft_checkpoint_id_count > 1) {
						
						echo $draft_checkpoint_id_city[$draft_checkpoint_id_count] . ', ' . $state_abbr[$draft_checkpoint_id_state_id[$draft_checkpoint_id_count]];
					} else {

						echo '<i class="red"><i class="fa fa-warning"></i> Info missing</i>';
					}
					?>
					
				</p>

				<small>
					
					<?php 
					if ($draft_checkpoint_id_date_time[$draft_checkpoint_id_count] != '11/30/-0001 0:00') {
						
						echo $draft_checkpoint_id_date_time[$draft_checkpoint_id_count];
					}?>

				</small>
			</div>

			<div class="col-sm-12 col-md-4">
				
				<p<?= $draft_deadhead[1] != '0.0' ? ' class="red"' : '' ?>>
					<small>

						<b><?= $draft_deadhead[1] != '0.0' ? 'DH: ' . str_replace('.0', '', $draft_deadhead[1]) . 'm' : 'no deadhead' ?></b>

					</small>
				</p>

			</div>

			<div class="col-sm-12 col-md-4 text-center" style="padding-top: 15px;">

				<small>Rate</small>
				<p class="lead">
					
					<b style="color: #006400;">$<?= $draft_initial_rate[1]; ?> </b>
				</p>

			</div>

			<div class="col-sm-12 col-md-4 text-right">

				<?php
				# Draft loads can only be shown to agents
		  	if ($user->hasPermission('developer') || $user->hasPermission('dispatcher')) { ?>
							
					<a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>&edit_main=1" class="btn btn-link btn-sm<?= $_GET['edit_main'] ? ' hidden' : '' ?>">
						Edit main data <i class="fa fa-pencil"></i>
					</a> <?php
				}
				?>

			</div>
		</div>
	</div>

	<div class="col-sm-12 col-md-6">

		<ul class="list-group">

			<?php
  		if ($_GET['edit_main']) {

				# Edit main data ?>
				<li class="list-group-item">

					<div class="alert alert-info">
						<i class="fa fa-info-circle fa-fw fa-lg"></i>
						Main data update
					</div>

					<form action="" method="post">

						<div class="row">

							<div class="form-group col-sm-12 col-md-6">
								<label class="control-label">Broker's name &amp; number</label>
								<input name="broker_name_number" type="text" class="form-control" value="<?= $draft_broker_name_number[1] ?>">
							</div>
							
							<div class="form-group col-sm-12 col-md-6">
								<label class="control-label">Broker's email</label>
								<input name="broker_email" type="email" class="form-control" value="<?= strtolower($draft_broker_email[1]) ?>">
							</div>
						</div>

						<div class="row">
							
							<div class="form-group col-sm-12 col-md-2">
								<label class="control-label">Rate</label>
								<input name="initial_rate" type="number" class="form-control" min="0" step="0.01" value="<?= str_replace(',', '', $draft_initial_rate[1]) ?>">
							</div>
							
							<div class="form-group col-sm-12 col-md-2">
								<label class="control-label text-right">Deadhead</label>
								<input name="deadhead" type="number" class="form-control" min="0" step="0.1" value="<?= str_replace(',', '', $draft_deadhead[1]) ?>">
							</div>

							<div class="form-group col-sm-12 col-md-8">
								<label class="control-label text-right">Notes</label>
								<input name="note" type="text" class="form-control" value="<?= $draft_note[1] ?>">
							</div>

							<div class="form-group col-sm-12 col-md-12">
								
								<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> Save</button>
								
								<a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="btn btn-link red"> Cancel</a>
							</div>
						</div>

						<input type="hidden" name="_controller_draft_load" value="update_draft">
						<input type="hidden" name="token" value="<?= $csrfToken ?>">

					</form>
				</li> <?php
			}
  		?>

		  <li class="list-group-item<?= $draft_accepted ? '' : ' hidden' ?>">
		  	<small>
		  		<b>Driver: </b> 

		  		<?php
		  		# Driver name
		  		echo $user_list_id_name[$draft_accepted] . ' ' . $user_list_id_last_name[$draft_accepted];
		  		?>

		  	</small>

		  </li>
		  <?= $draft_note[1] ? '<li class="list-group-item"><small><b>Notes: </b>' . $draft_note[1] . '</small></li>' : ''; ?>
		  
		  <li class="list-group-item list-group-item-danger"><small><b>Status: </b>Draft</small></li>
		  
		  <li class="list-group-item list-group-item-default">
		  	<small>
		  		<b>Dispatcher: </b><?= $user_list_id_name[$draft_user_id[1]] . ' ' . $user_list_id_last_name[$draft_user_id[1]]; ?> 
		  		<i style="color: #888;"> <?= $draft_added[1] . ' at ' . $draft_added_time[1] ?></i>
		  	</small>
		  </li>

		  <li class="list-group-item list-group-item-default">
		  	<small>
		  		<b>Broker: </b><?= $draft_broker_name_number[1]; ?> 

		  		<a href="mailto:<?= $draft_broker_email[1] ?>"><?= $draft_broker_email[1] ?></a>
		  	</small>
		  </li>

		</ul>
	</div>

	<div class="col-sm-12 col-md-12">

		<div class="main-box">

			<header class="main-box-header clearfix">
				<h2 class="pull-left">Leads</h2>

				<form action="" method="post"<?= $_POST['add_new_lead'] || !$draft_lead_count || $draft_accepted ? ' class="hidden"' : '' ?>>
					
					<button type="submit" class="bnt btn-link" data-toggle="tooltip" data-placement="top" title="Add new lead">
						
						<i class="fa fa-plus"></i>
					</button>

					<input type="hidden" name="add_new_lead" value="1">
				</form>
			</header>

			<div class="main-box-body clearfix">
				<div class="row">

					<div class="<?= $_POST['add_new_lead'] || !$draft_lead_count ? 'col-md-3' : ' hidden' ?>">

						<form action="" method="post">
          
		          <div class="form-group">

		            <select name="driver_id" class="form-control">
									<option>New lead, choose driver</option>
									
									<?php
				          for ($i = 1; $i <= $driver_list_count ; $i++) {

				          	# Show only active client drivers
				          	if ($driver_list_client_status[$i] == 1) { ?>
				            
				            	<option value="<?= $driver_list_user_id[$i] ?>">
				            		
				            		<?= $user_list_id_name[$driver_list_user_id[$i]] . ' ' . $user_list_id_last_name[$driver_list_user_id[$i]] . ' [' . $client_id_company_name[$driver_list_client_id[$i]] . ']' ?>
				            		
				            	</option> <?php
				          	}
				          }
				          ?>
								</select>
		          </div>

		          <div class="form-group">

		            <select id="lead-status" name="status" class="form-control">

									<option>Response type</option>
									<option value="1">No answer</option>
									<option value="2">On hold (requires note)</option>
									<option value="3">Declined (requires note)</option>
									<option value="4"<?= $pick_drop_ready ? '' : ' disabled' ?>>Accepted</option>
								</select>
		          </div>
		          
		          <div class="form-group" id="lead-note">

		            <input 
		            	name="note" 
		            	type="text" 
		            	class="form-control" 
		            	placeholder="Note" 
		            	placeholder="Notes"
		            	data-toggle="tooltip" 
		            	data-placement="top"
		            	title="Lead notes stay in the draft, if this draft becomes a 
		            	load these notes will not be seen there. The only notes 
		            	passed are the draft notes.">
		          </div>

		          <div class="col-sm-12 col-md-12 text-right">
		            <div class="form-group">

		              <button class="btn btn-link"><i class="fa fa-save"></i> Save</button>
		              <a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="btn btn-link red"> Cancel</a>
		            </div>
		          </div>

		          <input type="hidden" name="_controller_draft_lead" value="add">
		          <input type="hidden" name="token" value="<?= $csrfToken ?>">
		        </form>
					</div>

					<div class="<?= $_POST['add_new_lead'] || !$draft_lead_count ? 'col-md-9' : '' ?>">

						<?php 

						if ($draft_lead_count) {

							for ($i = 1; $i <= $draft_lead_count; $i++) {

								# Hide when put_on_hold or decline actions are called
								if (
									(isset($_GET['put_on_hold']) && ($_GET['put_on_hold'] != $draft_lead_id[$i]))
									|| (isset($_GET['decline']) && ($_GET['decline'] != $draft_lead_id[$i]))
								) {
								 	
								 	# Hide lead
								 	echo '<div class="hidden">';
								} ?>

								<p>
									<strong>
										<?php

										# Display loop data
										echo $user_list_id_name[$draft_lead_driver_id[$i]] . ' ' . $user_list_id_last_name[$draft_lead_driver_id[$i]];
										echo ' | ' . $client_id_company_name[$draft_client_id[$i]];
										?>
									</strong>
								</p>

								<?php

								# Clause
								if ($_GET['accept']) {
									
									$clause = " WHERE id = " . $_GET['accept'];
								} else {

									$clause = " WHERE lead_id = " . $draft_lead_id[$i] . " ORDER BY added DESC";
								}

								# loader_load_draft_lead_status
								$draft_lead_status = DB::getInstance()->query("SELECT * FROM loader_load_draft_lead_status" . $clause);

								$draft_lead_status_count = $draft_lead_status->count();

								$lsi = 1;

								if ($draft_lead_status_count) {

									# Iterate through items
									foreach ($draft_lead_status->results() as $draft_lead_status_data) {
										
										$draft_lead_status_lead_id[$lsi] = $draft_lead_status_data->lead_id;
										$draft_lead_status_id[$lsi] = $draft_lead_status_data->id;
										$draft_lead_status_status[$lsi] = $draft_lead_status_data->status; // 1 no answer 2 on hold 3 declined 4 accepted 5 canceled
										$draft_lead_status_note[$lsi] = html_entity_decode($draft_lead_status_data->note);
										$draft_lead_status_user_id[$lsi] = $draft_lead_status_data->user_id;
										$draft_lead_status_added[$lsi] = date('M d Y G:i', strtotime($draft_lead_status_data->added));
										$draft_lead_status_added_1[$lsi] = date('M d Y', strtotime($draft_lead_status_data->added));
										$draft_lead_status_added_2[$lsi] = date('G:i', strtotime($draft_lead_status_data->added));
										$draft_lead_status_added_3[$lsi] = date('m/d/Y', strtotime($draft_lead_status_data->added)); ?>

										<div class="row">

											<div class="col-sm-12 col-md-12<?= $draft_accepted ? ' hidden' : '' ?>"> 

												<!-- Put on hold link, activates form with note input | $draft_lead_status[1] == last status update -->
												<a 
													class="
														btn 
														btn-link 
														pull-left
														<?= $draft_lead_status_status[$lsi] == 3 && !$_GET['put_on_hold'] && !$_GET['accept'] && $lsi == 1 ? '' : ' hidden' ?>" 
													href="draft-load?draft_id=<?= $_GET['draft_id'] ?>&put_on_hold=<?= $draft_lead_id[$i] ?>">
													
													Put on hold
												</a>

												<?php

												# Accept link, activates form with driver_request_rate and final_rate inputs
												# Only display on last status
												if ($lsi == 1) {

													# Only display if not updating status
													if (!$_GET['accept'] && !$_GET['decline'] && !$_GET['put_on_hold']) { ?>

														<a 
															class="
																btn 
																btn-link 
																pull-left
																<?php 																
																	
																if ($draft_lead_status_status[$lsi] == 1 || $draft_lead_status_status[$lsi] == 4) {
																	
																	echo ' hidden';
																} ?>" 

															href="
																draft-load?draft_id=<?= $_GET['draft_id'] ?>
																&lead_id=<?= $draft_lead_id[$i] ?>
																&accept=<?= $draft_lead_status_id[$lsi] ?>
															"
															<?= $pick_drop_ready ? '' : ' disabled="disabled"' ?>
															<?= $pick_drop_ready ? '' : ' data-toggle="tooltip" data-placement="top" title="Checkpoints missing"' ?>>

															<?= $pick_drop_ready ? '' : '<s>' ?>
															Accept
															<?= $pick_drop_ready ? '' : '</s>' ?>
														</a> <?php
													}
												} ?>

												<!-- Mark accepted form -->
												<form 
													action="" 
													method="post" 
													class="
														pull-left
														<?php 

														if (!$_GET['accept']) {
															
															echo ' hidden';
														} ?>
													">

													<div class="row">
														
														<div class="form-group col-sm-12 col-md-6 has-error">
															
															<input type="number" step="0.01" name="driver_request_rate" class="form-control" placeholder="Driver request rate">
														</div>

														<div class="form-group col-sm-12 col-md-6 has-error">
															
															<input type="number" step="0.01" name="final_rate" class="form-control" placeholder="Final rate">
														</div>
													</div>
													
													<a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="small btn btn-link red">Cancel</a>
													<button type="submit" class="small btn btn-link pull-left">Accept</button><hr>
													<input type="hidden" name="lead_id" value="<?= $draft_lead_id[$i] ?>">
													<input type="hidden" name="_controller_draft_lead" value="accept">
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form>

												<?php

												# Mark as declined link, display when on hold and accepted, hide if updating status
												# Only display on last status
												if ($lsi == 1) { ?>

													<a 
														class="
															btn 
															btn-link 
															pull-left
															<?php

															if ($draft_lead_status_status[$lsi] == 1 || $draft_lead_status_status[$lsi] == 3) {
																
																echo ' hidden';
															} ?>" 

														style="color: #777;"

														href="draft-load?draft_id=<?= $_GET['draft_id'] ?>&decline=<?= $draft_lead_id[$i] ?>">
														
														Mark as declined
													</a> <?php
												}
												?>

												<!-- Mark as declined form, with note input -->
												<form 
													action="" 
													method="post" 
													class="<?= $_GET['decline'] && ($lsi == 1) ? '' : ' hidden' ?>">
													
													<div class="form-group has-error" style="margin-top: 20px;">
														
														<input 
															type="text" 
															name="note" 
															class="form-control" 
															placeholder="Mark as declined notes" 
															data-toggle="tooltip" 
															data-placement="top" 
															title="Enter to save">	
													</div>

													<a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="small btn btn-link red">Cancel</a><hr>
													<input type="hidden" name="driver_id" value="<?= $draft_lead_driver_id[$i] ?>">
													<input type="hidden" name="lead_id" value="<?= $draft_lead_id[$i] ?>">
													<input type="hidden" name="_controller_draft_lead" value="decline">
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form>

												<!-- Put on hold, second form, with note input -->
												<form action="" method="post" class="<?= $_GET['put_on_hold'] ? '' : ' hidden' ?>">
													
													<div class="form-group has-error" style="margin-top: 20px;">
														
														<input 
															type="text" 
															name="note" 
															class="form-control" 
															placeholder="Put on hold notes" 
															data-toggle="tooltip" 
															data-placement="top" 
															title="Enter to save">	
													</div>

													<a href="draft-load?draft_id=<?= $_GET['draft_id'] ?>" class="small btn btn-link red">Cancel</a>
													<input type="hidden" name="lead_id" value="<?= $draft_lead_id[$i] ?>">
													<input type="hidden" name="_controller_draft_lead" value="put_on_hold">
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form> <?php

												echo $_GET['put_on_hold'] ? ' <hr>' : ''; ?>

											</div>
										</div>

										<p class="small<?= $lsi == 1 ? ' bg-info' : '' ?>">

										<?php

											# If today
											if (date('m/d/Y') == $draft_lead_status_added_3[$lsi]) {
												
												echo '</b>' . $draft_lead_status_added_2[$lsi] . '</b>';
											} else {

												echo ' on <b>' . $draft_lead_status_added_1[$lsi] . '</b> at <b>' . $draft_lead_status_added_2[$lsi] . '</b>';
											}
											
											# Status
											if ($draft_lead_status_status[$lsi] == 1) { ?>
												
												<b class="text-danger">No answer</b> <?php

											} elseif ($draft_lead_status_status[$lsi] == 2) { ?>
												
												<b class="text-warning">On hold</b> <?php

											} elseif ($draft_lead_status_status[$lsi] == 3) { ?>
												
												<b class="text-muted">Declined</b> <?php

											} elseif ($draft_lead_status_status[$lsi] == 4) { ?>
												
												<b class="text-success">Accepted</b> <?php
											}

											# Added by
											echo " | Dispatcher: ";
											echo $user_list_id_name[$draft_lead_status_user_id[$lsi]] . ' ' . $user_list_id_last_name[$draft_lead_status_user_id[$lsi]];

											# Status auto update to declined in 30 minutes (only if active status is 2 (on hold))
											if ($draft_lead_status_status[$lsi] == 2 && $lsi == 1 && !$draft_accepted) { ?>

												 | 

												<span class="text-danger">
													 <i class="fa fa-warning"></i> 
													 Status auto-updates to declined at 

													<b>
														
														<?= date("G:i", strtotime("$draft_lead_status_added_2[$lsi] +30 minutes")) ?>
													</b>
												 <?php
											}
											?>
										</p> <?php

										# Notes
										if ($draft_lead_status_note[$lsi]) { ?>
											
											<p class="small bg-<?= $i == 1 ? 'primary' : 'warning' ?>"><?= $draft_lead_status_note[$lsi] ?></p> <?php
										}

										echo '<hr>';

										$lsi++;
									}
								} else {

									echo 'There are no leads for this draft.';
								}

								# Hide when put_on_hold or decline actions are called
								if (
									(isset($_GET['put_on_hold']) && ($_GET['put_on_hold'] != $draft_lead_id[$i]))
									|| (isset($_GET['decline']) && ($_GET['decline'] != $draft_lead_id[$i]))
								) {
								 	
								 	# Hide lead
								 	echo '</div>';
								}

							}
						} else {

							# No leads
							echo '<p class="text-center red"> <i class="fa fa-warning"></i>There are no leads to show.</p>';
						}
						?>
					</div>
				</div>
		</div>
	</div>
</div>

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
			