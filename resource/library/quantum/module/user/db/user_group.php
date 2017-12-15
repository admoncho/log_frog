<?php

# item clause
isset($_GET['user_group_id']) ? $clause = " WHERE group_id = " . $_GET['user_group_id'] : $clause = " ORDER BY name ASC";

# Clause specific table connection
$user_group = DB::getInstance()->query("SELECT * FROM user_group" . $clause);
$user_group_count = $user_group->count();
$i = 1;

# Iterate through table items
foreach ($user_group->results() as $user_group_data) {
	
	$user_group_group_id[$i] = $user_group_data->group_id;
	$user_group_name[$i] = $user_group_data->name;
	$user_group_permissions[$i] = $user_group_data->permissions; // 1 active 2 deleted
	$i++;

	$user_group_id_name[$user_group_data->group_id] = $user_group_data->name;
}
