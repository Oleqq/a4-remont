<?php
/**
 * Payment and guarantees hero section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/payment-delivery/payment-delivery-hero.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title      = (string) get_sub_field( 'title' );
$text       = (string) get_sub_field( 'text' );
$cta_button = get_sub_field( 'cta_button' );
$has_cta_button = a4_remont_has_sub_field_action_button( 'cta_button', $cta_button );

if ( '' === trim( $title ) && '' === trim( $text ) && ! $has_cta_button ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="payment-delivery-hero"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="payment-delivery-hero__container _container">
		<article class="payment-delivery-hero__card">
			<div class="payment-delivery-hero__grid">
				<div class="payment-delivery-hero__main">
					<?php if ( $title ) : ?>
						<h1 class="payment-delivery-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
					<?php endif; ?>
				</div>

				<div class="payment-delivery-hero__side">
					<?php if ( $text ) : ?>
						<p class="payment-delivery-hero__text"><?php echo nl2br( esc_html( $text ) ); ?></p>
					<?php endif; ?>

					<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey payment-delivery-hero__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			</div>
		</article>
	</div>
</section>
