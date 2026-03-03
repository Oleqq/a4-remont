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
	$font_stylesheet        = 'https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap';
	$swiper_stylesheet_path = '/css/vendor/swiper-bundle.css';
	$theme_stylesheet_path  = '/css/style.css';
	$swiper_script_path     = '/js/vendor/swiper-bundle.js';
	$theme_script_path      = '/js/script.js';

	wp_enqueue_style( 'a4-remont-theme', get_stylesheet_uri(), array(), A4_REMONT_THEME_VERSION );
	wp_enqueue_style( 'a4-remont-fonts', $font_stylesheet, array(), null );

	if ( file_exists( get_theme_file_path( $swiper_stylesheet_path ) ) ) {
		wp_enqueue_style(
			'a4-remont-swiper',
			get_theme_file_uri( $swiper_stylesheet_path ),
			array( 'a4-remont-theme', 'a4-remont-fonts' ),
			a4_remont_asset_version( $swiper_stylesheet_path )
		);
	}

	if ( file_exists( get_theme_file_path( $theme_stylesheet_path ) ) ) {
		wp_enqueue_style(
			'a4-remont-static',
			get_theme_file_uri( $theme_stylesheet_path ),
			array( 'a4-remont-swiper' ),
			a4_remont_asset_version( $theme_stylesheet_path )
		);
	}

	if ( file_exists( get_theme_file_path( $swiper_script_path ) ) ) {
		wp_enqueue_script(
			'a4-remont-swiper',
			get_theme_file_uri( $swiper_script_path ),
			array(),
			a4_remont_asset_version( $swiper_script_path ),
			true
		);
	}

	if ( file_exists( get_theme_file_path( $theme_script_path ) ) ) {
		wp_enqueue_script(
			'a4-remont-static-app',
			get_theme_file_uri( $theme_script_path ),
			array( 'a4-remont-swiper' ),
			a4_remont_asset_version( $theme_script_path ),
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'a4_remont_enqueue_assets' );

/**
 * Mark the static runtime bundle as an ES module.
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Script handle.
 * @param string $src    Script source URL.
 * @return string
 */
function a4_remont_filter_script_loader_tag( $tag, $handle, $src ) {
	if ( 'a4-remont-static-app' !== $handle ) {
		return $tag;
	}

	return sprintf(
		'<script type="module" src="%1$s" id="%2$s-js"></script>' . "\n",
		esc_url( $src ),
		esc_attr( $handle )
	);
}
add_filter( 'script_loader_tag', 'a4_remont_filter_script_loader_tag', 10, 3 );
