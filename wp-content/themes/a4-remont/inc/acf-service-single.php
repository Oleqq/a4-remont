<?php
/**
 * ACF builders for single service pages.
 *
 * @package a4-remont
 */

/**
 * Return the layout map for the single service builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_service_single_section_map() {
	return array(
		'service_hero'              => 'service-hero',
		'service_repair_types'      => 'service-repair-types',
		'service_housing_types'     => 'service-housing-types',
		'service_order_steps'       => 'service-order-steps',
		'service_why_order'         => 'service-why-order',
		'service_portfolio_gallery' => 'portfolio-gallery',
		'service_payment_banner'    => 'service-payment-banner',
		'service_feedback_showcase' => array(
			'template' => 'feedback-showcase',
			'args'     => array(
				'fallback_partial' => 'section/service-single/feedback-showcase-2.html',
			),
		),
		'service_faq'               => 'faq',
		'service_company_contacts'  => 'company-contacts-services',
	);
}

/**
 * Return ACF layouts for single service pages.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_service_single_layouts() {
	$feedback_source_mode_key = a4_remont_acf_key( 'service_feedback_showcase', 'source_mode' );
	$why_order_icon_key       = a4_remont_acf_key( 'service_why_order', 'item_icon' );

	return array(
		a4_remont_acf_layout(
			'service_hero',
			'Первый экран услуги',
			array(
				a4_remont_acf_message( 'service_hero', 'guide', 'Как работает секция', 'Если заголовок, лид или изображение оставить пустыми, секция сможет использовать данные самой записи: заголовок услуги, краткое описание и миниатюру записи.' ),
				a4_remont_acf_tab( 'service_hero', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_hero', 'title', 'Заголовок H1', 'textarea', array( 'rows' => 2, 'instructions' => 'Можно оставить пустым, тогда будет использован заголовок записи услуги.' ) ),
				a4_remont_acf_field( 'service_hero', 'lead', 'Подводка', 'textarea', array( 'rows' => 4, 'instructions' => 'Если оставить пустым, будет использовано краткое описание записи.' ) ),
				...a4_remont_acf_action_button_fields( 'service_hero', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'service_hero', 'feature_tab', 'Акцентный блок' ),
				a4_remont_acf_field( 'service_hero', 'feature_title', 'Заголовок акцентного блока', 'text' ),
				a4_remont_acf_field( 'service_hero', 'feature_text', 'Текст акцентного блока', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_tab( 'service_hero', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'service_hero', 'image', 'Главное изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Если не заполнить, будет использована миниатюра записи услуги.' ) ),
				a4_remont_acf_tab( 'service_hero', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_hero', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_repair_types',
			'Виды ремонта',
			array(
				a4_remont_acf_tab( 'service_repair_types', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_repair_types', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_repair_types', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 5 ) ),
				...a4_remont_acf_action_button_fields( 'service_repair_types', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'service_repair_types', 'items_tab', 'Карточки' ),
				a4_remont_acf_field(
					'service_repair_types',
					'items',
					'Карточки видов ремонта',
					'repeater',
					array(
						'button_label' => 'Добавить карточку',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'service_repair_types', 'item_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'service_repair_types', 'item_title', 'Заголовок карточки', 'text' ),
							a4_remont_acf_field( 'service_repair_types', 'item_text', 'Текст карточки', 'textarea', array( 'rows' => 5 ) ),
							a4_remont_acf_field( 'service_repair_types', 'item_image', 'Изображение карточки', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						),
					)
				),
				a4_remont_acf_tab( 'service_repair_types', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_repair_types', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_housing_types',
			'Типы жилья',
			array(
				a4_remont_acf_tab( 'service_housing_types', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_housing_types', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_housing_types', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'service_housing_types', 'items_tab', 'Карточки жилья' ),
				a4_remont_acf_field(
					'service_housing_types',
					'items',
					'Карточки жилья',
					'repeater',
					array(
						'button_label' => 'Добавить тип жилья',
						'layout'       => 'block',
						'collapsed'    => a4_remont_acf_key( 'service_housing_types', 'item_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'service_housing_types', 'item_title', 'Название типа жилья', 'text' ),
							a4_remont_acf_field( 'service_housing_types', 'item_text', 'Описание', 'textarea', array( 'rows' => 5 ) ),
							a4_remont_acf_field( 'service_housing_types', 'item_image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
						),
					)
				),
				a4_remont_acf_tab( 'service_housing_types', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_housing_types', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_order_steps',
			'Этапы заказа',
			array(
				a4_remont_acf_tab( 'service_order_steps', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_order_steps', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_order_steps', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				...a4_remont_acf_action_button_fields( 'service_order_steps', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'service_order_steps', 'steps_tab', 'Шаги' ),
				a4_remont_acf_field(
					'service_order_steps',
					'steps',
					'Шаги процесса',
					'repeater',
					array(
						'button_label' => 'Добавить шаг',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'service_order_steps', 'step_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'service_order_steps', 'step_number', 'Номер шага', 'text', array( 'instructions' => 'Например: 01' ) ),
							a4_remont_acf_field( 'service_order_steps', 'step_title', 'Заголовок шага', 'text' ),
							a4_remont_acf_field( 'service_order_steps', 'step_text', 'Текст шага', 'textarea', array( 'rows' => 5 ) ),
						),
					)
				),
				a4_remont_acf_tab( 'service_order_steps', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_order_steps', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_why_order',
			'Почему заказывают у нас',
			array(
				a4_remont_acf_tab( 'service_why_order', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_why_order', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_why_order', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_tab( 'service_why_order', 'items_tab', 'Преимущества' ),
				a4_remont_acf_field(
					'service_why_order',
					'items',
					'Список преимуществ',
					'repeater',
					array(
						'button_label' => 'Добавить преимущество',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'service_why_order', 'item_title' ),
						'sub_fields'   => array(
							a4_remont_acf_field(
								'service_why_order',
								'item_icon',
								'Иконка',
								'select',
								array(
									'choices'       => array(
										'wallet' => 'Прозрачная оплата',
										'gift'   => 'Бонусы',
										'shield' => 'Ответственность',
									),
									'default_value' => 'wallet',
									'wrapper'       => array( 'width' => 30 ),
								)
							),
							a4_remont_acf_field( 'service_why_order', 'item_title', 'Заголовок преимущества', 'text', array( 'wrapper' => array( 'width' => 35 ) ) ),
							a4_remont_acf_field( 'service_why_order', 'item_text', 'Текст преимущества', 'textarea', array( 'rows' => 3, 'wrapper' => array( 'width' => 35 ) ) ),
						),
					)
				),
				a4_remont_acf_tab( 'service_why_order', 'right_tab', 'Правая колонка' ),
				a4_remont_acf_field( 'service_why_order', 'note_text', 'Текст плашки', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_field( 'service_why_order', 'image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'service_why_order', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_why_order', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_portfolio_gallery',
			'Портфолио',
			array(
				a4_remont_acf_tab( 'service_portfolio_gallery', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_portfolio_gallery', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_portfolio_gallery', 'section_text', 'Описание', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'service_portfolio_gallery', 'gallery', 'Галерея', 'gallery', array( 'preview_size' => 'medium' ) ),
				...a4_remont_acf_action_button_fields( 'service_portfolio_gallery', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'service_portfolio_gallery', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_portfolio_gallery', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_payment_banner',
			'Цены и оплата',
			array(
				a4_remont_acf_tab( 'service_payment_banner', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_payment_banner', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'service_payment_banner', 'text_primary', 'Первый абзац', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'service_payment_banner', 'text_secondary', 'Второй абзац', 'textarea', array( 'rows' => 4 ) ),
				...a4_remont_acf_action_button_fields( 'service_payment_banner', 'cta_button', 'Кнопка' ),
				a4_remont_acf_tab( 'service_payment_banner', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'service_payment_banner', 'image', 'Изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium' ) ),
				a4_remont_acf_tab( 'service_payment_banner', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_payment_banner', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_feedback_showcase',
			'Отзывы клиентов',
			array(
				a4_remont_acf_message( 'service_feedback_showcase', 'guide', 'Как работает блок', 'Секция выводит карточки из <strong>CPT "Отзывы"</strong>. Можно показать последние отзывы автоматически или вручную выбрать нужные карточки и их порядок.' ),
				a4_remont_acf_tab( 'service_feedback_showcase', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_feedback_showcase', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'service_feedback_showcase', 'section_lead', 'Подводка', 'textarea', array( 'rows' => 4 ) ),
				a4_remont_acf_field( 'service_feedback_showcase', 'archive_button', 'Кнопка архива', 'link' ),
				a4_remont_acf_tab( 'service_feedback_showcase', 'query_tab', 'Источник данных' ),
				a4_remont_acf_field(
					'service_feedback_showcase',
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
					'service_feedback_showcase',
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
					'service_feedback_showcase',
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
				a4_remont_acf_tab( 'service_feedback_showcase', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_feedback_showcase', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_faq',
			'FAQ',
			array(
				a4_remont_acf_tab( 'service_faq', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_faq', 'section_title', 'Заголовок секции', 'text' ),
				a4_remont_acf_field( 'service_faq', 'open_first', 'Открывать первый вопрос сразу', 'true_false', array( 'ui' => 1, 'default_value' => 1 ) ),
				a4_remont_acf_field(
					'service_faq',
					'items',
					'Вопросы и ответы',
					'repeater',
					array(
						'button_label' => 'Добавить вопрос',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'service_faq', 'question' ),
						'sub_fields'   => array(
							a4_remont_acf_field( 'service_faq', 'question', 'Вопрос', 'text' ),
							a4_remont_acf_field( 'service_faq', 'answer', 'Ответ', 'textarea', array( 'rows' => 4 ) ),
						),
					)
				),
				a4_remont_acf_tab( 'service_faq', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_faq', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'service_company_contacts',
			'Контактная информация',
			array(
				a4_remont_acf_tab( 'service_company_contacts', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'service_company_contacts', 'section_title', 'Заголовок', 'text' ),
				a4_remont_acf_field( 'service_company_contacts', 'section_text', 'Текст справа', 'textarea', array( 'rows' => 5 ) ),
				a4_remont_acf_tab( 'service_company_contacts', 'contacts_tab', 'Контакты и ссылки' ),
				a4_remont_acf_field( 'service_company_contacts', 'phone', 'Телефон', 'text' ),
				a4_remont_acf_field( 'service_company_contacts', 'email', 'Электронная почта', 'email' ),
				a4_remont_acf_field( 'service_company_contacts', 'address', 'Адрес', 'textarea', array( 'rows' => 3 ) ),
				a4_remont_acf_field( 'service_company_contacts', 'address_url', 'Ссылка на карту', 'url' ),
				a4_remont_acf_field( 'service_company_contacts', 'telegram_url', 'Ссылка на Telegram', 'url' ),
				a4_remont_acf_field( 'service_company_contacts', 'vk_url', 'Ссылка на VK', 'url' ),
				a4_remont_acf_field( 'service_company_contacts', 'reviews_url', 'Ссылка на отзывы', 'url' ),
				a4_remont_acf_tab( 'service_company_contacts', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'service_company_contacts', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync single service field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_service_single_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_service_single_sections',
		'title'                 => 'Страница услуги',
		'fields'                => array(
			a4_remont_acf_message(
				'service_single',
				'guide',
				'Как работать со страницей услуги',
				'<strong>Рекомендованный порядок заполнения:</strong><ol><li>Введите название услуги в стандартный заголовок записи.</li><li>Заполните миниатюру записи и краткое описание: они используются в карточках архива и могут работать как fallback для первого экрана.</li><li>Ниже собирайте страницу через Flexible Content и меняйте порядок секций drag-and-drop.</li><li>Если часть полей внутри секций оставить пустой, тема по возможности использует fallback из записи или статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_service_sections',
				'label'        => 'Секции страницы услуги',
				'name'         => 'service_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Добавляйте секции, раскрывайте их вкладки и настраивайте контент. Порядок секций можно менять перетаскиванием.',
				'layouts'      => a4_remont_get_service_single_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'service',
				),
			),
		),
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => array(
			'the_content',
			'discussion',
			'comments',
			'custom_fields',
		),
		'active'                => true,
	);

	$group_id    = a4_remont_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = 'a4_remont_service_single_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_service_single_field_group', 50 );

/**
 * Render flexible sections for a single service.
 *
 * @return bool
 */
function a4_remont_render_service_single_sections() {
	return a4_remont_render_mapped_flexible_sections( 'service_sections', a4_remont_get_service_single_section_map() );
}

/**
 * Render static fallback sections for a single service.
 *
 * @return bool
 */
function a4_remont_render_default_service_single_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_service_single_section_map() );
}

/**
 * Render an admin-only edit button for the current service.
 *
 * @return void
 */
function a4_remont_render_service_single_edit_link() {
	if ( ! is_singular( 'service' ) || ! is_user_logged_in() || ! current_user_can( 'edit_post', get_the_ID() ) ) {
		return;
	}

	edit_post_link(
		'Редактировать услугу',
		'<div class="a4-remont-editor-link _container">',
		'</div>',
		get_the_ID(),
		'btn btn--grey a4-remont-editor-link__button'
	);
}

/**
 * Render a single service page with flexible sections and fallback markup.
 *
 * @return void
 */
function a4_remont_render_service_single_content() {
	a4_remont_render_service_single_edit_link();

	$has_sections = a4_remont_render_service_single_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_service_single_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'service-entry _container' ); ?>>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div>
	</article>
	<?php
}
