<?php
/**
 * Front page template.
 *
 * @package a4-remont
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	if ( function_exists( 'a4_remont_render_homepage_content' ) ) {
		a4_remont_render_homepage_content();
	}
endwhile;
?>

<?php
get_footer();
