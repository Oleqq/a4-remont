<?php
/**
 * Theme header.
 *
 * @package a4-remont
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'a4-remont' ); ?></a>

	<?php
	if ( function_exists( 'a4_remont_render_site_header' ) ) {
		a4_remont_render_site_header();
	}
	?>

	<main id="primary" class="main site-main">
