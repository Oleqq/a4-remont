<?php
/**
 * News archive preview section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/news/news-preview.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$news_posts = a4_remont_get_news_archive_posts();

if ( empty( $news_posts ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id        = sanitize_title( (string) get_sub_field( 'section_id' ) );
$section_title     = (string) get_sub_field( 'section_title' );
$section_lead      = (string) get_sub_field( 'section_lead' );
$read_label        = (string) get_sub_field( 'read_label' );
$more_button_label = (string) get_sub_field( 'more_button_label' );
$initial_desktop   = (int) get_sub_field( 'initial_desktop' );
$initial_tablet    = (int) get_sub_field( 'initial_tablet' );
$initial_mobile    = (int) get_sub_field( 'initial_mobile' );
$load_desktop      = (int) get_sub_field( 'load_desktop' );
$load_tablet       = (int) get_sub_field( 'load_tablet' );
$load_mobile       = (int) get_sub_field( 'load_mobile' );

if ( '' === trim( $section_title ) ) {
	$section_title = 'Новости и статьи в сфере ремонта и дизайна';
}

if ( '' === trim( $section_lead ) ) {
	$section_lead = 'В нашем блоге - полезные статьи о ремонте и дизайне: разбираем нюансы перепланировки, выбираем материалы и находим стильные решения для любого бюджета.';
}

if ( '' === trim( $read_label ) ) {
	$read_label = 'Читать';
}

if ( '' === trim( $more_button_label ) ) {
	$more_button_label = 'Больше новостей';
}

if ( $initial_desktop < 1 ) {
	$initial_desktop = 3;
}

if ( $initial_tablet < 1 ) {
	$initial_tablet = 3;
}

if ( $initial_mobile < 1 ) {
	$initial_mobile = 3;
}

if ( $load_desktop < 1 ) {
	$load_desktop = 3;
}

if ( $load_tablet < 1 ) {
	$load_tablet = 3;
}

if ( $load_mobile < 1 ) {
	$load_mobile = 3;
}
?>
<section
	class="news-preview"
	data-news-preview="data-news-preview"
	data-initial-desktop="<?php echo esc_attr( $initial_desktop ); ?>"
	data-initial-tablet="<?php echo esc_attr( $initial_tablet ); ?>"
	data-initial-mobile="<?php echo esc_attr( $initial_mobile ); ?>"
	data-load-desktop="<?php echo esc_attr( $load_desktop ); ?>"
	data-load-tablet="<?php echo esc_attr( $load_tablet ); ?>"
	data-load-mobile="<?php echo esc_attr( $load_mobile ); ?>"
	<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>
>
	<div class="news-preview__container _container">
		<div class="news-preview__top">
			<?php if ( $section_title ) : ?>
				<h2 class="section__title news-preview__title"><?php echo wp_kses_post( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_lead ) : ?>
				<p class="section__subtitle news-preview__lead"><?php echo nl2br( esc_html( $section_lead ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="news-preview__grid">
			<?php foreach ( $news_posts as $news_post ) : ?>
				<?php
				$image_html = get_the_post_thumbnail(
					$news_post,
					'large',
					array(
						'class'   => 'news-card__img',
						'loading' => 'lazy',
					)
				);
				$terms   = a4_remont_get_post_term_names( $news_post->ID, 'news_category', 2 );
				$excerpt = a4_remont_get_post_excerpt( $news_post, 26 );
				?>
				<a class="news-card" href="<?php echo esc_url( get_permalink( $news_post ) ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Читать новость: %s', get_the_title( $news_post ) ) ); ?>">
					<div class="news-card__media">
						<?php if ( $image_html ) : ?>
							<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php endif; ?>

						<div class="news-card__top">
							<?php if ( $terms ) : ?>
								<div class="news-card__tags">
									<?php foreach ( $terms as $term_name ) : ?>
										<span class="news-card__tag"><?php echo esc_html( $term_name ); ?></span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<span class="news-card__read"><?php echo esc_html( $read_label ); ?></span>
						</div>
					</div>

					<div class="news-card__content">
						<time class="news-card__date" datetime="<?php echo esc_attr( get_the_date( 'Y-m-d', $news_post ) ); ?>"><?php echo esc_html( get_the_date( 'd.m.y', $news_post ) ); ?></time>
						<h3 class="news-card__title"><?php echo esc_html( get_the_title( $news_post ) ); ?></h3>

						<?php if ( $excerpt ) : ?>
							<p class="news-card__text"><?php echo esc_html( $excerpt ); ?></p>
						<?php endif; ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="news-preview__bottom">
			<button class="btn btn--primary news-preview__more" type="button" data-news-more="data-news-more"><?php echo esc_html( $more_button_label ); ?></button>
		</div>
	</div>
</section>
