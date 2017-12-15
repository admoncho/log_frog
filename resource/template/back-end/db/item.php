<?php

### ABOUT THIS FILE ###

# This file makes a connection to the instance being called, returns all items 
# and passes them through all columns in table to see DATA_TYPE and apply any 
# formatting required.

### eo ABOUT THIS FILE ###

# Clause specific table connection
${$this_file_name} = DB::getInstance()->query("SELECT * FROM " . $this_file_name . $clause);
${$this_file_name . '_array'} = ${$this_file_name}->results();
${$this_file_name . '_count'} = ${$this_file_name}->count();
$i = 1;

# Iterate through table items
foreach (${$this_file_name}->results() as ${$this_file_name . '_data'}) {
	
	# Iterate through table columns to apply special formatting depending on $DATA_TYPE
	for ($column_i = 1; $column_i <= ${$this_file_name . '_column_count'} ; $column_i++) { 
		
		if ($DATA_TYPE[$column_i] == 'int') {
			
			${$this_file_name . '_' . $COLUMN_NAME[$column_i]}[$i] = ${$this_file_name . '_data'}->$COLUMN_NAME[$column_i];

		} elseif ($DATA_TYPE[$column_i] == 'varchar') {
			
			${$this_file_name . '_' . $COLUMN_NAME[$column_i]}[$i] = html_entity_decode(${$this_file_name . '_data'}->$COLUMN_NAME[$column_i]);
		} elseif ($DATA_TYPE[$column_i] == 'timestamp') {
			
			${$this_file_name . '_' . $COLUMN_NAME[$column_i]}[$i] = date('M d, Y', strtotime(${$this_file_name . '_data'}->$COLUMN_NAME[$column_i]));
		}
	}

	$i++;
}
