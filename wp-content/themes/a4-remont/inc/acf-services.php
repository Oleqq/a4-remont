<?php
/**
 * ACF builders for the service archive.
 *
 * @package a4-remont
 */

/**
 * Return the service archive options page slug.
 *
 * @return string
 */
function a4_remont_get_service_archive_options_slug() {
	return 'a4-remont-service-archive';
}

/**
 * Register the service archive options page.
 *
 * @return void
 */
function a4_remont_register_service_archive_options_page() {
	if ( ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Архив услуг',
			'menu_title'  => 'Настройки архива',
			'menu_slug'   => a4_remont_get_service_archive_options_slug(),
			'parent_slug' => 'edit.php?post_type=service',
			'capability'  => 'edit_posts',
			'redirect'    => false,
			'position'    => 99,
		)
	);
}
add_action( 'acf/init', 'a4_remont_register_service_archive_options_page', 5 );

/**
 * Return section map for the service archive builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_service_archive_section_map() {
	return array(
		'hero_services'              => 'hero-services',
		'service_stream'             => 'service-stream',
		'process_steps'              => 'process-steps',
		'service_arguments'          => 'service-arguments',
		'services_feedback_showcase' => 'feedback-showcase',
		'service_promo'              => 'service-promo',
		'services_portfolio_gallery' => array(
			'template' => 'portfolio-gallery',
			'args'     => array(
				'fallback_partial' => 'section/about-us/portfolio-gallery-2.html',
			),
		),
		'company_contacts_services'  => 'company-contacts-services',
	);
}

/**
 * Return ACF layouts for the service archive.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_service_archive_layouts() {
	$service_stream_group_source_mode_key = a4_remont_acf_key( 'service_stream_group', 'source_mode' );
	$feedback_source_mode_key             = a4_remont_acf_key( 'services_feedback_showcase', 'source_mode' );

	return array(
		a4_remont_acf_layout(
			'hero_services',
			'Главный экран архива',
			array(
				a4_remont_acf_tab( 'hero_services', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'hero_services', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'hero_services', 'text', 'Описание', 'textarea', array( 'rows' => 5 ) ),
				...a4_remont_acf_action_button_fields( 'hero_services', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'hero_services', 'media_tab', 'Галерея' ),
				a4_remont_acf_field( 'hero_services', 'gallery', 'Изображения', 'gallery', array( 'preview_size' => 'medium', 'instructions' => 'Лучше использовать 2 изображения, как в исходной верстке.' ) ),
				a4_remont_acf_tab( 'hero_services', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'hero_services', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_stream',
			'Поток услуг по категориям',
			array(
				a4_remont_acf_message( 'service_stream', 'guide', 'Как работает блок', 'Секция состоит из репитера категорий. Каждая категория может показывать услуги автоматически из выбранной категории <strong>service_category</strong> или вручную из списка услуг.' ),
				a4_remont_acf_tab( 'service_stream', 'content_tab', 'Контент секции' ),
				a4_remont_acf_field( 'service_stream', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_stream', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'service_stream', 'groups_tab', 'Категории и карточки' ),
				a4_remont_acf_field(
					'service_stream',
					'groups',
					'Блоки категорий',
					'repeater',
					array(
						'button_label' => 'Добавить категорию',
						'layout'       => 'block',
						'collapsed'    => a4_remont_acf_key( 'service_stream_group', 'group_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'service_stream_group', 'group_title', 'Заголовок блока', 'text', array( 'instructions' => 'Если оставить пустым, будет использовано название выбранной категории.' ) ),
							a4_remont_acf_field( 'service_stream_group', 'group_text', 'Описание блока', 'textarea', array( 'rows' => 4, 'instructions' => 'Если оставить пустым, можно использовать описание термина категории.' ) ),
							a4_remont_acf_field( 'service_stream_group', 'group_quote', 'Цитата справа', 'textarea', array( 'rows' => 3 ) ),
							a4_remont_acf_field(
								'service_stream_group',
								'source_mode',
								'Источник карточек',
								'button_group',
								array(
									'choices'       => array(
										'auto'   => 'Авто по категории',
										'manual' => 'Ручной выбор',
									),
									'default_value' => 'auto',
								)
							),
							a4_remont_acf_field(
								'service_stream_group',
								'category',
								'Категория услуг',
								'taxonomy',
								array(
									'taxonomy'          => 'service_category',
									'field_type'        => 'select',
									'return_format'     => 'id',
									'add_term'          => 0,
									'save_terms'        => 0,
									'load_terms'        => 0,
									'allow_null'        => 1,
									'conditional_logic' => array(
										array(
											array(
												'field'    => $service_stream_group_source_mode_key,
												'operator' => '==',
												'value'    => 'auto',
											),
										),
									),
								)
							),
							a4_remont_acf_field(
								'service_stream_group',
								'limit',
								'Количество карточек',
								'number',
								array(
									'default_value'     => 4,
									'min'               => 1,
									'max'               => 12,
									'conditional_logic' => array(
										array(
											array(
												'field'    => $service_stream_group_source_mode_key,
												'operator' => '==',
												'value'    => 'auto',
											),
										),
									),
								)
							),
							a4_remont_acf_field(
								'service_stream_group',
								'manual_items',
								'Выбранные услуги',
								'relationship',
								array(
									'post_type'         => array( 'service' ),
									'return_format'     => 'id',
									'filters'           => array( 'search', 'taxonomy' ),
									'conditional_logic' => array(
										array(
											array(
												'field'    => $service_stream_group_source_mode_key,
												'operator' => '==',
												'value'    => 'manual',
											),
										),
									),
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'service_stream', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_stream', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'process_steps',
			'Этапы работы',
			array(
				a4_remont_acf_tab( 'process_steps', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'process_steps', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'process_steps', 'section_text', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'process_steps', 'section_note', 'Нижний текст', 'textarea', array( 'rows' => 4 ) ),
				...a4_remont_acf_action_button_fields( 'process_steps', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'process_steps', 'steps_tab', 'Шаги' ),
				a4_remont_acf_field(
					'process_steps',
					'steps',
					'Шаги процесса',
					'repeater',
					array(
						'button_label' => 'Добавить шаг',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'process_steps', 'step_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'process_steps', 'step_count', 'Счетчик', 'text', array( 'instructions' => 'Например: 1/5' ) ),
							a4_remont_acf_field( 'process_steps', 'step_title', 'Заголовок шага', 'text' ),
							a4_remont_acf_field( 'process_steps', 'step_text', 'Описание шага', 'textarea', array( 'rows' => 3 ) ),
							a4_remont_acf_field( 'process_steps', 'step_image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
							a4_remont_acf_field(
								'process_steps',
								'step_modifier',
								'Стиль карточки',
								'select',
								array(
									'choices' => array(
										''                                                        => 'По умолчанию',
										'process-steps__item--accent'                             => 'Акцентная',
										'process-steps__item--accent process-steps__item--wide' => 'Акцентная широкая',
									),
									'default_value' => '',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'process_steps', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'process_steps', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_arguments',
			'Аргументы в нашу пользу',
			array(
				a4_remont_acf_tab( 'service_arguments', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_arguments', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_arguments', 'section_text', 'Подводка', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_tab( 'service_arguments', 'cards_tab', 'Карточки преимуществ' ),
				a4_remont_acf_field(
					'service_arguments',
					'cards',
					'Карточки',
					'repeater',
					array(
						'button_label' => 'Добавить карточку',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'service_arguments', 'card_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'service_arguments', 'card_title', 'Заголовок карточки', 'text' ),
							a4_remont_acf_field( 'service_arguments', 'card_text', 'Текст карточки', 'textarea', array( 'rows' => 3 ) ),
							a4_remont_acf_field( 'service_arguments', 'card_image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
							a4_remont_acf_field(
								'service_arguments',
								'card_modifier',
								'Визуальный модификатор',
								'select',
								array(
									'choices' => array(
										'benefits-card--worker'    => 'Профи',
										'benefits-card--geo'       => 'География',
										'benefits-card--materials' => 'Материалы',
										'benefits-card--wallet'    => 'Честные цены',
										'benefits-card--trust'     => 'Репутация',
									),
									'default_value' => 'benefits-card--worker',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'service_arguments', 'bottom_tab', 'Нижний блок' ),
				a4_remont_acf_field( 'service_arguments', 'result_primary', 'Первый итоговый абзац', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'service_arguments', 'result_secondary', 'Второй итоговый абзац', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_tab( 'service_arguments', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_arguments', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'services_feedback_showcase',
			'Отзывы клиентов',
			array(
				a4_remont_acf_message( 'services_feedback_showcase', 'guide', 'Как работает блок', 'Секция выводит карточки из <strong>CPT "Отзывы"</strong>. Можно автоматически показать последние отзывы или вручную выбрать нужные карточки и их порядок.' ),
				a4_remont_acf_tab( 'services_feedback_showcase', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'services_feedback_showcase', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'services_feedback_showcase', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'services_feedback_showcase', 'archive_button', 'Кнопка архива', 'link' ),
				a4_remont_acf_tab( 'services_feedback_showcase', 'query_tab', 'Источник данных' ),
				a4_remont_acf_field(
					'services_feedback_showcase',
					'source_mode',
					'Режим вывода',
					'button_group',
					array(
						'choices'       => array(
							'latest' => 'Последние отзывы',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'latest',
					)
				),
				a4_remont_acf_field(
					'services_feedback_showcase',
					'items_limit',
					'Количество карточек',
					'number',
					array(
						'default_value'     => 4,
						'min'               => 1,
						'max'               => 12,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $feedback_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
					)
				),
				a4_remont_acf_field(
					'services_feedback_showcase',
					'manual_feedback_items',
					'Выбранные отзывы',
					'relationship',
					array(
						'post_type'         => array( 'feedback' ),
						'return_format'     => 'id',
						'filters'           => array( 'search' ),
						'conditional_logic' => array(
							array(
								array(
									'field'    => $feedback_source_mode_key,
									'operator' => '==',
									'value'    => 'manual',
								),
							),
						),
					)
				),
				a4_remont_acf_tab( 'services_feedback_showcase', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'services_feedback_showcase', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_promo',
			'Промо-блок услуг',
			array(
				a4_remont_acf_tab( 'service_promo', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_promo', 'title', 'Заголовок', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'service_promo', 'text', 'Описание', 'textarea', array( 'rows' => 5 ) ),
				...a4_remont_acf_action_button_fields( 'service_promo', 'cta_button', 'Кнопка' ),
				a4_remont_acf_field( 'service_promo', 'brand_text', 'Текст бренда', 'text', array( 'instructions' => 'Используется, если не задан логотип/изображение бренда.' ) ),
				a4_remont_acf_field( 'service_promo', 'brand_image', 'Изображение бренда', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'service_promo', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'service_promo', 'image', 'Главное изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'service_promo', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_promo', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'services_portfolio_gallery',
			'Портфолио',
			array(
				a4_remont_acf_tab( 'services_portfolio_gallery', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'services_portfolio_gallery', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'services_portfolio_gallery', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'services_portfolio_gallery', 'gallery', 'Галерея', 'gallery', array( 'preview_size' => 'medium' ) ),
				...a4_remont_acf_action_button_fields( 'services_portfolio_gallery', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'services_portfolio_gallery', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'services_portfolio_gallery', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'company_contacts_services',
			'Контактная информация',
			array(
				a4_remont_acf_tab( 'company_contacts_services', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'company_contacts_services', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'company_contacts_services', 'section_text', 'Текст справа', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_tab( 'company_contacts_services', 'contacts_tab', 'Контакты и ссылки' ),
				a4_remont_acf_field( 'company_contacts_services', 'phone', 'Телефон', 'text' ),
				a4_remont_acf_field( 'company_contacts_services', 'email', 'Электронная почта', 'email' ),
				a4_remont_acf_field( 'company_contacts_services', 'address', 'Адрес', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'company_contacts_services', 'address_url', 'Ссылка на карту', 'url' ),
				a4_remont_acf_field( 'company_contacts_services', 'telegram_url', 'Ссылка на Telegram', 'url' ),
				a4_remont_acf_field( 'company_contacts_services', 'vk_url', 'Ссылка на VK', 'url' ),
				a4_remont_acf_field( 'company_contacts_services', 'reviews_url', 'Ссылка на отзывы', 'url' ),
				a4_remont_acf_tab( 'company_contacts_services', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'company_contacts_services', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the service archive field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_service_archive_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_service_archive_sections',
		'title'                 => 'Архив услуг',
		'fields'                => array(
			a4_remont_acf_message(
				'service_archive',
				'guide',
				'Как работать с архивом',
				'<strong>Порядок настройки архива услуг:</strong><ol><li>Редактируйте секции ниже на странице настроек архива.</li><li>Меняйте порядок блоков перетаскиванием.</li><li>В секции "Поток услуг" можно собрать любое количество категорий с карточками услуг.</li><li>Если часть полей оставить пустой, на фронтенде сохранится fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_service_archive_sections',
				'label'        => 'Секции архива услуг',
				'name'         => 'service_archive_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Перетаскивайте секции, чтобы менять порядок блоков на архивной странице услуг.',
				'layouts'      => a4_remont_get_service_archive_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => a4_remont_get_service_archive_options_slug(),
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
	$option_key  = 'a4_remont_service_archive_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_service_archive_field_group', 40 );

/**
 * Render service archive flexible sections from the options page.
 *
 * @return bool
 */
function a4_remont_render_service_archive_sections() {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'service_archive_sections', 'option' ) ) {
		return false;
	}

	while ( have_rows( 'service_archive_sections', 'option' ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( '' === $layout ) {
			continue;
		}

		$config        = a4_remont_get_mapped_section_config( $layout, a4_remont_get_service_archive_section_map() );
		$template_slug = 'template-parts/section/' . $config['template'];

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug, null, $config['args'] );
		}
	}

	return true;
}

/**
 * Render default service archive sections.
 *
 * @return bool
 */
function a4_remont_render_default_service_archive_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_service_archive_section_map() );
}

/**
 * Render an admin-only edit button for the service archive options.
 *
 * @return void
 */
function a4_remont_render_service_archive_edit_link() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) || ! function_exists( 'acf_add_options_sub_page' ) ) {
		return;
	}

	$url = admin_url( 'admin.php?page=' . a4_remont_get_service_archive_options_slug() );
	?>
	<div class="a4-remont-editor-link _container">
		<a class="btn btn--grey a4-remont-editor-link__button" href="<?php echo esc_url( $url ); ?>">Редактировать архив услуг</a>
	</div>
	<?php
}

/**
 * Render service archive content.
 *
 * @return void
 */
function a4_remont_render_service_archive_content() {
	a4_remont_render_service_archive_edit_link();

	$has_sections = a4_remont_render_service_archive_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_service_archive_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="archive-services__fallback _container">
		<h1 class="section__title"><?php post_type_archive_title(); ?></h1>
	</div>
	<?php
}
