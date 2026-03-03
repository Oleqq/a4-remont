<?php
/**
 * Trust points section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/about-us/trust-points.html' );
	return;
}

$title = (string) get_sub_field( 'section_title' );
$cards = get_sub_field( 'cards' );

if ( '' === trim( $title ) && empty( $cards ) ) {
	a4_remont_render_static_markup( 'section/about-us/trust-points.html' );
	return;
}

$section_id         = sanitize_title( (string) get_sub_field( 'section_id' ) );
$section_text       = (string) get_sub_field( 'section_text' );
$heading_text       = (string) get_sub_field( 'heading_text' );
$main_image         = get_sub_field( 'main_image' );
$badge_title_top    = (string) get_sub_field( 'badge_title_top' );
$badge_subtitle     = (string) get_sub_field( 'badge_subtitle' );
$badge_title_bottom = (string) get_sub_field( 'badge_title_bottom' );
$note_primary       = (string) get_sub_field( 'note_primary' );
$note_secondary     = (string) get_sub_field( 'note_secondary' );
$cards              = is_array( $cards ) ? $cards : array();
?>
<section class="trust-points"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="trust-points__container _container">
		<div class="trust-points__head">
			<div class="trust-points__intro">
				<?php if ( $title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $section_text ) : ?>
					<p class="section__subtitle"><?php echo nl2br( esc_html( $section_text ) ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $heading_text ) : ?>
				<div class="trust-points__heading">
					<p class="trust-points__heading-text"><?php echo nl2br( esc_html( $heading_text ) ); ?></p>
				</div>
			<?php endif; ?>
		</div>

		<div class="trust-points__stage">
			<div class="trust-points__hero">
				<?php if ( $main_image ) : ?>
					<div class="trust-points__media">
						<?php echo a4_remont_get_acf_image_html( $main_image, 'full', array( 'class' => 'trust-points__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php endif; ?>

				<?php if ( $badge_title_top || $badge_subtitle || $badge_title_bottom ) : ?>
					<div class="trust-points__badge">
						<?php if ( $badge_title_top ) : ?>
							<p class="trust-points__badge-title"><?php echo esc_html( $badge_title_top ); ?></p>
						<?php endif; ?>
						<?php if ( $badge_subtitle ) : ?>
							<p class="trust-points__badge-sub"><?php echo esc_html( $badge_subtitle ); ?></p>
						<?php endif; ?>
						<?php if ( $badge_title_bottom ) : ?>
							<p class="trust-points__badge-title"><?php echo esc_html( $badge_title_bottom ); ?></p>
						<?php endif; ?>
						<span class="trust-points__badge-check" aria-hidden="true"></span>
					</div>
				<?php endif; ?>

				<?php if ( $cards ) : ?>
					<div class="trust-points__cards">
						<?php foreach ( $cards as $card ) : ?>
							<?php
							$modifier = ! empty( $card['card_modifier'] ) ? (string) $card['card_modifier'] : 'trust-points__card--light';
							?>
							<article class="trust-points__card <?php echo esc_attr( $modifier ); ?>">
								<?php if ( ! empty( $card['card_text'] ) ) : ?>
									<p class="trust-points__card-text"><?php echo nl2br( esc_html( $card['card_text'] ) ); ?></p>
								<?php endif; ?>
								<span class="trust-points__card-check" aria-hidden="true"></span>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $note_primary || $note_secondary ) : ?>
				<div class="trust-points__foot">
					<div class="trust-points__note">
						<?php if ( $note_primary ) : ?>
							<p><?php echo nl2br( esc_html( $note_primary ) ); ?></p>
						<?php endif; ?>
						<?php if ( $note_secondary ) : ?>
							<p><?php echo nl2br( esc_html( $note_secondary ) ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
