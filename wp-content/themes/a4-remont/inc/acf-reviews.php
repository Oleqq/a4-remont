<?php
/**
 * ACF builders for the reviews archive.
 *
 * @package a4-remont
 */

/**
 * Return the reviews archive options page slug.
 *
 * @return string
 */
function a4_remont_get_feedback_archive_options_slug() {
	return 'a4-remont-feedback-archive';
}

/**
 * Register the reviews archive options page.
 *
 * @return void
 */
function a4_remont_register_feedback_archive_options_page() {
	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Архив отзывов',
			'menu_title'  => 'Настройки архива',
			'menu_slug'   => a4_remont_get_feedback_archive_options_slug(),
			'parent_slug' => 'edit.php?post_type=feedback',
			'capability'  => 'edit_posts',
			'redirect'    => false,
			'position'    => 99,
		)
	);
}
add_action( 'acf/init', 'a4_remont_register_feedback_archive_options_page', 5 );

/**
 * Return section map for the reviews archive builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_feedback_archive_section_map() {
	return array(
		'reviews_feedback_archive' => array(
			'template' => 'reviews-feedback',
			'args'     => array(
				'fallback_partial' => 'section/reviews/reviews-feedback.html',
			),
		),
		'reviews_cta_form'        => array(
			'template' => 'cta-form',
			'args'     => array(
				'fallback_partial' => 'section/our-works/cta-form.html',
			),
		),
		'reviews_why_us'          => 'why-us',
		'reviews_services'        => array(
			'template' => 'reviews-services',
			'args'     => array(
				'fallback_partial' => 'section/reviews/reviews-services.html',
			),
		),
	);
}

/**
 * Return ACF layouts for the reviews archive.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_feedback_archive_layouts() {
	$photo_source_mode_key = a4_remont_acf_key( 'reviews_feedback_archive', 'photo_source_mode' );
	$video_source_mode_key = a4_remont_acf_key( 'reviews_feedback_archive', 'video_source_mode' );
	$why_us_card_type_key  = a4_remont_acf_key( 'reviews_why_us', 'card_type' );

	return array(
		a4_remont_acf_layout(
			'reviews_feedback_archive',
			'Фото- и видео-отзывы',
			array(
				a4_remont_acf_message(
					'reviews_feedback_archive',
					'guide',
					'Как работает секция',
					'Секция собирает контент из <strong>CPT "Отзывы"</strong>. <strong>Фото-отзывы</strong> и <strong>видео-отзывы</strong> берутся из записей feedback по полю <code>Формат отзыва</code>. Для текстовых карточек на других страницах продолжает использоваться отдельный блок <code>feedback-showcase</code>.'
				),
				a4_remont_acf_tab( 'reviews_feedback_archive', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'section_title',
					'Заголовок секции',
					'textarea',
					array(
						'rows'         => 2,
						'instructions' => 'Если оставить пустым, тема покажет заголовок из статической версии: "Отзывы на ремонтные работы и дизайн проекты".',
					)
				),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'photos_more_label',
					'Текст кнопки "Показать еще"',
					'text',
					array(
						'default_value' => 'Смотреть больше отзывов',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'video_title',
					'Заголовок блока видео',
					'text',
					array(
						'default_value' => 'Видео-отзывы',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_tab( 'reviews_feedback_archive', 'photo_query_tab', 'Фото-отзывы' ),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'photo_source_mode',
					'Режим вывода фото-отзывов',
					'button_group',
					array(
						'choices'       => array(
							'latest' => 'Последние фото-отзывы',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'latest',
						'instructions'  => 'Автоматический режим подбирает записи feedback с форматом "Фото-отзыв".',
					)
				),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'photo_items_limit',
					'Сколько фото-отзывов загрузить',
					'number',
					array(
						'default_value'     => 20,
						'min'               => 1,
						'max'               => 60,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $photo_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
						'instructions'      => 'Кнопка "Смотреть больше отзывов" раскрывает уже загруженные карточки порциями, без отдельного AJAX-запроса.',
					)
				),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'manual_photo_items',
					'Выбранные фото-отзывы',
					'relationship',
					array(
						'post_type'         => array( 'feedback' ),
						'return_format'     => 'id',
						'filters'           => array( 'search' ),
						'conditional_logic' => array(
							array(
								array(
									'field'    => $photo_source_mode_key,
									'operator' => '==',
									'value'    => 'manual',
								),
							),
						),
						'instructions'      => 'Подбирайте только отзывы с форматом "Фото-отзыв". Тема автоматически отфильтрует записи других форматов.',
					)
				),
				a4_remont_acf_tab( 'reviews_feedback_archive', 'photo_grid_tab', 'Сетка фото' ),
				a4_remont_acf_field( 'reviews_feedback_archive', 'initial_desktop', 'Сразу на десктопе', 'number', array( 'default_value' => 8, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'reviews_feedback_archive', 'initial_tablet', 'Сразу на планшете', 'number', array( 'default_value' => 6, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'reviews_feedback_archive', 'initial_mobile', 'Сразу на мобильном', 'number', array( 'default_value' => 4, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'reviews_feedback_archive', 'load_desktop', 'Подгружать на десктопе', 'number', array( 'default_value' => 4, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'reviews_feedback_archive', 'load_tablet', 'Подгружать на планшете', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'reviews_feedback_archive', 'load_mobile', 'Подгружать на мобильном', 'number', array( 'default_value' => 2, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_tab( 'reviews_feedback_archive', 'video_query_tab', 'Видео-отзывы' ),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'video_source_mode',
					'Режим вывода видео-отзывов',
					'button_group',
					array(
						'choices'       => array(
							'latest' => 'Последние видео-отзывы',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'latest',
					)
				),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'video_items_limit',
					'Сколько видео-отзывов загрузить',
					'number',
					array(
						'default_value'     => 6,
						'min'               => 1,
						'max'               => 20,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $video_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
					)
				),
				a4_remont_acf_field(
					'reviews_feedback_archive',
					'manual_video_items',
					'Выбранные видео-отзывы',
					'relationship',
					array(
						'post_type'         => array( 'feedback' ),
						'return_format'     => 'id',
						'filters'           => array( 'search' ),
						'conditional_logic' => array(
							array(
								array(
									'field'    => $video_source_mode_key,
									'operator' => '==',
									'value'    => 'manual',
								),
							),
						),
						'instructions'      => 'Подбирайте только отзывы с форматом "Видео-отзыв".',
					)
				),
				a4_remont_acf_tab( 'reviews_feedback_archive', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'reviews_feedback_archive', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'reviews_cta_form',
			'Форма захвата',
			array(
				a4_remont_acf_message(
					'reviews_cta_form',
					'guide',
					'Как работает секция',
					'Если ввести shortcode формы, на фронтенде будет выведена реальная форма плагина. Если shortcode оставить пустым, тема покажет встроенный fallback-шаблон.'
				),
				a4_remont_acf_tab( 'reviews_cta_form', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'reviews_cta_form', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'reviews_cta_form', 'lead', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'reviews_cta_form', 'brand_tab', 'Брендинг' ),
				a4_remont_acf_field( 'reviews_cta_form', 'brand_image', 'Логотип или изображение бренда', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_field(
					'reviews_cta_form',
					'brand_text',
					'Текстовый логотип',
					'text',
					array(
						'instructions' => 'Используется, если не задано изображение бренда. Например: A4 Remont.',
					)
				),
				a4_remont_acf_tab( 'reviews_cta_form', 'form_tab', 'Форма и подписи' ),
				a4_remont_acf_field(
					'reviews_cta_form',
					'form_shortcode',
					'Shortcode формы',
					'textarea',
					array(
						'rows'         => 3,
						'instructions' => 'Например: [contact-form-7 id="123" title="Форма страницы отзывов"]',
					)
				),
				a4_remont_acf_field( 'reviews_cta_form', 'name_placeholder', 'Плейсхолдер поля "Имя"', 'text', array( 'default_value' => 'Ваше имя', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'reviews_cta_form', 'phone_placeholder', 'Плейсхолдер поля "Телефон"', 'text', array( 'default_value' => '+7 000 000 00 00', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'reviews_cta_form', 'email_placeholder', 'Плейсхолдер поля "E-mail"', 'text', array( 'default_value' => 'E-mail', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'reviews_cta_form', 'message_placeholder', 'Плейсхолдер поля "Сообщение"', 'text', array( 'default_value' => 'Сообщение', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field(
					'reviews_cta_form',
					'agreement_text',
					'Текст согласия',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Короткий текст под чекбоксом. Можно вставить ссылки на политику конфиденциальности и обработку персональных данных.',
					)
				),
				a4_remont_acf_field( 'reviews_cta_form', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Отправить' ) ),
				a4_remont_acf_tab( 'reviews_cta_form', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'reviews_cta_form', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'reviews_why_us',
			'Почему мы',
			array(
				a4_remont_acf_tab( 'reviews_why_us', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'reviews_why_us', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'reviews_why_us', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'reviews_why_us', 'slides_tab', 'Слайды' ),
				a4_remont_acf_field(
					'reviews_why_us',
					'slides',
					'Слайды',
					'repeater',
					array(
						'button_label' => 'Добавить слайд',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'reviews_why_us', 'card_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field(
								'reviews_why_us',
								'card_type',
								'Тип карточки',
								'select',
								array(
									'choices'       => array(
										'content' => 'Контентная карточка',
										'image'   => 'Карточка-изображение',
									),
									'default_value' => 'content',
									'ui'            => 1,
								)
							),
							a4_remont_acf_field(
								'reviews_why_us',
								'icon_image',
								'Иконка',
								'image',
								array(
									'return_format'     => 'array',
									'preview_size'      => 'thumbnail',
									'conditional_logic' => array(
										array(
											array(
												'field'    => $why_us_card_type_key,
												'operator' => '==',
												'value'    => 'content',
											),
										),
									),
								)
							),
							a4_remont_acf_field(
								'reviews_why_us',
								'card_title',
								'Заголовок',
								'text',
								array(
									'conditional_logic' => array(
										array(
											array(
												'field'    => $why_us_card_type_key,
												'operator' => '==',
												'value'    => 'content',
											),
										),
									),
								)
							),
							a4_remont_acf_field(
								'reviews_why_us',
								'card_text',
								'Описание',
								'textarea',
								array(
									'rows'              => 4,
									'conditional_logic' => array(
										array(
											array(
												'field'    => $why_us_card_type_key,
												'operator' => '==',
												'value'    => 'content',
											),
										),
									),
								)
							),
							a4_remont_acf_field(
								'reviews_why_us',
								'card_image',
								'Изображение',
								'image',
								array(
									'return_format'     => 'array',
									'preview_size'      => 'medium',
									'conditional_logic' => array(
										array(
											array(
												'field'    => $why_us_card_type_key,
												'operator' => '==',
												'value'    => 'image',
											),
										),
									),
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'reviews_why_us', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'reviews_why_us', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'reviews_services',
			'Промо услуг',
			array(
				a4_remont_acf_message(
					'reviews_services',
					'guide',
					'Как работает секция',
					'Это фиксированная композиция из <strong>двух промо-блоков услуг</strong>: темная карточка с верхним изображением и золотая карточка с нижним изображением. Такой формат выбран специально, чтобы не давать в админке псевдо-гибкость, которую не поддерживает текущий дизайн.'
				),
				a4_remont_acf_tab( 'reviews_services', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'reviews_services',
					'section_title',
					'Заголовок секции',
					'text',
					array(
						'instructions' => 'Если оставить пустым, тема покажет заголовок из статической версии: "Виды предоставляемых услуг".',
					)
				),
				a4_remont_acf_tab( 'reviews_services', 'first_tab', 'Первый блок' ),
				a4_remont_acf_field( 'reviews_services', 'first_title', 'Заголовок первого блока', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'reviews_services', 'first_text', 'Текст первого блока', 'textarea', array( 'rows' => 8 ) ),
				a4_remont_acf_field( 'reviews_services', 'first_image', 'Изображение первого блока', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'reviews_services', 'second_tab', 'Второй блок' ),
				a4_remont_acf_field( 'reviews_services', 'second_title', 'Заголовок второго блока', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'reviews_services', 'second_text', 'Текст второго блока', 'textarea', array( 'rows' => 8 ) ),
				a4_remont_acf_field( 'reviews_services', 'second_image', 'Изображение второго блока', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'reviews_services', 'cta_tab', 'Нижняя кнопка' ),
				...a4_remont_acf_action_button_fields( 'reviews_services', 'bottom_button', 'Кнопка' ),
				a4_remont_acf_tab( 'reviews_services', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'reviews_services', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the reviews archive field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_feedback_archive_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_feedback_archive_sections',
		'title'                 => 'Архив отзывов',
		'fields'                => array(
			a4_remont_acf_message(
				'feedback_archive',
				'guide',
				'Как работать с архивом отзывов',
				'<strong>Порядок настройки страницы /reviews/:</strong><ol><li>Редактируйте секции на странице настроек архива, а не в обычной записи или странице.</li><li>Меняйте порядок блоков drag-and-drop прямо внутри Flexible Content.</li><li>Фото- и видео-отзывы подхватываются из <strong>CPT "Отзывы"</strong> по формату записи.</li><li>Если часть полей оставить пустыми, тема сохранит fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_feedback_archive_sections',
				'label'        => 'Секции архива отзывов',
				'name'         => 'feedback_archive_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Добавляйте и переставляйте секции, чтобы управлять порядком блоков на странице /reviews/.',
				'layouts'      => a4_remont_get_feedback_archive_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => a4_remont_get_feedback_archive_options_slug(),
				),
			),
		),
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	);

	$group_id    = a4_remont_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = 'a4_remont_feedback_archive_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_feedback_archive_field_group', 40 );

/**
 * Render reviews archive flexible sections from the options page.
 *
 * @return bool
 */
function a4_remont_render_feedback_archive_sections() {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'feedback_archive_sections', 'option' ) ) {
		return false;
	}

	while ( have_rows( 'feedback_archive_sections', 'option' ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( '' === $layout ) {
			continue;
		}

		$config        = a4_remont_get_mapped_section_config( $layout, a4_remont_get_feedback_archive_section_map() );
		$template_slug = 'template-parts/section/' . $config['template'];

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug, null, $config['args'] );
		}
	}

	return true;
}

/**
 * Render default reviews archive sections.
 *
 * @return bool
 */
function a4_remont_render_default_feedback_archive_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_feedback_archive_section_map() );
}

/**
 * Render an admin-only edit button for the reviews archive options.
 *
 * @return void
 */
function a4_remont_render_feedback_archive_edit_link() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) || ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	$url = admin_url( 'admin.php?page=' . a4_remont_get_feedback_archive_options_slug() );
	?>
	<div class="a4-remont-editor-link _container">
		<a class="btn btn--grey a4-remont-editor-link__button" href="<?php echo esc_url( $url ); ?>">Редактировать архив отзывов</a>
	</div>
	<?php
}

/**
 * Render reviews archive content.
 *
 * @return void
 */
function a4_remont_render_feedback_archive_content() {
	a4_remont_render_feedback_archive_edit_link();

	$has_sections = a4_remont_render_feedback_archive_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_feedback_archive_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="archive-feedback__fallback _container">
		<h1 class="section__title"><?php post_type_archive_title(); ?></h1>
	</div>
	<?php
}
