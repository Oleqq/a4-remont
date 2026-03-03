<?php
/**
 * Works portfolio section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/our-works/works-portfolio.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$work_posts = a4_remont_get_work_archive_posts();
$title      = (string) get_sub_field( 'section_title' );
$lead       = (string) get_sub_field( 'section_lead' );

if ( empty( $work_posts ) ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id         = sanitize_title( (string) get_sub_field( 'section_id' ) );
$project_link_label = (string) get_sub_field( 'project_link_label' );
$more_button_label  = (string) get_sub_field( 'more_button_label' );
$initial_desktop    = (int) get_sub_field( 'initial_desktop' );
$initial_tablet     = (int) get_sub_field( 'initial_tablet' );
$initial_mobile     = (int) get_sub_field( 'initial_mobile' );
$load_desktop       = (int) get_sub_field( 'load_desktop' );
$load_tablet        = (int) get_sub_field( 'load_tablet' );
$load_mobile        = (int) get_sub_field( 'load_mobile' );

if ( $initial_desktop < 1 ) {
	$initial_desktop = 6;
}

if ( $initial_tablet < 1 ) {
	$initial_tablet = 3;
}

if ( $initial_mobile < 1 ) {
	$initial_mobile = 3;
}

if ( $load_desktop < 1 ) {
	$load_desktop = 4;
}

if ( $load_tablet < 1 ) {
	$load_tablet = 3;
}

if ( $load_mobile < 1 ) {
	$load_mobile = 3;
}

if ( '' === trim( $title ) ) {
	$title = 'Портфолио выполненных работ по ремонту';
}

if ( '' === trim( $project_link_label ) ) {
	$project_link_label = 'Смотреть проект';
}

if ( '' === trim( $more_button_label ) ) {
	$more_button_label = 'Смотреть больше проектов';
}
?>
<section
	class="works-portfolio"
	data-works-portfolio="data-works-portfolio"
	data-initial-desktop="<?php echo esc_attr( $initial_desktop ); ?>"
	data-initial-tablet="<?php echo esc_attr( $initial_tablet ); ?>"
	data-initial-mobile="<?php echo esc_attr( $initial_mobile ); ?>"
	data-load-desktop="<?php echo esc_attr( $load_desktop ); ?>"
	data-load-tablet="<?php echo esc_attr( $load_tablet ); ?>"
	data-load-mobile="<?php echo esc_attr( $load_mobile ); ?>"
	<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>
>
	<div class="works-portfolio__container _container">
		<div class="works-portfolio__top">
			<?php if ( $title ) : ?>
				<h1 class="section__title works-portfolio__title"><?php echo wp_kses_post( $title ); ?></h1>
			<?php endif; ?>

			<?php if ( $lead ) : ?>
				<p class="section__subtitle works-portfolio__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
			<?php endif; ?>
		</div>

		<div class="works-portfolio__grid" data-works-portfolio-grid="data-works-portfolio-grid">
			<?php foreach ( $work_posts as $work_post ) : ?>
				<?php
				$image_html = get_the_post_thumbnail(
					$work_post,
					'large',
					array(
						'class'   => 'works-portfolio__image',
						'loading' => 'lazy',
					)
				);
				$excerpt = a4_remont_get_post_excerpt( $work_post, 28 );
				?>
				<a class="works-portfolio__item" href="<?php echo esc_url( get_permalink( $work_post ) ); ?>" data-work-item="data-work-item" aria-label="<?php echo esc_attr( sprintf( 'Открыть проект: %s', get_the_title( $work_post ) ) ); ?>">
					<?php if ( $image_html ) : ?>
						<?php echo $image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>

					<span class="works-portfolio__project"><?php echo esc_html( $project_link_label ); ?></span>

					<div class="works-portfolio__content">
						<h3 class="works-portfolio__item-title"><?php echo esc_html( get_the_title( $work_post ) ); ?></h3>

						<?php if ( $excerpt ) : ?>
							<p class="works-portfolio__item-text"><?php echo esc_html( $excerpt ); ?></p>
						<?php endif; ?>
					</div>
				</a>
			<?php endforeach; ?>
		</div>

		<div class="works-portfolio__bottom">
			<button class="btn btn--grey works-portfolio__more" type="button" data-works-portfolio-more="data-works-portfolio-more"><?php echo esc_html( $more_button_label ); ?></button>
		</div>
	</div>
</section>
