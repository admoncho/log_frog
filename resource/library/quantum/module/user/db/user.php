<?php

# item clause
isset($_GET['user_id']) ? $clause = " WHERE id = " . $_GET['user_id'] : $clause = " ORDER BY name ASC";

# Clause specific table connection
$user_list = DB::getInstance()->query("SELECT * FROM user" . $clause);
$user_list_count = $user_list->count();
$i = 1;

# Iterate through table items
foreach ($user_list->results() as $user_list_data) {
	
	$user_list_id[$i] = $user_list_data->id;
	$user_list_user_group[$i] = $user_list_data->user_group;
	$user_list_status[$i] = $user_list_data->status; // 1 active 2 deleted
	$user_list_email[$i] = $user_list_data->email;
	$user_list_name[$i] = html_entity_decode($user_list_data->name);
	$user_list_last_name[$i] = html_entity_decode($user_list_data->last_name);
	$user_list_dob[$i] = date('m/d/Y', strtotime($user_list_data->dob));
	$user_list_added[$i] = date('m/d/Y', strtotime($user_list_data->added));

	# Check if user is online
	$is_online = DB::getInstance()->query("SELECT * FROM user_session WHERE id = " . $user_list_id[$i]);
	$is_online_count[$i] = $is_online->count();
	
	# Check if user has avatar
	file_exists($img_content_directory . 'user/avatar/' . $user_list_id[$i] . '.jpg') ? $has_avatar[$i] = 1 : '';

	$i++;

	$user_list_id_name[$user_list_data->id] = html_entity_decode($user_list_data->name);
	$user_list_id_last_name[$user_list_data->id] = html_entity_decode($user_list_data->last_name);
	$user_list_id_email[$user_list_data->id] = $user_list_data->email;
	$user_list_id_dob[$user_list_data->id] = date('m/d/Y', strtotime($user_list_data->dob));
}
