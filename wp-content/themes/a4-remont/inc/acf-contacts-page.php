<?php
/**
 * ACF builders for the contacts page.
 *
 * @package a4-remont
 */

/**
 * Return the contacts page template slug.
 *
 * @return string
 */
function a4_remont_get_contacts_page_template_slug() {
	return 'page-templates/contacts-page.php';
}

/**
 * Return section map for the contacts page builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_contacts_page_section_map() {
	return array(
		'contacts_cta_form'         => array(
			'template' => 'cta-form',
			'args'     => array(
				'fallback_partial'    => 'section/contacts/cta-form-2.html',
				'title_modifier_class' => 'inner',
			),
		),
		'contacts_company_details'  => array(
			'template' => 'company-contacts-services',
			'args'     => array(
				'fallback_partial' => 'section/contacts/company-contacts-3.html',
				'section_class'    => 'contacts-page',
				'wide_cols'        => true,
			),
		),
	);
}

/**
 * Return ACF layouts for the contacts page.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_contacts_page_layouts() {
	return array(
		a4_remont_acf_layout(
			'contacts_cta_form',
			'Форма захвата',
			array(
				a4_remont_acf_message(
					'contacts_cta_form',
					'guide',
					'Как работает секция',
					'Это <strong>главная форма страницы контактов</strong>. Она повторно использует общий шаблон <code>cta-form</code>, поэтому администратор получает знакомую форму редактирования без дублирования логики.'
				),
				a4_remont_acf_tab( 'contacts_cta_form', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'contacts_cta_form',
					'title',
					'Заголовок',
					'textarea',
					array(
						'rows'         => 2,
						'instructions' => 'Главный заголовок формы. Можно оставлять переносы строк.',
					)
				),
				a4_remont_acf_field(
					'contacts_cta_form',
					'lead',
					'Подводка',
					'textarea',
					array(
						'rows'         => 4,
						'instructions' => 'Кратко объясните, зачем оставлять заявку и что клиент получит после отправки формы.',
					)
				),
				a4_remont_acf_field(
					'contacts_cta_form',
					'brand_text',
					'Текст бренда / подпись',
					'text',
					array(
						'wrapper'      => array( 'width' => 50 ),
						'instructions' => 'Если не хотите использовать логотип, можно оставить текстовый вариант.',
					)
				),
				a4_remont_acf_field(
					'contacts_cta_form',
					'brand_image',
					'Логотип / изображение бренда',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_tab( 'contacts_cta_form', 'form_tab', 'Форма' ),
				a4_remont_acf_field(
					'contacts_cta_form',
					'form_shortcode',
					'Shortcode формы',
					'textarea',
					array(
						'rows'         => 3,
						'instructions' => 'Если укажете shortcode, на фронте будет выведена форма плагина вместо встроенного шаблона.',
					)
				),
				a4_remont_acf_field( 'contacts_cta_form', 'name_placeholder', 'Плейсхолдер поля "Имя"', 'text', array( 'default_value' => 'Ваше имя', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'contacts_cta_form', 'phone_placeholder', 'Плейсхолдер поля "Телефон"', 'text', array( 'default_value' => '+7 000 000 00 00', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'contacts_cta_form', 'email_placeholder', 'Плейсхолдер поля "E-mail"', 'text', array( 'default_value' => 'E-mail', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field( 'contacts_cta_form', 'message_placeholder', 'Плейсхолдер поля "Сообщение"', 'text', array( 'default_value' => 'Сообщение', 'wrapper' => array( 'width' => 50 ) ) ),
				a4_remont_acf_field(
					'contacts_cta_form',
					'agreement_text',
					'Текст согласия',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Текст рядом с чекбоксом. Можно добавить ссылки на политику и обработку персональных данных.',
					)
				),
				a4_remont_acf_field( 'contacts_cta_form', 'submit_label', 'Текст кнопки', 'text', array( 'default_value' => 'Отправить' ) ),
				a4_remont_acf_tab( 'contacts_cta_form', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'contacts_cta_form', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'contacts_company_details',
			'Контактные данные компании',
			array(
				a4_remont_acf_message(
					'contacts_company_details',
					'guide',
					'Как работает секция',
					'Это <strong>вариант уже существующей секции контактов</strong>, адаптированный под страницу <code>/contacts/</code>. Здесь обычно достаточно одного заголовка, карточек с телефоном, e-mail и адресом, а также ссылок на соцсети.'
				),
				a4_remont_acf_tab( 'contacts_company_details', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'contacts_company_details',
					'section_title',
					'Заголовок секции',
					'textarea',
					array(
						'rows'         => 2,
						'instructions' => 'Основной заголовок блока контактов.',
					)
				),
				a4_remont_acf_field(
					'contacts_company_details',
					'section_text',
					'Дополнительный текст',
					'textarea',
					array(
						'rows'         => 4,
						'instructions' => 'Необязательно. Если нужен правый текстовый столбец, заполните это поле. Если оставить пустым, на странице останется только левый заголовок.',
					)
				),
				a4_remont_acf_tab( 'contacts_company_details', 'cards_tab', 'Карточки контактов' ),
				a4_remont_acf_field( 'contacts_company_details', 'phone', 'Телефон', 'text', array( 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_field( 'contacts_company_details', 'email', 'E-mail', 'text', array( 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_field( 'contacts_company_details', 'address', 'Адрес', 'textarea', array( 'rows' => 3, 'wrapper' => array( 'width' => 34 ) ) ),
				a4_remont_acf_field(
					'contacts_company_details',
					'address_url',
					'Ссылка на карту / адрес',
					'url',
					array(
						'instructions' => 'Сюда можно вставить ссылку на Яндекс Карты, 2GIS или Google Maps.',
					)
				),
				a4_remont_acf_tab( 'contacts_company_details', 'social_tab', 'Соцсети и площадки' ),
				a4_remont_acf_field( 'contacts_company_details', 'telegram_url', 'Ссылка на Telegram', 'url', array( 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_field( 'contacts_company_details', 'vk_url', 'Ссылка на VK', 'url', array( 'wrapper' => array( 'width' => 33 ) ) ),
				a4_remont_acf_field( 'contacts_company_details', 'reviews_url', 'Ссылка на отзывы / площадку', 'url', array( 'wrapper' => array( 'width' => 34 ) ) ),
				a4_remont_acf_tab( 'contacts_company_details', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'contacts_company_details', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the contacts page field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_contacts_page_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_contacts_page_sections',
		'title'                 => 'Страница "Контакты"',
		'fields'                => array(
			a4_remont_acf_message(
				'contacts_page',
				'guide',
				'Как работать со страницей контактов',
				'<strong>Порядок настройки страницы /contacts/:</strong><ol><li>Назначьте странице шаблон <strong>Контакты</strong>.</li><li>Ниже управляйте порядком секций через Flexible Content.</li><li>Форма и блок контактов переиспользуют уже существующие шаблоны темы, поэтому редактирование остается предсказуемым.</li><li>Если поля не заполнены, фронтенд сохранит fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_contacts_page_sections',
				'label'        => 'Секции страницы контактов',
				'name'         => 'contacts_page_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Добавляйте и переставляйте секции, чтобы управлять структурой страницы /contacts/. Внутри каждой секции поля уже разложены по вкладкам.',
				'layouts'      => a4_remont_get_contacts_page_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => a4_remont_get_contacts_page_template_slug(),
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
	$option_key  = 'a4_remont_contacts_page_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_contacts_page_field_group', 32 );

/**
 * Render contacts page sections.
 *
 * @return bool
 */
function a4_remont_render_contacts_page_sections() {
	return function_exists( 'a4_remont_render_mapped_flexible_sections' ) && a4_remont_render_mapped_flexible_sections( 'contacts_page_sections', a4_remont_get_contacts_page_section_map() );
}

/**
 * Render default contacts page sections.
 *
 * @return bool
 */
function a4_remont_render_default_contacts_page_sections() {
	return function_exists( 'a4_remont_render_mapped_default_sections' ) && a4_remont_render_mapped_default_sections( a4_remont_get_contacts_page_section_map() );
}

/**
 * Render the contacts page content.
 *
 * @return void
 */
function a4_remont_render_contacts_page_content() {
	if ( function_exists( 'a4_remont_render_homepage_edit_link' ) ) {
		a4_remont_render_homepage_edit_link();
	}

	$has_sections = a4_remont_render_contacts_page_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_contacts_page_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="contacts-page__fallback _container">
		<h1 class="section__title"><?php the_title(); ?></h1>
	</div>
	<?php
}
