<?php
/**
 * Single news content section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/news-single/news-single-content.html' );
	return;
}

$blocks    = get_sub_field( 'blocks' );
$news_post = get_post();

if ( empty( $blocks ) && ( ! $news_post instanceof WP_Post || '' === trim( wp_strip_all_tags( (string) $news_post->post_content ) ) ) ) {
	a4_remont_render_static_markup( 'section/news-single/news-single-content.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="news-single-content"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="news-single-content__container _container">
		<div class="news-single-content__inner">
			<?php if ( ! empty( $blocks ) && is_array( $blocks ) ) : ?>
				<?php foreach ( $blocks as $block ) : ?>
					<?php
					$title              = ! empty( $block['title'] ) ? (string) $block['title'] : '';
					$intro_content      = ! empty( $block['intro_content'] ) ? (string) $block['intro_content'] : '';
					$highlight_content  = ! empty( $block['highlight_content'] ) ? (string) $block['highlight_content'] : '';
					$list_type          = ! empty( $block['list_type'] ) ? (string) $block['list_type'] : '';
					$list_items         = ! empty( $block['list_items'] ) && is_array( $block['list_items'] ) ? $block['list_items'] : array();
					$media_type         = ! empty( $block['media_type'] ) ? (string) $block['media_type'] : '';
					$image              = ! empty( $block['image'] ) ? $block['image'] : null;
					$gallery            = ! empty( $block['gallery'] ) && is_array( $block['gallery'] ) ? $block['gallery'] : array();
					$caption            = ! empty( $block['caption'] ) ? (string) $block['caption'] : '';
					$after_media_content = ! empty( $block['after_media_content'] ) ? (string) $block['after_media_content'] : '';

					if ( '' === trim( $title ) && '' === trim( wp_strip_all_tags( $intro_content ) ) && '' === trim( wp_strip_all_tags( $highlight_content ) ) && empty( $list_items ) && empty( $image ) && empty( $gallery ) && '' === trim( $caption ) && '' === trim( wp_strip_all_tags( $after_media_content ) ) ) {
						continue;
					}
					?>
					<article class="news-single-content__block">
						<?php if ( $title ) : ?>
							<h2 class="news-single-content__title"><?php echo esc_html( $title ); ?></h2>
						<?php endif; ?>

						<?php if ( '' !== trim( wp_strip_all_tags( $intro_content ) ) ) : ?>
							<div class="news-single-content__text">
								<?php echo wp_kses_post( $intro_content ); ?>
							</div>
						<?php endif; ?>

						<?php if ( '' !== trim( wp_strip_all_tags( $highlight_content ) ) ) : ?>
							<div class="news-single-content__subtitle">
								<?php echo wp_kses_post( $highlight_content ); ?>
							</div>
						<?php endif; ?>

						<?php if ( ! empty( $list_items ) && in_array( $list_type, array( 'ul', 'ol' ), true ) ) : ?>
							<?php $list_tag = $list_type; ?>
							<<?php echo esc_html( $list_tag ); ?> class="news-single-content__list">
								<?php foreach ( $list_items as $list_item ) : ?>
									<?php $item_text = ! empty( $list_item['item_text'] ) ? (string) $list_item['item_text'] : ''; ?>
									<?php if ( '' === trim( $item_text ) ) : ?>
										<?php continue; ?>
									<?php endif; ?>
									<li><?php echo esc_html( $item_text ); ?></li>
								<?php endforeach; ?>
							</<?php echo esc_html( $list_tag ); ?>>
						<?php endif; ?>

						<?php if ( 'image' === $media_type && $image ) : ?>
							<figure class="news-single-content__figure">
								<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'news-single-content__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</figure>
						<?php elseif ( 'gallery' === $media_type && ! empty( $gallery ) ) : ?>
							<figure class="news-single-content__gallery">
								<?php foreach ( $gallery as $gallery_image ) : ?>
									<?php echo a4_remont_get_acf_image_html( $gallery_image, 'large', array( 'class' => 'news-single-content__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php endforeach; ?>
							</figure>
						<?php endif; ?>

						<?php if ( $caption ) : ?>
							<p class="news-single-content__caption"><?php echo nl2br( esc_html( $caption ) ); ?></p>
						<?php endif; ?>

						<?php if ( '' !== trim( wp_strip_all_tags( $after_media_content ) ) ) : ?>
							<div class="news-single-content__text">
								<?php echo wp_kses_post( $after_media_content ); ?>
							</div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			<?php elseif ( $news_post instanceof WP_Post && '' !== trim( wp_strip_all_tags( (string) $news_post->post_content ) ) ) : ?>
				<article class="news-single-content__block">
					<div class="news-single-content__text">
						<?php echo apply_filters( 'the_content', $news_post->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</article>
			<?php endif; ?>
		</div>
	</div>
</section>
