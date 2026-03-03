<?php
/**
 * Portfolio gallery section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/portfolio-gallery.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$gallery = get_sub_field( 'gallery' );

if ( empty( $gallery ) || ! is_array( $gallery ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$title      = (string) get_sub_field( 'section_title' );
$text       = (string) get_sub_field( 'section_text' );
$cta_button = get_sub_field( 'cta_button' );
$modifiers  = array( 'a', 'b', 'c', 'd', 'e', 'f', 'g' );
?>
<section class="portfolio-gallery"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="portfolio-gallery__container _container">
		<div class="portfolio-gallery__top">
			<div class="portfolio-gallery__intro">
				<?php if ( $title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>
			</div>
			<?php if ( $text ) : ?>
				<p class="section__subtitle portfolio-gallery__subtitle"><?php echo nl2br( esc_html( $text ) ); ?></p>
			<?php endif; ?>
		</div>
		<div class="portfolio-gallery__grid">
			<?php foreach ( array_slice( $gallery, 0, 7 ) as $index => $image ) : ?>
				<?php $modifier = isset( $modifiers[ $index ] ) ? $modifiers[ $index ] : 'a'; ?>
				<a class="portfolio-gallery__item portfolio-gallery__item--<?php echo esc_attr( $modifier ); ?>" href="<?php echo esc_url( is_array( $image ) && ! empty( $image['url'] ) ? $image['url'] : '#' ); ?>" aria-label="Open gallery image">
					<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endforeach; ?>
		</div>
		<div class="portfolio-gallery__bottom">
			<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey portfolio-gallery__cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
