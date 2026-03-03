<?php
/**
 * ACF builders for the payment and guarantees page.
 *
 * @package a4-remont
 */

/**
 * Return the payment and guarantees page template slug.
 *
 * @return string
 */
function a4_remont_get_payment_delivery_page_template_slug() {
	return 'page-templates/payment-delivery-page.php';
}

/**
 * Return section map for the payment and guarantees page builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_payment_delivery_page_section_map() {
	return array(
		'payment_delivery_hero'       => array(
			'template' => 'payment-delivery-hero',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/payment-delivery-hero.html',
			),
		),
		'payment_delivery_methods'    => array(
			'template' => 'payment-delivery-methods',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/payment-delivery-methods.html',
			),
		),
		'payment_delivery_stages'     => array(
			'template' => 'payment-delivery-stages',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/payment-delivery-stages.html',
			),
		),
		'payment_delivery_guarantee'  => array(
			'template' => 'payment-delivery-guarantee',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/payment-delivery-guarantee.html',
			),
		),
		'payment_delivery_cases'      => array(
			'template' => 'payment-delivery-cases',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/payment-delivery-cases.html',
			),
		),
		'payment_delivery_insurance'  => array(
			'template' => 'payment-delivery-insurance',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/payment-delivery-insurance.html',
			),
		),
		'payment_delivery_faq'        => array(
			'template' => 'faq',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/faq-2.html',
			),
		),
		'payment_delivery_cta_form'   => array(
			'template' => 'cta-form',
			'args'     => array(
				'fallback_partial' => 'section/payment-delivery/cta-form-3.html',
			),
		),
	);
}

/**
 * Return ACF layouts for the payment and guarantees page.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_payment_delivery_page_layouts() {
	return array(
		a4_remont_acf_layout(
			'payment_delivery_hero',
			'Первый экран',
			array(
				a4_remont_acf_message(
					'payment_delivery_hero',
					'guide',
					'Как работает секция',
					'Это <strong>первый экран страницы оплаты и гарантий</strong>. Здесь задаются основной заголовок, поясняющий текст справа и главная кнопка. Если поля оставить пустыми, тема сохранит fallback из исходной статической верстки.'
				),
				a4_remont_acf_tab( 'payment_delivery_hero', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'payment_delivery_hero',
					'title',
					'Заголовок страницы',
					'textarea',
					array(
						'rows'         => 3,
						'instructions' => 'Главный H1 страницы. Можно переносить строки, чтобы сохранить композицию из макета.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_hero',
					'text',
					'Текст справа',
					'textarea',
					array(
						'rows'         => 6,
						'instructions' => 'Коротко объясните, как у вас устроены расчеты, гарантии и почему это безопасно для клиента.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_hero', 'actions_tab', 'Кнопка' ),
				...a4_remont_acf_action_button_fields(
					'payment_delivery_hero',
					'cta_button',
					'Кнопка действия',
					array(
						'link_instructions' => 'Например: «Хочу проконсультироваться».',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_hero', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_hero', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'payment_delivery_methods',
			'Способы оплаты',
			array(
				a4_remont_acf_message(
					'payment_delivery_methods',
					'guide',
					'Как работает секция',
					'Секция показывает <strong>основные сценарии оплаты</strong> и помогает администратору отдельно объяснить условия для физических и юридических лиц. Карточки ниже можно расширять, если появятся новые варианты.'
				),
				a4_remont_acf_tab( 'payment_delivery_methods', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'payment_delivery_methods',
					'section_title',
					'Заголовок секции',
					'text',
					array(
						'wrapper'      => array( 'width' => 55 ),
						'instructions' => 'Короткий понятный заголовок блока.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_methods',
					'lead',
					'Подводка',
					'textarea',
					array(
						'rows'         => 5,
						'wrapper'      => array( 'width' => 45 ),
						'instructions' => 'Пояснение над карточками: как устроены расчеты и почему они прозрачны.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_methods', 'cards_tab', 'Карточки оплаты' ),
				a4_remont_acf_field(
					'payment_delivery_methods',
					'cards',
					'Карточки способов оплаты',
					'repeater',
					array(
						'button_label' => 'Добавить карточку',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'payment_delivery_methods', 'card_title' ),
						'instructions' => 'Каждая карточка объясняет отдельный сценарий оплаты. Обычно это «Для физических лиц», «Для юридических лиц» и похожие варианты.',
						'sub_fields'   => array(
							a4_remont_acf_field(
								'payment_delivery_methods',
								'card_title',
								'Заголовок карточки',
								'text',
								array(
									'wrapper'      => array( 'width' => 35 ),
									'instructions' => 'Короткий заголовок без перегруза.',
								)
							),
							a4_remont_acf_field(
								'payment_delivery_methods',
								'card_text',
								'Текст карточки',
								'textarea',
								array(
									'rows'         => 5,
									'wrapper'      => array( 'width' => 65 ),
									'instructions' => 'Кратко опишите, как проходит оплата в этом сценарии.',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'payment_delivery_methods', 'media_tab', 'Медиа' ),
				a4_remont_acf_field(
					'payment_delivery_methods',
					'image',
					'Основное изображение',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => 'Картинка слева от текста.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_methods',
					'image_alt',
					'Alt-текст изображения',
					'text',
					array(
						'instructions' => 'Если оставить пустым, будет использован alt из медиабиблиотеки.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_methods', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_methods', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'payment_delivery_stages',
			'Этапы оплаты',
			array(
				a4_remont_acf_message(
					'payment_delivery_stages',
					'guide',
					'Как работает секция',
					'Этот блок объясняет <strong>поэтапную оплату</strong>. Карточки лучше держать в хронологическом порядке. При желании можно заменить декоративные линии для desktop/tablet/mobile.'
				),
				a4_remont_acf_tab( 'payment_delivery_stages', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'payment_delivery_stages',
					'section_title',
					'Заголовок секции',
					'text',
					array(
						'wrapper'      => array( 'width' => 40 ),
						'instructions' => 'Например: «Этапы оплаты».',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_stages',
					'lead',
					'Подводка',
					'textarea',
					array(
						'rows'         => 5,
						'wrapper'      => array( 'width' => 60 ),
						'instructions' => 'Кратко объясните, почему вы работаете без больших авансов и как клиент принимает каждый этап.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_stages', 'items_tab', 'Карточки этапов' ),
				a4_remont_acf_field(
					'payment_delivery_stages',
					'items',
					'Этапы оплаты',
					'repeater',
					array(
						'button_label' => 'Добавить этап',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'payment_delivery_stages', 'item_title' ),
						'instructions' => 'Каждая карточка описывает один этап ремонта и момент оплаты.',
						'sub_fields'   => array(
							a4_remont_acf_field(
								'payment_delivery_stages',
								'item_title',
								'Название этапа',
								'text',
								array(
									'wrapper'      => array( 'width' => 35 ),
									'instructions' => 'Например: «Черновая отделка».',
								)
							),
							a4_remont_acf_field(
								'payment_delivery_stages',
								'item_text',
								'Описание этапа',
								'textarea',
								array(
									'rows'         => 5,
									'wrapper'      => array( 'width' => 65 ),
									'instructions' => 'Опишите, что входит в этап и когда происходит оплата.',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'payment_delivery_stages', 'media_tab', 'Декоративные линии' ),
				a4_remont_acf_field(
					'payment_delivery_stages',
					'line_desktop',
					'Линия для desktop',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => 'Необязательно. Если не заполнить, на desktop линия не будет выведена.',
						'wrapper'       => array( 'width' => 34 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_stages',
					'line_tablet',
					'Линия для tablet',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => 'Необязательно. Используется только на планшетах.',
						'wrapper'       => array( 'width' => 33 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_stages',
					'line_mobile',
					'Линия для mobile',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'instructions'  => 'Необязательно. Используется только на мобильных.',
						'wrapper'       => array( 'width' => 33 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_stages',
					'line_alt',
					'Alt-текст линий',
					'text',
					array(
						'instructions' => 'Можно оставить пустым, тогда будет подставлен безопасный текст по умолчанию.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_stages', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_stages', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'payment_delivery_guarantee',
			'Гарантии',
			array(
				a4_remont_acf_message(
					'payment_delivery_guarantee',
					'guide',
					'Как работает секция',
					'Секция показывает <strong>главное обещание по гарантии</strong> поверх крупного изображения. Хорошо работает для короткого сильного сообщения и одного акцента вроде «24 месяца».'
				),
				a4_remont_acf_tab( 'payment_delivery_guarantee', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'payment_delivery_guarantee',
					'section_title',
					'Заголовок секции',
					'textarea',
					array(
						'rows'         => 3,
						'instructions' => 'Можно переносить строки, если так лучше выглядит по макету.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_guarantee',
					'lead',
					'Описание',
					'textarea',
					array(
						'rows'         => 5,
						'instructions' => 'Основное пояснение о гарантийных обязательствах. Допустимы акценты через <strong>strong</strong>.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_guarantee', 'media_tab', 'Медиа' ),
				a4_remont_acf_field(
					'payment_delivery_guarantee',
					'image_desktop',
					'Изображение для desktop',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_guarantee',
					'image_mobile',
					'Изображение для mobile',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'wrapper'       => array( 'width' => 50 ),
						'instructions'  => 'Необязательно. Если оставить пустым, на мобильном останется desktop-изображение.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_guarantee',
					'image_alt',
					'Alt-текст изображения',
					'text',
					array(
						'instructions' => 'Если оставить пустым, будет использован alt из медиабиблиотеки.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_guarantee', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_guarantee', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'payment_delivery_cases',
			'Гарантийные случаи',
			array(
				a4_remont_acf_message(
					'payment_delivery_cases',
					'guide',
					'Как работает секция',
					'Здесь лучше коротко и честно объяснить, <strong>что входит в гарантию</strong>, а что нет. Это важный доверительный блок, поэтому формулировки должны быть понятными и без юридической перегрузки.'
				),
				a4_remont_acf_tab( 'payment_delivery_cases', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'payment_delivery_cases',
					'section_title',
					'Заголовок секции',
					'text',
					array(
						'instructions' => 'Например: «Что относится к гарантийным случаям».',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cases',
					'section_text',
					'Текст секции',
					'textarea',
					array(
						'rows'         => 5,
						'instructions' => 'Кратко перечислите принцип: что вы исправляете за свой счет и какие случаи не относятся к гарантии.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_cases', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_cases', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'payment_delivery_insurance',
			'Страховка в подарок',
			array(
				a4_remont_acf_message(
					'payment_delivery_insurance',
					'guide',
					'Как работает секция',
					'Секция подчеркивает <strong>дополнительную защиту клиента</strong>: страховку, включенную в договор. Обычно это короткий заголовок, текст и одно акцентное изображение.'
				),
				a4_remont_acf_tab( 'payment_delivery_insurance', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'payment_delivery_insurance',
					'title',
					'Заголовок блока',
					'textarea',
					array(
						'rows'         => 2,
						'wrapper'      => array( 'width' => 45 ),
						'instructions' => 'Короткий акцентный заголовок.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_insurance',
					'text',
					'Описание',
					'textarea',
					array(
						'rows'         => 5,
						'wrapper'      => array( 'width' => 55 ),
						'instructions' => 'Расскажите, что покрывает страховка и почему это выгодно клиенту.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_insurance', 'media_tab', 'Медиа' ),
				a4_remont_acf_field(
					'payment_delivery_insurance',
					'image',
					'Изображение',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_insurance',
					'image_alt',
					'Alt-текст изображения',
					'text',
					array(
						'instructions' => 'Если оставить пустым, будет использован alt из медиабиблиотеки.',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_insurance', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_insurance', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'payment_delivery_faq',
			'FAQ по оплате и гарантиям',
			array(
				a4_remont_acf_message(
					'payment_delivery_faq',
					'guide',
					'Как работает секция',
					'Это <strong>обычный FAQ-блок</strong> с дизайном из страницы оплаты и гарантий. Вопросы лучше формулировать так, как их реально задают клиенты: про этапы оплаты, дополнительные работы, гарантийные случаи и документы.'
				),
				a4_remont_acf_tab( 'payment_delivery_faq', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'payment_delivery_faq',
					'section_title',
					'Заголовок секции',
					'text',
					array(
						'instructions' => 'Например: «Часто задаваемые вопросы по оплате и гарантиям».',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_faq',
					'open_first',
					'Открывать первый вопрос сразу',
					'true_false',
					array(
						'ui'            => 1,
						'default_value' => 1,
						'instructions'  => 'Полезно, если хотите сразу показать самый важный ответ.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_faq',
					'items',
					'Вопросы и ответы',
					'repeater',
					array(
						'button_label' => 'Добавить вопрос',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'payment_delivery_faq', 'question' ),
						'instructions' => 'Держите ответы короткими и практичными. Эта секция не про юридические простыни, а про ясность.',
						'sub_fields'   => array(
							a4_remont_acf_field(
								'payment_delivery_faq',
								'question',
								'Вопрос',
								'text',
								array(
									'instructions' => 'Сформулируйте вопрос так, как его задает клиент.',
								)
							),
							a4_remont_acf_field(
								'payment_delivery_faq',
								'answer',
								'Ответ',
								'textarea',
								array(
									'rows'         => 4,
									'instructions' => 'Короткий понятный ответ без перегруза.',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'payment_delivery_faq', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_faq', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'payment_delivery_cta_form',
			'Форма / CTA',
			array(
				a4_remont_acf_message(
					'payment_delivery_cta_form',
					'guide',
					'Как работает секция',
					'Это <strong>переиспользование общей формы</strong> <code>cta-form</code>. Можно подключить shortcode плагина формы или оставить встроенный fallback-шаблон.'
				),
				a4_remont_acf_tab( 'payment_delivery_cta_form', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'payment_delivery_cta_form', 'title', 'Заголовок', 'textarea', array( 'rows' => 2 ) ),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'lead',
					'Подводка',
					'textarea',
					array(
						'rows'         => 5,
						'instructions' => 'Коротко объясните, зачем оставлять заявку на этой странице.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'brand_text',
					'Текст бренда / подпись',
					'text',
					array(
						'instructions' => 'Если не хотите использовать изображение логотипа, можно оставить текст.',
						'wrapper'      => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'brand_image',
					'Логотип / изображение бренда',
					'image',
					array(
						'return_format' => 'array',
						'preview_size'  => 'medium',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_tab( 'payment_delivery_cta_form', 'form_tab', 'Форма' ),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'form_shortcode',
					'Shortcode формы',
					'textarea',
					array(
						'rows'         => 3,
						'instructions' => 'Если здесь указан shortcode, на фронте будет выведена форма плагина вместо встроенного шаблона.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'name_placeholder',
					'Плейсхолдер поля "Имя"',
					'text',
					array(
						'default_value' => 'Ваше имя',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'phone_placeholder',
					'Плейсхолдер поля "Телефон"',
					'text',
					array(
						'default_value' => '+7 000 000 00 00',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'email_placeholder',
					'Плейсхолдер поля "E-mail"',
					'text',
					array(
						'default_value' => 'E-mail',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'message_placeholder',
					'Плейсхолдер поля "Сообщение"',
					'text',
					array(
						'default_value' => 'Сообщение',
						'wrapper'       => array( 'width' => 50 ),
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'agreement_text',
					'Текст согласия',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'basic',
						'media_upload' => 0,
						'instructions' => 'Текст под чекбоксом. Можно добавить ссылки на политику и обработку персональных данных.',
					)
				),
				a4_remont_acf_field(
					'payment_delivery_cta_form',
					'submit_label',
					'Текст кнопки',
					'text',
					array(
						'default_value' => 'Отправить',
					)
				),
				a4_remont_acf_tab( 'payment_delivery_cta_form', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'payment_delivery_cta_form', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync the payment and guarantees page field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_payment_delivery_page_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_payment_delivery_sections',
		'title'                 => 'Страница "Оплата и гарантии"',
		'fields'                => array(
			a4_remont_acf_message(
				'payment_delivery_page',
				'guide',
				'Как работать со страницей оплаты и гарантий',
				'<strong>Порядок настройки страницы /payment-delivery/:</strong><ol><li>Назначьте странице шаблон <strong>Оплата и гарантии</strong>.</li><li>Ниже добавляйте секции и меняйте их порядок drag-and-drop внутри Flexible Content.</li><li>Если часть полей оставить пустыми, на фронтенде сохранится fallback из исходной статической верстки.</li><li>FAQ и форма внизу страницы переиспользуют уже знакомые шаблоны, поэтому админ не учится новой логике без необходимости.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_payment_delivery_sections',
				'label'        => 'Секции страницы оплаты и гарантий',
				'name'         => 'payment_delivery_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Добавляйте и переставляйте секции, чтобы управлять структурой страницы /payment-delivery/. Внутри каждой секции поля уже разложены по вкладкам для более чистого UX.',
				'layouts'      => a4_remont_get_payment_delivery_page_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => a4_remont_get_payment_delivery_page_template_slug(),
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
	$option_key  = 'a4_remont_payment_delivery_page_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_payment_delivery_page_field_group', 32 );

/**
 * Render payment and guarantees page sections.
 *
 * @return bool
 */
function a4_remont_render_payment_delivery_page_sections() {
	return function_exists( 'a4_remont_render_mapped_flexible_sections' ) && a4_remont_render_mapped_flexible_sections( 'payment_delivery_sections', a4_remont_get_payment_delivery_page_section_map() );
}

/**
 * Render default payment and guarantees page sections.
 *
 * @return bool
 */
function a4_remont_render_default_payment_delivery_page_sections() {
	return function_exists( 'a4_remont_render_mapped_default_sections' ) && a4_remont_render_mapped_default_sections( a4_remont_get_payment_delivery_page_section_map() );
}

/**
 * Render the payment and guarantees page content.
 *
 * @return void
 */
function a4_remont_render_payment_delivery_page_content() {
	if ( function_exists( 'a4_remont_render_homepage_edit_link' ) ) {
		a4_remont_render_homepage_edit_link();
	}

	$has_sections = a4_remont_render_payment_delivery_page_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_payment_delivery_page_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="payment-delivery-page__fallback _container">
		<h1 class="section__title"><?php the_title(); ?></h1>
	</div>
	<?php
}
