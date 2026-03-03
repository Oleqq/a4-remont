<?php
/**
 * Service stream section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/services/service-stream.html' );
	return;
}

$section_title = (string) get_sub_field( 'section_title' );
$section_lead  = (string) get_sub_field( 'section_lead' );
$groups        = get_sub_field( 'groups' );

if ( '' === trim( $section_title ) && '' === trim( $section_lead ) && empty( $groups ) ) {
	a4_remont_render_static_markup( 'section/services/service-stream.html' );
	return;
}

$section_id      = sanitize_title( (string) get_sub_field( 'section_id' ) );
$rendered_groups = array();

foreach ( (array) $groups as $group ) {
	if ( ! is_array( $group ) ) {
		continue;
	}

	$group_term  = a4_remont_get_service_stream_group_term( $group );
	$group_posts = a4_remont_get_service_stream_group_posts( $group );
	$group_title = ! empty( $group['group_title'] ) ? (string) $group['group_title'] : '';
	$group_text  = ! empty( $group['group_text'] ) ? (string) $group['group_text'] : '';
	$group_quote = ! empty( $group['group_quote'] ) ? (string) $group['group_quote'] : '';

	if ( '' === trim( $group_title ) && $group_term instanceof WP_Term ) {
		$group_title = $group_term->name;
	}

	if ( '' === trim( $group_text ) && $group_term instanceof WP_Term ) {
		$group_text = wp_strip_all_tags( (string) $group_term->description );
	}

	if ( '' === trim( $group_title ) && '' === trim( $group_text ) && '' === trim( $group_quote ) && empty( $group_posts ) ) {
		continue;
	}

	$rendered_groups[] = array(
		'title' => $group_title,
		'text'  => $group_text,
		'quote' => $group_quote,
		'posts' => $group_posts,
	);
}

if ( '' === trim( $section_title ) && '' === trim( $section_lead ) && empty( $rendered_groups ) ) {
	a4_remont_render_static_markup( 'section/services/service-stream.html' );
	return;
}
?>
<section class="service-stream"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="service-stream__container _container">
		<?php if ( $section_title || $section_lead ) : ?>
			<div class="service-stream__head">
				<?php if ( $section_title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $section_title ); ?></h2>
				<?php endif; ?>

				<?php if ( $section_lead ) : ?>
					<p class="service-stream__lead"><?php echo nl2br( esc_html( $section_lead ) ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php foreach ( $rendered_groups as $group_index => $group_data ) : ?>
			<div class="service-stream__block" data-stream="data-stream">
				<div class="service-stream__top">
					<div class="service-stream__top-left">
						<?php if ( $group_data['title'] ) : ?>
							<p class="service-stream__top-title"><?php echo esc_html( $group_data['title'] ); ?></p>
						<?php endif; ?>

						<?php if ( $group_data['text'] ) : ?>
							<p class="service-stream__top-text"><?php echo nl2br( esc_html( $group_data['text'] ) ); ?></p>
						<?php endif; ?>
					</div>

					<?php if ( $group_data['quote'] ) : ?>
						<div class="service-stream__top-right">
							<p class="service-stream__quote"><?php echo nl2br( esc_html( $group_data['quote'] ) ); ?></p>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $group_data['posts'] ) ) : ?>
					<div class="service-stream__body">
						<div class="service-stream__slider swiper" data-stream-slider="data-stream-slider">
							<div class="swiper-wrapper">
								<?php foreach ( $group_data['posts'] as $service_post ) : ?>
									<div class="swiper-slide">
										<article class="offer-card">
											<a class="offer-card__link" href="<?php echo esc_url( get_permalink( $service_post ) ); ?>" aria-label="<?php echo esc_attr( sprintf( 'Открыть: %s', get_the_title( $service_post ) ) ); ?>">
												<div class="offer-card__media">
													<?php echo get_the_post_thumbnail( $service_post, 'large', array( 'class' => 'offer-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
													<span class="offer-card__icon" aria-hidden="true"></span>
												</div>
												<div class="offer-card__content">
													<h3 class="offer-card__title"><?php echo esc_html( get_the_title( $service_post ) ); ?></h3>
													<p class="offer-card__text"><?php echo esc_html( a4_remont_get_post_excerpt( $service_post, 28 ) ); ?></p>
												</div>
											</a>
										</article>
									</div>
								<?php endforeach; ?>
							</div>
						</div>

						<div class="service-stream__nav">
							<button class="service-stream__btn service-stream__btn--prev" type="button" aria-label="<?php echo esc_attr( sprintf( 'Предыдущий слайд для блока %d', $group_index + 1 ) ); ?>">
								<span aria-hidden="true">&larr;</span>
							</button>
							<div class="service-stream__scrollbar-wrap">
								<div class="service-stream__scrollbar swiper-scrollbar"></div>
							</div>
							<button class="service-stream__btn service-stream__btn--next" type="button" aria-label="<?php echo esc_attr( sprintf( 'Следующий слайд для блока %d', $group_index + 1 ) ); ?>">
								<span aria-hidden="true">&rarr;</span>
							</button>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
</section>
