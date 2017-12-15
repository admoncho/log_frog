<?php 
session_start();
ob_start();
# Redirect if no query string
if (!$_SERVER['QUERY_STRING']) { ?>
	<script type="text/javascript">
		window.location = "/dashboard/"
	</script> <?php
}

require $_SESSION['ProjectPath'] . '/resource/library/quantum/init.php';

# Include module notification file
include $module_directory . 'inc/notification.php';
?>

<div class="row">

	<div class="col-sm-12 col-md-12">

		<?php 

		if (file_exists($ppg_file_name_alt)) {
			
			# Display file ?>

			<embed src="<?= $_SESSION["href_location"] ?>files/ppg/<?= $_GET['file_name'] ?>.pdf?r=<?= date('Gis') ?>" width="100%" height="1000px"> <?php
		} else { ?>
			
			<div class="col-sm-12 col-sm-6 col-sm-offset-3">

				<?php
				
				# Include whats_this_ppg
				include(LIBRARY_PATH . "/quantum/module/core/inc/whats_this_ppg.php");
				?>

				<form action="" method="post" enctype="multipart/form-data">
									
					<div class="form-group">
						<label>Choose file</label>
			      <input type="file" name="file" accept="application/pdf" class="btn btn-default btn-block">
					</div>

					<div class="form-group">
		      	<button type="submit" class="btn btn-link"> <i class="fa fa-save"></i> <?= $core_language[17] ?></button>
		      </div>

		      <input type="hidden" name="_controller_ppg" value="add_pdf">
		      <input type="hidden" name="token" value="<?= $csrfToken ?>">
		    </form>
	    </div> <?php
		} ?>
	</div>
</div> 

<?php require TEMPLATE_PATH .'/back-end/bottom.php'; ?>
