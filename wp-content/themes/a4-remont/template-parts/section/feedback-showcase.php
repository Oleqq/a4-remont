<?php
/**
 * Feedback showcase section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/feedback-showcase.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_posts = a4_remont_get_feedback_showcase_posts();
$title         = (string) get_sub_field( 'section_title' );

if ( '' === trim( $title ) && empty( $section_posts ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id     = sanitize_title( (string) get_sub_field( 'section_id' ) );
$lead           = (string) get_sub_field( 'section_lead' );
$archive_button = get_sub_field( 'archive_button' );

if ( empty( $archive_button ) && post_type_exists( 'feedback' ) ) {
	$archive_link = get_post_type_archive_link( 'feedback' );

	if ( $archive_link ) {
		$archive_button = array(
			'url'    => $archive_link,
			'title'  => 'Смотреть больше отзывов',
			'target' => '',
		);
	}
}
?>
<section class="feedback-showcase"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="feedback-showcase__container _container">
		<div class="feedback-showcase__head">
			<?php if ( $title ) : ?>
				<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
			<?php endif; ?>

			<?php if ( $lead ) : ?>
				<p class="feedback-showcase__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="feedback-showcase__body">
			<div class="feedback-showcase__slider swiper" data-reviews-swiper="data-reviews-swiper">
				<div class="feedback-showcase__track swiper-wrapper">
					<?php foreach ( $section_posts as $feedback_post ) : ?>
						<div class="feedback-showcase__slide swiper-slide">
							<article class="review-card">
								<div class="review-card__top">
									<h3 class="review-card__name"><?php echo esc_html( get_the_title( $feedback_post ) ); ?></h3>
									<?php echo a4_remont_render_rating_stars( a4_remont_get_feedback_rating( $feedback_post ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>

								<p class="review-card__text"><?php echo esc_html( a4_remont_get_post_excerpt( $feedback_post, 38 ) ); ?></p>

								<?php
								$display_date = a4_remont_get_feedback_display_date( $feedback_post );
								if ( $display_date ) :
									?>
									<time class="review-card__date" datetime="<?php echo esc_attr( get_the_date( 'c', $feedback_post ) ); ?>"><?php echo esc_html( $display_date ); ?></time>
								<?php endif; ?>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

		<div class="feedback-showcase__footer">
			<?php echo a4_remont_get_acf_link_html( $archive_button, 'btn btn--grey feedback-showcase__cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
