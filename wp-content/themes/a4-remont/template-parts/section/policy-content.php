<?php
/**
 * Privacy policy content section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/privacy-policy/policy-content.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$title         = trim( (string) get_sub_field( 'title' ) );
$intro_content = (string) get_sub_field( 'intro_content' );
$blocks        = get_sub_field( 'blocks' );

$normalized_blocks = array();

if ( is_array( $blocks ) ) {
	foreach ( $blocks as $block ) {
		$subtitle = isset( $block['subtitle'] ) ? trim( (string) $block['subtitle'] ) : '';
		$content  = isset( $block['content'] ) ? trim( (string) $block['content'] ) : '';

		if ( '' === $subtitle && '' === wp_strip_all_tags( $content ) ) {
			continue;
		}

		$normalized_blocks[] = array(
			'subtitle' => $subtitle,
			'content'  => $content,
		);
	}
}

if ( '' === $title && '' === trim( wp_strip_all_tags( $intro_content ) ) && empty( $normalized_blocks ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="policy-content"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="policy-content__container _container">
		<div class="policy-content__inner">
			<?php if ( $title ) : ?>
				<h1 class="section__title policy-content__title"><?php echo esc_html( $title ); ?></h1>
			<?php endif; ?>

			<?php if ( '' !== trim( wp_strip_all_tags( $intro_content ) ) ) : ?>
				<div class="policy-content__text">
					<?php echo wp_kses_post( $intro_content ); ?>
				</div>
			<?php endif; ?>

			<?php foreach ( $normalized_blocks as $block ) : ?>
				<?php if ( $block['subtitle'] ) : ?>
					<h2 class="policy-content__subtitle"><?php echo esc_html( $block['subtitle'] ); ?></h2>
				<?php endif; ?>
				<div class="policy-content__text">
					<?php echo wp_kses_post( $block['content'] ); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
