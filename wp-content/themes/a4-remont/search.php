<?php
/**
 * Search results template.
 *
 * @package a4-remont
 */

get_header();
?>

<header class="page-header">
	<h1 class="page-title">
		<?php
		printf(
			/* translators: %s: search query. */
			esc_html__( 'Search results for: %s', 'a4-remont' ),
			'<span>' . esc_html( get_search_query() ) . '</span>'
		);
		?>
	</h1>
</header>

<?php if ( have_posts() ) : ?>
	<?php
	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', get_post_type() );
	endwhile;

	the_posts_navigation();
	?>
<?php else : ?>
	<?php get_template_part( 'template-parts/content', 'none' ); ?>
<?php endif; ?>

<?php
get_footer();
