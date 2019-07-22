<?php

namespace Stack;

if ( ! class_exists( 'WP_CLI' ) ) {
	return;
}

\WP_CLI::add_command('stack', Command::class);
