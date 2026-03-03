<?php
/**
 * Secondary CTA banner section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/cta-banner-secondary.html' );
	return;
}

$title = (string) get_sub_field( 'title' );
$image = get_sub_field( 'image' );

if ( '' === trim( $title ) && empty( $image ) ) {
	a4_remont_render_static_markup( 'section/cta-banner-secondary.html' );
	return;
}

$section_id         = sanitize_title( (string) get_sub_field( 'section_id' ) );
$subtitle           = (string) get_sub_field( 'subtitle' );
$phone_placeholder  = (string) get_sub_field( 'phone_placeholder' );
$submit_label       = (string) get_sub_field( 'submit_label' );
$privacy_text       = (string) get_sub_field( 'privacy_text' );

if ( '' === $phone_placeholder ) {
	$phone_placeholder = '+7 ( _ _ _ ) _ _ _ - _ _ - _ _';
}

if ( '' === $submit_label ) {
	$submit_label = 'Перезвоните мне';
}
?>
<section class="cta-banner-secondary"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="cta-banner-secondary__container _container">
		<article class="cta-banner-secondary__card">
			<div class="cta-banner-secondary__grid">
				<?php if ( $image ) : ?>
					<div class="cta-banner-secondary__media">
						<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'cta-banner-secondary__people', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<div class="cta-banner-secondary__content">
					<?php if ( $title ) : ?>
						<h2 class="cta-banner-secondary__title"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( $subtitle ) : ?>
						<p class="cta-banner-secondary__subtitle"><?php echo nl2br( esc_html( $subtitle ) ); ?></p>
					<?php endif; ?>

					<form class="cta-banner-secondary__form" action="#" method="post">
						<div class="cta-banner-secondary__fields">
							<label class="cta-banner-secondary__field">
								<span class="visually-hidden">Телефон</span>
								<input class="cta-banner-secondary__input" type="tel" name="phone" placeholder="<?php echo esc_attr( $phone_placeholder ); ?>" inputmode="tel" autocomplete="tel">
							</label>
							<button class="cta-banner-secondary__submit" type="submit"><?php echo esc_html( $submit_label ); ?></button>
						</div>

						<?php if ( $privacy_text ) : ?>
							<label class="cta-banner-secondary__agree">
								<input class="cta-banner-secondary__checkbox" type="checkbox" name="agree" checked="checked">
								<span class="cta-banner-secondary__agree-ui" aria-hidden="true"></span>
								<span class="cta-banner-secondary__agree-text"><?php echo wp_kses_post( nl2br( $privacy_text ) ); ?></span>
							</label>
						<?php endif; ?>
					</form>
				</div>
			</div>
		</article>
	</div>
</section>
