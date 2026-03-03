<?php
/**
 * ACF builders for the FAQ page.
 *
 * @package a4-remont
 */

/**
 * Return the FAQ page template slug.
 *
 * @return string
 */
function a4_remont_get_faq_page_template_slug() {
	return 'page-templates/faq-page.php';
}

/**
 * Return section map for the FAQ page builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_faq_page_section_map() {
	return array(
		'faq_page_hero'                 => array(
			'template' => 'faq-hero',
			'args'     => array(
				'fallback_partial' => 'section/faq-page/faq-hero.html',
			),
		),
		'faq_page_secondary'            => array(
			'template' => 'faq-secondary',
			'args'     => array(
				'fallback_partial' => 'section/faq-page/faq-secondary.html',
			),
		),
		'faq_page_cta_banner_secondary' => array(
			'template' => 'cta-banner-secondary',
			'args'     => array(
				'fallback_partial' => 'section/faq-page/cta-banner-secondary.html',
			),
		),
	);
}

/**
 * Return ACF layouts for the FAQ page.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_faq_page_layouts() {
	return array(
		a4_remont_acf_layout(
			'faq_page_hero',
			'Первый экран FAQ',
			array(
				a4_remont_acf_message(
					'faq_page_hero',
					'guide',
					'Как работает секция',
					'Это отдельный <strong>первый экран страницы FAQ</strong>, который не связан с обычной секцией <code>faq</code> на других страницах. Здесь настраиваются <strong>заголовок</strong>, <strong>фоновое изображение</strong> и <strong>текстовая подложка</strong>. Если поля оставить пустыми, тема сохранит fallback из исходной статической верстки.'
				),
				a4_remont_acf_tab( 'faq_page_hero', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'faq_page_hero',
					'title',
					'Заголовок страницы',
					'textarea',
					array(
						'rows'         => 2,
						'instructions' => 'Главный H1 страницы FAQ. Лучше держать в 1-2 строках.',
					)
				),
				a4_remont_acf_field(
					'faq_page_hero',
					'note',
					'Текст в подложке',
					'textarea',
					array(
						'rows'         => 5,
						'instructions' => 'Краткое пояснение под изображением. Используйте 2-4 предложения без перегруза.',
					)
				),
				a4_remont_acf_tab( 'faq_page_hero', 'media_tab', 'Медиа' ),
				a4_remont_acf_field(
					'faq_page_hero',
					'image_desktop',
					'Изображение для десктопа',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => 'Основное широкое изображение первого экрана.',
					)
				),
				a4_remont_acf_field(
					'faq_page_hero',
					'image_mobile',
					'Изображение для мобильного',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => 'Необязательно. Если не заполнить, на мобильном останется десктопное изображение.',
					)
				),
				a4_remont_acf_field(
					'faq_page_hero',
					'image_alt',
					'Alt-текст изображения',
					'text',
					array(
						'instructions' => 'Если оставить пустым, тема попробует взять alt из медиафайла.',
					)
				),
				a4_remont_acf_tab( 'faq_page_hero', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'faq_page_hero', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'faq_page_secondary',
			'Список вопросов FAQ',
			array(
				a4_remont_acf_message(
					'faq_page_secondary',
					'guide',
					'Как работает секция',
					'Это <strong>страничный FAQ-аккордеон</strong> со своим отдельным дизайном <code>faq-secondary</code>. Он не конфликтует с обычной секцией <code>faq</code>, которая уже используется на других страницах. Здесь лучше держать <strong>короткие вопросы</strong> и <strong>понятные ответы</strong> без лишней воды.'
				),
				a4_remont_acf_tab( 'faq_page_secondary', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'faq_page_secondary',
					'open_first',
					'Открывать первый вопрос сразу',
					'true_false',
					array(
						'ui'            => 1,
						'default_value' => 1,
						'instructions'  => 'Если включено, первый вопрос будет раскрыт при загрузке страницы.',
					)
				),
				a4_remont_acf_field(
					'faq_page_secondary',
					'items',
					'Вопросы и ответы',
					'repeater',
					array(
						'button_label' => 'Добавить вопрос',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'faq_page_secondary', 'question' ),
						'instructions' => 'Добавляйте вопросы в том порядке, в котором они должны идти на странице.',
						'sub_fields'   => array(
							a4_remont_acf_field(
								'faq_page_secondary',
								'question',
								'Вопрос',
								'text',
								array(
									'instructions' => 'Короткий понятный вопрос без канцелярита.',
								)
							),
							a4_remont_acf_field(
								'faq_page_secondary',
								'answer',
								'Ответ',
								'wysiwyg',
								array(
									'tabs'         => 'visual',
									'toolbar'      => 'basic',
									'media_upload' => 0,
									'instructions' => 'Можно выделять текст и ставить ссылки, но не перегружайте ответ тяжелым форматированием.',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'faq_page_secondary', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'faq_page_secondary', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'faq_page_cta_banner_secondary',
			'Форма / CTA',
			array(
				a4_remont_acf_message(
					'faq_page_cta_banner_secondary',
					'guide',
					'Как работает секция',
					'Это переиспользование секции <code>cta-banner-secondary</code> для страницы FAQ. Здесь настраиваются только те поля, которые реально нужны текущему дизайну: <strong>заголовок</strong>, <strong>текст</strong>, <strong>изображение</strong> и <strong>подписи формы</strong>.'
				),
				a4_remont_acf_tab( 'faq_page_cta_banner_secondary', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'faq_page_cta_banner_secondary', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'faq_page_cta_banner_secondary', 'subtitle', 'Описание', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_tab( 'faq_page_cta_banner_secondary', 'form_tab', 'Форма' ),
				a4_remont_acf_field( 'faq_page_cta_banner_secondary', 'phone_placeholder', 'Плейсхолдер телефона', 'text', array( 'default_value' => '+7 ( _ _ _ ) _ _ _ - _ _ - _ _', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'faq_page_cta_banner_secondary', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Перезвоните мне', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field(
					'faq_page_cta_banner_secondary',
					'privacy_text',
					'Текст согласия',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Короткий текст под чекбоксом. Можно добавить ссылки на политику и обработку персональных данных.',
					)
				),
				a4_remont_acf_tab( 'faq_page_cta_banner_secondary', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'faq_page_cta_banner_secondary', 'image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'faq_page_cta_banner_secondary', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'faq_page_cta_banner_secondary', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the FAQ page field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_faq_page_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_faq_page_sections',
		'title'                 => 'Страница "FAQ"',
		'fields'                => array(
			a4_remont_acf_message(
				'faq_page',
				'guide',
				'Как работать со страницей FAQ',
				'<strong>Порядок настройки страницы FAQ:</strong><ol><li>Назначьте странице шаблон <strong>FAQ</strong>.</li><li>Добавляйте секции ниже и меняйте их порядок перетаскиванием внутри Flexible Content.</li><li>Страница использует <strong>отдельный FAQ-дизайн</strong>, который не зависит от стандартной секции FAQ на других страницах.</li><li>Если часть полей оставить пустой, на фронтенде сохранится fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_faq_page_sections',
				'label'        => 'Секции страницы FAQ',
				'name'         => 'faq_page_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Перетаскивайте секции, чтобы менять порядок блоков на странице /faq/. Внутри каждой секции поля уже разбиты по вкладкам для удобного редактирования.',
				'layouts'      => a4_remont_get_faq_page_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => a4_remont_get_faq_page_template_slug(),
				),
			),
		),
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => array(
			'the_content',
			'excerpt',
			'discussion',
			'comments',
			'custom_fields',
		),
		'active'                => true,
	);

	$group_id    = a4_remont_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = 'a4_remont_faq_page_acf_schema_hash';

	if ( $group_id ) {
		$field_group['ID'] = $group_id;
	}

	if ( $group_id && get_option( $option_key ) === $schema_hash ) {
		return;
	}

	$imported = acf_import_field_group( $field_group );

	if ( is_array( $imported ) && ! empty( $imported['key'] ) ) {
		update_option( $option_key, $schema_hash, false );
	}
}
add_action( 'acf/init', 'a4_remont_sync_faq_page_field_group', 32 );

/**
 * Render FAQ page sections.
 *
 * @return bool
 */
function a4_remont_render_faq_page_sections() {
	return function_exists( 'a4_remont_render_mapped_flexible_sections' ) && a4_remont_render_mapped_flexible_sections( 'faq_page_sections', a4_remont_get_faq_page_section_map() );
}

/**
 * Render default FAQ page sections.
 *
 * @return bool
 */
function a4_remont_render_default_faq_page_sections() {
	return function_exists( 'a4_remont_render_mapped_default_sections' ) && a4_remont_render_mapped_default_sections( a4_remont_get_faq_page_section_map() );
}

/**
 * Render the FAQ page content.
 *
 * @return void
 */
function a4_remont_render_faq_page_content() {
	if ( function_exists( 'a4_remont_render_homepage_edit_link' ) ) {
		a4_remont_render_homepage_edit_link();
	}

	$has_sections = a4_remont_render_faq_page_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_faq_page_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="faq-page__fallback _container">
		<h1 class="section__title"><?php the_title(); ?></h1>
	</div>
	<?php
}
