<?php
/**
 * Service promo section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/services/service-promo.html' );
	return;
}

$title      = (string) get_sub_field( 'title' );
$text       = (string) get_sub_field( 'text' );
$cta_button = get_sub_field( 'cta_button' );
$image      = get_sub_field( 'image' );
$has_cta_button = a4_remont_has_sub_field_action_button( 'cta_button', $cta_button );

if ( '' === trim( $title ) && '' === trim( $text ) && ! $has_cta_button && empty( $image ) ) {
	a4_remont_render_static_markup( 'section/services/service-promo.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$brand_text = (string) get_sub_field( 'brand_text' );
$brand_image = get_sub_field( 'brand_image' );
?>
<section class="service-promo"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-promo__container _container">
		<article class="service-promo__card">
			<div class="service-promo__grid">
				<div class="service-promo__content">
					<?php if ( $title ) : ?>
						<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( $text ) : ?>
						<p class="section__subtitle"><?php echo nl2br( esc_html( $text ) ); ?></p>
					<?php endif; ?>

					<div class="service-promo__actions">
						<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'service-promo__btn btn btn--primary' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

						<?php if ( $brand_image || $brand_text ) : ?>
							<p class="service-promo__brand">
								<?php if ( $brand_image ) : ?>
									<?php echo a4_remont_get_acf_image_html( $brand_image, 'large', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php else : ?>
									<?php echo esc_html( $brand_text ); ?>
								<?php endif; ?>
							</p>
						<?php endif; ?>
					</div>
				</div>

				<?php if ( $image ) : ?>
					<div class="service-promo__media">
						<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'service-promo__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</div>
		</article>
	</div>
</section>
