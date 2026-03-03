<?php
/**
 * About hero section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/about-us/hero-about-us.html' );
	return;
}

$title = (string) get_sub_field( 'title' );
$image = get_sub_field( 'image' );

if ( '' === trim( $title ) && empty( $image ) ) {
	a4_remont_render_static_markup( 'section/about-us/hero-about-us.html' );
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
?>
<section class="hero hero-about-us"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
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

			<?php if ( $image ) : ?>
				<div class="hero__media">
					<div class="hero__slider swiper" data-hero-swiper="data-hero-swiper">
						<div class="swiper-wrapper">
							<div class="swiper-slide hero__slide">
								<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'hero__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
