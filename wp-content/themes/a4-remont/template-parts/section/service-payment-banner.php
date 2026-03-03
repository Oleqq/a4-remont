<?php
/**
 * Single service payment banner section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/service-single/service-payment-banner.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$text_primary  = (string) get_sub_field( 'text_primary' );
$text_secondary = (string) get_sub_field( 'text_secondary' );
$cta_button    = get_sub_field( 'cta_button' );
$image         = get_sub_field( 'image' );
$has_cta_button = a4_remont_has_sub_field_action_button( 'cta_button', $cta_button );

if ( '' === trim( $section_title ) && '' === trim( $text_primary ) && '' === trim( $text_secondary ) && ! $has_cta_button && empty( $image ) ) {
	a4_remont_render_static_markup( 'section/service-single/service-payment-banner.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="service-payment-banner"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-payment-banner__container _container">
		<article class="service-payment-banner__card">
			<div class="service-payment-banner__grid">
				<div class="service-payment-banner__content">
					<?php if ( $section_title ) : ?>
						<h2 class="section__title service-payment-banner__title"><?php echo wp_kses_post( $section_title ); ?></h2>
					<?php endif; ?>

					<?php if ( $text_primary ) : ?>
						<p class="section__subtitle service-payment-banner__text"><?php echo nl2br( esc_html( $text_primary ) ); ?></p>
					<?php endif; ?>

					<?php if ( $text_secondary ) : ?>
						<p class="section__subtitle service-payment-banner__text"><?php echo nl2br( esc_html( $text_secondary ) ); ?></p>
					<?php endif; ?>

					<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--primary service-payment-banner__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>

				<?php if ( $image ) : ?>
					<div class="service-payment-banner__media">
						<?php echo a4_remont_get_acf_image_html( $image, 'large', array( 'class' => 'service-payment-banner__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</div>
		</article>
	</div>
</section>
