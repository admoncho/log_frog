<?php 

# Show "what's this" note if user has no articles
if (!$ppg_count) { ?>
	
	<div class="col-sm-12 col-md-12">
		
		<div class="main-box no-header clearfix">
			
			<div class="main-box-body clearfix">
				
				<p><span class="label label-danger"><i class="fa fa-info"></i> - What's this?</span></p>
				<p>You can add a new <b>PPG</b> item using the form below. All you need is a title, you will then be redirected to its new URL so that you can add the PDF file for it.</p>
			</div>
		</div>

	</div> <?php
} elseif ($ppg_count && !file_exists($ppg_file_name_alt)) { ?>
	
	<div class="col-sm-12 col-md-12">
		
		<div class="main-box no-header clearfix">
			
			<div class="main-box-body clearfix">
				
				<p><span class="label label-danger"><i class="fa fa-info"></i> - What's this?</span></p>
				<p>This <b>PPG</b> is missing PDF files. Please use the form below to add the first one.</p>
			</div>
		</div>

	</div> <?php
}
