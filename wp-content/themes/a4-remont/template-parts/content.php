<?php
/**
 * Generic content template part.
 *
 * @package a4-remont
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry-card' ); ?>>
	<header class="entry-header">
		<?php if ( is_singular() ) : ?>
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php else : ?>
			<?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
		<?php endif; ?>

		<?php if ( 'post' === get_post_type() ) : ?>
			<p class="entry-meta"><?php echo esc_html( get_the_date() ); ?></p>
		<?php endif; ?>
	</header>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumbnail">
			<?php the_post_thumbnail( 'large' ); ?>
		</div>
	<?php endif; ?>

	<div class="entry-content">
		<?php if ( is_singular() ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>
	</div>
</article>
