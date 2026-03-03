<?php
/**
 * News latest section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/news-latest.html' );
	return;
}

$section_posts = a4_remont_get_news_section_posts();
$title         = (string) get_sub_field( 'section_title' );

if ( '' === trim( $title ) && empty( $section_posts ) ) {
	a4_remont_render_static_markup( 'section/news-latest.html' );
	return;
}

$section_id     = sanitize_title( (string) get_sub_field( 'section_id' ) );
$text           = (string) get_sub_field( 'section_text' );
$archive_button = get_sub_field( 'archive_button' );

if ( empty( $archive_button ) && post_type_exists( 'news' ) ) {
	$archive_link = get_post_type_archive_link( 'news' );

	if ( $archive_link ) {
		$archive_button = array(
			'url'    => $archive_link,
			'title'  => 'Все новости',
			'target' => '',
		);
	}
}
?>
<section class="news-latest"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="news-latest__container _container">
		<div class="news-latest__head">
			<div class="news-latest__info">
				<?php if ( $title ) : ?>
					<h2 class="section__title news-latest__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $text ) : ?>
					<p class="section__subtitle news-latest__lead"><?php echo nl2br( esc_html( $text ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php echo a4_remont_get_acf_link_html( $archive_button, 'btn btn--grey news-latest__btn news-latest__btn--desktop' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>

		<div class="news-latest__grid">
			<?php foreach ( $section_posts as $news_post ) : ?>
				<?php $terms = a4_remont_get_post_term_names( $news_post->ID, 'news_category', 2 ); ?>
				<article class="news-latest-card">
					<a class="news-latest-card__link" href="<?php echo esc_url( get_permalink( $news_post ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $news_post ) ); ?>">
						<div class="news-latest-card__media">
							<?php echo get_the_post_thumbnail( $news_post, 'large', array( 'class' => 'news-latest-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php if ( $terms ) : ?>
								<div class="news-latest-card__tags">
									<?php foreach ( $terms as $term_name ) : ?>
										<span class="news-latest-card__tag"><?php echo esc_html( $term_name ); ?></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
						<div class="news-latest-card__bottom">
							<h3 class="news-latest-card__title"><?php echo esc_html( get_the_title( $news_post ) ); ?></h3>
							<span class="news-latest-card__more">View</span>
						</div>
					</a>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="news-latest__bottom">
			<?php echo a4_remont_get_acf_link_html( $archive_button, 'btn btn--grey news-latest__btn news-latest__btn--mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
