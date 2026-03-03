<?php
/**
 * Services archive hero section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/services/hero-services.html' );
	return;
}

$title      = (string) get_sub_field( 'title' );
$text       = (string) get_sub_field( 'text' );
$cta_button = get_sub_field( 'cta_button' );
$gallery    = get_sub_field( 'gallery' );
$has_cta_button = a4_remont_has_sub_field_action_button( 'cta_button', $cta_button );

if ( '' === trim( $title ) && '' === trim( $text ) && ! $has_cta_button && empty( $gallery ) ) {
	a4_remont_render_static_markup( 'section/services/hero-services.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$gallery    = is_array( $gallery ) ? array_slice( $gallery, 0, 2 ) : array();
?>
<section class="hero-services"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="hero-services__container _container">
		<div class="hero-services__inner">
			<div class="hero-services__content">
				<?php if ( $title ) : ?>
					<h1 class="section__title"><?php echo wp_kses_post( $title ); ?></h1>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<p class="hero-services__text"><?php echo nl2br( esc_html( $text ) ); ?></p>
				<?php endif; ?>

				<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey hero-services__btn hero-services__btn--desktop' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<?php if ( $gallery ) : ?>
				<div class="hero-services__media">
					<div class="hero-services__gallery">
						<?php foreach ( $gallery as $image ) : ?>
							<div class="hero-services__pic">
								<?php echo a4_remont_get_acf_image_html( $image, 'large', array( 'class' => 'hero-services__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey hero-services__btn hero-services__btn--mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
