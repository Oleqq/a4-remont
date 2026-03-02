<?php
/**
 * Page template.
 *
 * @package a4-remont
 */

get_header();
?>

<?php
while ( have_posts() ) :
	the_post();
	$has_sections = function_exists( 'a4_remont_render_flexible_sections' ) && a4_remont_render_flexible_sections();

	if ( ! $has_sections ) :
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-entry' ); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>

			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endif;
endwhile;
?>

<?php
get_footer();
