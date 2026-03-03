<?php
/**
 * Single service template.
 *
 * @package a4-remont
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( function_exists( 'a4_remont_render_service_single_content' ) ) {
		a4_remont_render_service_single_content();
	}
endwhile;

get_footer();
