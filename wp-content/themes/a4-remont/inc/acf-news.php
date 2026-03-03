<?php
/**
 * ACF builders for the news archive.
 *
 * @package a4-remont
 */

/**
 * Return the news archive options page slug.
 *
 * @return string
 */
function a4_remont_get_news_archive_options_slug() {
	return 'a4-remont-news-archive';
}

/**
 * Register the news archive options page.
 *
 * @return void
 */
function a4_remont_register_news_archive_options_page() {
	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Архив новостей',
			'menu_title'  => 'Настройки архива',
			'menu_slug'   => a4_remont_get_news_archive_options_slug(),
			'parent_slug' => 'edit.php?post_type=news',
			'capability'  => 'edit_posts',
			'redirect'    => false,
			'position'    => 99,
		)
	);
}
add_action( 'acf/init', 'a4_remont_register_news_archive_options_page', 5 );

/**
 * Return section map for the news archive builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_news_archive_section_map() {
	return array(
		'news_preview_archive'    => array(
			'template' => 'news-preview',
			'args'     => array(
				'fallback_partial' => 'section/news/news-preview.html',
			),
		),
		'news_archive_faq'        => 'faq',
		'news_archive_cta_banner' => array(
			'template' => 'news-cta-banner',
			'args'     => array(
				'fallback_partial' => 'section/news/cta-banner-2.html',
			),
		),
	);
}

/**
 * Return ACF layouts for the news archive.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_news_archive_layouts() {
	$news_source_mode_key = a4_remont_acf_key( 'news_preview_archive', 'source_mode' );

	return array(
		a4_remont_acf_layout(
			'news_preview_archive',
			'Лента новостей',
			array(
				a4_remont_acf_message(
					'news_preview_archive',
					'guide',
					'Как работает секция',
					'Секция выводит карточки из <strong>CPT "Новости"</strong>. Можно автоматически показывать последние материалы, ограничивать выборку категорией <strong>news_category</strong> или вручную собирать нужный порядок карточек. Кнопка "Больше новостей" не делает новый запрос к серверу, а постепенно открывает уже загруженные карточки.'
				),
				a4_remont_acf_tab( 'news_preview_archive', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'news_preview_archive', 'section_title', 'Заголовок секции', 'textarea', array( 'rows' => 2, 'instructions' => 'Если оставить пустым, будет использован заголовок из исходной статической верстки.' ) ),
				a4_remont_acf_field( 'news_preview_archive', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4, 'instructions' => 'Короткое пояснение над сеткой карточек.' ) ),
				a4_remont_acf_field( 'news_preview_archive', 'read_label', 'Подпись "Читать"', 'text', array( 'default_value' => 'Читать', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'news_preview_archive', 'more_button_label', 'Текст кнопки "Показать еще"', 'text', array( 'default_value' => 'Больше новостей', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_tab( 'news_preview_archive', 'query_tab', 'Источник новостей' ),
				a4_remont_acf_field(
					'news_preview_archive',
					'source_mode',
					'Режим вывода',
					'button_group',
					array(
						'choices'       => array(
							'latest' => 'Последние новости',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'latest',
						'instructions'  => 'Автоматический режим подходит для живого архива. Ручной режим нужен, если надо зафиксировать конкретный набор и порядок карточек.',
					)
				),
				a4_remont_acf_field(
					'news_preview_archive',
					'news_category',
					'Категория новостей',
					'taxonomy',
					array(
						'taxonomy'          => 'news_category',
						'field_type'        => 'select',
						'return_format'     => 'id',
						'add_term'          => 0,
						'save_terms'        => 0,
						'load_terms'        => 0,
						'allow_null'        => 1,
						'instructions'      => 'Необязательно. Ограничивает автоматическую выборку одной категорией.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $news_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
						'wrapper'           => array( 'width' => 60 ),
					)
				),
				a4_remont_acf_field(
					'news_preview_archive',
					'posts_limit',
					'Количество карточек',
					'number',
					array(
						'default_value'     => 9,
						'min'               => 1,
						'max'               => 30,
						'instructions'      => 'Сколько карточек WordPress загрузит в сетку. Кнопка "Больше новостей" будет раскрывать уже загруженные карточки порциями.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $news_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
						'wrapper'           => array( 'width' => 40 ),
					)
				),
				a4_remont_acf_field(
					'news_preview_archive',
					'manual_posts',
					'Выбранные новости',
					'relationship',
					array(
						'post_type'         => array( 'news' ),
						'return_format'     => 'id',
						'filters'           => array( 'search', 'taxonomy' ),
						'instructions'      => 'Выберите конкретные новости и расположите их в нужном порядке.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $news_source_mode_key,
									'operator' => '==',
									'value'    => 'manual',
								),
							),
						),
					)
				),
				a4_remont_acf_tab( 'news_preview_archive', 'grid_tab', 'Сетка и подгрузка' ),
				a4_remont_acf_field( 'news_preview_archive', 'initial_desktop', 'Сразу на десктопе', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'news_preview_archive', 'initial_tablet', 'Сразу на планшете', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'news_preview_archive', 'initial_mobile', 'Сразу на мобильном', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'news_preview_archive', 'load_desktop', 'Подгружать на десктопе', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'news_preview_archive', 'load_tablet', 'Подгружать на планшете', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_field( 'news_preview_archive', 'load_mobile', 'Подгружать на мобильном', 'number', array( 'default_value' => 3, 'min' => 1, 'max' => 24, 'wrapper' => array( 'width' => 16 ) ) ),
				a4_remont_acf_tab( 'news_preview_archive', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'news_preview_archive', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'news_archive_faq',
			'FAQ',
			array(
				a4_remont_acf_tab( 'news_archive_faq', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'news_archive_faq', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'news_archive_faq', 'open_first', 'Открывать первый вопрос сразу', 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				a4_remont_acf_field(
					'news_archive_faq',
					'items',
					'Вопросы и ответы',
					'repeater',
					array(
						'button_label' => 'Добавить вопрос',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'news_archive_faq', 'question' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'news_archive_faq', 'question', 'Вопрос', 'text' ),
							a4_remont_acf_field( 'news_archive_faq', 'answer', 'Ответ', 'textarea', array( 'rows' => 4 ) ),
						),
					)
				),
				a4_remont_acf_tab( 'news_archive_faq', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'news_archive_faq', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'news_archive_cta_banner',
			'Форма / CTA',
			array(
				a4_remont_acf_message(
					'news_archive_cta_banner',
					'guide',
					'Как работает секция',
					'Это расширенный CTA-блок из страницы новостей. Здесь можно отдельно настроить заголовок, форму, соцсети и контакты. Если оставить поля пустыми, на фронтенде сохранится fallback из исходной статической верстки.'
				),
				a4_remont_acf_tab( 'news_archive_cta_banner', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'subtitle', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'news_archive_cta_banner', 'form_tab', 'Форма' ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'phone_placeholder', 'Плейсхолдер телефона', 'text', array( 'default_value' => '+7 ( _ _ _ ) _ _ _ - _ _ - _ _', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Перезвоните мне', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field(
					'news_archive_cta_banner',
					'privacy_text',
					'Текст согласия',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Короткий текст под чекбоксом. Можно добавить ссылки на обработку персональных данных и политику конфиденциальности.',
					)
				),
				a4_remont_acf_tab( 'news_archive_cta_banner', 'contacts_tab', 'Контакты и ссылки' ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'telegram_url', 'Ссылка на Telegram', 'url', array( 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'vk_url', 'Ссылка на VK', 'url', array( 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'reviews_url', 'Ссылка на отзывы', 'url', array( 'wrapper' => array( 'width' => 34 ) ) ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'phone_number', 'Телефон', 'text', array( 'wrapper' => array( 'width' => 34 ) ) ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'email', 'Электронная почта', 'email', array( 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'address', 'Адрес', 'textarea', array( 'rows' => 3, 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_tab( 'news_archive_cta_banner', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'news_archive_cta_banner', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'news_archive_cta_banner', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the news archive field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_news_archive_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_news_archive_sections',
		'title'                 => 'Архив новостей',
		'fields'                => array(
			a4_remont_acf_message(
				'news_archive',
				'guide',
				'Как работать с архивом новостей',
				'<strong>Порядок настройки архива новостей:</strong><ol><li>Редактируйте секции ниже на странице настроек архива.</li><li>Меняйте порядок блоков drag-and-drop прямо внутри Flexible Content.</li><li>Секция "Лента новостей" может работать в автоматическом режиме от CPT "Новости" или в ручном режиме.</li><li>Если часть полей оставить пустой, на фронтенде сохранится fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_news_archive_sections',
				'label'        => 'Секции архива новостей',
				'name'         => 'news_archive_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Перетаскивайте секции, чтобы менять порядок блоков на странице /news/.',
				'layouts'      => a4_remont_get_news_archive_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => a4_remont_get_news_archive_options_slug(),
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
	$option_key  = 'a4_remont_news_archive_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_news_archive_field_group', 40 );

/**
 * Render news archive flexible sections from the options page.
 *
 * @return bool
 */
function a4_remont_render_news_archive_sections() {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'news_archive_sections', 'option' ) ) {
		return false;
	}

	while ( have_rows( 'news_archive_sections', 'option' ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( '' === $layout ) {
			continue;
		}

		$config        = a4_remont_get_mapped_section_config( $layout, a4_remont_get_news_archive_section_map() );
		$template_slug = 'template-parts/section/' . $config['template'];

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug, null, $config['args'] );
		}
	}

	return true;
}

/**
 * Render default news archive sections.
 *
 * @return bool
 */
function a4_remont_render_default_news_archive_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_news_archive_section_map() );
}

/**
 * Render an admin-only edit button for the news archive options.
 *
 * @return void
 */
function a4_remont_render_news_archive_edit_link() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) || ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	$url = admin_url( 'admin.php?page=' . a4_remont_get_news_archive_options_slug() );
	?>
	<div class="a4-remont-editor-link _container">
		<a class="btn btn--grey a4-remont-editor-link__button" href="<?php echo esc_url( $url ); ?>">Редактировать архив новостей</a>
	</div>
	<?php
}

/**
 * Render news archive content.
 *
 * @return void
 */
function a4_remont_render_news_archive_content() {
	a4_remont_render_news_archive_edit_link();

	$has_sections = a4_remont_render_news_archive_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_news_archive_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="archive-news__fallback _container">
		<h1 class="section__title"><?php post_type_archive_title(); ?></h1>
	</div>
	<?php
}
