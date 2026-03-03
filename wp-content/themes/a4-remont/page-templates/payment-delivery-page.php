<?php
/**
 * Template Name: Оплата и гарантии
 * Template Post Type: page
 *
 * @package a4-remont
 */

get_header();

while ( have_posts() ) :
	the_post();

	if ( function_exists( 'a4_remont_render_payment_delivery_page_content' ) ) {
		a4_remont_render_payment_delivery_page_content();
	}
endwhile;

get_footer();
