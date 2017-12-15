<?php

# Declare parameter for query
isset($_GET['user_id']) ? $params = " WHERE id = " . $_GET['user_id'] : $params = " ORDER BY id ASC";

# Get all users
$user_list = DB::getInstance()->query("SELECT * FROM user " . $params);

if ($_SERVER['REMOTE_ADDR'] == '201.191.198.116') {
	
	var_dump($user_list);
}
$user_list_count = $user_list->count();
$i = 1;

if ($user_list_count) {
	
	foreach ($user_list->results() as $user_list_data) {
		
		$user_id[$i] 				= $user_list_data->id;
		$user_group[$i] 		= $user_list_data->user_group;
		$user_status[$i] 		= $user_list_data->status; // 1 active 2 deleted
		$user_email[$i] 		= $user_list_data->email;
		$user_name[$i] 			= html_entity_decode($user_list_data->name);
		$user_last_name[$i] = html_entity_decode($user_list_data->last_name);
		$user_added[$i] 		= date('m/d/Y', strtotime($user_list_data->added));

		# Check if user is online
		$is_online = DB::getInstance()->query("SELECT * FROM user_session WHERE id = " . $user_id[$i]);
		$is_online_count[$i] = $is_online->count();

		# Check if user has avatar
		file_exists($img_content_directory . 'user/avatar/' . $user_id[$i] . '.jpg') ? $has_avatar[$i] = 1 : '';

		# Get user group name
		$user_group_name = DB::getInstance()->query("SELECT * FROM user_group WHERE group_id = " . $user_group[$i]);
		
		foreach ($user_group_name->results() as $user_group_name_data) {
			
			$group_name[$i] = $user_group_name_data->name;
		}

		# Increment counter
		$i++;
	}
}
