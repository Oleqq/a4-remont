<?php
/**
 * FAQ page hero section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/faq-page/faq-hero.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title         = (string) get_sub_field( 'title' );
$note          = (string) get_sub_field( 'note' );
$desktop_image = get_sub_field( 'image_desktop' );
$mobile_image  = get_sub_field( 'image_mobile' );

if ( '' === trim( $title ) && '' === trim( $note ) && empty( $desktop_image ) && empty( $mobile_image ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id  = sanitize_title( (string) get_sub_field( 'section_id' ) );
$desktop_url = a4_remont_get_acf_image_url( $desktop_image, 'full' );
$mobile_url  = a4_remont_get_acf_image_url( $mobile_image, 'full' );
$image_alt   = (string) get_sub_field( 'image_alt' );

if ( '' === $desktop_url ) {
	$desktop_url = $mobile_url;
}

if ( '' === $image_alt && is_array( $desktop_image ) && ! empty( $desktop_image['alt'] ) ) {
	$image_alt = (string) $desktop_image['alt'];
}

if ( '' === $image_alt ) {
	$image_alt = 'Ответы на вопросы о ремонте';
}
?>
<section class="faq-hero"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="faq-hero__container _container">
		<?php if ( $title ) : ?>
			<h1 class="section__title faq-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
		<?php endif; ?>

		<div class="faq-hero__media">
			<?php if ( $desktop_url ) : ?>
				<picture>
					<?php if ( $mobile_url ) : ?>
						<source media="(max-width: 467px)" srcset="<?php echo esc_url( $mobile_url ); ?>">
					<?php endif; ?>
					<img class="faq-hero__image" src="<?php echo esc_url( $desktop_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" title="<?php echo esc_attr( $image_alt ); ?>" loading="lazy">
				</picture>
			<?php endif; ?>

			<?php if ( $note ) : ?>
				<div class="faq-hero__note">
					<?php echo wpautop( esc_html( $note ) ); ?>
				</div>
			<?php endif; ?>

			<span class="faq-hero__badge" aria-hidden="true">?</span>
		</div>
	</div>
</section>
