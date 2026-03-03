<?php
/**
 * Work steps section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/work-steps.html' );
	return;
}

$steps = get_sub_field( 'steps' );

if ( empty( $steps ) || ! is_array( $steps ) ) {
	a4_remont_render_static_markup( 'section/work-steps.html' );
	return;
}

$section_id   = sanitize_title( (string) get_sub_field( 'section_id' ) );
$title        = (string) get_sub_field( 'section_title' );
$text         = (string) get_sub_field( 'section_text' );
$social_title = (string) get_sub_field( 'social_title' );
$cta_button   = get_sub_field( 'cta_button' );
$social_links = array(
		array( 'label' => 'TG', 'url' => (string) get_sub_field( 'telegram_url' ) ),
		array( 'label' => 'VK', 'url' => (string) get_sub_field( 'vk_url' ) ),
		array( 'label' => 'REV', 'url' => (string) get_sub_field( 'reviews_url' ) ),
	);
?>
<section class="work-steps"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="work-steps__container _container">
		<div class="work-steps__top">
			<div class="work-steps__intro">
				<?php if ( $title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>
				<?php if ( $text ) : ?>
					<p class="section__subtitle"><?php echo nl2br( esc_html( $text ) ); ?></p>
				<?php endif; ?>
			</div>
			<?php if ( $social_title ) : ?>
				<div class="work-steps__social">
					<p class="section__highlighted"><?php echo esc_html( $social_title ); ?></p>
					<div class="work-steps__social-links">
						<?php foreach ( $social_links as $social_link ) : ?>
							<?php if ( empty( $social_link['url'] ) ) : ?>
								<?php continue; ?>
							<?php endif; ?>
							<a class="work-steps__social-link" href="<?php echo esc_url( $social_link['url'] ); ?>" aria-label="<?php echo esc_attr( $social_link['label'] ); ?>"><?php echo esc_html( $social_link['label'] ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="work-steps__grid">
			<?php foreach ( $steps as $step ) : ?>
				<?php $classes = trim( 'work-step ' . (string) ( $step['step_modifier'] ?? '' ) ); ?>
				<article class="<?php echo esc_attr( $classes ); ?>">
					<?php if ( ! empty( $step['step_number'] ) ) : ?>
						<span class="work-step__num"><?php echo esc_html( $step['step_number'] ); ?></span>
					<?php endif; ?>
					<?php if ( ! empty( $step['step_title'] ) ) : ?>
						<h3 class="work-step__title"><?php echo wp_kses_post( $step['step_title'] ); ?></h3>
					<?php endif; ?>
					<?php if ( ! empty( $step['step_text'] ) ) : ?>
						<p class="work-step__text"><?php echo nl2br( esc_html( $step['step_text'] ) ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $step['step_image'] ) ) : ?>
						<div class="work-step__image">
							<?php echo a4_remont_get_acf_image_html( $step['step_image'], 'full', array( 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>

		<div class="work-steps__bottom">
			<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'work-steps__cta' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
