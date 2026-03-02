<?php
/**
 * Asset loading.
 *
 * @package a4-remont
 */

/**
 * Return a stable asset version based on filemtime.
 *
 * @param string $relative_path Theme-relative asset path with a leading slash.
 * @return string
 */
function a4_remont_asset_version( $relative_path ) {
	$absolute_path = get_theme_file_path( $relative_path );

	if ( file_exists( $absolute_path ) ) {
		return (string) filemtime( $absolute_path );
	}

	return A4_REMONT_THEME_VERSION;
}

/**
 * Enqueue frontend assets.
 *
 * @return void
 */
function a4_remont_enqueue_assets() {
	$stylesheet_relative_path = '/assets/css/app.css';
	$script_relative_path     = '/assets/js/app.js';

	wp_enqueue_style( 'a4-remont-theme', get_stylesheet_uri(), array(), A4_REMONT_THEME_VERSION );

	if ( file_exists( get_theme_file_path( $stylesheet_relative_path ) ) ) {
		wp_enqueue_style(
			'a4-remont-app',
			get_theme_file_uri( $stylesheet_relative_path ),
			array( 'a4-remont-theme' ),
			a4_remont_asset_version( $stylesheet_relative_path )
		);
	}

	if ( file_exists( get_theme_file_path( $script_relative_path ) ) ) {
		wp_enqueue_script(
			'a4-remont-app',
			get_theme_file_uri( $script_relative_path ),
			array(),
			a4_remont_asset_version( $script_relative_path ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'a4_remont_enqueue_assets' );
