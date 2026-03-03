<?php
/**
 * History achievements section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/about-us/history-achievements.html' );
	return;
}

$title = (string) get_sub_field( 'section_title' );
$stats = get_sub_field( 'stats' );

if ( '' === trim( $title ) && empty( $stats ) ) {
	a4_remont_render_static_markup( 'section/about-us/history-achievements.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$intro_text = (string) get_sub_field( 'intro_text' );
$body_text  = (string) get_sub_field( 'body_text' );
$stats      = is_array( $stats ) ? $stats : array();
?>
<section class="history-achievements"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="history-achievements__container _container">
		<div class="history-achievements__card">
			<div class="history-achievements__top">
				<div class="history-achievements__content">
					<?php if ( $title ) : ?>
						<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>

					<?php if ( $intro_text ) : ?>
						<p class="section__subtitle"><?php echo nl2br( esc_html( $intro_text ) ); ?></p>
					<?php endif; ?>

					<?php if ( $body_text ) : ?>
						<p class="section__subtitle"><?php echo nl2br( esc_html( $body_text ) ); ?></p>
					<?php endif; ?>
				</div>

				<?php if ( $stats ) : ?>
					<div class="history-achievements__stats">
						<?php foreach ( $stats as $stat ) : ?>
							<?php
							$modifier = ! empty( $stat['stat_modifier'] ) ? (string) $stat['stat_modifier'] : 'history-achievements__stat--dark';
							?>
							<article class="history-achievements__stat <?php echo esc_attr( $modifier ); ?>">
								<?php if ( ! empty( $stat['stat_title'] ) ) : ?>
									<p class="history-achievements__stat-title"><?php echo esc_html( $stat['stat_title'] ); ?></p>
								<?php endif; ?>

								<?php if ( ! empty( $stat['stat_value'] ) || ! empty( $stat['stat_unit'] ) ) : ?>
									<p class="history-achievements__stat-value">
										<?php if ( ! empty( $stat['stat_value'] ) ) : ?>
											<span class="history-achievements__stat-num"><?php echo wp_kses_post( $stat['stat_value'] ); ?></span>
										<?php endif; ?>
										<?php if ( ! empty( $stat['stat_unit'] ) ) : ?>
											<span class="history-achievements__stat-unit"><?php echo esc_html( $stat['stat_unit'] ); ?></span>
										<?php endif; ?>
									</p>
								<?php endif; ?>
							</article>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="history-achievements__timeline" aria-hidden="true"></div>
		</div>
	</div>
</section>
