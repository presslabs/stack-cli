<?php
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode,
    WP_CLI\Process;

function invoke_proc( $proc, $mode ) {
	$map = array(
		'run' => 'run_check',
		'try' => 'run'
	);
	$method = $map[ $mode ];
	return $proc->$method();
}

$steps->When( '/^I (run|try) `([^`]+)`$/',
	function ( $world, $mode, $cmd ) {
		$cmd = $world->replace_variables( $cmd );
		$world->result = invoke_proc( $world->proc( $cmd ), $mode );
	}
);
