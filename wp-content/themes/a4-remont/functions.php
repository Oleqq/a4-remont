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
	'/inc/slugs.php',
	'/inc/media.php',
	'/inc/enqueue.php',
	'/inc/content-types.php',
	'/inc/static-content.php',
	'/inc/section-helpers.php',
	'/inc/admin-docs.php',
	'/inc/header-builder.php',
	'/inc/footer-builder.php',
	'/inc/popups.php',
	'/inc/acf.php',
	'/inc/acf-header.php',
	'/inc/acf-popups.php',
	'/inc/acf-feedback.php',
	'/inc/acf-about.php',
	'/inc/acf-faq-page.php',
	'/inc/acf-contacts-page.php',
	'/inc/acf-privacy-policy.php',
	'/inc/acf-payment-delivery.php',
	'/inc/acf-news.php',
	'/inc/acf-reviews.php',
	'/inc/acf-services.php',
	'/inc/acf-works.php',
	'/inc/acf-news-single.php',
	'/inc/acf-work-single.php',
	'/inc/acf-service-single.php',
);

foreach ( $a4_remont_includes as $a4_remont_file ) {
	$a4_remont_path = get_template_directory() . $a4_remont_file;

	if ( file_exists( $a4_remont_path ) ) {
		require_once $a4_remont_path;
	}
}
