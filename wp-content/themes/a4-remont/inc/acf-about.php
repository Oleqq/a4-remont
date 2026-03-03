<?php
/**
 * ACF builders for secondary pages.
 *
 * @package a4-remont
 */

/**
 * Return the about page template slug.
 *
 * @return string
 */
function a4_remont_get_about_page_template_slug() {
	return 'page-templates/about-us.php';
}

/**
 * Return section map for the about page builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_about_page_section_map() {
	return array(
		'hero_about'           => 'hero-about-us',
		'history_achievements' => 'history-achievements',
		'team_preview'         => 'team-preview',
		'workflow_roadmap'     => 'workflow-roadmap',
		'trust_points'         => 'trust-points',
		'feedback_showcase'    => 'feedback-showcase',
		'about_portfolio_gallery' => array(
			'template' => 'portfolio-gallery',
			'args'     => array(
				'fallback_partial' => 'section/about-us/portfolio-gallery-2.html',
			),
		),
		'company_contacts'     => 'company-contacts',
		'cta_banner_secondary' => 'cta-banner-secondary',
	);
}

/**
 * Resolve a mapped section config.
 *
 * @param string                                 $layout Layout name.
 * @param array<string, string|array<string,mixed>> $map Layout map.
 * @return array<string,mixed>
 */
function a4_remont_get_mapped_section_config( $layout, $map ) {
	$config = array(
		'template' => str_replace( '_', '-', sanitize_key( $layout ) ),
		'args'     => array(),
	);

	if ( ! isset( $map[ $layout ] ) ) {
		return $config;
	}

	if ( is_string( $map[ $layout ] ) ) {
		$config['template'] = $map[ $layout ];
		return $config;
	}

	return wp_parse_args( $map[ $layout ], $config );
}

/**
 * Render flexible sections using a config map.
 *
 * @param string                                 $field_name Field name.
 * @param array<string, string|array<string,mixed>> $map Layout map.
 * @return bool
 */
function a4_remont_render_mapped_flexible_sections( $field_name, $map ) {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( $field_name ) ) {
		return false;
	}

	while ( have_rows( $field_name ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( '' === $layout ) {
			continue;
		}

		$config        = a4_remont_get_mapped_section_config( $layout, $map );
		$template_slug = 'template-parts/section/' . $config['template'];

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug, null, $config['args'] );
		}
	}

	return true;
}

/**
 * Render static sections in map order.
 *
 * @param array<string, string|array<string,mixed>> $map Layout map.
 * @return bool
 */
function a4_remont_render_mapped_default_sections( $map ) {
	$rendered = false;

	foreach ( array_keys( $map ) as $layout ) {
		$config        = a4_remont_get_mapped_section_config( $layout, $map );
		$template_slug = 'template-parts/section/' . $config['template'];

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug, null, $config['args'] );
			$rendered = true;
		}
	}

	return $rendered;
}

/**
 * Return ACF layouts for the about page.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_about_page_layouts() {
	$feedback_source_mode_key = a4_remont_acf_key( 'feedback_showcase', 'source_mode' );

	return array(
		a4_remont_acf_layout(
			'hero_about',
			'Главный экран',
			array(
				a4_remont_acf_tab( 'hero_about', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'hero_about', 'subtitle', 'Подзаголовок', 'text' ),
				a4_remont_acf_field( 'hero_about', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'hero_about', 'text', 'Описание', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_field( 'hero_about', 'note', 'Нижняя подпись', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'hero_about', 'buttons_tab', 'Кнопки' ),
				...a4_remont_acf_action_button_fields( 'hero_about', 'primary_button', 'Основная кнопка' ),
				...a4_remont_acf_action_button_fields( 'hero_about', 'secondary_button', 'Вторая кнопка' ),
				a4_remont_acf_tab( 'hero_about', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'hero_about', 'image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'hero_about', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'hero_about', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'history_achievements',
			'История и достижения',
			array(
				a4_remont_acf_tab( 'history_achievements', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'history_achievements', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'history_achievements', 'intro_text', 'Первый абзац', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'history_achievements', 'body_text', 'Второй абзац', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'history_achievements', 'stats_tab', 'Карточки статистики' ),
				a4_remont_acf_field(
					'history_achievements',
					'stats',
					'Статистика',
					'repeater',
					array(
						'button_label' => 'Добавить показатель',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'history_achievements', 'stat_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'history_achievements', 'stat_title', 'Заголовок', 'text' ),
							a4_remont_acf_field( 'history_achievements', 'stat_value', 'Значение', 'text' ),
							a4_remont_acf_field( 'history_achievements', 'stat_unit', 'Единица измерения', 'text' ),
							a4_remont_acf_field(
								'history_achievements',
								'stat_modifier',
								'Стиль карточки',
								'select',
								array(
									'choices'       => array(
										'history-achievements__stat--dark' => 'Темная',
										'history-achievements__stat--gold' => 'Золотая',
									),
									'default_value' => 'history-achievements__stat--dark',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'history_achievements', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'history_achievements', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'team_preview',
			'Команда',
			array(
				a4_remont_acf_tab( 'team_preview', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'team_preview', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'team_preview', 'section_text', 'Описание', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_tab( 'team_preview', 'members_tab', 'Сотрудники' ),
				a4_remont_acf_field(
					'team_preview',
					'members',
					'Список сотрудников',
					'repeater',
					array(
						'button_label' => 'Добавить сотрудника',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'team_preview', 'member_name' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'team_preview', 'member_name', 'Имя', 'text' ),
							a4_remont_acf_field( 'team_preview', 'member_role', 'Должность', 'text' ),
							a4_remont_acf_field( 'team_preview', 'member_experience', 'Подпись с опытом', 'text' ),
							a4_remont_acf_field( 'team_preview', 'member_image', 'Фото', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						),
					)
				),
				a4_remont_acf_tab( 'team_preview', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'team_preview', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'workflow_roadmap',
			'Как мы работаем',
			array(
				a4_remont_acf_tab( 'workflow_roadmap', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'workflow_roadmap', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'workflow_roadmap', 'intro_text', 'Первый абзац', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'workflow_roadmap', 'body_text', 'Второй абзац', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'workflow_roadmap', 'list_title', 'Подзаголовок списка', 'text' ),
				...a4_remont_acf_action_button_fields( 'workflow_roadmap', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'workflow_roadmap', 'steps_tab', 'Этапы' ),
				a4_remont_acf_field(
					'workflow_roadmap',
					'steps',
					'Список шагов',
					'repeater',
					array(
						'button_label' => 'Добавить шаг',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'workflow_roadmap', 'step_label' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'workflow_roadmap', 'step_number', 'Номер', 'text' ),
							a4_remont_acf_field( 'workflow_roadmap', 'step_label', 'Текст шага', 'textarea', array( 'rows' => 3 ) ),
						),
					)
				),
				a4_remont_acf_tab( 'workflow_roadmap', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'workflow_roadmap', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'trust_points',
			'Преимущества и гарантии',
			array(
				a4_remont_acf_tab( 'trust_points', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'trust_points', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'trust_points', 'section_text', 'Вводный текст', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'trust_points', 'heading_text', 'Подзаголовок блока', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'trust_points', 'main_image', 'Главное изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'trust_points', 'badge_tab', 'Бейдж' ),
				a4_remont_acf_field( 'trust_points', 'badge_title_top', 'Верхняя строка бейджа', 'text' ),
				a4_remont_acf_field( 'trust_points', 'badge_subtitle', 'Средняя строка бейджа', 'text' ),
				a4_remont_acf_field( 'trust_points', 'badge_title_bottom', 'Нижняя строка бейджа', 'text' ),
				a4_remont_acf_tab( 'trust_points', 'cards_tab', 'Карточки' ),
				a4_remont_acf_field(
					'trust_points',
					'cards',
					'Карточки преимуществ',
					'repeater',
					array(
						'button_label' => 'Добавить карточку',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'trust_points', 'card_text' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'trust_points', 'card_text', 'Текст карточки', 'textarea', array( 'rows' => 3 ) ),
							a4_remont_acf_field(
								'trust_points',
								'card_modifier',
								'Стиль карточки',
								'select',
								array(
									'choices'       => array(
										'trust-points__card--light' => 'Светлая',
										'trust-points__card--dark'  => 'Темная',
									),
									'default_value' => 'trust-points__card--light',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'trust_points', 'notes_tab', 'Нижний текст' ),
				a4_remont_acf_field( 'trust_points', 'note_primary', 'Первый абзац', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'trust_points', 'note_secondary', 'Второй абзац', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'trust_points', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'trust_points', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'feedback_showcase',
			'Отзывы клиентов',
			array(
				a4_remont_acf_message( 'feedback_showcase', 'guide', 'Как работает блок', 'Секция выводит карточки из <strong>CPT "Отзывы"</strong>. Можно автоматически показать последние отзывы или вручную выбрать нужные карточки и их порядок.' ),
				a4_remont_acf_tab( 'feedback_showcase', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'feedback_showcase', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'feedback_showcase', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'feedback_showcase', 'archive_button', 'Кнопка архива', 'link' ),
				a4_remont_acf_tab( 'feedback_showcase', 'query_tab', 'Источник данных' ),
				a4_remont_acf_field(
					'feedback_showcase',
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
					'feedback_showcase',
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
					'feedback_showcase',
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
				a4_remont_acf_tab( 'feedback_showcase', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'feedback_showcase', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'about_portfolio_gallery',
			'Портфолио',
			array(
				a4_remont_acf_tab( 'about_portfolio_gallery', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'about_portfolio_gallery', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'about_portfolio_gallery', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'about_portfolio_gallery', 'gallery', 'Галерея', 'gallery', array( 'preview_size' => 'medium' ) ),
				...a4_remont_acf_action_button_fields( 'about_portfolio_gallery', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'about_portfolio_gallery', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'about_portfolio_gallery', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'company_contacts',
			'Контакты компании',
			array(
				a4_remont_acf_tab( 'company_contacts', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'company_contacts', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'company_contacts', 'left_text', 'Текст слева', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'company_contacts', 'right_text', 'Текст справа', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'company_contacts', 'contacts_tab', 'Контакты и ссылки' ),
				a4_remont_acf_field( 'company_contacts', 'phone', 'Телефон', 'text' ),
				a4_remont_acf_field( 'company_contacts', 'email', 'Электронная почта', 'email' ),
				a4_remont_acf_field( 'company_contacts', 'address', 'Адрес', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'company_contacts', 'address_url', 'Ссылка на карту', 'url' ),
				a4_remont_acf_field( 'company_contacts', 'telegram_url', 'Ссылка на Telegram', 'url' ),
				a4_remont_acf_field( 'company_contacts', 'vk_url', 'Ссылка на VK', 'url' ),
				a4_remont_acf_field( 'company_contacts', 'reviews_url', 'Ссылка на отзывы', 'url' ),
				a4_remont_acf_tab( 'company_contacts', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'company_contacts', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'cta_banner_secondary',
			'Форма обратного звонка',
			array(
				a4_remont_acf_tab( 'cta_banner_secondary', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'cta_banner_secondary', 'title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'cta_banner_secondary', 'subtitle', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'cta_banner_secondary', 'image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_field( 'cta_banner_secondary', 'phone_placeholder', 'Плейсхолдер телефона', 'text', array( 'default_value' => '+7 ( _ _ _ ) _ _ _ - _ _ - _ _', 'instructions' => 'Подсказка внутри поля телефона в форме.' ) ),
				a4_remont_acf_field( 'cta_banner_secondary', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Перезвоните мне', 'instructions' => 'Короткий призыв к действию на кнопке формы.' ) ),
				a4_remont_acf_field( 'cta_banner_secondary', 'privacy_text', 'Текст согласия', 'textarea', array( 'rows' => 3, 'instructions' => 'Подпись под формой. Можно оставить текст про согласие на обработку персональных данных.' ) ),
				a4_remont_acf_tab( 'cta_banner_secondary', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'cta_banner_secondary', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the about page field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_about_page_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_about_page_sections',
		'title'                 => 'Страница "О нас"',
		'fields'                => array(
			a4_remont_acf_message(
				'about_page',
				'guide',
				'Как работать со страницей',
				'<strong>Порядок настройки страницы "О нас":</strong><ol><li>Назначьте странице шаблон <strong>О нас</strong>.</li><li>Добавляйте секции ниже и меняйте их порядок перетаскиванием.</li><li>Внутри каждой секции заполняйте вкладки по порядку: контент, источники данных, настройки.</li><li>Если часть полей оставить пустой, на фронтенде сохранится fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_about_sections',
				'label'        => 'Секции страницы "О нас"',
				'name'         => 'about_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Перетаскивайте секции, чтобы менять порядок блоков на странице. Для каждой секции доступны отдельные вкладки с контентом и настройками.',
				'layouts'      => a4_remont_get_about_page_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => a4_remont_get_about_page_template_slug(),
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
	$option_key  = 'a4_remont_about_page_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_about_page_field_group', 30 );

/**
 * Sync feedback post fields into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_feedback_field_group() {
	if ( function_exists( 'a4_remont_sync_feedback_content_field_group' ) ) {
		return;
	}

	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_feedback_fields',
		'title'                 => 'Поля отзыва',
		'fields'                => array(
			a4_remont_acf_field( 'feedback', 'rating', 'Оценка', 'number', array( 'default_value' => 5, 'min' => 1, 'max' => 5 ) ),
			a4_remont_acf_field( 'feedback', 'review_date', 'Дата отзыва', 'date_picker', array( 'display_format' => 'd.m.Y', 'return_format' => 'Y-m-d' ) ),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'feedback',
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
	$option_key  = 'a4_remont_feedback_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_feedback_field_group', 31 );

/**
 * Render the about page flexible sections.
 *
 * @return bool
 */
function a4_remont_render_about_page_sections() {
	return a4_remont_render_mapped_flexible_sections( 'about_sections', a4_remont_get_about_page_section_map() );
}

/**
 * Render default about page sections.
 *
 * @return bool
 */
function a4_remont_render_default_about_page_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_about_page_section_map() );
}

/**
 * Render the about page content.
 *
 * @return void
 */
function a4_remont_render_about_page_content() {
	if ( function_exists( 'a4_remont_render_homepage_edit_link' ) ) {
		a4_remont_render_homepage_edit_link();
	}

	$has_sections = a4_remont_render_about_page_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_about_page_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-entry' ); ?>>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
}
