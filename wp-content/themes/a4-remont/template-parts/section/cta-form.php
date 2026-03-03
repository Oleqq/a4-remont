<?php
/**
 * CTA form section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/our-works/cta-form.html';
$title_modifier   = ! empty( $args['title_modifier_class'] ) ? sanitize_html_class( (string) $args['title_modifier_class'] ) : '';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title          = (string) get_sub_field( 'title' );
$lead           = (string) get_sub_field( 'lead' );
$brand_image    = get_sub_field( 'brand_image' );
$brand_text     = (string) get_sub_field( 'brand_text' );
$form_shortcode = trim( (string) get_sub_field( 'form_shortcode' ) );

if ( '' === trim( $title ) && '' === trim( $lead ) && empty( $brand_image ) && '' === trim( $brand_text ) && '' === $form_shortcode ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id          = sanitize_title( (string) get_sub_field( 'section_id' ) );
$name_placeholder    = (string) get_sub_field( 'name_placeholder' );
$phone_placeholder   = (string) get_sub_field( 'phone_placeholder' );
$email_placeholder   = (string) get_sub_field( 'email_placeholder' );
$message_placeholder = (string) get_sub_field( 'message_placeholder' );
$agreement_text      = (string) get_sub_field( 'agreement_text' );
$submit_label        = (string) get_sub_field( 'submit_label' );

if ( '' === trim( $name_placeholder ) ) {
	$name_placeholder = 'Ваше имя';
}

if ( '' === trim( $phone_placeholder ) ) {
	$phone_placeholder = '+7 000 000 00 00';
}

if ( '' === trim( $email_placeholder ) ) {
	$email_placeholder = 'E-mail';
}

if ( '' === trim( $message_placeholder ) ) {
	$message_placeholder = 'Сообщение';
}

if ( '' === trim( $submit_label ) ) {
	$submit_label = 'Отправить';
}

if ( '' === trim( wp_strip_all_tags( $agreement_text ) ) ) {
	$agreement_text = 'Я даю согласие на <a href="#">обработку персональных данных</a> и соглашаюсь с <a href="#">политикой конфиденциальности</a>';
}

$logo_html = a4_remont_get_acf_image_html(
	$brand_image,
	'full',
	array(
		'alt'   => $brand_text,
		'class' => 'cta-form__logo-image',
	)
);

$shortcode_markup = '';

if ( '' !== $form_shortcode ) {
	$shortcode_markup = do_shortcode( $form_shortcode );
}
?>
<section class="cta-form"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="cta-form__container _container">
		<div class="cta-form__grid">
			<div class="cta-form__left">
				<?php if ( $title ) : ?>
					<h2 class="section__title cta-form__title<?php echo $title_modifier ? ' ' . esc_attr( $title_modifier ) : ''; ?>"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $lead ) : ?>
					<p class="section__subtitle cta-form__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
				<?php endif; ?>

				<?php if ( $logo_html || '' !== trim( $brand_text ) ) : ?>
					<div class="cta-form__logo" aria-hidden="true">
						<?php if ( $logo_html ) : ?>
							<?php echo $logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php else : ?>
							<span class="cta-form__logo-text"><?php echo esc_html( $brand_text ); ?></span>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="cta-form__right">
				<?php if ( '' !== trim( $shortcode_markup ) ) : ?>
					<div class="cta-form__plugin-form">
						<?php echo $shortcode_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php else : ?>
					<form class="cta-form__form" action="#" method="post">
						<div class="cta-form__row">
							<label class="cta-form__field">
								<span class="visually-hidden"><?php echo esc_html( $name_placeholder ); ?></span>
								<input class="cta-form__input" type="text" name="name" placeholder="<?php echo esc_attr( $name_placeholder ); ?>" autocomplete="name">
							</label>

							<label class="cta-form__field">
								<span class="visually-hidden"><?php echo esc_html( $phone_placeholder ); ?></span>
								<input class="cta-form__input" type="tel" name="phone" placeholder="<?php echo esc_attr( $phone_placeholder ); ?>" inputmode="tel" autocomplete="tel">
							</label>
						</div>

						<label class="cta-form__field">
							<span class="visually-hidden"><?php echo esc_html( $email_placeholder ); ?></span>
							<input class="cta-form__input" type="email" name="email" placeholder="<?php echo esc_attr( $email_placeholder ); ?>" autocomplete="email">
						</label>

						<label class="cta-form__field">
							<span class="visually-hidden"><?php echo esc_html( $message_placeholder ); ?></span>
							<textarea class="cta-form__textarea" name="message" rows="6" placeholder="<?php echo esc_attr( $message_placeholder ); ?>"></textarea>
						</label>

						<label class="cta-form__agree">
							<input class="cta-form__checkbox" type="checkbox" name="agree" checked="checked">
							<span class="cta-form__agree-ui" aria-hidden="true"></span>
							<span class="cta-form__agree-text"><?php echo wp_kses_post( $agreement_text ); ?></span>
						</label>

						<div class="cta-form__actions">
							<button class="btn btn--grey cta-form__submit" type="submit"><?php echo esc_html( $submit_label ); ?></button>
						</div>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
