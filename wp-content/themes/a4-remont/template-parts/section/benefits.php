<?php
/**
 * Benefits section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/benefits.html' );
	return;
}

$items = get_sub_field( 'items' );

if ( empty( $items ) || ! is_array( $items ) ) {
	a4_remont_render_static_markup( 'section/benefits.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="benefits"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="benefits__container _container">
		<div class="benefits__slider swiper" data-benefits-swiper="data-benefits-swiper">
			<div class="swiper-wrapper benefits__grid">
				<?php foreach ( $items as $item ) : ?>
					<div class="swiper-slide">
						<article class="benefits-card">
							<div class="benefits-card__head">
								<?php if ( ! empty( $item['item_title'] ) ) : ?>
									<h3 class="benefits-card__title"><?php echo wp_kses_post( $item['item_title'] ); ?></h3>
								<?php endif; ?>
								<span class="benefits-card__mark" aria-hidden="true"></span>
							</div>

							<?php if ( ! empty( $item['item_text'] ) ) : ?>
								<p class="benefits-card__text"><?php echo nl2br( esc_html( $item['item_text'] ) ); ?></p>
							<?php endif; ?>

							<?php if ( ! empty( $item['item_image'] ) ) : ?>
								<div class="benefits-card__media">
									<?php echo a4_remont_get_acf_image_html( $item['item_image'], 'full', array( 'class' => 'benefits-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							<?php endif; ?>
						</article>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
