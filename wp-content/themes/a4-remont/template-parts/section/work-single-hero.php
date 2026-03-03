<?php
/**
 * Single work hero section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/work-single/work-single-hero.html' );
	return;
}

$work_post = get_post();
$title     = (string) get_sub_field( 'title' );
$lead      = (string) get_sub_field( 'lead' );
$image     = get_sub_field( 'image' );
$facts     = get_sub_field( 'facts' );

if ( $work_post instanceof WP_Post ) {
	if ( '' === trim( $title ) ) {
		$title = get_the_title( $work_post );
	}

	if ( '' === trim( $lead ) ) {
		$lead = has_excerpt( $work_post ) ? (string) $work_post->post_excerpt : a4_remont_get_post_excerpt( $work_post, 36 );
	}

	if ( empty( $image ) && has_post_thumbnail( $work_post ) ) {
		$image = get_post_thumbnail_id( $work_post );
	}
}

$info_title   = (string) get_sub_field( 'info_title' );
$info_content = (string) get_sub_field( 'info_content' );

if ( '' === trim( $info_title ) && '' !== trim( wp_strip_all_tags( $info_content ) ) ) {
	$info_title = 'Общая информация о проекте';
}

if ( '' === trim( $title ) && '' === trim( $lead ) && empty( $facts ) && '' === trim( wp_strip_all_tags( $info_content ) ) && empty( $image ) ) {
	a4_remont_render_static_markup( 'section/work-single/work-single-hero.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
?>
<section class="work-single-hero"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="work-single-hero__container _container">
		<div class="work-single-hero__top">
			<div class="work-single-hero__head">
				<?php if ( $title ) : ?>
					<h1 class="section__title work-single-hero__title"><?php echo wp_kses_post( $title ); ?></h1>
				<?php endif; ?>

				<?php if ( $lead ) : ?>
					<p class="section__subtitle work-single-hero__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( ! empty( $facts ) && is_array( $facts ) ) : ?>
				<div class="work-single-hero__facts">
					<?php foreach ( $facts as $fact ) : ?>
						<?php
						$fact_label = ! empty( $fact['fact_label'] ) ? (string) $fact['fact_label'] : '';
						$fact_value = ! empty( $fact['fact_value'] ) ? (string) $fact['fact_value'] : '';

						if ( '' === trim( $fact_label ) && '' === trim( $fact_value ) ) {
							continue;
						}
						?>
						<article class="work-single-hero__fact">
							<?php if ( $fact_label ) : ?>
								<p class="work-single-hero__fact-label"><?php echo esc_html( $fact_label ); ?></p>
							<?php endif; ?>

							<?php if ( $fact_value ) : ?>
								<p class="work-single-hero__fact-value"><?php echo esc_html( $fact_value ); ?></p>
							<?php endif; ?>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<?php if ( $info_title || '' !== trim( wp_strip_all_tags( $info_content ) ) || $image ) : ?>
			<div class="work-single-hero__bottom">
				<div class="work-single-hero__info">
					<?php if ( $info_title ) : ?>
						<h2 class="section__title work-single-hero__info-title"><?php echo wp_kses_post( $info_title ); ?></h2>
					<?php endif; ?>

					<?php if ( '' !== trim( wp_strip_all_tags( $info_content ) ) ) : ?>
						<div class="work-single-hero__text" data-work-single-text="data-work-single-text">
							<?php echo wp_kses_post( $info_content ); ?>
						</div>
						<button class="work-single-hero__more" type="button" data-work-single-more="data-work-single-more">...ещё</button>
					<?php endif; ?>
				</div>

				<?php if ( $image ) : ?>
					<figure class="work-single-hero__media">
						<?php echo a4_remont_get_acf_image_html( $image, 'full', array( 'class' => 'work-single-hero__image', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</figure>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</section>
