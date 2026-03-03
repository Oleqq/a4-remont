<?php
/**
 * FAQ page accordion section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/faq-page/faq-secondary.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$items = get_sub_field( 'items' );

if ( empty( $items ) || ! is_array( $items ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$normalized_items = array_values(
	array_filter(
		$items,
		static function ( $item ) {
			$question = isset( $item['question'] ) ? trim( wp_strip_all_tags( (string) $item['question'] ) ) : '';
			$answer   = isset( $item['answer'] ) ? trim( wp_strip_all_tags( (string) $item['answer'] ) ) : '';

			return '' !== $question || '' !== $answer;
		}
	)
);

if ( empty( $normalized_items ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$open_first = (bool) get_sub_field( 'open_first' );
?>
<section class="faq-secondary"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?> data-faq>
	<div class="faq-secondary__container _container">
		<div class="faq-secondary__list">
			<?php foreach ( $normalized_items as $index => $item ) : ?>
				<?php
				$is_open  = $open_first && 0 === $index;
				$question = isset( $item['question'] ) ? (string) $item['question'] : '';
				$answer   = isset( $item['answer'] ) ? (string) $item['answer'] : '';
				?>
				<article class="faq-item<?php echo $is_open ? ' _active' : ''; ?>">
					<button class="faq-secondary__head" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" data-faq-btn>
						<span class="faq-secondary__question"><?php echo esc_html( $question ); ?></span>
						<span class="faq-secondary__icon" aria-hidden="true"></span>
					</button>
					<div class="faq-secondary__tooltip">
						<div class="faq-secondary__tooltip-inner">
							<?php echo wp_kses_post( $answer ); ?>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
