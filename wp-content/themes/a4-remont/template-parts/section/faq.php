<?php
/**
 * FAQ section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/faq.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$items = get_sub_field( 'items' );

if ( empty( $items ) || ! is_array( $items ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$title      = (string) get_sub_field( 'section_title' );
$open_first = (bool) get_sub_field( 'open_first' );
$split_at   = (int) ceil( count( $items ) / 2 );
$columns    = array(
	array_slice( $items, 0, $split_at ),
	array_slice( $items, $split_at ),
);
?>
<section class="faq" data-faq="data-faq"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="faq__container _container">
		<?php if ( $title ) : ?>
			<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
		<?php endif; ?>
		<div class="faq__grid">
			<?php foreach ( $columns as $column_index => $column_items ) : ?>
				<div class="faq__col">
					<?php foreach ( $column_items as $item_index => $item ) : ?>
						<?php $is_open = $open_first && 0 === $column_index && 0 === $item_index; ?>
						<article class="faq-item<?php echo $is_open ? ' _active' : ''; ?>">
							<button class="faq-item__head" type="button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" data-faq-btn="data-faq-btn">
								<span class="faq-item__q"><?php echo esc_html( $item['question'] ?? '' ); ?></span>
								<span class="faq-item__icon" aria-hidden="true">
									<svg width="21" height="21" viewBox="0 0 21 21" fill="none" xmlns="http://www.w3.org/2000/svg">
										<path fill-rule="evenodd" clip-rule="evenodd" d="M10.1562 0C10.4795 0 10.7895 0.128404 11.018 0.356964C11.2466 0.585524 11.375 0.895517 11.375 1.21875V8.9375H19.0938C19.417 8.9375 19.727 9.0659 19.9555 9.29446C20.1841 9.52302 20.3125 9.83302 20.3125 10.1562C20.3125 10.4795 20.1841 10.7895 19.9555 11.018C19.727 11.2466 19.417 11.375 19.0938 11.375H11.375V19.0938C11.375 19.417 11.2466 19.727 11.018 19.9555C10.7895 20.1841 10.4795 20.3125 10.1562 20.3125C9.83302 20.3125 9.52302 20.1841 9.29446 19.9555C9.0659 19.727 8.9375 19.417 8.9375 19.0938V11.375H1.21875C0.895517 11.375 0.585524 11.2466 0.356964 11.018C0.128404 10.7895 0 10.4795 0 10.1562C0 9.83302 0.128404 9.52302 0.356964 9.29446C0.585524 9.0659 0.895517 8.9375 1.21875 8.9375H8.9375V1.21875C8.9375 0.895517 9.0659 0.585524 9.29446 0.356964C9.52302 0.128404 9.83302 0 10.1562 0Z" fill="#C09B57"></path>
									</svg>
								</span>
							</button>
							<div class="faq-item__body">
								<div class="faq-item__content">
									<p><?php echo nl2br( esc_html( $item['answer'] ?? '' ) ); ?></p>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
