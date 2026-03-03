<?php
/**
 * ACF builders for global site popups.
 *
 * @package a4-remont
 */

/**
 * Return the popup options page slug.
 *
 * @return string
 */
function a4_remont_get_popups_options_slug() {
	return 'a4-remont-modals';
}

/**
 * Register the popup options page.
 *
 * @return void
 */
function a4_remont_register_popups_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title'  => 'Модальные окна',
			'menu_title'  => 'Модальные окна',
			'menu_slug'   => a4_remont_get_popups_options_slug(),
			'capability'  => 'edit_theme_options',
			'redirect'    => false,
			'position'    => 59,
			'icon_url'    => 'dashicons-format-chat',
		)
	);
}
add_action( 'acf/init', 'a4_remont_register_popups_options_page', 5 );

/**
 * Populate popup target select fields with current popup choices.
 *
 * @param array<string,mixed> $field ACF field config.
 * @return array<string,mixed>
 */
function a4_remont_populate_popup_target_choices( $field ) {
	if ( empty( $field['name'] ) || ! is_string( $field['name'] ) ) {
		return $field;
	}

	if ( ! str_ends_with( $field['name'], '_popup_target' ) ) {
		return $field;
	}

	$field['choices'] = a4_remont_get_popup_choices();

	if ( empty( $field['default_value'] ) ) {
		$field['default_value'] = a4_remont_get_default_popup_key();
	}

	return $field;
}
add_filter( 'acf/load_field', 'a4_remont_populate_popup_target_choices' );

/**
 * Sync popup options into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_popups_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_site_popups',
		'title'                 => 'Модальные окна',
		'fields'                => array(
			a4_remont_acf_message(
				'site_popups',
				'guide',
				'Как работать с модальными окнами',
				'<strong>Порядок настройки модальных окон:</strong><ol><li>Добавьте одно или несколько модальных окон ниже.</li><li>Для каждого окна задайте <strong>внутренний ключ</strong> латиницей, например <code>callback-popup</code> или <code>estimate-popup</code>.</li><li>Затем на нужных CTA-кнопках в секциях выберите действие <strong>Открыть модальное окно</strong> и укажите, какое окно нужно открыть.</li><li>Если список окон пока пустой, тема все равно покажет встроенное fallback-окно из исходной статики.</li></ol>'
			),
			a4_remont_acf_field(
				'site_popups',
				'site_popups',
				'Список модальных окон',
				'repeater',
				array(
					'button_label' => 'Добавить модальное окно',
					'layout'       => 'block',
					'collapsed'    => a4_remont_acf_key( 'site_popups', 'popup_admin_label' ),
					'instructions' => 'Одно модальное окно = одна глобальная форма. Кнопки на страницах потом смогут открывать нужное окно по его ключу.',
					'sub_fields'   => array(
						a4_remont_acf_tab( 'site_popups', 'identity_tab', 'Идентификация' ),
						a4_remont_acf_field(
							'site_popups',
							'popup_admin_label',
							'Название окна в админке',
							'text',
							array(
								'wrapper'      => array( 'width' => 50 ),
								'instructions' => 'Человеческое название для редактора, например: "Обратный звонок" или "Расчет стоимости".',
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'popup_key',
							'Внутренний ключ окна',
							'text',
							array(
								'wrapper'      => array( 'width' => 50 ),
								'instructions' => 'Латиница, цифры и дефисы. Пример: <code>callback-popup</code>. По этому ключу кнопки будут открывать модальное окно.',
							)
						),
						a4_remont_acf_tab( 'site_popups', 'content_tab', 'Контент окна' ),
						a4_remont_acf_field(
							'site_popups',
							'trigger_label',
							'Текст кнопки по умолчанию',
							'text',
							array(
								'wrapper'      => array( 'width' => 35 ),
								'instructions' => 'Используется как fallback, если у кнопки-триггера не задан свой текст.',
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'popup_title',
							'Заголовок окна',
							'textarea',
							array(
								'rows'         => 3,
								'wrapper'      => array( 'width' => 65 ),
								'instructions' => 'Главный заголовок внутри модального окна. Можно переносить строки.',
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'popup_lead',
							'Подводка / описание',
							'textarea',
							array(
								'rows'         => 5,
								'instructions' => 'Короткое описание рядом с формой: зачем оставлять заявку и что произойдет дальше.',
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'popup_logo',
							'Логотип / изображение',
							'image',
							array(
								'return_format' => 'array',
								'preview_size'  => 'medium',
								'instructions'  => 'Необязательно. Если не заполнить, будет использован логотип из статического fallback-окна.',
							)
						),
						a4_remont_acf_tab( 'site_popups', 'form_tab', 'Форма' ),
						a4_remont_acf_field(
							'site_popups',
							'form_shortcode',
							'Shortcode формы',
							'textarea',
							array(
								'rows'         => 3,
								'instructions' => 'Необязательно. Если заполнить, внутри модального окна будет выведена форма плагина вместо встроенного шаблона.',
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'name_placeholder',
							'Плейсхолдер поля "Имя"',
							'text',
							array(
								'default_value' => 'Ваше имя',
								'wrapper'       => array( 'width' => 25 ),
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'phone_placeholder',
							'Плейсхолдер поля "Телефон"',
							'text',
							array(
								'default_value' => '+7 000 000 00 00',
								'wrapper'       => array( 'width' => 25 ),
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'email_placeholder',
							'Плейсхолдер поля "E-mail"',
							'text',
							array(
								'default_value' => 'E-mail',
								'wrapper'       => array( 'width' => 25 ),
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'message_placeholder',
							'Плейсхолдер поля "Сообщение"',
							'text',
							array(
								'default_value' => 'Сообщение',
								'wrapper'       => array( 'width' => 25 ),
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'submit_label',
							'Текст кнопки отправки',
							'text',
							array(
								'default_value' => 'Отправить',
								'wrapper'       => array( 'width' => 35 ),
							)
						),
						a4_remont_acf_field(
							'site_popups',
							'agreement_text',
							'Текст согласия',
							'wysiwyg',
							array(
								'tabs'         => 'visual',
								'toolbar'      => 'basic',
								'media_upload' => 0,
								'instructions' => 'Текст под чекбоксом. Можно добавить ссылки на политику конфиденциальности и обработку персональных данных.',
							)
						),
					),
				)
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => a4_remont_get_popups_options_slug(),
				),
			),
		),
		'position'              => 'normal',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	);

	$group_id    = a4_remont_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = 'a4_remont_popups_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_popups_field_group', 18 );
