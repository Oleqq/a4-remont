<?php
/**
 * Payment and guarantees guarantee section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/payment-delivery/payment-delivery-guarantee.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title         = (string) get_sub_field( 'section_title' );
$lead          = (string) get_sub_field( 'lead' );
$desktop_image = get_sub_field( 'image_desktop' );
$mobile_image  = get_sub_field( 'image_mobile' );

if ( '' === trim( $title ) && '' === trim( $lead ) && empty( $desktop_image ) && empty( $mobile_image ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id  = sanitize_title( (string) get_sub_field( 'section_id' ) );
$desktop_url = a4_remont_get_acf_image_url( $desktop_image, 'full' );
$mobile_url  = a4_remont_get_acf_image_url( $mobile_image, 'full' );
$image_alt   = (string) get_sub_field( 'image_alt' );

if ( '' === $desktop_url ) {
	$desktop_url = $mobile_url;
}

if ( '' === $image_alt && is_array( $desktop_image ) && ! empty( $desktop_image['alt'] ) ) {
	$image_alt = (string) $desktop_image['alt'];
}

if ( '' === $image_alt ) {
	$image_alt = 'Гарантии на ремонт';
}
?>
<section class="payment-delivery-guarantee"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="payment-delivery-guarantee__container _container">
		<div class="payment-delivery-guarantee__media">
			<?php if ( $desktop_url ) : ?>
				<picture>
					<?php if ( $mobile_url ) : ?>
						<source media="(max-width: 467px)" srcset="<?php echo esc_url( $mobile_url ); ?>">
					<?php endif; ?>
					<img class="payment-delivery-guarantee__image" src="<?php echo esc_url( $desktop_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" title="<?php echo esc_attr( $image_alt ); ?>" loading="lazy">
				</picture>
			<?php endif; ?>

			<?php if ( $title || $lead ) : ?>
				<article class="payment-delivery-guarantee__card">
					<?php if ( $title ) : ?>
						<h2 class="section__title payment-delivery-guarantee__title"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( $lead ) : ?>
						<div class="section__subtitle payment-delivery-guarantee__lead"><?php echo wpautop( wp_kses_post( $lead ) ); ?></div>
					<?php endif; ?>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>
