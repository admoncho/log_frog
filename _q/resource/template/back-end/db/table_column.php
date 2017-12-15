<?php

# This connection's table columns
${$this_file_name . '_column'} = DB::getInstance()->query("SELECT COLUMN_NAME, DATA_TYPE, COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . Config::get('mysql/db') . "' && TABLE_NAME = '" . $this_file_name . "' ORDER BY ORDINAL_POSITION ASC");
${$this_file_name . '_column_array'} = ${$this_file_name . '_column'}->results();
${$this_file_name . '_column_count'} = ${$this_file_name . '_column'}->count();
$i = 1;

# Iterate through columns
foreach (${$this_file_name . '_column'}->results() as ${$this_file_name . '_data'}) {

	$COLUMN_NAME[$i] = ${$this_file_name . '_data'}->COLUMN_NAME;
	$DATA_TYPE[$i] = ${$this_file_name . '_data'}->DATA_TYPE;
	$COLUMN_COMMENT[$i] = ${$this_file_name . '_data'}->COLUMN_COMMENT;
	$i++;
}
