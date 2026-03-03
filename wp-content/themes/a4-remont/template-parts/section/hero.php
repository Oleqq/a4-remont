<?php
/**
 * Hero section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/hero.html' );
	return;
}

$title = (string) get_sub_field( 'title' );
$slides = get_sub_field( 'slides' );

if ( '' === trim( $title ) && empty( $slides ) ) {
	a4_remont_render_static_markup( 'section/hero.html' );
	return;
}

$section_id       = sanitize_title( (string) get_sub_field( 'section_id' ) );
$subtitle         = (string) get_sub_field( 'subtitle' );
$text             = (string) get_sub_field( 'text' );
$note             = (string) get_sub_field( 'note' );
$primary_button   = get_sub_field( 'primary_button' );
$secondary_button = get_sub_field( 'secondary_button' );
$has_primary_button   = a4_remont_has_sub_field_action_button( 'primary_button', $primary_button );
$has_secondary_button = a4_remont_has_sub_field_action_button( 'secondary_button', $secondary_button );
$slides           = is_array( $slides ) ? $slides : array();
?>
<section class="hero"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="hero__container _container">
		<div class="hero__grid">
			<div class="hero__content">
				<?php if ( $subtitle ) : ?>
					<p class="hero__subtitle"><?php echo wp_kses_post( $subtitle ); ?></p>
				<?php endif; ?>

				<?php if ( $title ) : ?>
					<h2 class="hero__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<div class="hero__text"><?php echo wpautop( wp_kses_post( $text ) ); ?></div>
				<?php endif; ?>

				<div class="hero__bottom">
					<?php if ( $note ) : ?>
						<p class="hero__note"><?php echo nl2br( esc_html( $note ) ); ?></p>
					<?php endif; ?>

					<?php if ( $has_primary_button || $has_secondary_button ) : ?>
						<div class="hero__actions">
							<?php echo a4_remont_get_sub_field_action_button_html( 'primary_button', 'btn btn--primary hero__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php echo a4_remont_get_sub_field_action_button_html( 'secondary_button', 'btn btn--grey hero__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $slides ) : ?>
				<div class="hero__media">
					<div class="hero__slider swiper" data-hero-swiper="data-hero-swiper">
						<div class="swiper-wrapper">
							<?php foreach ( $slides as $slide ) : ?>
								<div class="swiper-slide hero__slide">
									<?php echo a4_remont_get_acf_image_html( $slide, 'full', array( 'class' => 'hero__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							<?php endforeach; ?>
						</div>
						<div class="hero__controls">
							<button class="hero__arrow hero__arrow--prev" type="button" aria-label="Previous slide" data-hero-prev="data-hero-prev"><span aria-hidden="true">&larr;</span></button>
							<div class="hero__dots" data-hero-pagination="data-hero-pagination"></div>
							<button class="hero__arrow hero__arrow--next" type="button" aria-label="Next slide" data-hero-next="data-hero-next"><span aria-hidden="true">&rarr;</span></button>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
