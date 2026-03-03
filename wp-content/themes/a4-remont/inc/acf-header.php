<?php
/**
 * ACF builders for the shared site shell settings.
 *
 * @package a4-remont
 */

/**
 * Register the shared site shell options page.
 *
 * @return void
 */
function a4_remont_register_site_shell_options_page() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => 'Шапка и подвал сайта',
			'menu_title' => 'Шапка и подвал',
			'menu_slug'  => a4_remont_get_site_shell_options_slug(),
			'capability' => 'edit_theme_options',
			'redirect'   => false,
			'position'   => 58,
			'icon_url'   => 'dashicons-editor-kitchensink',
		)
	);
}
add_action( 'acf/init', 'a4_remont_register_site_shell_options_page', 5 );

/**
 * Sync the shared header and footer field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_site_shell_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$header_item_type_key = a4_remont_acf_key( 'site_header_item', 'item_type' );
	$footer_nav_source_key = a4_remont_acf_key( 'site_footer', 'nav_source' );

	$field_group = array(
		'key'                   => 'group_a4_remont_site_header',
		'title'                 => 'Шапка и подвал сайта',
		'fields'                => array(
			a4_remont_acf_message(
				'site_shell',
				'guide',
				'Как работать с общими настройками сайта',
				'<strong>Порядок настройки:</strong><ol><li>Во вкладке <strong>Шапка сайта</strong> настройте логотип, телефон, кнопку и навигацию.</li><li>Во вкладке <strong>Подвал сайта</strong> настройте логотип, ссылки, юридические документы, реквизиты и нижнюю строку.</li><li>Если часть полей оставить пустыми, тема использует безопасные резервные значения из уже существующих страниц, архивов и общих настроек сайта.</li></ol>'
			),
			a4_remont_acf_tab( 'site_shell', 'header_main_tab', 'Шапка сайта' ),
			a4_remont_acf_tab( 'site_header', 'branding_tab', 'Логотип и фирменный стиль' ),
			a4_remont_acf_field(
				'site_header',
				'logo_image',
				'Логотип для полной версии сайта',
				'image',
				array(
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'instructions'  => 'Основной логотип в шапке. Если оставить поле пустым, тема попробует взять логотип WordPress или название сайта.',
					'wrapper'       => array( 'width' => 50 ),
				)
			),
			a4_remont_acf_field(
				'site_header',
				'mobile_logo_image',
				'Логотип для мобильного меню',
				'image',
				array(
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'instructions'  => 'Необязательно. Если оставить пустым, в мобильном меню будет использован основной логотип.',
					'wrapper'       => array( 'width' => 50 ),
				)
			),
			a4_remont_acf_field(
				'site_header',
				'logo_alt',
				'Описание логотипа',
				'text',
				array(
					'instructions' => 'Текст для атрибута alt. Если оставить пустым, будет использовано название сайта.',
				)
			),
			a4_remont_acf_tab( 'site_header', 'contacts_tab', 'Телефон и основная кнопка' ),
			a4_remont_acf_field(
				'site_header',
				'phone_label',
				'Телефон',
				'text',
				array(
					'default_value' => '+7 000 000 00 00',
					'instructions'  => 'Будет показан и в полной версии шапки, и в мобильном меню. Ссылка tel: собирается автоматически.',
					'wrapper'       => array( 'width' => 35 ),
				)
			),
			...a4_remont_acf_action_button_fields(
				'site_header',
				'cta_button',
				'Основная кнопка',
				array(
					'action_label'              => 'Что делает кнопка',
					'action_instructions'       => 'Эта кнопка показывается и в полной версии шапки, и в мобильном меню.',
					'link_instructions'         => 'Используйте ссылку, если кнопка должна вести на отдельную страницу. Для формы связи лучше выбрать модальное окно.',
					'popup_target_label'        => 'Какое модальное окно открывать',
					'popup_target_instructions' => 'Список берется из раздела "Модальные окна".',
					'popup_label_label'         => 'Текст кнопки',
					'popup_label_instructions'  => 'Один и тот же текст будет использован и в полной версии шапки, и в мобильном меню.',
					'default_action'            => 'popup',
					'popup_label_default'       => 'Связаться с нами',
					'action_wrapper'            => array( 'width' => 25 ),
					'link_wrapper'              => array( 'width' => 75 ),
				)
			),
			a4_remont_acf_tab( 'site_header', 'menu_tab', 'Навигация' ),
			a4_remont_acf_field(
				'site_header',
				'menu_items',
				'Пункты меню',
				'repeater',
				array(
					'button_label' => 'Добавить пункт меню',
					'layout'       => 'block',
					'collapsed'    => a4_remont_acf_key( 'site_header_item', 'item_label' ),
					'instructions' => 'Здесь настраивается вся шапка сайта: один и тот же список используется и в полной версии, и в мобильном меню.',
					'sub_fields'   => array(
						a4_remont_acf_field(
							'site_header_item',
							'item_label',
							'Название пункта',
							'text',
							array(
								'wrapper'      => array( 'width' => 35 ),
								'instructions' => 'Текст верхнего пункта меню.',
							)
						),
						a4_remont_acf_field(
							'site_header_item',
							'item_type',
							'Тип пункта',
							'button_group',
							array(
								'choices'       => array(
									'link'     => 'Обычная ссылка',
									'dropdown' => 'Пункт с выпадающим меню',
								),
								'default_value' => 'link',
								'wrapper'       => array( 'width' => 25 ),
								'instructions'  => 'Если выбрать выпадающее меню, ниже появятся колонки и ссылки подменю.',
							)
						),
						a4_remont_acf_field(
							'site_header_item',
							'item_link',
							'Ссылка пункта',
							'link',
							array(
								'wrapper'      => array( 'width' => 40 ),
								'instructions' => 'Для пункта с выпадающим меню это необязательная страница верхнего уровня. Если оставить поле пустым, верхний пункт будет только раскрывать подменю.',
							)
						),
						a4_remont_acf_field(
							'site_header_item',
							'dropdown_columns',
							'Колонки выпадающего меню',
							'repeater',
							array(
								'button_label'      => 'Добавить колонку меню',
								'layout'            => 'block',
								'collapsed'         => a4_remont_acf_key( 'site_header_column', 'column_title' ),
								'instructions'      => 'Каждая колонка становится отдельным блоком в выпадающем меню. Можно сделать одну или несколько колонок.',
								'conditional_logic' => array(
									array(
										array(
											'field'    => $header_item_type_key,
											'operator' => '==',
											'value'    => 'dropdown',
										),
									),
								),
								'sub_fields'        => array(
									a4_remont_acf_field(
										'site_header_column',
										'column_title',
										'Заголовок колонки',
										'text',
										array(
											'instructions' => 'Например: "Ремонт" или "Дизайн". Можно оставить пустым, если колонка должна состоять только из ссылок.',
										)
									),
									a4_remont_acf_field(
										'site_header_column',
										'column_links',
										'Ссылки колонки',
										'repeater',
										array(
											'button_label' => 'Добавить ссылку',
											'layout'       => 'row',
											'collapsed'    => a4_remont_acf_key( 'site_header_column_link', 'column_link' ),
											'sub_fields'   => array(
												a4_remont_acf_field(
													'site_header_column_link',
													'column_link',
													'Ссылка',
													'link',
													array(
														'instructions' => 'Одна строка в выпадающем меню.',
													)
												),
											),
										)
									),
								),
							)
						),
					),
				)
			),
			a4_remont_acf_tab( 'site_shell', 'footer_main_tab', 'Подвал сайта' ),
			a4_remont_acf_tab( 'site_footer', 'branding_tab', 'Логотип и контакты' ),
			a4_remont_acf_field(
				'site_footer',
				'footer_logo_image',
				'Логотип в подвале',
				'image',
				array(
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'instructions'  => 'Если оставить пустым, подвал попробует использовать логотип из шапки или название сайта.',
					'wrapper'       => array( 'width' => 50 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_logo_alt',
				'Описание логотипа в подвале',
				'text',
				array(
					'instructions' => 'Текст для атрибута alt у логотипа в подвале.',
					'wrapper'      => array( 'width' => 50 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_phone_label',
				'Телефон в подвале',
				'text',
				array(
					'instructions' => 'Если оставить поле пустым, будет использован телефон из шапки сайта.',
					'wrapper'      => array( 'width' => 40 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_email',
				'Электронная почта',
				'email',
				array(
					'instructions' => 'Будет показан в блоке реквизитов.',
					'wrapper'      => array( 'width' => 40 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_socials',
				'Социальные ссылки',
				'repeater',
				array(
					'button_label' => 'Добавить социальную ссылку',
					'layout'       => 'block',
					'collapsed'    => a4_remont_acf_key( 'site_footer_social', 'social_network' ),
					'instructions' => 'Эти иконки выводятся рядом с телефоном в подвале сайта.',
					'sub_fields'   => array(
						a4_remont_acf_field(
							'site_footer_social',
							'social_network',
							'Тип иконки',
							'button_group',
							array(
								'choices'       => array(
									'telegram' => 'Telegram',
									'vk'       => 'VK',
									'reviews'  => 'R',
								),
								'default_value' => 'telegram',
								'wrapper'       => array( 'width' => 35 ),
							)
						),
						a4_remont_acf_field(
							'site_footer_social',
							'social_label',
							'Подпись для доступности',
							'text',
							array(
								'instructions' => 'Используется в aria-label. Если оставить пустым, подпись будет взята из типа иконки.',
								'wrapper'      => array( 'width' => 25 ),
							)
						),
						a4_remont_acf_field(
							'site_footer_social',
							'social_link',
							'Ссылка',
							'link',
							array(
								'instructions' => 'Ссылка, на которую ведет иконка.',
								'wrapper'      => array( 'width' => 40 ),
							)
						),
					),
				)
			),
			a4_remont_acf_tab( 'site_footer', 'nav_tab', 'Навигация' ),
			a4_remont_acf_field(
				'site_footer',
				'footer_nav_source',
				'Откуда брать ссылки',
				'button_group',
				array(
					'choices'       => array(
						'header' => 'Использовать пункты из шапки',
						'custom' => 'Задать ссылки отдельно',
					),
					'default_value' => 'header',
					'instructions'  => 'Чтобы не дублировать ссылки вручную, можно использовать верхнее меню сайта. При необходимости подвал можно настроить отдельно.',
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_nav_columns',
				'Колонки навигации в подвале',
				'repeater',
				array(
					'button_label'      => 'Добавить колонку',
					'layout'            => 'block',
					'collapsed'         => a4_remont_acf_key( 'site_footer_column', 'column_title' ),
					'instructions'      => 'Используется только если выше выбран режим "Задать ссылки отдельно".',
					'conditional_logic' => array(
						array(
							array(
								'field'    => $footer_nav_source_key,
								'operator' => '==',
								'value'    => 'custom',
							),
						),
					),
					'sub_fields'        => array(
						a4_remont_acf_field(
							'site_footer_column',
							'column_title',
							'Заголовок колонки',
							'text',
							array(
								'instructions' => 'Необязательно. Можно оставить пустым, если в колонке нужны только ссылки.',
							)
						),
						a4_remont_acf_field(
							'site_footer_column',
							'column_links',
							'Ссылки колонки',
							'repeater',
							array(
								'button_label' => 'Добавить ссылку',
								'layout'       => 'row',
								'collapsed'    => a4_remont_acf_key( 'site_footer_column_link', 'column_link' ),
								'sub_fields'   => array(
									a4_remont_acf_field(
										'site_footer_column_link',
										'column_link',
										'Ссылка',
										'link'
									),
								),
							)
						),
					),
				)
			),
			a4_remont_acf_tab( 'site_footer', 'legal_tab', 'Юридические ссылки' ),
			a4_remont_acf_field(
				'site_footer',
				'footer_legal_links',
				'Ссылки на документы',
				'repeater',
				array(
					'button_label' => 'Добавить документ',
					'layout'       => 'table',
					'collapsed'    => a4_remont_acf_key( 'site_footer_legal', 'legal_link' ),
					'instructions' => 'Если оставить пустым, тема выведет ссылку на страницу политики конфиденциальности.',
					'sub_fields'   => array(
						a4_remont_acf_field(
							'site_footer_legal',
							'legal_link',
							'Ссылка',
							'link'
						),
					),
				)
			),
			a4_remont_acf_tab( 'site_footer', 'details_tab', 'Реквизиты' ),
			a4_remont_acf_field(
				'site_footer',
				'footer_address',
				'Адрес',
				'textarea',
				array(
					'rows'         => 3,
					'instructions' => 'Будет показан в отдельной колонке блока реквизитов.',
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_company_name',
				'Название компании',
				'text',
				array(
					'wrapper' => array( 'width' => 40 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_inn',
				'ИНН',
				'text',
				array(
					'wrapper' => array( 'width' => 30 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_kpp',
				'КПП',
				'text',
				array(
					'wrapper' => array( 'width' => 30 ),
				)
			),
			a4_remont_acf_tab( 'site_footer', 'bottom_tab', 'Нижняя строка' ),
			a4_remont_acf_field(
				'site_footer',
				'footer_copyright_text',
				'Текст копирайта',
				'text',
				array(
					'instructions' => 'Например: ©2026 - Официальный сайт «A4 Ремонт».',
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_developer_text',
				'Подпись блока разработчика',
				'text',
				array(
					'instructions' => 'Например: Сайт разработан компанией DS-ART.',
					'wrapper'      => array( 'width' => 45 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_developer_link',
				'Ссылка блока разработчика',
				'link',
				array(
					'instructions' => 'Необязательно. Если оставить пустым, подпись останется обычным текстом.',
					'wrapper'      => array( 'width' => 55 ),
				)
			),
			a4_remont_acf_field(
				'site_footer',
				'footer_developer_logo',
				'Иконка блока разработчика',
				'image',
				array(
					'return_format' => 'array',
					'preview_size'  => 'thumbnail',
					'instructions'  => 'Необязательно. Если оставить пустым, будет использована иконка из текущей верстки.',
				)
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => a4_remont_get_site_shell_options_slug(),
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
	$option_key  = 'a4_remont_site_shell_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_site_shell_field_group', 18 );

/**
 * Check whether the current admin page is the shared site shell options page.
 *
 * @return bool
 */
function a4_remont_is_site_shell_options_page() {
	if ( ! is_admin() ) {
		return false;
	}

	$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

	return a4_remont_get_site_shell_options_slug() === $page;
}

/**
 * Print custom styles for the shared site shell options page.
 *
 * @return void
 */
function a4_remont_site_shell_admin_styles() {
	if ( ! a4_remont_is_site_shell_options_page() ) {
		return;
	}
	?>
	<style>
		.a4-shell-tabs {
			margin: 20px 0 24px;
			padding: 20px;
			border: 1px solid #dcdcde;
			border-radius: 18px;
			background: #fff;
			box-shadow: 0 10px 30px rgba(29, 35, 39, 0.04);
		}

		.a4-shell-tabs__parents,
		.a4-shell-tabs__children {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
		}

		.a4-shell-tabs__children {
			margin-top: 14px;
			padding-top: 14px;
			border-top: 1px solid #e6e8eb;
		}

		.a4-shell-tabs__button {
			appearance: none;
			border: 1px solid #d0d7de;
			border-radius: 999px;
			background: #fff;
			color: #1d2327;
			cursor: pointer;
			font-size: 13px;
			font-weight: 600;
			line-height: 1.2;
			padding: 10px 16px;
			transition: background-color .2s ease, border-color .2s ease, color .2s ease, box-shadow .2s ease;
		}

		.a4-shell-tabs__button:hover {
			border-color: #b9c2cb;
			background: #f6f7f7;
		}

		.a4-shell-tabs__button.is-active {
			border-color: #1d2327;
			background: #1d2327;
			color: #fff;
			box-shadow: 0 8px 20px rgba(29, 35, 39, 0.14);
		}

		.a4-shell-tabs__button--child.is-active {
			border-color: #c09b57;
			background: #f4ead7;
			color: #6f521e;
			box-shadow: none;
		}

		.a4-shell-tabs__button--child {
			font-weight: 500;
		}

		.postbox .acf-field-tab[data-key="field_a4_remont_site_shell_header_main_tab"],
		.postbox .acf-field-tab[data-key="field_a4_remont_site_shell_footer_main_tab"] {
			display: none;
		}

		@media (max-width: 782px) {
			.a4-shell-tabs {
				padding: 16px;
				border-radius: 14px;
			}

			.a4-shell-tabs__button {
				width: 100%;
				justify-content: center;
				text-align: center;
			}

			.a4-shell-tabs__parents,
			.a4-shell-tabs__children {
				flex-direction: column;
			}
		}
	</style>
	<?php
}
add_action( 'admin_head', 'a4_remont_site_shell_admin_styles' );

/**
 * Print custom hierarchical tabs UX for the shared site shell options page.
 *
 * @return void
 */
function a4_remont_site_shell_admin_tabs_script() {
	if ( ! a4_remont_is_site_shell_options_page() ) {
		return;
	}
	?>
	<script>
		(function () {
			const parentTabs = {
				field_a4_remont_site_shell_header_main_tab: 'Шапка сайта',
				field_a4_remont_site_shell_footer_main_tab: 'Подвал сайта',
			};

			function getOriginalWrap() {
				return document.querySelector('.acf-tab-wrap.-top');
			}

			function getActiveOriginalTabKey(wrap) {
				if (!wrap) return '';

				const selected = wrap.querySelector('li.active a[data-key], li.-active a[data-key], a.-active[data-key]');
				return selected ? selected.dataset.key : '';
			}

			function buildShellTabs() {
				const wrap = getOriginalWrap();
				if (!wrap || wrap.dataset.a4ShellEnhanced === '1') {
					return;
				}

				const originalTabs = Array.from(wrap.querySelectorAll('a[data-key]'));
				if (!originalTabs.length) {
					return;
				}

				const groups = {};
				let currentParentKey = '';

				originalTabs.forEach((tabLink) => {
					const key = tabLink.dataset.key || '';

					if (parentTabs[key]) {
						currentParentKey = key;
						groups[key] = [];
						return;
					}

					if (currentParentKey) {
						groups[currentParentKey].push(tabLink);
					}
				});

				const parentKeys = Object.keys(groups);
				if (!parentKeys.length) {
					return;
				}

				wrap.dataset.a4ShellEnhanced = '1';
				wrap.style.display = 'none';

				const shellTabs = document.createElement('div');
				shellTabs.className = 'a4-shell-tabs';

				const parentNav = document.createElement('div');
				parentNav.className = 'a4-shell-tabs__parents';

				const childNav = document.createElement('div');
				childNav.className = 'a4-shell-tabs__children';

				shellTabs.appendChild(parentNav);
				shellTabs.appendChild(childNav);

				wrap.parentNode.insertBefore(shellTabs, wrap);

				const lastChildByParent = {};

				function syncState() {
					const activeKey = getActiveOriginalTabKey(wrap);
					const activeParent = shellTabs.dataset.activeParent || parentKeys[0];

					parentNav.querySelectorAll('.a4-shell-tabs__button').forEach((button) => {
						button.classList.toggle('is-active', button.dataset.parentKey === activeParent);
					});

					childNav.querySelectorAll('.a4-shell-tabs__button').forEach((button) => {
						button.classList.toggle('is-active', button.dataset.key === activeKey);
					});
				}

				function activateChildTab(parentKey, childKey) {
					const childTabs = groups[parentKey] || [];
					const targetTab = childTabs.find((tabLink) => tabLink.dataset.key === childKey) || childTabs[0];

					if (!targetTab) {
						syncState();
						return;
					}

					lastChildByParent[parentKey] = targetTab.dataset.key;
					targetTab.click();
					window.requestAnimationFrame(syncState);
				}

				function renderChildNav(parentKey) {
					childNav.innerHTML = '';

					const childTabs = groups[parentKey] || [];

					childTabs.forEach((tabLink) => {
						const button = document.createElement('button');
						button.type = 'button';
						button.className = 'a4-shell-tabs__button a4-shell-tabs__button--child';
						button.textContent = (tabLink.textContent || '').trim();
						button.dataset.key = tabLink.dataset.key || '';
						button.addEventListener('click', function () {
							activateChildTab(parentKey, button.dataset.key || '');
						});
						childNav.appendChild(button);
					});
				}

				function activateParent(parentKey, shouldActivateChild) {
					shellTabs.dataset.activeParent = parentKey;
					renderChildNav(parentKey);
					syncState();

					if (!shouldActivateChild) {
						return;
					}

					const rememberedChild = lastChildByParent[parentKey] || getActiveOriginalTabKey(wrap);
					activateChildTab(parentKey, rememberedChild);
				}

				parentKeys.forEach((parentKey) => {
					const button = document.createElement('button');
					button.type = 'button';
					button.className = 'a4-shell-tabs__button a4-shell-tabs__button--parent';
					button.textContent = parentTabs[parentKey];
					button.dataset.parentKey = parentKey;
					button.addEventListener('click', function () {
						activateParent(parentKey, true);
					});
					parentNav.appendChild(button);
				});

				wrap.addEventListener('click', function () {
					window.requestAnimationFrame(function () {
						const activeKey = getActiveOriginalTabKey(wrap);
						const matchedParent = parentKeys.find((parentKey) => {
							return (groups[parentKey] || []).some((tabLink) => tabLink.dataset.key === activeKey);
						});

						if (matchedParent && matchedParent !== shellTabs.dataset.activeParent) {
							shellTabs.dataset.activeParent = matchedParent;
							renderChildNav(matchedParent);
						}

						if (matchedParent && activeKey) {
							lastChildByParent[matchedParent] = activeKey;
						}

						syncState();
					});
				}, true);

				const initialActiveKey = getActiveOriginalTabKey(wrap);
				const initialParent = parentKeys.find((parentKey) => {
					return (groups[parentKey] || []).some((tabLink) => tabLink.dataset.key === initialActiveKey);
				}) || parentKeys[0];

				activateParent(initialParent, true);
			}

			if (window.acf && typeof window.acf.addAction === 'function') {
				window.acf.addAction('ready', buildShellTabs);
				window.acf.addAction('append', buildShellTabs);
			} else {
				document.addEventListener('DOMContentLoaded', buildShellTabs);
			}
		})();
	</script>
	<?php
}
add_action( 'admin_footer', 'a4_remont_site_shell_admin_tabs_script' );
