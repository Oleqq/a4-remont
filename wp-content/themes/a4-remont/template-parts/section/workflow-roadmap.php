<?php
/**
 * Workflow roadmap section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/about-us/workflow-roadmap.html' );
	return;
}

$title = (string) get_sub_field( 'section_title' );
$steps = get_sub_field( 'steps' );

if ( '' === trim( $title ) && empty( $steps ) ) {
	a4_remont_render_static_markup( 'section/about-us/workflow-roadmap.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$intro_text = (string) get_sub_field( 'intro_text' );
$body_text  = (string) get_sub_field( 'body_text' );
$list_title = (string) get_sub_field( 'list_title' );
$cta_button = get_sub_field( 'cta_button' );
$steps      = is_array( $steps ) ? $steps : array();
?>
<section class="workflow-roadmap"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="workflow-roadmap_container _container">
		<div class="workflow-roadmap__inner">
			<div class="workflow-roadmap__left">
				<?php if ( $title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<div class="workflow-roadmap__text">
					<?php if ( $intro_text ) : ?>
						<p><?php echo nl2br( esc_html( $intro_text ) ); ?></p>
					<?php endif; ?>
					<?php if ( $body_text ) : ?>
						<p><?php echo nl2br( esc_html( $body_text ) ); ?></p>
					<?php endif; ?>
				</div>

				<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey workflow-roadmap__cta desktop' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>

			<div class="workflow-roadmap__right">
				<?php if ( $list_title ) : ?>
					<p class="workflow-roadmap__subtitle"><?php echo wp_kses_post( $list_title ); ?></p>
				<?php endif; ?>

				<?php if ( $steps ) : ?>
					<ol class="workflow-roadmap__list">
						<?php foreach ( $steps as $step ) : ?>
							<li class="workflow-roadmap__item">
								<?php if ( ! empty( $step['step_number'] ) ) : ?>
									<span class="workflow-roadmap__num"><?php echo esc_html( $step['step_number'] ); ?></span>
								<?php endif; ?>
								<?php if ( ! empty( $step['step_label'] ) ) : ?>
									<span class="workflow-roadmap__label"><?php echo nl2br( esc_html( $step['step_label'] ) ); ?></span>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ol>
				<?php endif; ?>
			</div>
		</div>

		<?php echo a4_remont_get_sub_field_action_button_html( 'cta_button', 'btn btn--grey workflow-roadmap__cta mobile' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
</section>
