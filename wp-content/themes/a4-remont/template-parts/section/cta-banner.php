<?php
/**
 * CTA banner section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/cta-banner.html';

$section_class = 'cta-banner';

if ( ! empty( $args['section_class'] ) ) {
	$section_class .= ' ' . trim( (string) $args['section_class'] );
}

$render_static_banner = static function ( $partial, $class_name ) {
	$markup = a4_remont_get_static_markup( $partial );

	if ( '' === $markup ) {
		return false;
	}

	if ( 'cta-banner' !== $class_name ) {
		$markup = preg_replace(
			'/(<section\b[^>]*class=["\'])cta-banner(["\'])/',
			'$1' . esc_attr( $class_name ) . '$2',
			$markup,
			1
		);
	}

	echo $markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	return true;
};

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	$render_static_banner( $fallback_partial, $section_class );
	return;
}

$title = (string) get_sub_field( 'title' );
$image = get_sub_field( 'image' );

if ( '' === trim( $title ) && empty( $image ) ) {
	$render_static_banner( $fallback_partial, $section_class );
	return;
}

$section_id        = sanitize_title( (string) get_sub_field( 'section_id' ) );
$subtitle          = (string) get_sub_field( 'subtitle' );
$telegram_url      = (string) get_sub_field( 'telegram_url' );
$vk_url            = (string) get_sub_field( 'vk_url' );
$reviews_url       = (string) get_sub_field( 'reviews_url' );
$phone_placeholder = (string) get_sub_field( 'phone_placeholder' );
$submit_label      = (string) get_sub_field( 'submit_label' );
$privacy_text      = (string) get_sub_field( 'privacy_text' );
$phone_number      = (string) get_sub_field( 'phone_number' );
$email             = (string) get_sub_field( 'email' );
$address           = (string) get_sub_field( 'address' );

if ( '' === $submit_label ) {
	$submit_label = 'Call me back';
}
?>
<section class="<?php echo esc_attr( $section_class ); ?>"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="cta-banner__container _container">
		<div class="article cta-banner__card">
			<div class="cta-banner__social">
				<?php if ( $telegram_url ) : ?>
					<a class="cta-banner__social-link" href="<?php echo esc_url( $telegram_url ); ?>" aria-label="Telegram">TG</a>
				<?php endif; ?>
				<?php if ( $vk_url ) : ?>
					<a class="cta-banner__social-link" href="<?php echo esc_url( $vk_url ); ?>" aria-label="VK">VK</a>
				<?php endif; ?>
				<?php if ( $reviews_url ) : ?>
					<a class="cta-banner__social-link" href="<?php echo esc_url( $reviews_url ); ?>" aria-label="Reviews">REV</a>
				<?php endif; ?>
			</div>
			<div class="cta-banner__grid">
				<div class="cta-banner__content">
					<?php if ( $title ) : ?>
						<h2 class="cta-banner__title"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="cta-banner__subtitle"><?php echo nl2br( esc_html( $subtitle ) ); ?></p>
					<?php endif; ?>
					<form class="cta-banner__form" action="#" method="post">
						<div class="cta-banner__fields">
							<label class="cta-banner__field">
								<span class="visually-hidden">Phone</span>
								<input class="cta-banner__input" type="tel" name="phone" placeholder="<?php echo esc_attr( $phone_placeholder ); ?>" inputmode="tel" autocomplete="tel">
							</label>
							<button class="cta-banner__submit" type="submit"><?php echo esc_html( $submit_label ); ?></button>
						</div>
						<label class="cta-banner__agree">
							<input class="cta-banner__checkbox" type="checkbox" name="agree" checked>
							<span class="cta-banner__agree-ui" aria-hidden="true"></span>
							<span class="cta-banner__agree-text"><?php echo esc_html( $privacy_text ? $privacy_text : 'I agree to the processing of personal data and the privacy policy.' ); ?></span>
						</label>
					</form>
					<div class="cta-banner__contacts">
						<?php if ( $phone_number ) : ?>
							<a class="cta-banner__contact" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone_number ) ); ?>"><span><?php echo esc_html( $phone_number ); ?></span></a>
						<?php endif; ?>
						<?php if ( $email ) : ?>
							<a class="cta-banner__contact" href="<?php echo esc_url( 'mailto:' . $email ); ?>"><span><?php echo esc_html( $email ); ?></span></a>
						<?php endif; ?>
						<?php if ( $address ) : ?>
							<div class="cta-banner__contact"><span><?php echo esc_html( $address ); ?></span></div>
						<?php endif; ?>
					</div>
				</div>
				<?php if ( $image ) : ?>
					<div class="cta-banner__media">
						<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'cta-banner__people', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
