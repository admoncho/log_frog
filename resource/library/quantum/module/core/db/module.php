<?php

# Get module list (used postfix '_list' becuase there are variables called $module in environment config)
$module_list = DB::getInstance()->query("SELECT * FROM module ORDER BY id ASC");
$module_list_count = $module_list->count();
$i = 1;

if (isset($module_list_count)) {
	
	foreach ($module_list->results() as $module_list_data) {
		
		$module_list_id[$i] = $module_list_data->id;
		$module_list_name[$i] = html_entity_decode($module_list_data->name);
		$module_list_path[$i] = str_replace('core', '', html_entity_decode($module_list_data->name));
		$module_list_icon[$i] = $module_list_data->icon;
		$i++;
	}
}
