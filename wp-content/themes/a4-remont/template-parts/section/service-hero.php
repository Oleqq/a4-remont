<?php
/**
 * Single service hero section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/service-single/service-hero.html' );
	return;
}

$service_post = get_post();
$title        = (string) get_sub_field( 'title' );
$lead         = (string) get_sub_field( 'lead' );
$cta_button   = get_sub_field( 'cta_button' );
$image        = get_sub_field( 'image' );

if ( $service_post instanceof WP_Post ) {
	if ( '' === trim( $title ) ) {
		$title = get_the_title( $service_post );
	}

	if ( '' === trim( $lead ) ) {
		$lead = has_excerpt( $service_post ) ? (string) $service_post->post_excerpt : a4_remont_get_post_excerpt( $service_post, 36 );
	}

	if ( empty( $image ) && has_post_thumbnail( $service_post ) ) {
		$image = get_post_thumbnail_id( $service_post );
	}
}

$feature_title = (string) get_sub_field( 'feature_title' );
$feature_text  = (string) get_sub_field( 'feature_text' );

if ( '' === trim( $title ) && '' === trim( $lead ) && '' === trim( $feature_title ) && '' === trim( $feature_text ) && empty( $image ) ) {
	a4_remont_render_static_markup( 'section/service-single/service-hero.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="service-hero"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-hero__container _container">
		<div class="service-hero__grid">
			<div class="service-hero__intro">
				<?php if ( $title ) : ?>
					<h1 class="section__title service-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
				<?php endif; ?>

				<?php if ( $lead ) : ?>
					<p class="section__subtitle service-hero__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
				<?php endif; ?>

				<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey service-hero__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<?php if ( $image ) : ?>
				<div class="service-hero__media">
					<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'service-hero__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>

			<?php if ( $feature_title || $feature_text ) : ?>
				<div class="service-hero__feature">
					<?php if ( $feature_title ) : ?>
						<h2 class="service-hero__feature-title"><?php echo esc_html( $feature_title ); ?></h2>
					<?php endif; ?>

					<?php if ( $feature_text ) : ?>
						<p class="section__subtitle service-hero__feature-text"><?php echo nl2br( esc_html( $feature_text ) ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
