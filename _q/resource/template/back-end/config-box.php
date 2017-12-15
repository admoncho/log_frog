<?php

# Only ceo
if ($user->hasPermission('ceo')) { ?>
	
	<div id="config-tool" class="closed">
		<a id="config-tool-cog">
			<i class="fa fa-cog"></i>
		</a>
		
		<div id="config-tool-options">
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<h4>New language items</h4><br>
					<form action="" method="post">
						<div class="form-group">
							<input type="text" name="item_en" class="form-control" placeholder="English">
						</div>
						<div class="form-group">
							<input type="text" name="item_es" class="form-control" placeholder="Espa&ntilde;ol">
						</div>
						<button type="submit" class="btn btn-primary">Add</button>
						<input type="hidden" name="_controller_add_language_items" value="1">
	          <input type="hidden" name="token" value="<?= $csrfToken ?>">
					</form>
				</div>
			</div>
		</div>
	</div> <?php
}
