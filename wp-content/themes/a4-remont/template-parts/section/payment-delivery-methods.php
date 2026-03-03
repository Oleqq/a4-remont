<?php
/**
 * Payment and guarantees methods section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/payment-delivery/payment-delivery-methods.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title = (string) get_sub_field( 'section_title' );
$lead  = (string) get_sub_field( 'lead' );
$image = get_sub_field( 'image' );
$cards = get_sub_field( 'cards' );

if ( '' === trim( $title ) && '' === trim( $lead ) && empty( $image ) && empty( $cards ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$image_alt  = (string) get_sub_field( 'image_alt' );

if ( '' === $image_alt && is_array( $image ) && ! empty( $image['alt'] ) ) {
	$image_alt = (string) $image['alt'];
}

if ( '' === $image_alt ) {
	$image_alt = 'Оплата ремонта';
}
?>
<section class="payment-delivery-methods"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="payment-delivery-methods__container _container">
		<div class="payment-delivery-methods__grid">
			<?php if ( $image ) : ?>
				<div class="payment-delivery-methods__media">
					<?php echo a4_remont_get_acf_image_html( $image, 'large', array( 'class' => 'payment-delivery-methods__image', 'loading' => 'lazy', 'alt' => $image_alt, 'title' => $image_alt ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>

			<div class="payment-delivery-methods__content">
				<?php if ( $title ) : ?>
					<h2 class="section__title payment-delivery-methods__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $lead ) : ?>
					<p class="section__subtitle payment-delivery-methods__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $cards ) && is_array( $cards ) ) : ?>
					<div class="payment-delivery-methods__list">
						<?php foreach ( $cards as $card ) : ?>
							<?php
							$card_title = isset( $card['card_title'] ) ? (string) $card['card_title'] : '';
							$card_text  = isset( $card['card_text'] ) ? (string) $card['card_text'] : '';
							?>
							<article class="payment-delivery-methods__card">
								<?php if ( $card_title ) : ?>
									<h3 class="payment-delivery-methods__card-title"><?php echo esc_html( $card_title ); ?></h3>
								<?php endif; ?>

								<?php if ( $card_text ) : ?>
									<p class="payment-delivery-methods__card-text"><?php echo nl2br( esc_html( $card_text ) ); ?></p>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
