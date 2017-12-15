<?php
session_start();
ob_start();
require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';
?>

<div class="row">

	<div class="col-sm-12 col-md-4">

		<?= $ppg_category_count ? '' : '<p><span class="red"><i class="fa fa-warning"></i> There are no PPG categories, add the first one below.</span></p>' ?>

		<form action="" method="post">
			
			<div class="row">
				
				<div class="form-group col-sm-8 col-md-8">

		      <input type="text" name="name" class="form-control" placeholder="New category">
				</div>

				<div class="form-group col-sm-4 col-md-4">
	      	<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> <?= $core_language[17] ?></button>
	      </div>
			</div>

      <input type="hidden" name="_controller_ppg_category" value="add">
      <input type="hidden" name="token" value="<?= $csrfToken ?>">
    </form>

		<table class="table table-striped table-bordered<?= $ppg_category_count ? '' : ' hidden' ?>" cellspacing="0" width="100%">

			<thead>
				<tr>
					<th>Category name</th>
				</tr>
			</thead>

			<tbody>

				<?php for ($i=1; $i <= $ppg_category_count ; $i++) { ?>

					<tr<?= isset($_GET['ppg_category_id']) ? ($_GET['ppg_category_id'] == $ppg_category_id[$i] ? '' : ' class="hidden"') : '' ?>>
						<td>

							<a href="ppg_category?ppg_category_id=<?= $ppg_category_id[$i] ?>"<?= isset($_GET['ppg_category_id']) ? ' class="hidden"' : '' ?>> 
								<i class="fa fa-pencil"></i>
							</a> 

							<?php

								if (isset($_GET['ppg_category_id'])) {?>

									<form action="" method="post">
			
										<div class="row">
											
											<div class="form-group col-sm-7 col-md-7">

									      <input type="text" name="name" class="form-control" placeholder="Category name" value="<?= $ppg_category_name[$i] ?>">
											</div>

											<div class="form-group col-sm-5 col-md-5">
								      	<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> <?= $core_language[17] ?></button>
								      	<a href="ppg_category" class="btn btn-link red"> Cancel</a>
								      </div>
										</div>

							      <input type="hidden" name="_controller_ppg_category" value="update">
							      <input type="hidden" name="token" value="<?= $csrfToken ?>">
							    </form> <?php
								} else {

									echo $ppg_category_name[$i];
								}
							?>
						</td>
					</tr> <?php
				} ?>
			</tbody>
		</table>

		<?php 

		/*if (file_exists($ppg_file_name_alt)) {
			
			# Display file ?>

			<embed src="/files/ppg/<?= $_GET['file_name'] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1000px"> <?php
		} else { ?>
			
			<div class="col-sm-12 col-sm-6 col-sm-offset-3">

				<?php
				
				# Include whats_this_ppg
				include(LIBRARY_PATH . "/quantum/module/core/inc/whats_this_ppg.php");
				?>
	    </div> <?php
		}*/ ?>
	</div>
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
