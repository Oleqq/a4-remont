<?php
/**
 * Single service order steps section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/service-single/service-order-steps.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$section_lead  = (string) get_sub_field( 'section_lead' );
$steps         = get_sub_field( 'steps' );

if ( '' === trim( $section_title ) && '' === trim( $section_lead ) && empty( $steps ) ) {
	a4_remont_render_static_markup( 'section/service-single/service-order-steps.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$cta_button = get_sub_field( 'cta_button' );
$has_cta_button = a4_remont_has_sub_field_action_button( 'cta_button', $cta_button );
?>
<section class="service-order-steps"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-order-steps__container _container">
		<div class="service-order-steps__top">
			<?php if ( $section_title ) : ?>
				<h2 class="section__title service-order-steps__title"><?php echo wp_kses_post( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_lead ) : ?>
				<p class="section__subtitle service-order-steps__lead"><?php echo nl2br( esc_html( $section_lead ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $steps ) ) : ?>
			<div class="service-order-steps__slider swiper" data-service-order-steps-slider="data-service-order-steps-slider">
				<div class="service-order-steps__track swiper-wrapper">
					<?php foreach ( (array) $steps as $step ) : ?>
						<?php
						if ( ! is_array( $step ) ) {
							continue;
						}

						$step_number = ! empty( $step['step_number'] ) ? (string) $step['step_number'] : '';
						$step_title  = ! empty( $step['step_title'] ) ? (string) $step['step_title'] : '';
						$step_text   = ! empty( $step['step_text'] ) ? (string) $step['step_text'] : '';

						if ( '' === trim( $step_number ) && '' === trim( $step_title ) && '' === trim( $step_text ) ) {
							continue;
						}
						?>
						<article class="service-order-step service-order-steps__item swiper-slide">
							<?php if ( $step_number ) : ?>
								<span class="service-order-step__num"><?php echo esc_html( $step_number ); ?></span>
							<?php endif; ?>

							<?php if ( $step_title ) : ?>
								<h3 class="service-order-step__title"><?php echo esc_html( $step_title ); ?></h3>
							<?php endif; ?>

							<?php if ( $step_text ) : ?>
								<p class="service-order-step__text"><?php echo nl2br( esc_html( $step_text ) ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>

					<?php if ( $has_cta_button ) : ?>
						<div class="service-order-steps__cta-wrap swiper-slide">
							<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey service-order-steps__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $has_cta_button ) : ?>
			<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey service-order-steps__btn mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php endif; ?>
	</div>
</section>
