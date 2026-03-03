<?php
/**
 * Payment and guarantees stages section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/payment-delivery/payment-delivery-stages.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title = (string) get_sub_field( 'section_title' );
$lead  = (string) get_sub_field( 'lead' );
$items = get_sub_field( 'items' );

if ( '' === trim( $title ) && '' === trim( $lead ) && empty( $items ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id  = sanitize_title( (string) get_sub_field( 'section_id' ) );
$desktop     = get_sub_field( 'line_desktop' );
$tablet      = get_sub_field( 'line_tablet' );
$mobile      = get_sub_field( 'line_mobile' );
$line_alt    = (string) get_sub_field( 'line_alt' );
$line_alt    = '' !== $line_alt ? $line_alt : 'Линия этапов оплаты';
?>
<section class="payment-delivery-stages"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="payment-delivery-stages__container _container">
		<div class="payment-delivery-stages__panel">
			<div class="payment-delivery-stages__top">
				<?php if ( $title ) : ?>
					<h2 class="section__title payment-delivery-stages__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $lead ) : ?>
					<p class="section__subtitle payment-delivery-stages__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
				<?php endif; ?>
			</div>

			<div class="payment-delivery-stages__timeline">
				<?php if ( ! empty( $items ) && is_array( $items ) ) : ?>
					<div class="payment-delivery-stages__list">
						<?php foreach ( $items as $item ) : ?>
							<?php
							$item_title = isset( $item['item_title'] ) ? (string) $item['item_title'] : '';
							$item_text  = isset( $item['item_text'] ) ? (string) $item['item_text'] : '';
							?>
							<article class="payment-delivery-stages__card">
								<?php if ( $item_title ) : ?>
									<h3 class="payment-delivery-stages__card-title"><?php echo esc_html( $item_title ); ?></h3>
								<?php endif; ?>

								<?php if ( $item_text ) : ?>
									<p class="payment-delivery-stages__card-text"><?php echo nl2br( esc_html( $item_text ) ); ?></p>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $desktop || $tablet || $mobile ) : ?>
					<div class="payment-delivery-stages__line">
						<?php if ( $desktop ) : ?>
							<?php echo a4_remont_get_acf_image_html( $desktop, 'full', array( 'class' => 'payment-delivery-stages__line-desktop', 'loading' => 'lazy', 'alt' => $line_alt, 'title' => $line_alt ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>

						<?php if ( $tablet ) : ?>
							<?php echo a4_remont_get_acf_image_html( $tablet, 'full', array( 'class' => 'payment-delivery-stages__line-tablet', 'loading' => 'lazy', 'alt' => $line_alt, 'title' => $line_alt ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>

						<?php if ( $mobile ) : ?>
							<?php echo a4_remont_get_acf_image_html( $mobile, 'full', array( 'class' => 'payment-delivery-stages__line-mobile', 'loading' => 'lazy', 'alt' => $line_alt, 'title' => $line_alt ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
