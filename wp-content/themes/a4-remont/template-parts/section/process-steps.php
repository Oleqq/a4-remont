<?php
/**
 * Process steps section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/services/process-steps.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$section_text  = (string) get_sub_field( 'section_text' );
$steps         = get_sub_field( 'steps' );

if ( '' === trim( $section_title ) && '' === trim( $section_text ) && empty( $steps ) ) {
	a4_remont_render_static_markup( 'section/services/process-steps.html' );
	return;
}

$section_id  = sanitize_title( (string) get_sub_field( 'section_id' ) );
$section_note = (string) get_sub_field( 'section_note' );
$cta_button  = get_sub_field( 'cta_button' );
$has_cta_button = a4_remont_has_sub_field_action_button( 'cta_button', $cta_button );
?>
<section class="process-steps"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="process-steps__container _container">
		<div class="process-steps__top">
			<?php if ( $section_title ) : ?>
				<h2 class="section__title process-steps__title"><?php echo wp_kses_post( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_text ) : ?>
				<div class="process-steps__intro">
					<p class="section__subtitle process-steps__subtitle"><?php echo nl2br( esc_html( $section_text ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $steps ) ) : ?>
			<div class="process-steps__slider swiper" data-process-steps="data-process-steps">
				<div class="process-steps__wrapper swiper-wrapper">
					<?php foreach ( (array) $steps as $step ) : ?>
						<?php
						if ( ! is_array( $step ) ) {
							continue;
						}

						$step_count    = ! empty( $step['step_count'] ) ? (string) $step['step_count'] : '';
						$step_title    = ! empty( $step['step_title'] ) ? (string) $step['step_title'] : '';
						$step_text     = ! empty( $step['step_text'] ) ? (string) $step['step_text'] : '';
						$step_image    = ! empty( $step['step_image'] ) ? $step['step_image'] : null;
						$step_modifier = ! empty( $step['step_modifier'] ) ? (string) $step['step_modifier'] : '';

						if ( '' === trim( $step_count ) && '' === trim( $step_title ) && '' === trim( $step_text ) && empty( $step_image ) ) {
							continue;
						}
						?>
						<article class="process-steps__item swiper-slide<?php echo $step_modifier ? ' ' . esc_attr( $step_modifier ) : ''; ?>">
							<div class="process-steps__head">
								<?php if ( $step_count ) : ?>
									<span class="process-steps__count"><?php echo esc_html( $step_count ); ?></span>
								<?php endif; ?>

								<?php if ( $step_image ) : ?>
									<div class="process-steps__media">
										<?php echo a4_remont_get_acf_image_html( $step_image, 'large', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>
							</div>

							<div class="process-steps__body">
								<?php if ( $step_title ) : ?>
									<h3 class="process-steps__name"><?php echo esc_html( $step_title ); ?></h3>
								<?php endif; ?>

								<?php if ( $step_text ) : ?>
									<p class="process-steps__text"><?php echo nl2br( esc_html( $step_text ) ); ?></p>
								<?php endif; ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
				<div class="process-steps__pagination"></div>
			</div>
		<?php endif; ?>

		<?php if ( $section_note || $has_cta_button ) : ?>
			<div class="process-steps__bottom">
				<?php if ( $section_note ) : ?>
					<p class="process-steps__note"><?php echo nl2br( esc_html( $section_note ) ); ?></p>
				<?php endif; ?>

				<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey process-steps__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</section>
