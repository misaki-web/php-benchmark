<?php

# Variables
###########

$nb_iterations = 10000000;
$data = [
	'item 1,item 2,item 3,item 4,',
	',',
];

# Tests to benchmark
####################

# Each test to benchmark must follow this format:

#     function b_TEST_LABEL() {
#     	global $data; # If access to "$data" is needed.
#     	...PHP code to test...
#     	...PHP code to test...
#     	return ...PHP code result...;
#     }

# Example:

function b_substr() {
	global $data;
	return substr($data[0], 0, (strrpos($data[0], $data[1])));
}

function b_preg_replace() {
	global $data;
	return preg_replace('/' . preg_quote($data[1]) . '$/', '', $data[0]);
}

function b_basename() {
	global $data;
	return basename($data[0], $data[1]);
}
