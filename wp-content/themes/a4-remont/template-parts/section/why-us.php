<?php
/**
 * Why us section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/why-us.html' );
	return;
}

$slides = get_sub_field( 'slides' );

if ( empty( $slides ) || ! is_array( $slides ) ) {
	a4_remont_render_static_markup( 'section/why-us.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$title      = (string) get_sub_field( 'section_title' );
$text       = (string) get_sub_field( 'section_text' );
?>
<section class="why-us"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="why-us__container _container">
		<div class="why-us__top">
			<div class="why-us__intro">
				<?php if ( $title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $text ) : ?>
					<p class="section__subtitle"><?php echo nl2br( esc_html( $text ) ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<div class="why-us__slider swiper" data-why-swiper="data-why-swiper">
			<div class="swiper-wrapper why-us__grid">
				<?php foreach ( $slides as $slide ) : ?>
					<div class="swiper-slide">
						<?php if ( isset( $slide['card_type'] ) && 'image' === $slide['card_type'] && ! empty( $slide['card_image'] ) ) : ?>
							<article class="why-card why-card--image">
								<?php echo a4_remont_get_acf_image_html( $slide['card_image'], 'full', array( 'class' => 'why-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</article>
						<?php else : ?>
							<article class="why-card">
								<?php if ( ! empty( $slide['icon_image'] ) ) : ?>
									<div class="why-card__icon">
										<?php echo a4_remont_get_acf_image_html( $slide['icon_image'], 'thumbnail', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $slide['card_title'] ) ) : ?>
									<h3 class="why-card__title"><?php echo wp_kses_post( $slide['card_title'] ); ?></h3>
								<?php endif; ?>

								<?php if ( ! empty( $slide['card_text'] ) ) : ?>
									<p class="why-card__text"><?php echo nl2br( esc_html( $slide['card_text'] ) ); ?></p>
								<?php endif; ?>
							</article>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
