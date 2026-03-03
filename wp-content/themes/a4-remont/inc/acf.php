<?php
/**
 * ACF integration helpers.
 *
 * @package a4-remont
 */

/**
 * Save local ACF JSON inside the theme.
 *
 * @param string $path Default save path.
 * @return string
 */
function a4_remont_acf_json_save_path( $path ) {
	unset( $path );

	return get_stylesheet_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'a4_remont_acf_json_save_path' );

/**
 * Load local ACF JSON from the theme.
 *
 * @param array<int, string> $paths Default load paths.
 * @return array<int, string>
 */
function a4_remont_acf_json_load_paths( $paths ) {
	$paths[] = get_stylesheet_directory() . '/acf-json';

	return array_unique( $paths );
}
add_filter( 'acf/settings/load_json', 'a4_remont_acf_json_load_paths' );

/**
 * Return the homepage page-template slug.
 *
 * @return string
 */
function a4_remont_get_homepage_template_slug() {
	return 'page-templates/home-page.php';
}

/**
 * Return homepage section layouts mapped to template slugs.
 *
 * @return array<string, string>
 */
function a4_remont_get_homepage_section_map() {
	return array(
		'hero'              => 'hero',
		'benefits'          => 'benefits',
		'offer_tabs'        => 'offer-tabs',
		'portfolio_gallery' => 'portfolio-gallery',
		'work_steps'        => 'work-steps',
		'why_us'            => 'why-us',
		'about_reviews'     => 'about-reviews',
		'news_latest'       => 'news-latest',
		'faq'               => 'faq',
		'cta_banner'        => 'cta-banner',
	);
}

/**
 * Generate a stable ACF field key.
 *
 * @param string $layout Layout name.
 * @param string $name   Field name.
 * @return string
 */
function a4_remont_acf_key( $layout, $name ) {
	return 'field_a4_remont_' . $layout . '_' . $name;
}

/**
 * Build a generic ACF field definition.
 *
 * @param string              $layout Layout name.
 * @param string              $name   Field name.
 * @param string              $label  Field label.
 * @param string              $type   Field type.
 * @param array<string,mixed> $extra  Extra field config.
 * @return array<string,mixed>
 */
function a4_remont_acf_field( $layout, $name, $label, $type, $extra = array() ) {
	return array_merge(
		array(
			'key'   => a4_remont_acf_key( $layout, $name ),
			'label' => $label,
			'name'  => $name,
			'type'  => $type,
		),
		$extra
	);
}

/**
 * Build an ACF tab field definition.
 *
 * @param string              $layout Layout name.
 * @param string              $name   Field name suffix.
 * @param string              $label  Tab label.
 * @param array<string,mixed> $extra  Extra config.
 * @return array<string,mixed>
 */
function a4_remont_acf_tab( $layout, $name, $label, $extra = array() ) {
	return array_merge(
		array(
			'key'       => a4_remont_acf_key( $layout, $name ),
			'label'     => $label,
			'name'      => '',
			'type'      => 'tab',
			'placement' => 'top',
			'endpoint'  => 0,
		),
		$extra
	);
}

/**
 * Build an ACF message field definition.
 *
 * @param string              $layout  Layout name.
 * @param string              $name    Field name suffix.
 * @param string              $label   Field label.
 * @param string              $message HTML message.
 * @param array<string,mixed> $extra   Extra config.
 * @return array<string,mixed>
 */
function a4_remont_acf_message( $layout, $name, $label, $message, $extra = array() ) {
	return array_merge(
		array(
			'key'       => a4_remont_acf_key( $layout, $name ),
			'label'     => $label,
			'name'      => '',
			'type'      => 'message',
			'message'   => $message,
			'new_lines' => 'wpautop',
			'esc_html'  => 0,
		),
		$extra
	);
}

/**
 * Build a flexible content layout definition.
 *
 * @param string                    $name       Layout name.
 * @param string                    $label      Layout label.
 * @param array<int, array<string,mixed>> $sub_fields Layout sub fields.
 * @return array<string,mixed>
 */
function a4_remont_acf_layout( $name, $label, $sub_fields ) {
	return array(
		'key'        => 'layout_a4_remont_' . $name,
		'name'       => $name,
		'label'      => $label,
		'display'    => 'block',
		'sub_fields' => $sub_fields,
	);
}

/**
 * Build a button field cluster that can work as a link or popup trigger.
 *
 * @param string              $layout Layout name.
 * @param string              $name   Base field name.
 * @param string              $label  Link field label.
 * @param array<string,mixed> $config Extra config.
 * @return array<int, array<string,mixed>>
 */
function a4_remont_acf_action_button_fields( $layout, $name, $label, $config = array() ) {
	$action_key = a4_remont_acf_key( $layout, $name . '_action' );

	$default_config = array(
		'action_label'             => 'Действие кнопки',
		'action_instructions'      => 'Выберите, должна ли кнопка вести по ссылке или открывать модальное окно.',
		'link_instructions'        => '',
		'popup_target_label'       => 'Какое модальное окно открыть',
		'popup_target_instructions'=> 'Список берется из раздела "Модальные окна" в левом меню админки.',
		'popup_label_label'        => 'Текст кнопки для модального окна',
		'popup_label_instructions' => 'Текст именно на этой кнопке. Можно отличать формулировки на разных секциях, даже если модальное окно одно и то же.',
		'default_action'           => 'link',
		'action_wrapper'           => array( 'width' => 25 ),
		'link_wrapper'             => array( 'width' => 75 ),
		'popup_target_wrapper'     => array( 'width' => 40 ),
		'popup_label_wrapper'      => array( 'width' => 60 ),
		'popup_label_default'      => '',
	);

	$config = wp_parse_args( $config, $default_config );

	return array(
		a4_remont_acf_field(
			$layout,
			$name . '_action',
			$config['action_label'],
			'button_group',
			array(
				'choices'       => array(
					'link'  => 'Обычная ссылка',
					'popup' => 'Открыть модальное окно',
				),
				'default_value' => $config['default_action'],
				'instructions'  => $config['action_instructions'],
				'wrapper'       => $config['action_wrapper'],
			)
		),
		a4_remont_acf_field(
			$layout,
			$name,
			$label,
			'link',
			array_filter(
				array(
					'instructions'      => $config['link_instructions'],
					'wrapper'           => $config['link_wrapper'],
					'conditional_logic' => array(
						array(
							array(
								'field'    => $action_key,
								'operator' => '==',
								'value'    => 'link',
							),
						),
					),
				),
				static function ( $value ) {
					return '' !== $value && null !== $value;
				}
			)
		),
		a4_remont_acf_field(
			$layout,
			$name . '_popup_target',
			$config['popup_target_label'],
			'select',
			array(
				'choices'           => array(),
				'ui'                => 1,
				'allow_null'        => 0,
				'default_value'     => a4_remont_get_default_popup_key(),
				'instructions'      => $config['popup_target_instructions'],
				'wrapper'           => $config['popup_target_wrapper'],
				'conditional_logic' => array(
					array(
						array(
							'field'    => $action_key,
							'operator' => '==',
							'value'    => 'popup',
						),
					),
				),
			)
		),
		a4_remont_acf_field(
			$layout,
			$name . '_popup_label',
			$config['popup_label_label'],
			'text',
			array(
				'default_value'     => $config['popup_label_default'],
				'instructions'      => $config['popup_label_instructions'],
				'wrapper'           => $config['popup_label_wrapper'],
				'conditional_logic' => array(
					array(
						array(
							'field'    => $action_key,
							'operator' => '==',
							'value'    => 'popup',
						),
					),
				),
			)
		),
	);
}

/**
 * Build the flexible content layouts for the homepage.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_homepage_layouts() {
	$why_us_card_type_key   = a4_remont_acf_key( 'why_us', 'card_type' );
	$repair_source_mode_key = a4_remont_acf_key( 'offer_tabs', 'repair_source_mode' );
	$design_source_mode_key = a4_remont_acf_key( 'offer_tabs', 'design_source_mode' );
	$news_source_mode_key   = a4_remont_acf_key( 'news_latest', 'source_mode' );

	return array(
		a4_remont_acf_layout(
			'hero',
			'Главный экран',
			array(
				a4_remont_acf_tab( 'hero', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'hero', 'subtitle', 'Подзаголовок', 'text' ),
				a4_remont_acf_field( 'hero', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field( 'hero', 'text', 'Описание', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_field( 'hero', 'note', 'Нижняя подпись', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'hero', 'buttons_tab', 'Кнопки' ),
				...a4_remont_acf_action_button_fields( 'hero', 'primary_button', 'Основная кнопка' ),
				...a4_remont_acf_action_button_fields( 'hero', 'secondary_button', 'Вторая кнопка' ),
				a4_remont_acf_tab( 'hero', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'hero', 'slides', 'Слайды', 'gallery', array( 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'hero', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'hero', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'benefits',
			'Преимущества',
			array(
				a4_remont_acf_tab( 'benefits', 'items_tab', 'Карточки' ),
				a4_remont_acf_field(
					'benefits',
					'items',
					'Карточки преимуществ',
					'repeater',
					array(
						'button_label' => 'Добавить карточку',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'benefits', 'item_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'benefits', 'item_title', 'Заголовок', 'text' ),
							a4_remont_acf_field( 'benefits', 'item_text', 'Текст', 'textarea', array( 'rows' => 4 ) ),
							a4_remont_acf_field( 'benefits', 'item_image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						),
					)
				),
				a4_remont_acf_tab( 'benefits', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'benefits', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'offer_tabs',
			'Услуги (вкладки)',
			array(
				a4_remont_acf_message( 'offer_tabs', 'guide', 'Подсказка', 'Секция выводит карточки из <strong>CPT "Услуги"</strong>. Для каждой вкладки можно использовать автоматический режим по категории или ручной список карточек.' ),
				a4_remont_acf_tab( 'offer_tabs', 'content_tab', 'Контент секции' ),
				a4_remont_acf_field( 'offer_tabs', 'section_title', 'Заголовок секции', 'text', array( 'instructions' => 'Основной заголовок блока услуг.', 'wrapper' => array( 'width' => 60 ) ) ),
				...a4_remont_acf_action_button_fields(
					'offer_tabs',
					'cta_button',
					'Кнопка секции',
					array(
						'link_instructions' => 'Общая кнопка под слайдером.',
					)
				),
				a4_remont_acf_field( 'offer_tabs', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4, 'wrapper' => array( 'width' => 60 ) ) ),
				a4_remont_acf_field( 'offer_tabs', 'section_note', 'Примечание справа', 'textarea', array( 'rows' => 4, 'wrapper' => array( 'width' => 40 ) ) ),
				a4_remont_acf_tab( 'offer_tabs', 'repair_query_tab', 'Вкладка: Ремонт' ),
				a4_remont_acf_field( 'offer_tabs', 'repair_tab_label', 'Название вкладки', 'text', array( 'default_value' => 'Ремонт', 'wrapper' => array( 'width' => 30 ) ) ),
				a4_remont_acf_field(
					'offer_tabs',
					'repair_source_mode',
					'Источник карточек',
					'button_group',
					array(
						'choices'       => array(
							'auto'   => 'Авто по категории',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'auto',
						'instructions'  => 'Автоматический режим берет услуги из выбранной категории.',
						'wrapper'       => array( 'width' => 70 ),
					)
				),
				a4_remont_acf_field(
					'offer_tabs',
					'repair_category',
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
						'instructions'      => 'Если оставить пустым, будет использована категория со slug <code>repair</code>.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $repair_source_mode_key,
									'operator' => '==',
									'value'    => 'auto',
								),
							),
						),
						'wrapper'           => array( 'width' => 65 ),
					)
				),
				a4_remont_acf_field(
					'offer_tabs',
					'repair_limit',
					'Количество карточек',
					'number',
					array(
						'default_value'     => 4,
						'min'               => 1,
						'max'               => 12,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $repair_source_mode_key,
									'operator' => '==',
									'value'    => 'auto',
								),
							),
						),
						'wrapper'           => array( 'width' => 35 ),
					)
				),
				a4_remont_acf_field(
					'offer_tabs',
					'repair_manual_items',
					'Карточки услуг',
					'relationship',
					array(
						'post_type'         => array( 'service' ),
						'return_format'     => 'id',
						'filters'           => array( 'search', 'taxonomy' ),
						'instructions'      => 'Выберите конкретные услуги и задайте порядок вручную.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $repair_source_mode_key,
									'operator' => '==',
									'value'    => 'manual',
								),
							),
						),
					)
				),
				a4_remont_acf_tab( 'offer_tabs', 'design_query_tab', 'Вкладка: Дизайн' ),
				a4_remont_acf_field( 'offer_tabs', 'design_tab_label', 'Название вкладки', 'text', array( 'default_value' => 'Дизайн', 'wrapper' => array( 'width' => 30 ) ) ),
				a4_remont_acf_field(
					'offer_tabs',
					'design_source_mode',
					'Источник карточек',
					'button_group',
					array(
						'choices'       => array(
							'auto'   => 'Авто по категории',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'auto',
						'instructions'  => 'Автоматический режим берет услуги из выбранной категории.',
						'wrapper'       => array( 'width' => 70 ),
					)
				),
				a4_remont_acf_field(
					'offer_tabs',
					'design_category',
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
						'instructions'      => 'Если оставить пустым, будет использована категория со slug <code>design</code>.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $design_source_mode_key,
									'operator' => '==',
									'value'    => 'auto',
								),
							),
						),
						'wrapper'           => array( 'width' => 65 ),
					)
				),
				a4_remont_acf_field(
					'offer_tabs',
					'design_limit',
					'Количество карточек',
					'number',
					array(
						'default_value'     => 4,
						'min'               => 1,
						'max'               => 12,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $design_source_mode_key,
									'operator' => '==',
									'value'    => 'auto',
								),
							),
						),
						'wrapper'           => array( 'width' => 35 ),
					)
				),
				a4_remont_acf_field(
					'offer_tabs',
					'design_manual_items',
					'Карточки услуг',
					'relationship',
					array(
						'post_type'         => array( 'service' ),
						'return_format'     => 'id',
						'filters'           => array( 'search', 'taxonomy' ),
						'instructions'      => 'Выберите конкретные услуги и задайте порядок вручную.',
						'conditional_logic' => array(
							array(
								array(
									'field'    => $design_source_mode_key,
									'operator' => '==',
									'value'    => 'manual',
								),
							),
						),
					)
				),
				a4_remont_acf_tab( 'offer_tabs', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'offer_tabs', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'portfolio_gallery',
			'Портфолио',
			array(
				a4_remont_acf_tab( 'portfolio_gallery', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'portfolio_gallery', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'portfolio_gallery', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'portfolio_gallery', 'gallery', 'Галерея', 'gallery', array( 'preview_size' => 'medium' ) ),
				...a4_remont_acf_action_button_fields( 'portfolio_gallery', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'portfolio_gallery', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'portfolio_gallery', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'work_steps',
			'Этапы работы',
			array(
				a4_remont_acf_tab( 'work_steps', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'work_steps', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'work_steps', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'work_steps', 'social_title', 'Заголовок блока соцсетей', 'text' ),
				a4_remont_acf_field( 'work_steps', 'telegram_url', 'Telegram URL', 'url' ),
				a4_remont_acf_field( 'work_steps', 'vk_url', 'VK URL', 'url' ),
				a4_remont_acf_field( 'work_steps', 'reviews_url', 'Reviews URL', 'url' ),
				...a4_remont_acf_action_button_fields( 'work_steps', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'work_steps', 'steps_tab', 'Этапы' ),
				a4_remont_acf_field(
					'work_steps',
					'steps',
					'Этапы',
					'repeater',
					array(
						'button_label' => 'Добавить этап',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'work_steps', 'step_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'work_steps', 'step_number', 'Номер', 'text' ),
							a4_remont_acf_field( 'work_steps', 'step_title', 'Заголовок', 'text' ),
							a4_remont_acf_field( 'work_steps', 'step_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
							a4_remont_acf_field(
								'work_steps',
								'step_modifier',
								'Визуальный модификатор',
								'select',
								array(
									'choices' => array(
										''                               => 'По умолчанию',
										'work-step--wide work-step--image-house'     => 'Широкая карточка с домом',
										'work-step--wide work-step--image-furniture' => 'Широкая карточка с мебелью',
									),
									'default_value' => '',
								)
							),
							a4_remont_acf_field( 'work_steps', 'step_image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						),
					)
				),
				a4_remont_acf_tab( 'work_steps', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'work_steps', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'why_us',
			'Почему мы',
			array(
				a4_remont_acf_tab( 'why_us', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'why_us', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'why_us', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'why_us', 'slides_tab', 'Слайды' ),
				a4_remont_acf_field(
					'why_us',
					'slides',
					'Слайды',
					'repeater',
					array(
						'button_label' => 'Добавить слайд',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'why_us', 'card_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field(
								'why_us',
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
								'why_us',
								'icon_image',
								'Иконка',
								'image',
								array(
									'return_format'    => 'array',
									'preview_size'     => 'thumbnail',
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
								'why_us',
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
								'why_us',
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
								'why_us',
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
				a4_remont_acf_tab( 'why_us', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'why_us', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'about_reviews',
			'О компании и отзывы',
			array(
				a4_remont_acf_tab( 'about_reviews', 'company_tab', 'О компании' ),
				a4_remont_acf_field( 'about_reviews', 'background_image', 'Фоновое изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_field( 'about_reviews', 'company_title', 'Заголовок компании', 'text' ),
				a4_remont_acf_field( 'about_reviews', 'company_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'about_reviews', 'company_text', 'Основной текст', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'about_reviews', 'company_logo', 'Логотип', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_field( 'about_reviews', 'company_button', 'Кнопка компании', 'link' ),
				a4_remont_acf_tab( 'about_reviews', 'reviews_tab', 'Отзывы' ),
				a4_remont_acf_field( 'about_reviews', 'reviews_title', 'Заголовок отзывов', 'text' ),
				a4_remont_acf_field( 'about_reviews', 'reviews_text', 'Описание отзывов', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'about_reviews', 'reviews_button', 'Кнопка отзывов', 'link' ),
				a4_remont_acf_field(
					'about_reviews',
					'reviews',
					'Отзывы',
					'repeater',
					array(
						'button_label' => 'Добавить отзыв',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'about_reviews', 'review_name' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'about_reviews', 'review_name', 'Имя', 'text' ),
							a4_remont_acf_field( 'about_reviews', 'review_text', 'Текст отзыва', 'textarea', array( 'rows' => 4 ) ),
							a4_remont_acf_field( 'about_reviews', 'review_rating', 'Оценка', 'number', array( 'default_value' => 5, 'min' => 1, 'max' => 5 ) ),
							a4_remont_acf_field( 'about_reviews', 'review_date', 'Дата', 'date_picker', array( 'display_format' => 'd.m.Y', 'return_format' => 'Y-m-d' ) ),
						),
					)
				),
				a4_remont_acf_tab( 'about_reviews', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'about_reviews', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'news_latest',
			'Новости',
			array(
				a4_remont_acf_message( 'news_latest', 'guide', 'Подсказка', 'Секция работает с <strong>CPT "Новости"</strong>. Можно вывести последние новости автоматически или вручную выбрать конкретные карточки.' ),
				a4_remont_acf_tab( 'news_latest', 'content_tab', 'Контент секции' ),
				a4_remont_acf_field( 'news_latest', 'section_title', 'Заголовок секции', 'text', array( 'wrapper' => array( 'width' => 60 ) ) ),
				a4_remont_acf_field( 'news_latest', 'archive_button', 'Кнопка архива', 'link', array( 'instructions' => 'Если оставить пустым, на фронте будет использована ссылка на архив новостей.', 'wrapper' => array( 'width' => 40 ) ) ),
				a4_remont_acf_field( 'news_latest', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'news_latest', 'query_tab', 'Источник новостей' ),
				a4_remont_acf_field(
					'news_latest',
					'source_mode',
					'Режим вывода',
					'button_group',
					array(
						'choices'       => array(
							'latest' => 'Последние записи',
							'manual' => 'Ручной выбор',
						),
						'default_value' => 'latest',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'news_latest',
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
						'wrapper'           => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'news_latest',
					'posts_limit',
					'Количество карточек',
					'number',
					array(
						'default_value'     => 3,
						'min'               => 1,
						'max'               => 12,
						'conditional_logic' => array(
							array(
								array(
									'field'    => $news_source_mode_key,
									'operator' => '==',
									'value'    => 'latest',
								),
							),
						),
						'wrapper'           => array( 'width' => 25 ),
					)
				),
				a4_remont_acf_field(
					'news_latest',
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
				a4_remont_acf_tab( 'news_latest', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'news_latest', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'faq',
			'FAQ',
			array(
				a4_remont_acf_tab( 'faq', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'faq', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'faq', 'open_first', 'Открывать первый вопрос сразу', 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				a4_remont_acf_field(
					'faq',
					'items',
					'Вопросы и ответы',
					'repeater',
					array(
						'button_label' => 'Добавить вопрос',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'faq', 'question' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'faq', 'question', 'Вопрос', 'text' ),
							a4_remont_acf_field( 'faq', 'answer', 'Ответ', 'textarea', array( 'rows' => 4 ) ),
						),
					)
				),
				a4_remont_acf_tab( 'faq', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'faq', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'cta_banner',
			'Форма / CTA',
			array(
				a4_remont_acf_tab( 'cta_banner', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'cta_banner', 'title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'cta_banner', 'subtitle', 'Подзаголовок', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'cta_banner', 'telegram_url', 'Telegram URL', 'url' ),
				a4_remont_acf_field( 'cta_banner', 'vk_url', 'VK URL', 'url' ),
				a4_remont_acf_field( 'cta_banner', 'reviews_url', 'Reviews URL', 'url' ),
				a4_remont_acf_field( 'cta_banner', 'phone_placeholder', 'Плейсхолдер телефона', 'text', array( 'default_value' => '+7 ( _ _ _ ) _ _ _ - _ _ - _ _' ) ),
				a4_remont_acf_field( 'cta_banner', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Call me back' ) ),
				a4_remont_acf_field( 'cta_banner', 'privacy_text', 'Текст под формой', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'cta_banner', 'phone_number', 'Телефон', 'text' ),
				a4_remont_acf_field( 'cta_banner', 'email', 'Email', 'email' ),
				a4_remont_acf_field( 'cta_banner', 'address', 'Адрес', 'text' ),
				a4_remont_acf_field( 'cta_banner', 'image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'cta_banner', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'cta_banner', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Resolve a flexible layout name to a section template slug.
 *
 * @param string $layout Flexible layout name.
 * @return string
 */
function a4_remont_get_section_template_slug( $layout ) {
	$layout = sanitize_key( (string) $layout );
	$map    = a4_remont_get_homepage_section_map();

	if ( isset( $map[ $layout ] ) ) {
		return $map[ $layout ];
	}

	return str_replace( '_', '-', $layout );
}

/**
 * Get the database post ID for an ACF field group key.
 *
 * @param string $group_key ACF field group key.
 * @return int
 */
function a4_remont_get_acf_field_group_post_id( $group_key ) {
	$posts = get_posts(
		array(
			'post_type'      => 'acf-field-group',
			'name'           => $group_key,
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	if ( empty( $posts ) ) {
		return 0;
	}

	return (int) $posts[0];
}

/**
 * Sync the homepage field group into the real ACF GUI storage.
 *
 * @return void
 */
function a4_remont_sync_homepage_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_front_page_sections',
		'title'                 => 'Главная страница',
		'fields'                => array(
			a4_remont_acf_message(
				'homepage',
				'guide',
				'Как работать с главной',
				'<strong>Порядок работы:</strong><ol><li>Назначьте странице шаблон <strong>Главная страница</strong>.</li><li>Сделайте ее статической главной страницей в настройках WordPress.</li><li>Перетаскивайте секции ниже, чтобы менять порядок на фронтенде.</li><li>Секции "Услуги" и "Новости" можно наполнять автоматически из WP-данных или вручную.</li></ol>'
			),
			array(
				'key'               => 'field_a4_remont_page_sections',
				'label'             => 'Секции главной страницы',
				'name'              => 'page_sections',
				'type'              => 'flexible_content',
				'instructions'      => 'Добавляйте секции, раскрывайте их и настраивайте через вкладки. Порядок секций можно менять drag-and-drop.',
				'layouts'           => a4_remont_get_homepage_layouts(),
				'button_label'      => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => a4_remont_get_homepage_template_slug(),
				),
			),
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
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
	$option_key  = 'a4_remont_homepage_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_homepage_field_group', 20 );

/**
 * Render page sections from an ACF Flexible Content field.
 *
 * Each layout should have a matching file in template-parts/section/{layout-slug}.php.
 *
 * @param string $field_name Flexible Content field name.
 * @return bool
 */
function a4_remont_render_flexible_sections( $field_name = 'page_sections' ) {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( $field_name ) ) {
		return false;
	}

	while ( have_rows( $field_name ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( '' === $layout ) {
			continue;
		}

		$template_slug = 'template-parts/section/' . a4_remont_get_section_template_slug( $layout );

		if ( locate_template( $template_slug . '.php', false, false ) ) {
			get_template_part( $template_slug );
		}
	}

	return true;
}

/**
 * Render the static homepage sections in their design order.
 *
 * @return bool
 */
function a4_remont_render_default_homepage_sections() {
	$rendered = false;

	foreach ( a4_remont_get_homepage_section_map() as $template_slug ) {
		$template = 'template-parts/section/' . $template_slug;

		if ( locate_template( $template . '.php', false, false ) ) {
			get_template_part( $template );
			$rendered = true;
		}
	}

	return $rendered;
}

/**
 * Render an admin-only edit button on the homepage.
 *
 * @return void
 */
function a4_remont_render_homepage_edit_link() {
	if ( ! is_user_logged_in() || ! current_user_can( 'edit_post', get_the_ID() ) ) {
		return;
	}

	edit_post_link(
		'Редактировать страницу',
		'<div class="a4-remont-editor-link _container">',
		'</div>',
		get_the_ID(),
		'btn btn--grey a4-remont-editor-link__button'
	);
}

/**
 * Render the homepage builder output with graceful fallback.
 *
 * @return void
 */
function a4_remont_render_homepage_content() {
	a4_remont_render_homepage_edit_link();

	$has_sections = function_exists( 'a4_remont_render_flexible_sections' ) && a4_remont_render_flexible_sections();

	if ( ! $has_sections && function_exists( 'a4_remont_render_default_homepage_sections' ) ) {
		$has_sections = a4_remont_render_default_homepage_sections();
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
