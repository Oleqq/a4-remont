<?php
/**
 * Single post template.
 *
 * @package a4-remont
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	get_template_part( 'template-parts/content', get_post_type() );

	the_post_navigation(
		array(
			'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous', 'a4-remont' ) . '</span> <span class="nav-title">%title</span>',
			'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next', 'a4-remont' ) . '</span> <span class="nav-title">%title</span>',
		)
	);
endwhile;
?>

<?php
get_footer();
