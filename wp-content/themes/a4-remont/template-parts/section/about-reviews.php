<?php
/**
 * About reviews section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/about-reviews.html' );
	return;
}

$reviews = get_sub_field( 'reviews' );

if ( empty( $reviews ) || ! is_array( $reviews ) ) {
	a4_remont_render_static_markup( 'section/about-reviews.html' );
	return;
}

$section_id       = sanitize_title( (string) get_sub_field( 'section_id' ) );
$background_image = get_sub_field( 'background_image' );
$company_title    = (string) get_sub_field( 'company_title' );
$company_lead     = (string) get_sub_field( 'company_lead' );
$company_text     = (string) get_sub_field( 'company_text' );
$company_logo     = get_sub_field( 'company_logo' );
$company_button   = get_sub_field( 'company_button' );
$reviews_title    = (string) get_sub_field( 'reviews_title' );
$reviews_text     = (string) get_sub_field( 'reviews_text' );
$reviews_button   = get_sub_field( 'reviews_button' );
?>
<section class="about-reviews"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<?php if ( $background_image ) : ?>
		<div class="about-reviews__bg">
			<?php echo a4_remont_get_acf_image_html( $background_image, 'full', array( 'class' => 'about-reviews__bg-img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	<?php endif; ?>
	<div class="about-reviews__container _container">
		<article class="about-reviews__company">
			<?php if ( $company_title ) : ?>
				<h3 class="about-reviews__company-title"><?php echo wp_kses_post( $company_title ); ?></h3>
			<?php endif; ?>
			<?php if ( $company_lead ) : ?>
				<p class="about-reviews__company-strong"><?php echo nl2br( esc_html( $company_lead ) ); ?></p>
			<?php endif; ?>
			<?php if ( $company_text ) : ?>
				<p class="about-reviews__company-text"><?php echo nl2br( esc_html( $company_text ) ); ?></p>
			<?php endif; ?>
			<?php if ( $company_logo ) : ?>
				<div class="about-reviews__logo">
					<?php echo a4_remont_get_acf_image_html( $company_logo, 'full', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endif; ?>
			<?php echo a4_remont_get_acf_link_html( $company_button, 'about-reviews__company-btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</article>

		<div class="about-reviews__reviews">
			<div class="about-reviews__reviews-head">
				<?php if ( $reviews_title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $reviews_title ); ?></h2>
				<?php endif; ?>
				<?php if ( $reviews_text ) : ?>
					<p class="section__subtitle"><?php echo nl2br( esc_html( $reviews_text ) ); ?></p>
				<?php endif; ?>
			</div>
			<div class="about-reviews__cards">
				<?php foreach ( array_values( $reviews ) as $index => $review ) : ?>
					<article class="review-card review-card--<?php echo esc_attr( (string) ( $index + 1 ) ); ?>">
						<div class="review-card__top">
							<?php if ( ! empty( $review['review_name'] ) ) : ?>
								<h3 class="review-card__name"><?php echo esc_html( $review['review_name'] ); ?></h3>
							<?php endif; ?>
							<?php echo a4_remont_render_rating_stars( ! empty( $review['review_rating'] ) ? (int) $review['review_rating'] : 5 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
						<?php if ( ! empty( $review['review_text'] ) ) : ?>
							<p class="review-card__text"><?php echo nl2br( esc_html( $review['review_text'] ) ); ?></p>
						<?php endif; ?>
						<?php if ( ! empty( $review['review_date'] ) ) : ?>
							<time class="review-card__date" datetime="<?php echo esc_attr( $review['review_date'] ); ?>"><?php echo esc_html( date_i18n( 'd.m.Y', strtotime( $review['review_date'] ) ) ); ?></time>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>
			<div class="about-reviews__actions">
				<?php echo a4_remont_get_acf_link_html( $reviews_button, 'btn btn--primary about-reviews__all' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
	</div>
</section>
