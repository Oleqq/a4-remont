<?php
/**
 * Single work performed section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/work-single/work-single-performed.html' );
	return;
}

$section_title     = (string) get_sub_field( 'section_title' );
$section_text      = (string) get_sub_field( 'section_text' );
$section_text_bold = (string) get_sub_field( 'section_text_strong' );
$list_title        = (string) get_sub_field( 'list_title' );
$items             = get_sub_field( 'items' );
$main_image        = get_sub_field( 'main_image' );
$secondary_image   = get_sub_field( 'secondary_image' );
$gallery           = get_sub_field( 'gallery' );

if ( '' === trim( $section_title ) && '' === trim( $section_text ) && '' === trim( $section_text_bold ) && '' === trim( $list_title ) && empty( $items ) && empty( $main_image ) && empty( $secondary_image ) && empty( $gallery ) ) {
	a4_remont_render_static_markup( 'section/work-single/work-single-performed.html' );
	return;
}

if ( '' === trim( $section_title ) ) {
	$section_title = 'Какие работы были выполнены';
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="work-single-performed" data-work-performed="data-work-performed"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="work-single-performed__container _container">
		<div class="work-single-performed__top">
			<?php if ( $main_image ) : ?>
				<figure class="work-single-performed__main-media">
					<?php echo a4_remont_get_acf_image_html( $main_image, 'large', array( 'class' => 'work-single-performed__main-image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</figure>
			<?php endif; ?>

			<div class="work-single-performed__content">
				<?php if ( $section_title ) : ?>
					<h2 class="section__title work-single-performed__title"><?php echo wp_kses_post( $section_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $section_text ) : ?>
					<p class="section__subtitle work-single-performed__text"><?php echo nl2br( esc_html( $section_text ) ); ?></p>
				<?php endif; ?>

				<?php if ( $section_text_bold ) : ?>
					<p class="work-single-performed__text work-single-performed__text--strong"><?php echo nl2br( esc_html( $section_text_bold ) ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( $list_title || ( ! empty( $items ) && is_array( $items ) ) || $secondary_image ) : ?>
			<div class="work-single-performed__middle">
				<div class="work-single-performed__list">
					<?php if ( $list_title ) : ?>
						<h3 class="work-single-performed__list-title"><?php echo wp_kses_post( $list_title ); ?></h3>
					<?php endif; ?>

					<?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
						<ol class="work-single-performed__items">
							<?php foreach ( $items as $item ) : ?>
								<?php $item_text = ! empty( $item['item_text'] ) ? (string) $item['item_text'] : ''; ?>
								<?php if ( '' === trim( $item_text ) ) : ?>
									<?php continue; ?>
								<?php endif; ?>
								<li class="work-single-performed__item"><?php echo esc_html( $item_text ); ?></li>
							<?php endforeach; ?>
						</ol>
					<?php endif; ?>
				</div>

				<?php if ( $secondary_image ) : ?>
					<figure class="work-single-performed__secondary-media">
						<?php echo a4_remont_get_acf_image_html( $secondary_image, 'large', array( 'class' => 'work-single-performed__secondary-image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</figure>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $gallery ) && is_array( $gallery ) ) : ?>
			<div class="work-single-performed__gallery">
				<div class="work-single-performed__slider swiper" data-work-performed-slider="data-work-performed-slider">
					<div class="swiper-wrapper">
						<?php foreach ( $gallery as $gallery_item ) : ?>
							<div class="swiper-slide">
								<figure class="work-single-performed__thumb">
									<?php echo a4_remont_get_acf_image_html( $gallery_item, 'medium_large', array( 'class' => 'work-single-performed__thumb-image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</figure>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="work-single-performed__nav">
					<button class="work-single-performed__btn work-single-performed__btn--prev" type="button" aria-label="Предыдущий слайд">
						<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 19L1 10L10 1M2.25 10L20.5 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
					<div class="work-single-performed__scrollbar-wrap">
						<div class="work-single-performed__scrollbar swiper-scrollbar"></div>
					</div>
					<button class="work-single-performed__btn work-single-performed__btn--next" type="button" aria-label="Следующий слайд">
						<svg width="22" height="20" viewBox="0 0 22 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.5 1L20.5 10L11.5 19M19.25 10L1 10" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
