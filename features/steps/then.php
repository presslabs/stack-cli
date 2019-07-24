<?php
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

$steps->Then( '/^(STDOUT|STDERR) should (be|contain|not contain):$/',
	function ( $world, $stream, $action, PyStringNode $expected ) {
		$stream = strtolower( $stream );
		$expected = $world->replace_variables( (string) $expected );
		checkString( $world->result->$stream, $expected, $action, $world->result );
	}
);

$steps->Then( '/^the (.+) (file|directory) should (exist|not exist|be:|contain:|not contain:)$/',
	function ( $world, $path, $type, $action, $expected = null ) {
		$path = $world->replace_variables( $path );
		// If it's a relative path, make it relative to the current test dir
		if ( '/' !== $path[0] )
			$path = $world->variables['RUN_DIR'] . "/$path";
		if ( 'file' == $type ) {
			$test = 'file_exists';
		} else if ( 'directory' == $type ) {
			$test = 'is_dir';
		}
		switch ( $action ) {
		case 'exist':
			if ( ! $test( $path ) ) {
				throw new Exception( "$path doesn't exist." );
			}
			break;
		case 'not exist':
			if ( $test( $path ) ) {
				throw new Exception( "$path exists." );
			}
			break;
		default:
			if ( ! $test( $path ) ) {
				throw new Exception( "$path doesn't exist." );
			}
			$action = substr( $action, 0, -1 );
			$expected = $world->replace_variables( (string) $expected );
			if ( 'file' == $type ) {
				$contents = file_get_contents( $path );
			} else if ( 'directory' == $type ) {
				$files = glob( rtrim( $path, '/' ) . '/*' );
				foreach( $files as &$file ) {
					$file = str_replace( $path . '/', '', $file );
				}
				$contents = implode( PHP_EOL, $files );
			}
			checkString( $contents, $expected, $action );
		}
	}
);
