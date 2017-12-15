<?php 
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# This file name
$this_file_name = 'loader.php';
$this_file_name_underscore = 'loader.php';

# Include module notification file
include $module_directory . 'inc/notification.php';

# Notification
include(TEMPLATE_PATH . "/back-end/notification.php");

?>

<div class="row">
	<div class="col-sm-12 col-md-12">

		<?php 

		# Hide for external users
		if ($user->data()->user_group != 4) {
			
			# Draft loads 
	  	if (($draft_list_count > $draft_item_loaded_count) || $_GET['new_draft']) { ?>

  			<div class="panel panel-default<?= $_POST['add_load'] ? ' hidden' : '' ?>">
				  
				  <div class="panel-heading">
				  	
				  	<?= $_GET['new_draft'] ? 'Add new draft' : 'Draft Loads' ?>

				  	<form action="" method="post" class="pull-right<?= $_GET['new_draft'] ? ' hidden' : '' ?>">
				  		
				  		<label>

					      <input name="show_all_drafts" type="checkbox" onclick="this.form.submit();"<?= $_POST['show_all_drafts'] ? ' CHECKED' : '' ?>> 
					      Show all drafts
					    </label>
				  	</form>
				  </div>

				  <div class="panel-body<?= $_GET['new_draft'] ? '' : ' hidden' ?>">
				  	
				  	<div class="row">

				  		<form action="" method="post">

				  			<div class="col-sm-12 col-md-4">
					  			
					  			<div class="form-group" style="margin: 3px 0;">

							 			<input 
							 				type="number" 
							 				min="0" 
							 				class="form-control" 
							 				name="initial_rate" 
							 				placeholder="Rate ($)"
							 				<?= Input::get('initial_rate') ? 'value="' . Input::get('initial_rate') . '"' : '' ?>>
							 		</div>

							 		<div class="form-group" style="margin: 3px 0;">

							 			<input 
							 				type="number" 
							 				min="0" 
							 				class="form-control" 
							 				name="deadhead" 
							 				placeholder="Deadhead"
							 				<?= Input::get('deadhead') ? 'value="' . Input::get('deadhead') . '"' : '' ?>>
							 		</div>

							 		<div class="form-group" style="margin: 3px 0;">

							 			<input 
							 				type="number" 
							 				min="1" 
							 				class="form-control" 
							 				name="weight" 
							 				placeholder="Weight"
							 				<?= Input::get('weight') ? 'value="' . Input::get('weight') . '"' : '' ?>>
							 		</div>

					 				<div class="form-group" style="margin: 3px 0;">

							 			<input 
							 				type="text" 
							 				class="form-control" 
							 				name="broker_name_number" 
							 				placeholder="Broker name &amp; number"
							 				<?= Input::get('broker_name_number') ? 'value="' . Input::get('broker_name_number') . '"' : '' ?>>
							 		</div>

							 		<div class="form-group" style="margin: 3px 0;">

							 			<input 
							 				type="text" 
							 				class="form-control" 
							 				name="note" 
							 				placeholder="Notes"
							 				<?= Input::get('note') ? 'value="' . Input::get('note') . '"' : '' ?>>
							 		</div>
					  		</div>

					  		<div class="col-sm-12 col-md-4">

					  			<div class="form-group" style="margin: 3px 0;">

							 			<input 
							 				type="text" 
							 				class="form-control" 
							 				name="draft_pick_city" 
							 				placeholder="Pick up city"
							 				<?= Input::get('draft_pick_city') ? 'value="' . Input::get('draft_pick_city') . '"' : '' ?>>
							 		</div>

							 		<div class="form-group" style="margin: 3px 0;">

			              <select name="draft_pick_state_id" style="width:100%" id="pick_state_selector">

			              	<option value="">Pick up state</option>
			                
			                <?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>

			                  <option 
			                  	value="<?= $i ?>"
			                  	<?= Input::get('draft_pick_state_id') == $i ? ' selected' : '' ?>>

			                  	<?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?>
			                  </option> <?php
			                } ?>
			              </select>
			            </div>

			            <div class="row">
			            	
			            	<div class="form-group col-sm-12 col-md-12">

					            <div class="input-group">
					              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					              
					              <input
					              	name="pick_date"
					              	type="text"
					              	class="form-control"
					              	id="datepickerPickDate"
					              	<?= Input::get('pick_date') ? 'value="' . Input::get('pick_date') . '"' : '' ?>>
					            </div>
					            <span class="help-block">* Date - format mm-dd-yyyy</span>
					          </div>
			            </div>
					  		</div>

					  		<div class="col-sm-12 col-md-4">

					  			<div class="form-group" style="margin: 3px 0;">

							 			<input 
							 				type="text" 
							 				class="form-control" 
							 				name="draft_drop_city" 
							 				placeholder="Destination city"
							 				<?= Input::get('draft_drop_city') ? 'value="' . Input::get('draft_drop_city') . '"' : '' ?>>
							 		</div>

							 		<div class="form-group" style="margin: 3px 0;">

			              <select name="draft_drop_state_id" style="width:100%" id="drop_state_selector">

			              	<option value="">Destination state</option>
			                
			                <?php for ($i = 1; $i <= $geo_state_count ; $i++) { ?>
			                
			                  <option 
			                  	value="<?= $i ?>"
			                  	<?= Input::get('draft_drop_state_id') == $i ? ' selected' : '' ?>>

			                  	<?= $state_abbr[$i] . ' [' . $state_name[$i] . ']' ?>
			                  </option> <?php
			                } ?>
			              </select>
			            </div>

							  	<div class="row">
							  		
							  		<div class="form-group col-sm-12 col-md-12">

					            <div class="input-group">
					              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					              
					              <input 
					              	name="drop_date" 
					              	type="text" 
					              	class="form-control" 
					              	id="datepickerDropDate"
					              	<?= Input::get('drop_date') ? 'value="' . Input::get('drop_date') . '"' : '' ?>>
					            </div>
					            <span class="help-block">* Date - format mm-dd-yyyy</span>
					          </div>
							  	</div>
					  		</div>

					  		<div class="col-sm-12 col-md-12">

							 		<div class="form-group text-center" style="margin: 3px 0;">

							 			<hr>
							 			<button class="btn btn-link" type="submit"> <i class="fa fa-save"></i> Save</button> 
							 			<a class="btn btn-link red" href="<?= $_SESSION['href_location'] ?>dashboard/loader">Cancel</a>
							 		</div>
							 	</div>

						 		<input type="hidden" name="_controller_draft_load" value="add_draft">
								<input type="hidden" name="token" value="<?= $csrfToken ?>">
						 	</form>
				  	</div>
				  </div>

				  <div class="table-responsive<?= $_GET['new_draft'] ? ' hidden' : '' ?>">
						  
					  <table class="table table-hover">
					  	
					  	<thead>
								<tr>
									<th class="hidden"></th>
									<th<?= $_POST['show_all_drafts'] ? ' class="hidden"' : '' ?>><small>Driver</small></th>
									<th><small>Rate</small></th>
									<th><small>Broker name &amp; number</small></th>
									<th><small>Deadhead</small></th>
									<th><small>Weight</small></th>
									<th><small>Origin</small></th>
									<th><small>Destination</small></th>
									<th><small>Origin</small></th>
									<th><small>Destination</small></th>
									<th<?= $_POST['show_all_drafts'] ? ' class="hidden"' : '' ?>><small>Status</small></th>
									<th><small>Dispatcher</small></th>
									<th></th>
								</tr>
							</thead>

							<tbody>

								<?php

								for ($i = 1; $i <= $draft_list_count; $i++) {

									# Checkpoint data
							  	$draft_checkpoint = DB::getInstance()->query("
									SELECT * FROM loader_load_draft_checkpoint 
									WHERE load_draft_id = " . $draft_item_id[$i] . "
									ORDER BY added ASC");

							  	$draft_checkpoint_count = $draft_checkpoint->count();
									
									if ($draft_checkpoint_count) {

										$dci = 1;
										
										foreach ($draft_checkpoint->results() as $draft_checkpoint_data) {
											
											$draft_checkpoint_date[$dci] = date('m/d/y', strtotime($draft_checkpoint_data->date_time));
											$draft_checkpoint_city[$dci] = html_entity_decode($draft_checkpoint_data->city);
											$draft_checkpoint_state_id[$dci] = $draft_checkpoint_data->state_id;

											$dci++;
										}
									} ?>
									
									<tr class="<?= !$draft_item_id[$i] || $draft_item_loaded[$i] == 1 ? ' hidden' : '';?><?= $draft_has_rate_con[$i] ? ' danger' : '' ?>">
										
										<td class="hidden">
										<!-- This hidden td is to kill different formatting on the first item -->
										</td>

										<td<?= $_POST['show_all_drafts'] ? ' class="hidden"' : '' ?>>

											<small>

												<?= $user_list_id_name[$draft_list_driver_id[$i]] . ' ' . $user_list_id_last_name[$draft_list_driver_id[$i]] ?>
											</small>
										</td>

										<td class="text-right">

											<small>$ <?= $draft_item_final_rate[$i] ?></small>
										</td>

										<td>

											<small><?= $draft_item_broker_name_number[$i] ?></small>
										</td>

										<td class="text-right">

											<small><?= $draft_item_deadhead[$i] ?></small>
										</td>

										<td class="text-right">

											<small><?= $draft_item_weight[$i] ?></small>
										</td>

										<td>

											<small><?= $draft_checkpoint_date[1] ?></small>
										</td>

										<td>

											<small><?= $draft_checkpoint_date[$draft_checkpoint_count] ?></small>
										</td>

										<td>

											<small><?= $draft_checkpoint_city[1] . ', ' . $state_abbr[$draft_checkpoint_state_id[1]] ?></small>
										</td>

										<td>

											<small><?= $draft_checkpoint_city[$draft_checkpoint_count] . ', ' . $state_abbr[$draft_checkpoint_state_id[$draft_checkpoint_count]] ?></small>
										</td>

										<td<?= $_POST['show_all_drafts'] ? ' class="hidden"' : '' ?>>

											<small class="text-success">Accepted</small>
										</td>

										<td>
											
											<small><?= $user_list_id_name[$draft_item_user_id[$i]] . ' ' . $user_list_id_last_name[$draft_item_user_id[$i]] ?></small>
										</td>

										<td class="text-right">

											<small>
													
												<a 
													class="btn btn-link<?= $draft_has_rate_con[$i] ? ' green' : ' red' ?><?= $_GET['draft_rate_con'] ? ' hidden' : '' ?>" 
													href="?draft_rate_con=<?= $draft_item_id[$i] ?>">
													
													<span data-toggle="tooltip" data-placement="top" title="<?= $draft_has_rate_con[$i] ? 'View ' : 'Add' ?> ratecon">
														
														Ratecon
													</span>
												</a>

												<form 
													action="" 
													method="post"
													enctype="multipart/form-data" 
													<?= $_GET['draft_rate_con'] && !$draft_has_rate_con[$i] ? '' : ' class="hidden"' ?> >
													
													<a class="btn btn-link" id="file" style="cursor: pointer;" data-toggle="tooltip" data-placement="top" title="Choose file">
														
														<span class="fa fa-upload"></span>
													</a>

													<button class="btn btn-link" type="submit"> Upload ratecon</button>
													<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/" class="btn btn-link red">Cancel</a>
													<input id="file-prompt" type="file" name="file" style="display: none;" accept="application/pdf"/>
													
													<input type="hidden" name="_controller_draft_ratecon" value="add">
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form>

												<a 
													class="btn btn-link<?= $_GET['draft_rate_con'] ? ' hidden' : '' ?>" 
													href="draft-load?draft_id=<?= $draft_item_id[$i] ?>">
													
													<i data-toggle="tooltip" data-placement="top" title="Edit draft" class="fa fa-pencil"></i>
												</a>

												<form action="" method="post" class="pull-right<?= $_GET['draft_rate_con'] ? ' hidden' : '' ?>">

													<button class="btn btn-link red">
														
														<i data-toggle="tooltip" data-placement="top" title="Delete draft, this can't be undone" class="fa fa-trash-o"></i>
													</button>
													
													<input type="hidden" name="draft_id" value="<?= $draft_item_id[$i] ?>">
													<input type="hidden" name="_controller_draft_load" value="delete_draft">
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form>

												<a 
													class="btn btn-link red pull-left<?= $_GET['draft_rate_con'] && $draft_has_rate_con[1] ? '' : ' hidden' ?>" 
													href="<?= $_SESSION['href_location'] ?>dashboard/loader/">
													
													<i data-toggle="tooltip" data-placement="top" title="Close file" class="fa fa-window-close"></i>
												</a>

												<form action="" method="post" class="pull-left<?= $_GET['draft_rate_con'] && $draft_has_rate_con[1] ? '' : ' hidden' ?>">

													<button class="btn btn-link red">
														
														<i data-toggle="tooltip" data-placement="top" title="Delete file" class="fa fa-trash-o"></i>
													</button>
													
													<input type="hidden" name="_controller_draft_ratecon" value="delete_file">
													<input type="hidden" name="token" value="<?= $csrfToken ?>">
												</form>
											</small>

											<form 
												action="" 
												method="post" 
												class="form-inline pull-right<?= $_POST['show_all_drafts'] || ($_GET['draft_rate_con']) ? ' hidden' : '' ?>">

									      <div class="form-group">

									        <button 
									        	data-toggle="tooltip" 
									        	data-placement="top" 
									        	title="Add as load" 
									        	class="btn btn-link" 
									        	type="submit">
									        	
									        	<i class="fa fa-plus"></i>
									        	<sup>L</sup>
									        </button>
									      </div>
									      <input type="hidden" name="add_load" value="1">
									      <input type="hidden" name="driver_id" value="<?= $draft_list_driver_id[$i] ?>">
									      <input type="hidden" name="broker_name_number" value="<?= $draft_item_broker_name_number[$i] ?>">
									      <input type="hidden" name="broker_email" value="<?= $draft_item_broker_email[$i] ?>">
									      <input type="hidden" name="note" value="<?= $draft_item_note[$i] ?>">
									      <input type="hidden" name="line_haul" value="<?= $draft_item_final_rate_1[$i] ?>">
									      <input type="hidden" name="deadhead" value="<?= $draft_item_deadhead[$i] ?>">
									      <input type="hidden" name="weight" value="<?= $draft_item_weight[$i] ?>">
									      <input type="hidden" name="user_id" value="<?= $draft_item_user_id[$i] ?>">
									      <input type="hidden" name="draft_id" value="<?= $draft_item_id[$i] ?>">
									    </form>
										</td>
									</tr> <?php
								} ?>

					  </table>
					</div>

					<?php

					if ($_GET['draft_rate_con'] && $draft_has_rate_con[1]) { ?>
						
						<embed 
							src="<?= $_SESSION["href_location"] ?>files/draft-rate-confirmation/<?= $_GET['draft_rate_con'] ?>.pdf?r=<?= date('Gis') ?>" 
							width="100%" 
							height="1000px"> <?php
					}
					?>
				</div> <?php
			}
	  }
	  ?>

	  <div class="col-sm-12 col-md-12<?= $_POST['add_load'] ? ' hidden' : '' ?>">
	  	
	  	<?php

	  	# Filter div

	  	# Clear filters if any are set
	    if (isset($_POST['limit']) || isset($_POST['load_status']) || isset($_POST['broker_id']) || isset($_POST['load_number']) || isset($_POST['driver_id'])) { ?>
	    	<div class="filter-block" style="position: absolute; top: 21px; right: 525px; z-index: 9;">
	    		<a href="<?= $_SESSION['href_location'] ?>dashboard/loader/" class="btn btn-link red">Clear filters</a>
	    	</div> <?php
	    }

		  # Hide for external users
		  if ($user->data()->user_group != 4) { ?>

		  	<div class="filter-block" style="position: absolute; top: 21px; right:280px; z-index: 9;">
					<!-- status form -->
			    <form action="" method="post" class="form-inline pull-right">
			      <div class="form-group<?= isset($_POST['load_status']) ? ($_POST['load_status'] == 1 ? ' has-error' : '') : '' ?>">
			        <select name="load_status" class="form-control" onchange="this.form.submit()">
			          <option value="9">Active</option>
			          <option value="1"<?= isset($_POST['load_status']) ? ($_POST['load_status'] == 1 ? ' selected' : '') : '' ?>>Deleted</option>
			        </select>
			      </div>
			      <?= isset($_POST['limit']) ? '<input type="hidden" name="limit" value="' . $_POST['limit'] . '">' : '' ?>
			      <?= isset($_POST['broker_id']) ? '<input type="hidden" name="broker_id" value="' . $_POST['broker_id'] . '">' : '' ?>
			      <?= isset($_POST['driver_id']) ? '<input type="hidden" name="driver_id" value="' . $_POST['driver_id'] . '">' : '' ?>
			    </form>
			  </div>
		  	
		  	<div class="filter-block" style="position: absolute; top: 21px; left: 180px; z-index: 9;">
			    <!-- limit form -->
			    <form action="" method="post">
			      <div class="form-group form-group-select2" style="width: 100%;">
							
							<select name="driver_id" onchange="this.form.submit()" style="min-width: 200px;" id="driver_id_select2">
								<option>Driver</option>
								
								<?php
			          for ($i = 1; $i <= $driver_list_count ; $i++) { ?>
			            
			            <option value="<?= $driver_list_user_id[$i] ?>"<?= $driver_list_user_id[$i] == $_POST['driver_id'] ? ' selected' : '' ?>><?= $user_list_id_name[$driver_list_user_id[$i]] . ' ' . $user_list_id_last_name[$driver_list_user_id[$i]] ?></option> <?php
			          }
			          ?>
							</select>

						</div>
			      <?= isset($_POST['load_status']) ? '<input type="hidden" name="load_status" value="' . $_POST['load_status'] . '">' : '' ?>
			      <?= isset($_POST['broker_id']) ? '<input type="hidden" name="broker_id" value="' . $_POST['broker_id'] . '">' : '' ?>
			      <?= isset($_POST['driver_id']) ? '<input type="hidden" name="driver_id" value="' . $_POST['driver_id'] . '">' : '' ?>
			    </form>
			  </div> <?php
		  }
		  ?>

	    <div class="filter-block" style="position: absolute; top: 21px; right: 400px; z-index: 9;">
		    <!-- limit form -->
		    <form action="" method="post" class="form-inline pull-right">
		      <div class="form-group" style="margin-right: 4px;">
		        <input 
		          type="number" name="limit" min="1" max="<?= $load_ALT_count ?>" 
		          value="<?= isset($_POST['limit']) ? $_POST['limit'] : 250; ?>" 
		          data-toggle="tooltip" data-placement="top" title="Items displayed - MAX: <?= $load_ALT_count ?>" 
		          class="red form-control"
		        >
		      </div>
		      <?= isset($_POST['load_status']) ? '<input type="hidden" name="load_status" value="' . $_POST['load_status'] . '">' : '' ?>
		      <?= isset($_POST['broker_id']) ? '<input type="hidden" name="broker_id" value="' . $_POST['broker_id'] . '">' : '' ?>
		      <?= isset($_POST['driver_id']) ? '<input type="hidden" name="driver_id" value="' . $_POST['driver_id'] . '">' : '' ?>
		    </form>
		  </div>

		  <?php
		  # Draft loads add link
	  	# Hide for external users
			if ($user->data()->user_group != 4) { ?>

	  		<!-- add draft load link -->
	  		<div class="filter-block" style="position: absolute; top: 24px; right: 540px; z-index: 9;">

	  			<span data-toggle="tooltip" data-placement="top" title="New draft">
	
						<a href="?new_draft=1" class="fa fa-plus btn btn-link">
						 	
						<sup>D</sup>
						</a>

					</span>
			  </div>

			  <div class="filter-block" style="position: absolute; top: 21px; right: 500px; z-index: 9;">
			    
			    <!-- add load form -->
			    <form action="" method="post" class="form-inline pull-right">
			      <div class="form-group">

			        <button data-toggle="tooltip" data-placement="top" title="Add new load" class="btn btn-link" type="submit">
			        	
			        	<i class="fa fa-plus"></i>
			        	<sup>L</sup>
			        </button>
			      </div>
			      <input type="hidden" name="add_load" value="1">
			    </form>
			  </div> <?php 
		  }
	  	?>
	  </div>

		<div class="main-box no-header clearfix">
			<div class="main-box-body clearfix">

				<?php
				# Add load
				if (isset($_POST['add_load'])) { ?>

					<form action="" method="post" enctype="multipart/form-data">
						<?php 

						# This form has been changed to post to GET first so we can load the list of equipment available
						# for this driver, this first GET form passes the driver_id to the URL ?>
						<div class="row">
							<div class="col-sm-12 col-md-3">
								<div class="form-group form-group-select2" style="width: 100%;">
									<label class="control-label"><span class="red">* </span>Driver</label>
									
									<?php if ($_POST['driver_id']) {
										# Display text field with readonly driver name and last name ?>
										<input type="text" class="form-control" value="<?= $user_list_id_name[$_POST['driver_id']] . ' ' . $user_list_id_last_name[$_POST['driver_id']] ?>" readonly> <?php
									} else {
										# Display driver_id select if we are still on GET form ?>
										<select name="driver_id" onchange="this.form.submit()" style="width: 100%;" id="driver_id_select">
											<option></option>
											
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
										</select> <?php
									} ?>

								</div>
							</div>

							<?php 
							if ($_POST['driver_id']) { 
								# Display on POST form only ?>

								<div class="form-group form-group-select2 col-sm-12 col-md-3">
									<label class="control-label"><span class="red">* </span>Broker company</label>
									<select name="broker_id" style="width:100%" id="broker_id_select">
										<option></option>
										
										<?php for ($i = 1; $i <= $broker_count ; $i++) { ?>
											<option value="<?= $broker_data_id[$i] ?>"><?= $broker_company_name[$i] ?></option> <?php
										} ?>

									</select>
								</div>

								<div class="form-group col-sm-12 col-md-3">
									<label class="control-label"><span class="red">* </span>Broker's name &amp; number</label>
									<input 
										name="broker_name_number" 
										type="text" 
										class="form-control"
										<?= $_POST['broker_name_number'] ? ' value="' . $_POST['broker_name_number'] . '"' : '' ?>>
								</div>
								<div class="form-group col-sm-12 col-md-3">
									<label class="control-label"><span class="red">* </span>Broker's email</label>
									<input 
										name="broker_email" 
										type="text" 
										class="form-control"
										<?= $_POST['broker_email'] ? ' value="' . $_POST['broker_email'] . '"' : '' ?>>
								</div> <?php
							} ?>
						</div>
						
						<?php 
						if ($_POST['driver_id']) { 
							
							# Display on POST form only ?>
							<div class="row">

								<div class="form-group col-sm-12 col-md-3">
									<label class="control-label"><span class="red">* </span>Line haul</label>
									<input 
										name="line_haul" 
										type="number" 
										min="0" 
										class="form-control"
										<?= $_POST['line_haul'] ? ' value="' . $_POST['line_haul'] . '"' : '' ?>>
								</div>

								<div class="form-group col-sm-12 col-md-2">
									<label class="control-label text-right"><span class="red">* </span>Weight</label>
									<input name="weight" type="number" class="form-control" min="1">
								</div>

								<div class="form-group col-sm-12 col-md-2">
									<label class="control-label text-right"><span class="red">* </span>Miles</label>
									<input name="miles" type="number" class="form-control" min="1" step="0.1">
								</div>

								<div class="form-group col-sm-12 col-md-2">
									<label class="control-label text-right">Deadhead</label>
									<input 
										name="deadhead" 
										type="number" 
										class="form-control" 
										min="0" 
										step="0.1"
										<?= $_POST['deadhead'] ? ' value="' . $_POST['deadhead'] . '"' : '' ?>>
								</div>

								<div class="form-group col-sm-12 col-md-2">
									<label class="control-label text-right">Weight</label>
									<input 
										name="weight" 
										type="number" 
										class="form-control" 
										min="1" 
										<?= $_POST['weight'] ? ' value="' . $_POST['weight'] . '"' : '' ?>>
								</div>

								<!-- <div class="form-group col-sm-12 col-md-3">
									<label class="control-label text-right"><span class="red">* </span>Avg. diesel price</label>
									<input name="avg_diesel_price" type="number" class="form-control" min="1" max="9.99" step="0.01">
								</div> -->

								<div class="form-group col-sm-12 col-md-3">
									<label class="control-label text-right"><span class="red">* </span>Load #</label>
									<input name="load_number" type="text" class="form-control">
								</div>

								<div class="form-group col-sm-12 col-md-3">
									<label class="control-label text-right">Reference</label>
									<input name="reference" type="text" class="form-control">
								</div>

								<div class="form-group col-sm-12 col-md-3">

									<label class="control-label text-right">Notes</label>

									<input 
										name="notes" 
										type="text" 
										class="form-control"
										<?= $_POST['note'] ? ' value="' . $_POST['note'] . '"' : '' ?>>
								</div>

								<div class="form-group col-sm-12 col-md-3">
									<label class="control-label text-right">Commodity</label>
									<input name="commodity" type="text" class="form-control">
								</div>

								<div class="form-group col-sm-12 col-md-3">
									<label class="control-label text-right"><span class="red">* </span>Dispatcher</label>
									<select class="form-control" name="user_id">
										<option></option>
										<?php for ($i=1; $i <= $user_list_count ; $i++) { 

											if ($user_list_user_group[$i] == 1 || $user_list_user_group[$i] == 3) {
												
												# Show dispatcher's list ?>
											<option 
												value="<?= $user_list_id[$i] ?>"
												<?= $user_list_id[$i] == $user->data()->id ? ' selected' : '' ?>>

												<?= $user_list_name[$i] . ' ' . $user_list_last_name[$i] ?>
											</option> <?php
											}
										} ?>
									</select>
								</div>

								<!-- <div class="form-group col-sm-12 col-md-3">
									<label class="control-label text-right">Equipment</label>
									<select class="form-control" name="equipment[]" multiple>
										<?php /*for ($i=1; $i <= $loader_driver_equipment_assoc_count ; $i++) { 
											# Show driver equipment list ?>
											<option value="<?= $driver_equipment_assoc_equipment_id[$i] ?>"><?= $driver_equipment_name_did[$driver_equipment_assoc_equipment_id[$i]] . ' [' . $driver_equipment_assoc_quantity[$i] . ' unit' . ($driver_equipment_assoc_quantity[$i] > 1 ? 's' : '') . ']' ?></option> <?php
										}*/ ?>
									<!-- </select>
								</div> -->

								<div 
									class="form-group col-sm-12 col-md-3
									<?= $_POST['draft_id'] && file_exists($draft_rate_con_path . $_POST['draft_id'] . '.pdf') ? ' hidden' : '' ?>">

									<label class="control-label text-right"><span class="red">* </span>Rate confirmation</label>
									<div class="input-group form-group">
										<input style="display:inline; margin-right: 5px;" type="file" name="file" accept="application/pdf" class="btn btn-default">
									</div>
								</div>

								<div class="form-group col-sm-12 col-md-12 text-right">
									<small class="red pull-left">* Required fields</small>
									<button type="submit" class="btn btn-primary">Add</button>
								</div>
							</div>
							<input type="hidden" name="driver_id" value="<?= $_POST['driver_id'] ?>">
							<input type="hidden" name="added_by" value="<?= $user->data()->id ?>">
							<input type="hidden" name="draft_id" value="<?= $_POST['draft_id'] ?>">
							<input type="hidden" name="_controller_loader" value="add_load">
							<input type="hidden" name="token" value="<?= $csrfToken ?>"> <?php
						} else {

							# Pass add_load value to keep form at screen ?>
							<input type="hidden" name="add_load" value="1"> <?php
						} ?>
					</form>

					<?php

					if ($_POST['draft_id'] && file_exists($draft_rate_con_path . $_POST['draft_id'] . '.pdf')) { ?>
						
						<embed 
							src="<?= $_SESSION["href_location"] ?>files/draft-rate-confirmation/<?= $_POST['draft_id'] ?>.pdf?r=<?= date('Gis') ?>" 
							width="100%" 
							height="1000px"> <?php
					}
				} else {
				  
					# Display table if there are results
					if ($load_count) { ?>
						
						<div class="table-responsive">
							
							<table id="<?= $module_name ?>-table" class="table" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th class="hidden"><span>HIDDEN</span></th>
										<th><span>Broker</span></th>
										<th><span>Load #</span></th>
										<th><span>Driver</span></th>
										<th><span>Rate <i class="fa fa-arrows-h"></i> Miles</span></th>
										<th><span>ORIGIN</span></th>
										<th><span>DESTINATION</span></th>
										<th><span>ORIGIN</span></th>
										<th><span>DESTINATION</span></th>
										<th><span>Dispatcher</span></th>
										<th></th>
									</tr>
								</thead>
								<tbody>

									<?php
									for ($i = 1; $i <= $load_count ; $i++) { ?>
										
										<tr

											<?php 
											if ($load_billing_status[$i] == 0) {
												
												if ($bol_exists[$i]) {
													
													echo ' style="background: #af0b01; color: #fff;"'; // Danger, has bol but hasn't been charged
												}
											} elseif ($load_billing_status[$i] == 1) {
												
												echo ' style="background: #9c28b1; color: #fff;"'; // Info, has been charged
											} elseif ($load_billing_status[$i] == 2) {
												
												echo ' style="background: #e0b50a;"'; // Warning, has been charged with wrong data
											} elseif ($load_billing_status[$i] == 3) {
												
												echo ' style="background: #8bc34a;"'; // Success, charged and closed
											} ?>

										>
											
											<td class="hidden">
												<?= $load_last_checkpoint_date_1[$i] ?>
											</td>

											<td>
												<small>
													<?= ucwords($broker_id_company_name[$load_broker_id[$i]]) ?>
												</small>
											</td>

											<td>
												<small>
													<?= $load_load_number[$i] ?>
												</small>
											</td>
											<td>
												<small data-toggle="tooltip" data-placement="top" title="<?= $user_phone_number_user_id_phone_number[$entry_driver_id[$i]] ?>">
													<?= $user_list_id_name[$entry_driver_id[$i]] . ' ' . $user_list_id_last_name[$entry_driver_id[$i]]  ?>
												</small>
											</td>
											<td>
												<small data-toggle="tooltip" data-placement="top" title="Per loaded mile: $<?= number_format(str_replace(',', '', $load_line_haul[$i]) / $load_miles[$i], 2) ?>">
													<?= '$ ' . $load_line_haul_format_1[$i] .' <i class="fa fa-arrows-h"></i> ' . $load_miles[$i] ?>
												</small>
											</td>
											<td>
												<small>
													<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_first_checkpoint_time[$i] ?>"> <?= $load_first_checkpoint_date[$i] ?></span>
												</small>
											</td>
											<td>
												<small>
													<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_last_checkpoint_time[$i] ?>"> <?= $load_last_checkpoint_date[$i] ?></span>
												</small>
											</td>
											<td>
												<small>
													<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_first_checkpoint_zip_code[$i] . ' / dh ' . $load_deadhead[$i] ?>"> <?= ucwords(strtolower($load_first_checkpoint_city[$i])) . ', ' . $state_abbr[$load_first_checkpoint_state_id[$i]] ?></span>
												</small>
											</td>
											<td>
												<small>
													<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $load_last_checkpoint_zip_code[$i] ?>"> <?= ucwords(strtolower($load_last_checkpoint_city[$i])) . ', ' . $state_abbr[$load_last_checkpoint_state_id[$i]] ?></span>
												</small>
											</td>
											<td>
												<small>
													<span data-toggle="tooltip" data-placement="top" title="" data-original-title="<?= $user_list_id_name[$load_user_id[$i]] . ' ' . $user_list_id_last_name[$load_user_id[$i]] ?>"> <?= $user_list_id_name[$load_user_id[$i]] . ' ' . $user_list_id_last_name[$load_user_id[$i]] ?></span>
												</small>
											</td>
											<td>
												<a data-toggle="tooltip" data-placement="top" title="View load" href="<?= $_SESSION['href_location'] ?>dashboard/loader/load?load_id=<?= $load_load_id[$i] ?>">
													<i class="fa fa-cube red"></i>
												</a>
											</td>
										</tr> <?php
									}
									?>

								</tbody>
							</table>
						</div> <?php
					} else {

						include COMPONENT_PATH . 'alert_simple_danger.txt';
						 echo 'There are no results to show under those filters!</div>';
					}
				}
				?>

			</div>
		</div>

	</div>
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
