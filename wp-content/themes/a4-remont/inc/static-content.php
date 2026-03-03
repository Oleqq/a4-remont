<?php
/**
 * Static markup helpers.
 *
 * @package a4-remont
 */

/**
 * Rewrite project-local asset URLs inside imported static HTML.
 *
 * @param string $markup Raw HTML markup.
 * @return string
 */
function a4_remont_rewrite_static_asset_urls( $markup ) {
	$theme_uri    = untrailingslashit( get_template_directory_uri() );
	$replacements = array(
		'src="./images/' => 'src="' . $theme_uri . '/images/',
		"src='./images/" => "src='" . $theme_uri . '/images/',
		'src="images/'   => 'src="' . $theme_uri . '/images/',
		"src='images/"   => "src='" . $theme_uri . '/images/',
		'src="/images/'  => 'src="' . $theme_uri . '/images/',
		"src='/images/"  => "src='" . $theme_uri . '/images/',
		'src="./img/'    => 'src="' . $theme_uri . '/img/',
		"src='./img/"    => "src='" . $theme_uri . '/img/',
		'src="img/'      => 'src="' . $theme_uri . '/img/',
		"src='img/"      => "src='" . $theme_uri . '/img/',
		'src="/img/'     => 'src="' . $theme_uri . '/img/',
		"src='/img/"     => "src='" . $theme_uri . '/img/',
		'href="./css/'   => 'href="' . $theme_uri . '/css/',
		'href="css/'     => 'href="' . $theme_uri . '/css/',
		'href="/css/'    => 'href="' . $theme_uri . '/css/',
		'src="./js/'     => 'src="' . $theme_uri . '/js/',
		'src="js/'       => 'src="' . $theme_uri . '/js/',
		'src="/js/'      => 'src="' . $theme_uri . '/js/',
		'url("./images/' => 'url(' . $theme_uri . '/images/',
		'url(images/'    => 'url(' . $theme_uri . '/images/',
		'url("/images/'  => 'url(' . $theme_uri . '/images/',
		'url("./img/'    => 'url(' . $theme_uri . '/img/',
		'url(img/'       => 'url(' . $theme_uri . '/img/',
		'url("/img/'     => 'url(' . $theme_uri . '/img/',
	);

	return str_replace( array_keys( $replacements ), array_values( $replacements ), $markup );
}

/**
 * Get HTML markup from a static partial shipped inside the theme.
 *
 * @param string $relative_path Theme-relative path inside template-parts/static.
 * @return string
 */
function a4_remont_get_static_markup( $relative_path ) {
	$relative_path = ltrim( $relative_path, '/' );
	$absolute_path = get_theme_file_path( '/template-parts/static/' . $relative_path );

	if ( ! file_exists( $absolute_path ) ) {
		return '';
	}

	$markup = (string) file_get_contents( $absolute_path );

	if ( '' === $markup ) {
		return '';
	}

	return a4_remont_rewrite_static_asset_urls( $markup );
}

/**
 * Echo HTML markup from a static partial.
 *
 * @param string $relative_path Theme-relative path inside template-parts/static.
 * @return bool
 */
function a4_remont_render_static_markup( $relative_path ) {
	$markup = a4_remont_get_static_markup( $relative_path );

	if ( '' === $markup ) {
		return false;
	}

	echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	return true;
}
