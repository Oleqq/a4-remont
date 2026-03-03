<?php
/**
 * Theme setup.
 *
 * @package a4-remont
 */

/**
 * Register theme supports and menus.
 *
 * @return void
 */
function a4_remont_setup() {
	load_theme_textdomain( 'a4-remont', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/style.css' );

	add_theme_support(
		'html5',
		array(
			'search-form',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary menu', 'a4-remont' ),
			'footer'  => esc_html__( 'Footer menu', 'a4-remont' ),
		)
	);
}
add_action( 'after_setup_theme', 'a4_remont_setup' );

/**
 * Set the content width baseline.
 *
 * @return void
 */
function a4_remont_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'a4_remont_content_width', 1200 );
}
add_action( 'after_setup_theme', 'a4_remont_content_width', 0 );
