<?php
/**
 * Single service housing types section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/service-single/service-housing-types.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$section_lead  = (string) get_sub_field( 'section_lead' );
$items         = get_sub_field( 'items' );

if ( '' === trim( $section_title ) && '' === trim( $section_lead ) && empty( $items ) ) {
	a4_remont_render_static_markup( 'section/service-single/service-housing-types.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="service-housing-types"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-housing-types__container _container">
		<div class="service-housing-types__top">
			<?php if ( $section_title ) : ?>
				<h2 class="section__title service-housing-types__title"><?php echo wp_kses_post( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_lead ) : ?>
				<p class="section__subtitle service-housing-types__lead"><?php echo nl2br( esc_html( $section_lead ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( ! empty( $items ) ) : ?>
			<div class="service-housing-types__grid">
				<?php foreach ( (array) $items as $item ) : ?>
					<?php
					if ( ! is_array( $item ) ) {
						continue;
					}

					$item_title = ! empty( $item['item_title'] ) ? (string) $item['item_title'] : '';
					$item_text  = ! empty( $item['item_text'] ) ? (string) $item['item_text'] : '';
					$item_image = ! empty( $item['item_image'] ) ? $item['item_image'] : null;

					if ( '' === trim( $item_title ) && '' === trim( $item_text ) && empty( $item_image ) ) {
						continue;
					}
					?>
					<article class="service-housing-types__card">
						<div class="service-housing-types__badge service-stream__top-left">
							<?php if ( $item_title ) : ?>
								<p class="service-stream__top-title"><?php echo esc_html( $item_title ); ?></p>
							<?php endif; ?>

							<?php if ( $item_text ) : ?>
								<p class="service-stream__top-text"><?php echo nl2br( esc_html( $item_text ) ); ?></p>
							<?php endif; ?>
						</div>

						<?php if ( $item_image ) : ?>
							<?php echo a4_remont_get_acf_image_html( $item_image, 'large', array( 'class' => 'service-housing-types__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
