<?php
/**
 * News archive CTA banner section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/news/cta-banner-2.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title        = (string) get_sub_field( 'title' );
$subtitle     = (string) get_sub_field( 'subtitle' );
$telegram_url = (string) get_sub_field( 'telegram_url' );
$vk_url       = (string) get_sub_field( 'vk_url' );
$reviews_url  = (string) get_sub_field( 'reviews_url' );
$phone_number = (string) get_sub_field( 'phone_number' );
$email        = (string) get_sub_field( 'email' );
$address      = (string) get_sub_field( 'address' );
$image        = get_sub_field( 'image' );

if ( '' === trim( $title ) && '' === trim( $subtitle ) && '' === trim( $phone_number ) && '' === trim( $email ) && '' === trim( $address ) && '' === trim( $telegram_url ) && '' === trim( $vk_url ) && '' === trim( $reviews_url ) && empty( $image ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id        = sanitize_title( (string) get_sub_field( 'section_id' ) );
$phone_placeholder = (string) get_sub_field( 'phone_placeholder' );
$submit_label      = (string) get_sub_field( 'submit_label' );
$privacy_text      = (string) get_sub_field( 'privacy_text' );

if ( '' === trim( $title ) ) {
	$title = 'Планируете ремонт?<br>Мы поможем';
}

if ( '' === trim( $subtitle ) ) {
	$subtitle = 'Давайте обсудим ваш проект! Оставьте свои контакты в форме ниже и наша команда свяжется с вами для уточнения деталей.';
}

if ( '' === trim( $phone_placeholder ) ) {
	$phone_placeholder = '+7 ( _ _ _ ) _ _ _ - _ _ - _ _';
}

if ( '' === trim( $submit_label ) ) {
	$submit_label = 'Перезвоните мне';
}

if ( '' === trim( wp_strip_all_tags( $privacy_text ) ) ) {
	$privacy_text = 'Я даю согласие на обработку персональных данных и соглашаюсь с политикой конфиденциальности.';
}
?>
<section class="cta-banner cta-banner-2"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="cta-banner__container _container">
		<div class="article cta-banner__card">
			<?php if ( $telegram_url || $vk_url || $reviews_url ) : ?>
				<div class="cta-banner__social">
					<?php if ( $telegram_url ) : ?>
						<a class="cta-banner__social-link" href="<?php echo esc_url( $telegram_url ); ?>" aria-label="Telegram">TG</a>
					<?php endif; ?>
					<?php if ( $vk_url ) : ?>
						<a class="cta-banner__social-link" href="<?php echo esc_url( $vk_url ); ?>" aria-label="VK">VK</a>
					<?php endif; ?>
					<?php if ( $reviews_url ) : ?>
						<a class="cta-banner__social-link" href="<?php echo esc_url( $reviews_url ); ?>" aria-label="Отзывы">REV</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

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
								<span class="visually-hidden">Телефон</span>
								<input class="cta-banner__input" type="tel" name="phone" placeholder="<?php echo esc_attr( $phone_placeholder ); ?>" inputmode="tel" autocomplete="tel">
							</label>
							<button class="cta-banner__submit" type="submit"><?php echo esc_html( $submit_label ); ?></button>
						</div>

						<label class="cta-banner__agree">
							<input class="cta-banner__checkbox" type="checkbox" name="agree" checked="checked">
							<span class="cta-banner__agree-ui" aria-hidden="true"></span>
							<span class="cta-banner__agree-text"><?php echo wp_kses_post( $privacy_text ); ?></span>
						</label>
					</form>

					<?php if ( $phone_number || $email || $address ) : ?>
						<div class="cta-banner__contacts">
							<?php if ( $phone_number ) : ?>
								<a class="cta-banner__contact" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone_number ) ); ?>">
									<span class="cta-banner__contact-ic" aria-hidden="true">P</span>
									<span><?php echo esc_html( $phone_number ); ?></span>
								</a>
							<?php endif; ?>
							<?php if ( $email ) : ?>
								<a class="cta-banner__contact" href="<?php echo esc_url( 'mailto:' . $email ); ?>">
									<span class="cta-banner__contact-ic" aria-hidden="true">@</span>
									<span><?php echo esc_html( $email ); ?></span>
								</a>
							<?php endif; ?>
							<?php if ( $address ) : ?>
								<div class="cta-banner__contact">
									<span class="cta-banner__contact-ic" aria-hidden="true">M</span>
									<span><?php echo esc_html( $address ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					<?php endif; ?>
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
