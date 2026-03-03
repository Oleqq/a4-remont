<?php
/**
 * Reviews archive services promo section.
 *
 * @package a4-remont
 */

$fallback_partial = ! empty( $args['fallback_partial'] ) ? (string) $args['fallback_partial'] : 'section/reviews/reviews-services.html';

if ( ! function_exists( 'get_row_layout' ) || ! get_row_layout() ) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$section_id    = sanitize_title( (string) get_sub_field( 'section_id' ) );
$section_title = trim( (string) get_sub_field( 'section_title' ) );
$first_title   = trim( (string) get_sub_field( 'first_title' ) );
$first_text    = trim( (string) get_sub_field( 'first_text' ) );
$first_image   = get_sub_field( 'first_image' );
$second_title  = trim( (string) get_sub_field( 'second_title' ) );
$second_text   = trim( (string) get_sub_field( 'second_text' ) );
$second_image  = get_sub_field( 'second_image' );
$bottom_button = get_sub_field( 'bottom_button' );
$has_bottom_button = a4_remont_has_sub_field_action_button( 'bottom_button', $bottom_button );

if (
	'' === $section_title &&
	'' === $first_title &&
	'' === $first_text &&
	empty( $first_image ) &&
	'' === $second_title &&
	'' === $second_text &&
	empty( $second_image ) &&
	! $has_bottom_button
) {
	a4_remont_render_static_markup( $fallback_partial );
	return;
}

$default_section_title = 'Виды предоставляемых услуг';
$default_first_title   = 'Ремонт под ключ: превратим ваше пространство в идеал';
$default_first_text    = 'Мы берём на себя все этапы ремонтных работ — от предчистовой отделки до капитального ремонта с перепланировкой. Независимо от типа объекта: обычная московская квартира, компактная студия, загородный дом или жильё в ЖК бизнес-класса. Наши мастера найдут оптимальное решение для вашего пространства. Мы строго соблюдаем график, контролируем качество каждого этапа и помогаем закупать материалы по выгодным ценам. Результат? Стильное, функциональное помещение, которое будет радовать вас годами без необходимости переделок. Узнайте больше о наших ремонтных решениях и подберите подходящий вариант для вашего объекта.';
$default_second_title  = 'Дизайн-проект: визуализируйте мечту до начала работ';
$default_second_text   = 'Хотите представить, как будет выглядеть ваш интерьер ещё до старта ремонта? Профессиональный дизайн-проект — это не просто красивые картинки, а полноценный набор чертежей и реалистичных визуализаций, которые станут чёткой инструкцией для реализации задуманного. Мы создаём персонализированные решения: подстраиваем пространство под ваш ритм жизни в квартирах, воплощаем смелые идеи в загородных домах, подчёркиваем статус и эстетику в ЖК бизнес-класса. С нашим дизайн-проектом вы заранее увидите будущий результат, сможете внести правки и быть уверенными, что ремонт пройдёт точно по плану. Ознакомьтесь с примерами наших работ и начните путь к идеальному интерьеру уже сегодня!';
$default_first_image   = trailingslashit( get_template_directory_uri() ) . 'images/dfdfddfdfdfdf.png';
$default_second_image  = trailingslashit( get_template_directory_uri() ) . 'images/sdsdssdsds.png';

if ( '' === $section_title ) {
	$section_title = $default_section_title;
}

if ( '' === $first_title ) {
	$first_title = $default_first_title;
}

if ( '' === $first_text ) {
	$first_text = $default_first_text;
}

if ( '' === $second_title ) {
	$second_title = $default_second_title;
}

if ( '' === $second_text ) {
	$second_text = $default_second_text;
}

if ( empty( $bottom_button ) && post_type_exists( 'service' ) ) {
	$services_link = get_post_type_archive_link( 'service' );

	if ( $services_link ) {
		$bottom_button = array(
			'url'    => $services_link,
			'title'  => 'Ознакомиться с услугами',
			'target' => '',
		);
	}
}

$first_image_html = a4_remont_get_acf_image_html(
	$first_image,
	'full',
	array(
		'class'   => 'reviews-services__image',
		'loading' => 'lazy',
		'alt'     => $first_title,
	)
);

if ( '' === $first_image_html ) {
	$first_image_html = sprintf(
		'<img class="reviews-services__image" src="%1$s" alt="%2$s" loading="lazy">',
		esc_url( $default_first_image ),
		esc_attr( $first_title )
	);
}

$second_image_html = a4_remont_get_acf_image_html(
	$second_image,
	'full',
	array(
		'class'   => 'reviews-services__image',
		'loading' => 'lazy',
		'alt'     => $second_title,
	)
);

if ( '' === $second_image_html ) {
	$second_image_html = sprintf(
		'<img class="reviews-services__image" src="%1$s" alt="%2$s" loading="lazy">',
		esc_url( $default_second_image ),
		esc_attr( $second_title )
	);
}
?>
<section class="reviews-services"<?php echo $section_id ? ' id="' . esc_attr( $section_id ) . '"' : ''; ?>>
	<div class="reviews-services__container _container">
		<h2 class="section__title reviews-services__title"><?php echo esc_html( $section_title ); ?></h2>

		<div class="reviews-services__grid">
			<article class="reviews-services__card reviews-services__card--dark">
				<h3 class="reviews-services__card-title"><?php echo esc_html( $first_title ); ?></h3>
				<p class="reviews-services__card-text"><?php echo esc_html( $first_text ); ?></p>
			</article>

			<figure class="reviews-services__media reviews-services__media--top">
				<?php echo $first_image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</figure>

			<article class="reviews-services__card reviews-services__card--gold">
				<h3 class="reviews-services__card-title"><?php echo esc_html( $second_title ); ?></h3>
				<p class="reviews-services__card-text"><?php echo esc_html( $second_text ); ?></p>
			</article>

			<figure class="reviews-services__media reviews-services__media--bottom">
				<?php echo $second_image_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</figure>
		</div>

		<div class="reviews-services__bottom">
			<?php echo a4_remont_get_sub_field_action_button_html( 'bottom_button', 'btn btn--grey reviews-services__btn' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
</section>
