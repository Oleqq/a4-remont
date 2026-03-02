<?php
/**
 * Template for 404 pages.
 *
 * @package a4-remont
 */

get_header();
?>

<section class="page page-404">
	<header class="page__header">
		<h1 class="page__title"><?php esc_html_e( 'Page not found', 'a4-remont' ); ?></h1>
	</header>

	<div class="page__content">
		<p><?php esc_html_e( 'The requested page does not exist or has been moved.', 'a4-remont' ); ?></p>
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Return to the homepage', 'a4-remont' ); ?></a></p>
	</div>
</section>

<?php
get_footer();
