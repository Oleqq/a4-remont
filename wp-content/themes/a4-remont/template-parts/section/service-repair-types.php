<?php
/**
 * Single service repair types section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/service-single/service-repair-types.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$section_lead  = (string) get_sub_field( 'section_lead' );
$items         = get_sub_field( 'items' );

if ( '' === trim( $section_title ) && '' === trim( $section_lead ) && empty( $items ) ) {
	a4_remont_render_static_markup( 'section/service-single/service-repair-types.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$cta_button = get_sub_field( 'cta_button' );
?>
<section class="service-repair-types"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-repair-types__container _container">
		<div class="service-repair-types__top">
			<?php if ( $section_title ) : ?>
				<h2 class="section__title service-repair-types__title"><?php echo wp_kses_post( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_lead ) : ?>
				<p class="section__subtitle service-repair-types__lead"><?php echo nl2br( esc_html( $section_lead ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="service-repair-types__slider swiper" data-service-repair-slider="data-service-repair-slider">
				<div class="service-repair-types__track swiper-wrapper">
					<?php foreach ( (array) $items as $item ) : ?>
						<?php
						if ( ! is_array( $item ) ) {
							continue;
						}

						$item_title = ! empty( $item['item_title'] ) ? (string) $item['item_title'] : '';
						$item_text  = ! empty( $item['item_text'] ) ? (string) $item['item_text'] : '';
						$item_image = ! empty( $item['item_image'] ) ? $item['item_image'] : null;

						if ( '' === trim( $item_title ) && '' === trim( $item_text ) && empty( $item_image ) ) {
							continue;
						}
						?>
						<div class="service-repair-types__slide swiper-slide">
							<article class="benefits-card benefits-card--repair-type">
								<div class="benefits-card__head">
									<?php if ( $item_title ) : ?>
										<h3 class="benefits-card__title"><?php echo esc_html( $item_title ); ?></h3>
									<?php endif; ?>
									<span class="benefits-card__mark" aria-hidden="true"></span>
								</div>

								<?php if ( $item_text ) : ?>
									<p class="benefits-card__text"><?php echo nl2br( esc_html( $item_text ) ); ?></p>
								<?php endif; ?>

								<?php if ( $item_image ) : ?>
									<div class="benefits-card__media">
										<?php echo a4_remont_get_acf_image_html( $item_image, 'large', array( 'class' => 'benefits-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="service-repair-types__bottom">
			<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey service-repair-types__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
