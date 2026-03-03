<?php
/**
 * Single work result section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/work-single/work-single-result.html' );
	return;
}

$work_post                = get_post();
$title                    = (string) get_sub_field( 'title' );
$content                  = (string) get_sub_field( 'content' );
$brand_image              = get_sub_field( 'brand_image' );
$brand_text               = (string) get_sub_field( 'brand_text' );
$background_image         = get_sub_field( 'background_image' );
$background_image_mobile  = get_sub_field( 'background_image_mobile' );

if ( '' === trim( $title ) && '' !== trim( wp_strip_all_tags( $content ) ) ) {
	$title = 'Результат работ';
}

if ( '' === trim( $title ) && '' === trim( wp_strip_all_tags( $content ) ) && empty( $brand_image ) && '' === trim( $brand_text ) && empty( $background_image ) && empty( $background_image_mobile ) ) {
	a4_remont_render_static_markup( 'section/work-single/work-single-result.html' );
	return;
}

if ( empty( $background_image ) && $work_post instanceof WP_Post && has_post_thumbnail( $work_post ) ) {
	$background_image = get_post_thumbnail_id( $work_post );
}

if ( '' === trim( $brand_text ) && empty( $brand_image ) ) {
	$brand_text = get_bloginfo( 'name' );
}

$section_id          = sanitize_title( (string) get_sub_field( 'section_id' ) );
$desktop_background  = a4_remont_get_acf_image_url( $background_image, 'full' );
$mobile_background   = a4_remont_get_acf_image_url( $background_image_mobile, 'full' );
$background_alt      = $title ? $title : 'Фоновое изображение проекта';
$brand_logo_html     = a4_remont_get_acf_image_html( $brand_image, 'full', array( 'alt' => $brand_text ? $brand_text : get_bloginfo( 'name' ) ) );
?>
<section class="work-single-result" data-work-result="data-work-result"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="work-single-result__container">
		<?php if ( $desktop_background ) : ?>
			<div class="work-single-result__bg">
				<picture>
					<?php if ( $mobile_background ) : ?>
						<source media="(max-width: 467px)" srcset="<?php echo esc_url( $mobile_background ); ?>" type="image/png">
					<?php endif; ?>
					<img class="about-reviews__bg-img work-single-result__bg-img" src="<?php echo esc_url( $desktop_background ); ?>" alt="<?php echo esc_attr( $background_alt ); ?>" loading="lazy">
				</picture>
			</div>
		<?php endif; ?>

		<article class="about-reviews__company work-single-result__company">
			<?php if ( $title ) : ?>
				<h2 class="about-reviews__company-title work-single-result__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( '' !== trim( wp_strip_all_tags( $content ) ) ) : ?>
				<div class="work-single-result__text" data-work-result-text="data-work-result-text">
					<?php echo wp_kses_post( $content ); ?>
				</div>
				<button class="work-single-result__more" type="button" data-work-result-more="data-work-result-more">...ещё</button>
			<?php endif; ?>

			<?php if ( $brand_logo_html || '' !== trim( $brand_text ) ) : ?>
				<div class="about-reviews__logo work-single-result__logo" aria-hidden="true">
					<?php if ( $brand_logo_html ) : ?>
						<?php echo $brand_logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php else : ?>
						<span class="work-single-result__logo-text"><?php echo esc_html( $brand_text ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</article>
	</div>
</section>
