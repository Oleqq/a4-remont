<?php
/**
 * Offer tabs section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/offer-tabs.html' );
	return;
}

$repair_posts = a4_remont_get_offer_tab_posts( 'repair' );
$design_posts = a4_remont_get_offer_tab_posts( 'design' );
$title        = (string) get_sub_field( 'section_title' );

if ( '' === trim( $title ) && empty( $repair_posts ) && empty( $design_posts ) ) {
	a4_remont_render_static_markup( 'section/offer-tabs.html' );
	return;
}

$section_id       = sanitize_title( (string) get_sub_field( 'section_id' ) );
$lead             = (string) get_sub_field( 'section_lead' );
$note             = (string) get_sub_field( 'section_note' );
$repair_tab_label = (string) get_sub_field( 'repair_tab_label' );
$design_tab_label = (string) get_sub_field( 'design_tab_label' );
$cta_button       = get_sub_field( 'cta_button' );

if ( '' === $repair_tab_label ) {
	$repair_tab_label = 'Ремонт';
}

if ( '' === $design_tab_label ) {
	$design_tab_label = 'Дизайн';
}
?>
<section class="offer-tabs" data-offer-tabs="data-offer-tabs"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="offer-tabs__container _container">
		<div class="offer-tabs__head">
			<div class="offer-tabs__intro">
				<?php if ( $title ) : ?>
					<h2 class="offer-tabs__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $lead ) : ?>
					<p class="offer-tabs__lead"><?php echo nl2br( esc_html( $lead ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $note ) : ?>
				<div class="offer-tabs__note">
					<p class="offer-tabs__note-text"><?php echo nl2br( esc_html( $note ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<div class="offer-tabs__tabs" role="tablist" aria-label="Offer tabs">
			<button class="offer-tabs__tab" type="button" role="tab" aria-selected="true" aria-controls="offer-tabs-panel-repair" id="offer-tabs-tab-repair" data-offer-tab="repair"><span><?php echo esc_html( $repair_tab_label ); ?></span></button>
			<button class="offer-tabs__tab" type="button" role="tab" aria-selected="false" aria-controls="offer-tabs-panel-design" id="offer-tabs-tab-design" data-offer-tab="design"><span><?php echo esc_html( $design_tab_label ); ?></span></button>
		</div>

		<div class="offer-tabs__body">
			<?php foreach ( array( 'repair' => $repair_posts, 'design' => $design_posts ) as $tab_key => $tab_posts ) : ?>
				<div class="offer-tabs__panel<?php echo 'design' === $tab_key ? ' is-hidden' : ''; ?>" role="tabpanel" id="offer-tabs-panel-<?php echo esc_attr( $tab_key ); ?>" aria-labelledby="offer-tabs-tab-<?php echo esc_attr( $tab_key ); ?>" data-offer-panel="<?php echo esc_attr( $tab_key ); ?>">
					<div class="offer-tabs__slider swiper" data-offer-swiper="<?php echo esc_attr( $tab_key ); ?>">
						<div class="swiper-wrapper">
							<?php foreach ( $tab_posts as $service_post ) : ?>
								<div class="swiper-slide">
									<article class="offer-card">
										<a class="offer-card__link" href="<?php echo esc_url( get_permalink( $service_post ) ); ?>" aria-label="<?php echo esc_attr( get_the_title( $service_post ) ); ?>">
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

					<div class="offer-tabs__nav">
						<button class="offer-tabs__arrow offer-tabs__arrow--prev" type="button" aria-label="Previous" data-offer-prev="<?php echo esc_attr( $tab_key ); ?>"><span aria-hidden="true">&larr;</span></button>
						<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey offer-tabs__cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<button class="offer-tabs__arrow offer-tabs__arrow--next" type="button" aria-label="Next" data-offer-next="<?php echo esc_attr( $tab_key ); ?>"><span aria-hidden="true">&rarr;</span></button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
