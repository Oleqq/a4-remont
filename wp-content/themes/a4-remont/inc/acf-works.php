<?php
/**
 * ACF builders for the works archive.
 *
 * @package a4-remont
 */

/**
 * Return the works archive options page slug.
 *
 * @return string
 */
function a4_remont_get_work_archive_options_slug() {
	return 'a4-remont-work-archive';
}

/**
 * Register the works archive options page.
 *
 * @return void
 */
function a4_remont_register_work_archive_options_page() {
	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Архив работ',
			'menu_title'  => 'Настройки архива',
			'menu_slug'   => a4_remont_get_work_archive_options_slug(),
			'parent_slug' => 'edit.php?post_type=work',
			'capability'  => 'edit_posts',
			'redirect'    => false,
			'position'    => 99,
		)
	);
}
add_action( 'acf/init', 'a4_remont_register_work_archive_options_page', 5 );

/**
 * Return section map for the works archive builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_work_archive_section_map() {
	return array(
		'works_portfolio' => array(
			'template' => 'works-portfolio',
			'args'     => array(
				'fallback_partial' => 'section/our-works/works-portfolio.html',
			),
		),
		'works_cta_form'  => array(
			'template' => 'cta-form',
			'args'     => array(
				'fallback_partial' => 'section/our-works/cta-form.html',
			),
		),
		'works_faq'       => 'faq',
	);
}

/**
 * Return ACF layouts for the works archive.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_work_archive_layouts() {
	$portfolio_source_mode_key = a4_remont_acf_key( 'works_portfolio', 'source_mode' );

	return array(
		a4_remont_acf_layout(
			'works_portfolio',
			'Портфолио работ',
			array(
				a4_remont_acf_message(
					'works_portfolio',
					'guide',
					'Как работает секция',
					'Секция выводит карточки из <strong>CPT "Работы"</strong>. Можно показывать последние работы автоматически, отфильтровать их по категории или вручную собрать нужный набор проектов.'
				),
				a4_remont_acf_tab( 'works_portfolio', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'works_portfolio', 'section_title', 'Заголовок секции', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'works_portfolio', 'section_lead', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field(
					'works_portfolio',
					'project_link_label',
					'Подпись на карточке',
					'text',
					array(
						'default_value' => 'Смотреть проект',
						'instructions'  => 'Короткая подпись поверх карточки. Если оставить пустым, будет использовано значение по умолчанию.',
					)
				),
				a4_remont_acf_field(
					'works_portfolio',
					'more_button_label',
					'Текст кнопки "Показать еще"',
					'text',
					array(
						'default_value' => 'Смотреть больше проектов',
					)
				),
				a4_remont_acf_tab( 'works_portfolio', 'query_tab', 'Источник данных' ),
				a4_remont_acf_field(
					'works_portfolio',
					'source_mode',
					'Режим вывода',
					'button_group',
					array(
						'choices'       => array(
							'latest' => 'Последние работы',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'latest',
						'instructions'  => 'Используйте "Последние работы" для автоматического наполнения архива. "Ручной выбор" дает полный контроль над составом и порядком карточек.',
					)
				),
				a4_remont_acf_field(
					'works_portfolio',
					'items_limit',
					'Количество карточек',
					'number',
					array(
						'default_value'     => 10,
						'min'               => 1,
						'max'               => 30,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $portfolio_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
						'instructions'      => 'Сколько карточек WordPress загрузит в сетку. Кнопка "Показать еще" открывает уже загруженные карточки порциями на фронтенде.',
					)
				),
				a4_remont_acf_field(
					'works_portfolio',
					'work_category',
					'Категория работ',
					'taxonomy',
					array(
						'taxonomy'          => 'work_category',
						'field_type'        => 'select',
						'return_format'     => 'id',
						'add_term'          => 0,
						'save_terms'        => 0,
						'load_terms'        => 0,
						'allow_null'        => 1,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $portfolio_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
						'instructions'      => 'Необязательно. Если выбрать категорию, в архиве будут показаны только работы из нее.',
					)
				),
				a4_remont_acf_field(
					'works_portfolio',
					'manual_items',
					'Выбранные работы',
					'relationship',
					array(
						'post_type'         => array( 'work' ),
						'return_format'     => 'id',
						'filters'           => array( 'search', 'taxonomy' ),
						'conditional_logic' => array(
							array(
								array(
									'field'    => $portfolio_source_mode_key,
									'operator' => '==',
									'value'    => 'manual',
								),
							),
						),
						'instructions'      => 'Выберите конкретные карточки и расположите их в нужном порядке.',
					)
				),
				a4_remont_acf_tab( 'works_portfolio', 'grid_tab', 'Сетка и подгрузка' ),
				a4_remont_acf_field( 'works_portfolio', 'initial_desktop', 'Сразу на десктопе', 'number', array( 'default_value' => 6, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'works_portfolio', 'initial_tablet', 'Сразу на планшете', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'works_portfolio', 'initial_mobile', 'Сразу на мобильном', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'works_portfolio', 'load_desktop', 'Подгружать на десктопе', 'number', array( 'default_value' => 4, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'works_portfolio', 'load_tablet', 'Подгружать на планшете', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'works_portfolio', 'load_mobile', 'Подгружать на мобильном', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_tab( 'works_portfolio', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'works_portfolio', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'works_cta_form',
			'Форма захвата',
			array(
				a4_remont_acf_message(
					'works_cta_form',
					'guide',
					'Как работает секция',
					'Если ввести shortcode формы, на фронтенде будет выведена реальная форма плагина. Если shortcode оставить пустым, тема покажет аккуратный встроенный fallback-шаблон с вашими текстами и плейсхолдерами.'
				),
				a4_remont_acf_tab( 'works_cta_form', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'works_cta_form', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'works_cta_form', 'lead', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'works_cta_form', 'brand_tab', 'Брендинг' ),
				a4_remont_acf_field( 'works_cta_form', 'brand_image', 'Логотип или изображение бренда', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_field(
					'works_cta_form',
					'brand_text',
					'Текстовый логотип',
					'text',
					array(
						'instructions' => 'Используется, если не задано изображение бренда. Например: A4 Remont.',
					)
				),
				a4_remont_acf_tab( 'works_cta_form', 'form_tab', 'Форма и подписи' ),
				a4_remont_acf_field(
					'works_cta_form',
					'form_shortcode',
					'Shortcode формы',
					'textarea',
					array(
						'rows'         => 3,
						'instructions' => 'Например: [contact-form-7 id="123" title="Форма архива работ"]',
					)
				),
				a4_remont_acf_field( 'works_cta_form', 'name_placeholder', 'Плейсхолдер поля "Имя"', 'text', array( 'default_value' => 'Ваше имя', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'works_cta_form', 'phone_placeholder', 'Плейсхолдер поля "Телефон"', 'text', array( 'default_value' => '+7 000 000 00 00', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'works_cta_form', 'email_placeholder', 'Плейсхолдер поля "E-mail"', 'text', array( 'default_value' => 'E-mail', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'works_cta_form', 'message_placeholder', 'Плейсхолдер поля "Сообщение"', 'text', array( 'default_value' => 'Сообщение', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field(
					'works_cta_form',
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
				a4_remont_acf_field( 'works_cta_form', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Отправить' ) ),
				a4_remont_acf_tab( 'works_cta_form', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'works_cta_form', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'works_faq',
			'FAQ',
			array(
				a4_remont_acf_tab( 'works_faq', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'works_faq', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'works_faq', 'open_first', 'Открывать первый вопрос сразу', 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				a4_remont_acf_field(
					'works_faq',
					'items',
					'Вопросы и ответы',
					'repeater',
					array(
						'button_label' => 'Добавить вопрос',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'works_faq', 'question' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'works_faq', 'question', 'Вопрос', 'text' ),
							a4_remont_acf_field( 'works_faq', 'answer', 'Ответ', 'textarea', array( 'rows' => 4 ) ),
						),
					)
				),
				a4_remont_acf_tab( 'works_faq', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'works_faq', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the works archive field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_work_archive_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_work_archive_sections',
		'title'                 => 'Архив работ',
		'fields'                => array(
			a4_remont_acf_message(
				'work_archive',
				'guide',
				'Как работать с архивом работ',
				'<strong>Порядок настройки архива работ:</strong><ol><li>Редактируйте секции ниже на странице настроек архива.</li><li>Меняйте порядок блоков drag-and-drop прямо внутри Flexible Content.</li><li>Секция "Портфолио работ" работает с CPT "Работы" и может выводить карточки автоматически или вручную.</li><li>Если часть полей оставить пустыми, на фронтенде сохранится fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_work_archive_sections',
				'label'        => 'Секции архива работ',
				'name'         => 'work_archive_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Добавляйте и переставляйте секции, чтобы управлять порядком блоков на странице /our-works/.',
				'layouts'      => a4_remont_get_work_archive_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => a4_remont_get_work_archive_options_slug(),
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
	$option_key  = 'a4_remont_work_archive_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_work_archive_field_group', 40 );

/**
 * Render works archive flexible sections from the options page.
 *
 * @return bool
 */
function a4_remont_render_work_archive_sections() {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'work_archive_sections', 'option' ) ) {
		return false;
	}

	while ( have_rows( 'work_archive_sections', 'option' ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( '' === $layout ) {
			continue;
		}

		$config        = a4_remont_get_mapped_section_config( $layout, a4_remont_get_work_archive_section_map() );
		$template_slug = 'template-parts/section/' . $config['template'];

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug, null, $config['args'] );
		}
	}

	return true;
}

/**
 * Render default works archive sections.
 *
 * @return bool
 */
function a4_remont_render_default_work_archive_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_work_archive_section_map() );
}

/**
 * Render an admin-only edit button for the works archive options.
 *
 * @return void
 */
function a4_remont_render_work_archive_edit_link() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) || ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	$url = admin_url( 'admin.php?page=' . a4_remont_get_work_archive_options_slug() );
	?>
	<div class="a4-remont-editor-link _container">
		<a class="btn btn--grey a4-remont-editor-link__button" href="<?php echo esc_url( $url ); ?>">Редактировать архив работ</a>
	</div>
	<?php
}

/**
 * Render works archive content.
 *
 * @return void
 */
function a4_remont_render_work_archive_content() {
	a4_remont_render_work_archive_edit_link();

	$has_sections = a4_remont_render_work_archive_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_work_archive_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="archive-works__fallback _container">
		<h1 class="section__title"><?php post_type_archive_title(); ?></h1>
	</div>
	<?php
}
