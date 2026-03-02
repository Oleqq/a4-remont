<?php
/**
 * Theme bootstrap.
 *
 * @package a4-remont
 */

if ( ! defined( 'A4_REMONT_THEME_VERSION' ) ) {
	define( 'A4_REMONT_THEME_VERSION', '0.1.0' );
}

$a4_remont_includes = array(
	'/inc/setup.php',
	'/inc/enqueue.php',
	'/inc/acf.php',
);

foreach ( $a4_remont_includes as $a4_remont_file ) {
	$a4_remont_path = get_template_directory() . $a4_remont_file;

	if ( file_exists( $a4_remont_path ) ) {
		require_once $a4_remont_path;
	}
}
