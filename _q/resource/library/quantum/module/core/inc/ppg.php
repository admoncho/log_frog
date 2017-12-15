<?php
	
# Include whats_this_ppg
include(LIBRARY_PATH . "/quantum/module/core/inc/whats_this_ppg.php");
?>

<form action="" method="post">
	
	<div class="form-group">
		
		<label>How to title</label>
   	<input name="title" class="form-control" type="text" placeholder="Be as specific as possible, special characters are allowed">
	</div>

	<div class="form-group">

   	<button class="btn btn-link" type="submit"><i class="fa fa-save"></i> Save</button>
  	<button type="button" class="btn btn-link pull-right" data-dismiss="modal">Close</button>
	</div>

	<input type="hidden" name="_controller_ppg" value="add">
  <input type="hidden" name="token" value="<?= $csrfToken ?>">
</form>