<?php
/**
 * ACF builder for the privacy policy page.
 *
 * @package a4-remont
 */

/**
 * Return the privacy policy page template slug.
 *
 * @return string
 */
function a4_remont_get_privacy_policy_template_slug() {
	return 'page-templates/privacy-policy-page.php';
}

/**
 * Return section map for the privacy policy page builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_privacy_policy_section_map() {
	return array(
		'policy_content' => array(
			'template' => 'policy-content',
			'args'     => array(
				'fallback_partial' => 'section/privacy-policy/policy-content.html',
			),
		),
	);
}

/**
 * Return ACF layouts for the privacy policy page.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_privacy_policy_layouts() {
	return array(
		a4_remont_acf_layout(
			'policy_content',
			'Содержимое политики',
			array(
				a4_remont_acf_message(
					'policy_content',
					'guide',
					'Как работает секция',
					'Это <strong>основной юридический блок страницы политики конфиденциальности</strong>. Здесь удобно вести страницу как цельный документ: отдельно заполнить <strong>вводный текст</strong>, а ниже добавить тематические разделы с подзаголовками и форматированным содержимым. Если поля оставить пустыми, тема покажет fallback из исходной статической верстки.'
				),
				a4_remont_acf_tab( 'policy_content', 'content_tab', 'Контент' ),
				a4_remont_acf_field(
					'policy_content',
					'title',
					'Главный заголовок',
					'textarea',
					array(
						'rows'         => 2,
						'instructions' => 'Основной H1 страницы. Обычно это "Политика конфиденциальности" или близкая формулировка.',
					)
				),
				a4_remont_acf_field(
					'policy_content',
					'intro_content',
					'Вводный текст',
					'wysiwyg',
					array(
						'tabs'         => 'visual',
						'toolbar'      => 'full',
						'media_upload' => 0,
						'instructions' => 'Первый смысловой блок перед списком разделов. Здесь обычно размещают 1-3 абзаца с общей информацией о политике.',
					)
				),
				a4_remont_acf_field(
					'policy_content',
					'blocks',
					'Разделы документа',
					'repeater',
					array(
						'button_label' => 'Добавить раздел',
						'layout'       => 'row',
						'collapsed'    => a4_remont_acf_key( 'policy_content', 'subtitle' ),
						'instructions' => 'Добавляйте разделы в нужном порядке. Каждый раздел может содержать подзаголовок и форматированный текст со списками, ссылками и акцентами.',
						'sub_fields'   => array(
							a4_remont_acf_field(
								'policy_content',
								'subtitle',
								'Подзаголовок раздела',
								'text',
								array(
									'instructions' => 'Например: "Какие данные мы собираем", "Срок хранения данных", "Передача третьим лицам".',
								)
							),
							a4_remont_acf_field(
								'policy_content',
								'content',
								'Содержимое раздела',
								'wysiwyg',
								array(
									'tabs'         => 'visual',
									'toolbar'      => 'full',
									'media_upload' => 0,
									'instructions' => 'Можно использовать списки, ссылки и базовое форматирование. Для юридического текста лучше избегать декоративной перегрузки.',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'policy_content', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field(
					'policy_content',
					'section_id',
					'HTML-якорь секции',
					'text',
					array(
						'instructions' => 'Необязательно. Нужен только если на страницу будут вести якорные ссылки.',
					)
				),
			)
		),
	);
}

/**
 * Sync the privacy policy field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_privacy_policy_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_privacy_policy_sections',
		'title'                 => 'Страница "Политика конфиденциальности"',
		'fields'                => array(
			a4_remont_acf_message(
				'privacy_policy',
				'guide',
				'Как работать со страницей политики',
				'<strong>Порядок настройки страницы:</strong><ol><li>Назначьте странице шаблон <strong>Политика конфиденциальности</strong>.</li><li>Ниже добавьте блок <strong>Содержимое политики</strong>.</li><li>Сначала заполните вводный текст, затем добавляйте юридические разделы в нужном порядке.</li><li>Если часть полей пока пустая, тема сохранит fallback из исходной статической верстки.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_privacy_policy_sections',
				'label'        => 'Секции страницы',
				'name'         => 'privacy_policy_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Для этой страницы обычно достаточно одной секции "Содержимое политики". Если потребуется, в будущем сюда можно добавить дополнительные юридические блоки без смены архитектуры.',
				'layouts'      => a4_remont_get_privacy_policy_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'page_template',
					'operator' => '==',
					'value'    => a4_remont_get_privacy_policy_template_slug(),
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
	$option_key  = 'a4_remont_privacy_policy_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_privacy_policy_field_group', 32 );

/**
 * Render privacy policy flexible sections.
 *
 * @return bool
 */
function a4_remont_render_privacy_policy_sections() {
	return function_exists( 'a4_remont_render_mapped_flexible_sections' ) && a4_remont_render_mapped_flexible_sections( 'privacy_policy_sections', a4_remont_get_privacy_policy_section_map() );
}

/**
 * Render privacy policy fallback sections.
 *
 * @return bool
 */
function a4_remont_render_default_privacy_policy_sections() {
	return function_exists( 'a4_remont_render_mapped_default_sections' ) && a4_remont_render_mapped_default_sections( a4_remont_get_privacy_policy_section_map() );
}

/**
 * Render privacy policy page content.
 *
 * @return void
 */
function a4_remont_render_privacy_policy_page_content() {
	if ( function_exists( 'a4_remont_render_homepage_edit_link' ) ) {
		a4_remont_render_homepage_edit_link();
	}

	$has_sections = a4_remont_render_privacy_policy_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_privacy_policy_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<div class="policy-content__container _container">
		<div class="policy-content__inner">
			<h1 class="section__title policy-content__title"><?php the_title(); ?></h1>
		</div>
	</div>
	<?php
}
