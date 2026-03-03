<?php
/**
 * Service arguments section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/services/service-arguments.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$section_text  = (string) get_sub_field( 'section_text' );
$cards         = get_sub_field( 'cards' );

if ( '' === trim( $section_title ) && '' === trim( $section_text ) && empty( $cards ) ) {
	a4_remont_render_static_markup( 'section/services/service-arguments.html' );
	return;
}

$section_id       = sanitize_title( (string) get_sub_field( 'section_id' ) );
$result_primary   = (string) get_sub_field( 'result_primary' );
$result_secondary = (string) get_sub_field( 'result_secondary' );
?>
<section class="service-arguments"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-arguments__container _container">
		<div class="service-arguments__top">
			<?php if ( $section_title ) : ?>
				<h2 class="section__title service-arguments__title"><?php echo wp_kses_post( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_text ) : ?>
				<p class="section__subtitle service-arguments__subtitle"><?php echo nl2br( esc_html( $section_text ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $cards ) ) : ?>
			<div class="service-arguments__slider swiper" data-service-arguments="data-service-arguments">
				<div class="service-arguments__track swiper-wrapper">
					<?php foreach ( (array) $cards as $card ) : ?>
						<?php
						if ( ! is_array( $card ) ) {
							continue;
						}

						$card_title    = ! empty( $card['card_title'] ) ? (string) $card['card_title'] : '';
						$card_text     = ! empty( $card['card_text'] ) ? (string) $card['card_text'] : '';
						$card_image    = ! empty( $card['card_image'] ) ? $card['card_image'] : null;
						$card_modifier = ! empty( $card['card_modifier'] ) ? (string) $card['card_modifier'] : 'benefits-card--worker';

						if ( '' === trim( $card_title ) && '' === trim( $card_text ) && empty( $card_image ) ) {
							continue;
						}
						?>
						<div class="service-arguments__slide swiper-slide">
							<article class="benefits-card <?php echo esc_attr( $card_modifier ); ?>">
								<div class="benefits-card__head">
									<?php if ( $card_title ) : ?>
										<h3 class="benefits-card__title"><?php echo esc_html( $card_title ); ?></h3>
									<?php endif; ?>
									<span class="benefits-card__mark" aria-hidden="true"></span>
								</div>

								<?php if ( $card_text ) : ?>
									<p class="benefits-card__text"><?php echo nl2br( esc_html( $card_text ) ); ?></p>
								<?php endif; ?>

								<?php if ( $card_image ) : ?>
									<div class="benefits-card__media">
										<?php echo a4_remont_get_acf_image_html( $card_image, 'large', array( 'class' => 'benefits-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $result_primary || $result_secondary ) : ?>
			<div class="service-arguments__bottom">
				<?php if ( $result_primary ) : ?>
					<p class="service-arguments__result"><?php echo nl2br( esc_html( $result_primary ) ); ?></p>
				<?php endif; ?>

				<?php if ( $result_secondary ) : ?>
					<p class="service-arguments__result"><?php echo nl2br( esc_html( $result_secondary ) ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
