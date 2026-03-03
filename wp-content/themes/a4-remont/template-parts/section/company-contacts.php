<?php
/**
 * Company contacts section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/about-us/company-contacts.html' );
	return;
}

$title   = (string) get_sub_field( 'section_title' );
$phone   = (string) get_sub_field( 'phone' );
$email   = (string) get_sub_field( 'email' );
$address = (string) get_sub_field( 'address' );

if ( '' === trim( $title ) && '' === trim( $phone ) && '' === trim( $email ) && '' === trim( $address ) ) {
	a4_remont_render_static_markup( 'section/about-us/company-contacts.html' );
	return;
}

$section_id   = sanitize_title( (string) get_sub_field( 'section_id' ) );
$left_text    = (string) get_sub_field( 'left_text' );
$right_text   = (string) get_sub_field( 'right_text' );
$address_url  = (string) get_sub_field( 'address_url' );
$telegram_url = (string) get_sub_field( 'telegram_url' );
$vk_url       = (string) get_sub_field( 'vk_url' );
$reviews_url  = (string) get_sub_field( 'reviews_url' );
$phone_href   = $phone ? 'tel:' . preg_replace( '/[^\d+]/', '', $phone ) : '';
?>
<section class="company-contacts"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="company-contacts__container _container">
		<div class="company-contacts__head">
			<?php if ( $title ) : ?>
				<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>
		</div>

		<div class="company-contacts__cols">
			<?php if ( $left_text ) : ?>
				<div class="company-contacts__col company-contacts__col--left">
					<p class="company-contacts__text"><?php echo nl2br( esc_html( $left_text ) ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $right_text ) : ?>
				<div class="company-contacts__col company-contacts__col--right">
					<p class="company-contacts__text"><?php echo nl2br( esc_html( $right_text ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<div class="company-contacts__grid">
			<?php if ( $phone && $phone_href ) : ?>
				<a class="company-contacts__card company-contacts__card--phone" href="<?php echo esc_url( $phone_href ); ?>">
					<span class="company-contacts__icon company-contacts__icon--phone" aria-hidden="true"></span>
					<span class="company-contacts__value"><?php echo esc_html( $phone ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( $email ) : ?>
				<a class="company-contacts__card company-contacts__card--mail" href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>">
					<span class="company-contacts__icon company-contacts__icon--mail" aria-hidden="true"></span>
					<span class="company-contacts__value"><?php echo esc_html( $email ); ?></span>
				</a>
			<?php endif; ?>

			<?php if ( $address ) : ?>
				<a class="company-contacts__card company-contacts__card--addr" href="<?php echo esc_url( $address_url ? $address_url : '#' ); ?>"<?php echo $address_url ? ' target="_blank" rel="noopener"' : ''; ?>>
					<span class="company-contacts__icon company-contacts__icon--pin" aria-hidden="true"></span>
					<span class="company-contacts__value"><?php echo nl2br( esc_html( $address ) ); ?></span>
				</a>
			<?php endif; ?>

			<div class="company-contacts__social">
				<?php if ( $telegram_url ) : ?>
					<a class="company-contacts__soc company-contacts__soc--tg" href="<?php echo esc_url( $telegram_url ); ?>" aria-label="Telegram">
						<span class="company-contacts__soc-ico" aria-hidden="true">TG</span>
					</a>
				<?php endif; ?>

				<?php if ( $vk_url ) : ?>
					<a class="company-contacts__soc company-contacts__soc--vk" href="<?php echo esc_url( $vk_url ); ?>" aria-label="VK">
						<span class="company-contacts__soc-ico" aria-hidden="true">VK</span>
					</a>
				<?php endif; ?>

				<?php if ( $reviews_url ) : ?>
					<a class="company-contacts__soc company-contacts__soc--r" href="<?php echo esc_url( $reviews_url ); ?>" aria-label="Отзывы">
						<span class="company-contacts__soc-ico" aria-hidden="true">R</span>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
