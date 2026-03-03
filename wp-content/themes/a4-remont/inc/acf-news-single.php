<?php
/**
 * ACF builders for single news pages.
 *
 * @package a4-remont
 */

/**
 * Return the layout map for the single news builder.
 *
 * @return array<string, string|array<string,mixed>>
 */
function a4_remont_get_news_single_section_map() {
	return array(
		'news_single_hero'    => 'news-single-hero',
		'news_single_content' => 'news-single-content',
	);
}

/**
 * Return ACF layouts for single news pages.
 *
 * @return array<int, array<string,mixed>>
 */
function a4_remont_get_news_single_layouts() {
	$block_list_type_key  = a4_remont_acf_key( 'news_single_content_block', 'list_type' );
	$block_media_type_key = a4_remont_acf_key( 'news_single_content_block', 'media_type' );

	return array(
		a4_remont_acf_layout(
			'news_single_hero',
			'Первый экран новости',
			array(
				a4_remont_acf_message(
					'news_single_hero',
					'guide',
					'Как работает секция',
					'Если оставить заголовок, лид, дату или изображение пустыми, секция сможет использовать данные самой записи: заголовок новости, краткое описание, дату публикации и миниатюру.'
				),
				a4_remont_acf_tab( 'news_single_hero', 'content_tab', 'Контент' ),
				a4_remont_acf_field( 'news_single_hero', 'badge_prefix', 'Префикс даты', 'text', array( 'default_value' => 'Дата публикации:', 'wrapper' => array( 'width' => 40 ) ) ),
				a4_remont_acf_field( 'news_single_hero', 'share_button_label', 'Текст кнопки "Поделиться"', 'text', array( 'default_value' => 'Поделиться', 'wrapper' => array( 'width' => 30 ) ) ),
				a4_remont_acf_field( 'news_single_hero', 'share_tooltip_title', 'Заголовок всплывающего блока', 'text', array( 'default_value' => 'Поделиться', 'wrapper' => array( 'width' => 30 ) ) ),
				a4_remont_acf_field( 'news_single_hero', 'title', 'Заголовок H1', 'textarea', array( 'rows' => 2, 'instructions' => 'Можно оставить пустым, тогда будет использован заголовок записи.' ) ),
				a4_remont_acf_field( 'news_single_hero', 'lead', 'Подводка', 'textarea', array( 'rows' => 5, 'instructions' => 'Если оставить пустым, будет использовано краткое описание записи.' ) ),
				a4_remont_acf_tab( 'news_single_hero', 'media_tab', 'Медиа' ),
				a4_remont_acf_field( 'news_single_hero', 'image', 'Главное изображение', 'image', array( 'return_format' => 'array', 'preview_size' => 'medium', 'instructions' => 'Если не заполнять, будет использована миниатюра записи новости.' ) ),
				a4_remont_acf_tab( 'news_single_hero', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'news_single_hero', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
		a4_remont_acf_layout(
			'news_single_content',
			'Контент статьи',
			array(
				a4_remont_acf_message(
					'news_single_content',
					'guide',
					'Как работает секция',
					'Секция собирается из блоков статьи. Для каждого блока можно задать заголовок, текст, список, медиа и подпись. Если блоки оставить пустыми, тема сможет вывести стандартное содержимое записи или fallback из статической верстки.'
				),
				a4_remont_acf_tab( 'news_single_content', 'content_tab', 'Структура статьи' ),
				a4_remont_acf_field(
					'news_single_content',
					'blocks',
					'Блоки статьи',
					'repeater',
					array(
						'button_label' => 'Добавить блок',
						'layout'       => 'block',
						'collapsed'    => a4_remont_acf_key( 'news_single_content_block', 'title' ),
						'instructions' => 'Собирайте статью блоками. Порядок блоков можно менять перетаскиванием.',
						'sub_fields'   => array(
							a4_remont_acf_field( 'news_single_content_block', 'title', 'Заголовок блока', 'text' ),
							a4_remont_acf_field(
								'news_single_content_block',
								'intro_content',
								'Основной текст',
								'wysiwyg',
								array(
									'tabs'         => 'visual',
									'toolbar'      => 'basic',
									'media_upload' => 0,
									'instructions' => 'Параграфы до списка и изображений.',
								)
							),
							a4_remont_acf_field(
								'news_single_content_block',
								'highlight_content',
								'Акцентный текст',
								'wysiwyg',
								array(
									'tabs'         => 'visual',
									'toolbar'      => 'basic',
									'media_upload' => 0,
									'instructions' => 'Необязательный выделенный абзац перед списком.',
								)
							),
							a4_remont_acf_field(
								'news_single_content_block',
								'list_type',
								'Тип списка',
								'select',
								array(
									'choices'       => array(
										''   => 'Без списка',
										'ul' => 'Маркированный список',
										'ol' => 'Нумерованный список',
									),
									'default_value' => '',
									'ui'            => 1,
									'wrapper'       => array( 'width' => 40 ),
								)
							),
							a4_remont_acf_field(
								'news_single_content_block',
								'list_items',
								'Пункты списка',
								'repeater',
								array(
									'button_label' => 'Добавить пункт',
									'layout'       => 'table',
									'collapsed'    => a4_remont_acf_key( 'news_single_content_block', 'item_text' ),
									'conditional_logic' => array(
										array(
											array(
												'field'    => $block_list_type_key,
												'operator' => '!=empty',
											),
										),
									),
									'sub_fields'   => array(
										a4_remont_acf_field( 'news_single_content_block', 'item_text', 'Текст пункта', 'text' ),
									),
								)
							),
							a4_remont_acf_field(
								'news_single_content_block',
								'media_type',
								'Медиа',
								'select',
								array(
									'choices'       => array(
										''        => 'Без медиа',
										'image'   => 'Одно изображение',
										'gallery' => 'Галерея изображений',
									),
									'default_value' => '',
									'ui'            => 1,
									'wrapper'       => array( 'width' => 40 ),
								)
							),
							a4_remont_acf_field(
								'news_single_content_block',
								'image',
								'Изображение',
								'image',
								array(
									'return_format'     => 'array',
									'preview_size'      => 'medium',
									'conditional_logic' => array(
										array(
											array(
												'field'    => $block_media_type_key,
												'operator' => '==',
												'value'    => 'image',
											),
										),
									),
								)
							),
							a4_remont_acf_field(
								'news_single_content_block',
								'gallery',
								'Галерея',
								'gallery',
								array(
									'preview_size'      => 'medium',
									'instructions'      => 'Для вида как в статике обычно достаточно 2 изображений.',
									'conditional_logic' => array(
										array(
											array(
												'field'    => $block_media_type_key,
												'operator' => '==',
												'value'    => 'gallery',
											),
										),
									),
								)
							),
							a4_remont_acf_field( 'news_single_content_block', 'caption', 'Подпись под изображением', 'textarea', array( 'rows' => 3 ) ),
							a4_remont_acf_field(
								'news_single_content_block',
								'after_media_content',
								'Текст после медиа',
								'wysiwyg',
								array(
									'tabs'         => 'visual',
									'toolbar'      => 'basic',
									'media_upload' => 0,
									'instructions' => 'Используйте для дополнительного текста после изображения или галереи.',
								)
							),
						),
					)
				),
				a4_remont_acf_tab( 'news_single_content', 'fallback_tab', 'Резервный режим' ),
				a4_remont_acf_message(
					'news_single_content',
					'fallback_guide',
					'Как работает резервный режим',
					'Если блоки статьи не заполнены, тема попробует показать стандартное содержимое записи из редактора WordPress. Это удобно для редакторского workflow, когда не нужен покадровый ACF-конструктор статьи.'
				),
				a4_remont_acf_tab( 'news_single_content', 'settings_tab', 'Настройки' ),
				a4_remont_acf_field( 'news_single_content', 'section_id', 'HTML-якорь секции', 'text' ),
			)
		),
	);
}

/**
 * Sync single news field group into the ACF GUI.
 *
 * @return void
 */
function a4_remont_sync_news_single_field_group() {
	if ( ! function_exists( 'acf_import_field_group' ) ) {
		return;
	}

	$field_group = array(
		'key'                   => 'group_a4_remont_news_single_sections',
		'title'                 => 'Страница новости',
		'fields'                => array(
			a4_remont_acf_message(
				'news_single',
				'guide',
				'Как работать со страницей новости',
				'<strong>Рекомендуемый порядок заполнения:</strong><ol><li>Введите заголовок новости в стандартный заголовок записи.</li><li>Заполните краткое описание и миниатюру: они используются как fallback для первого экрана и карточек архива.</li><li>Ниже собирайте страницу через Flexible Content и меняйте порядок секций drag-and-drop.</li><li>Если секция "Контент статьи" не заполнена ACF-блоками, тема сможет использовать стандартное содержимое записи.</li></ol>'
			),
			array(
				'key'          => 'field_a4_remont_news_sections',
				'label'        => 'Секции страницы новости',
				'name'         => 'news_sections',
				'type'         => 'flexible_content',
				'instructions' => 'Добавляйте секции, раскрывайте вкладки и настраивайте контент. Порядок секций можно менять перетаскиванием.',
				'layouts'      => a4_remont_get_news_single_layouts(),
				'button_label' => 'Добавить секцию',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'news',
				),
			),
		),
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => array(
			'discussion',
			'comments',
			'custom_fields',
		),
		'active'                => true,
	);

	$group_id    = a4_remont_get_acf_field_group_post_id( $field_group['key'] );
	$schema_hash = md5( wp_json_encode( $field_group ) );
	$option_key  = 'a4_remont_news_single_acf_schema_hash';

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
add_action( 'acf/init', 'a4_remont_sync_news_single_field_group', 46 );

/**
 * Render flexible sections for a single news page.
 *
 * @return bool
 */
function a4_remont_render_news_single_sections() {
	return a4_remont_render_mapped_flexible_sections( 'news_sections', a4_remont_get_news_single_section_map() );
}

/**
 * Render static fallback sections for a single news page.
 *
 * @return bool
 */
function a4_remont_render_default_news_single_sections() {
	return a4_remont_render_mapped_default_sections( a4_remont_get_news_single_section_map() );
}

/**
 * Render an admin-only edit button for the current news post.
 *
 * @return void
 */
function a4_remont_render_news_single_edit_link() {
	if ( ! is_singular( 'news' ) || ! is_user_logged_in() || ! current_user_can( 'edit_post', get_the_ID() ) ) {
		return;
	}

	edit_post_link(
		'Редактировать новость',
		'<div class="a4-remont-editor-link _container">',
		'</div>',
		get_the_ID(),
		'btn btn--grey a4-remont-editor-link__button'
	);
}

/**
 * Render a single news page with flexible sections and fallback markup.
 *
 * @return void
 */
function a4_remont_render_news_single_content() {
	a4_remont_render_news_single_edit_link();

	$has_sections = a4_remont_render_news_single_sections();

	if ( ! $has_sections ) {
		$has_sections = a4_remont_render_default_news_single_sections();
	}

	if ( $has_sections ) {
		return;
	}
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( 'news-entry _container' ); ?>>
		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		</header>

		<div class="entry-content">
			<?php the_content(); ?>
		</div>
	</article>
	<?php
}
