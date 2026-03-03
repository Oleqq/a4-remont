<?php
/**
 * Team preview section.
 *
 * @package a4-remont
 */

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( 'section/about-us/team-preview.html' );
	return;
}

$title   = (string) get_sub_field( 'section_title' );
$members = get_sub_field( 'members' );

if ( '' === trim( $title ) && empty( $members ) ) {
	a4_remont_render_static_markup( 'section/about-us/team-preview.html' );
	return;
}

$section_id = sanitize_title( (string) get_sub_field( 'section_id' ) );
$text       = (string) get_sub_field( 'section_text' );
$members    = is_array( $members ) ? $members : array();
?>
<section class="team-preview"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="team-preview__container _container">
		<div class="team-preview__head">
			<div class="team-preview__text">
				<?php if ( $title ) : ?>
					<h2 class="section__title"><?php echo wp_kses_post( $title ); ?></h2>
				<?php endif; ?>

				<?php if ( $text ) : ?>
					<div class="section__subtitle"><?php echo wpautop( wp_kses_post( $text ) ); ?></div>
				<?php endif; ?>
			</div>

			<div class="team-preview__nav">
				<button class="team-preview__btn team-preview__btn--prev" type="button" aria-label="Предыдущий слайд">
					<span aria-hidden="true">&larr;</span>
				</button>
				<button class="team-preview__btn team-preview__btn--next" type="button" aria-label="Следующий слайд">
					<span aria-hidden="true">&rarr;</span>
				</button>
			</div>
		</div>

		<div class="team-preview__body">
			<div class="team-preview__slider swiper" data-team-slider="data-team-slider">
				<div class="swiper-wrapper">
					<?php foreach ( $members as $member ) : ?>
						<div class="swiper-slide">
							<article class="team-card">
								<?php if ( ! empty( $member['member_image'] ) ) : ?>
									<div class="team-card__media">
										<?php echo a4_remont_get_acf_image_html( $member['member_image'], 'medium_large', array( 'class' => 'team-card__img', 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
								<?php endif; ?>

								<div class="team-card__content">
									<?php if ( ! empty( $member['member_name'] ) ) : ?>
										<h3 class="team-card__name"><?php echo esc_html( $member['member_name'] ); ?></h3>
									<?php endif; ?>

									<?php if ( ! empty( $member['member_role'] ) ) : ?>
										<p class="team-card__role"><?php echo esc_html( $member['member_role'] ); ?></p>
									<?php endif; ?>

									<?php if ( ! empty( $member['member_experience'] ) ) : ?>
										<p class="team-card__exp"><?php echo esc_html( $member['member_experience'] ); ?></p>
									<?php endif; ?>
								</div>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="team-preview__scrollbar-wrap">
				<div class="team-preview__scrollbar swiper-scrollbar"></div>
			</div>
		</div>
	</div>
</section>
