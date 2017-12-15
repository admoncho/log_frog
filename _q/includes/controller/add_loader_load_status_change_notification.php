<?php

# If request comes through $_GET, update status only
if ($_GET['_hp_add_loader_load_status_change_notification']) {
	# Change checkpoint status
	$update = DB::getInstance()->query("UPDATE loader_checkpoint SET status = 1 WHERE checkpoint_id = " . $_GET['checkpoint_id']);
	$update->count() ? Session::flash('add_loader_load_status_change_notification', 'Checkpoint marked as complete.') . Redirect::to('view-load?load_id=' . $_GET['load_id']) : Session::flash('add_loader_load_status_change_notification_error', $_QC_language[16]) ;
} else {
	$validation = $validate->check($_POST, array(
		'to' => array('required' => true)
	));

	if($validation->passed()) {
		$to = Input::get('to');
		$to_items = explode(",", str_replace(' ', '', $to));
		$to_items_count = count($to_items);
		# Check for invalid emails on the $to_items array
		for ($i = 0; $i < $to_items_count ; $i++) {!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $to_items[$i], $matches) ? $items_error = 1 : '';}

		# cc is not required, an empty string throws an error, so validation runs only if input value is set
		if (Input::get('cc')) {
			$cc = Input::get('cc');
			$cc_items = explode(",", str_replace(' ', '', $cc));
			$cc_items_count = count($cc_items);
			# Check for invalid emails on the $cc_items array
			for ($i = 0; $i < $cc_items_count ; $i++) {!preg_match('/^[\w\-\.\+]+\@[a-zA-Z0-9\.\-]+\.[a-zA-z0-9]{2,4}$/', $cc_items[$i], $matches) ? $items_error = 1 : '';}
		}

		# Set from email address
		# If input "from" is not sent, use default no-reply address
		if (Input::get('from')) {
			$from = str_replace(' ', '', Input::get('from'));
		} else {
			$from = 'no-reply@' . $domain;
		}

		# Go on if there are no invalid emails
		if (!$items_error) {
			
			$add_loader_load_status_change_notification = DB::getInstance()->query("INSERT INTO quantum_mail (user_id) VALUES (" . $user->data()->user_id . ")");

			$last_added_id = DB::getInstance()->query("SELECT LAST_INSERT_ID() AS last_added_id FROM quantum_mail");
		    foreach ($last_added_id->results() as $id) {
		        $last_id = $id->last_added_id;
		    }

		    if ($add_loader_load_status_change_notification->count()) {
		    	# Add to items
		    	for ($i = 0; $i < $to_items_count ; $i++) {
		    		$add_to_items = DB::getInstance()->query("INSERT INTO quantum_mail_to (mail_id, item) VALUES (" . $last_id . ", '" . $to_items[$i] . "')");
		    	}

		    	# Add cc items
		    	for ($i = 0; $i < $cc_items_count ; $i++) {
		    		$add_cc_items = DB::getInstance()->query("INSERT INTO quantum_mail_cc (mail_id, item) VALUES (" . $last_id . ", '" . $cc_items[$i] . "')");
		    	}

		    	# Add from item
		    	$add_from_item = DB::getInstance()->query("INSERT INTO quantum_mail_from (mail_id, item) VALUES (" . $last_id . ", '" . $from . "')");

		    	# Add tag
		    	$add_tag_item = DB::getInstance()->query("INSERT INTO quantum_mail_tag (mail_id, item) VALUES (" . $last_id . ", 'loaderCheckpointStatusChangeNotification')");

		    	# Send mail
		    	$email_to = $to;

					$subject = Input::get('loader_status_change_notification_subject');

					$headers = "From: " . strip_tags($from) . "\r\n";
					$headers .= "Reply-To: ". strip_tags($from) . "\r\n";
					$headers .= "CC: " . $cc . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";

					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

					$message = '<html><body>';
					$message .= Input::get('loader_status_change_notification');
					$message .= '</body></html>';


					mail($email_to, $subject, $message, $headers);

					# Add note
					$insert = DB::getInstance()->query("INSERT INTO loader_load_note (load_id, note, important, type, user_id) VALUES (" . $_GET['load_id'] . ", '" . $subject . '<p>' . Input::get('loader_status_change_notification') . '</p>' . "', 0, 1, " . $user->data()->user_id . ")");
					# Change checkpoint status
					$update = DB::getInstance()->query("UPDATE loader_checkpoint SET status = 1 WHERE checkpoint_id = " . $_GET['checkpoint_id']);
					$update->count() ?  '' : Session::flash('add_loader_load_status_change_notification_error', $_QC_language[16]) ;
					# Flash and redirect outside as not always the status is updated (when resending notification for example).
					Session::flash('add_loader_load_status_change_notification', 'Email sent successfully, checkpoint marked as complete.');
					Redirect::to('view-load?load_id=' . $_GET['load_id']);
		    }
		} else {
			Session::flash('add_loader_load_status_change_notification_error', $_QC_language[16]);
		}
	} else {
		Session::flash('add_loader_load_status_change_notification_error', $_QC_language[16]);
	}
}
