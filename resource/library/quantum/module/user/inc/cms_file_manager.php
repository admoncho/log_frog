<?php
session_start();
ob_start();
?>
<?php

### File Manager Documentation ###

# File manager for the CMS module

# - It is necessary to document file data in the db, to make this have more functionality.

# DB

# TABLE: file_manager
# - id, name, added, user_id

# Files

# - /resource/library/quantum/module/cms/inc/file_manager.php
# - /resource/library/quantum/module/cms/inc/db/file_manager.php
# - /resource/library/quantum/module/cms/controller/file_manager.php

### EO File Manager Documentation ###

# Include db_data/file_manager
include($module_directory . "/db/cms_file_manager.php"); ?>

<div class="main-box clearfix" id="file-manager">
	<header class="main-box-header clearfix">
		<h2 class="pull-left">
			<?= $cms_language[8] ?>
		</h2>
		<div class="icon-box pull-right">
			<a data-toggle="tooltip" data-placement="top" title="<?= $cms_language[9] ?>" href="?section_id=<?= $_GET['section_id'] ?>" class="btn pull-left"><i class="fa fa-sign-out"></i></a>
		</div>
	</header>
	
	<div class="main-box-body clearfix">

	<?php

	# No images warning
	echo !$cms_file_manager_count ? '<div class="alert alert-danger fade in">
																<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
																<i class="fa fa-times-circle fa-fw fa-lg"></i>
																' . $cms_language[10] . '
															</div>' : '' ;

	# If image_id
	if (isset($_GET['image_id'])) {
		$img_path = getimagesize($img_layout_directory . '/section/' . $_GET['section_id'] . '/' . $_GET['file_name']);
		$extension = image_type_to_extension($img_path[2]);
		$extension == '.jpg' ? $accept_string = 'image/jpeg' : '';
		$extension == '.png' ? $accept_string = 'image/png' : ''; ?>

		<div class="row">
			<div class="col-sm-12 col-md-3 text-center">
				<img class="img-thumbnail img-responsive" src="<?= $_SESSION["href_location"] ?>img/layout/section/<?= $_GET['section_id'] ?>/<?= $_GET['file_name'] . '?=' . date('Gis') ?>">
			</div>
			<div class="col-sm-12 col-md-9">
				<p><b>Name: </b><?= $_GET['file_name'] ?></p>
				<p><b>URL: </b><?= 'http://' . $_SERVER['HTTP_HOST'] . '/img/layout/section/' . $_GET['section_id'] . '/' . $_GET['file_name'] ?></p>
				<p><b>Width: </b><?= $img_path[0] . 'px' ?></p>
				<p><b>Height: </b><?= $img_path[1] . 'px' ?></p>
				<p>
					<b>Size: </b><?= number_format(filesize($img_layout_directory . '/section/' . $_GET['section_id'] . '/' . $_GET['file_name']) / 1000, 2) . ' MB' ?>

					<?php
					if (!isset($_GET['replace'])) { ?>

						<a class="btn btn-danger pull-right" href="?section_id=<?= $_GET['section_id'] ?>&edit_image=1&image_id=<?= $_GET['image_id'] ?>&file_name=<?= $_GET['file_name'] ?>&order_by=<?= $_GET['order_by'] ?>&replace=1"><?= $cms_language[11] ?></a> <?php
					}
					?>

				</p>
			</div>

			<?php
			# Show replace image form
			if (isset($_GET['replace'])) { ?>
						
				<div class="col-sm-12 col-md-12">
					
					<div class="alert alert-info">
						<i class="fa fa-info-circle fa-fw fa-lg"></i>
						<strong><?= $cms_language[12] ?>!</strong> <?= $cms_language[13] ?>
					</div>

					<form action="" method="post" enctype="multipart/form-data">
						<label>Choose your image</label>
			      <div class="input-group form-group">
			        <input style="display:inline; margin-right: 5px;" type="file" name="image" accept="<?= $accept_string ?>" class="btn btn-default">
							<button type="submit" class="btn btn-primary"><?= $core_language[17] ?></button>
							<a style="margin: 0 5px;" class="red btn-btn-link" href="?section_id=<?= $_GET['section_id'] ?>&edit_image=1&image_id=<?= $_GET['image_id'] ?>&order_by=<?= $_GET['order_by'] ?>"><?= $core_language[18] ?></a>
			      </div>
				    <input type="hidden" name="file_name" value="<?= $_GET['file_name'] ?>">
				    <input type="hidden" name="_controller_cms_file_manager" value="file_name">
				    <input type="hidden" name="token" value="<?= $csrfToken ?>">
			  	</form>
			  </div> <?php
			} ?>

		</div><hr> <?
	}

	if ($cms_file_manager_count) { ?>
		
		<div class="row">
			<div class="col-sm-12 col-md-2">
				<form action="" method="get">
					<input type="hidden" name="section" value="<?= $_GET['section_id'] ?>">
					<input type="hidden" name="edit_image" value="1">
					<?= isset($_GET['image_id']) ? '<input type="hidden" name="image_id" value="' . $_GET['image_id'] . '">' : '' ?>
					<select name="order_by" class="form-control" onchange="this.form.submit()">
						<option value="name"<?= isset($_GET['order_by']) && $_GET['order_by'] == 'name' ? ' selected' : '' ?>><?= $core_language[87] ?> <?= $core_language[9] ?></option>
						<option value="size"<?= isset($_GET['order_by']) && $_GET['order_by'] == 'size' ? ' selected' : '' ?>><?= $core_language[87] ?> <?= $core_language[88] ?></option>
						<option value="added"<?= isset($_GET['order_by']) && $_GET['order_by'] == 'added' ? ' selected' : '' ?>><?= $core_language[87] ?> <?= $core_language[89] ?></option>
					</select>
				</form>
			</div>

			<div class="col-sm-12 col-md-12"> <?php

				# For each file
				for ($i = 1; $i <= $cms_file_manager_count ; $i++) { ?>
					
					<div class="pull-left" style="margin: 10px 5px;">
						<a href="?section_id=<?= $_GET['section_id'] ?>&edit_image=1&image_id=<?= $cms_file_manager_id[$i] ?>&file_name=<?= $cms_file_manager_name[$i] ?>&order_by=name">
							<img class="img-thumbnail" style="height: 110px;" src="<?= $_SESSION["href_location"] ?>img/layout/section/<?= $_GET['section_id'] ?>/<?= $cms_file_manager_name[$i] . '?r=' . date('Gis') ?>">
						</a>
					</div> <?php
				} ?>
			</div>
		</div> <?php
	} ?>
	</div>
</div>