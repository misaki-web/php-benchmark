#!/usr/bin/php
<?php

# PHP-benchmark: simple benchmark script to compare PHP code.
# Run `benchmark.php -h` for details.
# URL: https://github.com/misaki-web/php-benchmark

################################################################################
# SETTINGS
################################################################################

# Rename the file "benchmark-tests.template.php" to "benchmark-tests.php"
# and set the tests to benchmark.

################################################################################
# ARGUMENTS
################################################################################

$a = getopt('f:hi:');

if (isset($a['h'])) {
	usage();

	exit(0);
}

################################################################################
# FUNCTIONS
################################################################################

function print_result($label, $time, $result, $label_length = 0, $reference = PHP_INT_MAX) {
	if ($result !== '') {
		$result = " ($result)";
	}
	
	$s_label = '%' . ($label_length > 0 ? "-$label_length" : '') . 's';
	$diff = $time > $reference ? $time - $reference : 0;
	$diff_displayed = '';
	
	if ($diff > 0 && $reference > 0) {
		$diff_displayed = sprintf(' (+%.4f) (+%.2f%%)', $diff, ($diff / $reference) * 100);
	}
	
	printf("{$s_label} %.4f%s%s\n", "$label:", $time, $diff_displayed, $result);
}

# To start the timer:
#     t();
# 
# To end the timer and display the result of the current test:
#     t('test label');
# 
# To display all results:
#     t('all');
function t($label = '', $result = '') {
	static $t1;
	static $times = [];
	static $results = [];

	# Test starting
	if ($label === '') {
		$t1 = microtime(1);
	}

	# Test ending
	else if ($label !== 'all') {
		$times[$label] = microtime(1) - $t1;
		$results[$label] = $result;

		print_result($label, $times[$label], $results[$label]);
	}

	# Display all results
	else {
		asort($times, SORT_NUMERIC);
		
		$reference = null;
		$label_length = max(array_map('strlen', array_keys($times))) + 1;
		$display_results = (count(array_unique($results, SORT_REGULAR)) === 1) ? false : true;
		$separator = str_repeat('-', 50) . "\n";
		
		echo "\n$separator";

		foreach ($times as $test_label => $test_time) {
			if ($reference === null) {
				$reference = $test_time;
			}
			
			print_result($test_label, $test_time, $display_results ? $results[$test_label] : '', $label_length, $reference);
		}

		echo "$separator\n";

		if ($display_results) {
			echo "WARNING: Not all results are the same.\n\n";
		}
	}
}

function usage() {
	echo <<<HERE
		
		benchmark.php [-f TESTS_FILE.php] [-h] [-i NUMBER_OF_ITERATIONS]
		
		  -f: The file containing tests to benchmark. If empty, the script checks if
		      the file "benchmark-tests.php" exists.
		  -h: Display help and exit.
		  -i: Number of iterations for each test. It must be greater than 0. It can
		      also be specified in the tests file. If empty, the default value is 1.
		
		
		HERE;
}

################################################################################
# INCLUDE TESTS
################################################################################

$tests_file = '';

if (!empty($a['f']) && file_exists($a['f'])) {
	$tests_file = $a['f'];
} else if (file_exists(__DIR__ . '/benchmark-tests.php')) {
	$tests_file = __DIR__ . '/benchmark-tests.php';
} else if (file_exists(__DIR__ . '/benchmark-tests.template.php')) {
	$tests_file = __DIR__ . '/benchmark-tests.template.php';
}

if (empty($tests_file)) {
	echo "No benchmark tests specified.\n";
	
	usage();

	exit(1);
}

include $tests_file;

################################################################################
# TESTS
################################################################################

if (!empty($a['i'])) {
	$nb_iterations = $a['i'];
}

if (!isset($nb_iterations) || $nb_iterations < 1) {
	$nb_iterations = 1;
}

$php_functions = get_defined_functions();
$benchmark_functions = [];

if (!empty($php_functions['user'])) {
	$benchmark_functions = array_filter($php_functions['user'], function ($f) {
		return strpos($f, 'b_') === 0;
	});
}

$nb_iterations_formatted = number_format($nb_iterations);
$tests_file_name = basename($tests_file);

echo <<< HERE
	
	Running PHP-benchmark:
	
	  - Number of iterations: $nb_iterations_formatted
	  - Tests file: $tests_file_name
	
	
	HERE;

foreach ($benchmark_functions as $b) {
	t();

	for ($test_no = 1; $test_no <= $nb_iterations; $test_no++) {
		$result = $b();
	}

	t(preg_replace('/^b_/', '', $b), $result);
}

t('all');
