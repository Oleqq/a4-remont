<?php
/**
 * Main index template.
 *
 * @package a4-remont
 */

get_header();
?>

<?php if ( have_posts() ) : ?>
	<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
	<?php endif; ?>

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
