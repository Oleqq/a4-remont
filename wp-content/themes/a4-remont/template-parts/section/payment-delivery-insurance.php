<?php
/**
 * Payment and guarantees insurance section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/payment-delivery/payment-delivery-insurance.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title = (string) get_sub_field( 'title' );
$text  = (string) get_sub_field( 'text' );
$image = get_sub_field( 'image' );

if ( '' === trim( $title ) && '' === trim( $text ) && empty( $image ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$image_alt  = (string) get_sub_field( 'image_alt' );

if ( '' === $image_alt && is_array( $image ) && ! empty( $image['alt'] ) ) {
	$image_alt = (string) $image['alt'];
}

if ( '' === $image_alt ) {
	$image_alt = 'Страховка в подарок';
}
?>
<section class="payment-delivery-insurance"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="payment-delivery-insurance__container _container">
		<article class="payment-delivery-insurance__card">
			<div class="payment-delivery-insurance__content">
				<?php if ( $title ) : ?>
					<h2 class="payment-delivery-insurance__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<div class="payment-delivery-insurance__text"><?php echo wpautop( wp_kses_post( $text ) ); ?></div>
				<?php endif; ?>
			</div>

			<?php if ( $image ) : ?>
				<?php echo a4_remont_get_acf_image_html( $image, 'large', array( 'class' => 'payment-delivery-insurance__image', 'loading' => 'lazy', 'alt' => $image_alt, 'title' => $image_alt ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php endif; ?>
		</article>
	</div>
</section>
