<?php
/**
 * ACF integration helpers.
 *
 * @package a4-remont
 */

/**
 * Save local ACF JSON inside the theme.
 *
 * @param string $path Default save path.
 * @return string
 */
function a4_remont_acf_json_save_path( $path ) {
	unset( $path );

	return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'a4_remont_acf_json_save_path' );

/**
 * Load local ACF JSON from the theme.
 *
 * @param array<int, string> $paths Default load paths.
 * @return array<int, string>
 */
function a4_remont_acf_json_load_paths( $paths ) {
	$paths[] = get_stylesheet_directory() . '/acf-json';

	return array_unique( $paths );
}
add_filter( 'acf/settings/load_json', 'a4_remont_acf_json_load_paths' );

/**
 * Render page sections from an ACF Flexible Content field.
 *
 * Each layout should have a matching file in template-parts/section/{layout-slug}.php.
 *
 * @param string $field_name Flexible Content field name.
 * @return bool
 */
function a4_remont_render_flexible_sections( $field_name = 'page_sections' ) {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( $field_name ) ) {
		return false;
	}

	while ( have_rows( $field_name ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( '' === $layout ) {
			continue;
		}

		$template_slug = 'template-parts/section/' . sanitize_title( $layout );

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug );
		}
	}

	return true;
}
