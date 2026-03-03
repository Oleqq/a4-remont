<?php
/**
 * Payment and guarantees warranty cases section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/payment-delivery/payment-delivery-cases.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title = (string) get_sub_field( 'section_title' );
$text  = (string) get_sub_field( 'section_text' );

if ( '' === trim( $title ) && '' === trim( $text ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="payment-delivery-cases"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="payment-delivery-cases__container _container">
		<?php if ( $title ) : ?>
			<h2 class="section__title payment-delivery-cases__title"><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>

		<?php if ( $text ) : ?>
			<p class="section__subtitle payment-delivery-cases__text"><?php echo nl2br( esc_html( $text ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
