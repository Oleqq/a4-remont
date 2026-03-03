<?php
/**
 * Reviews archive media section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/reviews/reviews-feedback.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$photo_posts = a4_remont_get_feedback_posts_by_type(
	'photo',
	array(
		'source_mode'  => get_sub_field( 'photo_source_mode' ),
		'manual_items' => get_sub_field( 'manual_photo_items' ),
		'items_limit'  => get_sub_field( 'photo_items_limit' ),
	)
);

$video_posts = a4_remont_get_feedback_posts_by_type(
	'video',
	array(
		'source_mode'  => get_sub_field( 'video_source_mode' ),
		'manual_items' => get_sub_field( 'manual_video_items' ),
		'items_limit'  => get_sub_field( 'video_items_limit' ),
	)
);

$title = trim( (string) get_sub_field( 'section_title' ) );

if ( '' === $title && empty( $photo_posts ) && empty( $video_posts ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id        = sanitize_title( (string) get_sub_field( 'section_id' ) );
$photos_more_label = trim( (string) get_sub_field( 'photos_more_label' ) );
$video_title       = trim( (string) get_sub_field( 'video_title' ) );
$initial_desktop   = (int) get_sub_field( 'initial_desktop' );
$initial_tablet    = (int) get_sub_field( 'initial_tablet' );
$initial_mobile    = (int) get_sub_field( 'initial_mobile' );
$load_desktop      = (int) get_sub_field( 'load_desktop' );
$load_tablet       = (int) get_sub_field( 'load_tablet' );
$load_mobile       = (int) get_sub_field( 'load_mobile' );

if ( '' === $title ) {
	$title = 'Отзывы на ремонтные работы и дизайн проекты';
}

if ( '' === $photos_more_label ) {
	$photos_more_label = 'Смотреть больше отзывов';
}

if ( '' === $video_title ) {
	$video_title = 'Видео-отзывы';
}

if ( $initial_desktop < 1 ) {
	$initial_desktop = 8;
}

if ( $initial_tablet < 1 ) {
	$initial_tablet = 6;
}

if ( $initial_mobile < 1 ) {
	$initial_mobile = 4;
}

if ( $load_desktop < 1 ) {
	$load_desktop = 4;
}

if ( $load_tablet < 1 ) {
	$load_tablet = 3;
}

if ( $load_mobile < 1 ) {
	$load_mobile = 2;
}
?>
<section
	class="reviews-feedback"
	data-reviews-photos="data-reviews-photos"
	data-reviews-video="data-reviews-video"
	data-initial-desktop="<?php echo esc_attr( $initial_desktop ); ?>"
	data-initial-tablet="<?php echo esc_attr( $initial_tablet ); ?>"
	data-initial-mobile="<?php echo esc_attr( $initial_mobile ); ?>"
	data-load-desktop="<?php echo esc_attr( $load_desktop ); ?>"
	data-load-tablet="<?php echo esc_attr( $load_tablet ); ?>"
	data-load-mobile="<?php echo esc_attr( $load_mobile ); ?>"
	<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>
>
	<div class="reviews-feedback__container _container">
		<h1 class="section__title reviews-feedback__title"><?php echo esc_html( $title ); ?></h1>

		<?php if ( ! empty( $photo_posts ) ) : ?>
			<div class="reviews-feedback__photos-grid">
				<?php foreach ( $photo_posts as $feedback_post ) : ?>
					<?php
					$photo_preview_url = a4_remont_get_feedback_photo_preview_url( $feedback_post );
					$photo_full_url    = a4_remont_get_feedback_photo_full_url( $feedback_post );
					$photo_href        = $photo_full_url ? $photo_full_url : $photo_preview_url;
					$photo_style       = '';

					if ( $photo_preview_url ) {
						$photo_style = sprintf(
							'background-image: linear-gradient(0deg, rgba(34,34,34,0.14), rgba(34,34,34,0.14)), url(%1$s); background-size: cover; background-position: center;',
							esc_url( $photo_preview_url )
						);
					}
					?>
					<a
						class="reviews-feedback__photo-item"
						href="<?php echo esc_url( $photo_href ? $photo_href : '#' ); ?>"
						data-review-photo-item="data-review-photo-item"
						aria-label="<?php echo esc_attr( sprintf( 'Открыть фото-отзыв: %s', get_the_title( $feedback_post ) ) ); ?>"
						<?php echo $photo_style ? ' style="' . esc_attr( $photo_style ) . '"' : ''; ?>
						<?php echo $photo_href ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
					>
						<span class="reviews-feedback__photo-icon" aria-hidden="true"></span>
					</a>
				<?php endforeach; ?>
			</div>

			<div class="reviews-feedback__photos-bottom">
				<button class="btn btn--grey reviews-feedback__photos-more" type="button" data-reviews-photos-more="data-reviews-photos-more"><?php echo esc_html( $photos_more_label ); ?></button>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $video_posts ) ) : ?>
			<div class="reviews-feedback__video">
				<h2 class="section__title reviews-feedback__video-title"><?php echo esc_html( $video_title ); ?></h2>

				<div class="reviews-feedback__video-slider swiper" data-reviews-video-slider="data-reviews-video-slider">
					<div class="swiper-wrapper">
						<?php foreach ( $video_posts as $feedback_post ) : ?>
							<?php
							$video_preview_url = a4_remont_get_feedback_video_preview_url( $feedback_post );
							$video_url         = a4_remont_get_feedback_video_url( $feedback_post );
							$video_href        = $video_url ? $video_url : $video_preview_url;
							$video_style       = '';

							if ( $video_preview_url ) {
								$video_style = sprintf(
									'background-image: linear-gradient(0deg, rgba(34,34,34,0.16), rgba(34,34,34,0.16)), url(%1$s); background-size: cover; background-position: center;',
									esc_url( $video_preview_url )
								);
							}
							?>
							<div class="swiper-slide">
								<a
									class="reviews-feedback__video-item"
									href="<?php echo esc_url( $video_href ? $video_href : '#' ); ?>"
									aria-label="<?php echo esc_attr( sprintf( 'Смотреть видео-отзыв: %s', get_the_title( $feedback_post ) ) ); ?>"
									<?php echo $video_style ? ' style="' . esc_attr( $video_style ) . '"' : ''; ?>
									<?php echo $video_href ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>
								>
									<span class="reviews-feedback__video-icon" aria-hidden="true"></span>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="reviews-feedback__video-nav">
					<button class="reviews-feedback__video-btn reviews-feedback__video-btn--prev" type="button" aria-label="Предыдущий слайд">
						<span class="reviews-feedback__video-btn-icon reviews-feedback__video-btn-icon--prev" aria-hidden="true"></span>
					</button>

					<div class="reviews-feedback__video-scrollbar-wrap">
						<div class="reviews-feedback__video-scrollbar swiper-scrollbar"></div>
					</div>

					<button class="reviews-feedback__video-btn reviews-feedback__video-btn--next" type="button" aria-label="Следующий слайд">
						<span class="reviews-feedback__video-btn-icon reviews-feedback__video-btn-icon--next" aria-hidden="true"></span>
					</button>
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
