<?php
/**
 * Empty-state template part.
 *
 * @package a4-remont
 */

?>

<section class="no-results not-found">
	<header class="page-header">
		<h1 class="page-title"><?php esc_html_e( 'Nothing found', 'a4-remont' ); ?></h1>
	</header>

	<div class="page-content">
		<?php if ( is_search() ) : ?>
			<p><?php esc_html_e( 'No results matched your search request.', 'a4-remont' ); ?></p>
			<?php get_search_form(); ?>
		<?php else : ?>
			<p><?php esc_html_e( 'There is no published content for this request yet.', 'a4-remont' ); ?></p>
		<?php endif; ?>
	</div>
</section>
